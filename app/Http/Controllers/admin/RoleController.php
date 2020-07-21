<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\RoleRequest;
use App\Role;

class RoleController extends Controller
{
    protected $role;
    public function __construct (
        Role $role
    ) {
        $this->role = $role;
    }

    public function index()
    {
        $roles = $this->role->paginate(15)->OnEachSide(2);
        return view('admin.role.index', compact('roles'));
    }

    public function create()
    {
        return view('admin.role.add');
    }

    public function store(RoleRequest $request)
    {
        $this->role->create([
            'name'          => $request->name,
            'permissions'   => $request->permisstions,
            'description'   => $request->description,
        ]);
        return redirect()->route('role.list')->with('success_msg', 'CREATE_SUCCESS');
    }

    public function edit($id)
    {
        $role = $this->role->findOrFail($id);
        return view('admin.role.add', ['role' => $role]);
    }

    public function update(RoleRequest $request, $id)
    {
        $role = $this->role->findOrFail($id);
        $role->update([
            'name'          => $request->name,
            'permissions'   => $request->permisstions,
            'description'   => $request->description,
        ]);
        return redirect()->route('role.list')->with('success_msg', 'UPDATE_SUCCESS');
    }

    public function destroy($id)
    {
        $role = $this->role->findOrFail($id);
        $role->delete();
        return redirect()->back()->with('success_msg', 'DELETE_SUCCESS');
    }
}
