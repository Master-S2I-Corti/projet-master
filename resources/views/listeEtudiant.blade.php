@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/annuaire.css') }}"/>
    <style>
        .nav-item:nth-child(3) a {
            color: white !important;
        }
    </style>
@endsection

@section('content')
    <div class="w-75 m-auto pt-5">
        <div class="d-flex justify-content-between mb-5">
                <h2>Liste des étudiants</h2>
                @if(Auth::user()->isAdmin())
                    <button class="add btn btn-primary">Ajout d'un étudiant <i class="ml-2 d-inline fa fa-plus fa-lg"></i></button>
                    <form method="post" action="{!! url('annuaire/etudiants/saveEtudiants') !!}" enctype="multipart/form-data">
                        {{ csrf_field() }}
                        <input type="file" name="fichier" accept=".csv"/>
                        <button class="btn btn-primary">Ajout du fichier <i class="ml-2 d-inline fa fa-plus fa-lg"></i></button>
                    </form>
                @endif
            </div>

            <div class="card">
                <h3>Recherche de l'étudiant</h3>
                <form method="post" action="{!! url('annuaire/etudiants/search') !!}" accept-charset="UTF-8">
                    <div class="modal-body">
                            <div class="row">
                            {{ csrf_field() }}
                                <div class="col-md">
                                    <p> Nom : <input class="form-control form-control-sm" type="text" name="nom" value='' /><br/></p>
                                    </div>
                                <div class="col-md">
                                    <p> Prénom : <input class="form-control form-control-sm" type="text" name="prenom" value='' /><br/></p>
                                </div>
                                <div class="col-md">
                                <label>Filière : </label>
                                <input type="checkbox" id="DUT" name="Choix6" value="DUT">
                                    <label for="DUT">DUT</label>
                                    <input type="checkbox" id="l1" name="Choix1" value="LICENCE 1">
                                    <label for="LICENCE 1">L1</label>
                                    <input type="checkbox" id="l2" name="Choix2" value="LICENCE 2">
                                    <label for="LICENCE 2">L2</label>
                                    <input type="checkbox" id="l3" name="Choix3" value="LICENCE 3">
                                    <label for="LICENCE 3">L3</label></br>
                                    <input type="checkbox" id="M1" name="Choix4" value="MASTER 1">
                                    <label for="MASTER 1">M1</label>
                                    <input type="checkbox" id="M2" name="Choix5" value="MASTER 2">
                                    <label for="MASTER 2">M2</label>
                                    
                                    <input type="checkbox" id="Doc" name="Choix7" value="DOCTORAT">
                                    <label for="Doc">DOCTORAT</label>
                                </div>
                                <div class="col-md">
                                <p>Département :
                                        <select class="form-control form-control-sm" name="departement">
                                        @if ( isset($listeDepartement))
                                            @foreach ( $listeDepartement as $departement)
                                                    <option value="{{$departement->id_departement}}">{{$departement->libelle}}</option>
                                            @endforeach
                                        @endif
                                        </select> 
                                </p>
                                </div>
                                <div class="col-md">
                                </br>
                                    <button class="btn btn-primary">Rechercher </button>
                                </div>
                            </div>
                    </div>
                </form>
            </div>
        
        
        <table class="table table-bordered ">
            <thead>
            <tr>
                <th>#</th>
                <th>Nom</th>
                <th>Prenom</th>
                <th>Filière</th>
                @if(Auth::user()->isAdmin())
                    <th>Modifier</th>
                    <th>Supprimer</th>
                @endif
            </tr>
            </thead>
            <tbody>
           @if ( isset($listesEtudiant))
                @foreach ( $listesEtudiant as $etudiant)
                    <tr>
                            <th>{{$etudiant->id}}</th>
                            <th  class="opener">{{$etudiant->identity->nom}}</th>
                            <th  class="opener">{{$etudiant->identity->prenom}}</th>
                            <th  class="opener">{{$etudiant->annee[0]->libelle."  ".$etudiant->annee[0]->diplome->libelle}}</th>
                            @if(Auth::user()->isAdmin())
                            <th class="modifier" ><i class="fa fa-edit fa-2x"></i></th>
                            <th class="del"><i class="fa fa-trash fa-2x"></i></th>
                            @endif
                    </tr>
                
                @endforeach
           @endif
            </tbody>
        </table>
        {{ $listesEtudiant->render() }} <!-- Nombres de page et redirection de la pagination -->
    </div>

    <!-- POPUP D'AFFICHAGE -->
    <div id="dialog" title="Profil de l' Etudiant" class="modal fade" >
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="nom">Détails étudiant</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col">
                            <p id="email"> Email: Aucun email</p>
                        </div>
                        <div class="col">
                            <p id="fil"> Filière: Aucune filiere</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- POPUP DE MODIFICATON -->
    <div id="modif" title="Modification de l' Etudiant" class="modal fade" >
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form method="post" action="{!! url('updateEtudiant') !!}" accept-charset="UTF-8">

                    <div class="modal-header">
                        <h5 class="modal-title" ><span id="nom2" name="nom"> P </span> <span id="pre2" name="prenom">P</span></h5>

                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                            {{ csrf_field() }}
                            <div class="row">
