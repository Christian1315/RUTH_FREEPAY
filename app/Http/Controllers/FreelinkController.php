<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FreelinkController extends Controller
{
    public function Generer()
    {
        return view("lienunique");
    }

    public function Lien(Request $request)
    {
        $data = $request->all();
        $rules = [
            "motif" => ["required"],
            "titre" => ["required"],
            "montant" => ["required"],
            "moyen" => ["required"],
            "description" => ["required"],
            "logo" => ["required", "file"],

        ];

        $message = [
            "motif.required" => "Veuillez renseigner le champ *moti de paiement*",
            "titre.required" => "Veuillez renseigner le champ *titre de page*",
            "montant.required" => "Le montant est réquis!",
            "montant.numeric" => "Le montant est de format numeric!",
            "moyen.required" => "Le mayen de paiement est réquise!",
            "description.required" => "La formation est réquise!",
            "logo.required" => "La photo est réquise!",
            "logo.file" => "Ce champ doit etre une photo!",


        ];

        $request->validate(
            $rules,
            $message
        );

        $avatar = $request->file('photo');
        $avatar_name = $avatar->getClientOriginalName();
        $avatar->move("avata", $avatar_name);
        $data["photo"] = asset("avata/" . $avatar_name);
        #####___ENREGISTREMENT DANS LA DB
        etudiant::create($data);
        return redirect()->back()->with("success", "lien genérer avec succès!");
    }
}
