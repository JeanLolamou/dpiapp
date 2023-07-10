<?php

namespace App\Http\Controllers;

use App\Models\Entreprise;
use App\Models\Notifs;
use App\Models\Projet;
use App\Models\Rendezvou;
use App\Models\Secteurprefere;
use App\Models\User;
use App\Models\Wishlist;
use App\Notifications\AlerteNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Notifications\RegisteredUser;
use Illuminate\Support\Facades\Validator;

class AdminController extends Controller
{

  public function add_inscription(Request $request){

        $this->validate($request,[
           'nom' => ['required', 'string', 'max:255'],
            'prenom' => ['required', 'string', 'max:255'],
            'tel' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'genre' => ['required'],
        ]);

       
       
          $avatar='avatar.png';
         if ($request->genre=='Monsieur') {
           $avatar='avatar.png';
         }else{
          $avatar='avatar2.png';
         }

        $user=User::create([
            'categorie' => $request->categorie,
            'name' => $request->nom,
            'prenom' => $request->prenom,
            'genre' => $request->genre,
            'nationalite' => $request->nationalite,
            'pays' => $request->residence,
            'ville' => $request->ville,
            'email' => $request->email,
            'tel' => $request->tel,
            'adresse' => $request->adresse,
            'participation' => $request->participation,
            'userType' => $request->type,
            'image' => $avatar,
            'password' => Hash::make($request->password),
            'confirmation_token' => str_replace('/', '', Hash::make(Str::random(16))),

        ]);

         if ($request->type=='Promoteur') {

        $id_user=User::orderBy('id', 'desc')->first()->id;


        Projet::create(['fichier'=>$request->fichier,'fichierEn'=>$request->fichierEn,'bsplan'=>$request->bsplan, 'libelle'=>$request->projet,'ville_realisation'=>$request->villereal,'ca_prev'=>$request->cap,'cout_total'=>$request->cout, 'id_user'=>$id_user]);

        Entreprise::create(['denomination'=>$request->entreprise, 'secteur'=>$request->secteur,'poste'=>$request->poste,'description'=>$request->description, 'id_user'=>$id_user]);
      }

       if ($request->type=='Investisseur') {

        $id_user=User::orderBy('id', 'desc')->first()->id;

        Entreprise::create(['denomination'=>$request->entreprise, 'secteur'=>$request->secteur,'poste'=>$request->poste,'description'=>$request->description, 'id_user'=>$id_user]);
      }

      $user->notify(new RegisteredUser());




        return $user;

    }
   

    public function uploadFichier(Request $request){

        

        $fileName=time().'-'.$request->file->getClientOriginalName();

        $request->file->move(public_path('Fiche-Projet-GUIF2'),$fileName);

        return $fileName;       

    }

    public function addUser(Request $request){

    	$this->validate($request,[
            'nom' => ['required', 'string', 'max:255'],
            'prenom' => ['required', 'string', 'max:255'],
            'genre' => ['required', 'string', 'max:255'],
            'tel' => ['required', 'string', 'max:255'],
            'adresse' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'type' => ['required'],
        ]);

         $avatar='avatar.png';
         if ($request->genre=='Monsieur') {
           $avatar='avatar.png';
         }else{
          $avatar='avatar2.png';
         }

        if ($request->password==$request->password_confirmation) {
        	 
        	 $user=User::create(['name'=>$request->nom, 'prenom'=>$request->prenom, 'genre'=>$request->genre, 'tel'=>$request->tel, 'email'=>$request->email, 'password'=>Hash::make($request->password), 'adresse'=>$request->adresse, 'userType'=>$request->type, 'image'=>$avatar]);

        }

         return  $user;


       

    }

    public function addUtilisateur(Request $request){

     $this->validate($request,[
            'name'=>'required',
            'prenom'=>'required',
            'email'=>'required|email',
            'adresse'=>'required',
            'userType'=>'required',
        ]);

        $avatar='avatar.png';
         if ($request->genre=='Monsieur') {
           $avatar='avatar.png';
         }else{
          $avatar='avatar2.png';
         }

        $defaultPassword=Hash::make('apipadmin');


        $user=User::create(['name'=>$request->name,'prenom'=>$request->prenom,'email'=>$request->email,'adresse'=>$request->adresse,'genre'=>$request->genre, 'userType'=>$request->userType, 'password'=>$defaultPassword, 'image'=>$avatar]);

        

        return  $user;


       

    }

