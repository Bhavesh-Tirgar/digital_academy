<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        $users = User::paginate(10);
        return view('backend.user.index', compact('users'));
    }

    public function create()
    {
        return view('backend.user.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
            'role' => 'required|in:admin,instructor,student,user',
        ]);

        $role = $request->role;
        $roleId = match($role) {
            'admin' => 1,
            'instructor' => 3,
            default => 2,
        };

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => \Illuminate\Support\Facades\Hash::make($request->password),
            'role' => $role,
            'role_id' => $roleId,
            'avatar' => 'https://images.unsplash.com/photo-1535713875002-d1d0cf377fde?w=100',
        ]);

        return redirect('/admin/users')->with('info', 'User created successfully.');
    }

    public function show($id)
    {
        return redirect()->route('users.edit', $id);
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('backend.user.edit', compact('user'));
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,'.$id,
            'role' => 'nullable|string|in:admin,instructor,student,user',
            'status' => 'nullable|string',
        ]);

        $role = $request->role ?? $request->status ?? $user->role;
        $roleId = match($role) {
            'admin' => 1,
            'instructor' => 3,
            default => 2,
        };

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'role' => $role,
            'role_id' => $roleId,
        ]);

        return redirect('/admin/users')->with('info', 'User updated successfully.');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return redirect('/admin/users')->with('info', 'User deleted successfully.');
    }

    // Fallback alias
    public function delete($id)
    {
        return $this->destroy($id);
    }
}
