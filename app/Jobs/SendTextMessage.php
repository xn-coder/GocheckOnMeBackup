<?php
namespace App\Jobs;

use Twilio\Rest\Client;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class SendTextMessage implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $phoneNumber;
    protected $message;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($phoneNumber, $message)
    {
        $this->phoneNumber = $phoneNumber;
        $this->message = $message;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $client = new Client(env('TWILIO_AUTH_SID'), env('TWILIO_AUTH_TOKEN'));
        $client->messages->create(
            $this->phoneNumber,
            [
                'from' => env('TWILIO_PHONE_NUMBER'),
                'body' => $this->message
            ]
        );
    }
}
