<?php

namespace App\Http\Controllers;

use App\Mail\JobNotificationEmail;
use App\Models\Category;
use App\Models\Job;
use App\Models\JobApplication;
use App\Models\JobType;
use App\Models\Saved_Job;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class JobsController extends Controller
{
    // this method will show jobs page
    public function index(Request $request)
    {

        $categories = Category::where('status', 1)->get();
        $jobtypes = JobType::where('status', 1)->get();

        $jobs = Job::where('status', 1);
        //  search using keyword
        if (!empty($request->keyword)) {
            // or query use group wise
            $jobs = $jobs->where(function ($query) use ($request) {
                $query->orWhere('title', 'like', '%' . $request->keyword . '%');
                $query->orWhere('Keywords', 'like', '%' . $request->keyword . '%');
            });
        }
        // search using location
        if (!empty($request->location)) {
            $jobs = $jobs->where('location', $request->location);
        }

        // search using category
        if (!empty($request->category)) {
            $jobs = $jobs->where('category_id', $request->category);
        }

        // search using jobtype
        $jobTypeArray = [];
        if (!empty($request->jobType)) {
            $jobTypeArray = explode(',', $request->jobType); //1,2,3
            $jobs = $jobs->whereIn('job_type_id', $jobTypeArray);
        }

        // search using exp
        if (!empty($request->experience)) {
            $jobs = $jobs->where('experience', $request->experience);
        }

        $jobs = $jobs->with(['jobType', 'category']);
        if ($request->sort == 0) {
            $jobs = $jobs->orderBy('created_at', 'ASC');
        } else {
            $jobs = $jobs->orderBy('created_at', 'DESC');
        }


        $jobs = $jobs->paginate(9);

        return view('frontend.jobs', compact('categories', 'jobtypes', 'jobs', 'jobTypeArray'));
    }

    //    this method will show job detail page
    public function details($id)
    {
        $job = Job::where('status', 1)->where('id', $id)->with('jobType')->first();

        if ($job == null) {
            abort(404);
        }
           // Check if the user has already saved the job
           $count = Saved_Job::where([
            'user_id' => Auth::user()->id,
            'job_id' => $id,
        ])->count();

        return view('frontend.detail', compact('job','count'));
    }

    public function applyJob(Request $request)
    {

        $id = $request->id;
        $job = Job::where('id', $id)->first();

        // if job not found
        if ($job == null) {
            session()->flash('error', 'job does not exist');
            return response()->json([
                'status' => false,
                'msg' => 'not exist'
            ]);
        }
        // you can not apply on your own job
        $employer_id = $job->user_id;
        if ($employer_id == Auth::user()->id) {
            session()->flash('error', 'you can not apply on your own job');
            return response()->json([
                'status' => false,
                'msg' => 'you can not apply on your own job'
            ]);
        }


        // you can not apply on a job twise
        $jobApplicationCount = JobApplication::where(['user_id' => Auth::user()->id, 'job_id' => $id])->count();
        if ($jobApplicationCount > 0) {
            session()->flash('error', 'You already already applied on this job');
            return response()->json([
                'status' => false,
                'msg' => 'You already already applied on this job'
            ]);
        }

        $application = new JobApplication();
        $application->job_id = $id;
        $application->user_id = Auth::user()->id;
        $application->employer_id = $employer_id;
        $application->applied_date = now();
        $application->save();

        // send noti email to employer
        // $emplyer = User::where('id',$employer_id)->first();
        // $mailData = [
        //     'employer'=> $emplyer,
        //     'user'=> Auth::user(),
        //     'job'=> $job,
        // ];
        // Mail::to($emplyer->email)->send(new JobNotificationEmail($mailData));

        session()->flash('success', 'Applied');
        return response()->json([
            'status' => true,
            'msg' => 'applied'
        ]);
    }

    public function saveJobwish(Request $request)
    {
        // Get the job ID from the request
        $id = $request->id;

        // Find the job by its ID
        $job = Job::find($id);

        // If the job does not exist, return an error response
        if ($job == null) {
            session()->flash('success', 'Job Not Found');
            return response()->json(['status' => false]);
        }

        // Check if the user has already saved the job
        $count = Saved_Job::where([
            'user_id' => Auth::user()->id,
            'job_id' => $id,
        ])->count();

        // If the job has already been saved by the user, return an error response
        if ($count > 0) {
            session()->flash('error', 'You Already Saved This Job');
            return response()->json(['status' => false]);
        }

        // If the job is not already saved by the user, create a new Saved_Job record
        $savedJob = new Saved_Job();
        $savedJob->job_id = $id;
        $savedJob->user_id = Auth::user()->id;
        $savedJob->save();

        // Return a success response
        session()->flash('success', 'Saved This Job');
        return response()->json(['status' => true]);
    }
}
