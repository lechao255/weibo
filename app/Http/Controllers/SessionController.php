<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;

class SessionController extends Controller
{
	public function __construct(){
		$this->middleware('guest', ['only' => ['create']]);
	}

    //
    public function create(){
    	return view('sessions.create');
    }

    //
    public function store(Request $request){
    	$credentials = $this->validate($request, [
    		'email' => 'required|email|max:255',
    		'password' => 'required'
    	]);

    	if (Auth::attempt($credentials, $request->has('remember'))) {
    		// 登录成功后的操作
    		// 验证用户是否已经激活
    		if (Auth::user()->activated) {
    			session()->flash('success', '欢迎回来！');
	    		//return redirect()->route('users.show', [Auth::user()]);
	    		$fallback = route('users.show', [Auth::user()]);
	    		// redirect 实例提供了一个 intended 方法，该方法可将页面重定向到上一次请求尝试访问的页面上，参数为默认跳转
	    		return redirect()->intended($fallback);
    		}else{
    			Auth::logout();
    			session()->flash('warning', '您的账号未激活，请检查邮箱中的注册邮件进行激活。');
    			return redirect('/');
    		}
    	}else{
    		// 登录失败后的操作
    		session()->flash('danger', '很抱歉，您的邮箱和密码不匹配');
    		return redirect()->back()->withInput();
    	}
    }

    //
    public function destroy(){
    	Auth::logout();
    	session()->flash('success', '您已成功退出！');
    	return redirect('login');
    }
}
