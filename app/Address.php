<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    protected $fillable = ['fullname', 'city', 'province', 'address', 'postalcode', 'phone', 'mobile', 'areacode', 'selected', 'latitude', 'longitude'];
    public $timestamps = false;
    protected $primaryKey = 'ID';
}
