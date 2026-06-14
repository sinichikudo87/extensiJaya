<?php

namespace App\Http\Controllers\Admin\ProjectFinish;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use App\Http\Controllers\BaseController;

class ProjectFinishController extends BaseController
{
    public function index(Request $request)
    {
        // 1. Ambil data yang SUDAH TERFILTER dari database
        $allProjects = collect(DB::select('CALL GetFinishedProjectsManagement_xx26()'));

        // 2. Atur komponen Pagination
        $perPage = 10;
        $currentPage = (int) $request->input('page', 1);

        // 3. Potong data sesuai halaman aktif
        $currentItems = $allProjects
            ->slice(($currentPage - 1) * $perPage, $perPage)
            ->values() // Pastikan indeksnya reset menjadi 0, 1, 2...
            ->all();

        // 4. Buat Paginator Laravel
        $projects = new LengthAwarePaginator(
            $currentItems,
            $allProjects->count(), // Total data pasti akurat dari SQL (contoh: 100)
            $perPage,
            $currentPage,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        return view('admin.project-finish.index', compact('projects'));
    }

    public function getHistory($id)
    {
        try {
            $history = DB::select('CALL sp_get_project_progress_history(?)', [$id]);

            return response()->json([
                'status' => 'success',
                'data' => $history
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
