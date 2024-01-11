<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\TransactionType;

class TRANSACTION_TYPE_HELPER extends BASE_HELPER
{
    static function allTransactionType()
    {
        $Transaction_Type =  TransactionType::orderBy("id", "desc")->get();
        return self::sendResponse($Transaction_Type, 'Tout les Type de Transaction récupérés avec succès!!');
    }

    static function _retrieveTransactionType($id)
    {
        $Transaction_Type = TransactionType::where(['id' => $id])->get();
        if ($Transaction_Type->count() == 0) {
            return self::sendError("Ce Type de Transaction n'existe pas!", 404);
        }
        return self::sendResponse($Transaction_Type, "Type de Transaction récupéré avec succès:!!");
    }
}
