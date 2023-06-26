<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Blog;
use Carbon\Carbon;
use Exception;

class BlogController extends Controller
{

    public function create_Blog(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'title' => ['required', 'min:3', 'max:255'],
                'description' => ['required', 'min:8'],
                'type' => ['required']
            ]);
            if ($validator->fails()) {
                return response()->json(["error" => "Blog fields must be filled in correctly!"], 422);
            }
            $date = Carbon::now();
            $newBlogFields = [
                "title" => $request->title,
                "description" => $request->description,
                "status" => "In preparation",
                "creation_date" => $date->toDateTimeString(),
                "type" => $request->type,
                "author_id" => auth()->user()->id
            ];
            if ($request->hasFile("image")) {
                $newBlogFields["image"] = $request->file("image")->store("images", "public");
            }
            $blog = Blog::create($newBlogFields);
            if ($blog->exists) {
                return response()->json(["message" => 'You have created this blog!'], 200);
            }
            return response()->json(["message" => 'Failed to create this blog!'], 400);
        } catch (Exception $e) {
            return response()->json(["error" => 'There was an error in creating this blog!'], 500);
        }
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

    public function get_All_Blogs()
    {
        try {
            $blogs = Blog::select(
                "blog.id",
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
            if (count($blogs) != 0) {
                return $blogs;
            }
            return response()->json(["error" => "These blogs do not exist!"], 404);
        } catch (Exception $e) {
            dd($e->getMessage());
            return response()->json(["error" => 'There was an error in getting blogs!'], 500);
        }
    }

    public function get_Blog($id)
    {
        try {
            $blog = Blog::select(
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
            if (count($blog) != 0) {
                return $blog;
            }
            return response()->json(["error" => 'This blog does not exist!'], 404);
        } catch (Exception $e) {
            return response()->json(["error" => 'There was an error in getting this blog!'], 500);
        }
    }

    public function edit_Blog(Request $request, $id)
    {
        try {
            $validator = Validator::make($request->all(), [
                'title' => ['required', 'min:3'],
                'description' => ['required', 'min:6'],
                'type' => ['required']
            ]);
            if ($validator->fails()) {
                return response()->json(["error" => "Blog fields must be filled in correctly!"], 422);
            }
            $image = $request->image;
            if ($request->hasFile('imageFile')) {
                $image = $request->file('imageFile')->store('images', 'public');
            }
            $blog = Blog::find($id);
            $blog->title = $request->title;
            $blog->image = $image;
            $blog->description = $request->description;
            $blog->type = $request->type;
            $saved = $blog->save();
            if ($saved) {
                return response()->json(["message" => 'You have edited this blog!'], 200);
            }
            return response()->json(["error" => 'Failed to edit this blog!'], 400);
        } catch (Exception $e) {
            return response()->json(["error" => 'There was an error in editing this blog!'], 500);
        }
    }

    public function delete_Blog($id)
    {
        try {
            if (Blog::where('id', $id)->delete()) {
                return response()->json(["message" => 'You have deleted this blog!'], 200);
            }
            return response()->json(["error" => 'This blog does not exist!'], 404);
        } catch (Exception $e) {
            return response()->json(["error" => "There was an error in deleting this blog!"], 500);
        }
    }

    public function publish_Blog($id)
    {
        try {
            $blog = Blog::find($id);
            if ($blog == null) {
                return response()->json(["error" => 'This blog does not exist!'], 404);
            }
            $date = Carbon::now();
            $blog->publication_date = $date->toDateTimeString();
            $blog->status = "Published";
            $saved = $blog->save();
            if ($saved) {
                return response()->json(["message" => 'You have published this blog!'], 200);
            }
            return response()->json(["error" => 'Failed to publish this blog!'], 400);
        } catch (Exception $e) {
            return response()->json(["error" => "There was an error in publishing this blog!"], 500);
        }
    }

    public function archive_Blog($id)
    {
        try {
            $blog = Blog::find($id);
            if ($blog == null) {
                return response()->json(["error" => 'This blog does not exist!'], 404);
            }
            if ($blog->status == "In preparation") {
                return response(["error" => "You cannot archive if you did not publish first!"], 400);
            }
            if ($blog->status == "Published") {
                $date = Carbon::now();
                $blog->archiving_date = $date->toDateTimeString();
                $blog->status = "Archived";
                $saved = $blog->save();
                if ($saved) {
                    return response(["message" => "You have archived this blog!"], 200);
                } else {
                    return response(["error" => "Failed to archive this blog!"], 400);
                }
            }
            if ($blog->status == "Archived") {
                return response(["error" => "You already archived!"], 400);
            }
        } catch (Exception $e) {
            return response()->json(["error" => "There was an error in archiving this blog!"], 500);
        }
    }
}
