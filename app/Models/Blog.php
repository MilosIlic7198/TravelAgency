<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Blog extends Model
{
    use HasFactory;

    protected $table = 'blog';
    protected $fillable = ['naslov', 'opis', 'status', 'datumivreme_kreiranja', 'datumivreme_objavljivanja', 'datumivreme_arhiviranja', 'type', 'autor_id'];
    public $timestamps = false;
}