     public function editUtilisateur(Request $request){

       $this->validate($request,[
            'name'=>'required',
            'email'=>'required|email',
            'userType'=>'required',
        ]);

       
       
         $user1=User::where('id', $request->id)->update(['name'=>$request->name,'email'=>$request->email, 'prenom'=>$request->prenom,'tel'=>$request->tel,'genre'=>$request->genre,'adresse'=>$request->adresse]);


       

        return  $user1;

    }

    public function deleteUser(Request $request){

             $user1=User::where('id', $request->id)
             ->update(['supprimer'=>1]);
             
               return  $user1;

    }

     public function edit_password(Request $request){

       $this->validate($request,[
            'password'=>'required',
        ]);

       
       
         $user1=User::where('id', $request->id)->update(['password'=>Hash::make($request->password)]);


       

        return  $user1;

    }

    

     public function getAllUser(){
        
        return DB::table('users')
        ->where('supprimer',0)
        ->orderBy('id', 'DESC')
        ->get();

    }

     public function getAllpays(){
        
        return DB::table('pays')
        ->orderBy('nom_fr_fr')
        ->get();

    }

    public function getAllSecteur(){
        
        return DB::table('secteur_activite')
        ->orderBy('libelle')
        ->get();

    }

      public function upload(Request $request){

     	$this->validate($request,[
    		'file'=>'required|mimes:pdf,jpg,png,jpeg',
    	]);

        $fileName=time().'-'.$request->file->getClientOriginalName();

     	$request->file->move(public_path('uploads'),$fileName);

        return $fileName;    	

    }

     public function uploadPhoto(Request $request){

     	$this->validate($request,[
    		'file'=>'required|mimes:jpg,png,jpeg',
    	]);

        $fileName=time().'-'.$request->file->getClientOriginalName();

     	$request->file->move(public_path('images/avatars'),$fileName);

        return $fileName;    	

    }

    // Entreprises

    public function listEntreprises(){

    	
        $entreprise=DB::table('entreprises')
        ->where([['supprimer','=',0],['id_user','=',Auth::id()]])
        ->get();

         return  $entreprise;
       

    }

   public function addCompany(Request $request){

    	$this->validate($request,[
            'denomination' => ['required'],
            'forme_juridiq' => ['required'],
        ]);

        $entreprise=Entreprise::create(['id_user'=>Auth::id(),'denomination'=>$request->denomination, 'forme_juridiq'=>$request->forme_juridiq, 'secteur'=>$request->secteur,'autres_secteur'=>$request->autres_secteur, 'associes'=>$request->associes, 'rccm'=>$request->rccm, 'nif'=>$request->nif]);

         return  $entreprise;
       

    }


    public function editCompany(Request $request){

    	$this->validate($request,[
            'denomination' => ['required'],
            'forme_juridiq' => ['required'],
        ]);

         $entreprise1=Entreprise::where('id', $request->id)
             ->update(['denomination'=>$request->denomination, 'forme_juridiq'=>$request->forme_juridiq, 'secteur'=>$request->secteur,'autres_secteur'=>$request->autres_secteur, 'associes'=>$request->associes, 'rccm'=>$request->rccm, 'nif'=>$request->nif]);

               return  $entreprise1;     

    }

    public function deleteEntreprise(Request $request){

             $entreprise1=Entreprise::where('id', $request->id)
             ->update(['supprimer'=>1]);

               return  $entreprise1;

    }

    // Projets

    public function listProjet(){

    	
        $projets=DB::table('projets')
        ->where([['supprimer','=',0],['id_user','=',Auth::id()]])
        ->get();

         return  $projets;
       

    }

