<?php

namespace App\Http\Controllers;

use App\Models\Person;
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
            $person = Person::find(Auth::user()->id);
            $roles = $person->roles()->get()->toArray();
            return response()->json(['id' => $person->id, 'email' => $person->email, 'role' => $roles[0]['name']], 200);
        }
        return response()->json(["error" => 'Failed to login!'], 401);
    }
    public function register(Request $request)
    {
        $formFields = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'min:6', 'max:16']
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
            return response()->json(["error" => 'Failed to login!'], 401);
        }
        return response()->json(["error" => 'Failed to register!'], 401);
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
        $person = Person::select("id", "email")->where("id", $id)->get();
        if ($person->isEmpty()) {
            return response()->json(["error" => 'Person not found!'], 404);
        }
        $personData = [$person->toArray()[0], $roles->toArray()];
        return $personData;
    }

    public function edit_Person(Request $request, $id)
    {
        $formFields = $request->validate([
            'email' => ['required', 'email'],
        ]);
        $roles = explode(',', $request->roles);
        $person = Person::find($id);
        $person->email = $formFields['email'];
        if (isset($request->newPassword)) {
            $formFields = $request->validate([
                'newPassword' => ['required', 'min:6'],
            ]);
            $person->password = bcrypt($formFields['newPassword']);
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
        $saved = $person->save();
        if ($saved) {
            return response()->json(["message" => 'Edit was success!'], 200);
        }
        return response()->json(["error" => 'Failed to edit'], 204);
    }

    public function delete_Person(Request $request, $id)
    {
        $person = Person::where('id', $id)->delete();
        if ($person >= 1) {
            return response()->json(["messsage" => "You have deleted this person!"], 200);
        }
        return response()->json(["error" => "There was an error in deleting this person!"], 404);
    }
}
