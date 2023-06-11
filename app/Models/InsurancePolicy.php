<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class InsurancePolicy extends Model
{
    use HasFactory;

    protected $table = 'insurance_policy';
    protected $fillable = ['type', 'description', 'holder_name', 'holder_surname', 'holder_phone', 'date_from', 'date_to'];
    public $timestamps = false;

    public function participants(): HasMany
    {
        return $this->hasMany(Participant::class);
    }
}
