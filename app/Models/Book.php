<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Book extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'author_id',
        'title',
        'isbn',
        'genre',
        'description',
    ];

    public const GENRES = [
        'Ficção Científica',
        'Fantasia',
        'Romance',
        'Mistério / Suspense',
        'Terror',
        'Biografia',
        'História',
        'Autoajuda',
        'Técnico / Educacional',
    ];

    public function author()
    {
        return $this->belongsTo(Author::class);
    }

     public function user()
    {
        return $this->belongsTo(User::class);
    }
}