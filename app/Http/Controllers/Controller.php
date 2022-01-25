<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected function failJsonMessage ($msgText,$extra=[])
    {
        return array_merge(['ok' => false,'msg' => $msgText],$extra);
    }

    protected function successJsonMessage ($data=null)
    {
        if ($data) { return ['ok' => true,'data' => $data]; }
        return ['ok' => true];
    }
}
