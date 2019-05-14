<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Auth;
//use App\Http\Requests\SignupInfo;

class UserController extends Controller
{
	public function __construct(){
		$this->middleware('auth', ['except' => ['show', 'create', 'store', 'index']]);

		$this->middleware('guest', ['only' => ['create']]);
	}

	public function index(){
		//$users = User::all();
		$users = User::paginate(10);
		return view('users.index', compact('users'));
	}

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

    	// 创建表单请求验证类（SignupInfo）来处理数据验证。
    	// art make:request SignupInfo  新创建的验证类保存在 app/Http/Requests/ 目录下，将验证规则添加到 rules 方法中，authorize 方法 return true。
    	// 使用：use App\Http\Requests\SignupInfo 引入，
    	// 然后 $validated = $request->validated(); 调用
    	
    	$user = User::create([
    		'name' => $request->name,
    		'email' => $request->email,
    		'password' => bcrypt($request->password),
    	]);

    	Auth::login($user);

    	// 由于http协议是无状态的，所以Laravel提供了一种用于临时保存用户数据的方法-会话（Session）
    	// flash方法保存的数据只会保留到下个HTTP请求到来之前，然后就会被删除，闪存数据主要用于短期的状态消息
    	session()->flash('success', '欢迎，您将在这里开启一段新的旅程~');

    	return redirect()->route('users.show', [$user]);
    }

    // 编辑用户信息页面
    public function edit(User $user){
    	$this->authorize('update', $user);
    	return view('users.edit', compact('user'));
    }

    // 保存用户编辑信息
    public function update(User $user, Request $request){
    	$this->authorize('update', $user);
    	$this->validate($request, ['name' => 'required|max:50', 'password' => 'nullable|confirmed|min:6']);

    	$data = [];
    	$data ['name'] = $request->name;
    	if ($request->password) {
    		$data ['password'] = bcrypt($request->password);
    	}

    	$user->update($data);

    	session()->flash('success', '个人资料更新成功！');

    	return redirect()->route('users.show', $user->id);
    }

    // 管理员删除用户
    public function destroy(User $user){
    	$this->authorize('destroy', $user);
    	$user->delete();
    	session()->flash('success', '成功删除用户！');
    	return back();
    }
}
