<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class HomeController extends Controller
{

    public function index()
    {
        $user = Auth::user();

        return view('home.index', [
            'user' => $user,
            'roles' => $user->getRoleNames(), // returns a collection of role names
            'permissions' => $user->getAllPermissions(), // returns a collection of Permission models
        ]);
    }


    public function about()
    {
        return view('about');
    }

    public function contact()
    {
        return view('contact');
    }
}
