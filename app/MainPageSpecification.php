<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MainPageSpecification extends Model
{
    public $timestamps = false;
    protected $fillable = [
        "logo_splash",
        "logo",
        "title",
        "desc",
        "splash_bgcolor",
        "splash_fontcolor",
        "toolbar_bgcolor",
        "toolbar_fontcolor",
        "show_instagram_button",
        "instagram_page_url",
        "show_category_button"
    ];
    protected $table = 'pages';
}
