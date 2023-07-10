<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use Session;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $lang=langue();
        return view('home',compact('lang'));
    }

   public function changelangueFr()
    {
        Session::put('langue', 'Fr');
        return back();

    }

     public function changelangueEn()
    {
        Session::put('langue', 'En');
        return back();

    }

    public function getLangue()
    {
        $langue=Session::get('langue');
        if ($langue=="") {
            $langue="Fr";
        }
        return $langue;

    }

    public function projetPublique()
    {
        $lang=langue();
         return view('projetPublique',compact('lang'));
    }

     public function etatInscrit(Request $request)
    {
        $total=DB::table('users')
        ->where([['userType','!=','Super-Administrateur'],['supprimer','=',0]])
        ->get();
        $promoteur=DB::table('users')
        ->where([['userType','Promoteur'],['supprimer','=',0]])
        ->get();
        $investisseur=DB::table('users')
        ->where([['userType','Investisseur'],['supprimer','=',0]])
        ->get();
        $officiel=DB::table('users')
        ->where([['userType','Officiel'],['supprimer','=',0]])
        ->get();
        $participant=DB::table('users')
        ->where([['userType','Participant'],['supprimer','=',0]])
        ->get();

        $pay=DB::table('pays')
        ->orderBy('nom_fr_fr')
        ->get();



        $residence="";$nationalite="";$type="";$participation="";$date_debut="";
        $date_fin="";

        $requete="select * from users where supprimer=0 and userType!='Super-Administrateur'";

        if (isset($request->residence) and ($request->residence!='Tous')) {
            $requete.=" and pays='".$request->residence."'";
            $residence=$request->residence;
        }

        if (isset($request->nationalite) and ($request->nationalite!='Tous')) {
            $requete.=" and nationalite='".$request->nationalite."'";
            $nationalite=$request->nationalite;
        }

        if (isset($request->type) and ($request->type!='Tous')) {
            $requete.=" and userType='".$request->type."'";
            $type=$request->type;
        }

         if (isset($request->participation) and ($request->participation!='Tous')) {
            $requete.=" and participation='".$request->participation."'";
            $participation=$request->participation;
        }

        if (isset($request->date_debut)) {
           $debut=date($request->date_debut);
           $requete.=" and created_at>='$debut'";
           $date_debut=$request->date_debut;
        }

        if (isset($request->date_fin)) {
           $fin=date($request->date_fin);
           $requete.=" and created_at<='$fin'";
           $date_fin=$request->date_fin;
        }


         $users=DB::SELECT($requete);

         


        return view('etatInscrit', compact('users','pay','nationalite','residence','type','participation','promoteur','investisseur','officiel','participant','total','date_debut','date_fin'));
    }


    public function deleteParticipant($id)
    {
        

         $userDelete=User::where('id', $id)->delete();

    session()->flash('message','Participant supprimé avec succès');

    
        $pay=DB::table('pays')
        ->orderBy('nom_fr_fr')
        ->get();

        $residence="";$nationalite="";$type="";$participation="";

        $requete="select * from users where supprimer=0 and userType!='Super-Administrateur'";


         $users=DB::SELECT($requete);

         session()->flash('message','Participant supprimé avec succès');

         


        return view('etatInscrit', compact('users','pay','nationalite','residence','type','participation'));
    }


     public function listeUser($type)
    {
        $users=DB::table('users')
        ->where('userType','=',$type)
        ->get();
        $userType=$type;

        return view('listeUser', compact('users','userType'));
    }
}
