<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    public $timestamps = false;
    protected $primaryKey = 'ID';
    protected $fillable = ['slide_ID', 'image', 'large_image', 'target', 'targetID'];
    protected $table = 'pages';

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function slide()
    {
        return $this->belongsTo('App\Slide', 'slide_ID', 'ID');
    }
}
