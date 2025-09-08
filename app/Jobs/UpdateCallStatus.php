<?php
namespace App\Jobs;

use Twilio\Rest\Client;
use App\Models\CallStatus;
use App\Jobs\SendEmail;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Log;
class UpdateCallStatus implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $callsid;
    protected $user;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($callsid, $user)
    {
        $this->callsid = $callsid;
        $this->user = $user;
                Log::info("UpdateCallStatus job created with callsid: {$this->callsid} for user: {$this->user->id}");
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            $client = new Client(env("TWILIO_AUTH_SID"), env("TWILIO_AUTH_TOKEN"));
            $call = $client->calls($this->callsid)->fetch();
            $status = $call->status;
            $duration = $call->duration;
            $answeredBy = $call->answeredBy; // This will be \'machine\', \'human\', or null

            Log::info("UpdateCallStatus job executing - CallSID: {$this->callsid}, Status: {$status}, AnsweredBy: {$answeredBy}");

            // Update call status in database
            $updateData = ["call_status" => $status];
            
            if ($duration) {
                $updateData["call_duration"] = $duration;
            }
            
            if ($answeredBy) {
                $updateData["answered_by"] = $answeredBy;
            }
            
            CallStatus::where("call_sid", $this->callsid)->update($updateData);

            // Send notification based on call outcome
            if ($status === "busy" || $status === "no-answer" || $status === "failed") {
                // Call was not answered at all
                $message = "Your loved one did not answer the wellness check call. Please follow up to ensure they are okay.";
                $this->sendNotifications($message);
                Log::info("Notifications sent for unanswered call - User: {$this->user->id}, Status: {$status}");
            } else if ($status === "completed") {
                if ($answeredBy === "machine") {
                    // Call went to voicemail - this is considered successful
                    $message = "Wellness check call completed - voicemail message left for your loved one.";
                    Log::info("Voicemail message left successfully - User: {$this->user->id}, Duration: {$duration}s");
                    // Optionally send success notification
                    // $this->sendNotifications($message);
                } else if ($answeredBy === "human") {
                    // Call was answered by human - best outcome
                    Log::info("Call answered by human successfully - User: {$this->user->id}, Duration: {$duration}s");
                } else {
                    // Call completed but we don\'t know who answered
                    Log::info("Call completed - User: {$this->user->id}, Duration: {$duration}s");
                }
            }
            
        } catch (\Exception $e) {
            Log::error("Error in UpdateCallStatus job - CallSID: {$this->callsid}, Error: " . $e->getMessage());
        }
    }
    
    /**
     * Send notifications to user\'s alternate contacts
     */
    private function sendNotifications($message)
    {
        // Send email notification if alternate email exists
        if ($this->user->alternate_email) {
            SendEmail::dispatch($this->user->alternate_email, "Wellness Check Alert", $message);
            Log::info("Email notification sent to: {$this->user->alternate_email}");
        }
        
        // Send SMS notification if alternate phone exists
        if ($this->user->alternate_phone_no) {
            SendTextMessage::dispatch($this->user->alternate_phone_no, $message);
            Log::info("SMS notification sent to: {$this->user->alternate_phone_no}");
        }
    }
}
