<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    use HasFactory;

    protected $fillable = [
        'slug',
        'title_en',
        'title_ar',
        'meta_title',
        'meta_description',
        'og_image',
    ];

    /**
     * Get the sections for the page.
     */
    public function sections()
    {
        return $this->hasMany(Section::class)->orderBy('order');
    }
}
