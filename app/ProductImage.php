<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductImage extends Model
{
    protected $fillable = ['name','main_image','link_on_digikala'];

    public function product ()
    {
        return $this->belongsTo(Product::class);
    }
}
