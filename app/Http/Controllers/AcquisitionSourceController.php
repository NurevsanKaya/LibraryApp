<?php

namespace App\Http\Controllers;

use App\Models\AcquisitionSource;
use Illuminate\Http\Request;

class AcquisitionSourceController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:acquisition_sources,name',
        ]);

        AcquisitionSource::create(['name' => $request->name]);

        return redirect()->route('admin.data-adding')->with('success', 'Edinme Kaynağı başarıyla eklendi.');
    }

}
