<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\User;
use App\Gender;
use Carbon\Carbon;

class CustomerController extends Controller
{
    protected $user;
    protected $gender;

    public function __construct(
        User $user,
        Gender $gender
    ){
        $this->user = $user;
        $this->gender = $gender;
    }

    public function index(){
        $users = $this->user->where('role',1)->paginate(10);
        return response()->json([
            'customers' => $users
        ]);
    }

    public function destroy($id){
        $user = $this->user->findOrFail($id);
        $user->delete();
        return redirect()->back()->with('success_msg', 'Delete Success !');
    }

    public function create(UserRequest $request){
        $name = '';
        $save_name = '';
        if($request->hasFile('img')){
            $img = $request->img;
            $img_name = $img->getClientOriginalName();
            $time = Carbon::now()->second;
            $name = $time.''.$img_name;
            $img->move('assets/vendor/img/user', $name);
            $save_name = 'assets/vendor/img/user/'.$name;
        }
        user::create([
            'email'         => $request->email,
            'password'      => bcrypt($request->password),
            'first_name'    => $request->firstname,
            'last_name'     => $request->lastname,
            'gender_id'     => $request->gender,
            'address'       => $request->address,
            'tel'           => $request->tel,
            'img'           => $save_name
        ]);
        return redirect()->back()->withInput($request->flash())->with('success_msg', 'Add User Success !');
    }

    public function edit($id){
        $user = $this->user->findOrFail($id);
        return response()->json([
            'customer' => $user
        ]);
    }

    public function update(UpdateUserRequest $request, $id){
        $user = $this->user->findOrFail($id);
        $save_name = $user->img;
        if($request->hasFile('img')){
            if($user->img != null || $user->img != ""){
                unlink($user->img);
            }
            $img = $request->img;
            $img_name = $img->getClientOriginalName();
            $time = Carbon::now()->second;
            $name = $time.''.$img_name;
            $img->move('assets/vendor/img/user', $name);
            $save_name = 'assets/vendor/img/user/'.$name;
        }
        $user->update([
            'email'         => $request->email,
            'first_name'    => $request->firstname,
            'last_name'     => $request->lastname,
            'gender_id'     => $request->gender,
            'address'       => $request->address,
            'tel'           => $request->tel,
            'img'           => $save_name
        ]);
        return redirect()->back()->withInput($request->flash())->with('success_msg', 'Update User Success !');
    }

    public function search($keyword){    
        $users = $this->user->where('email', 'like', '%'. $keyword . '%')
        ->orWhere('first_name', 'like', '%'. $keyword . '%')
        ->orWhere('last_name', 'like', '%'. $keyword . '%')
        ->paginate(10);
        return response()->json([
            'customers' => $users
        ]);
    }

    public function garbage(){
        $users = $this->user->onlyTrashed()->paginate(10);
        return view('admin.user.garbage', compact('users'));
    }

    public function restore($id){
        $user = $this->user->onlyTrashed()->findOrFail($id);
        $user->restore();
        return redirect()->back()->with('success_msg', 'Restore User Success!');
    }

    public function forceDelete($id){
        $user = $this->user->onlyTrashed()->findOrFail($id);
        $user->forceDelete();
        return redirect()->back()->with('success_msg', 'Force Delete User Success!');
    }

    public function searchDeleted(Request $request){
        if($request->ajax()){
            $users = $this->user->onlyTrashed()->where('email', 'like', '%'. $request->keyword . '%')->paginate(10);
            return view('admin.ajax-view.user-garbage', compact('users'))->render();
        }
        $users = $this->user->onlyTrashed()->paginate(10);
        return view('admin.ajax-view.user-garbage', compact('users'))->render();
    }
}
