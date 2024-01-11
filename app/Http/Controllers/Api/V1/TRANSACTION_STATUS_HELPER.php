<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\TransactionStatus;

class TRANSACTION_STATUS_HELPER extends BASE_HELPER
{
    static function allTransactionStatus()
    {
        $Transaction_status =  TransactionStatus::orderBy("id", "desc")->get();
        return self::sendResponse($Transaction_status, 'Tout les status de Transaction récupérés avec succès!!');
    }

    static function _retrieveTransactionStatus($id)
    {
        $Transaction_status = TransactionStatus::where(['id' => $id])->get();
        if ($Transaction_status->count() == 0) {
            return self::sendError("Ce status de Transaction n'existe pas!", 404);
        }
        return self::sendResponse($Transaction_status, "Status de Transaction récupéré avec succès:!!");
    }
}
