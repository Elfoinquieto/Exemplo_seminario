<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class User extends Authenticatable
{
    use HasFactory;

    protected $fillable = [
        'username',
        'email',
        'password',
    ];

    public function authors()
    {
        return $this->hasMany(Author::class);
    }
    
    public function books()
    {
        return $this->hasMany(Book::class);
    }

   
}