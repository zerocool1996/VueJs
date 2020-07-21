<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Cart;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Requests\UserRequest;
use App\Http\Requests\LoginRequest;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Exception;

class AuthController extends Controller
{
    protected $user;
    public function __construct(User $user)
    {
        $this->middleware('auth:api', ['except' => ['login', 'signup']]);
    }

    public function login(LoginRequest $request)
    {
        //$credentials = $request->only('email', 'password');

        if ($token = Auth::guard()->attempt([
            'email'     => $request->email,
            'password'  => $request->password,
            'role'      => 1
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
        Auth::guard()->logout();

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
        return Auth::guard();
    }

    public function signup(UserRequest $request)
    {

        $user = User::create($request->except('img'));
        $this->handleUploadImg($request, $user);
        $user->userCart()->create(['user_id' => $user->id]);

        return response()->json([
            'message' => 'Đăng kí thành công !'
        ]);
    }

    public function update(UpdateUserRequest $request, $id)
    {
        $data = $request->except(['img']);
        $user = User::find($id);
        DB::beginTransaction();
        try {
            $user->update($data);
            $this->handleUploadImg($request, $user);
            DB::commit();
            return response()->json([
                'user' => $user,
                'message' => 'Cập nhật thông tin thành công !'
            ]);
        } catch (Exception $e) {
            DB::rollBack();
        }
        return response()->json([
            'user' => $user,
            'message' => 'Cập nhật thông tin ko thành công !'
        ]);
    }

    public function handleUploadImg($request, $data){
        $old_img = $data->img ?? null;
        if ($request->input('img')) {
            $image = $request->input('img');
            $dataImage = explode(',', $image)[1];
            $type = explode('/', explode(':', substr($image, 0, strpos($image, ';')))[1])[1];
            $filePath = \Config::get('app.env').'/images/';
            $fileName = $filePath.time().'.'.rand().'.'.$type;
            Storage::disk('public')->put($fileName, base64_decode($dataImage));
            $data->update([
                'img' => $fileName
            ]);
            if ($old_img) {
                Storage::disk('public')->delete($old_img);
            }
        }
    }
}
