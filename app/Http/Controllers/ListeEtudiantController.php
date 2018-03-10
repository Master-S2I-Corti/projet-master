<?php

namespace App\Http\Controllers;

use App\Personne;
use Illuminate\Http\Request;
use App\Etudiant;
use App\Departement;
use App\Annee;
use App\Diplome;
use Illuminate\Support\Facades\Hash;

class ListeEtudiantController extends Controller
{
    // Accès à la page Liste etudiant
    public function index()
    {
        $listesEtudiant = Personne::where('code_etudiant','!=',0)->paginate(7);
        $max= count($listesEtudiant);
        $contenu = Etudiant::where('id', '<>', [ $listesEtudiant[0]->id -1 ,$listesEtudiant[$max-1]->id ] )
                            ->get();
        $listeDepartement = Departement::get();
        $annee = Annee::get();
        $diplome = Diplome::get();

        for ($i = 1 ; $i <= count($annee) ; $i++ )
        {
            foreach($diplome as &$value)
            {
                $j = $i -1;
                if($annee[$j]->id_diplome == $value->id_diplome)
                {
                    foreach($listeDepartement as &$val)
                    {
                        if($val->id_departement == $value->id_departement)
                        {
                            $listDiplome[$j] = [
                                                    'id'=>$annee[$j]->id_annee,
                                                    'libelle'=>$annee[$j]->libelle.'  '.$value->libelle.'  '.$val->libelle
                                                ];
                        }
                    }
                }
            }
        }

        for ($i = 1 ; $i <= count($contenu) ; $i++ )
        {
            $j = $i -1;
            foreach($listDiplome as &$val)
            {
                if ( $contenu[$j]->id_annee == $val['id'] )
                {
                    $contenuEtudiant[$j] = [
                                                'id'=> $listesEtudiant[$j]->id,
                                                'nom'=> $listesEtudiant[$j]->nom,
                                                'prenom'=> $listesEtudiant[$j]->prenom,
                                                'email'=> $listesEtudiant[$j]->email,
                                                'filiere'=> $val['libelle']
                                            ];
                }
            }
        }
       
        return view('listeEtudiant', compact('listesEtudiant','listeDepartement','listDiplome','contenuEtudiant'));
    }

    //Enregistrement d'un nouveau etudiant
    public function store(Request $request){
        
        $personne = Personne::firstOrCreate([
            'login'=>$request->nom,
            'nom' => $request->nom,
            'prenom' => $request->prenom,
            'email'=>$request->nom .'@webmail.universita.corsica',
            'email_sos' => $request->emailSos,
            'naissance'=> $request->naissance,
            'password' =>  Hash::make(str_replace("-","",$request->naissance)),
            'tel' => $request->tel,
            'adresse' =>$request->adresse,
            'code_postal' =>$request->codePostal,
            'ville' =>$request->ville,
            'admin' =>0
            ]);
            
            $personne->where([
                                ['nom', '=', $request->nom],
                                ['prenom', '=', $request->prenom],
                                ['naissance', '=', $request->naissance],
                            ])->first();

            $etudiant = Etudiant::firstOrCreate(['id'=>$personne->id ,'id_annee'=>$request->diplome]);
            $etudiant = $etudiant->where('id', $personne->id)->first();
            $personne->update(['code_etudiant' =>$etudiant->code_etudiant]);
    
            return redirect()->action('ListeEtudiantController@index');
    }

    //Modification du etudiant 
    public function update(Request $request)
    {
        $personne = Personne::findOrFail($request->id);
        $personne->update(['email' =>$request->email]);
        return redirect()->action('ListeEtudiantController@index');
    }

    //Suppression du etudiant
    public function destroy(Request $request)
    {
        $personne = Personne::findOrFail($request->id);
        $test = [ 'code_etudiant' => null];
        $personne->update($test);
        $etudiant = Etudiant::findOrFail($request->id);
        $test = [ 'id' => null];
        $etudiant->update($test);
        $personne->delete();
        $etudiant->delete();
        return redirect()->action('ListeEtudiantController@index');
    }
    
    //Ajout des étudiants grâce à un fichier .csv
    public function multipleStore(Request $request){ 
        
        if(count($request->all()) != 1)
        {
            $info = $request->fichier;
            if(($handle = fopen($info->getRealPath(),"r"))!== FALSE){
                while(($data = fgetcsv($handle,1000,",")) !== FALSE){
                    //Gestion du tableau de formation A UTILISER PLUS TARD
                    /*echo "\neleve: ";
                    if ((strpos($data[6], '-'))){
                      $tabFormation = explode('-', $data[6]);
                      for($n = 0; $n < count($tabFormation); $n++){
                          echo $tabFormation[$n] . " ";
                      }
                    } else {
                        echo $data[6];
                    }*/

                    $personne = Personne::firstOrCreate([
                        'identifiant' => $data[2],
                        'nom' => $data[2],
                        'prenom' => $data[1],
                        'email_sos' => $data[3],
                        'naissance'=> $data[5],
                        'password' =>  Hash::make(str_replace("-","",$data[5])),
                        'tel' => $data[4],
                        'admin' =>0
                        ]); //'commentaire' => $data[7],

                    $personne->where([
                                ['nom', '=', $data[2]],
                                ['prenom', '=', $data[1]],
                                ['naissance', '=', $data[5]],
                            ])->first();
                    $etudiant = Etudiant::firstOrCreate(['id'=>$personne->id]);
                    $etudiant = $etudiant->where('id', $personne->id)->first();
                    $personne->where('identifiant', $personne['identifiant'])->update(['code_etudiant' =>$etudiant->code_etudiant]);
                }
            } 
        }
        return redirect()->action('ListeEtudiantController@index');
    }

}
