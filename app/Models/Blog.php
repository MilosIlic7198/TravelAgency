<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Blog extends Model
{
    use HasFactory;

    protected $table = 'blog';
    protected $fillable = ['title', 'description', 'image', 'status', 'creation_date', 'publication_date', 'archiving_date', 'type', 'author_id'];
    public $timestamps = false;

    public function people(): BelongsTo
    {
        return $this->belongsTo(Person::class, 'id');
    }
}
