<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CodeInjection extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'location',
        'is_active',
        'pages',
    ];

    protected $casts = [
        'pages' => 'array',
        'is_active' => 'boolean',
    ];

    /**
     * Scope a query to only include active injections.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope a query to filter injections by page slug.
     * Null pages means it should appear on all pages.
     */
    public function scopeForPage($query, string $slug)
    {
        return $query->where(function ($q) use ($slug) {
            $q->whereNull('pages')
              ->orWhereJsonContains('pages', $slug);
        });
    }
}
