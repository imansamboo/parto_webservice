<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductSlide extends Model
{
    protected $fillable = ['image', 'large_image', 'product_ID'];
    public $timestamps = false;
    protected $primaryKey = 'ID';
    protected $table = 'product_slides';

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function product()
    {
        return $this->belongsTo('App\Product', 'product_ID', 'ID');
    }
}
