<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductMenu extends Model
{
    protected $fillable = ['image', 'title', 'target', 'targetID', 'product_ID'];
    public $timestamps = false;
    protected $primaryKey = 'ID';
}
