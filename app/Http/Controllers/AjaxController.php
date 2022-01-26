<?php

namespace App\Http\Controllers;

use App\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;

// use PHPHtmlParser\Dom;

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
        if (!$pageSource) {
            return $this->failJsonMessage('مشکلی در هنگام ارتباط با دیجی کالا پیش آمد. لطفا دوباره تلاش کنید!');
        }

        // $dom = new Dom;
        // $dom->loadStr($pageSource);
        // $scripts = $dom->find('img')[0];
        // dd($scripts);

        $totalStrLen = mb_strlen($pageSource);
        $pos1 = mb_strpos($pageSource,'"@type": "Product"');
        if (!$pos1) {
            return $this->failJsonMessage('هیچ فیلمی در لینک داده شده یافت نشد!');
        }
        $pos1 -= mb_strlen('"@context": "https://www.schema.org"');
        $pos1 -= 7;

        $pos2 = mb_strpos($pageSource,'"@type": "Rating"');
        if (!$pos2) {
            return $this->failJsonMessage('هیچ فیلمی در لینک داده شده یافت نشد!');
        }

        $endStr = mb_substr($pageSource,$pos2,$totalStrLen-$pos2-5);
        $pos3 = mb_strpos($endStr,'"worstRating"');
        if (!$pos3) {
            return $this->failJsonMessage('هیچ فیلمی در لینک داده شده یافت نشد!');
        }

        $count = mb_strlen(mb_substr($endStr,0,$pos3)) + mb_strlen('"worstRating"') + 4;
        $json = '{' . mb_substr($pageSource,$pos1,$pos2 - $pos1 + $count) . '}}}';
        $json = json_decode($json,true);
        if (!$json) {
            return $this->failJsonMessage('هیچ فیلمی در لینک داده شده یافت نشد!');
        }

        $productName = isset($json['name']) ? $json['name'] : null;
        $productAlternateName = isset($json['alternateName']) ? $json['alternateName'] : null;
        $imageArr = isset($json['image']) ? $json['image'] : null;
        $productDescription = isset($json['description']) ? $json['description'] : null;

        $productPrice = isset($json['offers']) && isset($json['offers']['highPrice']) ? $json['offers']['highPrice'] : null;
        if (!$productPrice) {
            $productPrice = isset($json['offers']) && isset($json['offers']['lowPrice']) ? $json['offers']['lowPrice'] : null;
        }

        $request->session()->put('import_product',[
            'name' => $productName,
            'alternate_name' => $productAlternateName,
            'image_arr' => $imageArr,
            'description' => $productDescription,
            'price' => $productPrice,
            'digikala_link' => $digikalaLink,
        ]);

        return $this->successJsonMessage([
            'name' => $productName ? $productName : '',
            'alternate_name' => $productAlternateName ? $productAlternateName : '',
            'image_arr' => $imageArr ? $imageArr : [],
            'description' => $productDescription ? $productDescription : '',
            'price' => $productPrice ? $productPrice : '',
        ]);
    }

    public function importProductCheckSession (Request $request)
    {
        if ($request->session()->has('import_product')) {
            $importProduct = $request->session()->get('import_product');
            return $this->successJsonMessage([
                'has' => true,
                'info' => [
                    'name' => $importProduct['name'] ? $importProduct['name'] : '',
                    'alternate_name' => $importProduct['alternate_name'] ? $importProduct['alternate_name'] : '',
                    'image_arr' => $importProduct['image_arr'] ? $importProduct['image_arr'] : [],
                    'description' => $importProduct['description'] ? $importProduct['description'] : '',
                    'price' => $importProduct['price'] ? $importProduct['price'] : '',
                ],
                'digikala_link' => $importProduct['digikala_link'],
            ]);
        }

        return $this->successJsonMessage(['has' => false]);
    }

    public function importProductFinalize (Request $request)
    {
        $productName = $request->input('product_name');
        $productAlternateName = $request->input('product_alternate_name');
        $productDescription = $request->input('product_description');
        $productPrice = $request->input('product_price');
        $imageFilter = $request->input('image_filter');
        $alsoPublish = $request->input('also_publish');

        $validationRules = [
            'product_name' => 'required|string|max:255',
            'product_price' => 'required|numeric',
        ];
        if ($productAlternateName) { $validationRules['product_alternate_name'] = 'string|max:255'; }
        if ($productDescription) { $validationRules['product_description'] = 'string|max:2000'; }
        $validation = Validator::make($request->all(),$validationRules);
        if ($validation->fails()) {
            return $this->failJsonMessage($validation->errors()->all()[0]);
        }

        $importProduct = $request->session()->get('import_product');
        if (!$importProduct) {
            return $this->failJsonMessage('جلسه ثبت محصول یافت نشد!',['reset' => true]);
        }

        $productUniqId = 'p' . time();
        $product = Product::create([
            'uniq_id' => $productUniqId,
            'name' => $productName,
            'alternate_name' => $productAlternateName ? $productAlternateName : null,
            'description' => $productDescription ? $productDescription : null,
            'price' => $productPrice,
            'link_on_digikala' => $importProduct['digikala_link'],
            'status' => $alsoPublish == 1 ? 1 : 0,
        ]);

        if (!file_exists('product_images')) { mkdir('product_images'); }
        mkdir('product_images/'.$productUniqId);

        $imageArr = $importProduct['image_arr'] ? $importProduct['image_arr'] : [];
        if ($imageFilter) {
            $imageFilter = explode('#',$imageFilter);
        }
        else { $imageFilter = []; }

        if (count($imageArr) >= 1) {
            for ($i=0;$i<count($imageArr);$i++) {
                if (count($imageFilter) >= 1) {
                    if (isset($imageFilter[$i]) && !$imageFilter[$i]) { continue; }   
                }

                $imageData = Http::get($imageArr[$i]);
                if ($imageData->successful() && $imageData) {
                    $imageLocalName = uniqid();
                    file_put_contents('product_images/'.$productUniqId.'/'.$imageLocalName,$imageData);
                    $product->images()->create([
                        'name' => $imageLocalName,
                        'link_on_digikala' => $imageArr[$i],
                    ]);
                }
            }
        }

        $request->session()->forget('import_product');
        return $this->successJsonMessage();
    }

    public function importProductCancel (Request $request)
    {
        if ($request->session()->has('import_product')) {
            $request->session()->forget('import_product');
        }

        return $this->successJsonMessage();
    }
}
