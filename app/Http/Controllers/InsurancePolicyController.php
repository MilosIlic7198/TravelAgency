<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\InsurancePolicy;
use Carbon\Carbon;

class InsurancePolicyController extends Controller
{
    //
    public function get_All_Policies()
    {
        return InsurancePolicy::all();
    }

    public function create_Policy(Request $request)
    {
        $formFields = $request->validate([
            'type' => ['required'],
            'description' => ['required', 'min:8'],
            'holdersFirstName' => ['required', 'min:3'],
            'holdersLastName' => ['required', 'min:3'],
            'holdersPhoneNumber' => ['required', 'digits:10'],
            'dateFrom' => ['required', 'date'],
            'dateTo' => ['required', 'date'],
        ]);
        $participantFields = Validator::make($request->all(), [
            'participants.*.firstName' => ['required', 'min:3'],
            'participants.*.lastName' => ['required', 'min:3'],
            'participants.*.birthdate' => ['required', 'date'],
        ]);
        $formFields['dateFrom'] = Carbon::parse($formFields['dateFrom'])->toDateString();
        $formFields['dateTo'] = Carbon::parse($formFields['dateTo'])->toDateString();
        if ($formFields['type'] == "Group") {
            if ($participantFields->fails()) {
                return response()->json([
                    'errors' => "Participants fields are empty!",
                ], 422);
            }
            $policy = InsurancePolicy::create([
                'type' => $formFields['type'],
                'description' => $formFields['description'],
                'holder_name' => $formFields['holdersFirstName'],
                'holder_surname' => $formFields['holdersLastName'],
                'holder_phone' => $formFields['holdersPhoneNumber'],
                'date_from' =>  $formFields['dateFrom'],
                'date_to' =>  $formFields['dateTo']
            ]);
            foreach ($request->participants as $participant) {
                $birthdate =  Carbon::parse($participant['birthdate'])->toDateString();
                $policy->participants()->create([
                    'name' => $participant['firstName'],
                    'surname' => $participant['lastName'],
                    'birthdate' => $birthdate
                ]);
            }
            return response()->json([
                'success' => "Success in getting policy!",
            ], 200);
        }
        $policy = InsurancePolicy::create([
            'type' => $formFields['type'],
            'description' => $formFields['description'],
            'holder_name' => $formFields['holdersFirstName'],
            'holder_surname' => $formFields['holdersLastName'],
            'holder_phone' => $formFields['holdersPhoneNumber'],
            'date_from' =>  $formFields['dateFrom'],
            'date_to' =>  $formFields['dateTo']
        ]);
        return response()->json([
            'success' => "Success in getting policy!",
        ], 200);
    }
}
