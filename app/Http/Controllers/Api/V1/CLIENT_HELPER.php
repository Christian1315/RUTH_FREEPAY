<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Client;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class CLIENT_HELPER extends BASE_HELPER
{
    ##======== REGISTER VALIDATION =======##
    static function client_rules(): array
    {
        return [
            "name" => "required",
            'email' => ['required', 'email', Rule::unique("clients")],
            'phone' => ['required', Rule::unique("clients")],
            'country' => 'required',
        ];
    }

    static function client_messages(): array
    {
        return [
            'name.required' => 'Le name du client est réquis!',
            'email.email' => 'Le format mail n\'est pas respecté',
            'email.required' => 'Le champ mail est requis!',
            'email.unique' => 'Un client existe déjà au nom de ce mail!',

            'phone.required' => 'Veuillez précisez votre numéro téléphone',
            'phone.unique' => 'Un client existe déjà au nom de ce phone!',

            'country.reauired' => 'Le pays est réquis',
        ];
    }

    static function Client_Validator($formDatas)
    {
        $rules = self::client_rules();
        $messages = self::client_messages();

        $validator = Validator::make($formDatas, $rules, $messages);
        return $validator;
    }

    static function createClient($request)
    {
        $user = request()->user();
        $formData = $request->all();

        ##ENREGISTREMENT DU CLIENT
        $client = Client::create($formData);
        return self::sendResponse($client, 'Client ajouté avec succès!!');
    }

    static function retrieveClient($id)
    {
        $client = Client::find($id);
        if (!$client) {
            return self::sendError('Ce client n\'existe pas!', 404);
        };
        return self::sendResponse($client, "Client récupéré avec succès");
    }

    static function clients()
    {
        $clients = Client::orderBy('id', 'desc')->get();
        return self::sendResponse($clients, 'Liste des clients récupérés avec succès!!');
    }

    static function updateClient($request, $id)
    {
        $user = request()->user();
        $formData = $request->all();
        $client = Client::find($id);

        if (!$client) {
            return self::sendError("Ce client n'existe pas!", 404);
        }

        if ($request->get("phone")) {
            $phone = Client::where(["phone" => $request->get("phone")])->first();
            if ($phone) {
                return self::sendError("Ce phone existe déjà", 505);
            }
        }

        if ($request->get("email")) {
            $email = Client::where(["email" => $request->get("email")])->first();
            if ($email) {
                return self::sendError("Ce email existe déjà", 505);
            }
        }

        $client->update($formData);
        return self::sendResponse($client, "Client modifié avec succès!!");
    }

    static function deleteClient($id)
    {
        $user = request()->user();
        $client = Client::find($id);

        if (!$client) {
            return self::sendError("Ce Client n'existe pas", 404);
        }
        $client->delete(); ### SUPPRESSION DU CLIENT;
        return self::sendResponse($client, "Client a été supprimé avec succès!!");
    }
}
