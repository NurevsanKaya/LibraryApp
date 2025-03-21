<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index()
    {
        $users = User::where('role_id', 2) // Sadece normal kullanıcıları getir
                     ->orderBy('created_at', 'desc')
                     ->paginate(10);

        return view('admin.users.index', compact('users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'phone' => ['nullable', 'string', 'max:20'],
            'is_active' => ['required', 'boolean'],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone' => $request->phone,
            'is_active' => $request->is_active,
            'role_id' => 2, // Normal kullanıcı rolü
        ]);

        return redirect()->route('admin.users.index')
            ->with('success', 'Kullanıcı başarıyla eklendi.');
    }
    
    public function edit($id)
    {
        $user = User::findOrFail($id);
        return response()->json($user);
    }
    
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'phone' => ['nullable', 'string', 'max:20'],
            'is_active' => ['required', 'boolean'],
        ];
        
        // Şifre girilmişse doğrulama kurallarını ekle
        if ($request->filled('password')) {
            $rules['password'] = ['string', 'min:8', 'confirmed'];
        }
        
        $validated = $request->validate($rules);
        
        // Güncellenecek verileri hazırla
        $userData = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'is_active' => $validated['is_active'],
        ];
        
        // Şifre girilmişse güncelle
        if ($request->filled('password')) {
            $userData['password'] = Hash::make($request->password);
        }
        
        $user->update($userData);
        
        return redirect()->route('admin.users.index')
            ->with('success', 'Kullanıcı başarıyla güncellendi.');
    }
} 