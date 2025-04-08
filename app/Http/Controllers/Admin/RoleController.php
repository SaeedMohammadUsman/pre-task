<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{


    // Display a listing of the roles
    public function index()
    {
        $roles = Role::all(); // Get all roles
        $permissions = Permission::all();
        return view('dashboard.role', compact('roles', 'permissions')); // Display roles in a table in the view
    }

    // Show the form for creating a new role
    public function create()
    {
        $permissions = Permission::all(); // Get all permissions
        return view('admin.roles.create', compact('permissions')); // Pass permissions to the view
    }


    public function store(Request $request)
    {
        // Step 1: Validate the input
        $validated = $request->validate([
            'name' => 'required|string|unique:roles,name',
            'permissions' => 'required|array',
            'permissions.*' => 'exists:permissions,id',
        ]);

        DB::beginTransaction();

        try {
            // Step 2: Create the role using Spatie's Role model
            $role = Role::create([
                'name' => $validated['name'],
                'guard_name' => 'web' // Important: Match your guard (default is 'web')
            ]);

            // Step 3: Assign permissions to the role
            $permissions = Permission::whereIn('id', $validated['permissions'])->get();
            $role->syncPermissions($permissions);

            DB::commit();

            return redirect()
                ->route('admin.roles.index')
                ->with('success', 'Role created and permissions assigned successfully.');
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Failed to create role', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect()
                ->route('admin.roles.index')
                ->with('error', 'An error occurred while creating the role.');
        }
    }

    // Show the form for editing the specified role
    public function edit(Role $role)
    {
        $permissions = Permission::all(); // Get all permissions
        $rolePermissions = $role->permissions->pluck('id')->toArray(); // Get current permissions for the role
        return view('admin.roles.edit', compact('role', 'permissions', 'rolePermissions')); // Pass to view
    }

    // Update the specified role in the database
    public function update(Request $request, Role $role)
    {
        $request->validate([
            'name' => 'required|unique:roles,name,' . $role->id, // Validate role name, but exclude the current role
            'permissions' => 'required|array', // Validate permissions (array of IDs)
            'permissions.*' => 'exists:permissions,id', // Each permission ID must exist in the permissions table
        ]);

        // Update the role name
        $role->update(['name' => $request->name]);

        // Sync the permissions (assign only the selected permissions)
        $role->syncPermissions($request->permissions);

        return redirect()->route('dashboard.role')->with('success', 'Role updated successfully.');
    }

    // Remove the specified role from the database
    // Remove the specified role from the database
    public function destroy(Role $role)
    {
        // Delete the role
        $role->delete();

        // Redirect to the roles index page (admin.roles.index)
        return redirect()->route('admin.roles.index')->with('success', 'Role deleted successfully.');
    }
}
