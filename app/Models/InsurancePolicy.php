<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
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

    public function fetchFilters($start, $length, $colName, $colOrder, $search)
    {
        $name = "";
        switch ($colName) {
            case 0:
                $name = "id";
                break;
            case 1:
                $name = "type";
                break;
            case 2:
                $name = "description";
                break;
            case 3:
                $name = "holder_name";
                break;
        }
        $filters = DB::table('insurance_policy')
            ->orderBy($name, $colOrder);
        if (!empty($search)) {
            $filters = $filters->whereRaw(
                "(insurance_policy.id LIKE '%{$search}%' OR insurance_policy.type LIKE '%{$search}%' OR insurance_policy.description LIKE '%{$search}%' OR insurance_policy.holder_name LIKE '%{$search}%' OR insurance_policy.holder_surname LIKE '%{$search}%')"
            );
        }

        $filterNum = $filters->get();

        $total = DB::table('insurance_policy');

        $filters = $filters->offset($start)->limit($length)->get();

        return ['data' => $filters, 'total' => $total->count(), "filtered" => $filterNum->count()];
    }
}
