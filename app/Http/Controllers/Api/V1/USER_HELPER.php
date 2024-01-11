<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\DeveloperKey;
use App\Models\User;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class USER_HELPER extends BASE_HELPER
{
    ##======== REGISTER VALIDATION =======##
    static function register_rules(): array
    {
        return [
            'lastname' => ['required'],
            'firstname' => ['required'],
            'email' => ['required', 'email', Rule::unique('users')],
            'phone' => ['required', "numeric", Rule::unique("users")],
            'password' => ['required'],
        ];
    }

    static function register_messages(): array
    {
        return [
            'lastname.required' => 'Le lastname est réquis!',
            'firstname.required' => 'Le firstname est réquis!',

            'email.required' => 'Le champ Email est réquis!',
            'email.email' => 'Le format mail n\'est pas respecté!',
            'email.unique' => 'Ce mail existe déjà!',

            'phone.required' => 'Le champ Phone est réquis!',
            'phone.numeric' => 'Le champ Phone doit être de type numéric!',
            'phone.unique' => 'Ce Phone existe déjà!',
            'password.required' => 'Le champ Password est réquis!',
        ];
    }

    static function Register_Validator($formDatas)
    {
        $rules = self::register_rules();
        $messages = self::register_messages();

        $validator = Validator::make($formDatas, $rules, $messages);
        return $validator;
    }

    ##======== ADDING A USER =======##
    static function add_user_rules(): array
    {
        return [
            'role' => ['required', "integer"],
            'phone' => ['required', "integer", Rule::unique("users")],
            'email' => ['required', 'email', Rule::unique('users')],
            'password' => ['required', Rule::unique('users')],
        ];
    }

    static function add_user_messages(): array
    {
        return [
            'role.required' => 'Le champ role est réquis!',
            'role.integer' => 'Le champ role doit être un entier!',

            'phone.required' => 'Le champ Phone est réquis!',
            'phone.integer' => 'Le champ Phone doit être un entier!',
            'phone.unique' => 'Ce Phone existe déjà!',
            'email.required' => 'Le champ Email est réquis!',
            'email.email' => 'Ce champ est un mail!',
            'email.unique' => 'Ce mail existe déjà!',
            'password.required' => 'Le champ Password est réquis!',
            'password.unique' => 'Ce mot de passe existe déjà!!',
        ];
    }

    static function Add_user_Validator($formDatas)
    {
        $rules = self::add_user_rules();
        $messages = self::add_user_messages();

        $validator = Validator::make($formDatas, $rules, $messages);
        return $validator;
    }

    ##======== LOGIN VALIDATION =======##
    static function login_rules(): array
    {
        return [
            'account' => 'required',
            'password' => 'required',
        ];
    }

    static function login_messages(): array
    {
        return [
            'account.required' => 'Le account est réquis!',
            'password.required' => 'Le champ Password est réquis!',
        ];
    }

    static function Login_Validator($formDatas)
    {
        $rules = self::login_rules();
        $messages = self::login_messages();
        $validator = Validator::make($formDatas, $rules, $messages);
        return $validator;
    }

    ##======== NEW PASSWORD VALIDATION =======##
    static function NEW_PASSWORD_rules(): array
    {
        return [
            'old_password' => 'required',
            'new_password' => 'required',
        ];
    }

    static function NEW_PASSWORD_messages(): array
    {
        return [
            // 'new_password.required' => 'Veuillez renseigner soit votre username,votre phone ou soit votre email',
            // 'password.required' => 'Le champ Password est réquis!',
        ];
    }

    static function NEW_PASSWORD_Validator($formDatas)
    {
        $rules = self::NEW_PASSWORD_rules();
        $messages = self::NEW_PASSWORD_messages();

        $validator = Validator::make($formDatas, $rules, $messages);
        return $validator;
    }

    #CREATION D'UN USER
    static function createUser($request)
    {
        $formData = $request->all();
        // return $formData;
        $user = User::create($formData); #ENREGISTREMENT DU USER DANS LA DB
        $username = Get_Username($user, "FRKP_");
        $user->username = $username;

        $active_compte_code = Get_compte_active_Code($user, "ACT");
        $user->active_compte_code = $active_compte_code;
        $user->compte_actif = 0;
        $user->save();

        ##CREATION DU DEVELOPPER  PRIVATE API DU USER
        $Developer = new DeveloperKey();
        $Developer->public_key = Str::uuid();
        $Developer->private_key = Str::uuid();
        $Developer->owner = $user->id;
        $Developer->save();

        


        #===== ENVOIE D'SMS AU USER DU COMPTE =======~####

        $compte_msg = "Votre compte a été crée avec succès sur FREEPAY";

        $account_activation_code = "http://127.0.0.1:8000/auth/register/activate?code=" . $active_compte_code;
        $compte_activation_msg = "Votre compte n'est pas encore actif. Veuillez l'activer en utilisant le ci-dessous : " . $account_activation_code;

        #=====ENVOIE D'EMAIL =======~####
        Send_Notification(
            $user,
            "CREATION DE COMPTE SUR FREEPAY",
            $compte_msg,
        );
        Send_Notification(
            $user,
            "ACTIVATION DE COMPTE SUR FREEPAY",
            $compte_activation_msg,
        );
        return self::sendResponse($user, 'Compte crée avec succès! Veuillez le valider en consultant votre mail!');
    }

    static function activateAccount($request)
    {
        if (!$request->get("active_compte_code")) {
            return self::sendError("Le Champ **active_compte_code** est réquis", 505);
        }
        $user =  User::where(["active_compte_code" => $request->active_compte_code])->get();
        if ($user->count() == 0) {
            return self::sendError("Ce Code ne corresponds à aucun compte! Veuillez saisir le vrai code", 505);
        }

        $user = $user[0];
        ###VERIFIONS SI LE COMPTE EST ACTIF DEJA
        if ($user->compte_actif) {
            return self::sendError("Ce compte est déjà actif!", 505);
        }

        $user->compte_actif = 1;
        $user->save();
        return self::sendResponse($user, 'Votre compte à été activé avec succès!!');
    }

    static function userAuthentification($request)
    {
        if (is_numeric($request->get('account'))) {
            $credentials  =  ['phone' => $request->get('account'), 'password' => $request->get('password')];
        } elseif (filter_var($request->get('account'), FILTER_VALIDATE_EMAIL)) {
            $credentials  =  ['email' => $request->get('account'), 'password' => $request->get('password')];
        } else {
            $credentials  =  ['username' => $request->get('account'), 'password' => $request->get('password')];
        }

        if (Auth::attempt($credentials)) { #SI LE USER EST AUTHENTIFIE
            $user = Auth::user();

            ###VERIFIONS SI LE COMPTE EST ACTIF
            if (!$user->compte_actif) {
                return self::sendError("Ce compte n'est pas actif! Veuillez l'activer", 404);
            }

            $token = $user->createToken('MyToken', ['api-access'])->accessToken;
            $user["token"] = $token;
            #RENVOIE D'ERREURE VIA **sendResponse** DE LA CLASS BASE_HELPER
            return self::sendResponse($user, 'Vous etes connecté(e) avec succès!!');
        }

        #RENVOIE D'ERREURE VIA **sendResponse** DE LA CLASS BASE_HELPER
        return self::sendError('Connexion échouée! Vérifiez vos données puis réessayez à nouveau!', 500);
    }

    static function getUsers()
    {
        $users =  User::orderBy("id", "desc")->get();
        return self::sendResponse($users, 'Tout les utilisatreurs récupérés avec succès!!');
    }

    static function _updatePassword($formData)
    {
        $user = User::where(['id' => request()->user()->id])->get();
        if (count($user) == 0) {
            return self::sendError("Ce compte ne vous appartient pas!", 404);
        };

        #### VERIFIONS SI LE NOUVEAU PASSWORD CORRESPONDS ENCORE AU ANCIEN PASSWORD
        if ($formData["old_password"] == $formData["new_password"]) {
            return self::sendError('Le nouveau mot de passe ne doit pas etre identique à votre ancien mot de passe', 404);
        }

        if (Hash::check($formData["old_password"], $user[0]->password)) { #SI LE old_password correspond au password du user dans la DB
            $user[0]->update(["password" => $formData["new_password"]]);
            return self::sendResponse($user, 'Mot de passe modifié avec succès!');
        }
        return self::sendError("Votre mot de passe est incorrect", 505);
    }

    static function _updateUser($request)
    {
        $user = request()->user();
        $user = User::find($user->id);
        if (!$user) {
            return self::sendError("Ce compte ne vous appartient pas!", 404);
        };

        // $formData = $request->all();
        if ($request->get("firstname")) {
            $user->firstname = $request->get("firstname");
        }
        if ($request->get("lastname")) {
            $user->lastname = $request->get("lastname");
        }
        if ($request->get("username")) {
            $user->username = $request->get("username");
        }
        if ($request->get("phone")) {
            $user->phone = $request->get("phone");
        }
        if ($request->get("email")) {
            $user->email = $request->get("email");
        }

        $user->save();
        return self::sendResponse($user, "Utilisateur modifié avec succès!!");
    }

    static function _demandReinitializePassword($request)
    {

        if (!$request->get("account")) {
            return self::sendError("Le Champ account est réquis!", 404);
        }
        $account = $request->get("account");

        $user = null;
        if (is_numeric($account)) {
            $user = User::where(['phone' => $account])->get();
        } elseif (filter_var($account, FILTER_VALIDATE_EMAIL)) {
            $user = User::where(['email' => $account])->get();
        }
        if (!$user) {
            return self::sendError("Ce compte n'existe pas!", 404);
        };

        $user = $user[0];
        $pass_code = Get_passCode($user, "PASS");
        $user->pass_code = $pass_code;
        $user->pass_code_active = 1;
        $user->save();

        $message = "Demande de réinitialisation éffectuée avec succès sur FREEPAY! Voici vos informations de réinitialisation de password ::" . $pass_code;

        #=====ENVOIE D'EMAIL =======~####
        Send_Notification(
            $user,
            "DEMANDE DE REINITIALISATION DE COMPTE SUR FREEPAY",
            $message,
        );

        return self::sendResponse($user, "Demande de réinitialisation éffectuée avec succès! Un message vous a été envoyé par mail");
    }

    static function _reinitializePassword($request)
    {

        $pass_code = $request->get("pass_code");

        if (!$pass_code) {
            return self::sendError("Ce Champ pass_code est réquis!", 404);
        }

        $new_password = $request->get("new_password");

        if (!$new_password) {
            return self::sendError("Ce Champ new_password est réquis!", 404);
        }

        $user = User::where(['pass_code' => $pass_code])->get();

        if (count($user) == 0) {
            return self::sendError("Ce code n'est pas correct!", 404);
        };

        $user = $user[0];
        #Voyons si le passs_code envoyé par le user est actif

        if ($user->pass_code_active == 0) {
            return self::sendError("Ce Code a déjà été utilisé une fois! Veuillez faire une autre demande de réinitialisation", 404);
        }

        #UPDATE DU PASSWORD
        $user->update(['password' => $new_password]);

        #SIGNALONS QUE CE pass_code EST D2J0 UTILISE
        $user->pass_code_active = 0;
        $user->save();


        $message = "Réinitialisation de password éffectuée avec succès sur FREEPAY!";

        #=====ENVOIE D'EMAIL =======~####
        Send_Notification(
            $user,
            "REINITIALISATION DE COMPTE SUR FREEPAY",
            $message,
        );

        return self::sendResponse($user, "Réinitialisation éffectuée avec succès!");
    }

    static function retrieveUsers($id)
    {
        $user = User::find($id);
        if (!$user) {
            return self::sendError("Ce utilisateur n'existe pas!", 404);
        }
        return self::sendResponse($user, "Utilisateur récupé avec succès:!!");
    }

    static function userLogout($request)
    {
        $request->user()->token()->revoke();
        return self::sendResponse([], 'Vous etes déconnecté(e) avec succès!');
    }
}
