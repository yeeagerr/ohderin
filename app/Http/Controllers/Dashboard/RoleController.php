<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Role;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function index()
    {
        $roles = Role::all();
        return view('dashboard.roles.index', compact('roles'));
    }

    public function create()
    {
        $permissions = config('permissions');
        return view('dashboard.roles.create', compact('permissions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:roles,name',
            'permissions' => 'array'
        ]);

        Role::create([
            'name' => $request->name,
            'permissions' => $request->permissions ?? []
        ]);

        return redirect()->route('roles.index')->with('success', 'Role berhasil ditambahkan.');
    }

    public function edit(Role $role)
    {
        $permissions = config('permissions');
        return view('dashboard.roles.edit', compact('role', 'permissions'));
    }

    public function update(Request $request, Role $role)
    {
        $request->validate([
            'name' => 'required|unique:roles,name,' . $role->id,
            'permissions' => 'array'
        ]);

        $role->update([
            'name' => $request->name,
            'permissions' => $request->permissions ?? []
        ]);

        return redirect()->route('roles.index')->with('success', 'Role berhasil diperbarui.');
    }

    public function destroy(Role $role)
    {
        if ($role->users()->count() > 0) {
            return redirect()->route('roles.index')->with('error', 'Role tidak bisa dihapus karena masih digunakan oleh user.');
        }

        if ($role->name === 'Super Admin') {
            return redirect()->route('roles.index')->with('error', 'Role Super Admin tidak bisa dihapus.');
        }

        $role->delete();
        return redirect()->route('roles.index')->with('success', 'Role berhasil dihapus.');
    }
}
