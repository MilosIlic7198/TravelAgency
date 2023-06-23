<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Blog;
use Carbon\Carbon;
use Exception;

class BlogController extends Controller
{

    public function create_Blog(Request $request)
    {
        $formFields = $request->validate([
            'title' => ['required', 'min:3', 'max:255'],
            'description' => ['required', 'min:8'],
            'type' => ['required']
        ]);
        if ($request->hasFile('image')) {
            $formFields['image'] = $request->file('image')->store('images', 'public');
        }
        $date = Carbon::now();
        $formFields['status'] = 'In preparation';
        $formFields['creation_date'] = $date->toDateTimeString();
        $formFields['author_id'] = auth()->user()->id;
        $blog = Blog::create($formFields);
        if ($blog->exists) {
            return response()->json(["message" => 'You have created this blog!'], 200);
        }
        return response()->json(["error" => 'Failed to create this blog!'], 204);
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
        try {
            return Blog::select(
                "blog.title",
                "blog.description",
                "blog.image",
                "blog.publication_date",
                "blog.type",
                "person.email as author"
            )
                ->join("person", "person.id", "=", "blog.author_id")
                ->where("status", "Published")
                ->get();
        } catch (Exception $e) {
            return response()->json(["error" => $e->getMessage()], $e->getCode());
        }
    }

    public function get_Blog(Request $request, $id)
    {
        try {
            return Blog::select(
                "blog.title",
                "blog.description",
                "blog.image",
                "blog.publication_date",
                "blog.type",
                "person.email as author"
            )
                ->join("person", "person.id", "=", "blog.author_id")
                ->where("blog.id", $id)
                ->get();
        } catch (Exception $e) {
            return response()->json(["error" => $e->getMessage()], $e->getCode());
        }
    }

    public function edit_Blog(Request $request, $id)
    {
        $formFields = $request->validate([
            'title' => ['required', 'min:3'],
            'description' => ['required', 'min:6'],
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
        $saved = $blog->save();
        if ($saved) {
            return response()->json(["message" => 'You have edited this blog!'], 200);
        }
        return response()->json(["error" => 'Failed to edit this blog!'], 204);
    }

    public function delete_Blog(Request $request, $id)
    {
        $blog = Blog::where('id', $id)->delete();
        if ($blog >= 1) {
            return response()->json(["message" => 'You have deleted this blog!'], 200);
        }
        return response()->json(["error" => 'Failed to delete this blog!'], 204);
    }

    public function publish_Blog(Request $request, $id)
    {
        $date = Carbon::now();
        $blog = Blog::find($id);
        $blog->publication_date = $date->toDateTimeString();
        $blog->status = "Published";
        $saved = $blog->save();
        if ($saved) {
            return response()->json(["message" => 'You have published this blog!'], 200);
        }
        return response()->json(["error" => 'Failed to publish this blog!'], 204);
    }

    public function archive_Blog(Request $request, $id)
    {
        $blog = Blog::find($id);
        if ($blog->status == "Published") {
            $date = Carbon::now();
            $blog->archiving_date = $date->toDateTimeString();
            $blog->status = "Archived";
            $saved = $blog->save();
            if ($saved) {
                return response(["message" => "You already archived!"], 200);
            } else {
                return response(["error" => "There was an error in archiving this blog!"], 204);
            }
        } else if ($blog->status == "Archived") {
            return response(["error" => "You already archived!"], 204);
        } else {
            return response(["error" => "You cannot archive if you did not publish first!"], 204);
        }
    }
}
