<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;

class BaseController extends Controller
{
    public function __construct()
    {
        // Ambil menu dari procedure
        $menus = DB::select("CALL GetDashboardMenus_xx25()");

        // Jika tidak ada menu, tetap share array kosong
        if (empty($menus)) {
            View::share('menuData', []);
            return;
        }

        // Decode submenu JSON
        $menus = array_map(function ($menu) {
            $menu->submenus = json_decode($menu->submenus, true);
            return $menu;
        }, $menus);

        // Share ke semua view
        View::share('menuData', $menus);
    }
}
