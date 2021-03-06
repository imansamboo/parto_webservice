<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = ['title', 'readmoreUrl', 'shareTxt', 'showColor', 'desc', 'image', 'total_cell_count', 'visibility', 'maxquantity'];
    public $timestamps = false;
    protected $primaryKey = 'ID';
    protected $table = 'products';

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function features()
    {
        return $this->hasMany('App\ProductFeature', 'product_ID', 'ID');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function menus()
    {
        return $this->hasMany('App\ProductMenu', 'product_ID', 'ID');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function prices()
    {
        return $this->hasMany('App\ProductPrice', 'product_ID', 'ID');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function slides()
    {
        return $this->hasMany('App\ProductSlide', 'product_ID', 'ID');
    }


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function favors()
    {
        return $this->hasMany('App\ProductFavors', 'product_ID', 'ID');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function categories()
    {
        return $this->belongsToMany('App\Category', 'product_categories', 'product_ID', 'category_ID');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function tags()
    {
        return $this->belongsToMany('App\Tag', 'product_tags', 'product_ID', 'tag_ID');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function discountCodes()
    {
        return $this->belongsToMany('App\DiscountCode', 'product_discount_codes', 'product_ID', 'discount_code_ID');
    }
}
