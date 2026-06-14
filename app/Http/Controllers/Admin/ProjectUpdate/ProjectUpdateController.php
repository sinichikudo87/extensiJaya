<?php

namespace App\Http\Controllers\Admin\ProjectUpdate;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use App\Events\NewNotification;
use Illuminate\Support\Facades\Crypt;
use App\Http\Controllers\BaseController;

class ProjectUpdateController extends BaseController
{
    public function index(Request $request)
    {
        $allProjects = collect(DB::select('CALL GetAllProjectsManagement_xx26()'));

        $perPage = 10;
        $currentPage = $request->input('page', 1);

        $currentItems = $allProjects
            ->where('progress', '<', 100)
            ->slice(($currentPage - 1) * $perPage, $perPage)
            ->values()
            ->all();

        $projects = new LengthAwarePaginator(
            $currentItems,
            $allProjects->count(),
            $perPage,
            $currentPage,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        return view('admin.project-update.index', compact(
            'projects'
        ));
    }

    public function chartSettings()
    {
        $data = DB::select('CALL GetChartSettings_xx26(?)', [0]);

        return response()->json($data);
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
        $request->validate([
            'project_id'    => 'required|exists:projects_management_xx26,id',
            'progress_val'  => 'required|integer|min:0|max:100',
            'status_update' => 'required|in:planned,on-progress,on-hold,completed',
            'notes'         => 'required|string',
            'additional_cost' => 'nullable|numeric|min:0',
            'chart_setting_id' => 'nullable|exists:chart_settings_xx26,id',
        ]);

        try {
            $projectId = $request->project_id;
            $userId    = session('user_id') ?? 1;
            $progress  = (int) $request->progress_val;
            $status    = $request->status_update;
            $notes     = $request->notes;
            $cost      = $request->additional_cost ?? 0;
            $chartSettingId = $request->chart_setting_id;
            if ($progress === 100) {
                $status = 'completed';
            }

            $result = DB::select('CALL InsertProjectProgress_xx26(?, ?, ?, ?, ?, ?, ?)', [
                $projectId,
                $userId,
                $progress,
                $status,
                $notes,
                $cost,
                $chartSettingId
            ]);

            $project = DB::table('projects_management_xx26')->where('id', $projectId)->first();

            $message = "Project {$project->name} telah diperbarui dengan progress {$progress}% oleh " . session('employee_name');

            if ($progress == 100) {
                $message = "Project {$project->name} telah selesai oleh " . session('employee_name');
            }

            DB::select('CALL InsertNotification_xx26(?, ?, ?, ?, ?, ?)', [
                $projectId,
                $userId,
                'Update Project',
                $message,
                $cost,
                now()->format('H:i:s')
            ]);
            
            broadcast(new NewNotification(
                'Update Project',
                $message,
                $cost,
                now()->format('H:i:s')
            ));

            return response()->json([
                'status'  => 'success',
                'message' => 'Progress updated successfully!',
                'data'    => [
                    'new_id'       => $result[0]->new_detail_id ?? null,
                    'final_status' => $status,
                    'cost_logged'  => $cost
                ]
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Failed to update progress: ' . $e->getMessage()
            ], 500);
        }
    }

    public function projectProgress($id)
    {
        if (!is_numeric($id)) {
            return response()->json([
                'message' => 'Invalid ID'
            ], 400);
        }

        $id = (int) $id;
        $chat = DB::select(
            "CALL GetChatMessagesByProjectId_xx26(?)",
            [$id]
        );

        if (!empty($chat)) {
            return response()->json([
                'source' => 'chat',
                'data' => $chat
            ]);
        }

        $progress = DB::select(
            "CALL GetProjectProgressDetailsByProjectId_xx26(?)",
            [$id]
        );

        return response()->json([
            'source' => 'progress',
            'data' => $progress
        ]);
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
