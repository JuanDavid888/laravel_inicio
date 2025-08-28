<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDelete;

class Post extends Model
{
    use HasFactory, SoftDelete;

    protected $table = "posts";

    protected $fillable = [
        'title', 'content', 'status',
        'published_at', 'conver_image', 'tags', 'meta'
    ];

    protected $casts = [
        'pubished_at' => 'datetime',
        'tags' => 'array',
        'meta' => 'meta'
    ];

    public function categories() {
        // Tabla pivot post_category
        return $this->belongsToMany(Category::class)->using(CategoryPost::class)->withTimestamps();
    }
}
