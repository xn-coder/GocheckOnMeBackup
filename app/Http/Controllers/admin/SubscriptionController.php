<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Models\Subscription;
use App\Models\User;
use DB;
use Validator;
class SubscriptionController extends Controller
{
    public function subscription_list()
    {
         $subscriptions = Subscription::orderby('id','desc')->get();
         return view('admin.subscription.subscriptions_list', compact('subscriptions'));
    }

    
    public function edit_subscription($id){
      $subscription = Subscription::find($id);
      return view('admin.subscription.subscription_edit',compact('subscription'));

    }

    public function update_subscription(Request $request){
        $subscription = Subscription::find($request->subscription_id);
        $subscription->name = $request->name;
        $subscription->amount = $request->amount;
        $subscription->save();
        return response()->json(['status'=>true,'message'=>'Subscription updated successfully']);
    }
    
}