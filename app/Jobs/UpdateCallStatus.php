<?php
namespace App\Jobs;

use Twilio\Rest\Client;
use App\Models\CallStatus;
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
        $client = new Client(env('TWILIO_AUTH_SID'), env('TWILIO_AUTH_TOKEN'));
        $call = $client->calls($this->callsid)->fetch();
        $status = $call->status;

        CallStatus::where('call_sid', $this->callsid)->update(['call_status' => $status]);

        if ($status === 'busy' || $status === 'no-answer') {
            
            $message = 'Your loved one did not answer the call.';
            // SendTextMessage::dispatch($this->user->alternate_phone_no, $message);
            SendEmail::dispatch($this->user->alernate_email, 'Call Status', $message);
        } else {
            // SendTextMessage::dispatch($call->to, 'Your call has been completed.');
        }
    }
}
