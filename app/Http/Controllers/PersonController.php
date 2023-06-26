<?php

namespace App\Http\Controllers;

use App\Models\Person;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Exception;

class PersonController extends Controller
{
    //
    public function login(Request $request)
    {
        try {
            $loginFields = Validator::make($request->all(), [
                'email' => ['required', 'email'],
                'password' => ['required']
            ]);
            if ($loginFields->fails()) {
                return response()->json(["error" => "Login fields must be filled in correctly!"], 422);
            }
            if (Auth::attempt($request->all())) {
                $person = Person::find(Auth::user()->id);
                $roles = $person->roles()->get()->toArray();
                return response()->json(['id' => $person->id, 'email' => $person->email, 'role' => $roles[0]['name']], 200);
            }
            return response()->json(["error" => 'Failed to login!'], 401);
        } catch (Exception $e) {
            return response()->json(["error" => "There was an error in logging in this person!"], 500);
        }
    }
    public function register(Request $request)
    {
        try {
            $registerFields = Validator::make($request->all(), [
                'email' => ['required', 'email'],
                'password' => ['required', 'min:6', 'max:16']
            ]);
            if ($registerFields->fails()) {
                return response()->json(["error" => "Register fields must be filled in correctly!"], 422);
            }
            $pass = bcrypt($request->password);
            $person = new Person();
            $person->email = $request->email;
            $person->password = $pass;
            $saved = $person->save();
            $person->roles()->attach(3);
            if ($saved) {
                return response()->json(["message" => 'Register was successful!'], 200);
            }
            return response()->json(["error" => 'Failed to register!'], 401);
        } catch (Exception $e) {
            return response()->json(["error" => "There was an error in registering this person!"], 500);
        }
    }

    public function logout()
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

    public function get_Person($id)
    {
        try {
            $person = Person::find($id);
            if ($person == null) {
                return response()->json(["error" => 'Person not found!'], 404);
            }
            $roles = $person->roles()->get();
            $roles = $roles->map(function ($role) {
                return $role->name;
            });
            return response()->json(['id' => $person->id, 'email' => $person->email, 'roles' => $roles->toArray()], 200);
        } catch (Exception $e) {
            return response()->json(["error" => "There was an error in getting this person!"], 500);
        }
    }

    public function edit_Person(Request $request, $id)
    {
        try {
            $editPersonEmail = Validator::make($request->all(), [
                'email' => ['required', 'email'],
            ]);
            if ($editPersonEmail->fails()) {
                return response()->json(["error" => "Edit fields must be filled in correctly!"], 422);
            }
            $person = Person::find($id);
            $person->email = $request->email;
            if (isset($request->newPassword)) {
                $editPersonPass = Validator::make($request->all(), [
                    'newPassword' => ['required', 'min:6', 'max:16'],
                ]);
                if ($editPersonPass->fails()) {
                    return response()->json(["error" => "Edit fields must be filled in correctly!"], 422);
                }
                $person->password = bcrypt($request->newPassword);
            }
            $person->roles()->detach();
            foreach ($request->roles as $role) {
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
            if ($person->save()) {
                return response()->json(["message" => 'Edit was success!'], 200);
            }
            return response()->json(["error" => 'Failed to edit'], 400);
        } catch (Exception $e) {
            return response()->json(["error" => "There was an error in editing this person!"], 500);
        }
    }

    public function delete_Person($id)
    {
        try {
            if (Person::where('id', $id)->delete()) {
                return response()->json(["message" => "You have deleted this person!"], 200);
            }
            return response()->json(["error" => "This person does not exist!"], 404);
        } catch (Exception $e) {
            return response()->json(["error" => "There was an error in deleting this person!"], 500);
        }
    }
}
