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
class VoiceController extends Controller
{
    public function __construct() {
        $this->account_sid = env('TWILIO_AUTH_SID');
        $this->auth_token = env('TWILIO_AUTH_TOKEN');
        $this->from = env('TWILIO_PHONE_NUMBER');
        $this->client = new Client($this->account_sid, $this->auth_token);
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
        $currentTime = Carbon::now('UTC')->format('H:i');
        $currentDay = strtolower(Carbon::now()->format('D'));
        $allUsers = User::where('role', 2)->get();

        foreach ($allUsers as $user) {
            $timeSlots = Time::where('user_id', $user->id)->where('day', $currentDay)->first();

            if ($timeSlots) {
                $times = [$timeSlots->time1, $timeSlots->time2, $timeSlots->time3];

                foreach ($times as $time) {
                    if ($time && $currentTime == $time) {
                        // Initiate call
                        $this->initiateCall($user);
                        return response()->json(['message' => 'Call initiated successfully.']);
                    }
                }
            }
        }

        return response()->json(['message' => 'Time not found.']);
    }

    public function initiateCall($user){
      try {
       $lovedone = Lovedone::where('user_id', $user->id)->where('service_status',1)->first();
       if (!$lovedone) {
            return response()->json(['message' => 'No loved one found for the user.']);
        }

        $lovedoneNumber = $lovedone->phone_no;
        $phone_number = $this->client->lookups->v1->phoneNumbers($lovedoneNumber)->fetch();

        if ($phone_number) {
            $voiceMessageUrl = url('/') . $user->record_voice;
            $statusCallbackUrl = url('/call-status');

            if ($voiceMessageUrl) {
                $call = $this->client->calls->create(
                    $lovedoneNumber,
                    $this->from,
                    [
                        "record" => true,
                        "url" => $voiceMessageUrl,
                        "statusCallback" => "https://developerdesk.tech/development/gocheckonme/call-status",
                        "statusCallbackEvent" => ["initiated", "ringing", "answered", "completed"],
                        "statusCallbackMethod" => "POST"
                    ]
                );

                if ($call) {
                    CallStatus::create([
                        'user_id' => $user->id,
                        'lovedone_number' => $lovedoneNumber,
                        'call_status' => 'initiated',
                        'call_sid' => $call->sid
                    ]);

                    echo 'Call initiated successfully';
                    // Schedule the job to update call status after 1 minute
                     UpdateCallStatus::dispatch($call->sid, $user)->delay(now()->addMinute());

                } else {
                    echo 'Call failed';
                }
            } else {
                \Log::error('No voice message found for user.');
            }
        }
    } catch (Exception $e) {
        Log::error('Error: ' . $e->getMessage());
    } catch (RestException $rest) {
        Log::error('Error: ' . $rest->getMessage());
    }
 }



    //  public function getCallHistory($callsid)
    // {
    //     $call = $this->client->calls($callsid)->fetch();
    //     dd($call->status);
   
    // }

}