   public function addProjet(Request $request){

    	$this->validate($request,[
            'libelle' => ['required'],
            'ville_realisation' => ['required'],
            'nombre_employ_actuel' => ['required'],
            'nombre_employ_prev' => ['required'],
            'ca_prev' => ['required'],
            'cout_total' => ['required'],
            'apport' => ['required'],
            'financement_dem' => ['required'],
            'fichier' => ['required'],
            'secteurActivite' => ['required'],
        ]);

        $projet=Projet::create(['id_user'=>Auth::id(),'libelle'=>$request->libelle, 'entreprise'=>$request->entreprise, 'ville_realisation'=>$request->ville_realisation,'nombre_employ_actuel'=>$request->nombre_employ_actuel, 'nombre_employ_prev'=>$request->nombre_employ_prev, 'ca_prev'=>$request->ca_prev, 'cout_total'=>$request->cout_total, 'apport'=>$request->apport, 'financement_dem'=>$request->financement_dem, 'resume'=>$request->resume,'secteurActivite'=>$request->secteurActivite, 'fichier'=>$request->fichier]);

         return  $projet;
       

    }


    public function editProjet(Request $request){

    	$this->validate($request,[
            'libelle' => ['required'],
            'ville_realisation' => ['required'],
            'fichier' => ['required'],
            'bsplan' => ['required'],
        ]);

         $projet1=Projet::where('id', $request->id)
             ->update(['libelle'=>$request->libelle, 'entreprise'=>$request->entreprise, 'ville_realisation'=>$request->ville_realisation,'nombre_employ_actuel'=>$request->nombre_employ_actuel, 'nombre_employ_prev'=>$request->nombre_employ_prev, 'ca_prev'=>$request->ca_prev, 'cout_total'=>$request->cout_total, 'apport'=>$request->apport, 'financement_dem'=>$request->financement_dem, 'resume'=>$request->resume,'secteurActivite'=>$request->secteurActivite, 'fichier'=>$request->fichier, 'fichierEn'=>$request->fichierEn, 'bsplan'=>$request->bsplan]);

               return  $projet1;     

    }

    public function deleteProjet(Request $request){

             $projet1=Projet::where('id', $request->id)
             ->update(['supprimer'=>1]);

               return  $projet1;

    }

     public function getProjet($id){
        
        return DB::table('projets')
        ->join('users', 'users.id', '=', 'projets.id_user')
        ->select('projets.*','users.name','users.prenom')
        ->where([['projets.id','=', $id],['projets.supprimer','=', 0]])
        ->get();

    }

    public function getNomSecteur($id){
        
        return DB::table('secteur_activite')
        ->where('id','=', $id)
        ->get();

    }

    public function getInvestisseur($id){
        
        return DB::table('users')
        ->where([['id','=', $id],['supprimer','=', 0]])
        ->get();

    }

    public function getAllOfficiel(){
        
        return DB::table('users')
        ->where([['supprimer', 0],['userType','Officiel']])
        ->get();

    }

    public function getAllParticipant(){
        
        return DB::table('users')
        ->where([['supprimer', 0],['userType','Participant']])
        ->get();

    }

    public function getAllProjet(){
        
        return DB::table('projets')
        ->join('users', 'users.id', '=', 'projets.id_user')
        ->select('projets.*', 'users.name', 'users.prenom', 'users.image')
        ->where([['projets.supprimer', 0]])
        ->get();

    }


    public function getUserProjet($id){
        
        return DB::table('projets')
        ->where([['supprimer', 0],['id_user', $id]])
        ->get();

    }

    public function addWishlist(Request $request){


        $wishlist=Wishlist::create(['id_investisseur'=>Auth::id(),'id_projet'=>$request->id]);

         return  $wishlist;
       

    }

    public function ExistInWishlist(){
        
        return DB::table('wishlists')
        ->where([['id_investisseur', Auth::id()],['supprimer', 0]])
        ->get();

    }

     public function getActifUserWishlist(){

    	
        $projets=DB::table('wishlists')
        ->where([['supprimer','=',0],['id_investisseur','=',Auth::id()]])
        ->get();

         return  $projets;
       

    }

    public function getWishProjet(){

      
        $projets=DB::table('wishlists')
        ->join('projets', 'projets.id', '=', 'wishlists.id_projet') 
         ->join('users', 'users.id', '=', 'projets.id_user')
        ->select('wishlists.id as wishproject','projets.*', 'users.name', 'users.prenom','users.image')
        ->where([['wishlists.supprimer','=',0],['projets.supprimer','=',0],['wishlists.id_investisseur','=',Auth::id()]])
        ->get();

         return  $projets;
       

    }

