<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = ['title', 'readmoreUrl', 'shareTxt', 'showColor', 'desc'];
    public $timestamps = false;
    protected $primaryKey = 'ID';
}
