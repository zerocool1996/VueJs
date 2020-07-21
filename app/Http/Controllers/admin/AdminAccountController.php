<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\AdminAccountRequest;
use App\Http\Requests\UpdateUserRequest;
use App\User;
use App\RoleGroup;
use App\Gender;
use Carbon\Carbon;

class AdminAccountController extends Controller
{
    protected $user;
    protected $gender;

    public function __construct(
        User        $user,
        Gender      $gender,
        RoleGroup   $rolegroup
    ){
        $this->user     = $user;
        $this->gender   = $gender;
        $this->rolegroup      = $rolegroup;
    }

    public function index(){
        $users = $this->user->where('role',2)->paginate(10);
        return view('admin.admin-account.index', compact('users'));
    }

    public function destroy($id){
        $user = $this->user->findOrFail($id);
        $user->delete();
        return redirect()->back()->with('success_msg', 'Delete Success !');
    }

    public function add(){
        $rolegroups = $this->rolegroup->get(['id', 'title']);
        return view('admin.admin-account.add', compact('rolegroups'));
    }
    public function create(AdminAccountRequest $request){
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
            'img'           => $save_name,
            'role'          => 2,
            'role_group_id' => $request->rolegroup,
        ]);
        return redirect()->back()->withInput($request->flash())->with('success_msg', 'Add User Success !');
    }

    public function edit($id){
        $rolegroups = $this->rolegroup->get(['id', 'title']);
        $user = $this->user->findOrFail($id);
        return view('admin.admin-account.edit', compact('user', 'rolegroups'));
    }

    public function update(UpdateUserRequest $request, $id){
        if($request->role_group_id){
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
                'img'           => $save_name,
                'role_group_id' => $request->role_group_id,
            ]);
            return redirect()->back()->withInput($request->flash())->with('success_msg', 'Update User Success !');
        }else{
            return redirect()->back()->withInput($request->flash())->with('erorr_msg', 'Role group is required !');
        }

    }

    public function search(Request $request){
        if($request->ajax()){
            $users = $this->user->where([ ['email', 'like', '%'. $request->keyword . '%'], ['role', 2] ])->paginate(10);
            return view('admin.ajax-view.user-page', compact('users'))->render();
        }
        $users = $this->user->paginate(10);
        return view('admin.ajax-view.user-garbage', compact('users'));
    }

    public function garbage(){
        $users = $this->user->onlyTrashed()->where('role', 2)->paginate(10);
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
            $users = $this->user->onlyTrashed()->where([ ['email', 'like', '%'. $request->keyword . '%'], ['role', 2] ])->paginate(10);
            return view('admin.ajax-view.user-garbage', compact('users'))->render();
        }
        $users = $this->user->onlyTrashed()->paginate(10);
        return view('admin.ajax-view.user-garbage', compact('users'))->render();
    }
}
