<?php

namespace App\Http\Controllers\Admin\Company;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\BaseController;

class ProfileController extends BaseController
{
    public function index()
    {
        $results = DB::select('CALL SelectCompanies_xx26(?)', [1]);
        $company = !empty($results) ? $results[0] : null;

        return view('admin.company.index', compact('company'));
    }

    public function store(Request $request)
    {
        $id = 1;

        $request->validate([
            'name' => 'required|max:200',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        try {
            DB::beginTransaction(); // Tambahkan transaction agar data aman

            $fileName = null;

            // ✅ Upload logo
            if ($request->hasFile('logo')) {
                $file = $request->file('logo');
                
                // Menggunakan public_path() agar otomatis masuk ke folder 'public/images' 
                // folder ini berada di: /home/upwx2196/app_test/public/images
                $path = public_path('images');

                if (!file_exists($path)) {
                    mkdir($path, 0755, true);
                }

                // Beri nama unik agar tidak tertimpa
                $fileName = 'logo_' . time() . '.' . $file->getClientOriginalExtension();
                
                // Pindahkan file
                $file->move($path, $fileName);
                
                // Opsional: Hapus foto lama jika ada di server untuk menghemat ruang
                $oldLogo = DB::table('companies_dash_xx26')->where('id', $id)->value('logo');
                if ($oldLogo && file_exists($path . '/' . $oldLogo)) {
                    @unlink($path . '/' . $oldLogo);
                }
            }

            // Jika tidak upload baru, pakai nama file yang lama
            if (!$fileName) {
                $fileName = DB::table('companies_dash_xx26')
                    ->where('id', $id)
                    ->value('logo');
            }

            // Jalankan Stored Procedure
            DB::statement("CALL UpsertCompanyProfile_xx26(
                ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?
            )", [
                $id,
                $request->name,
                $request->alias_name,
                $request->owner_name,
                $request->permit_number,
                $request->account_number,
                $request->email,
                $request->phone,
                $request->owner_phone,
                $request->address,
                $request->city,
                $fileName, // Nama file tersimpan di DB
                $request->bank_name,
                $request->npwp,
                $request->ppn ?? 0,
                $request->latitude,
                $request->longitude,
                $request->coverage ?? 0,
                $request->has('is_active') ? 1 : 0
            ]);

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Data perusahaan berhasil disimpan 🚀',
                'logo_url' => asset('images/' . $fileName)
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal simpan: ' . $e->getMessage()
            ], 500);
        }
    }

}