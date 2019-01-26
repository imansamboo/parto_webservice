<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = ['title', 'target', 'targetID', 'tab_ID'];
    public $timestamps = false;
    protected $primaryKey = 'ID';
}
