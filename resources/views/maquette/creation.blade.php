@extends('layouts.app')




@section('content')
<br>

<select id="archive" >
<option selected></option>
<?php
foreach (json_decode(stripslashes($data["archive"])) as $annee) {
    echo"<option value='".$annee->annee."'>".$annee->annee."</option>";
}?>
</select>  <button id="archbut" onclick="archClick()" >charger une maquette des annees précédentes</button>
<br>
</br>
<button onclick="delAll()">Suprimer toute les Ue et matiere </button>
<br>
<div id="semestre" class="container-fluid"><!-- les tableau s'afficheront dans cette div au chargement de la page -->
<br>
</div>



	
<button id="postB" >Modifier le semestre </button><!--Bouton servant a enregistre la requete-->

	

<br><br>






<div id='overlay'><!--le pop ud qui s'affiche lorsque l'on click sur une textarrea de la colone "detail (si tu veux le desactive va dans le json et met en com les fonction open et close box va aussi dans le css pour supprimer le resize:none de la class detail--> 
<button onclick='closeBox()' >Fermer</button>
<br>
<textarea cols='100' rows='20' id='desbox'>test</textarea>
</div>

	

<div id='popup' >




</div>
<!-- Button trigger modal -->
<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModalLong">
  Launch demo modal
</button>
<!-- Modal -->
<div class="modal fade " id="exampleModalLong" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
  <div class="modal-dialog modal-lg "role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">Modal title</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
	  test
 <form class="form-horizontal">
            <fieldset>
            
            <!-- Form Name -->
            <legend>Ajouter une UE</legend>
            
            <!-- input Designation-->
            <div class="form-group">
              <label class="col-md-4 control-label" for="textinput">Désignation</label>  
              <div class="col-md-4">
              <input id="textinput" name="textinput" placeholder="Désignation" required="" class="form-control input-md" type="text"> 
              </div>
            </div>
            
            <!-- TextAera Description -->
            <div class="form-group">
              <label class="col-md-4 control-label" for="textarea">Description</label>
              <div class="col-md-4">                     
                <textarea class="form-control" id="textarea" required="" name="textarea">Description</textarea>
              </div>
            </div>
            
            <!-- Select Responsable -->
            <div class="form-group">
              <label class="col-md-4 control-label" for="selectbasic">Responsable</label>
              <div class="col-md-4">
                <select id="selectbasic" name="selectbasic" class="form-control">
                </select>
              </div>
            </div>
            
            <!-- Input Coeff-->
            <div class="form-group">
              <label class="col-md-4 control-label" for="textinput">Coefficiant</label>  
              <div class="col-md-4">
              <input id="coeff" name="textinput" placeholder="Coeff" class="form-control input-md" required="" type="number">
                
              </div>
            </div>
            
            <!-- Input ECTS-->
            <div class="form-group">
                <label class=" col-md-4 control-label" for="textinput">ECTS</label>  
                <div class="col-md-4">
                <input id="textinput" name="textinput" placeholder="ECTS" class="form-control input-md" required="" type="number">
                  
                </div>
              </div>
            <!-- Création de Sous-UE ? -->
            <div class="form-group">
              <label class=" control-label" for="radios">Création de sous-ue ?</label>
              <div class=""> 
                <label class="radio-inline" for="radios-0">
                  <input name="radios" id="radios-0" value="1" checked="checked" type="radio">
                  oui
                </label> 
                <label class="radio-inline" for="radios-1">
                  <input name="radios" id="radios-1" value="2" type="radio">
                  non
                </label>
              </div>
            </div>
            
            <!-- Input Nb Sous-UE -->
            <div class="form-group">
              <label class=" control-label" for="textinput">Nombre de sous-ue(s)</label>  
              <div class="">
              <input id="textinput" name="textinput" placeholder="nombre" class="form-control input-md" type="text">
                
              </div>
            </div>
            
            
            
            <!-- Buttons  -->
            <div class="form-group">
              <div class="col-md-8">
                <button id="button0id" name="button0id" class="btn btn-danger">Annuler</button>
                <button id="button1id" name="button1id" class="btn btn-primary">Valider et en créer une autre</button>
                <button id="button2id" name="button2id" class="btn btn-success">Terminer</button>
              </div>
            </div>
            
            </fieldset>
            </form>
		
      </div>
      <div class="modal-footer">
       
      
      </div>
    </div>
  </div>
