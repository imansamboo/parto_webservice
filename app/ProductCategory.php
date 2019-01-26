<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductCategory extends Model
{
    protected $fillable = ['title', 'product_ID', 'category_ID'];
    public $timestamps = false;
    protected $primaryKey = 'ID';
}
