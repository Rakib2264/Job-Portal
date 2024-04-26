<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Job;
use Illuminate\Http\Request;

class JobController extends Controller
{
    public function index(){
        $jobs = Job::orderBy('created_at','desc')
        ->with('user','applications')
        ->paginate(10);
        return view('admin.jobs.list',compact('jobs'));
    }

    public function edit($id){
        

    }
}
