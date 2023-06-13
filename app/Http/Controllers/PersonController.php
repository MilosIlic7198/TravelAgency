<?php

namespace App\Http\Controllers;

use App\Models\Person;
use App\Models\Blog;
use App\Models\Role;
use App\Models\InsurancePolicy;
use App\Models\Participant;
use Illuminate\Http\Request;

class PersonController extends Controller
{
    //
    public function login(Request $request)
    {
        $formFields = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required']
        ]);

        if (auth()->attempt($formFields)) {
            $request->session()->regenerate();
            return response()->json([
                'message' => 'Success',
                'status' => 200,
                'person' => [
                    'id' => auth()->user()->id,
                    'email' => auth()->user()->email
                ]
            ]);
        }
        return response('Failed', 401);

        //Attempt to login person by email and password, return response, success or failed!



        //Testing model relationships!
        /*
        $blogs = Person::find(1)->blogs()->get();
        echo $blogs;
        foreach ($blogs as $blog) {
            echo $blog['status'];
        }
        */
        /*
        $people = Blog::find(1)->people()->get();
        echo $people;
        foreach ($people as $person) {
            echo $person['email'];
        }
        */
        /*
        $roles = Person::find(1)->roles()->get();
        echo $roles;
        foreach ($roles as $role) {
            echo $role['name'];
        }
        */
        /*
        $people = Role::find(1)->people()->get();
        echo $people;
        foreach ($people as $person) {
            echo $person['email'];
        }
        */
        /*
        $participants = InsurancePolicy::find(1)->participants()->get();
        echo $participants;
        foreach ($participants as $participant) {
            echo $participant['name'];
        }
        */
        /*
        $policies = Participant::find(1)->policies()->get();
        echo $policies;
        foreach ($policies as $policy) {
            echo $policy['holder_name'];
        }
        */
        /*
        $formFields = array(
            'type' => 'Group',
            'description' => 'This is a test insert!',
            'holder_name' => 'Joe',
            'holder_surname' => 'Rogan',
            'holder_phone'  => '0611842259',
            'date_from' => '2023-08-12',
            'date_to' => '2023-08-29'
        );
        $q = InsurancePolicy::create($formFields);
        $q->participants()->create(['name' => 'John', 'surname' => 'Wick', 'birthdate' => '2000-04-24']);
        */
    }
}
