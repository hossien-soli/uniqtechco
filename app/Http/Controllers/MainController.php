<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MainController extends Controller
{
    public function index ()
    {
        $title = "یونیتک";
        return view('main.common',compact('title'));
    }

    public function products ()
    {
        
    }

    public function import ()
    {

    }
}
