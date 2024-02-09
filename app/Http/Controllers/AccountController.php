<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
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
       $id = Auth::user()->id;
       $user = User::where('id',$id)->first();


        return view("frontend.account.profile",[
            'user'=>$user
        ]);
    }

    public function updateProfile(Request $request){
        $id = Auth::user()->id;
        $validator = Validator::make($request->all(),[
            'name'=>'required|min:3|max:20',
            'email'=>'required|email|unique:users,email,'.$id.',id',

          ]);
          if($validator->fails()){
            return response()->json([
                'status'=>'faild',
                'errors'=>$validator->messages()
            ]);
          }else{
            $user = User::find($id);
            $user->name = $request->name;
            $user->email = $request->email;
            $user->designation = $request->designation;
            $user->mobile = $request->mobile;
            $user->save();
            session()->flash('success','Profile Updated successfully.');
            return response()->json([
            ]);
          }

    }

    public function logout(){
        Auth::logout();
        return redirect()->route('processLogin');
    }

    public function updateProfilePic(Request $request){
        $id = Auth::user()->id;
        $validator = Validator::make($request->all(),[
            'image'=>'required|image',

          ]);
          if($validator->fails()){
            return response()->json([
                'status'=>'faild',
                'errors'=>$validator->messages()
            ]);
          }else{

             $image = $request->image;
            $ext = $image->getClientOriginalExtension();
            $imageName = $id.'_'.time().'.'.$ext; //3-121212.jpg
            $image->move(public_path('images/profile/'),$imageName);

            // create a small thum
                $sourcePath = public_path('images/profile/'.$imageName);
                $manager = new ImageManager(Driver::class);
                $image = $manager->read($sourcePath);

                // crop the best fitting 5:3 (600x360) ratio and resize to 600x360 pixel
                $image->cover(150, 150);
                $image->toPng()->save(public_path('images/thum/').$imageName);

                // delete old profile pic
                File::delete(public_path('images/profile/').Auth::user()->image);
                File::delete(public_path('images/thum/').Auth::user()->image);

           User::where('id',$id)->update([

            'image'=>$imageName
           ]);

            session()->flash('success','Profile Image Saved.');
            return response()->json([
            ]);
          }

    }
}
