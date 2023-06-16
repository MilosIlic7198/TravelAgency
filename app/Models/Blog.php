<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
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

    public function fetchFilters($personId, $start, $length, $colName, $colOrder, $search)
    {
        $name = "";
        switch ($colName) {
            case 0:
                $name = "title";
            case 2:
                $name = "description";
            case 3:
                $name = "status";
            case 4:
                $name = "type";
        }
        $filters = DB::table('blog')
            ->orderBy($name, $colOrder)
            ->where("author_id", $personId);
        if (!empty($search)) {
            $filters = $filters->whereRaw(
                "(blog.title LIKE '%{$search}%' OR blog.description LIKE '%{$search}%' OR blog.status LIKE '%{$search}%' OR blog.type LIKE '%{$search}%')"
            );
        }

        $filterNum = $filters->get();

        $total = DB::table('blog')->where("author_id", $personId);

        $filters = $filters->offset($start)->limit($length)->get();

        return ['data' => $filters, 'total' => $total->count(), "filtered" => $filterNum->count()];
    }
}
