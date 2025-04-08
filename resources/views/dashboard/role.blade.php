<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Role Management') }}
        </h2>
    </x-slot>

    <div class="py-5">
        <div class="container">
            <!-- Success Message -->
            @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            @endif

            <!-- Add Role Button -->
            <div class="mb-3 text-end">
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addRoleModal">
                    Add New Role
                </button>
            </div>

            <!-- Roles Table -->
            <div class="table-responsive">
                <table class="table table-hover table-bordered align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th scope="col">No</th>
                            <th scope="col">Role Name</th>
                            <th scope="col">Permissions</th>
                            <th scope="col" class="text-center">Options</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($roles as $role)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ ucfirst($role->name) }}</td>
                            <td>
                                @foreach($role->permissions as $permission)
                                    <span class="badge bg-info">{{ $permission->name }}</span>
                                @endforeach
                            </td>
                            <td class="text-center">
                                <a href="{{ route('admin.roles.edit', $role->id) }}" class="btn btn-sm btn-warning" title="Edit">
                                    <i class="bi bi-pencil-square"></i>
                                </a>

                                <form action="{{ route('admin.roles.destroy', $role->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" title="Delete">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

<!-- Add Role Modal -->
<div class="modal fade" id="addRoleModal" tabindex="-1" aria-labelledby="addRoleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form class="modal-content" method="POST" action="{{ route('admin.roles.store') }}">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title" id="addRoleModalLabel">Add New Role</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Role Name -->
                <div class="mb-3">
                    <label for="role_name" class="form-label">Role Name</label>
                    <input type="text" name="name" id="role_name" class="form-control" placeholder="Enter role name" required>
                </div>

                <!-- Permissions -->
                <div class="mb-3">
                    <label for="permissions" class="form-label">Permissions</label>
                    <div id="permissions" class="form-check">
                        @foreach($permissions as $permission)
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="permissions[]" value="{{ $permission->id }}" id="permission_{{ $permission->id }}">
                                <label class="form-check-label" for="permission_{{ $permission->id }}">
                                    {{ ucfirst($permission->name) }}
                                </label>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-success">Save Role</button>
            </div>
        </form>
    </div>
</div>


</x-app-layout>
