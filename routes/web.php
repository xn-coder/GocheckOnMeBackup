<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\admin\Admin;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('front.index');
});
Route::get('/call', function () {
    return view('front.call');
});
Route::get('/test-time-conversion', function () {
    // Test the time conversion logic
    $userController = new App\Http\Controllers\UserController();
    
    // Test cases
    $testCases = [
        ['time' => '1:11', 'ampm' => 'am', 'timezone' => 'Asia/Kolkata'],
        ['time' => '1:12', 'ampm' => 'am', 'timezone' => 'Asia/Kolkata'],
        ['time' => '1:13', 'ampm' => 'am', 'timezone' => 'Asia/Kolkata'],
        ['time' => '12:00', 'ampm' => 'am', 'timezone' => 'Asia/Kolkata'],
        ['time' => '12:00', 'ampm' => 'pm', 'timezone' => 'Asia/Kolkata'],
    ];
    
    $results = [];
    foreach ($testCases as $test) {
        // Using reflection to access private method
        $reflection = new ReflectionClass($userController);
        $method = $reflection->getMethod('convertToUTC');
        $method->setAccessible(true);
        
        $utcTime = $method->invoke($userController, $test['time'], $test['ampm'], $test['timezone']);
        
        $results[] = [
            'input' => $test['time'] . ' ' . $test['ampm'] . ' (' . $test['timezone'] . ')',
            'output_utc' => $utcTime,
            'status' => $utcTime ? 'SUCCESS' : 'FAILED'
        ];
    }
    
    return response()->json($results, 200, [], JSON_PRETTY_PRINT);
});
Route::get('/test-call-check', function () {
    // Test the call checking logic without actually making calls
    $voiceController = new App\Http\Controllers\VoiceController();
    $request = new Illuminate\Http\Request();
    
    // Get the response
    $response = $voiceController->checkAndInitiateCall($request);
    $data = $response->getData();
    
    return response()->json([
        'current_time_utc' => now()->format('Y-m-d H:i:s T'),
        'message' => $data->message,
        'debug_info' => 'Check application logs for detailed scheduling information'
    ], 200, [], JSON_PRETTY_PRINT);
});
Route::get('/debug-times', function () {
    // Debug endpoint to see what times are stored in database
    $users = App\Models\User::where('role', 2)->with(['lovedones', 'times'])->get();
    
    $debug_data = [];
    foreach ($users as $user) {
        $lovedone = $user->lovedones->first();
        $times = $user->times;
        
        $user_debug = [
            'user_id' => $user->id,
            'user_name' => $user->name,
            'timezone' => $lovedone ? $lovedone->timezone : 'Not set',
            'scheduled_times' => []
        ];
        
        foreach ($times as $time) {
            $user_debug['scheduled_times'][] = [
                'day' => $time->day,
                'time1' => $time->time1,
                'time2' => $time->time2, 
                'time3' => $time->time3,
                'am_pm1' => $time->am_pm1,
                'am_pm2' => $time->am_pm2,
                'am_pm3' => $time->am_pm3
            ];
        }
        
        $debug_data[] = $user_debug;
    }
    
    return response()->json([
        'debug_message' => 'Current time schedules in database',
        'current_utc_time' => now()->format('Y-m-d H:i:s A'),
        'users_with_schedules' => $debug_data
    ], 200, [], JSON_PRETTY_PRINT);
});

Route::get('/practice', function () {
    return view('front.practice');
});
// Route::get('/upload-voice', [VoiceController::class, 'showUploadForm']);
// Route::view('/', 'call');
Route::post('/call', 'App\Http\Controllers\VoiceController@initiateCall')->name('initiate_call');
Route::get('/upload-voice', 'App\Http\Controllers\VoiceController@showUploadForm')->name('showUploadForm');
Route::post('/upload_voice', 'App\Http\Controllers\VoiceController@uploadVoice')->name('uploadVoice');
Route::get('/twiml', 'App\Http\Controllers\VoiceController@twimlResponse')->name('twiml');
Route::get('/checkAndInitiateCall', 'App\Http\Controllers\VoiceController@checkAndInitiateCall')->name('checkAndInitiateCall');
Route::post('/call-status', 'App\Http\Controllers\VoiceController@handleCallback')->name('call_status_callback');
Route::get('/getCallHistory', 'App\Http\Controllers\VoiceController@getCallHistory')->name('getCallHistory');
// Route::post('/voice/message', 'TwilioController@handleVoiceMessage')->name('twilio.voice.message');



Route::get('/competitors', function () {return view('front.competitors');});
Route::get('/contactus', function () {return view('front.contactus');});
Route::get('/howitworks', function () {return view('front.howitworks');});
// Route::get('/instructions', function () {return view('front.instructions');});
Route::get('/refund', function () {return view('front.refund');});
Route::get('/termsofservice', function () {return view('front.termsofservice');});

