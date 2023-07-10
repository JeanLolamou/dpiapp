<?php 
use Illuminate\Support\Facades\DB;

if (! function_exists('active_route')) {
	function active_route($route){

		return Route::is($route) ? 'current-menu-item' : '';


	}
}

if (! function_exists('langue')) {
    function langue(){
        
        $langue=Session::get('langue');
        if ($langue=="") {
            $langue="Fr";
        }
        return $langue;

    }
}


if (! function_exists('nomEntreprise')) {
	function nomEntreprise($id){
        $nom="";
		$entreprise=DB::table('entreprises')->where('id_user',$id)->get();
		foreach ($entreprise as $entreprise) {
			$nom=$entreprise->denomination;
		}
        return $nom;

	}
}

if (! function_exists('nomUser')) {
	function nomUser($id){
        $nom="";
		$users=DB::table('users')->where('id',$id)->get();
		foreach ($users as $users) {
			$nom=$users->name;
			$nom.=' ';
			$nom.=$users->prenom;
		}
        return $nom;

	}
}

if (! function_exists('descriptionEntreprise')) {
	function descriptionEntreprise($id){
        $nom="";
		$entreprise=DB::table('entreprises')->where('id_user',$id)->get();
		foreach ($entreprise as $entreprise) {
			$nom=$entreprise->description;
		}
        return $nom;

	}
}

if (! function_exists('secteurEntreprise')) {
	function secteurEntreprise($id){
        $nom="";
		$entreprise=DB::table('entreprises')->where('id_user',$id)->get();
		foreach ($entreprise as $entreprise) {
			$nom=$entreprise->secteur;
		}
        return $nom;

	}
}

if (! function_exists('nomProjet')) {
	function nomProjet($id){
        $nom="";
		$entreprise =DB::table('projets')->where('id_user',$id)->get();
		foreach ($entreprise as $entreprise) {
			$nom=$entreprise->libelle;
		}
        return $nom;

	}
}

if (! function_exists('nomFichier')) {
	function nomFichier($id){
        $nom="";
		$entreprise =DB::table('projets')->where('id_user',$id)->get();
		foreach ($entreprise as $entreprise) {
			$nom=$entreprise->fichier;
		}
        return $nom;

	}
}

if (! function_exists('participation')) {
	function participation($type){
		$users =DB::table('users')->where([['participation',$type],['supprimer','=',0]])->get();
		return $users;

	}
}

if (! function_exists('indecis')) {
	function indecis(){
		$users =DB::table('users')
		->where([['userType','!=','Super-Administrateur'],['supprimer','=',0]])
		->whereNull('participation')->get();
		return $users;

	}
}

if (! function_exists('participationPays')) {
	function participationPays($pays){

		$users =DB::table('users')->where([['nationalite',$pays],['supprimer','=',0]])->get();

		return $users;

	}
}



if (! function_exists('getProjetId')) {
	function getProjetId($id_user){
        $id=0;
		$projets =DB::table('projets')->where('id_user',$id_user)->get();
		foreach ($projets as $projets) {
			$id=$projets->id;
		}
        return $id;

	}
}


 ?>