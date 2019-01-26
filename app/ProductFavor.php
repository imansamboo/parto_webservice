<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductFavor extends Model
{
    protected $fillable = ['user_ID', 'product_ID', 'is_favor'];
    public $timestamps = false;
    protected $primaryKey = 'ID';
}
