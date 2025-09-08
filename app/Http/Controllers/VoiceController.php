<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Twilio\Exceptions\RestException;
use Twilio\Rest\Client;
use Illuminate\Support\Facades\Auth;
use Twilio\TwiML\VoiceResponse;
use Illuminate\Support\Facades\Log;
use App\Models\Lovedone;
use App\Models\Time;
use App\Models\User;
use App\Models\CallStatus;
use Carbon\Carbon;
use Illuminate\Support\Facades\Artisan;
use App\Jobs\UpdateCallStatus;
use App\Jobs\SendTextMessage;
use Twilio\Http\CurlClient;
use Exception;

class VoiceController extends Controller
{
    public function __construct() {
        $this->account_sid = env('TWILIO_AUTH_SID');
        $this->auth_token = env('TWILIO_AUTH_TOKEN');
        $this->from = env('TWILIO_PHONE_NUMBER');
        if (!$this->account_sid || !$this->auth_token) {
            throw new \Exception("Twilio credentials are missing in .env");
        }
        $httpClient = new CurlClient([
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => 0
        ]);
        
        // Initialize Twilio client with the custom HTTP client
        $this->client = new Client(
            $this->account_sid, 
            $this->auth_token,
            null,
            null,
            $httpClient
        );
    }

    public function showUploadForm() {
        return view('front.record_voice');
    }

    public function uploadVoice(Request $request) {
        $request->validate([
            'voice_message' => 'required|mimes:mp3,wav|max:10240',
        ]);

        $voice_url = "";
        if ($request->hasfile('voice_message')) { 
            $file = $request->file('voice_message');
            $fileimage = time() . "." . $file->getClientOriginalExtension();
            $destination = public_path("voice");
            $file->move($destination, $fileimage);
            $voice_url = '/voice' . '/' . $fileimage;
        }

        $user = auth()->user();
        $user->record_voice = $voice_url;
        $user->save();

        return redirect()->back()->with('success', 'Voice message uploaded successfully.');
    }


    public function checkAndInitiateCall(Request $request)
    {
        $allUsers = User::where("role", 2)->get();
        $callsInitiated = 0;

        foreach ($allUsers as $user) {
            // Get loved one"s timezone or use UTC as fallback
            $lovedone = Lovedone::where("user_id", $user->id)->where("service_status", 1)->first();
            
            if (!$lovedone) {
                continue; // Skip if no active loved one
            }
            
            $timezone = $lovedone->timezone ?: "UTC";
            
            // Get current time in the loved one"s timezone
            $currentTime = Carbon::now($timezone);
            $currentTimeFormatted = $currentTime->format("H:i");
            $currentDay = strtolower($currentTime->format("D"));
            
            Log::info("Checking calls for user {$user->id} - Current time: {$currentTimeFormatted} ({$timezone}), Day: {$currentDay}");
            
            $timeSlots = Time::where("user_id", $user->id)->where("day", $currentDay)->first();

            if ($timeSlots) {
                $times = [
                    $timeSlots->time1,
                    $timeSlots->time2, 
                    $timeSlots->time3
                ];

                foreach ($times as $scheduledTime) {
                    if ($scheduledTime) {

                          // Handle different time formats that might be stored
                          $scheduledInUserTz = null;
                            
                          // Try H:i format first
                          $carbonTime = Carbon::createFromFormat("H:i:s", $scheduledTime, "UTC");
                          if ($carbonTime !== false) {
                              $scheduledInUserTz = $carbonTime->setTimezone($timezone)->format("H:i");
                          } else {
                              // Try H:i:s format
                              $carbonTime = Carbon::createFromFormat("H:i:s", $scheduledTime, "UTC");
                              if ($carbonTime !== false) {
                                  $scheduledInUserTz = $carbonTime->setTimezone($timezone)->format("H:i");
                              }
                          }
                          
                          if ($scheduledInUserTz === null) {
                              Log::error("Unable to parse scheduled time format: {$scheduledTime} for user {$user->id}");
                              continue;
                          }

                        
                        Log::info("Comparing: Current={$currentTimeFormatted}, Scheduled={$scheduledInUserTz} (UTC: {$scheduledTime})");
                        
                        // Allow 1-minute window for matching (current time or 1 minute past)
                        $currentMinute = $currentTime->format("H:i");
                        $previousMinute = $currentTime->copy()->subMinute()->format("H:i");
                        
                        if ($scheduledInUserTz === $currentMinute || $scheduledInUserTz === $previousMinute) {
                            // Check if call was already initiated in the last 10 minutes to prevent duplicates
                            $recentCall = CallStatus::where("user_id", $user->id)
                                ->where("created_at", ">=", Carbon::now()->subMinutes(10))
                                ->where("call_status", "initiated")
                                ->first();
                            
                            if (!$recentCall) {
                                Log::info("Initiating call for user {$user->id} at scheduled time {$scheduledInUserTz}");
                                $this->initiateCall($user);
                                $callsInitiated++;
                            } else {
                                Log::info("Skipping duplicate call for user {$user->id} - recent call found");
                            }
                        }
                    }
                }
            }
        }

        if ($callsInitiated > 0) {
            return response()->json(["message" => "Initiated {$callsInitiated} calls successfully."]);
        }
        
        return response()->json(["message" => "No calls to initiate at this time."]);
    }


/**
 * Generate TwiML response for playing voice message
 */
public function twimlResponse(Request $request)
{
    $userId = $request->get("user_id");
    $user = User::find($userId);
    
    $twiml = new VoiceResponse();
    
    if ($user && $user->record_voice) {
        $voiceMessageUrl = url("/") . $user->record_voice;
        
        // Play the recorded voice message
        $twiml->play($voiceMessageUrl);
        
        // Optionally, you can add a pause and then hangup
        $twiml->pause(["length" => 1]);
        $twiml->hangup();
    } else {
        // Fallback message if no recording found
        $twiml->say("Hello, this is a wellness check call. Please call back if you need assistance.");
        $twiml->hangup();
    }
    
    return response($twiml)->header("Content-Type", "text/xml");
}

/**
 * Handle Twilio call status callbacks
 */
public function handleCallback(Request $request)
{
    $callSid = $request->input("CallSid");
    $callStatus = $request->input("CallStatus");
    $duration = $request->input("CallDuration");
    $recordingUrl = $request->input("RecordingUrl");
    
    Log::info("Call status update - SID: {$callSid}, Status: {$callStatus}");
    
    if ($callSid && $callStatus) {
        $updateData = ["call_status" => $callStatus];
        
        // Add timestamps based on status
        switch ($callStatus) {
            case "ringing":
                // Call is ringing
                break;
            case "in-progress":
            case "answered":
                $updateData["answered_at"] = Carbon::now();
                break;
            case "completed":
                $updateData["completed_at"] = Carbon::now();
                if ($duration) {
                    $updateData["call_duration"] = $duration;
                }
                if ($recordingUrl) {
                    $updateData["recording_url"] = $recordingUrl;
                }
                break;
        }
        
        CallStatus::where("call_sid", $callSid)->update($updateData);
    }
    
    return response("OK", 200);
}

/**
 * Get call history for a user
 */
public function getCallHistory(Request $request)
{
    $userId = $request->get("user_id");
    
    if (!$userId) {
        return response()->json(["error" => "User ID required"], 400);
    }
    
    $callHistory = CallStatus::where("user_id", $userId)
        ->orderBy("created_at", "desc")
        ->paginate(20);
        
    return response()->json($callHistory);
}


//  public function getCallHistory($callsid)
// {
//     $call = $this->client->calls($callsid)->fetch();
//     dd($call->status);

// }


