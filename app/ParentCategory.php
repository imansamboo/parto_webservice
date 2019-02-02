<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = ['title', 'target', 'targetID', 'tab_ID'];
    public $timestamps = false;
    protected $primaryKey = 'ID';
    protected $table = 'parent_categories';

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function tab()
    {
        return $this->belongsTo('App\Tab', 'tab_ID', 'ID');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function categories()
    {
        return $this->hasMany('App\Category', 'parent_ID', 'ID');
    }
}