<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    protected $fillable = ['fullname', 'city', 'province', 'address', 'postalcode', 'phone', 'mobile', 'areacode', 'selected', 'latitude', 'longitude', 'user_ID'];
    public $timestamps = false;
    protected $primaryKey = 'ID';

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function city()
    {
        return $this->belongsTo('App\City', 'city', 'title');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('App\User', 'user_ID', 'ID');
    }


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function province()
    {
        return $this->belongsTo('App\Province', 'province', 'title');
    }
}
