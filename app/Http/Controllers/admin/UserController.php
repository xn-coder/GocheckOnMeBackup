<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Hash;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;
use Illuminate\Http\Request;
use App\Helpers\Helper;
use App\Models\User;
use App\Models\Lovedone;
use App\Models\Subscription;
use App\Models\CreditCard;
use App\Models\CallStatus;
use DB;
// use Hash;
use Validator;
class UserController extends Controller
{
    public function user_list()
    {
        $users = User::where("role",2)
        ->with(["lovedones", "times"])
        ->orderby("id","desc")
        ->get();   
         return view('admin.users.users-list', compact('users'));
    }

     /**
     * Enhanced client management view with cancellation and payment controls
     */
    public function client_management()
    {
        $clients = User::where("role", 2)
                      ->with(["lovedones", "times"])
                      ->orderby("id", "desc")
                      ->get();
        
        // Get additional client info for management
        foreach ($clients as $client) {
            $client->call_count = CallStatus::where("user_id", $client->id)->count();
            $client->last_call = CallStatus::where("user_id", $client->id)
                                         ->latest()
                                         ->first();
            // Add subscription info if available
            $client->subscription_status = $client->lovedones->first() ? 
                                         $client->lovedones->first()->service_status : 0;
        }
        
        return view("admin.client-management", compact("clients"));
    }

    /**
     * Cancel client and stop all services
     */
    public function cancel_client(Request $request)
    {
        $clientId = $request->client_id;
        $reason = $request->reason ?? "Admin cancellation";
        
        try {
            DB::beginTransaction();
            
            // Find the client
            $client = User::find($clientId);
            if (!$client) {
                return response()->json(["status" => false, "message" => "Client not found"]);
            }
            
            // Stop all call services
            Lovedone::where("user_id", $clientId)->update(["service_status" => 0]);
            
            // Mark client as cancelled
            $client->update([
                "status" => 0,
                "cancelled_at" => now(),
                "cancellation_reason" => $reason
            ]);
            
            // Log the cancellation
            \Log::info("Client {$clientId} ({$client->email}) cancelled by admin. Reason: {$reason}");
            
            DB::commit();
            
            return response()->json([
                "status" => true, 
                "message" => "Client {$client->name} has been cancelled successfully. All services stopped."
            ]);
            
        } catch (\Exception $e) {
            DB::rollback();
            \Log::error("Error cancelling client {$clientId}: " . $e->getMessage());
            return response()->json(["status" => false, "message" => "Error cancelling client"]);
        }
    }

    /**
     * Stop/Resume payment processing for a client
     */
    public function toggle_payment_processing(Request $request)
    {
        $clientId = $request->client_id;
        $action = $request->action; // "stop" or "resume"
        
        try {
            $client = User::find($clientId);
            if (!$client) {
                return response()->json(["status" => false, "message" => "Client not found"]);
            }
            
            // Update payment processing status (you might need to add this field to users table)
            $paymentStatus = ($action === "stop") ? 0 : 1;
            $client->update(["payment_processing_enabled" => $paymentStatus]);
            
            $message = ($action === "stop") ? 
                      "Payment processing stopped for {$client->name}" : 
                      "Payment processing resumed for {$client->name}";
            
            \Log::info("Payment processing {$action}ped for client {$clientId} ({$client->email})");
            
            return response()->json(["status" => true, "message" => $message]);
            
        } catch (\Exception $e) {
            \Log::error("Error toggling payment for client {$clientId}: " . $e->getMessage());
            return response()->json(["status" => false, "message" => "Error updating payment status"]);
        }
    }

    /**
     * Get detailed client information for refunds
     */
    public function get_client_details($id)
    {
        $client = User::with(["lovedones", "times"])->find($id);
        
        if (!$client) {
            return response()->json(["error" => "Client not found"], 404);
        }
        
        $clientDetails = [
            "id" => $client->id,
            "name" => $client->name,
            "email" => $client->email,
            "phone" => $client->phone,
            "cell_phone_no" => $client->cell_phone_no,
            "alternate_email" => $client->alernate_email,
            "alternate_phone_no" => $client->alternate_phone_no,
            "created_at" => $client->created_at,
            "status" => $client->status,
            "loved_ones" => $client->lovedones,
            "call_schedules" => $client->times,
            "total_calls" => CallStatus::where("user_id", $id)->count(),
            "recent_calls" => CallStatus::where("user_id", $id)
                                      ->latest()
                                      ->take(10)
                                      ->get(),
            "service_status" => $client->lovedones->first() ? 
                               $client->lovedones->first()->service_status : 0
        ];
        
        return response()->json($clientDetails);
    }

    
    public function create_user()
    {
        return view('admin.users.user-create');
    }

    public function add_user(Request $request){
        $user = new User();
        $user->name = $request->name;
        // $user->phone = $request->phone;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->status = 1;
        $user->role = 2;
        $message = 'Your login credentials '.$request->email.''.$request->password.'';
        $subject = 'Login credentials';
        Helper::sendMail($request->email, $subject, $message);
        $user->save();
        return response()->json(['status'=>true,'message'=>'User create successfully']);
    }

    public function user_view($id){
        $user_view = User::find($id);
        return view('admin.users.user_view',compact('user_view'));
    }
    
    public function edit_user($id){
      $edit_user = User::find($id);
      return view('admin.users.user-edit',compact('edit_user'));

    }

    public function update_user(Request $request){
        $update_user = User::find($request->user_id);
        $update_user->name = $request->name;
        $update_user->phone = $request->phone;
        $update_user->cell_phone_no = $request->cell_phone_no;
        $update_user->alternate_phone_no = $request->alternate_phone_no;
        $update_user->email = $request->email;
        $update_user->alernate_email = $request->alernate_email;
        $update_user->password = Hash::make($request->password);
        $message = 'Your login credentials '.$request->email.''.$request->password.'';
        $subject = 'Login credentials';
        Helper::sendMail($request->email, $subject, $message);
        $update_user->save();
        return response()->json(['status'=>true,'message'=>'User updated successfully']);
    }
    public function user_Status(Request $request)
    {
        $id = $request->id;
        $status = $request->status;
        $record = User::find($id);
        if ($record->status == 0) {
            $record->status = 1;
        } else {
            $record->status = 0;
        }

        $record->save();
        return response()->json(['message' => 'Status updated successfully']);
    }
    public function delete_user(Request $request, $id)
    {
        User::where('id', $id)->delete();
        // return redirect()->route('user_list');
        return response()->json(['status'=>true,'message' => 'User deleted successfully']);
        

    }
    
}