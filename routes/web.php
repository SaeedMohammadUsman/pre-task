<?php

use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Spatie\Permission\Models\Role;


Route::redirect('/', '/login');

Route::get('/dashboard',  [HomeController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});



Route::middleware('auth', 'role:admin')->group(function () {
    // View users and manage
    Route::get('/admin/users', [UserController::class, 'index'])->name('admin.users.index');

    // Store new user
    Route::post('/admin/users', [UserController::class, 'store'])->name('admin.users.store');

    Route::get('/admin/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::put('/admin/users/{user}', [UserController::class, 'update'])->name('admin.users.update');
    Route::delete('/admin/users/{user}', [UserController::class, 'destroy'])->name('admin.users.delete');



    // Role management
    Route::get('/admin/roles', [RoleController::class, 'index'])->name('admin.roles.index'); // View roles
    Route::get('/admin/roles/create', [RoleController::class, 'create'])->name('admin.roles.create'); // Create role form
    Route::post('/admin/roles', [RoleController::class, 'store'])->name('admin.roles.store'); // Store new role
    Route::get('/admin/roles/{role}/edit', [RoleController::class, 'edit'])->name('admin.roles.edit'); // Edit role form
    Route::put('/admin/roles/{role}', [RoleController::class, 'update'])->name('admin.roles.update'); // Update role
    Route::delete('/admin/roles/{role}', [RoleController::class, 'destroy'])->name('admin.roles.destroy'); // Delete role
});






require __DIR__ . '/auth.php';