<<<<<<< HEAD
                                <div class="col">
                                    <input id="id2" type="hidden" name="id"  value="" />
                                    <p> Email: <br /></p>
=======
                                <div class="col-md-4">
                                    <input id="id2" type="hidden" name="id" value="" />
                                    <p> Email: <input class="form-control form-control-md" type="email"  name="email" id="email2" value='' required/><br /></p>
>>>>>>> 7293e14236425f69e08d7fefb1270061ca747ab7
                                </div>
                                <div class="col">
                                    <p> Filière: <input type="text" name="filiere" id="fil2" value=''/><br /></p>
                                </div>
<<<<<<< HEAD
                                <div class="row">
                                    <div class="col">
                                    </div>
=======
                                <div class="col-md-4">
                                    <!--<p> Filière: <input class="form-control form-control-md" type="text" name="filiere" id="fil2" value='' /><br /></p>-->
                                    <p>Filière :
                                        <select class="form-control form-control-sm" name="filiere" id="fil2">
                                        @if ( isset($listDiplome))
                                            @foreach ( $listDiplome as $diplome)
                                                    <option value="{{$diplome['id']}}">{{$diplome['libelle']}}</option>
                                            @endforeach
                                        @endif
                                        </select> 
                                </p> 
>>>>>>> 7293e14236425f69e08d7fefb1270061ca747ab7
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-primary"> Modifier</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- POPUP D'AJOUT -->
    <div id="ajout" title="Ajouter de l' Etudiant" class="modal fade" >
        <form class="modal-dialog modal-lg" method="post" action="{!! url('annuaire/etudiants/saveEtudiant') !!}" accept-charset="UTF-8">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title">Ajouter nouvel étudiant</h5>

                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="container">
                        <div class="row">
                            <div class="col-md-4">
                                {{ csrf_field() }}
                                <p> Nom: <input class="form-control form-control-sm" type="text" name="nom" value='' required/><br/></p>
                                <p> Date de Naissance :  <input class="form-control form-control-sm" type="date" name="naissance" value='' required/><br></p>
                            </div>
                            <div class="col-md-2">

                            </div>
                            <div class="col-md-4">
                                <p> Prénom: <input class="form-control form-control-sm" type="text" name="prenom" value='' required/><br/><br/><br></p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <p> Adresse : <input class="form-control form-control-sm" type="text" name="adresse" value='' required/><br/></p>
                                <p> Code Postal :  <input class="form-control form-control-sm" type="text" name="codePostal" value='' required/><br/></p>
                                <p> Ville :  <input class="form-control form-control-sm" type="text" name="ville" value='' required/><br/></p>
                            </div>
                            <div class="col-md-2">

                            </div>
                            <div class="col-md-4">
                                
                                <p> Numéro de télephone : <input class="form-control form-control-sm" type="text" name="tel" value='' required/><br/></p>
                                <p> Email de Secours: <input class="form-control form-control-sm" type="email"  name="emailSos" value='' required/><br/></p>
                            </div>
                        </div>
                        <div class="rows">
                            <div class="col-md-4">
                                <p>Département :
                                        <select class="form-control form-control-sm" name="departement">
                                        @if ( isset($listeDepartement))
                                            @foreach ( $listeDepartement as $departement)
                                                    <option value="{{$departement->id_departement}}">{{$departement->libelle}}</option>
                                            @endforeach
                                        @endif
                                        </select> 
                                </p> 
                            </div>
                            <div class="col-md-4">
                                <p>Filière :
                                        <select class="form-control form-control-sm" name="diplome">
                                        @if ( isset($listDiplome))
                                            @foreach ( $listDiplome as $diplome)
                                                    <option value="{{$diplome['id']}}">{{$diplome['libelle']}}</option>
                                            @endforeach
                                        @endif
                                        </select> 
                                </p> 
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary">Ajouter</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                </div>
            </div>
        </form>
    </div>

    <!-- POPUP DE SUPRESSION -->
        <div id="sup" title="Profil de l'etudiant" class="modal fade">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="nom">Suppression de l'Etudiant</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                        <div class="container">
                            <h4> Êtes-vous sûr de supprimer <span id="nomS" name="nom"> cet étudiant </span> ?</h4>
                            <div class="row">
                                <div class="col">
                                    <form method="post" action="{{ url('annuaire/etudiants/deleteEtudiant') }}">
                                        {{ csrf_field() }}
                                        <input id="idS" type="hidden" name="id" value=""/>
                                        <button class="btn btn-danger">Confirmer</button>
                                    </form>
                                </div>
                                <div class="col">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                                </div>
                            </div>
                        </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')

    <script>
        $( function() {
            //AFFICHAGE POPUP
            $( ".opener" ).on( "click", function(e) {
                var elements = e.target.parentElement.querySelectorAll("th")
                var id_personne = elements.item(0).innerHTML
                var num = 0
                var personnes = JSON.parse('<?= json_encode($listesEtudiant->all());  ?>')
                for (var i = 0; i < personnes.length; i++) {
                    if ( id_personne == personnes[i]['id'])
                    {
                        num = i;
                    }
                }
                document.querySelector("#nom").innerHTML = elements.item(1).innerHTML +" "+elements.item(2).innerHTML
<<<<<<< HEAD
                document.querySelector("#email").innerHTML ="Email : "+ personnes[num]['email']
                
                
=======
                document.querySelector("#email").innerHTML = "Email : "+ personnes[num].identity.email
                document.querySelector("#fil").innerHTML = " Filière : "+ elements.item(3).innerHTML
>>>>>>> 7293e14236425f69e08d7fefb1270061ca747ab7
                $( "#dialog" ).modal('show');
            });

            $( ".modifier" ).on( "click", function(e) {
                var elements = e.target.parentElement.parentElement.querySelectorAll("th")
<<<<<<< HEAD
                document.getElementById("id2").value = elements.item(0).innerHTML
                document.querySelector("#nom2").innerHTML = elements.item(1).innerHTML
                document.querySelector("#pre2").innerHTML = elements.item(2).innerHTML
                document.getElementById("fil2").value = elements.item(3).innerHTML;
=======
                var id_personne = elements.item(0).innerHTML;
                var name_diplome = elements.item(3).innerHTML;
                var idIdentity = 0, idDiplome = 0;
                var personnes = JSON.parse('<?= json_encode($listesEtudiant->all());  ?>')
                var diplome = JSON.parse('<?= json_encode($listDiplome);  ?>')
                for (var i = 0; i < personnes.length; i++) {
                    if ( id_personne == personnes[i]['id'])
                    {
                        idIdentity = i;
                    }
                }
                diplome.forEach(function (value) {
                    if(  name_diplome == value['libelle'])
                        {
                            idDiplome = value['id'];
                        }
                });
                document.getElementById("id2").value = id_personne
                document.querySelector("#nom2").innerHTML = elements.item(1).innerHTML
                document.querySelector("#pre2").innerHTML = elements.item(2).innerHTML
                document.querySelector("#fil2").value = idDiplome
                document.querySelector("#email2").value = personnes[idIdentity].identity.email
>>>>>>> 7293e14236425f69e08d7fefb1270061ca747ab7
                $( "#modif" ).modal( "show" );
            });

            $( ".add" ).on( "click", function(e) {
                /*var elements = e.target.parentElement.parentElement.querySelectorAll("th")
                document.querySelector("#nom3").innerHTML = elements.item(1).innerHTML +" "+elements.item(2).innerHTML
                document.getElementById("fil2").value = elements.item(3).innerHTML;*/
                $( "#ajout" ).modal( "show" );
            });

            $( ".del" ).on( "click", function(e) {
                var elements = e.target.parentElement.parentElement.querySelectorAll("th")
                document.getElementById("idS").value = elements.item(0).innerHTML
                document.querySelector("#nomS").innerHTML = elements.item(1).innerHTML +" "+elements.item(2).innerHTML;
                $( "#sup" ).modal( "show" );
            });
        } );

    </script>
@endsection
