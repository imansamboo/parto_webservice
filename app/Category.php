<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = ['title', 'target', 'targetID', 'tab_ID'];
    public $timestamps = false;
    protected $primaryKey = 'ID';
    protected $table = 'categories';

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function products()
    {
        return $this->belongsToMany('App\Product', 'product_categories', 'category_ID', 'product_ID');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function tab()
    {
        return $this->belongsTo('App\Product', 'product_ID', 'ID');
    }
}
