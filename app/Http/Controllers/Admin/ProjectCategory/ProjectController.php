<?php

namespace App\Http\Controllers\Admin\ProjectCategory;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use App\Http\Controllers\BaseController;

class ProjectController extends BaseController
{
    public function index(Request $request)
    {
        $allProjects = collect(\DB::select('CALL getProjectsCategory_xx26()'));
        
        $perPage = 10;
        $currentPage = $request->input('page', 1);
        $currentItems = $allProjects->slice(($currentPage - 1) * $perPage, $perPage)->all();

        $projects = new LengthAwarePaginator(
            $currentItems, 
            $allProjects->count(), 
            $perPage, 
            $currentPage, 
            ['path' => $request->url(), 'query' => $request->query()]
        );

        return view('admin.project-category.index', compact('projects'));
    }

    public function store(Request $request)
    {
        try {
            // ✅ VALIDASI SESUAI TABLE
            $request->validate([
                'id' => 'nullable|integer',
                'name' => 'required|string|max:255',
                'status' => 'required|in:active,non-active',
            ]);

            // ✅ CALL STORED PROCEDURE UPSERT
            DB::statement('CALL upsertProjectsCategory_xx26(?,?,?)', [
                $request->id,
                $request->name,
                $request->status
            ]);

            return response()->json([
                'status' => 'success',
                'message' => $request->id ? 'Data berhasil diupdate' : 'Data berhasil ditambahkan'
            ]);

        } catch (\Throwable $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

}
