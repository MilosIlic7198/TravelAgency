<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
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

    public function fetchFilters($participantsId, $start, $length, $colName, $colOrder, $search)
    {
        $name = "";
        switch ($colName) {
            case 0:
                $name = "name";
                break;
            case 1:
                $name = "surname";
                break;
        }
        $filters = DB::table('participant')
            ->select("participant.name", "participant.surname", "participant.birthdate")
            ->orderBy($name, $colOrder)
            ->where("insurance_policy_id", $participantsId);
        if (!empty($search)) {
            $filters = $filters->whereRaw(
                "(participant.name LIKE '%{$search}%' OR participant.surname LIKE '%{$search}%')"
            );
        }

        $filterNum = $filters->get();

        $total = DB::table('participant')->where("insurance_policy_id", $participantsId);

        $filters = $filters->offset($start)->limit($length)->get();

        return ['data' => $filters, 'total' => $total->count(), "filtered" => $filterNum->count()];
    }
}
