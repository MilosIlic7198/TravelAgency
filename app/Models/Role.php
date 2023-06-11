<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Role extends Model
{
    use HasFactory;

    protected $table = 'role';
    public $timestamps = false;

    public function people(): BelongsToMany
    {
        return $this->belongsToMany(Person::class, 'person_role', 'person_id', 'role_id');
    }
}
