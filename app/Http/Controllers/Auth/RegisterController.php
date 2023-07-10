<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Entreprise;
use App\Models\Projet;
use App\Models\User;
use App\Notifications\RegisteredUser;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }


    public function register(Request $request)
    {
        $this->validator($request->all())->validate();

        

        event(new Registered($user = $this->create($request->all())));

         $user->notify(new RegisteredUser());

       
        return $user;
    }


    public function confirm($id,$token)
    {
        $users = User::where('id',$id)->where('confirmation_token',$token)->first();
        $util=DB::table('users')
        ->where('id',$id)
        ->get();
        
        if ($users) {
            
            $users->update(['confirmation_token'=>null]);
             // Auth::login($users, true);
             session()->flash('success','Votre compte a bien été activé.');
           
             return view('mailConfirmer',compact('util'));
               
        }else{
           session()->flash('error','Ce lien ne semble plus valide');
       return view('mailDejaConfirmer');
        }
    }



    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'nom' => ['required', 'string', 'max:255'],
            'prenom' => ['required', 'string', 'max:255'],
            'tel' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'genre' => ['required'],
            
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
        $avatar='avatar.png';
         if ($data['genre']=='Monsieur') {
           $avatar='avatar.png';
         }else{
          $avatar='avatar2.png';
         }

        $user=User::create([
            'categorie' => $data['categorie'],
            'name' => $data['nom'],
            'prenom' => $data['prenom'],
            'genre' => $data['genre'],
            'nationalite' => $data['nationalite'],
            'pays' => $data['residence'],
            'ville' => $data['ville'],
            'email' => $data['email'],
            'tel' => $data['tel'],
            'adresse' => $data['adresse'],
            'participation' => $data['participation'],
            'userType' => $data['type'],
            'image' => $avatar,
            'password' => Hash::make($data['password']),
            'confirmation_token' => str_replace('/', '', Hash::make(Str::random(16))),

        ]);

         if ($data['type']=='Promoteur') {

        $id_user=User::orderBy('id', 'desc')->first()->id;


        Projet::create(['fichier'=>$data['fichier'],'fichierEn'=>$data['fichierEn'],'bsplan'=>$data['bsplan'], 'libelle'=>$data['projet'],'ville_realisation'=>$data['villereal'],'ca_prev'=>$data['cap'],'cout_total'=>$data['cout'], 'id_user'=>$id_user]);

        Entreprise::create(['denomination'=>$data['entreprise'], 'secteur'=>$data['secteur'],'poste'=>$data['poste'],'description'=>$data['description'], 'id_user'=>$id_user]);
      }

       if ($data['type']=='Investisseur') {

        $id_user=User::orderBy('id', 'desc')->first()->id;

        Entreprise::create(['denomination'=>$data['entreprise'], 'secteur'=>$data['secteur'],'poste'=>$data['poste'],'description'=>$data['description'], 'id_user'=>$id_user]);
      }




        return $user;
    }
}
