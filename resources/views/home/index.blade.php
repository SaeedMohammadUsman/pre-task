
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Admin Dashboard') }}
        </h2>
    </x-slot>
   
   
   
    <div class="container py-4">
        <div class="row">
            <!-- Left Column: User Info -->
            <div class="col-md-5">
                <div class="card mb-4">
                    <div class="card-header bg-primary text-white">
                        Welcome, {{ $user->name }}
                    </div>
                    <div class="card-body">
                        <p><strong>Email:</strong> {{ $user->email }}</p>
    
                        <h6 class="mt-3"><strong>Roles:</strong></h6>
                        <ul class="list-group mb-3">
                            @forelse ($roles as $role)
                                <li class="list-group-item">{{ $role }}</li>
                            @empty
                                <li class="list-group-item text-muted">No roles assigned</li>
                            @endforelse
                        </ul>
    
                        <h6><strong>Permissions:</strong></h6>
                        <ul class="list-group">
                            @forelse ($permissions as $permission)
                                <li class="list-group-item">{{ $permission->name }}</li>
                            @empty
                                <li class="list-group-item text-muted">No permissions assigned</li>
                            @endforelse
                        </ul>
                    </div>
                </div>
            </div>
    
            <!-- Right Column: Post with Buttons -->
            <div class="col-md-7">
                <div class="card">
                    <div class="card-header bg-secondary text-white">
                        Sample Post
                    </div>
                    <div class="card-body">
                        <p>This is a demo post visible to users with appropriate permissions.</p>
                        <div class="d-flex gap-2 flex-wrap">
                            @can('post.view')
                                <button class="btn btn-outline-primary btn-sm">View</button>
                            @endcan
                            @can('post.comment')
                                <button class="btn btn-outline-success btn-sm">Comment</button>
                            @endcan
                            @can('post.like')
                                <button class="btn btn-outline-warning btn-sm">Like</button>
                            @endcan
                            @can('post.share')
                                <button class="btn btn-outline-info btn-sm">Share</button>
                            @endcan
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
        
</x-app-layout>

