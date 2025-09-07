<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Models\Lovedone;
use App\Models\User;
use DB;
use Validator;
class LovedonesController extends Controller
{
    public function lovedones_list()
    {
         $lovedones = Lovedone::orderby('id','desc')->get();
         foreach ($lovedones as $key => $value) {
             $value->u_name = User::where('id',$value->user_id)->first()->name;
         }
         return view('admin.lovedones.loved_ones_list', compact('lovedones'));
    }

    
    public function edit_lovedones($id){
      $edit_lovedones = Lovedone::find($id);
      return view('admin.lovedones.lovedone_edit',compact('edit_lovedones'));

    }

    public function update_lovedone(Request $request){
        $lovedone = Lovedone::find($request->lovedone_id);
        $lovedone->phone_no = $request->phone_no;
        $lovedone->timezone = $request->timezone;
        $lovedone->save();
        return response()->json(['status'=>true,'message'=>'Lovedone updated successfully']);
    }
    
    public function delete_lovedone(Request $request, $id)
    {
        Lovedone::where('id', $id)->delete();
        return response()->json(['status'=>true,'message' => 'Lovedone deleted successfully']);
        

    }
    
}