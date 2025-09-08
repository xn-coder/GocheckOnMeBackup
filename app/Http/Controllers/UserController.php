<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Http\Request;

use Session;
use App\Models\User;
use App\Models\Time;
use App\Models\Lovedone;
use App\Models\CreditCard;
use App\Helpers\Helper;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use SessionHandler;
use DateTime; 
use Carbon\Carbon;

class UserController extends Controller
{
    public function user_signup(){
    
      return view('front.user-signup');
    }

    public function usersignup(Request $request){

         $validate = Validator::make($request->all(), [
          'name' => 'required',
          'email' => 'required|unique:users,email',
          'password' => 'required',
          'confirm_password' => 'required',
      ]);
      
      if ($validate->fails()) {  
          $result = array('status' => false, 'message' => 'Validation failed', 'errors' => $validate->errors());
          }else{
            $user = new User();
            $user->name = $request->name;
            $user->email = $request->email;
            $user->password = Hash::make($request->password);
            $request->session()->put('user_info', $request->all());        
          }
  
          return response()->json($result, 200);
    }

    public function Login(){
      return view('front.user-login');
    }

    public function userLogin(Request $request)
    {

      $validate = Validator::make($request->all(), [
        'email' => 'required',
        'password' => 'required',
    ]);
    
    if ($validate->fails()) {  
        $result = array('status' => false, 'message' => 'Validation failed', 'errors' => $validate->errors());
        }else{
        $credentials = $request->only('email', 'password');
        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            $token = $user->createToken('AuthToken')->plainTextToken;
            return response()->json(['status'=>true,'message'=>'User login successfully','token' => $token], 200);
        }else{
          return response()->json(['status'=>false,'message' => 'email end password do not match'], );
        }
    }
  }

   public function LinkSend(Request $request){
     return view('front.user-email-send');
 }


   public function checkemail(Request $request){
     $user = User::where('email', $request->email)->first();
     if (!empty($user)) {
         $verificationLink = url('/forgot-password/' . $user->id);
         Helper::sendMail($user->email, $verificationLink);
         return response()->json(['status' => true, 'message' => 'Your Email password Forgot Link send  successful', 'email' => $user->email], 200);
     } else {
         return response()->json(['status' => false, 'message' => 'Email not found']);
     }
 }
 public function forgotPassword(Request $request,$id){
  $user = User::where('id',$id)->first();
  return view('front.forgot-password',compact('user'));
}
 public function passwordupdate(Request $request){
    $user = User::where('id', $request->emailcheck)->first();
    if (!empty($user)) {
        $user->update(['password' => Hash::make($request->new_password)]);
        return response()->json(['status' => true, 'message' => 'Password updated successfully'], 200);
    } else {
        return response()->json(['status' => false, 'message' => 'Email not found']);
    }
}

  public function logout(Request $request){
      Auth::logout();
       $result = array('status' => true,'message' => "logout successfully");
      return redirect('/');
  }

  public function instructions(){
    $user = auth::user();
    $userId = isset($user->id) ? $user->id:'';
    if(isset($userId)){
       $loved = Lovedone::where('user_id',$userId)->first();
       $times = Time::where('user_id',$userId)->get();
       if($times->isNotEmpty()){
          $timecheck = true;
       }else{
        $timecheck = false;
       }
       $timezone = isset($loved->timezone) ? $loved->timezone:'';
        
       $times->transform(function($time) use ($timezone) {
        $time->time1 = $this->convertToTimezone($time->time1, $timezone);
        $time->time2 = $this->convertToTimezone($time->time2, $timezone);
        $time->time3 = $this->convertToTimezone($time->time3, $timezone);
        return $time;
    });
       // echo "<pre>";print_r($timezone);die;
    }
  // dd($timecheck);
    return view('front.instructions',compact('user','loved','times','timecheck'));
  }

  private function convertToTimezone($time, $timezone)
{
    if (!$time) {
        return null;
    }
    $format = strlen($time) === 8 ? 'H:i:s' : 'H:i';
    return Carbon::createFromFormat($format, $time, 'UTC')
                 ->setTimezone($timezone)
                 ->format('h:i');
}


  public function fill_data_instructions(Request $request){
      // dd($request->all());
    $loginUser = Auth::user();
    $days = ['mon', 'tue', 'wed', 'thu', 'fri', 'sat', 'sun'];
    $timezone = $request->input('timezone');

    $user = User::find($loginUser->id);
    // dd('+'.$request->phone_country_code.$request->phone);
    $voice_url = "";
        if ($request->hasfile('voice_message')) { 
            $file = $request->file('voice_message');
            $fileimage = time() . "." . $file->getClientOriginalExtension();
            $destination = public_path("voice");
            $file->move($destination, $fileimage);
            $voice_url = '/voice' . '/' . $fileimage;
        }else{
          $voice_url = $user->record_voice;
        }
            // dd($voice_url);
    $user->update([
        'phone' => '+'.$request->phone_country_code.$request->phone,
        'cell_phone_no' => '+'.$request->cell_phone_country_code.$request->cell_phone_no,
        'record_voice' => $voice_url,
    ]);
    

    Lovedone::updateOrCreate(
        ['user_id' => $loginUser->id],
        ['phone_no' => '+'.$request->phone_no_country_code.$request->phone_no, 'timezone' => $request->timezone]
    );

    foreach ($days as $day) {
        $existingSchedule = Time::where('user_id', $loginUser->id)
                                ->where('day', $day)
                                ->first();

        if ($existingSchedule) {

            $existingSchedule->update([
                'time1' => $this->convertToUTC($request->input("{$day}_time1"), $request->input("{$day}_am_pm1"), $timezone),
                'time2' => $this->convertToUTC($request->input("{$day}_time2"), $request->input("{$day}_am_pm2"), $timezone),
                'time3' => $this->convertToUTC($request->input("{$day}_time3"), $request->input("{$day}_am_pm3"), $timezone),
                'am_pm1' => $request->input("{$day}_am_pm1"),
                'am_pm2' => $request->input("{$day}_am_pm2"),
                'am_pm3' => $request->input("{$day}_am_pm3"),
            ]);
        } else {

            $newSchedule = new Time();
            $newSchedule->day = $day;
            $newSchedule->user_id = $loginUser->id;
            $newSchedule->time1 = $this->convertToUTC($request->input("{$day}_time1"), $request->input("{$day}_am_pm1"), $timezone);
            $newSchedule->time2 = $this->convertToUTC($request->input("{$day}_time2"), $request->input("{$day}_am_pm2"), $timezone);
            $newSchedule->time3 = $this->convertToUTC($request->input("{$day}_time3"), $request->input("{$day}_am_pm3"), $timezone);
            $newSchedule->am_pm1 = $request->input("{$day}_am_pm1");
            $newSchedule->am_pm2 = $request->input("{$day}_am_pm2");
            $newSchedule->am_pm3 = $request->input("{$day}_am_pm3");

            if ($newSchedule->time1 || $newSchedule->time2 || $newSchedule->time3) {
                $newSchedule->save();
            }
        }
    }

    return response()->json(['status' => true, 'message' => 'Time schedule added successfully']);
}

