<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Section extends Model
{
    public $timestamps = false;
    protected $primaryKey = 'ID';
    protected $fillable = [
        'title',
        'type',
        'more_button_text',
        'image',
        'expire_date',
        'target',
        'targetID',
    ];
    protected $table = 'sections';

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function pages()
    {
        return $this->belongsToMany('App\Page', 'page_sections', 'section_ID', 'page_ID');
    }

}
