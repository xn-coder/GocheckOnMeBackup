<?php 
namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\CallStatus;
use Twilio\Rest\Client;

class FetchCallHistory extends Command
{
    protected $signature = 'fetch:call-history';
    protected $description = 'Fetch the call history and update the call status';

    public function __construct()
    {
        parent::__construct();
    }

  
    public function handle()
    {
        $sid =  env('TWILIO_AUTH_SID');
        $token = env('TWILIO_AUTH_TOKEN');
        $twilio = new Client($sid, $token);
        $callStatuses = CallStatus::where('call_status', '!=', 'completed')->get();
        // dd($callStatuses);
        foreach ($callStatuses as $callStatus) {
            try {
                $call = $call = $twilio->calls($callSid)->fetch();
                $callStatus->call_status = $call->status;
                $callStatus->save();
                $this->info('Call status updated successfully for SID: ' . $callStatus->call_sid);
            } catch (\Exception $e) {
                $this->error('Failed to update call status for SID: ' . $callStatus->call_sid . '. Error: ' . $e->getMessage());
            }
        }
    }
}
