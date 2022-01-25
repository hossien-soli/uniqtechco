<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class AjaxController extends Controller
{
    public function importProduct (Request $request)
    {
        $digikalaLink = $request->input('digikala_link');
        $validate = $digikalaLink && mb_strlen($digikalaLink) <= 1500;
        if (!$validate) {
            return $this->failJsonMessage('اطلاعات ورودی نامعتبر هستند!');
        }
        
        $response = Http::get($digikalaLink);
        if (!$response->successful()) {
            return $this->failJsonMessage('مشکلی در هنگام ارتباط با دیجی کالا پیش آمد. لطفا دوباره تلاش کنید!');
        }

        $pageSource = $response->body();
        
    }
}
