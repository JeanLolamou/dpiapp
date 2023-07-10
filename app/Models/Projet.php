<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Projet extends Model
{
    protected $fillable=['id_user','libelle','entreprise','ville_realisation','nombre_employ_actuel','nombre_employ_prev','ca_prev','cout_total','apport','financement_dem','resume','secteurActivite','fichier','fichierEn','bsplan','supprimer'];
}
