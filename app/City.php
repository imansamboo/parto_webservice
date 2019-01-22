<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    public $timestamps = false;
    protected $primaryKey = 'ID';
    protected $fillable = ['title', 'province_id'];
}
