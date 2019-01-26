<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductPrice extends Model
{
    protected $fillable = ['colorID', 'txtcolorcode', 'colortitle', 'colorcode', 'garrantytitle', 'pricetxt', 'price', 'oldpricetxt', 'discount', 'product_ID'];
    public $timestamps = false;
    protected $primaryKey = 'ID';
}
