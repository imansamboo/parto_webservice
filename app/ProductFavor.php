<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductFavor extends Model
{
    protected $fillable = ['user_ID', 'product_ID', 'is_favor'];
    public $timestamps = false;
    protected $primaryKey = 'ID';
    protected $table = 'product_favors';

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function product()
    {
        return $this->belongsTo('App\Product', 'product_ID', 'ID');
    }
}
