<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Reversement;
use App\Models\ReversementType;
use App\Models\User;
use App\Models\Usersreversement;
use DateTimeImmutable;
use Illuminate\Support\Facades\Validator;

class REVERSEMENT_HELPER extends BASE_HELPER
{
    ##======== REGISTER VALIDATION =======##
    static function reversement_rules(): array
    {
        return [
            "amount" => "required",
            'moyen_paiement' => 'required',

        ];
    }

    static function reversement_messages(): array
    {
        return [
            'amount.required' => 'Le montant à reverser au client est réquis!',
            'moyen_paiement.required' => 'Veuillez précisez votre numéro téléphone',

        ];

    }
 
    static function reversement_Validator($formDatas)
    {
        $rules = self::reversement_rules();
        $messages = self::reversement_messages();

        $validator = Validator::make($formDatas, $rules, $messages);
        return $validator;
    }


    static function createReversement($request)
    {
        $user = request()->user();
        $formData = $request->all();
        $formData["date_reversement"] = now();
        $formData["status"] = 2;
        ##ENREGISTREMENT DU REVERSEMENT
        $reversement = Reversement::create($formData);
        return self::sendResponse($reversement, 'reversement effectué avec succès!!');
    }

    static function retrieveReversement($id)
    {
        $reversement = Reversement::find($id);
        if (!$reversement) {
            return self::sendError('Ce client n\'existe pas!', 404);
        };
        return self::sendResponse($reversement, "Reversement récupéré avec succès");
    }

    static function Reversements()
    {
        $reversement = Reversement::orderBy('id', 'desc')->get();
        return self::sendResponse($reversement, 'Liste des reversements récupérés avec succès!!');
    }

    static function updateReversement($request, $id)
    {
        $user = request()->user();
        $formData = $request->all();
        $reversement = Reversement::find($id);

        if (!$reversement) {
            return self::sendError("Ce Reversement n'existe pas!", 404);
        }

        if ($request->get("phone")) {
            $phone = Reversement::where(["phone" => $request->get("phone")])->first();
            if ($phone) {
                return self::sendError("Ce phone existe déjà", 505);
            }
        }

        if ($request->get("email")) {
            $email = Reversement::where(["email" => $request->get("email")])->first();
            if ($email) {
                return self::sendError("Ce email existe déjà", 505);
            }
        }

        $reversement->update($formData);
        return self::sendResponse($reversement, "Reversement modifié avec succès!!");
    }

    static function deleteReversement($id)
    {
        $user = request()->user();
        $reversement = Reversement::find($id);

        if (!$reversement) {
            return self::sendError("Ce Reversement n'existe pas", 404);
        }
        $reversement->delete(); ### SUPPRESSION DU CLIENT;
        return self::sendResponse($reversement, "Reversement a été supprimé avec succès!!");
    }
}
