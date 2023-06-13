<?php

namespace App\Http\Controllers;

use App\Models\Person;
use App\Models\Blog;
use App\Models\Role;
use App\Models\InsurancePolicy;
use App\Models\Participant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PersonController extends Controller
{
    //
    public function login(Request $request)
    {
        $formFields = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required']
        ]);
        if (Auth::attempt($formFields)) {
            $person = Auth::user();
            $roles = $person->roles()->get()->toArray();
            return response()->json(['id' => $person->id, 'email' => $person->email, 'role' => $roles[0]['name']], 200);
        }
        return response()->json('Failed', 401);
    }
    public function register(Request $request)
    {
        $formFields = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'min:6']
        ]);
        $pass = bcrypt($formFields['password']);
        $person = new Person();
        $person->email = $formFields['email'];
        $person->password = $pass;
        $saved = $person->save();
        $person->roles()->attach(3);
        $roles = $person->roles()->get()->toArray();
        if ($saved) {
            if (Auth::attempt($formFields)) {
                return response()->json(['id' => $person->id, 'email' => $person->email, 'role' => $roles[0]['name']], 200);
            }
            return response()->json('Failed', 401);
        }
        return response()->json('Failed', 401);
    }
}
