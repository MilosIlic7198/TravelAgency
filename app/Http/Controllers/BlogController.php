<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Blog;
use App\Models\Person;
use Carbon\Carbon;

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
        $formFields['creation_date'] = $date->toDateString();
        $formFields['author_id'] = auth()->user()->id;
        Blog::create($formFields);
    }

    public function get_All_Blogs(Request $request)
    {
        return Person::find(auth()->user()->id)->blogs()->get();
    }
}
