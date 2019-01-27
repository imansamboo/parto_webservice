<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductFeature extends Model
{
    protected $fillable = ['key', 'value', 'product_ID'];
    public $timestamps = false;
    protected $primaryKey = 'ID';
    protected $table = 'product_features';

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function product()
    {
        return $this->belongsTo('App\Product', 'product_ID', 'ID');
    }
}
