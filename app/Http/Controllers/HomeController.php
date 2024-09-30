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
        return view('home');
    }

    public function changePassword(Request $request)
    {
        Auth::logout();
        return redirect()->route('login');
    }
}
