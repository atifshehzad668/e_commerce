<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index()
    {
        // $admin = Auth::guard('admin')->user();
        // echo "welcome" . $admin->name . '<a href="' . route('admin.logout') . '">logout</a>';
        return view('admin.dashboard');
    }

    public function logout()
    {
        $admin = Auth::guard('admin')->logout();
        return redirect()->route('admin.login');
    }
}
