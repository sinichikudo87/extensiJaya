<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;

class LoginController extends Controller
{
    public function index()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string|min:6',
        ]);

        $result = collect(DB::select('CALL GetUserByEmail_xx26(?)', [$request->email]));

        if ($result->isEmpty()) {
            return back()->withErrors([
                'login' => 'Email atau password salah',
            ])->withInput();
        }

        $userData = $result->first();

        // Password awal
        if (Hash::check('000000', $userData->user_password)) {
            session([
                'user_temp_email' => $userData->user_email,
                'user_temp_id'    => $userData->user_id,
            ]);

            return redirect()
                ->route('login.change-password')
                ->with('info', 'Silakan ubah password awal Anda.');
        }

        // Cek password
        if (!Hash::check($request->password, $userData->user_password)) {
            return back()->withErrors([
                'email' => 'Email atau password salah',
            ])->withInput();
        }

        // Cek user aktif
        if ($userData->user_is_active == 0) {
            return back()->withErrors([
                'email' => 'Akun Anda tidak aktif, silakan hubungi admin.',
            ])->withInput();
        }

        // Login Auth
        $user = User::find($userData->user_id);
        if (!$user) {
            return back()->withErrors([
                'email' => 'User tidak ditemukan di sistem.',
            ])->withInput();
        }

        Auth::login($user, $request->has('remember'));

        // =========================
        // SESSION
        // =========================
        session([
            'user_id'         => $userData->user_id,
            'email'           => $userData->user_email,
            'division_id'     => $userData->divisions_id,
            'role_id'         => $userData->role_id,
            'role_name'       => $userData->role_name,            
            'employee_code'   => $userData->employee_code,
            'employee_name'   => $userData->employee_name
        ]);

        // =========================
        // ROUTING
        // =========================
        $route = 'dashboard';

        return redirect()->route($route)
            ->with('success', 'Login berhasil, selamat datang ' . $userData->user_email);
    }

    public function redirectToDashboard()
    {
        return redirect('/dashboard');
    }

    // Logout
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->flush();
        $request->session()->regenerateToken();
        Cookie::queue(Cookie::forget('remember_web'));
        return redirect()->route('login')->with('success', 'Berhasil logout');
    }
    
    public function updatePassword(Request $request)
    {
        $email = session('user_temp_email');

        $user = \App\Models\User::where('email', $email)->first();

        if ($user) {
            $user->password = \Illuminate\Support\Facades\Hash::make($request->new_password);
            $user->save();
        }

        // hapus session biar nggak dipakai lagi
        session()->forget(['user_temp_email', 'user_temp_id']);

        return redirect()->route('login');
    }

    public function changePasswordView(Request $request)
    {
        return view('auth.change-password');
    }
}