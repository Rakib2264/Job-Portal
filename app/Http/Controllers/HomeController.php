<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Job;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    //This method will show our home page
    public function index(){
        $categories = Category::where('status',1)->orderBy('name','ASC')->take(8)->get();
        $allcategories = Category::where('status',1)->orderBy('name','ASC')->get();
        $isfeatured = Job::where('isfeatured',1)->orderBy('id','DESC')->with('jobType')->take(6)->get();
        $latestjobs = Job::orderBy('id','DESC')->take(6)->get();
        return view ("frontend.home",compact("categories","isfeatured","latestjobs","allcategories"));
    }
}
