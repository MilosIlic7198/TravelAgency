<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Blog;
use Carbon\Carbon;

class BlogController extends Controller
{
    //
    public function show_Blogs()
    {
        return view('blogs');
    }

    public function create_Blog(Request $request)
    {
        //dd($request->all());
        $formFields = $request->validate([
            'naslov' => ['required', 'min:6'],
            'opis' => ['required', 'min:8'],
            'type' => ['required', 'min:2']
        ]);
        $date = Carbon::now();
        $blog = array_merge($formFields, ['status' => 'U pripremi', 'datumivreme_kreiranja' => $date->toDateString(), 'autor_id' => 1]);
        Blog::create($blog);

        return redirect('/');
    }
}
