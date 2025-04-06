<?php

namespace App\Http\Controllers;

use App\Models\Author;
use Illuminate\Http\Request;

class AuthorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
        ], [
            'first_name.required' => 'Yazar adı zorunludur.',
            'first_name.string' => 'Yazar adı metin olmalıdır.',
            'first_name.max' => 'Yazar adı en fazla 255 karakter olabilir.',
            'last_name.required' => 'Yazar soyadı zorunludur.',
            'last_name.string' => 'Yazar soyadı metin olmalıdır.',
            'last_name.max' => 'Yazar soyadı en fazla 255 karakter olabilir.',
        ]);

        Author::create($validated);

        return redirect()->route('admin.data.adding')
            ->with('success', 'Yazar başarıyla eklendi.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Author $author)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Author $author)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Author $author)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Author $author)
    {
        //
    }
}
