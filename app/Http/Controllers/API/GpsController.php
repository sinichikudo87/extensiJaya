<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GpsController extends Controller
{
    public function save(Request $request)
    {
        $request->validate([
            'imei' => 'required|string|max:20',
            'lat'  => 'required|numeric',
            'lng'  => 'required|numeric',
            'speed' => 'nullable|integer',
            'time' => 'required|date',
        ]);

        try {
            DB::statement("CALL InsertGPSLocation_xx25(?, ?, ?, ?, ?)", [
                $request->imei,
                $request->lat,
                $request->lng,
                $request->speed ?? 0,
                $request->time
            ]);

            return response()->json([
                'success' => true,
                'message' => 'GPS data saved successfully.'
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function getLastLocation()
    {
        try {
            $gpsData = DB::select('CALL GetAllGPSLocations_xx25()');

            return response()->json($gpsData); // langsung kirim array semua lokasi
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], 500);
        }
    }


}
