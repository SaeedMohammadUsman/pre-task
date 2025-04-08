
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('User Managment') }}
        </h2>
    </x-slot>
    <div class="py-5">
        <div class="container">
            <!-- Welcome Message -->
           
            <!-- Success Message -->
        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif
        
            <!-- Add User Button -->
            <div class="mb-3 text-end">
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addUserModal">
                    Add New User
                </button>
            </div>
            {{-- {{ dd($roles); }} --}}

            <!-- Users Table -->
            <div class="table-responsive">
                <table class="table table-hover table-bordered align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th scope="col">No</th>
                            <th scope="col">Name</th>
                            <th scope="col">Email</th>
                            <th scope="col">Role</th>
                            <th scope="col" class="text-center">Options</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $user)
                            <tr>
                                <td>{{ $user->id }}</td>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>{{ $user->getRoleNames()->first() }}</td> <!-- Assuming each user has one role -->
                                <td class="text-center">
                                    {{-- <a href="{{ route('users.edit', $user->id) }}" class="btn btn-sm btn-warning me-1" title="Edit">
                                        <i class="bi bi-pencil-square"></i>
                                    </a> --}}
                                    <a 
    href="#"
    class="btn btn-sm btn-warning me-1 edit-btn" 
    data-id="{{ $user->id }}"
    data-name="{{ $user->name }}"
    data-email="{{ $user->email }}"
    data-role="{{ $user->getRoleNames()->first() }}"
    data-url="{{ route('admin.users.update', $user->id) }}"

    data-bs-toggle="modal"
    data-bs-target="#editUserModal"
    title="Edit">
    <i class="bi bi-pencil-square"></i>
</a>

                                    
                                    <form action="{{ route('admin.users.delete', $user->id) }}" method="POST" style="display:inline;">
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

            <!-- Add User Modal -->
            <div class="modal fade" id="addUserModal" tabindex="-1" aria-labelledby="addUserModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                                    <form class="modal-content" id="createUser" method="POST" action="{{ route('admin.users.store') }}">
                    @csrf
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="addUserModalLabel">Add New User</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <!-- Form Inputs -->
                                        <div class="mb-3">
                                            <label for="name" class="form-label">Name</label>
                                            <input type="text"  name="name" id="name" class="form-control" placeholder="Enter name">
                                        </div>
                                        <div class="mb-3">
                                            <label for="email" class="form-label">Email</label>
                                            <input type="email" name="email" id="email" class="form-control" placeholder="Enter email">
                                        </div>
                    <!-- Password -->
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input name="password" type="password" id="password" class="form-control" placeholder="Enter 4-digit password" minlength="4" maxlength="4" required>
                        @error('password')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Confirm Password -->
                    <div class="mb-3">
                        <label for="password_confirmation" class="form-label">Confirm Password</label>
                        <input name="password_confirmation" type="password" id="password_confirmation" class="form-control" placeholder="Confirm password" required>
                        @error('password_confirmation')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Password Error Message (hidden initially) -->
                    <div id="password-error-message" class="text-danger" style="display: none;">
                        Passwords do not match!
                    </div>
                                        <div class="mb-3">
                                            <label for="role" class="form-label">Role</label>
                                        
                                            <select name="role" id="role" class="form-control" required>
                                                @foreach($roles as $role)
                                                    <option value="{{ $role->name }}" {{ old('role') === $role->name ? 'selected' : '' }}>
                                                        {{ ucfirst($role->name) }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                        <button type="submit" class="btn btn-success">Save User</button>
                                    </div>
                                </form>
                </div>
            </div>
            
            
            <!-- Edit User Modal -->
<div class="modal fade" id="editUserModal" tabindex="-1" aria-labelledby="editUserModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form class="modal-content" id="editUserForm" method="POST">
            @csrf
            @method('PUT') <!-- Laravel spoofing for PUT -->
            <div class="modal-header">
                <h5 class="modal-title" id="editUserModalLabel">Edit User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Hidden ID -->
                <input type="hidden" id="edit_user_id">

                <!-- Name -->
                <div class="mb-3">
                    <label for="edit_name" class="form-label">Name</label>
                    <input type="text" name="name" id="edit_name" class="form-control">
                </div>

                <!-- Email -->
                <div class="mb-3">
                    <label for="edit_email" class="form-label">Email</label>
                    <input type="email" name="email" id="edit_email" class="form-control">
                </div>

                <!-- Role -->
                <div class="mb-3">
                    <label for="edit_role" class="form-label">Role</label>
                    <select name="role" id="edit_role" class="form-control" required>
                        @foreach($roles as $role)
                            <option value="{{ $role->name }}">{{ ucfirst($role->name) }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Optional: No password fields here (for simplicity) -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Update User</button>
            </div>
        </form>
    </div>
</div>

            
</x-app-layout>

<script>
    document.getElementById('createUser').addEventListener('submit', function(event) {
        // Get the password and confirm password fields
        var password = document.getElementById('password').value;
        var passwordConfirmation = document.getElementById('password_confirmation').value;

        // Check if the passwords match
        if (password !== passwordConfirmation) {
            // Prevent form submission
            event.preventDefault();
            
            // Show the error message
            document.getElementById('password-error-message').style.display = 'block';
        } else {
            // Hide the error message if passwords match
            document.getElementById('password-error-message').style.display = 'none';
        }
    });
</script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const editButtons = document.querySelectorAll('.edit-btn');

        editButtons.forEach(button => {
            button.addEventListener('click', function () {
                const userId = this.getAttribute('data-id');
                const userName = this.getAttribute('data-name');
                const userEmail = this.getAttribute('data-email');
                const userRole = this.getAttribute('data-role');
                const updateUrl = this.getAttribute('data-url');

                // Populate modal fields
                document.getElementById('edit_user_id').value = userId;
                document.getElementById('edit_name').value = userName;
                document.getElementById('edit_email').value = userEmail;
                document.getElementById('edit_role').value = userRole;

                // Set form action dynamically
                document.getElementById('editUserForm').setAttribute('action', updateUrl);
            });
        });
    });
</script>