</div>
@endsection

@section('css')
	<link rel="stylesheet" href="{{ URL::asset('css/maquette.css') }}" />
@endsection
@section('script')
<script type="text/javascript" src="{{ URL::asset('js/maquette.js') }}"></script>
<script>

 var table = document.getElementById("previsio");
 var prof=' {!! $data["enseignant"] !!}';
 
 var filiere="{!!addslashes(json_encode($data))!!}";//sans le add slash un probleme se crée lors du passage en js si il y a un saut de ligne */
 var archive='{!! $data["archive"] !!}';
var semestre=' {!! $data["semestre"] !!}'
	function mFunction()///fonction s'activant a la création de la table 
	{ 
		getSemestre(filiere);///affiche les donnée
		for(var i=0;i<document.getElementsByClassName("newue").length;i++)
		{
			
			document.getElementsByClassName("newue")[i].addEventListener("click",function()/////ajoute un event listener qui crée un nouvelle row pour les ue une row pour la matiere attachée a cette row
				{
					table=this.parentElement.parentElement.parentElement.parentElement;//table est le tableau du semestre du bouton
					newue(JSON.parse(prof),table)
					lastRow=table.rows[table.rows.length-1];
					
					newmat(lastRow);
				})
		}
	}
	function mFunction2()///fonction s'activant a la création de la table 
	{ 
	
		console.log(prof);
		var sem=JSON.parse(semestre);
		loadSemestre(sem);
		for(var i=0;i<document.getElementsByClassName("newue").length;i++)
		{
			
			document.getElementsByClassName("newue")[i].addEventListener("click",function()/////ajoute un event listener qui crée un nouvelle row pour les ue une row pour la matiere attachée a cette row
				{
					table=this.parentElement.parentElement.parentElement.parentElement;//table est le tableau du semestre du bouton
					newue(JSON.parse(prof),table)
					lastRow=table.rows[table.rows.length-1];
					
					newmat(lastRow);
				})
		}
		
	}
	 function archClick()
	{
		
		if(document.getElementById("archive").value=="")
			alert("Selctionné une année");
		
		else{
		r=confirm("Vouler vous chargée cette maquette")
	if (r==false)
		return"";
	loadArchive(JSON.parse(archive),JSON.parse(prof));
	}
	}
   
 $("#postB").click(function(){////////fonction de la requete ajax pour enregistrer la maquete  dans la bdd 
         var send=getjson();////send est un tableau la premiere case indique si il n'y a pas d'erreur dans notre maquete si elle est a un aucune erreur n'a ete detectée
							//la seconde case est le json de la maquete si il n'ya pas d'erreur si il y en a lors c'est le message d'erreur a affichér
		
		 
		if(send[0]==1)
		 {
			  var data={id_diplome:JSON.parse(filiere).diplome,
			  detail:send[1]
			  };
			console.log(send[1]);
			 $.ajax({
				   type:'POST',
				   url:'save2',
					 headers: {
			'X-CSRF-TOKEN': '<?php echo csrf_token(); ?>'
			
			  },
			  //data:,
			   data: {'filiere' : data},
			 // dataType: 'JSON',
			success:function(msg,data, settings) {
					alert('La maquète a été enregistrée');
								},
			error:function(){	alert('Erreur');}	
					
				   
				});
		 }
		else if(send[1]!=null)
			alert(send[1]);
		var car = {type:"Fiat", model:"500", color:["yellow","red"]};
		
	
		
	});
	
	
document.addEventListener("load",mFunction2());

</script>
@endsection