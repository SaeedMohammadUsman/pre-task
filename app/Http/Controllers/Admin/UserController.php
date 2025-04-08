<?php

namespace App\Http\Controllers\Admin;

use Spatie\Permission\Models\Role;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    // Display all users    
    public function index()
    {

        Log::info('UserController@index called');

        $users = User::where('id', '!=', 1) // id 1 is the super admin
            ->get();
        $roles = Role::all();

        Log::info('Roles fetched:', $roles->pluck('name')->toArray());

        return view('dashboard.admin', compact('users', 'roles'));
    }

    // Store a new user
    public function store(Request $request)
    {
        $roles = Role::all();
        // dd($roles);
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|confirmed|min:4|max:4',

            'role' => 'required|string|exists:roles,name',

        ]);

        $user =  User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);

        // return redirect()->route('admin.users.index');
        $user->assignRole($validated['role']);
        return redirect()->route('admin.users.index')->with('success', 'User  created successfully!');
    }

    // Update an existing user
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'role' => 'required|string|exists:roles,name',
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);
        $user->syncRoles([$validated['role']]);
        return redirect()->route('admin.users.index')->with('success', 'User updated with role.');
    }

    // Delete a user
    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('admin.users.index')->with('success', 'User deleted successfully!');
    }
}
