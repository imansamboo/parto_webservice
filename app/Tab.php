<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tab extends Model
{
    public $timestamps = false;
    protected $primaryKey = 'ID';
    protected $fillable = ['title'];
    protected $table = 'tabs';

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function categories(){
        return $this->hasMany('App\ParentCategory', 'tab_ID', 'ID');
    }


}
