<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tab extends Model
{
    public $timestamps = false;
    protected $primaryKey = 'ID';
    protected $fillable = ['title'];
}
