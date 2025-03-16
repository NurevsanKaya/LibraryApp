<?php

namespace App\Http\Controllers;

use App\Models\Publisher;
use Illuminate\Http\Request;

class PublisherController extends Controller
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
            'name' => 'required|string|max:255|unique:publishers,name',
        ], [
            'name.required' => 'Yayınevi adı zorunludur.',
            'name.string' => 'Yayınevi adı metin olmalıdır.',
            'name.max' => 'Yayınevi adı en fazla 255 karakter olabilir.',
            'name.unique' => 'Bu yayınevi adı zaten kullanılıyor.',
        ]);

        Publisher::create($validated);

        return redirect()->route('admin.data.adding')
            ->with('success', 'Yayınevi başarıyla eklendi.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Publisher $publisher)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Publisher $publisher)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Publisher $publisher)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Publisher $publisher)
    {
        //
    }
}
