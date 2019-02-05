<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Discount extends Model
{
    protected $fillable = [
        'discount_code',
        'is_enabled'
    ];
    public $timestamps = false;
    protected $primaryKey = 'ID';
    protected $table = 'discounts';
}
