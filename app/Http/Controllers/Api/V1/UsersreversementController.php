<?php
namespace App\Http\Controllers\Api\V1;
use Illuminate\Http\Request;

class UsersreversementController extends USERSREVERSEMENT_HELPER
{
    public function __construct()
    {
        $this->middleware(['auth:api', 'scope:api-access']);
    }

    
   
    public function _Choice(Request $request)
    {
        #VERIFICATION DE LA METHOD
        if ($this->methodValidation($request->method(), "POST") == False) {
            #RENVOIE D'ERREURE VIA **sendError** DE LA CLASS BASE_HELPER HERITEE PAR CLIENT_HELPER
            return $this->sendError("La methode " . $request->method() . " n'est pas supportée pour cette requete!!", 404);
        };
        #VALIDATION DES DATAs DEPUIS LA CLASS CLIENT_HELPER
        $validator = $this->Reversementchoice_Validator($request->all());

        if ($validator->fails()) {
            #RENVOIE D'ERREURE VIA **sendResponse** DE LA CLASS BASE_HELPER HERITEE PAR Transaction_HELPER
            return $this->sendError($validator->errors(), 404);
        }

        #ENREGISTREMENT DANS LA DB VIA **createClient** DE LA CLASS BASE_HELPER HERITEE PAR Transaction_HELPER
        return $this->choice($request);
    }
}
