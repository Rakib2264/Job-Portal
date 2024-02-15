<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Job;
use App\Models\JobType;
use Illuminate\Http\Request;

class JobsController extends Controller
{
    // this method will show jobs page
   public function index(Request $request){

    $categories = Category::where('status',1)->get();
    $jobtypes = JobType::where('status',1)->get();

    $jobs = Job::where('status',1);
    //  search using keyword
    if (!empty($request->keyword)) {
// or query use group wise
    $jobs = $jobs->where(function($query) use ($request){
    $query->orWhere('title','like','%'.$request->keyword.'%');
    $query->orWhere('Keywords','like','%'.$request->keyword.'%');
     });
    }
    // search using location
    if(!empty($request->location)){
        $jobs = $jobs->where('location',$request->location);
    }

       // search using category
       if(!empty($request->category)){
        $jobs = $jobs->where('category_id',$request->category);
    }

     // search using jobtype
     $jobTypeArray = [];
     if(!empty($request->jobType)){
        $jobTypeArray = explode(',',$request->jobType);//1,2,3
        $jobs = $jobs->whereIn('job_type_id',$jobTypeArray);
    }

      // search using exp
      if(!empty($request->experience)){
        $jobs = $jobs->where('experience',$request->experience);
    }

    $jobs = $jobs->with(['jobType','category']);
    if($request->sort == 0){
        $jobs = $jobs->orderBy('created_at', 'ASC');
    } else{
        $jobs = $jobs->orderBy('created_at', 'DESC');
    }


    $jobs =$jobs->paginate(9);

    return view('frontend.jobs',compact('categories','jobtypes','jobs','jobTypeArray'));
   }
}
