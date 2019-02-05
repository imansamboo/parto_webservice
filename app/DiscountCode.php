<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DiscountCode extends Model
{
    protected $fillable = [
        'discount_code',
        'is_enabled'
    ];
    public $timestamps = false;
    protected $primaryKey = 'ID';
    protected $table = 'discount_codes';

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function products()
    {
        return $this->belongsToMany('App\Product', 'product_discount_codes', 'discount_code_ID', 'product_ID');
    }
}
