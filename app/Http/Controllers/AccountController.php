<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
         'email'=>'required|email|unique:users,email',
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
    public function authenticate(Request $request){
        $validator = Validator::make($request->all(),[
          'email'=>'required|email',
          'password'=>'required',

        ]);
        if($validator->passes()){

            if(Auth::attempt(['email'=>$request->email,'password'=>$request->password])){  //database check email and password same or not
                return redirect()->route('profile');
            }else{
                return redirect()->route('processLogin')->with('error','Either Email/Password is incurrect');
            }

        }else{
          return redirect()->route('processLogin')->withErrors($validator)->withInput($request->only('email'));
        }

    }
    public function profile(){
        return view("frontend.account.profile");
    }

    public function logout(){
        Auth::logout();
        return redirect()->route('processLogin');
    }
}
