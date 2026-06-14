<?php

namespace App\Http\Controllers\Admin\RoleAkses;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\BaseController;

class SetRoleController extends BaseController
{
    public function index()
    {
        $roles = DB::select('CALL GetAllRolesSummary_xx26()');

        return view('admin.role-akses.index', compact('roles'));
    }

    public function store(Request $request, $id = null)
    {
        $request->validate([
            'role_name'    => 'required|string|max:50',
            'access_level' => 'required|integer|between:0,99',
            'is_active'    => 'nullable',
        ]);

        try {
            DB::beginTransaction();
            $roleResult = DB::select('CALL UpsertRole_xx26(?, ?, ?, ?, ?, ?, ?)', [
                $id,
                $request->role_name,
                $request->access_level,
                $request->ip_requirements,
                $request->access_start ?? '08:00:00',
                $request->access_end ?? '17:00:00',
                $request->has('is_active') ? 1 : 0
            ]);

            $roleId = $roleResult[0]->role_id;

            $permissions = $request->input('permissions', []);
            $allModules = DB::table('modules_xx26')->get();

            foreach ($allModules as $module) {
                $mId = $module->id;
                $canCreate = isset($permissions[$mId]['C']) ? 1 : 0;
                $canRead   = isset($permissions[$mId]['R']) ? 1 : 0;
                $canUpdate = isset($permissions[$mId]['U']) ? 1 : 0;
                $canDelete = isset($permissions[$mId]['D']) ? 1 : 0;
                DB::statement('CALL UpsertPermission_xx26(?, ?, ?, ?, ?, ?)', [
                    $roleId,
                    $mId,
                    $canCreate,
                    $canRead,
                    $canUpdate,
                    $canDelete
                ]);
            }

            DB::commit();
            return redirect()->back()->with('success', 'Role and Access Control updated successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'System Error: ' . $e->getMessage());
        }
    }

}