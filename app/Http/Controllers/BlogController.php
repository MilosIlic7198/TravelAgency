<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Blog;
use Carbon\Carbon;
use \Illuminate\Database\RecordsNotFoundException;
use \Illuminate\Database\QueryException;
use Exception;

class BlogController extends Controller
{

    public function create_Blog(Request $request)
    {
        $formFields = $request->validate([
            'title' => ['required', 'min:6'],
            'description' => ['required', 'min:8'],
            'type' => ['required', 'min:2']
        ]);
        if ($request->hasFile('image')) {
            $formFields['image'] = $request->file('image')->store('images', 'public');
        }
        $date = Carbon::now();
        $formFields['status'] = 'In preparation';
        $formFields['creation_date'] = $date->toDateTimeString();
        $formFields['author_id'] = auth()->user()->id;
        Blog::create($formFields);
    }

    public function get_Persons_Blogs(Request $request)
    {
        $personBlogs = new Blog();
        $data = $personBlogs->fetchFilters(
            auth()->user()->id,
            $request->start,
            $request->length,
            $request->order[0]['column'],
            $request->order[0]["dir"],
            $request->search['value'],
        );
        return [
            "draw" => $request->draw,
            "recordsTotal" => $data['total'],
            "recordsFiltered" => $data['filtered'],
            "data" => $data['data']
        ];
    }

    public function get_All_Blogs(Request $request)
    {
        return Blog::all();
    }

    public function delete_Blog(Request $request, $id)
    {
        try {
            Blog::where('id', $id)->delete();
            return response()->json("Success", 200);
        } catch (RecordsNotFoundException $e) {
            return response()->json("Records not found!", 500);
        } catch (QueryException $e) {
            return response()->json("Bad query!", 500);
        } catch (Exception $e) {
            return response()->json("General exception!", 500);
        }
    }
}