    public function getInvestisseurAllwish($id){

      
        $projets=DB::table('wishlists')
        ->join('projets', 'projets.id', '=', 'wishlists.id_projet') 
         ->join('users', 'users.id', '=', 'projets.id_user')
        ->select('projets.*', 'users.name', 'users.prenom','users.image')
        ->where([['wishlists.supprimer','=',0],['wishlists.id_investisseur','=',$id],['projets.supprimer','=',0]])
        ->get();

         return  $projets;
       

    }

    public function notifCreationProjet(){

      $projets=DB::table('projets')
        ->where([['created_at','>',Auth::user()->last_log],['supprimer','=',0]])
        ->get();

         return  $projets;
       

    }

    public function notifWishList(){

      $wishlists=DB::table('wishlists')
        ->where([['created_at','>',Auth::user()->last_log],['supprimer','=',0]])
        ->get();

         return  $wishlists;
       

    }

     public function notifRendezvous(){

      $rendezvous=DB::table('rendezvous')
        ->where([['updated_at','>',Auth::user()->last_log],['supprimer','=',0]])
        ->get();

         return  $rendezvous;
       

    }

    public function editLastLog(Request $request){
        
             $user=User::where('id', $request->id)
             ->update(['last_log'=>now()]);

               return  $user;

    }

    public function getAllWishProjet(){

      
        $projets=DB::table('wishlists')
        ->join('projets', 'projets.id', '=', 'wishlists.id_projet') 
        ->join('users', 'users.id', '=', 'projets.id_user')
        ->join('users as Investisseur', 'Investisseur.id', '=', 'wishlists.id_investisseur')
        ->select('projets.*', 'users.name', 'users.prenom','users.image', 'Investisseur.name as nomI', 'Investisseur.prenom as prenomI', 'wishlists.id_investisseur','Investisseur.image as imageI')
        ->where([['wishlists.supprimer','=',0],['projets.supprimer','=',0]])
        ->get();

         return  $projets;
       

    }

    public function getAllWishInvestisseur($id){

      
        $projets=DB::table('wishlists')
        ->join('users', 'users.id', '=', 'wishlists.id_investisseur')
        ->select('wishlists.*', 'users.*')
        ->where('wishlists.id_projet','=',$id)
        ->get();

         return  $projets;
       

    }


     public function removeWishlist(Request $request){

             $projet1=Wishlist::find($request->wishproject);

               return  $projet1->delete();

    }


     public function getProjetPromoteur($id){

     	$entreprise=DB::table('entreprises')
        ->where([['id','=', $id],['supprimer','=', 0]])
        ->get();

        $id_user=0;

        foreach ($entreprise as $entreprise) {
        	$id_user=$entreprise->id_user;
        }

         return DB::table('users')
        ->where('id', $id_user)
        ->get();

    }

     public function getPromoteur($id){
        
        return DB::table('users')
        ->where('id', $id)
        ->get();

    }

     public function getEntreprise($id){
        
        return DB::table('entreprises')
        ->where([['id', $id],['supprimer', 0]])
        ->get();

    }

    public function Allentreprises(){
        
        return DB::table('entreprises')
        ->where('supprimer', 0)
        ->get();

    }

     public function getAllInvestisseur(){
        
        return DB::table('users')
        ->where([['supprimer', 0],['userType','Investisseur']])
        ->get();

    }

    public function getAllInvestisseurWithEtrep(){
        
        return DB::table('users')
        ->join('entreprises', 'entreprises.id_user', '=', 'users.id')
        ->select('users.*', 'entreprises.denomination')
        ->where([['users.supprimer', 0],['users.userType','Investisseur']])
        ->get();

    }

    public function deleteInvestisseur(Request $request){
        
        $investisseur1=User::where('id', $request->id)
             ->update(['supprimer'=>1]);

               return  $investisseur1;

    }

    public function getAllPromoteur(){
        
        return DB::table('users')
        ->where([['supprimer', 0],['userType','Promoteur']])
        ->get();

    }

    public function getAllPromoteurWithEtrep(){
        
        return DB::table('users')
        ->join('entreprises', 'entreprises.id_user', '=', 'users.id')
        ->select('users.*', 'entreprises.denomination')
        ->where([['users.supprimer', 0],['users.userType','Promoteur']])
        ->get();

    }

