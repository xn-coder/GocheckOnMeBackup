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

            Log::info("UpdateCallStatus job executing - CallSID: {$this->callsid}, Status: {$status}");

            // Update call status in database
            $updateData = ["call_status" => $status];
            
            if ($duration) {
                $updateData["call_duration"] = $duration;
            }
            
            CallStatus::where("call_sid", $this->callsid)->update($updateData);

            // Send notification if call was not answered
            if ($status === "busy" || $status === "no-answer" || $status === "failed") {
                $message = "Your loved one did not answer the wellness check call. Please follow up to ensure they are okay.";
                
                // Send email notification if alternate email exists
                if ($this->user->alternate_email) {
                    SendEmail::dispatch($this->user->alternate_email, "Wellness Check - No Answer", $message);
                    Log::info("Email notification sent to: {$this->user->alternate_email}");
                }
                
                // Send SMS notification if alternate phone exists
                if ($this->user->alternate_phone_no) {
                    SendTextMessage::dispatch($this->user->alternate_phone_no, $message);
                    Log::info("SMS notification sent to: {$this->user->alternate_phone_no}");
                }
                
                Log::info("Notifications sent for unanswered call - User: {$this->user->id}, Status: {$status}");
            } else if ($status === "completed") {
                // Call was answered successfully
                Log::info("Call completed successfully - User: {$this->user->id}, Duration: {$duration}s");
            }
            
        } catch (\Exception $e) {
            Log::error("Error in UpdateCallStatus job - CallSID: {$this->callsid}, Error: " . $e->getMessage());
        }
    }
}
