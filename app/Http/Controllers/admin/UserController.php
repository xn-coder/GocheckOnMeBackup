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
use DB;
// use Hash;
use Validator;
class UserController extends Controller
{
    public function user_list()
    {
         $users = User::where('role',2)->orderby('id','desc')->get();   
         return view('admin.users.users-list', compact('users'));
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