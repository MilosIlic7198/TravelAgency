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
        return Blog::where("status", "Published")->get();
    }

    public function get_Blog(Request $request, $id)
    {
        return Blog::find($id);
    }

    public function edit_Blog(Request $request, $id)
    {
        $formFields = $request->validate([
            'title' => ['required', 'min:6'],
            'description' => ['required', 'min:8'],
            'type' => ['required', 'min:2']
        ]);
        $formFields['image'] = $request->image;
        if ($request->hasFile('imageFile')) {
            $formFields['image'] = $request->file('imageFile')->store('images', 'public');
        }
        $blog = Blog::find($id);
        $blog->title = $formFields['title'];
        $blog->image = $formFields['image'];
        $blog->description = $formFields['description'];
        $blog->type = $formFields['type'];
        $blog->save();
    }

    public function delete_Blog(Request $request, $id)
    {
        try {
            Blog::where('id', $id)->delete();
            return response()->json("Success", 200);
        } catch (RecordsNotFoundException $e) {
            return response()->json("Records not found!", $e->getCode());
        } catch (QueryException $e) {
            return response()->json("Bad query!", $e->getCode());
        } catch (Exception $e) {
            return response()->json("General exception!", $e->getCode());
        }
    }

    public function publish_Blog(Request $request, $id)
    {
        $date = Carbon::now();
        $blog = Blog::find($id);
        $blog->publication_date = $date->toDateTimeString();
        $blog->status = "Published";
        $blog->save();
    }

    public function archive_Blog(Request $request, $id)
    {
        $blog = Blog::find($id);
        if ($blog->status == "Published") {
            $date = Carbon::now();
            $blog->archiving_date = $date->toDateTimeString();
            $blog->status = "Archived";
            $blog->save();
        } else if ($blog->status == "Archived") {
            return response(["message" => "You already archived!"], 403);
        } else {
            return response(["message" => "You cannot archive if you did not publish first!"], 403);
        }
    }
}
