<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Role extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'slug'];

    // Automatically generate slug when creating a new role
    protected static function boot()
    {
        parent::boot();
        static::creating(function ($role) {
            $role->slug = Str::slug($role->name);
        });
    }
}