    public function initiateCall($user){
        try {
            $lovedone = Lovedone::where("user_id", $user->id)->where("service_status",1)->first();
            if (!$lovedone) {
                Log::error("No loved one found for user ID: {$user->id}");
                return response()->json(["message" => "No loved one found for the user."]);
            }

            $lovedoneNumber = $lovedone->phone_no;
            
            // Validate phone number format
            if (!$lovedoneNumber || strlen($lovedoneNumber) < 10) {
                Log::error("Invalid phone number for user ID: {$user->id}");
                return response()->json(["message" => "Invalid loved one phone number."]);
            }

            // Verify phone number exists and is valid
            $phone_number = $this->client->lookups->v1->phoneNumbers($lovedoneNumber)->fetch();

            if ($phone_number) {
                // Create TwiML URL for playing voice message
                $twimlUrl = url("/twiml") . "?user_id=" . $user->id;
                $statusCallbackUrl = url("/call-status");

                if ($user->record_voice) {
                    $call = $this->client->calls->create(
                        $lovedoneNumber,
                        $this->from,
                        [
                            "record" => true,
                            "url" => $twimlUrl,
                            "statusCallback" => $statusCallbackUrl,
                            "statusCallbackEvent" => ["initiated", "ringing", "answered", "completed"],
                            "statusCallbackMethod" => "POST",
                            "timeout" => 30, // Ring for 30 seconds before considering no-answer
                            "machineDetection" => "Enable" // Detect if voicemail picks up
                        ]
                    );

                    if ($call) {
                        CallStatus::create([
                            "user_id" => $user->id,
                            "lovedone_number" => $lovedoneNumber,
                            "call_status" => "initiated",
                            "call_sid" => $call->sid,
                            "initiated_at" => Carbon::now()
                        ]);

                        Log::info("Call initiated successfully for user {$user->id} with SID: {$call->sid}");
                        
                        // Schedule the job to update call status after 2 minutes
                        UpdateCallStatus::dispatch($call->sid, $user)->delay(now()->addMinutes(2));

                        return response()->json(["message" => "Call initiated successfully", "call_sid" => $call->sid]);
                    } else {
                        Log::error("Failed to create call for user ID: {$user->id}");
                        return response()->json(["message" => "Call failed to initiate"]);
                    }
                } else {
                    Log::error("No voice message recorded for user ID: {$user->id}");
                    return response()->json(["message" => "No voice message found for user."]);
                }
            }
        } catch (Exception $e) {
            Log::error("General error initiating call for user {$user->id}: " . $e->getMessage());
            return response()->json(["message" => "Error initiating call: " . $e->getMessage()]);
        } catch (RestException $rest) {
            Log::error("Twilio error initiating call for user {$user->id}: " . $rest->getMessage());
            return response()->json(["message" => "Twilio error: " . $rest->getMessage()]);
        }
    }



    //  public function getCallHistory($callsid)
    // {
    //     $call = $this->client->calls($callsid)->fetch();
    //     dd($call->status);
   
    // }

}
