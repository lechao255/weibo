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

    // 展示个人详情
    public function show(User $user){
    	return view('users.show', compact('user'));
    	// 如上的compact用法可以这样理解
    	// 寻找以compact括号内的参数（user）作为变量名的变量（$user），如果存在，则以此参数（user）作为数组键，此变量（$user）的值作为数组值
    	// return view('users.show', ['user' => $user]);
    }

    // 保存用户数据
    public function store(Request $request){
    	$this->validate($request, ['name' => 'required|max:50', 'email' => 'required|email|unique:users|max:255', 'password' => 'required|confirmed|min:6']);
    	
    	$user = User::create([
    		'name' => $request->name,
    		'email' => $request->email,
    		'password' => bcrypt($request->password),
    	]);

    	// 由于http协议是无状态的，所以Laravel提供了一种用于临时保存用户数据的方法-会话（Session）
    	// flash方法保存的数据只会保留到下个HTTP请求到来之前，然后就会被删除，闪存数据主要用于短期的状态消息
    	session()->flash('success', '欢迎，您将在这里开启一段新的旅程~');

    	return redirect()->route('users.show', [$user]);
    }
}
