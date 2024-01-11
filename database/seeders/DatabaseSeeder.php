<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        ##======== CREATION D'UN ADMIN PAR DEFAUT ============####
        $users = [
            [
                'firstname' => 'Admin ',
                'lastname' => 'admin 1',
                'username' => 'admin',
                'email' => 'admin@gmail.com',
                'password' => '$2y$10$8JhR1nysW.mE1hI7CqkArelFuSLglJaBxJK5w1yLaNSpedc.4q.fq', #admin
                'phone' => "22961765590",
                'is_admin' => true,
                'compte_actif' => true,
            ],
            [
                'firstname' => 'PP JJOEL',
                'lastname' => 'admin 2',
                'username' => 'ppjjoel',
                'email' => 'ppjjoel@gmail.com',
                'password' => '$2y$10$ZT2msbcfYEUWGUucpnrHwekWMBDe1H0zGrvB.pzQGpepF8zoaGIMC', #ppjjoel
                'phone' => "22997555619",
                'is_admin' => true,
                'compte_actif' => true,
            ]
        ];
        foreach ($users as $user) {
            \App\Models\User::factory()->create($user);
        }

        ##======== CREATION DES STATUS DES TRANSACTIONS PAR DEFAUT ============####
        $transaction_status = [
            [
                'name' => 'Payée',
                'description' => 'Cette transaction a été payée',
            ],
            [
                'name' => 'Non Payée',
                'description' => 'Cette transaction a été payée',
            ],
        ];
        foreach ($transaction_status as $transaction_statu) {
            \App\Models\TransactionStatus::factory()->create($transaction_statu);
        }

        ##======== CREATION DES TYPES DES TRANSACTIONS PAR DEFAUT ============####
        $transaction_types = [
            [
                'name' => 'En attente',
                'description' => 'Cette transaction est en attente!',
            ],
            [
                'name' => 'Echec',
                'description' => 'Cette transaction a échouée!',
            ],
            [
                'name' => 'Succès',
                'description' => 'Cette transaction a été éffectuée avec succès!',
            ],
        ];
        foreach ($transaction_types as $transaction_type) {
            \App\Models\TransactionType::factory()->create($transaction_type);
        }

        #======== CREATION DES STATUS DE SOLDE PAR DEFAUT =========#
        $soldStatus = [
            [
                "name" => "traitement",
                "description" => "Ce sold est en phase de traitement",
            ],
            [
                "name" => "disponible",
                "description" => "Ce sold est disponible!",
            ]
        ];
        foreach ($soldStatus as $status) {
            \App\Models\SoldStatus::factory()->create($status);
        }

        #======== CREATION DES MODULES DE TRANSACTION PAR DEFAUT =========#
        $MODULES = [
            [
                "label" => "MTN Money",
                "description" => "Paiement via MTN money",
            ],
            [
                "label" => "MOOV Money",
                "description" => "Paiement via MOOV money",
            ],
            [
                "label" => "Carte VISA",
                "description" => "Paiement via Carte VISA",
            ],
            [
                "label" => "Carte UBA",
                "description" => "Paiement via Carte UBA",
            ]
        ];
        foreach ($MODULES as $MODULE) {
            \App\Models\PayementModule::factory()->create($MODULE);
        }

         
         ##======== CREATION DES TYPES DES TRANSACTIONS PAR DEFAUT ============####
         $reversement_statuts = [
            [
                'name' => 'En attente',
                'description' => 'Cette transaction est en attente!',
            ],
            [
                'name' => 'Echec',
                'description' => 'Cette transaction a échouée!',
            ],
            [
                'name' => 'Succès',
                'description' => 'Cette transaction a été éffectuée avec succès!',
            ],
        ];
        foreach ($reversement_statuts as $reversement_statut) {
            \App\Models\ReversementStatus::factory()->create($reversement_statut);
        }

         ##======== CREATION DES TYPES DE REVERSEMENT PAR DEFAUT ============####
         $reversement_types = [
            [
                'typerevers' => ' Reversement Periodique',
                'description' => ' Ce type de reversement vous permet de definir un intervalle de temps a laquelle vous souhaitezrecuperer vos divers avoirs',
            ],
            [
                'typerevers' => 'Reversement par palier',
                'description' => 'Ce type de reversement vous permet de specifier a partir de quelle montantdisponoble sur votre compte feepay devra vous reverser vos avoirs. Ce montant est variable, mais ne peut être inferieur a 50 000f cfa',
            ],
            [
                'typerevers' => 'Reversement instantane',
                'description' => 'Ce type de reversement permet a un marchand proffesionnel Freepay de percevoir, de façon immediat,la totalite ou une partie de son solde de disponibilite.Ce reversement permet au marchand de se faire reverser jusqu a 300.000 Fcfa par semaine!',
            ],
        ];
        foreach ($reversement_types as $reversement_type) {
            \App\Models\ReversementType::factory()->create($reversement_type);
        }
    }
}
