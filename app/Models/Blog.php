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
                $name = "id";
                break;
            case 1:
                $name = "title";
                break;
            case 3:
                $name = "description";
                break;
            case 4:
                $name = "status";
                break;
            case 5:
                $name = "type";
                break;
            case 9:
                $name = "person.email";
                break;
        }
        $filters = DB::table('blog')
            ->select("blog.*", "person.email as author")
            ->join("person", "person.id", "=", "blog.author_id")
            ->orderBy($name, $colOrder)
            ->where("author_id", $personId);
        if (!empty($search)) {
            $filters = $filters->whereRaw(
                "(blog.id LIKE '%{$search}%' OR blog.title LIKE '%{$search}%' OR blog.description LIKE '%{$search}%' OR blog.status LIKE '%{$search}%' OR blog.type LIKE '%{$search}%' OR person.email LIKE '%{$search}%')"
            );
        }

        $filterNum = $filters->get();

        $total = DB::table('blog')->where("author_id", $personId);

        $filters = $filters->offset($start)->limit($length)->get();

        return ['data' => $filters, 'total' => $total->count(), "filtered" => $filterNum->count()];
    }
}
