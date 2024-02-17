<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Job;
use App\Models\JobApplication;
use App\Models\JobType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

        return view('frontend.detail', compact('job'));
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
        session()->flash('success', 'Applied');
        return response()->json([
            'status' => true,
            'msg' => 'applied'
        ]);
    }
}
