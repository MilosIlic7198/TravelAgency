<?php

namespace App\Http\Controllers;

use App\Models\Person;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use \Illuminate\Database\RecordsNotFoundException;
use \Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Crypt;
use \Illuminate\Contracts\Encryption\DecryptException;
use Exception;

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
            $person = Person::find(Auth::user()->id);
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

    public function logout(Request $request)
    {
        Auth::logout();
    }

    public function get_All_People(Request $request)
    {
        $people = new Person();
        $data = $people->fetchFilters(
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

    public function get_Person(Request $request, $id)
    {
        $roles = Person::find($id)->roles()->get();
        $roles = $roles->map(function ($role) {
            return $role->name;
        });
        $person = Person::find($id);
        return [$person, $roles];
    }

    public function edit_Person(Request $request, $id)
    {
        $formFields = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'min:6'],
        ]);
        $roles = explode(',', $request->roles);
        $person = Person::find($id);
        $person->email = $formFields['email'];
        try {
            $pass = Crypt::decryptString($formFields['password']);
        } catch (DecryptException $e) {
            dd("Jell!");
            $person->password = bcrypt($formFields['password']);
        }
        $person->roles()->detach();
        foreach ($roles as $role) {
            if ($role == "Admin") {
                $person->roles()->attach(1);
            }
            if ($role == "Moderator") {
                $person->roles()->attach(2);
            }
            if ($role == "User") {
                $person->roles()->attach(3);
            }
        }
        $person->save();
    }

    public function delete_Person(Request $request, $id)
    {
        try {
            Person::where('id', $id)->delete();
            return response()->json("Success", 200);
        } catch (RecordsNotFoundException $e) {
            return response()->json("User not found!", $e->getCode());
        } catch (QueryException $e) {
            return response()->json("Bad query!", $e->getCode());
        } catch (Exception $e) {
            return response()->json("General exception!", $e->getCode());
        }
    }
}
