<?php

namespace App\Http\Controllers;


use Validator;
use Carbon\Carbon;
use App\Static\Filtrage;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class FiltrageController extends Controller
{
    public function save(Request $request)
    { 
        
        $params =[
            "first_name" => $request->first_name,
            "last_name" => $request->last_name,
            "birth_day" => $request->birth_day,
            "birth_month" => $request->birth_month,
            "birth_year" => $request->birth_year,
            "country" => $request->country,
            "adress_type" => $request->adress_type,
            "entity_type" => $request->entity_type,
            "WriteResultsToDatabase" => $request->WriteResultsToDatabase,
        ];
            $payload = self::payload($request);
 
            $result = Filtrage::insert($payload, $params);

        return response()->json($result, $result["code"]);
    }

    private static function payload(Request $data): string
    {
        $newRef = Str::uuid();
        
        $payload = '{
            "ClientContext": {
                "ClientReference": ' . '"' . $newRef . '"
            },
            "SearchConfiguration": {
                "AssignResultTo": {
                    "Division": "Toutes les divisions",
                    "EmailNotification": "false",
                    "RolesOrUsers": [
                        "Administrator"
                    ],
                    "Type": "Role"
                },
                "PredefinedSearchName": "Filtrage de paiement",
                "WriteResultsToDatabase": ' . '"' . $data->WriteResultsToDatabase . '",
                "ExcludeScreeningListMatches": "false",
                "DuplicateMatchSuppression": "false",
                "DuplicateMatchSuppressionSameDivisionOnly": "false"
            },
            "SearchInput": {
                "Records": [
                    {
                        "Entity": {
                            "EntityType": ' . '"' . $data->entity_type . '",
                            "Name": {
                                "First": ' . '"' . $data->first_name . '",
                                "Last": ' . '"' . $data->last_name . '"
                            },
                            "AdditionalInfo": [
                                {
                                    "Date": {
                                        "Day": "' . $data->birth_day . '",
                                        "Month": "' . $data->birth_month . '",
                                        "Year": "' . $data->birth_year . '"
                                    },
                                    "Type": "DOB"
                                }
                            ],
                            "Addresses": [
                                {
                                    "Country": "' . $data->country . '",
                                    "Type": "' . $data->adress_type. '"
                                }
                            ]
                        }
                    }
                ]
            }
        }'; 
        return $payload;
    }
}
