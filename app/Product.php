<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = ['uniq_id','name','alternate_name','description','price','link_on_digikala'];

    public function images ()
    {
        return $this->hasMany(ProductImage::class);
    }
}
