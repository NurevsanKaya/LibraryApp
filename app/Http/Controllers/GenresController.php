<?php

namespace App\Http\Controllers;

use App\Models\Genres;
use Illuminate\Http\Request;

class GenresController extends Controller
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
            'name' => 'required|string|max:255|unique:genres,name',
        ], [
            'name.required' => 'Tür adı zorunludur.',
            'name.string' => 'Tür adı metin olmalıdır.',
            'name.max' => 'Tür adı en fazla 255 karakter olabilir.',
            'name.unique' => 'Bu tür adı zaten kullanılıyor.',
        ]);

        Genres::create($validated);

        return redirect()->route('admin.metadata')
            ->with('success', 'Tür başarıyla eklendi.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Genres $genres)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Genres $genres)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Genres $genres)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Genres $genres)
    {
        //
    }
}
