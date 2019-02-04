<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductTag extends Model
{
    public $timestamps = false;
    protected $primaryKey = 'ID';
    protected $fillable = ['product_ID', 'tag_ID'];
    protected $table = 'product_tags';
}
