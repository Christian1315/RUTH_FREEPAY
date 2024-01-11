<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\PayementModule;

class TRANSACTION_MODULE_HELPER extends BASE_HELPER
{
    static function allTransactionModule()
    {
        $modules =  PayementModule::orderBy("id", "desc")->get();
        return self::sendResponse($modules, 'Tout les modules de Transaction récupérés avec succès!!');
    }

    static function _retrieveTransactionModule($id)
    {
        $module = PayementModule::where(['id' => $id])->get();
        if ($module->count() == 0) {
            return self::sendError("Ce module de Transaction n'existe pas!", 404);
        }
        return self::sendResponse($module, "Module de Transaction récupéré avec succès:!!");
    }
}
