<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Entreprise extends Model
{
    protected $fillable=['id_user','denomination','secteur','autres_secteur','forme_juridiq','description','poste','secteur','associes','rccm','nif','supprimer'];
}
