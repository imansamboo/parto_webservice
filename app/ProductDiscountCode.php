<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductDiscountCode extends Model
{
    protected $fillable = [
        'product_ID',
        'discount_code_ID'
    ];
    public $timestamps = false;
    protected $primaryKey = 'ID';
    protected $table = 'product_discount_codes';

}
