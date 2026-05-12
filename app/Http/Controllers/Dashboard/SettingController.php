<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        $settings = Setting::all()->pluck('value', 'key')->toArray();
        return view('dashboard.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        // For now, we handle specific checkboxes
        $booleanKeys = [
            'require_recipe_to_sell_product',
            // Add other keys here dynamically later
        ];

        foreach ($booleanKeys as $key) {
            Setting::updateOrCreate(
                ['key' => $key],
                [
                    'value' => $request->has($key) ? '1' : '0',
                    'type' => 'boolean'
                ]
            );
        }

        // Add regular text settings logic here if needed

        return redirect()->route('settings.index')->with('success', 'Pengaturan berhasil diperbarui.');
    }
}
