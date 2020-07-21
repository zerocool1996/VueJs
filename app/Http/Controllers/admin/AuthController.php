<?php

// namespace App\Http\Controllers\Admin;

// use Illuminate\Http\Request;
// use Illuminate\Support\Facades\Auth;
// use App\Http\Controllers\Controller;
// use App\User;
// use App\Gender;
// use App\Http\Requests\UserRequest;
// class AuthController extends Controller
// {
//     protected $user;
//     protected $gender;

//     public function __construct(
//         User $user,
//         Gender $gender
//     ){
//         $this->user = $user;
//         $this->gender = $gender;
//     }

//     public function index(){
//         return view('admin.layout.admin');
//     }

//     public function login(){
//         return view('admin.login');
//     }

//     public function postlogin(Request $req){
//         if(Auth::guard('admin')->attempt(['email' => $req->email, 'password' => $req->password, 'role' => 2]) || Auth::guard('admin')->attempt(['email' => $req->email, 'password' => $req->password, 'role' => 3])){
//             return redirect()->route('admin.index');
//         }else{
//             return redirect()->route('admin.login')->withErrors('Login Fail');
//         }
//     }

//     public function signup(){
//         return view('admin.signup');
//     }

//     public function postsignup(UserRequest $request){
//         User::create([
//             'email'         => $request->email,
//             'password'      => bcrypt($request->password),
//             'first_name'    => $request->firstname,
//             'last_name'     => $request->lastname,
//             'gender_id'     => $request->gender,
//             'address'       => $request->address,
//             'tel'           => $request->tel
//         ]);
//         return redirect()->route('admin.login');
//     }

//     public function logout(){
//         Auth::guard('admin')->logout();
//         return redirect()->route('admin.login');
//     }

// }

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login']]);
    }

    public function login(LoginRequest $request)
    {
        //$credentials = $request->only('email', 'password');

        if ($token = $this->guard()->attempt([
                'email'     => $request->email,
                'password'  => $request->password,
                'role'      => 2
            ]))
        {
            return $this->respondWithToken($token);
        }elseif ($token = $this->guard()->attempt([
            'email'     => $request->email,
            'password'  => $request->password,
            'role'      => 3
        ])) {
            return $this->respondWithToken($token);
        }

        return response()->json(['errors' => [
            'credentials' => 'Tai khoan hoac mat khau khong chinh xac'
        ]], 422);
    }

    public function me()
    {
        return response()->json($this->guard()->user());
    }

    public function logout()
    {
        $this->guard()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
        ]);
    }

    public function guard()
    {
        return Auth::guard('admin');
    }
}
