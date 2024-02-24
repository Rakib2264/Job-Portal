<?php

namespace App\Http\Controllers;
use App\Models\Category;
use App\Models\Job;
use App\Models\JobApplication;
use App\Models\JobType;
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

    public function createJob(){
        $category = Category::orderBy('name','ASC')->where('status',1)->get();
        $jobtype = JobType::orderBy('name','ASC')->where('status',1)->get();
        return view('frontend.account.job.create',compact('category','jobtype'));
    }

    public function saveJob(Request $request){
        $rules = [
            'title'=>'required|min:5|max:150',
            'category'=>'required',
            'jobType'=>'required',
            'vacancy'=>'required|integer',
            'Location'=>'required|max:50',
            'description'=>'required',
            'company_name'=>'required|max:60',
        ];
        $validator = Validator::make($request->all(),$rules);
        if ($validator->fails()) {

            return response()->json([
                'status'=>'faild',
                'errors'=>$validator->messages()
            ]);

        }else{
           $job = new Job();
           $job->title = $request->title;
           $job->category_id  = $request->category;
           $job->job_type_id  = $request->jobType;
           $job->user_id   = Auth::user()->id;
           $job->vacancy = $request->vacancy;
           $job->salary = $request->salary;
           $job->location = $request->Location;
           $job->description = $request->description;
           $job->benefits = $request->benefits;
           $job->responsibility = $request->responsibility;
           $job->qualifications = $request->qualifications;
           $job->experience = $request->experience;
           $job->keywords = $request->keywords;
           $job->company_name = $request->company_name;
           $job->company_location = $request->company_location;
           $job->company_website = $request->company_website;
           $job->save();
           session()->flash('success','Job Added Successfully');

           return response()->json([]);
        }

    }

    public function myJob(){
     $jobs = Job::where('user_id',Auth::user()->id)->with('jobType','category')->orderBy('created_at','DESC')->paginate(5);
       return view('frontend.account.job.my-jobs',compact('jobs'));
    }

    public function editJob(Request $request ,$id){
        $category = Category::orderBy('name','ASC')->where('status',1)->get();
        $jobtype = JobType::orderBy('name','ASC')->where('status',1)->get();
         $job = Job::where([ 'user_id'=>Auth::user()->id,'id'=>$id])->first();

         if ($job == null) {
            abort(404);
         }

        return view('frontend.account.job.edit',compact('category','jobtype','job'));
    }

    public function updateJob(Request $request , $id){
        $rules = [
            'title'=>'required|min:5|max:150',
            'category'=>'required',
            'jobType'=>'required',
            'vacancy'=>'required|integer',
            'Location'=>'required|max:50',
            'description'=>'required',
            'company_name'=>'required|max:60',
        ];
        $validator = Validator::make($request->all(),$rules);
        if ($validator->fails()) {

            return response()->json([
                'status'=>'faild',
                'errors'=>$validator->messages()
            ]);

        }else{
           $job = Job::find($id);
           $job->title = $request->title;
           $job->category_id  = $request->category;
           $job->job_type_id  = $request->jobType;
           $job->user_id   = Auth::user()->id;
           $job->vacancy = $request->vacancy;
           $job->salary = $request->salary;
           $job->location = $request->Location;
           $job->description = $request->description;
           $job->benefits = $request->benefits;
           $job->responsibility = $request->responsibility;
           $job->qualifications = $request->qualifications;
           $job->experience = $request->experience;
           $job->keywords = $request->keywords;
           $job->company_name = $request->company_name;
           $job->company_location = $request->company_location;
           $job->company_website = $request->company_website;
           $job->update();
           session()->flash('success','Job Updated Successfully');

           return response()->json([]);
        }

    }
    public function deleteJob(Request $request){

      $job = Job::where(['user_id'=>Auth::user()->id,'id'=>$request->id])->first();

      if($job == null){
        session()->flash('error','Job Not Found');
        return response()->json([
            'status'=>true
        ]);
      }

      $job->where('id',$request->id)->delete();
      session()->flash('info','Job Deleted');
      return response()->json([
          'status'=>true
      ]);    }


      public function myJobApplication(){
        $jobapplications = JobApplication::where('user_id',Auth::user()->id)->with(['job','job.jobType','job.applications'])->paginate(5);
        return view('frontend.account.job.my-job-application',compact('jobapplications'));
      }
      public function removeJob(Request $request){
        $jobApplication = JobApplication::where(['id'=>$request->id,'user_id'=>Auth::user()->id])->first();
        if ($jobApplication==null) {
            session()->flash('error','Job Not Found');

           return response()->json([
            'status'=>false,
           ]);
        }
        JobApplication::find($request->id)->delete();
        session()->flash('success','Job Removed');
        return response()->json([
            'status'=>true,
           ]);

      }
}
