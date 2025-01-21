<?php

namespace App\Static;
use App\Models\Personne;
use Carbon\Carbon;
use App\Models\Aka;
use App\Models\Name;
use App\Models\Adress;
use App\Models\Matche;
use App\Models\Record;
use App\Models\Conflict;
use App\Models\Sanction;
use App\Models\Watchlist;
use App\Models\MatchState;
use App\Models\SourceItem;
use App\Models\RecordState;
use App\Models\EntityDetail;
use App\Models\RecordDetail;
use App\Models\Relationship;
use App\Models\AdditionalInfo;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use App\Models\AdressForRecordDetail;
use Illuminate\Support\Facades\Facade;
use Illuminate\Support\ServiceProvider;
use App\Models\AdditionalInfoForEntityDetail;

class Filtrage
{
    public static function insert($payload, array $params = [])
    {   
      
        $clientReference = "";
        $endpoint = config("app.endpoints.filtrageEndpoint", " ");

        $response = Http::withBody($payload, 'application/json')->post($endpoint);

        $apiRecords = $response->collect()["Records"] ?? null;

        if (array_key_exists("ClientReference", $response->collect()->toArray())) {
            $clientReference = $response->collect()["ClientReference"];
        }

        if (!($apiRecords == null)) {

            $date = Carbon::parse($params["birth_year"]."/".$params["birth_month"]."/".$params["birth_day"]);
          
           $personid = Personne::firstOrCreate([

                "client_ref" => $clientReference,
                "first_name" => $params["first_name"],
                "last_name" => $params["last_name"],
                "birth_date" => $date,
                "country" => $params["country"],
                "adress_type" => $params["adress_type"],
                "entity_type" => $params["entity_type"],
            ])->id;

            foreach ($apiRecords as $recordKey => $record) {
                Record::updateOrCreate([

                    "personne_id" =>  $personid,
                    "client_ref" => $clientReference,
                    "record" => $record["Record"] ?? null,
                    "result_id" => $record["ResultID"] ?? null,
                    "run_id" => $record["RunID"] ?? null,
                    "lockedAlert" => $record["LockedAlert"] ?? null,

                ]);
                
                $recordDetailId = RecordDetail::updateOrCreate([
                    "personne_id" =>  $personid ?? null,
                ], [
                    "acceptlistid" => $record["RecordDetails"]["AcceptListID"] ?? null,
                    "accountamount" => $record["RecordDetails"]["AccountAmount"] ?? null,
                    // "accountdate" => $record["RecordDetails"]["AccountDate"] == "" ? null : Carbon::parse($record["RecordDetails"]["AccountDate"]),
                    "accountdate" => empty($record["RecordDetails"]["AccountDate"]) ? null : Carbon::parse($record["RecordDetails"]["AccountDate"]),
                    "accountgroupid" => $record["RecordDetails"]["AccountGroupID"] ?? null,
                    "accountotherdata" => $record["RecordDetails"]["AccountOtherData"] ?? null,
                    "accountproviderid" => $record["RecordDetails"]["AccountProviderID"] ?? null,
                    "accountmemberid" => $record["RecordDetails"]["AccountMemberID"] ?? null,
                    "accounttype" => $record["RecordDetails"]["AccountType"] ?? null,
                    "addedtoacceptlist" => $record["RecordDetails"]["AddedToAcceptList"] ?? null,
                    "predefinedsearch" => $record["RecordDetails"]["PredefinedSearch"] ?? null,
                    "pdsversion" => $record["RecordDetails"]["PDSVersion"] ?? null,
                    "dppa" => $record["RecordDetails"]["DPPA"] ?? null,
                    "efttype" => $record["RecordDetails"]["EFTType"] ?? null,
                    "entitytype" => $record["RecordDetails"]["EntityType"] ?? null,
                    "gender" => $record["RecordDetails"]["Gender"] ?? null,
                    "glb" => $record["RecordDetails"]["GLB"] ?? null,
                    "lastupdateddate" => Carbon::parse($record["RecordDetails"]["LastUpdatedDate"]) == "" ? null : Carbon::parse($record["RecordDetails"]["LastUpdatedDate"]),
                    "searchdate" => Carbon::parse($record["RecordDetails"]["SearchDate"]) == "" ? null : Carbon::parse($record["RecordDetails"]["SearchDate"])

                ])->id;




                if ($record["RecordDetails"]["AdditionalInfo"]) {

                    if (array_key_exists("AdditionalInfo", $record["RecordDetails"])) {

                        if (count($record["RecordDetails"]["AdditionalInfo"]) > 1) {
                            foreach ($record["RecordDetails"]["AdditionalInfo"] as $key => $additionalInfos) {
                                 
                                AdditionalInfo::updateOrCreate([
                                    "personne_id" =>  $personid,  // DÃ©commentez si nÃ©cessaire
                                    "type" => $additionalInfos["Type"],
                                    "value" => $additionalInfos["Value"]
                                ]);


                            }
                        } else {
                            $additionalInfos = $record["RecordDetails"]["AdditionalInfo"][0];

                            AdditionalInfo::updateOrCreate([
                                "personne_id" =>  $personid, 
                                "type" => $additionalInfos["Type"],
                                "value" => $additionalInfos["Value"]
                            ]);
                        }

                    }

                }


                if ($record["RecordDetails"]["Addresses"]) {
                    foreach ($record["RecordDetails"]["Addresses"] as $adress) {

                        AdressForRecordDetail::updateOrCreate([
                            "personne_id" =>  $personid,
                            "country" => $adress["Country"] ?? null,
                            "type" => $adress["Type"] ?? null
                        ]);
                    }
                }



                $watchList = Watchlist::updateOrCreate([
                    "personne_id" =>  $personid,
                    "status" => $record["Watchlist"]["Status"]
                ])->id;

                if ($record["Watchlist"]["Matches"]) {
                    foreach ($record["Watchlist"]["Matches"] as $matche) {
                        $matchId = Matche::updateOrCreate(
                            [
                                "personne_id" =>  $personid,
                                "acceptlistid" => $matche["AcceptListID"] ?? null,
                                "addedtoacceptlist" => $matche["AddedToAcceptList"] ?? null,
                                "addressname" => $matche["AddressName"] ?? null,
                                "autofalsepositive" => $matche["AutoFalsePositive"] ?? null,
                                "bestaddressispartial" => $matche["BestAddressIsPartial"] ?? null,

                            ],
                            [

                                "bestcountry" => $matche["BestCountry"] ?? null,
                                "bestcountryscore" => $matche["BestCountryScore"] ?? null,
                                "bestcountrytype" => $matche["BestCountryType"] ?? null,
                                "bestdobispartial" => $matche["BestDOBIsPartial"] ?? null,
                                "bestname" => $matche["BestName"] ?? null,
                                "bestnamescore" => $matche["BestNameScore"] ?? null,
                                "checksum" => $matche["CheckSum"] ?? null,
                                "entityname" => $matche["EntityName"] ?? null,
                                "entityscore" => $matche["EntityScore"] ?? null,
                                "entityuniqueid" => $matche["EntityUniqueID"] ?? null,
                                "falsepositive" => $matche["FalsePositive"] ?? null,
                                "gatewayofacscreeningindicatormatch" => $matche["GatewayOFACScreeningIndicatorMatch"] ?? null,
                                "id_from_api" => $matche["ID"] ?? null,
                                "matchrealert" => $matche["MatchReAlert"] ?? null,
                                "previousresultid" => $matche["PreviousResultID"] ?? null,
                                "reasonlisted" => $matche["ReasonListed"] ?? null,
                                "resultdate" => $matche["ResultDate"] == "" ? null : Carbon::parse($matche["ResultDate"]),
                                "secondaryofacscreeningindicatormatch" => $matche["SecondaryOFACScreeningIndicatorMatch"],
                                "truematch" => $matche["TrueMatch"] ?? null,
                                "datemodified" => $matche["DateModified"] == "" ? null : Carbon::parse($matche["DateModified"]),
                                "status" => $matche["Status"] ?? null,
                            ]

                        )->id;



                        Conflict::updateOrCreate([
                            "personne_id" =>  $personid,
                            "addressconflict" => $matche["Conflicts"]["AddressConflict"] ?? null,
                            "citizenshipconflict" => $matche["Conflicts"]["CitizenshipConflict"] ?? null,
                            "countryconflict" => $matche["Conflicts"]["CountryConflict"] ?? null,
                            "dobconflict" => $matche["Conflicts"]["DOBConflict"] ?? null,
                            "entitytypeconflict" => $matche["Conflicts"]["EntityTypeConflict"] ?? null,
                            "genderconflict" => $matche["Conflicts"]["GenderConflict"] ?? null,
                            "idconflict" => $matche["Conflicts"]["IDConflict"] ?? null,
                            "phoneconflict" => $matche["Conflicts"]["PhoneConflict"] ?? null,

                        ]);

                        if (array_key_exists("AdditionalInfo", $matche["EntityDetails"])) {
                            if ($matche["EntityDetails"]["AdditionalInfo"]) {
                                foreach ($matche["EntityDetails"]["AdditionalInfo"] as $additionalInfos) {
                                    AdditionalInfoForEntityDetail::updateOrCreate([
                                        "personne_id" =>  $personid ?? null,
                                        "type" => $additionalInfos["Type"] ?? null,
                                        "value" => $additionalInfos["Value"] ?? null
                                    ]);

                                }


                            }
                        }




                        $entityDetailId = EntityDetail::updateOrCreate([
                            "personne_id" =>  $personid ?? null,
                            "datelisted" => $matche["EntityDetails"]["DateListed"] ?? null,
                            "EntityType" => $matche["EntityDetails"]["EntityType"] ?? null,
                            "gender" => $matche["EntityDetails"]["Gender"] ?? null,
                            "listReferenceNumber" => $matche["EntityDetails"]["ListReferenceNumber"] ?? null,
                            "comments" => $matche["EntityDetails"]["Comments"] ?? null

                        ])->id;

                        if (array_key_exists("Addresses", $matche["EntityDetails"])) {
                            if ($matche["EntityDetails"]["Addresses"]) {
                                foreach ($matche["EntityDetails"]["Addresses"] as $adress) {
                                    Adress::updateOrCreate([
                                        "personne_id" =>  $personid,
                                        "country" => $adress["Country"] ?? null,
                                        "type" => $adress["Type"] ?? null,
                                        "category" => $adress["Category"] ?? null
                                    ]);
                                }
                            }
                        }

                        if (array_key_exists("Sanctions", $matche)) {
                            if ($matche["Sanctions"]) {
                                foreach ($matche["Sanctions"] as $sanction) {
                                    Sanction::updateOrCreate([
                                        "personne_id" =>  $personid ?? null,
                                        "country" => $sanction["Country"] ?? null,
                                        "source" => $sanction["Source"] ?? null,
                                        "dateModified" => $sanction["DateModified"] == null ? null : Carbon::parse($sanction["DateModified"])
                                    ]);
                                }
                            }
                        }


                        if (array_key_exists("AKAs", $matche["EntityDetails"])) {
                            if ($matche["EntityDetails"]["AKAs"]) {
                                foreach ($matche["EntityDetails"]["AKAs"] as $aka) {

                                    Aka::updateOrCreate([
                                       "personne_id" =>  $personid,
                                        "category" => $aka["Category"] ?? null,
                                        "first" => $aka["Name"]["First"] ?? null,
                                        "full" => $aka["Name"]["Full"] ?? null,
                                        "last" => $aka["Name"]["Last"] ?? null,
                                        "type" => $aka["Type"] ?? null,
                                        "middle" => $aka["Middle"] ?? null,
                                        "scripttype" => $aka["ScriptType"] ?? null,
                                    ]);
                                }
                            }
                        }



                    }
                }


                if (array_key_exists("Relationships", $matche)) {
                    if ($matche["Relationships"]) {
                        foreach ($matche["Relationships"] as $relationShip) {

                            Relationship::updateOrCreate([
                                "personne_id" =>  $personid ?? null,

                                "group" => $relationShip["Group"] ?? null,
                                "type" => $relationShip["Type"] ?? null,
                                "entityId" => $relationShip["EntityId"] ?? null,
                                "datemodified" => $relationShip["DateModified"] == null ? null : Carbon::parse($sanction["DateModified"]),
                                "entityname" => $relationShip["EntityName"] ?? null,
                                "ownershippercentage" => $relationShip["OwnershipPercentage"] ?? null,
                                "segments" => $relationShip["Segments"] ?? null,

                            ]);
                        }
                    }
                }


                if (array_key_exists("SourceItems", $matche)) {
                    if ($matche["SourceItems"]) {
                        foreach ($matche["SourceItems"] as $sourceItem) {

                            SourceItem::updateOrCreate([

                                "personne_id" =>  $personid ?? null,
                                "sourceURI" => $sourceItem["SourceURI"] ?? null,
                                "datemodified" => $sourceItem["DateModified"] == null ? null : Carbon::parse($sourceItem["DateModified"]),

                            ]);
                        }
                    }
                }


                $recordState = RecordState::updateOrCreate([
                    "personne_id" =>  $personid,
                    "addedtoacceptlist" => $record["RecordDetails"]["RecordState"]["AddedToAcceptList"],
                    "alertstate" => $record["RecordDetails"]["RecordState"]["AlertState"],
                    "assignmenttype" => $record["RecordDetails"]["RecordState"]["AssignmentType"],
                    "status" => $record["RecordDetails"]["RecordState"]["Status"],
                ])->id;


                foreach ($record["RecordDetails"]["RecordState"]["MatchStates"] as $matchState) {

                    MatchState::updateOrCreate([
                       "personne_id" =>  $personid,
                        "match_id" => $matchState["MatchID"],
                        "type" => $matchState["Type"]
                    ]);
                }

            }

            return [
                "messsage" => "Records found for client Reference: " . $clientReference . ". Database Insertion sussessfull!!!",
                "code" => 200
            ];
        }

        if (!$clientReference == null) {
            return [
                "message" => "No records found for client Reference: " . $clientReference,
                "code" => 201
            ];
        } else {
            return [
                "message" => $response->body()." FROM ORIGINAL API",
                "code" => 202
            ];
        }

    }
}