Route::get('/user-signup', [App\Http\Controllers\UserController::class, 'user_signup']);
Route::post('/signup', [App\Http\Controllers\UserController::class, 'usersignup']);
Route::get('/user-login', [App\Http\Controllers\UserController::class, 'Login']);
Route::post('/user', [App\Http\Controllers\UserController::class, 'userLogin']);
Route::get('/user-send-link', [App\Http\Controllers\UserController::class, 'LinkSend']);
Route::get('/forgot-password/{id}', [App\Http\Controllers\UserController::class, 'forgotPassword']);
Route::post('/user-check', [App\Http\Controllers\UserController::class, 'checkemail']);
Route::post('/password-update', [App\Http\Controllers\UserController::class, 'passwordupdate']);
Route::get('/logout', [App\Http\Controllers\UserController::class, 'logout']);
Route::get('/instructions', [App\Http\Controllers\UserController::class, 'instructions']);
Route::post('/fill_data_instructions', [App\Http\Controllers\UserController::class, 'fill_data_instructions']);
Route::post('/notification_number_and_email', [App\Http\Controllers\UserController::class, 'notification_number_and_email']);
Route::post('/send_call_and_stop', [App\Http\Controllers\UserController::class, 'send_call_and_stop']);
//paypal


Route::any('process-transaction', [\App\Http\Controllers\PaypalController::class, 'processTransaction'])->name('processTransaction');
Route::get('success-transaction', [\App\Http\Controllers\PaypalController::class, 'successTransaction'])->name('successTransaction');
Route::get('cancel-transaction', [\App\Http\Controllers\PaypalController::class, 'cancelTransaction'])->name('cancelTransaction');
Route::get('success', [\App\Http\Controllers\PaypalController::class, 'success'])->name('success');
Route::get('failed', [\App\Http\Controllers\PaypalController::class, 'failed'])->name('failed');
///admin route
Route::prefix('admin')->group(function () {
    Route::get('/login', [App\Http\Controllers\admin\Admin::class, 'index'])->name('login');
    Route::get('/forget_password', [App\Http\Controllers\admin\Admin::class, 'forget_password'])->name('forget_password');
    Route::post('custom-login', [App\Http\Controllers\admin\Admin::class, 'customLogin'])->name('login.custom'); 
    Route::post('admin_check_password', [App\Http\Controllers\admin\Admin::class, 'admin_check_password'])->name('admin_check_password');
    Route::get('adminforgetpasswordview/{id}', [App\Http\Controllers\admin\Admin::class, 'adminforgetpasswordview'])->name('adminforgetpasswordview');
    Route::post('verify_adminforgetpassword', [App\Http\Controllers\admin\Admin::class, 'verify_adminforgetpassword'])->name('verify_adminforgetpassword');
    Route::get('/dashboard', [App\Http\Controllers\admin\Admin::class, 'dashboard'])->middleware('rememberme'); 
    Route::get('signout', [App\Http\Controllers\admin\Admin::class, 'signOut'])->name('signout')->middleware('rememberme');
    Route::post('update_admin_profile', [App\Http\Controllers\admin\Admin::class, 'update_admin_profile'])->name('update_admin_profile');
    Route::post('user_change_password', [App\Http\Controllers\admin\Admin::class, 'user_change_password'])->name('user_change_password');
    Route::get('/my_profile', function () {return view('admin.my_profile');});
    Route::get('/register', [Admin::class, 'showRegisterForm'])->name('admin.register.form');
    Route::post('/register', [Admin::class, 'register'])->name('admin.register');

    //users route
    Route::get('users-list',[App\Http\Controllers\admin\UserController::class,'user_list'])->name('user_list');
    Route::get('client-management',[App\Http\Controllers\admin\UserController::class,'client_management'])->name('client_management');
    Route::post('cancel-client',[App\Http\Controllers\admin\UserController::class,'cancel_client'])->name('cancel_client');
    Route::post('toggle-payment',[App\Http\Controllers\admin\UserController::class,'toggle_payment_processing'])->name('toggle_payment');
    Route::get('client-details/{id}',[App\Http\Controllers\admin\UserController::class,'get_client_details'])->name('client_details');
    Route::get('user-create',[App\Http\Controllers\admin\UserController::class,'create_user'])->name('create_user');
    Route::post('add_user',[App\Http\Controllers\admin\UserController::class,'add_user'])->name('add_user');
    Route::get('user-view/{id}',[App\Http\Controllers\admin\UserController::class,'user_view'])->name('user_view');
    Route::get('user_edit/{id}',[App\Http\Controllers\admin\UserController::class,'edit_user'])->name('edit_user');
    Route::post('update_user',[App\Http\Controllers\admin\UserController::class,'update_user'])->name('update_user');
    Route::post('user_Status',[App\Http\Controllers\admin\UserController::class,'user_Status'])->name('user_Status');
    Route::get('delete_user/{id}',[App\Http\Controllers\admin\UserController::class,'delete_user'])->name('delete_user');

    //////lovedones
    Route::get('lovedones_list',[App\Http\Controllers\admin\LovedonesController::class,'lovedones_list'])->name('lovedones_list');
    Route::get('edit_lovedones/{id}',[App\Http\Controllers\admin\LovedonesController::class,'edit_lovedones'])->name('edit_lovedones');
    Route::post('update_lovedone',[App\Http\Controllers\admin\LovedonesController::class,'update_lovedone'])->name('update_lovedone');
    Route::get('delete_lovedone/{id}',[App\Http\Controllers\admin\LovedonesController::class,'delete_lovedone'])->name('delete_lovedone');

    ////////subscription
    Route::get('subscription_list',[App\Http\Controllers\admin\SubscriptionController::class,'subscription_list'])->name('subscription_list');
    Route::get('edit_subscription/{id}',[App\Http\Controllers\admin\SubscriptionController::class,'edit_subscription'])->name('edit_subscription');
    Route::post('update_subscription',[App\Http\Controllers\admin\SubscriptionController::class,'update_subscription'])->name('update_subscription');
    
});
