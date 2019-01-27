<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductMenu extends Model
{
    protected $fillable = ['image', 'title', 'target', 'targetID', 'product_ID'];
    public $timestamps = false;
    protected $primaryKey = 'ID';
    protected $table = 'product_menus';

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function product()
    {
        return $this->belongsTo('App\Product', 'product_ID', 'ID');
    }

}
