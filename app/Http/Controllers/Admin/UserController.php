<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
   public function index(){
    $users = User::orderBy('created_at','desc')->paginate(10);
    return view('admin.users.list',compact('users'));
   }
}
