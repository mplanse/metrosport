<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DiaHora;

class DiaHoraController extends Controller
{
    public function store(Request $request)
    {
        $checkboxes = $request->input('checkboxes', []);

        foreach ($checkboxes as $hour => $days) {
            foreach ($days as $day => $value) {
                DiaHora::create([
                    'hour' => $hour,
                    'day' => $day,
                    'value' => $value,
                ]);
            }
        }

        return redirect()->back()->with('success', 'Datos guardados correctamente.');
    }
}
