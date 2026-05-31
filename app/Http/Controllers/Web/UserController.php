<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function index()
    {
        // 
    }

    public function getCounseleeList()
    {
        $title = 'Konseli';
        $users = User::where('role', 'konseli')->get();
        return view('users', compact('title', 'users'));
    }

    public function getCounselorList()
    {
        $title = 'Konselor';
        $users = User::where('role', 'konselor')->get();
        return view('users', compact('title', 'users'));
    }
}
