<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    // 返回注册页面
    public function create()
    {
    	return view('users.create');
    }

    //
    public function show(User $user){
    	return view('users.show', compact('user'));
    }
}
