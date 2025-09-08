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
        $sid = env("TWILIO_AUTH_SID");
        $token = env("TWILIO_AUTH_TOKEN");
        $twilio = new Client($sid, $token);
        
        $callStatuses = CallStatus::whereNotIn("call_status", ["completed", "failed", "busy", "no-answer"])
            ->where("created_at", ">=", now()->subDays(1)) // Only check calls from last 24 hours
            ->get();
            
        $this->info("Found " . $callStatuses->count() . " call statuses to update.");
        
        foreach ($callStatuses as $callStatus) {
            try {
                $call = $twilio->calls($callStatus->call_sid)->fetch();
                
                $oldStatus = $callStatus->call_status;
                $newStatus = $call->status;
                
                if ($oldStatus !== $newStatus) {
                    $callStatus->call_status = $newStatus;
                    
                    // Update additional fields based on status
                    if ($newStatus === "completed") {
                        $callStatus->call_duration = $call->duration;
                        $callStatus->completed_at = now();
                    }
                    
                    $callStatus->save();
                    $this->info("Call status updated: SID {$callStatus->call_sid} from {$oldStatus} to {$newStatus}");
                } else {
                    $this->info("No change for SID {$callStatus->call_sid}: {$newStatus}");
                }
                
            } catch (\Exception $e) {
                $this->error("Failed to update call status for SID: " . $callStatus->call_sid . ". Error: " . $e->getMessage());
            }
        }
        
        $this->info("Call history fetch completed.");
    }
}
