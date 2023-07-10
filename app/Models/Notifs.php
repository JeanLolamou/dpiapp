<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notifs extends Model
{
    protected $fillable=['type','id_promoteur','id_investisseur','statut','id_admin'];
}
