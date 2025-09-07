<?php
namespace App\Jobs;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Log;
class SendEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $to;
    protected $subject;
    protected $body;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($to, $subject, $body)
    {
        $this->to = $to;
        $this->subject = $subject;
        $this->body = $body;
        Log::info("SendEmail job created with email: {$this->to}, subject: {$this->subject}");
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // dd('hh');
        $mail = new PHPMailer(true);
        try {
            Log::info("Attempting to send email to: {$this->to}");
            //Server settings
            $mail->SMTPDebug = 0;
            $mail->isSMTP();
            $mail->Host       = "smtp.gmail.com";
            $mail->SMTPAuth   = true;
            $mail->Username   = "raviappic@gmail.com";
            $mail->Password   = "ljkwtylzgtrgvbac";
            $mail->SMTPSecure = "tls";
            $mail->Port       = "587";

            //Recipients
            $mail->setFrom('raviappic@gmail.com', 'Gocheckonme');
            $mail->addAddress($this->to);

            // Content
            $mail->isHTML(true);
            $mail->Subject = $this->subject;
            $mail->Body    = $this->body;

            $mail->send();
            Log::info("Email sent to: {$this->to}");
        } catch (Exception $e) {
            \Log::error("Message could not be sent. Mailer Error: {$mail->ErrorInfo}");
        }
    }


}
