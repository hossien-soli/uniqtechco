<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('uniq_id')->unique();
            $table->string('name');
            $table->string('alternate_name')->nullable();
            $table->text('description')->nullable();
            $table->integer('price')->unsigned();
            $table->tinyInteger('status')->default(0);  // 0=created  1=published  2=hidden
            $table->text('link_on_digikala')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('products');
    }
}
