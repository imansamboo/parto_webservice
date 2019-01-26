<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductSlide extends Model
{
    protected $fillable = ['image', 'large_image', 'product_ID'];
    public $timestamps = false;
    protected $primaryKey = 'ID';
}
