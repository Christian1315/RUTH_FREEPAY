<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Client;
use App\Models\DeveloperKey;
use App\Models\Transaction;
use App\Models\TransactionStatus;
use App\Models\TransactionType;
use App\Models\Transport;
use App\Models\Type;
use App\Models\User;
use Illuminate\Support\Facades\Validator;

class TRANSACTION_HELPER extends BASE_HELPER
{
    ##======== REGISTER VALIDATION =======##
    static function transaction_rules(): array
    {

        return [
            "amount" => "required|integer",
            'type' => 'required|integer',
            'client' => 'required|integer',
            'module' => 'required|integer',
            'account_owner' => 'required|integer',
        ];
    }

    static function transaction_messages(): array
    {
        return [
            'amount.required' => 'Le montant de la transaction est réquise!',
            'amount.integer' => 'Le montant de la transaction doit être un entier!',
            'type.required' => 'Veuillez précisez le type de moyen de transport que vous essayez d\'ajouter',
            'type.integer' => 'Le type de transaction doit être un entier',
            'client.required' => 'Le champ client est requis',
            'client.integer' => 'Le champ client doit être un entier',
            'module.required' => 'Le champ module est requis',
            'module.integer' => 'Le champ module doit être un entier',
            'account_owner.required' => 'Le champ compte marchand est requis',
            'account_owner.integer' => 'Le champ  doit être un entier',

        ];
    }

    static function Transaction_Validator($formDatas)
    {
        $rules = self::transaction_rules();
        $messages = self::transaction_messages();

        $validator = Validator::make($formDatas, $rules, $messages);
        return $validator;
    }

    static function createTransaction($request)
    {

        $user = request()->user();
        $formData = $request->all();
        ### VERIFIONS SI LA REQUETE CONTIENT la Clé API dans le header
        $key = $request->header()['key'][0];
        $user_id = $request->header()['id'][0];

        if ($key == '') {
            return self::sendError("La clé API est réquise dans le header", 505);
        }

        if ($user_id == '') {
            return self::sendError("Veuillez renseigner votre ID", 505);
        }

        ### VERIFIONS SI Clé API Existe
        $dev = DeveloperKey::where(["private_key" => $key])->get();
        if ($dev->count() == 0) {
            return self::sendError("La clé API n'existe pas", 505);
        }

        ### VERIFIONS SI Clé API EST VALIDE(voyons si elle appartient au user en question)
        $dev = DeveloperKey::where(["private_key" => $key, "owner" => $user_id])->get();
        if ($dev->count() == 0) {
            return self::sendError("La clé API ne vous appartient pas", 505);
        }

        ##VERIFIONS SI CE CLIENT EXISTE DEJA
        $client = Client::find($formData['email']);
        if (!$client) {
            $client = new Client();
            $client->name = 'modo';
            $client->phone = '97665852';
            $client->email = 'toto@gmail.com';
            $client->country = 'benin';
            $client->save();
        }


        ###__
        $type = TransactionType::find($formData['type']);
        if (!$type) {
            return self::sendError('Ce type de transaction n\' existe pas!', 404);
        }

        ##__

        $client = Client::find($formData['client']);
        if (!$client) {
            return self::sendError('Ce client n\' existe pas!', 404);
        }
        $formData["date_transaction"] = now();
        $formData["status"] = 2;
        $formData["transaction_id"] = TRANSACTION_ID($user);
        $formData["transaction_amount"] = TRANSACTION_COMMISSION($formData['amount']);
        $formData["payment_amount"] = $formData['amount'] - TRANSACTION_COMMISSION($formData['amount']);

        ##ENREGISTREMENT DE LA TRANSACTION
        $transaction = Transaction::create($formData);
        return self::sendResponse($transaction, 'Transaction effectuée avec succès!!');
    }

    static function retrieveTransaction($id)
    {
        $transaction = Transaction::with(["type", "client", "status"])->where(["id" => $id])->find($id);
        if (!$transaction) {
            return self::sendError('Cette transaction n\'existe pas!', 404);
        };
        return self::sendResponse($transaction, "Transport récupéré avec succès");
    }

    static function transactions()
    {
        $transactions = Transaction::with(["type", "client", "status"])->orderBy('id', 'desc')->get();
        return self::sendResponse($transactions, 'Liste des transactions récupérés avec succès!!');
    }

    static function updateTransaction($request, $id)
    {
        $user = request()->user();
        $formData = $request->all();
        $transaction = Transaction::find($id);

        if (!$transaction) {
            return self::sendError("Cette transaction n'existe pas", 404);
        }

        if ($request->get("status")) {
            $status = TransactionStatus::find($request->get("status"));
            if (!$status) {
                return self::sendError("Ce status de transaction n'existe pas!", 404);
            }
            $transaction->status = $request->get("status");
        }

        if ($request->get("type")) {
            $type = TransactionType::find($request->get("type"));
            if (!$type) {
                return self::sendError("Ce type de transaction n'existe pas!", 404);
            }
            $transaction->type = $request->get("type");
        }


        if ($request->get("client")) {
            $client = Client::find($request->get("client"));

            if (!$client) {
                return self::sendError("Ce client n'existe pas!", 404);
            }
            $transaction->client = $request->get("client");
        }

        $transaction->update($formData);
        $transaction->save();
        //return self::sendResponse($transaction, "Transaction modifiée avec succès!!");
    }

    static function deleteTransaction($id)
    {
        $user = request()->user();
        $transaction = Transaction::find($id);

        if (!$transaction) {
            return self::sendError("Cette transaction n'existe pas", 404);
        }
        $transaction->delete(); ### SUPPRESSION DE LA TRANSACTION;
        return self::sendResponse($transaction, "Transaction a été supprimée avec succès!!");
    }
}
