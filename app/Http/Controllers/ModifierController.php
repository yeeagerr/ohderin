<?php

namespace App\Http\Controllers;

use App\Models\Modifier;
use Illuminate\Http\Request;

class ModifierController extends Controller
{
    public function index()
    {
        $modifiers = Modifier::latest()->paginate(10);
        return view('dashboard.modifiers', compact('modifiers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:add,remove,level',
            'category' => 'nullable|string|max:255',
            'price_adjustment' => 'required|numeric',
            'is_active' => 'nullable|boolean',
        ]);

        Modifier::create([
            'name' => $request->name,
            'type' => $request->type,
            'category' => $request->category,
            'price_adjustment' => $request->price_adjustment,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('modifiers.index')->with('success', 'Modifier berhasil ditambahkan!');
    }

    public function update(Request $request, Modifier $modifier)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:add,remove,level',
            'category' => 'nullable|string|max:255',
            'price_adjustment' => 'required|numeric',
            'is_active' => 'nullable|boolean',
        ]);

        $modifier->update([
            'name' => $request->name,
            'type' => $request->type,
            'category' => $request->category,
            'price_adjustment' => $request->price_adjustment,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('modifiers.index')->with('success', 'Modifier berhasil diperbarui!');
    }

    public function destroy(Modifier $modifier)
    {
        $modifier->delete();
        return redirect()->route('modifiers.index')->with('success', 'Modifier berhasil dihapus!');
    }
}
