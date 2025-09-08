<?php 

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\VoiceController;
use Illuminate\Http\Request;

class CheckAndInitiateCalls extends Command
{
    protected $signature = 'calls:check-and-initiate';
    protected $description = 'Check scheduled times and initiate calls for wellness checks';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $this->info('Checking for scheduled calls...');
        
        $voiceController = new VoiceController();
        $request = new Request();
        
        $response = $voiceController->checkAndInitiateCall($request);
        $responseData = $response->getData();
        
        $this->info($responseData->message);
        
        return 0;
    }
}