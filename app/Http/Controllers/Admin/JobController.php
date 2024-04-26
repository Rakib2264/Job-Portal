<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Job;
use App\Models\JobType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class JobController extends Controller
{
    public function index(){
        $jobs = Job::orderBy('created_at','desc')
        ->with('user','applications')
        ->paginate(10);
        return view('admin.jobs.list',compact('jobs'));
    }

    public function edit($id){
        $job = Job::findOrFail($id);
        $category = Category::all();
        $jobtype = JobType::all();
        return view('admin.jobs.edit',compact('job','category','jobtype'));

    }

    public function update(Request $request,$id){
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
           $job->status = $request->status;
           $job->isfeatured = (!empty($request->is_Featured)) ? $request->is_Featured :0;
           $job->update();
           session()->flash('success','Job Updated Successfully');

           return response()->json([]);
        }
    }
}
