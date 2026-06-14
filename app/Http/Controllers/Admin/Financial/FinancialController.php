<?php

namespace App\Http\Controllers\Admin\Financial;

use App\Http\Controllers\BaseController;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;

class FinancialController extends BaseController
{
    public function index()
    {
        // 🔥 CALL SP
        $data = DB::select('CALL getProjectsWithProgress_xx26()');

        $collection = collect($data);

        // 🔥 GROUP BY PROJECT (ANTI DUPLICATE)
        $groupedFinancials = $collection->groupBy('project_id');

        // 🔥 DETAIL PER PROJECT
        $projectDetails = $collection->groupBy('project_id');

        // 🔥 PAGINATION (UNIQUE PROJECT ONLY)
        $uniqueProjects = $groupedFinancials->map(function ($items) {
            return $items->first();
        })->values();

        $page = request('page', 1);
        $perPage = 10;

        $paginatedData = new LengthAwarePaginator(
            $uniqueProjects->forPage($page, $perPage),
            $uniqueProjects->count(),
            $perPage,
            $page,
            [
                'path' => request()->url(),
                'query' => request()->query()
            ]
        );

        return view('admin.financial.index', compact(
            'groupedFinancials',
            'projectDetails',
            'paginatedData'
        ));
    }

    public function updateCost(Request $request)
    {
        // validasi input
        $request->validate([
            'id' => 'required|integer',
            'additional_cost' => 'required|numeric'
        ]);

        try {
            // call stored procedure
            DB::statement('CALL UpdateAdditionalCostById_xx26(?, ?)', [
                $request->id,
                $request->additional_cost
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Additional cost berhasil diupdate'
            ]);

        } catch (\Exception $e) {

            return response()->json([
                'success' => false,
                'message' => 'Gagal update data',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function updateBudget(Request $request, $id)
    {
        try {
            $request->validate([
                'budget' => 'required|numeric|min:0'
            ]);

            $budget = $request->budget;
            DB::statement('CALL UpdateProjectBudget_xx26(?, ?)', [
                $id,
                $budget
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Budget berhasil diupdate'
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {

            return response()->json([
                'success' => false,
                'message' => $e->validator->errors()->first()
            ], 422);

        } catch (\Exception $e) {

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
}