private function convertToUTC($time, $amPm, $timezone)
{
    if (empty($time) || empty($amPm)) {
        return null;
    }

    $amPm = strtolower(trim($amPm));
    if ($amPm !== "am" && $amPm !== "pm") {
        return null;
    }

    // Clean the time string - remove any existing am/pm
    $time = trim($time);
    $time = str_replace([" am", " pm", "am", "pm"], "", $time);
    
    // Validate time format (should be H:i or H:i:s)
    if (!preg_match("/^\d{1,2}:\d{2}(:\d{2})?$/", $time)) {
        return null;
    }

    try {
        // Create datetime string in 12-hour format
        $dateTimeString = $time . " " . $amPm;
        
        // Use today"s date for conversion (since we only care about time)
        $today = date("Y-m-d");
        $fullDateTime = $today . " " . $dateTimeString;
        
        // Create DateTime object in the user"s timezone
        $dateTime = \DateTime::createFromFormat("Y-m-d g:i A", $fullDateTime, new \DateTimeZone($timezone));
        
        if ($dateTime === false) {
            // Try with seconds format
            $dateTime = \DateTime::createFromFormat("Y-m-d g:i:s A", $fullDateTime, new \DateTimeZone($timezone));
        }
        
        if ($dateTime === false) {
            return null;
        }
        
        // Convert to UTC
        $dateTime->setTimezone(new \DateTimeZone("UTC"));
        return $dateTime->format("H:i");
        
    } catch (\Exception $e) {
        \Log::error("Time conversion error: " . $e->getMessage() . " for time: $time $amPm in timezone: $timezone");
        return null;
    }
}

    public function notification_number_and_email(Request $request){
        $user_id = Auth::user()->id;
        $user = User::find($user_id);
        $user->alernate_email = $request->email_notification;
        $user->alternate_phone_no = $request->notification_number;
        $user->save();
        return response()->json(['status' => true, 'message' => 'Added successfully']);
    }

    public function send_call_and_stop(Request $request){
      // dd($request->all());
      $user  = Auth::user();
      $type = $request->type;
      if($type == 'send_call'){
         Lovedone::where('user_id',$user->id)->update(['service_status'=>1]);
          return response()->json(['status' => true, 'message' => 'Service stated successfully']);
      }else{
        Lovedone::where('user_id',$user->id)->update(['service_status'=>0]);
         return response()->json(['status' => true, 'message' => 'Service stop successfully']);
      }

    }
}