    public function deletePromoteur(Request $request){
        
        $promoteur1=User::where('id', $request->id)
             ->update(['supprimer'=>1]);

               return  $promoteur1;

    }

    public function getActifUser(){
        
        return Auth::user();

    }

    public function editActifUser(Request $request){

       $this->validate($request,[
            'name'=>'required',
            'prenom'=>'required',
            'email'=>'required|email',
            'adresse'=>'required',
            'tel'=>'required',
        ]);

       $user1=User::where('id', $request->id)->update(['name'=>$request->name,'adresse'=>$request->adresse,'prenom'=>$request->prenom,'tel'=>$request->tel, 'genre'=>$request->genre]);


       

        return  $user1;

    }

    public function editPasswordUser(Request $request){

       $this->validate($request,[
            'password'=>'required',
            'confirm'=>'required',
        ]);

       
       
         if ($request->password==$request->confirm) {

             $user1=User::where('id', $request->id)->update(['password'=>Hash::make($request->password)]);
         }


       

        return  $user1;

    }

    public function getProjetActifUser(){
        
        return DB::table('projets')
        ->where([['id_user', Auth::id()],['supprimer', 0]])
        ->get();

    }

     public function getPreference(){
        
        return DB::table('secteurpreferes')
        ->where('id_user', Auth::id())
        ->get();

    }

    public function getSecteur(){
        
        return DB::table('secteur_activite')
        ->get();

    }

    public function editSecteurPrefere(Request $request){

          $prefere1=Secteurprefere::where('id_user', Auth::id())->delete();
        

          
          if ($request->agriculture) {
             $prefere1=Secteurprefere::create(['id_user'=>Auth::id(),'id_secteur'=>1]);
              }
          if ($request->elevage) {
            $prefere1=Secteurprefere::create(['id_user'=>Auth::id(),'id_secteur'=>2]);
          }
           if ($request->peche) {
            $prefere1=Secteurprefere::create(['id_user'=>Auth::id(),'id_secteur'=>3]);
          }
          if ($request->tic) {
            $prefere1=Secteurprefere::create(['id_user'=>Auth::id(),'id_secteur'=>4]);
          }
          if ($request->energies) {
            $prefere1=Secteurprefere::create(['id_user'=>Auth::id(),'id_secteur'=>5]);
          }
          if ($request->mines) {
            $prefere1=Secteurprefere::create(['id_user'=>Auth::id(),'id_secteur'=>6]);
          }
           if ($request->btp) {
            $prefere1=Secteurprefere::create(['id_user'=>Auth::id(),'id_secteur'=>7]);
          }
          if ($request->sante) {
            $prefere1=Secteurprefere::create(['id_user'=>Auth::id(),'id_secteur'=>8]);
          }
           if ($request->hydraulique) {
            $prefere1=Secteurprefere::create(['id_user'=>Auth::id(),'id_secteur'=>9]);
          }
          if ($request->logistique) {
            $prefere1=Secteurprefere::create(['id_user'=>Auth::id(),'id_secteur'=>10]);
          }
           if ($request->industrie) {
            $prefere1=Secteurprefere::create(['id_user'=>Auth::id(),'id_secteur'=>11]);
          }
          if ($request->transport) {
            $prefere1=Secteurprefere::create(['id_user'=>Auth::id(),'id_secteur'=>12]);
          }
          if ($request->commerce) {
            $prefere1=Secteurprefere::create(['id_user'=>Auth::id(),'id_secteur'=>13]);
          }
          if ($request->education) {
            $prefere1=Secteurprefere::create(['id_user'=>Auth::id(),'id_secteur'=>14]);
          }
          if ($request->services) {
            $prefere1=Secteurprefere::create(['id_user'=>Auth::id(),'id_secteur'=>15]);
          }
           
            return  $prefere1;

    }

     public function updateUser(Request $request){

             $user1=User::where('id', $request->id)
             ->update(['image'=>$request->image]);

               return  $user1;

    }

    public function getAgenda(){
        
        return DB::table('rendezvous')
        ->join('users', 'users.id', '=', 'rendezvous.id_investisseur')
        ->join('projets', 'projets.id', '=', 'rendezvous.id_projet') 
        ->select('rendezvous.*', 'users.name', 'users.prenom', 'projets.libelle')
        ->where([['id_promoteur', Auth::id()],['rendezvous.supprimer','=',0]])
        ->orderBy('id','DESC')
        ->get();

    }

