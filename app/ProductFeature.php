<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductFeature extends Model
{
    protected $fillable = ['key', 'value', 'product_ID'];
    public $timestamps = false;
    protected $primaryKey = 'ID';
}
