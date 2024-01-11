<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ReversementType extends Model
{
    use HasFactory;
    protected $fillable = [
        "typerevers",
        "description",  

    ];
    function users(): HasMany
    {
        return $this->hasMany(User::class, "reversement_type");
    }
    
}
