<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
class BaseController extends Controller
{
    public function __construct()
    {
        if (!Auth::check() && request()->route()->getName() != 'login') {
            redirect()->route('login')->withErrors([
                'session' => 'Sesi Anda sudah habis, silakan login kembali.',
            ])->send();
            exit;
        }
        
        // Ambil menu dari procedure
        $menus = DB::select("CALL GetDashboardMenus_xx26()");
        if (empty($menus)) {
            View::share('menuData', []);
            return;
        }

        $menus = array_map(function ($menu) {
            $menu->submenus = json_decode($menu->submenus, true);
            return $menu;
        }, $menus);

        // Share ke semua view
        View::share('menuData', $menus);
    }
}
