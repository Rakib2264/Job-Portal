<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function index()
    {
        $users = User::orderBy('created_at', 'desc')->paginate(10);
        return view('admin.users.list', compact('users'));
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('admin.users.edit', compact('user'));
    }
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|min:3|max:20',
            'email' => 'required|email|unique:users,email,' . $id . ',id',

        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => 'faild',
                'errors' => $validator->messages()
            ]);
        } else {
            $user = User::findOrFail($id);
            $user->name = $request->name;
            $user->email = $request->email;
            $user->designation = $request->designation;
            $user->mobile = $request->mobile;
            $user->update();
            session()->flash('success', 'User Updated successfully.');
            return response()->json([]);
        }
    }

    public function delete(Request $request)
    {
        $user = User::findOrFail($request->id);
        if ($user == null) {
            session()->flash('error','User Not Found.');
            return response()->json(['status' => false]);
        }
        $user->delete();
        session()->flash('success','User Deleted.');
        return response()->json(['status' => true]);
    }
}
