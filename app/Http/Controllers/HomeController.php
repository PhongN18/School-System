<?php

namespace App\Http\Controllers;


use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index()
    {
        $user = Auth::user();


        if ($user->hasRole('Admin')) {


            return view('home', );

        } elseif ($user->hasRole('Teacher')) {



            return view('home');

        } elseif ($user->hasRole('Parent')) {



            return view('home');

        } elseif ($user->hasRole('Student')) {



            return view('home');

        } else {
            return 'NO ROLE ASSIGNED YET!';
        }

    }

    public function changePassword(Request $request)
    {
        Auth::logout();
        return redirect()->route('login');
    }
}
