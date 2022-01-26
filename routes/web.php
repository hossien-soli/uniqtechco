<?php

use Illuminate\Support\Facades\Route;

Route::as('main.')->group(function () {
    Route::get('/',['as' => 'index','uses' => 'MainController@index']);
    Route::get('/products',['as' => 'products','uses' => 'MainController@products']);
    Route::get('/import',['as' => 'import','uses' => 'MainController@import']);
});

Route::prefix('/ajax')->group(function () {
    Route::post('/import-product','AjaxController@importProduct');
    Route::get('/import-product/check-session','AjaxController@importProductCheckSession');
});