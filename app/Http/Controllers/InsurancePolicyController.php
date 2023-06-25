<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\InsurancePolicy;
use Carbon\Carbon;
use Exception;

class InsurancePolicyController extends Controller
{
    //
    public function get_All_Policies(Request $request)
    {
        $participants = new InsurancePolicy();
        $data = $participants->fetchFilters(
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

    public function create_Policy(Request $request)
    {
        try {
            $formFields = Validator::make($request->all(), [
                'type' => ['required'],
                'description' => ['required', 'min:8'],
                'holdersFirstName' => ['required', 'min:3'],
                'holdersLastName' => ['required', 'min:3'],
                'holdersPhoneNumber' => ['required', 'numeric', 'digits:10'],
                'dateFrom' => ['required', 'date'],
                'dateTo' => ['required', 'date'],
            ]);
            if ($formFields->fails()) {
                return response()->json(["error" => "Holder fields must be filled in correctly!"], 422);
            }
            $dateFrom = Carbon::parse($request->dateFrom)->toDateString();
            $dateTo = Carbon::parse($request->dateTo)->toDateString();
            if ($request->type == "Group") {
                $participantFields = Validator::make($request->all(), [
                    'participants.*.firstName' => ['required', 'min:3'],
                    'participants.*.lastName' => ['required', 'min:3'],
                    'participants.*.birthdate' => ['required', 'date'],
                ]);
                if ($participantFields->fails()) {
                    return response()->json(["error" => "Participants fields must be filled in correctly!"], 422);
                }
                $policy = InsurancePolicy::create([
                    'type' => $request->type,
                    'description' => $request->description,
                    'holder_name' => $request->holdersFirstName,
                    'holder_surname' => $request->holdersLastName,
                    'holder_phone' => $request->holdersPhoneNumber,
                    'date_from' => $dateFrom,
                    'date_to' => $dateTo
                ]);
                foreach ($request->participants as $participant) {
                    $birthdate =  Carbon::parse($participant['birthdate'])->toDateString();
                    $policy->participants()->create([
                        'name' => $participant['firstName'],
                        'surname' => $participant['lastName'],
                        'birthdate' => $birthdate
                    ]);
                }
                return response()->json(["message" => "Success in getting policy!"], 200);
            }
            InsurancePolicy::create([
                'type' => $request->type,
                'description' => $request->description,
                'holder_name' => $request->holdersFirstName,
                'holder_surname' => $request->holdersLastName,
                'holder_phone' => $request->holdersPhoneNumber,
                'date_from' => $dateFrom,
                'date_to' => $dateTo
            ]);
            return response()->json(["message" => "Success in getting policy!"], 200);
        } catch (Exception $e) {
            return response()->json(["error" => "There was an error in getting this policy!"], 500);
        }
    }

    public function delete_Policy($id)
    {
        try {
            if (InsurancePolicy::where('id', $id)->delete()) {
                return response()->json(["message" => "You have deleted this policy!"], 200);
            }
            return response()->json(["error" => "This policy does not exist!"], 404);
        } catch (Exception $e) {
            return response()->json(["error" => "There was an error in deleting this person!"], 500);
        }
    }
}
