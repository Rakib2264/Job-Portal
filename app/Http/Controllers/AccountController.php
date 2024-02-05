<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AccountController extends Controller
{
    //This method will show user registation page
    public function registation(){
          return view("frontend.account.register");
    }

    //This method will save a user
    public function processRegistation(Request $request){
       $valitator = Validator::make($request->all(),[
         'name'=>'required',
         'email'=>'required|email',
         'password'=>'required|min:5|same:confirm_password',
         'confirm_password'=>'required|min:5',
       ]);

       if ($valitator->passes()) {
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        // $user->confirm_password = Hash::make($request->confirm_password);
        $user->save();
        session()->flash('success','You have been registered successfully.');
        return response()->json([
            'status'=>true,
            'errors'=>[]
        ]);
       }else{
        return response()->json([
            'status'=>false,
            'errors'=>$valitator->errors()
        ]);
       }
    }

   //This method will show user login page
    public function login(){
        return view('frontend.account.login');

    }
}
