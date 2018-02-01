<?php

namespace App\Http\Controllers;

use App\Etudiant;
use App\Personne;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ListeEtudiantController extends Controller
{
    // Accès à la page Liste etudiant
    public function index()
    {
        $recherche = null;
        $listesEtudiant = null;
        $personnes = Personne::get();
        if( isset($personnes) )
        {
            $conteur = 0;
            foreach ( $personnes as $personne): 
                if ($personne->code_etudiant !=null)
                {
                    $listesEtudiant[$conteur] = $personne;
                    $conteur++;
                }

            endforeach;
        }
        return view('listeEtudiant', compact('listesEtudiant','recherche'));
    }

    //Enregistrement d'un nouveau etudiant
    public function store(Request $request){
       $personne = Personne::firstOrCreate([
        'identifiant' => $request->nom,
        'nom' => $request->nom,
        'prenom' => $request->prenom,
        'mail' => $request->email,
        'password' =>  Hash::make(str_replace("-","",$request->dateNaissance))
        ]);
    
        $personne->where('identifiant', $personne['identifiant'])->first();
        $etudiant = Etudiant::firstOrCreate(['id'=>$personne->id]);
        $etudiant = $etudiant->where('id', $personne->id)->first();
        $personne->where('identifiant', $personne['identifiant'])->update(['code_etudiant' =>$etudiant->code_etudiant]);

        return redirect()->action('ListeEtudiantController@index');
    }

    //Accès à la page de modification d'un etudiant
    public function edit($id) 
    {
        $etudiants = Etudiant::findOrFail($id);
        return view('test/editEtudiant', compact('etudiants'));
    }

    //Modification du etudiant 
    public function update( Request $request)
    {
        $etudiants = Etudiant::findOrFail($request->id);
        $etudiants->update($request->all());
        $user = 'admin';
        return redirect()->action('ListeEtudiantController@index', compact('user'));
    }

    //Suppression du etudiant
    public function destroy($id)
    {
        $etudiants = Etudiant::findOrFail($id);
        $etudiants->delete();
        $user = 'admin';
        return redirect()->action('ListeEtudiantController@index', compact('user'));
    }
    

    
}
