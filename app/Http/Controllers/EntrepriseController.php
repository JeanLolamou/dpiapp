<?php

namespace App\Http\Controllers;

use App\Models\Entreprise;
use Illuminate\Http\Request;

class EntrepriseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request,[
            'denomination'=>'required',
            'secteur'=>'required',
            'ca'=>'required',
            'anneexperience'=>'required',
            'pays'=>'required',
            'typeinvest'=>'required',
            'nom'=>'required',
            'prenom'=>'required',
            'fonction'=>'required',
            'personcontact'=>'required',
            'foncpersoncontact'=>'required',
            'tel'=>'required',
            'email'=>'required',
        ]);

         $entreprise=Entreprise::create(['nom'=>$request->nom, 'prenom'=>$request->prenom, 'denomination'=>$request->denomination, 'tel'=>$request->tel, 'email'=>$request->email, 'secteur'=>$request->secteur, 'ca'=>$request->ca, 'anneexperience'=>$request->anneexperience, 'pays'=>$request->pays, 'typeinvest'=>$request->typeinvest, 'fonction'=>$request->fonction, 'personcontact'=>$request->personcontact, 'foncpersoncontact'=>$request->foncpersoncontact]);

     session()->flash('message','Votre enregistrement a été éffectué succés');
     return redirect()->route('accueil');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
