<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Slide extends Model
{
    public $timestamps = false;
    protected $primaryKey = 'ID';
    protected $fillable = ['title', 'page_ID'];
    protected $table = 'slides';

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function page()
    {
        return $this->belongsTo('App\Page', 'page_ID', 'ID');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function banners()
    {
        return $this->hasMany('App\Banner', 'slide_ID', 'ID');
    }
}
