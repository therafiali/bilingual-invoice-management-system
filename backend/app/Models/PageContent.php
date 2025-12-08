<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PageContent extends Model
{
    protected $table = 'page_contents';
    protected $primaryKey = 'id';
    protected $fillable = ['page_id', 'category', 'lang', 'text', 'media_links'];



    protected $casts = [
        'media_links' => 'array',
        'lang' => 'boolean',
    ];


    // Define relationship to Page model

    public function page()
    {
        return $this->belongsTo(Page::class);
    }
}
