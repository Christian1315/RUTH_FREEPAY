<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\ReversementType;
use App\Models\User;
use App\Models\Usersreversement;
use Illuminate\Support\Facades\Validator;

class USERSREVERSEMENT_HELPER extends BASE_HELPER
{
    ##======== REGISTER VALIDATION =======##
    static function reversementchoice_rules(): array
    {
        return [
            "user_id" => "required|integer",
            "reversementtype_id" => "required|integer",


        ];
    }

    static function reversementchoice_messages(): array
    {
        return [
            'user_id.required' => 'L\'utilisateur  est réquis!',
            'user_id.integer' => 'Le champ user doit être un entier',

            'reversementtype_id.required' => 'Veuillez précisez le type de reversement',
            'reversementtype_id.integer' => 'Le champ type de reversement doit être un entier',

            'periode.required' => 'Veuillez précisez la periode du reversement ',

        ];
    }

    static function reversementchoice_Validator($formDatas)
    {
        $rules = self::reversementchoice_rules();
        $messages = self::reversementchoice_messages();

        $validator = Validator::make($formDatas, $rules, $messages);
        return $validator;
    }
    static function choice($request)
    {
        $user = request()->user();
        $formData = $request->all();
        $user = User::find(request()->user()->id);
        $typerevers = ReversementType::find($formData["reversementtype_id"]);
        if (!$typerevers) {
            return self::sendError("Ce type de reversement n'existe pas!", 404);
        }

        if ($typerevers->id == 1) {
            if (!$request->get("period")) {
                return self::sendError("veillez precicez la periode du reversement!", 404);
            }
            $formData["periode"] = $request->get("period");
        }

        $formData["user_id"] = $user->id;

        ##VERIFIONS SI CE USER DISPOSE DEJA D'UN TYPE DE REVERSEMENT
        $user_reversement = Usersreversement::where(["user_id" => $user->id])->first();

        if ($user_reversement) {
            $res = $user_reversement->update($formData);
            if($user_reversement->periode){
                $user_reversement->periode = null;
                $user_reversement->save();
            }
            return self::sendResponse($user_reversement, "Votre formule de reversement a été modifié avec succès!");
        } else {
            $res = Usersreversement::create($formData);
            return self::sendResponse($res, "Choix de reversement éffectué avec succès!");
        }
    }
}
