<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Role;
use App\RoleGroup;
use App\Http\Requests\RoleGroupRequest;
use Illuminate\Support\Arr;

class RoleGroupController extends Controller
{
    protected $rolegroup;
    protected $role;

    public function __construct(
        Role $role,
        RoleGroup $rolegroup
    ){
        $this->role = $role;
        $this->rolegroup = $rolegroup;
    }

    public function index () {
        $rolegroups = $this->rolegroup->paginate(15);
        return view('admin.rolegroup.index', compact('rolegroups'));
    }

    public function create () {
        $roles = $this->role->all();
        return view('admin.rolegroup.create', compact('roles'));
    }

    public function store(RoleGroupRequest $request){
        $arr = Arr::sort($request->roles);
        $this->rolegroup->create([
            'title'         => $request->title,
            'description'   => $request->description,
            'roles_id'      => $arr
        ]);
        return redirect()->route('rolegroup.list')->with('success_msg', 'CREATE_SUCCESS');
    }

    public function edit($id){
        $rolegroup = $this->rolegroup->findOrFail($id);
        $roles = $this->role->all();
        return view('admin.rolegroup.edit', compact('rolegroup', 'roles'));
    }

    public function update(RoleGroupRequest $request, $id){
        $arr = Arr::sort($request->roles);
        $rolegroup = $this->rolegroup->findOrFail($id);
        $rolegroup->update([
            'title'         => $request->title,
            'description'   => $request->description,
            'roles_id'      => $arr
        ]);
        return redirect()->route('rolegroup.list')->with('success_msg', 'UPDATE_SUCCESS');
    }

    public function destroy($id){
        $rolegroup = $this->rolegroup->findOrFail($id);
        $rolegroup->delete();
        return redirect()->route('rolegroup.list')->with('success_msg', 'DELETE_SUCCESS');
    }

    public function search(Request $req)
    {
        if ($req->ajax()) {
            if ($req->keyword) {
                $rolegroups = $this->rolegroup->where('title', 'like', '%' . $req->keyword . '%')->orWhere('description', 'like', '%' . $req->keyword . '%')->paginate(15);
            } else {
                $rolegroups = $this->rolegroup->paginate(15);
            }
            $search = view('admin.ajax.search-role-group', compact('rolegroups'))->render();

            return response()->json(['html' => $search], 200);
        }
    }
}
