<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Job;
use App\Models\JobType;
use Illuminate\Http\Request;

class JobsController extends Controller
{
    // this method will show jobs page
   public function index(){
    $categories = Category::where('status',1)->get();
    $jobtypes = JobType::where('status',1)->get();
    $jobs = Job::where('status',1)->with('jobType')->orderBy('id','desc')->paginate(9);
   return view('frontend.jobs',compact('categories','jobtypes','jobs'));
   }
}
