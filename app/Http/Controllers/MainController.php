<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MainController extends Controller
{
    private $app_data;

    public function __construct()
    {
        //load app_data.php file fromapp folder    
        $this->app_data = require(app_path('app_data.php'));
    }
    public function ShowData()
    {
        return response()->json($this->app_data)   ;
    }
}
