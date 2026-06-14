<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\BaseController;

class DashboardController extends BaseController
{
    public function index()
    {
        $result = DB::select('CALL sp_get_project_summary()');
        $summary = $result[0] ?? null;

        $projects = DB::table('projects_management_xx26 as p')
            ->leftJoin('projects_category_xx26 as c', 'c.id', '=', 'p.category_project_id')
            ->leftJoin('project_progress_details_xx26 as d', function ($join) {
                $join->on('d.project_id', '=', 'p.id')
                    ->whereRaw('d.created_at = (
                        SELECT MAX(created_at)
                        FROM project_progress_details_xx26
                        WHERE project_id = p.id
                    )');
            })
            ->leftJoin('users as u', 'u.id', '=', 'd.user_id')
            ->whereNull('p.deleted_at')
            ->orderByDesc('d.created_at')
            ->select([
                'p.id',
                'p.name',
                'p.code',
                'p.status',
                'p.progress',
                'p.priority',
                'p.pm_name',
                'c.name as category_name',
                'd.created_at as last_update',
                'd.progress_val as last_progress',
                'd.status_update as last_status',
                'd.notes as last_notes',
                'u.email as updated_by'
            ])
            ->get();

        return view('dashboards.index', [
            'summary' => $summary,
            'projects' => $projects
        ]);
    }

    public function getSummaryApi()
    {
        $summaries = DB::select('CALL GetProjectSummary_xx26()');

        return response()->json([
            'success' => true,
            'data' => $summaries
        ]);
    }
}
