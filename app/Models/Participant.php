<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Participant extends Model
{
    use HasFactory;

    protected $table = 'participant';
    protected $primaryKey = 'insurance_policy_id';
    protected $fillable = ['name', 'surname', 'birthdate'];
    public $timestamps = false;

    public function policies(): BelongsTo
    {
        return $this->belongsTo(InsurancePolicy::class, 'insurance_policy_id');
    }
}
