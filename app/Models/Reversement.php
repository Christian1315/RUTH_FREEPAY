<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reversement extends Model
{
    use HasFactory;
    protected $fillable = [
        "amount",
        "date_reversement",
        "moyen_paiement",  
        "statut",

    ];
}