    public function AgendaInvestisseur(){
        
        return DB::table('rendezvous')
        ->join('users', 'users.id', '=', 'rendezvous.id_promoteur')
        ->join('projets', 'projets.id', '=', 'rendezvous.id_projet') 
        ->select('rendezvous.*', 'users.name', 'users.prenom','users.image', 'projets.libelle')
        ->where([['id_investisseur', Auth::id()],['rendezvous.supprimer','=',0]])
        ->orderBy('id','DESC')
        ->get();

    }

    public function getAllAgenda(){
        
        return DB::table('rendezvous')
        ->join('users', 'users.id', '=', 'rendezvous.id_promoteur')
        ->join('users as Investisseur', 'Investisseur.id', '=', 'rendezvous.id_investisseur')
        ->join('projets', 'projets.id', '=', 'rendezvous.id_projet') 
        ->select('rendezvous.*', 'users.name', 'users.prenom', 'projets.libelle','Investisseur.name as nomI','Investisseur.prenom as prenomI')
        ->where('rendezvous.supprimer','=',0)
        ->orderBy('id','DESC')
        ->get();

    }

     public function getAllRdvProjet($id){

      
        return DB::table('rendezvous')
        ->join('users', 'users.id', '=', 'rendezvous.id_promoteur')
        ->join('users as Investisseur', 'Investisseur.id', '=', 'rendezvous.id_investisseur')
        ->join('projets', 'projets.id', '=', 'rendezvous.id_projet') 
        ->select('rendezvous.*', 'users.name', 'users.prenom', 'projets.libelle','Investisseur.name as nomI','Investisseur.prenom as prenomI')
        ->where([['rendezvous.supprimer','=',0],['rendezvous.id_projet','=',$id]])
        ->orderBy('id','DESC')
        ->get();
       

    }

    public function getAllRdvInvestisseur($id){

      
        return DB::table('rendezvous')
        ->join('users', 'users.id', '=', 'rendezvous.id_promoteur')
        ->join('users as Investisseur', 'Investisseur.id', '=', 'rendezvous.id_investisseur')
        ->join('projets', 'projets.id', '=', 'rendezvous.id_projet') 
        ->select('rendezvous.*', 'users.name', 'users.prenom', 'projets.libelle','Investisseur.name as nomI','Investisseur.prenom as prenomI')
        ->where([['rendezvous.supprimer','=',0],['rendezvous.id_investisseur','=',$id]])
        ->orderBy('id','DESC')
        ->get();
       

    }

     public function getAllRdvPromoteur($id){

      
        return DB::table('rendezvous')
        ->join('users', 'users.id', '=', 'rendezvous.id_promoteur')
        ->join('users as Investisseur', 'Investisseur.id', '=', 'rendezvous.id_investisseur')
        ->join('projets', 'projets.id', '=', 'rendezvous.id_projet') 
        ->select('rendezvous.*', 'users.name', 'users.prenom', 'projets.libelle','Investisseur.name as nomI','Investisseur.prenom as prenomI')
        ->where([['rendezvous.supprimer','=',0],['rendezvous.id_promoteur','=',$id]])
        ->orderBy('id','DESC')
        ->get();
       

    }

    public function addRendevous(Request $request){

      $this->validate($request,[
            'id_projet' => ['required'],
            'id_promoteur' => ['required'],
            'id_investisseur' => ['required'],
            'date' => ['required'],
            'heure' => ['required'],
        ]);

        $rendezvous=Rendezvou::create(['id_projet'=>$request->id_projet,'id_promoteur'=>$request->id_promoteur, 'id_investisseur'=>$request->id_investisseur, 'typerencontre'=>$request->typerencontre,'date'=>$request->date, 'heure'=>$request->heure, 'lieu'=>$request->lieu, 'lien'=>$request->lien, 'details'=>$request->details]);

        // notification
         $notification=Notifs::create(['type'=>'addRendevous','id_promoteur'=>$request->id_promoteur, 'id_investisseur'=>$request->id_investisseur]);

        //envoi mail de notification
         $promoteur=User::where('id', $request->id_promoteur)->first();
         $promoteur->notify(New AlerteNotification($rendezvous));



         $investisseur=User::where('id', $request->id_investisseur)->first();
         $investisseur->notify(New AlerteNotification($rendezvous));

         return  $rendezvous;
       

    }

