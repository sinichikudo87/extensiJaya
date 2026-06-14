<?php

namespace App\Http\Controllers\Admin\ChartColorSetting;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\BaseController;

class ChartController extends BaseController
{
    public function index()
    {
        $chartSettings = \DB::select("CALL GetChartSettings_xx26(?)", [0]);
        return view('admin.chart-color-setting.index', compact('chartSettings'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'color_1' => 'required|string|max:7',
            'description_1'  => 'required|string|max:100',

            'color_2' => 'required|string|max:7',
            'description_2'  => 'required|string|max:100',

            'color_3' => 'required|string|max:7',
            'description_3'  => 'required|string|max:100',
        ]);

        DB::beginTransaction();

        try {
            for ($i = 1; $i <= 3; $i++) {
                DB::statement("CALL UpsertChartSettings_xx26(?, ?, ?, ?)", [
                    $i, // id = 1,2,3 (anggap 3 row)
                    $request->input("color_$i"),
                    $request->input("description_$i"),
                    1
                ]);
            }

            DB::commit();

            return back()->with('success', 'Konfigurasi berhasil diperbarui!');
            
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
    }

}