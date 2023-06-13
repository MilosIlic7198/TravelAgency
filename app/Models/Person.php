<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;


class Person extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'person';
    protected $fillable = ['email', 'password'];
    public $timestamps = false;
    public static $rules = array();

    public function blogs(): HasMany
    {
        return $this->hasMany(Blog::class, 'author_id');
    }

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'person_role', 'person_id', 'role_id');
    }
}
