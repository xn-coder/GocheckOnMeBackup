<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
use Illuminate\Support\Facades\Hash;
use DB; 
use Session;
use Validator;
use DateTime;
use App\Models\Verification;
use App\Models\User;
use App\Models\Password_otp;
use App\Helpers\Helper;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Response;
use SessionHandler;

class Admin extends Controller
{
    public function index()
    {
        return view('admin.login');
    }  
      
    public function privacy_policy()
    {
        return view('Pages.Privacy');
    }    

    public function forget_password()
    {
        return view("admin.forgetpsdadmin");
    }
    public function adminforgetpasswordview($id)
      {
        return view("admin.adminforgetpasswordview",compact('id'));
      }

      public function showRegisterForm()
{
    return view('admin.register');
}

public function register(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:users',
        'password' => 'required|string|min:6|confirmed',
    ]);

    $user = new User();
    $user->name = $request->name;
    $user->email = $request->email;
    $user->password = Hash::make($request->password);
    $user->role = 1; // ✅ Mark as admin
    $user->status = 1;
    $user->save();

    return redirect()->route('login')->with('success', 'Admin registered successfully!');
}


    public function customLogin(Request $request)
    {
        $this->validate($request, [
            'email' => 'required',
            'password' => 'required',
        ]);
    
        $remember_me = $request->has('remember') ? true : false; 
        
        if (auth()->attempt(['email' => $request->input('email'), 'password' => $request->input('password')], $remember_me))
        {
            $user = auth()->user();
        
            $request->session()->put('id',$user->id);
            $request->session()->put('name',$user->name);
            $request->session()->put('email',$user->email);
            $request->session()->put('user_id',$user->id);
            $request->session()->put('role',$user->role);
            $request->session()->put('use_image',$user->use_image);
            $request->session()->put('phone',$user->phone);
            $request->session()->put('image',$user->image);
            $user = Auth::getProvider()->retrieveByCredentials(['email' => $request->input('email'), 'password' => $request->input('password')]);

            
            if($remember_me==true)
            {
                $minutes = 14400;
                $response = new Response();
                $cooky=(cookie('remember_me', $user->remember_token, $minutes));
                return redirect()->to('/admin/dashboard') ->withSuccess('Signed in')->withCookie($cooky);
            }else{
                $minutes = 0;
                return redirect()->to('/admin/dashboard') ->withSuccess('Signed in')->withCookie(cookie('remember_me','', $minutes));
            }
          
        }else{
            return redirect('admin/login')->with('msg', 'Please enter valid login credentials.');  
        }
    
    }

  


    public function dashboard()
    {
            $total_user = User::where('role',2)->count();
        return view('admin.dashboard',compact('total_user'));
    }

    public function user_delete(Request $request){
        $user_delete = User::where('id',$request->id)->delete();
        return redirect('user_list');
    }
    
    public function user_view(Request $request){
        $user_view = User::where('id',$request->id)->first();
        return view('Pages.user_view',compact('user_view'));
    }
    public function user_status(Request $request){
     $id = $request->id;
     $status = $request->status;
     $data = ['status'=>$status];
     $update = User::where('id',$request->id)->update($data);
     if($update){
      $result = array('status'=>true, 'message'=>'status upated');
     }else{
       $result = array('status'=>false, 'message'=>'Something went please try again');
    }
        echo json_encode($result);
    }

    public function signOut() {
        Session()->flush();
        Session()->forget('role');
        Session()->forget('id');
        Auth::logout();
        return Redirect('admin/login'); //redirect back to admin
    } 
    
  public function admin_check_password(Request $request ){
    if(!empty($request->input())){
            date_default_timezone_set('Asia/Kolkata');
            $date = date("Y-m-d h:i:s", time());
            $email = $request->input('email');
            $request->session()->put('email',$email);
            $checkemail = User::where('email',$email)->first();
            if(!empty($checkemail))
            {
                $subject="Forgot password";
                $redriecturl = url('admin/adminforgetpasswordview')."/".$checkemail->id;

               $message ="<a href='".$redriecturl."'>click</a>";
                
                if(Helper::sendMail($email, $message))
                {
                    $result = array('status'=> true, 'message'=>'Forget Password send Link On Your Email Address'); 
                }
                else
                {
                    $result = array('status'=> false, 'message'=>'Invalid Email Address');
                }
            }
            else
            {
                $result = array("status"=> false, "message"=>"Invalid Email Address.");
            }
        }
        echo json_encode($result);

    }

    public function verify_adminforgetpassword(Request $request){
        if($request->input()){
            $id = $request->input('id');
            $password =  Hash::make($request->password);
        
            $update = User::where('id',$id)->update(array('password'=>$password));
            if($update)
            {
                $result=array('status'=>true,'message'=>'Password Reset Successfully.','url'=>"login");
            }
            else
            {
                $result=array('status'=>false,'message'=>'Password Reset Failed.');
            }
            echo json_encode($result);
        }

        
      }

       public function update_admin_profile(Request $request){
             if(!empty($request->input())){
                   $image_url=url('/images/userimage.png');
                $id = $request->id;
                $usreData = User::where('id',$id)->first();
                $users =  new User();
                 $fileimage="";
                   $image_url='';
                   if($request->hasfile('image'))
                  {
                    $url="/";
                    $url .="images/";
                    $file_image=$request->file('image');
                    $fileimage=md5(date("Y-m-d h:i:s", time())).".".$file_image->getClientOriginalExtension();
                    $destination=public_path("images");
                    $file_image->move($destination,$fileimage);
                    $url.=$fileimage;
                    $image_url=url($url);
               
            }
                  else
                  {
                    $image_url= $usreData->image;
                  }
                $user =   $users->where("id",$id)->first();
                $data['name'] = isset($request->name)? $request->name: $user->name;
                $data['image']=$image_url;
   
                $update = User::where('id', $id)->update($data);
                if($update)
                {
                    $result = array("status"=> true, "message"=>"Profile Update Successfully");
                }
                else
                {
                    $result = array("status"=> true, "message"=>"Profile Update Fail");
                }
            }
        echo json_encode($result);
    } 

    public function user_change_password(Request $request)
    {
        if(!empty($request->input()))
        {
            $old_password = $request->old_password;
            $new_password = $request->new_password;
            $id = $request->id;
             $users =  new User();
            
            $user =   $users->where("id",$id)->first();
            if($old_password==$new_password)
            {
                $result = array("status"=> false, "message"=>"Old Password and New Password should not be same");
            }
            else
            {
                if (!$user) {
                    $result = array("status"=> false, "message"=>"invalid old password");
                    
                 }

                 if (!Hash::check($old_password, $user->password)) {
                    $result = array("status"=> false, "message"=>"invalid old password");
                 }
                 else{
                    
                //    $result = array("status"=> false, "message"=>"invalid old password");
                    $data['password'] = Hash::make($new_password);
                  
                    $update = $user->where('id',$id) ->update($data);
                    $result = array("status"=> true, "message"=>"change password Successfully");
               }  
            }
         echo json_encode($result);
        }
    }

    
}