    public function editRendezvous(Request $request){

             $agenda1=Rendezvou::where('id', $request->id)
             ->update(['id_projet'=>$request->id_projet,'id_promoteur'=>$request->id_promoteur, 'id_investisseur'=>$request->id_investisseur, 'typerencontre'=>$request->typerencontre,'date'=>$request->date, 'heure'=>$request->heure, 'lieu'=>$request->lieu, 'lien'=>$request->lien, 'details'=>$request->details]);

              // notification
         $notification=Notifs::create(['type'=>'editRendezvous','id_promoteur'=>$request->id_promoteur, 'id_investisseur'=>$request->id_investisseur]);

          //envoi mail de notification
         $promoteur=User::where('id', $request->id_promoteur)->first();
         $promoteur->notify(New AlerteNotification());
         
         $investisseur=User::where('id', $request->id_investisseur)->first();
         $investisseur->notify(New AlerteNotification());

               return  $agenda1;

    }

    public function delete_rendezvous(Request $request){

             $agenda1=Rendezvou::where('id', $request->id)
             ->update(['supprimer'=>1]);

               return  $agenda1;

    }


    public function ApprouverRdv(Request $request){
        
         $agenda1=Rendezvou::where('id', $request->id)
             ->update(['statut'=>1]);

          // notification
         $notification=Notifs::create(['type'=>'ApprouverRdv','id_promoteur'=>$request->id_promoteur, 'id_investisseur'=>$request->id_investisseur]);

         //envoi mail de notification
         $promoteur=User::where('id', $request->id_promoteur)->first();
         $promoteur->notify(New AlerteNotification());
         
         $investisseur=User::where('id', $request->id_investisseur)->first();
         $investisseur->notify(New AlerteNotification());


               return  $agenda1;

    }

    public function AnnulerRdv(Request $request){
        
         $agenda1=Rendezvou::where('id', $request->id)
             ->update(['statut'=>2,'q_annule'=>Auth::id(),'commentaire'=>$request->commentaire]);

        // notification
         $notification=Notifs::create(['type'=>'AnnulerRdv','id_promoteur'=>$request->id_promoteur, 'id_investisseur'=>$request->id_investisseur]);

         //envoi mail de notification
         $promoteur=User::where('id', $request->id_promoteur)->first();
         $promoteur->notify(New AlerteNotification());
         
         $investisseur=User::where('id', $request->id_investisseur)->first();
         $investisseur->notify(New AlerteNotification());

               return  $agenda1;

    }

    public function getNotification(){

      
        if (Auth::user()->userType=='Investisseur') {
          return DB::table('notifs')
        ->where([['statut','=',0],['id_investisseur','=',Auth::id()]])
        ->orderBy('id','DESC')
        ->get();
        }elseif(Auth::user()->userType=='Promoteur'){
          return DB::table('notifs')
        ->where([['statut','=',0],['id_promoteur','=',Auth::id()]])
        ->orderBy('id','DESC')
        ->get();
        }
       

    }

     public function updateNotification(Request $request){
        
        if (Auth::user()->userType=='Investisseur') {
           $notif1=Notifs::where([['type','=','addRendevous'],['id_investisseur','=',Auth::id()]])
    ->orWhere([['type','=','ApprouverRdv'],['id_investisseur','=',Auth::id()]])
    ->orWhere([['type','=','AnnulerRdv'],['id_investisseur','=',Auth::id()]])
    ->orWhere([['type','=','editRendezvous'],['id_investisseur','=',Auth::id()]])
    ->update(['statut'=>1]);
        }elseif (Auth::user()->userType=='Promoteur') {

          $notif1=Notifs::where([['type','=','addRendevous'],['id_promoteur','=',Auth::id()]])
    ->orWhere([['type','=','AnnulerRdv'],['id_promoteur','=',Auth::id()]])
    ->orWhere([['type','=','ApprouverRdv'],['id_promoteur','=',Auth::id()]])
    ->orWhere([['type','=','editRendezvous'],['id_promoteur','=',Auth::id()]])
    ->update(['statut'=>1]);
        }

               return  $notif1;

    }

}
