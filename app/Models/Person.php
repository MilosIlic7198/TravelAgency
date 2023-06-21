<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
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

    public function fetchFilters($start, $length, $colName, $colOrder, $search)
    {
        $name = "";
        switch ($colName) {
            case 0:
                $name = "id";
                break;
            case 1:
                $name = "email";
                break;
            case 3:
                $name = "role";
                break;
        }
        $filters = DB::table('person')
            ->join('person_role', 'person.id', '=', 'person_role.person_id')
            ->join('role', 'role.id', '=', 'person_role.role_id')
            ->selectRaw('person.id, person.email, GROUP_CONCAT(role.name) as role')
            ->orderBy($name, $colOrder)
            ->groupBy('person.id');

        if (!empty($search)) {
            $filters = $filters->whereRaw(
                "(person.id LIKE '%{$search}%' OR person.email LIKE '%{$search}%' OR role.name LIKE '%{$search}%')"
            );
        }

        $filterNum = $filters->get();

        $total = DB::table('person');

        $filters = $filters->offset($start)->limit($length)->get();

        return ['data' => $filters, 'total' => $total->count(), "filtered" => $filterNum->count()];
    }
}
