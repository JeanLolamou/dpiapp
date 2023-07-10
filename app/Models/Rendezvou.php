<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rendezvou extends Model
{
    protected $fillable=['id_projet','id_promoteur','id_investisseur','date','heure','lieu','lien','details','statut','commentaire','q_annule','fichier','typerencontre','supprimer'];
}
