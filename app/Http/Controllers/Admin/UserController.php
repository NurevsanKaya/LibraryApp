<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        $users = User::where('role_id', 2) // Sadece normal kullanıcıları getir
                     ->orderBy('created_at', 'desc')
                     ->paginate(10);

        return view('admin.users.index', compact('users'));
    }
} 