<?php

namespace App\Http\Controllers\Admin\Employee;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\BaseController;

class EmployeeController extends BaseController
{
    public function index(Request $request)
    {
        $search = $request->query('search');
        $status = $request->query('status');

        $employees = collect(DB::select(
            "CALL SelectEmployees_xx26(?, ?)",
            [$search ?: null, $status ?: null]
        ));

        // PAGINATION
        $paginatedData = $this->paginate($employees, 10);

        // GROUPING
        $groupedEmployees = collect($paginatedData->items())
            ->groupBy(function ($item) {
                return strtoupper(substr($item->name, 0, 1));
            })
            ->sortKeys();

        $division = DB::select('CALL GetDivisions_xx26(?)', [null]);
        $divisions = collect($division);
        $roles = collect(DB::select('CALL GetAllRolesSummary_xx26()'));
        
        return view('admin.employee.index', [
            'groupedEmployees' => $groupedEmployees,
            'paginatedData'    => $paginatedData,
            'search'           => $search,
            'status'           => $status,
            'divisions'        => $divisions,
            'roles'            => $roles
        ]);
    }
    
    private function paginate($items, $perPage = 10, $page = null)
    {
        $page = $page ?: (\Illuminate\Pagination\Paginator::resolveCurrentPage() ?: 1);
        return new \Illuminate\Pagination\LengthAwarePaginator(
            $items->forPage($page, $perPage),
            $items->count(),
            $perPage,
            $page,
            ['path' => \Illuminate\Pagination\Paginator::resolveCurrentPath()]
        );
    }

    public function store(Request $request)
    {
        try {
            DB::beginTransaction();

            try {
                $request->validate([
                    'employee_code' => 'required',
                    'name'          => 'required',
                    'email'         => 'required|email|unique:users,email,' . ($request->users_id ?? 'NULL'),
                    'role_id'       => 'required|exists:roles_xx26,id',
                    'division_id'   => 'required|exists:divisions_xx26,id',
                    'salary'        => 'required|numeric',
                    'join_date'     => 'required|date',
                ]);
            } catch (ValidationException $e) {
                return response()->json([
                    'success' => false,
                    'errors' => $e->errors()
                ], 422);
            }

            if ($request->users_id) {
                $password = DB::table('users')
                    ->where('id', $request->users_id)
                    ->value('password');
            } else {
                $password = Hash::make('000000');
            }

            DB::select('CALL UpsertEmployeeUser_xx26(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)', [
                $request->users_id ?? 0,
                $request->id ?? 0,
                $request->role_id,
                $request->division_id,
                $request->email,
                $password,
                $request->employee_code,
                $request->name,
                $request->phone,
                $request->address,
                $request->city,
                $request->join_date,
                $request->salary,
                $request->status ?? 'active'
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Data tersimpan ! 🚀'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function showEdit($id)
    {
        try {
            $data = DB::select('CALL GetEmployeeById_xx26(?)', [$id]);

            if (empty($data)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data tidak ditemukan'
                ]);
            }

            return response()->json([
                'success' => true,
                'data' => $data[0]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error ambil data',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function delete($id)
    {
        try {
            DB::statement('CALL UpdateEmployeeStatus_xx26(?)', [$id]);

            return response()->json([
                'success' => true,
                'message' => 'Karyawan berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}