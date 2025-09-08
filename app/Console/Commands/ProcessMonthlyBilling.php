<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Subscription;
use App\Models\Payment;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class ProcessMonthlyBilling extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = "billing:monthly";

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Process monthly billing for all active subscribers";

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info("Starting monthly billing process...");
        
        // Get all active users who need to be billed today
        $today = Carbon::today();
        $usersToBill = User::where("role", 2)
                           ->where("status", 1)
                           ->where("payment_processing_enabled", 1)
                           ->whereDay("created_at", $today->day)
                           ->with(["lovedones"])
                           ->get();
        
        $this->info("Found {$usersTobill->count()} users to bill today");
        
        foreach ($usersToProcess as $user) {
            $this->processUserBilling($user);
        }
        
        $this->info("Monthly billing process completed");
        return Command::SUCCESS;
    }
    
    private function processUserBilling($user)
    {
        try {
            // Check if user has active service
            $lovedone = $user->lovedones->first();
            if (!$lovedone || $lovedone->service_status != 1) {
                $this->warn("Skipping billing for user {$user->id} - service not active");
                return;
            }
            
            // Check if already billed this month
            $currentMonth = Carbon::now()->format("Y-m");
            $existingPayment = Payment::where("user_id", $user->id)
                                     ->where("payment_date", "like", $currentMonth . "%")
                                     ->first();
            
            if ($existingPayment) {
                $this->warn("User {$user->id} already billed for {$currentMonth}");
                return;
            }
            
            // Process the payment
            $amount = 7.47; // Monthly fee
            $paymentResult = $this->processPayment($user, $amount);
            
            if ($paymentResult["success"]) {
                // Record the payment
                Payment::create([
                    "user_id" => $user->id,
                    "amount" => $amount,
                    "payment_date" => Carbon::now(),
                    "payment_method" => "automatic_billing",
                    "transaction_id" => $paymentResult["transaction_id"],
                    "status" => "completed"
                ]);
                
                // Send email notification
                $this->sendBillingNotification($user, $amount, $paymentResult["transaction_id"]);
                
                $this->info("Successfully billed user {$user->id} - {$user->email}");
                
            } else {
                $this->error("Failed to bill user {$user->id}: " . $paymentResult["error"]);
                Log::error("Monthly billing failed for user {$user->id}: " . $paymentResult["error"]);
            }
            
        } catch (\Exception $e) {
            $this->error("Error processing billing for user {$user->id}: " . $e->getMessage());
            Log::error("Monthly billing error for user {$user->id}: " . $e->getMessage());
        }
    }
    
    private function processPayment($user, $amount)
    {
        // This is where you would integrate with your payment processor
        // For now, we"ll simulate a successful payment
        
        // TODO: Integrate with actual payment processor (Stripe/PayPal)
        // For demonstration, we"ll return a mock successful response
        
        return [
            "success" => true,
            "transaction_id" => "TXN_" . time() . "_" . $user->id,
            "amount" => $amount
        ];
    }
    
    private function sendBillingNotification($user, $amount, $transactionId)
    {
        $subject = "Monthly Billing Notification - Go Check On Me";
        $currentDate = Carbon::now()->format("F j, Y");
        
        $emailContent = '
        <html>
        <body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">
            <div style="max-width: 600px; margin: 0 auto; padding: 20px;">
                <img src="" . url("front-assets/gocheck.gif") . "" alt="Go Check On Me" style="display: block; margin: 0 auto 20px;">
                
                <h2 style="color: #800000; text-align: center;">Monthly Billing Notification</h2>
                
                <p>Dear {$user->name},</p>
                
                <p>Your monthly wellness check service has been automatically renewed for another month.</p>
                
                <div style="background-color: #f8f9fa; padding: 15px; border-radius: 5px; margin: 20px 0;">
                    <h3 style="margin-top: 0; color: #800000;">Billing Details:</h3>
                    <p><strong>Service:</strong> Wellness Check Calls (Up to 3 calls per day)</p>
                    <p><strong>Billing Period:</strong> " . Carbon::now()->format("F Y") . "</p>
                    <p><strong>Amount Charged:</strong> $" . number_format($amount, 2) . "</p>
                    <p><strong>Transaction ID:</strong> {$transactionId}</p>
                    <p><strong>Date Processed:</strong> {$currentDate}</p>
                </div>
                
                <p>Your wellness check service will continue as scheduled. If you need to make any changes to your call times or settings, please log into your account.</p>
                
                <p style="margin-top: 30px;">
                    <a href="" . url("/user-login") . "" style="background-color: #800000; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;">
                        Access Your Account
                    </a>
                </p>
                
                <hr style="margin: 30px 0;">
                
                <p style="font-size: 12px; color: #666;">
                    If you have any questions about this charge or need to cancel your service, please contact us immediately.
                    <br><br>
                    Thank you for trusting Go Check On Me with your wellness check needs.
                </p>
            </div>
        </body>
        </html>';
        
        try {
            // Send email using Laravel"s mail system
            Mail::html($emailContent, function ($message) use ($user, $subject) {
                $message->to($user->email, $user->name)
                       ->subject($subject)
                       ->from(env("MAIL_FROM_ADDRESS", "noreply@gocheckonme.com"), "Go Check On Me");
            });
            
            Log::info("Billing notification sent to user {$user->id} - {$user->email}");
            
        } catch (\Exception $e) {
            Log::error("Failed to send billing notification to user {$user->id}: " . $e->getMessage());
        }
    }
}