<?php

namespace App\Http\Controllers\Admin\ProjectEntry;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Crypt;
use App\Http\Controllers\BaseController;

class ProjectController extends BaseController
{
    public function index(Request $request)
    {
        // 1. Ambil semua data dari Stored Procedure
        $allProjects = collect(\DB::select('CALL GetAllProjectsManagement_xx26()'));

        // 2. Ambil bulan dan tahun saat ini
        $currentMonth = date('m'); // Format: 01 - 12
        $currentYear = date('Y');  // Format: 2026

        // 3. Filter data di tingkat Controller (Progress = 0 & Bulan Sekarang)
        $filteredProjects = $allProjects->filter(function ($project) use ($currentMonth, $currentYear) {
            if (empty($project->start_date)) {
                return false;
            }

            $projectTimestamp = strtotime($project->start_date);
            $projectMonth = date('m', $projectTimestamp);
            $projectYear = date('Y', $projectTimestamp);

            // Hanya lolos jika progress 0 DAN (bulan & tahun cocok)
            return (int)$project->progress === 0 
                && $projectMonth === $currentMonth 
                && $projectYear === $currentYear;
        });

        // 4. Setup Konfigurasi Paginasi
        $perPage = 10;
        $currentPage = (int) $request->input('page', 1);

        // 5. Potong data yang sudah terfilter sesuai halaman aktif
        $currentItems = $filteredProjects
            ->slice(($currentPage - 1) * $perPage, $perPage)
            ->values()
            ->all();

        // 6. Buat Paginator dengan total dari data yang sudah difilter
        $projects = new \Illuminate\Pagination\LengthAwarePaginator(
            $currentItems,
            $filteredProjects->count(),
            $perPage,
            $currentPage,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        $allProjectsCategory = collect(\DB::select('CALL getProjectsCategory_xx26()'));

        return view('admin.project-entry.index', compact(
            'projects',
            'allProjectsCategory'
        ));
    }

    public function store(Request $request)
    {
        try {
            // ✅ VALIDASI
            $request->validate([
                'project_category_id' => 'required|string|max:255',
                'project_name' => 'required|string|max:255',
                'start_date' => 'nullable|date',
                'end_date' => 'nullable|date',
                'budget' => 'nullable|numeric',
                'priority' => 'nullable|in:low,medium,high',
                'pm_name' => 'nullable|string|max:255',
                'description' => 'nullable|string',
                'thumbnail' => 'nullable|image|max:2048'
            ]);

            // ✅ UPLOAD KE public/image
            $thumbnailPath = null;

            if ($request->hasFile('thumbnail')) {
                $file = $request->file('thumbnail');

                $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();

                $file->move(public_path('image'), $filename);

                // simpan path ke DB
                $thumbnailPath = 'image/' . $filename;
            }

            // ✅ CALL STORED PROCEDURE
            $result = DB::select('CALL UpsertProject_xx26(?,?,?,?,?,?,?,?,?,?,?,?,?,?)', [
                null,
                $request->project_category_id,
                $request->project_name,
                null,
                $thumbnailPath,
                $request->start_date,
                $request->end_date,
                $request->budget ?? 0,
                0,
                $request->priority ?? 'medium',
                $request->pm_name,
                null,
                $request->description,
                'planned'
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Data perusahaan berhasil disimpan'
            ]);

        } catch (\Throwable $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 500);
        }
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

    public function updateProgress(Request $request)
    {
        // 1. Validasi Input
        $request->validate([
            'project_id'    => 'required|exists:project_progress_details_xx26,id',
            'progress_val'  => 'required|integer|min:0|max:100',
            'status_update' => 'required|in:planned,on-progress,on-hold,completed',
            'notes'         => 'nullable|string'
        ]);

        try {
            $projectId = $request->project_id;
            $userId = 1;
            $progress = (int) $request->progress_val;
            $status = $request->status_update;
            $notes = $request->notes;

            if ($progress === 100) {
                $status = 'completed';
            }

            $result = DB::select('CALL InsertProjectProgress_xx26(?, ?, ?, ?, ?)', [
                $projectId,
                $userId,
                $progress,
                $status,
                $notes
            ]);

            return response()->json([
                'status'  => 'success',
                'message' => 'Progress updated successfully!',
                'data'    => [
                    'new_id' => $result[0]->new_detail_id ?? null,
                    'final_status' => $status
                ]
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Failed to update progress: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $decryptedId = decrypt($id);

            DB::statement('CALL UpdateProjectStatus_xx26(?, ?)', [
                $decryptedId,
                'on-hold'
            ]);

            return response()->json([
                'message' => 'Project diubah ke On Hold'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'ID tidak valid'
            ], 400);
        }
    }
}
