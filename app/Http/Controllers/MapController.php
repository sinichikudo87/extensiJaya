<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Http\Controllers\BaseController;
use Illuminate\Support\Facades\Auth;

class MapController extends BaseController
{
    public function index()
    {
        return view('maps/index');
    }
}