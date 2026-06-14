<?php

namespace App\Http\Controllers\Admin\Report;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\BaseController;

class MonitoringController extends BaseController {
    public function index(Request $request)
    {
        $monitoringLogsRaw = DB::select('CALL GetProjectProgressHistory_xx26(?)', [0]);
        $monitoringLogs = collect($monitoringLogsRaw);

        // 2. Ambil setting tambahan
        $settings = \DB::select('CALL GetChartSettings_xx26(?)', [1]);
        $chartSetting = !empty($settings) ? $settings[0] : null;
        $allProjectsCategory = collect(\DB::select('CALL getProjectsCategory_xx26()'));

        // 3. Logika Pencarian (Independent)
        $search = $request->input('search');
        if ($search) {
            $monitoringLogs = $monitoringLogs->filter(function ($item) use ($search) {
                return str_contains(strtolower($item->project_name ?? ''), strtolower($search)) || 
                    str_contains(strtolower($item->project_code ?? ''), strtolower($search));
            });
        }

        if ($request->ajax()) {
            return view('admin.report.partials._monitoring_table', compact('monitoringLogs'))->render();
        }

        return view('admin.report.monitoring', compact('monitoringLogs', 'chartSetting', 'allProjectsCategory'));
    }

    public function getProjectsByCategory(Request $request)
    {
        $categoryId = $request->input('category_project_id');
        if ($categoryId === 'ALL') $categoryId = null;

        $projects = DB::select('CALL GetProjectsManagementByCategory_xx26(?)', [$categoryId]);

        return response()->json([
            'success' => true,
            'total' => count($projects),
            'data' => $projects
        ]);
    }
}