<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');
include 'Controleur.php';
$c=new Controleur($dbh);
if (strlen($_SESSION['sid']==0)) {
 header('location:logout.php');
} 

if(isset($_GET['dev'])) {
  $id = intval($_GET['id']);
  $stmt = $dbh->prepare("UPDATE fact SET devis = 1, bcmd = 0, facture = 0 WHERE iddevis = :id");
  $stmt->execute(['id' => $id]);
}

if(isset($_GET['bon'])) {
  $id = intval($_GET['id']);
  $stmt = $dbh->prepare("UPDATE fact SET devis = 0, bcmd = 1, facture = 0 WHERE iddevis = :id");
  $stmt->execute(['id' => $id]);
}

if(isset($_GET['fac'])) {
  $id = intval($_GET['id']);
  $stmt = $dbh->prepare("UPDATE fact SET devis = 0, bcmd = 0, facture = 1 WHERE iddevis = :id");
  $stmt->execute(['id' => $id]);
}

?>

<!DOCTYPE html>
<html>
<?php @include("includes/head.php"); ?>
<body class="hold-transition sidebar-mini">
  <div class="wrapper">
    <!-- Navbar -->
    <?php @include("includes/header.php"); ?>
    <!-- /.navbar -->

    <!-- Main Sidebar Container -->
    <?php
    if($_SESSION['service']=='controle'){
      @include("includes/sidebar.php");
    }
    if($_SESSION['service']=='formation'){
      @include("includes/sidebarf.php");
    }
    if($_SESSION['service']=='RH'){
      @include("includes/sidebarRH.php");
    }
    if($_SESSION['service']=='achat_dr'){
      @include("includes/sidebarachat_dr.php");
    }
    if($_SESSION['service']=='achat_cmplx'){
      @include("includes/sidebarachat_cmplx.php");
    }
    if($_SESSION['service']=='compta'){
      @include("includes/sidebarcompta.php");
    }
    ?>

<style>

@media (min-width: 1024px) {
  #tp{

 width:140px;
 height:38px;
 margin-top:32px;

}
#ch{
  border: none; 
  outline: none; 
  width: 100px; 
  height: 95px;
  background-color: transparent;
  color: blue;
  margin-left:10px;
  margin-top:-80px;
}

#ch1{
  border: none; 
  outline: none; 
  width: 130px; 
  height: 95px;
  background-color: transparent;
  color: blue;
  margin-left:10px;
  margin-top:-80px;
}

}


/* Style pour les écrans mobiles */
@media (max-width: 700px) {
  #tp{

  width:140px;
  height:30px;
  margin-top:10px;
  margin-left:10px;
}


#ch{
  border: none; 
  outline: none; 
  width: 100px; 
  height: 95px;
  background-color: transparent;
  color: blue;
  
}

#ch1{
  border: none; 
  outline: none; 
  width: 120px; 
  height: 95px;
  background-color: transparent;
  color: blue;
  
}

}

</style>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
      <!-- Content Header (Page header) -->
      <section class="content-header">
        <div class="container-fluid">
          <div class="row mb-2">
            <div class="col-sm-6">
              <h1>Détails Paiement sociétés</h1>
            </div>
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="dash.php">Accueil</a></li>
                <li class="breadcrumb-item active">Reçu de paiement société</li>
              </ol>
            </div>
          </div>
        </div><!-- /.container-fluid -->
      </section>
       <div class="card-header">
                                  <form method="post"  >
              <div class="row">
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;   <div class="form-group col-md-3">
                          <label for="names">Selectionner la Date de Debut</label>
                          <input type="date" class="form-control" id="dd" name="dd" placeholder="Nom" required>
                        </div>
                        <div class="form-group col-md-3">
                          <label for="prenom">Selectionner la Date De Fin</label>
                          <input type="date" class="form-control" id="df" name="df" placeholder="Prenom" required>
                          </div>
                          </div>

<button  type="submit" name="chercher" id="ch" ><i class="fa fa-search" ></i> Chercher</button>
<button  type="submit" name="cht" id="ch1" ><i class="fa fa-search" ></i> afficher tous</button>
<div class="form-group col-md-3">
                         
                         
                      
<script>
// Obtenez la balise input type date par son ID
const dd = document.getElementById("dd");
const df = document.getElementById("df");

// Obtenez la date d'aujourd'hui sous forme de chaîne (AAAA-MM-JJ)
const today = new Date().toISOString().split('T')[0];

// Définir la valeur par défaut de la balise sur la date d'aujourd'hui
dd.value = today;
df.value = today;
</script>
                
                </div>
      <!-- Main content -->
      <section class="content"  style="margin-top:-30px;">
        <div class="container-fluid">
          <div class="row">
            <div class="col-12">
              <div class="card">
                <!--   end modal -->

                <div class="card-body mt-2">
                    <div class="table-responsive">
                  <table id="example1" class="table table-bordered table-hover">
                    <thead> 
                      <tr> 
                        <th>#</th>
                        <th>Société</th>
                        <th>Formation</th>
                        <th>Prix</th>
                        <th>Devis</th>
                        <th>B.commande</th>
                        <th>Facture</th>
                        <th>Date</th>
                        <th>Action</th>
                        
                      </tr> 
                    </thead> 
                    <tbody>
                      <?php

      $t=0;
      $f1=0;
      $s=0;

      if(isset($_GET['payer'])){
        $req="UPDATE fact SET payer = 1 WHERE iddevis='$_GET[payer]'";
        $query=mysqli_query($con,$req);
        $query=mysqli_query($con,"SELECT * FROM fact WHERE DATE_FORMAT(datef,'%Y-%m') >= DATE_FORMAT(DATE_SUB(NOW(), INTERVAL 1 MONTH),'%Y-%m')");
        
      }

      $query=mysqli_query($con,"SELECT * FROM fact WHERE DATE_FORMAT(datef,'%Y-%m') >= DATE_FORMAT(DATE_SUB(NOW(), INTERVAL 1 MONTH),'%Y-%m')");
      if(isset($_POST['chercher'])){
          $dd=$_POST['dd'];
          $ff=$_POST['df'];
          $query=mysqli_query($con,"SELECT * FROM fact WHERE datef between '$dd' and '$ff'");    
      }
      if(isset($_POST['cht'])) { 
        $query=mysqli_query($con,"SELECT * FROM fact");
      }  
        $d='';$b='';$fa='';
        $style1="";$style2="";$style3="";
                    
                      while($row=mysqli_fetch_array($query))
                      {
                        if($row['devis']==0){
                          $style1="style=background-color:red";
                        } else{
                          $style1="style=background-color:green";
                        }  
                        if($row['facture']==0){
                          $style3="style=background-color:red";
                        } else{
                          $style3="style=background-color:green";
                        } 
                        if($row['bcmd']==0){
                          $style2="style=background-color:red";
                        } else{
                          $style2="style=background-color:green";
                        } 
                        

                        $nom=$c->chercher_ste_ids($row['ids']);

                        ?> 
                        
                        <tr>
                          <td class="donnees-td"><?php echo htmlentities($row['iddevis']);?></td>
                          <td><a href="javascript:void(0);" onclick="submite(<?php echo htmlentities($row['iddevis']);?>);"><?php echo htmlentities($nom);?></a></td>
                          <td><?php echo htmlentities($row['nature']); ?></td>
                              <td><?php echo htmlentities($row['prix']); ?></td>

                              <td  <?php echo $style1; ?>>
                              <span style="color: transparent;"><?php echo htmlentities($row['devis']); ?></span>
                              <a href="facture_ste.php?id=<?php echo $row['iddevis']?>&dev=delete" onClick="return confirm('Etes-vous sûr que vous voulez Modifier?')" style="color: black;" >
                                Devis</a>
                              </td>

                              <td <?php echo $style2; ?>>
                              <span style="color: transparent;"><?php echo htmlentities($row['bcmd']); ?></span>
                              <a href="facture_ste.php?id=<?php echo $row['iddevis']?>&bon=delete" onClick="return confirm('Etes-vous sûr que vous voulez Modifier?')" style="color: black;">
                              B.commande</a>
                              </td>

                              <td  <?php echo $style3; ?>>
                              <span style="color: transparent;"><?php echo htmlentities($row['facture']); ?></span>
                              <a href="facture_ste.php?id=<?php echo $row['iddevis']?>&fac=delete" onClick="return confirm('Etes-vous sûr que vous voulez Modifier?')" style="color: black;">
                              Facture</a>
                              </td>

                              <td><?php echo htmlentities($row['datef']); ?></td>
                              <td><a class="btn w-100  btn-sm <?php if($row['payer']==1 || $row['facture']!=1){if($row['payer']==1){echo 'disabled btn-danger';}else{echo 'disabled btn-info';}}else{echo' btn-primary';} ?>" 
                                  onClick="return confirm('Etes-vous sûr que vous voulez payer cette facture?')" 
                                  href="facture_ste.php?payer=<?php echo $row['iddevis']?>"><?php if($row['payer']==1){echo '<small>Facture payée</small> ';}else{if($row['facture']==1 and $row['payer']==0){echo '<small>Payer</small>';}else{if($row['payer']==0){echo '<small>Attend Validation</small>';}}} ?>
                                </a></td>

                              <input type="hidden" name="id" value="<?php echo htmlentities($row['iddevis']); ?>">
                        </tr>
                        <?php $cnt=$cnt+1;
                      } ?>
                    </tbody>
                  </table>
                   
                  </div>
               
                </div>
                <!-- /.card-body -->
              </div>
              <!-- /.card -->
            </div>
            <!-- /.col -->
          </div>
          <!-- /.row -->
        </div>
        <!-- /.container-fluid -->
      </section>
      <!-- /.content -->
     
                  
    </div>
    <!-- /.content-wrapper -->
    
   


    <!-- Control Sidebar -->
    <aside class="control-sidebar control-sidebar-dark">
      <!-- Control sidebar content goes here -->
     
    </aside>
    <!-- /.control-sidebar -->
  </div>
  
  <!-- ./wrapper -->
  <?php @include("includes/foot.php"); ?>
 
    
<script type="text/javascript">


// Fonction pour ajouter un événement de clic à chaque ligne de la table
function ajouterEvenementsClic() {
  var lignes = document.querySelectorAll('#example1 tr');
  lignes.forEach(function(ligne) {
    ligne.addEventListener('click', function() {
      afficherContenuLigne(this); // Passer la ligne actuelle en tant que paramètre
    });
  });
}


function afficherContenuLigne(ligne) {
  var cellules = ligne.querySelectorAll('td');
  var contenuLigne = "";

  cellules.forEach(function(cellule, index) {
    if (index > 0) {
      contenuLigne += ", ";
    }
    contenuLigne += cellule.innerText;
  });

  
 
  var parts = contenuLigne.split(',');
var ste = parts[1].trim();
var formation = parts[2].trim();
var prix = parts[3].trim();
var devis = parts[4].trim();
var fac = parts[5].trim();
var bon = parts[6].trim();
var tp = "";

// Extraire le premier caractère de chaque valeur pour vérifier
var devisVal = devis.substring(0, 1);
var facVal = fac.substring(0, 1);
var bonVal = bon.substring(0, 1);

if (devisVal == "1") {
    tp = "Devis";
} else if (facVal == "1") {
    tp = "Bon De Commande";
} else if (bonVal == "1") {
    tp = "Facture";
}

var dt = parts[7].trim();

var num_r = Math.floor(Math.random() * 10000) + 1;
var tp1 = tp; // tp est déjà une chaîne

//alert("Contenu de la ligne sélectionnée : " + tp1);


genererRecu(ste,formation,prix,tp1,dt,num_r);
   
}



async function submite(iddevis) {

 //ajouterEvenementsClic();
 event.preventDefault();

// Envoyer une requête AJAX au serveur pour récupérer les données
const response = await fetch('getData.php?iddevis=' + iddevis);
const data = await response.json();

// Vérifier si les données sont correctement récupérées
if (data) {
  var raison_sociale=data.raison_sociale
  var adresse=data.adresse
  var email=data.email
  var tel=data.tel
  var fix=data.fix
  var nature=data.nature
  var duree=data.duree
  var prix=data.prix
  var nbr_personne=data.nbr_personne
  var datef=data.datef
  var lieu_deroulement=data.lieu_deroulement
  var payer=data.payer
  var etat=""
  if(data.bcmd==1){etat="B.command"}
  if(data.devis==1){etat="Devis"}
  if(data.facture==1){etat="Facture"}
  genererRecu(etat,raison_sociale,lieu_deroulement,datef,nbr_personne,prix,duree,nature,fix,tel,email,adresse,raison_sociale,payer)
} else {
    alert('Erreur lors de la récupération des données.');
}
  
    }
    var t=['','mille','millions','milliards']
            var wahadat=['','un','deux','troix','quatre','cinq','six','sept','huit','neuf']
            var whadatMabin10w20=['dix','onze','douze','treise','quatorze','quinze','seize','dix-sept','dix-huit','dix-neuf']
            var acharat=['vingt','trente','quarante','cinquante','soixante','soixante-dix','quatre-vinght','quatre-vinght']
            function transfere_nbr_lettre(nbr){
                var inp=-1
                for(var i=0;i<nbr.length;i++){
                    if(nbr[i]=="," || nbr[i]=="."){
                        inp=i
                        break
                    }
                }
                if(inp==-1){return (avantVirgule(nbr))}
                if(inp==nbr[nbr.length-1]){return(avantVirgule(nbr.slice(nbr.length-2,nbr.length)))}
                if(inp!=-1 &&   inp!=nbr[nbr.length-1]){
                    var avant_virgule=nbr.slice(0,inp)
                    var apres_virgule=nbr.slice(inp+1,nbr.length)
                    var res=avantVirgule(avant_virgule)+apresVirgule(apres_virgule)
                    return (res)
                }
            }
            function apresVirgule(nbr){
                var inp=-1
                var res=" virgule "
                for(var i=0;i<nbr.length;i++){
                    if(nbr[i]!="0"){
                        inp=i
                        break
                    }
                }if(inp==-1){return ""}
                else{
                    for(var j=0;j<inp;j++){
                        res+="zero "
                    }
                    for(var i=0;i<nbr.length;i++){
                       if(nbr[nbr.length-(i+1)]!="0"){
                           break
                       }
                    }
                    var ff=nbr.slice(inp,nbr.length-i)
                    return res+=avantVirgule(String(ff))
                }
        }
            function avantVirgule(nbr){
                if(nbr.length>=0 && nbr.length<=3){
                    if(mi2at(nbr)==""){
                        return ("zero")
                    }else{return mi2at(nbr)}
                }
                else{
                    var res=""
                    j=nbr.length
                    k=0
                    var espace=0
                    while (k<parseInt(nbr.length/3)){
                        const firstPart = nbr.slice(j-3,j);
                        if(mi2at(firstPart)!=''){
                            if(mi2at(firstPart)==="un" && t[k]!="mille"){
                                res=mi2at(firstPart)+" "+(t[k].slice(0,(t[k].length-1)))+" "+res
                            }
                            else{
                                res=mi2at(firstPart)+" "+t[k]+" "+res
                                espace+=2
                            }
                        }
                        if(mi2at(firstPart)==''){
                            res=mi2at(firstPart)+" "+res
                        }
                        k+=1
                        j=j-3
                    }
                    if(nbr.length%3==0){
                        if(res==' '*espace){return "zero"}
                        else{return res}
                    }
                    else{
                        const firstPart = nbr.slice(0,j);
                        if(mi2at(firstPart)!=""){
                            if(mi2at(firstPart)==="un" && t[k]!="mille"){
                                res=mi2at(firstPart)+" "+(t[k].slice(0,(t[k].length-1)))+" "+res
                            }
                            else{
                                res=mi2at(firstPart)+" "+t[k]+" "+res
                                espace+=2
                            }
                            if(mi2at(firstPart)==''){
                                if(res==' '*espace){return "zero"}
                                else{return res}
                            }
                            if(res==' '*espace){return "zero"}
                            else{return res}
                        }
                        else{
                            if(res==' '*espace){return "zero"}
                            else{return res}
                        }
                        
                    }
                }
            }
            function wahadatt(nbr){
                var n=Number(nbr)
                if(n===0){
                    return ""
                }else{return wahadat[n]}
            }
            function acharatt(nbr){
                  if(nbr[0]==="1"){
                     return whadatMabin10w20[Number(nbr[1])]
                  }else{
                    if(nbr[0]==="0"){
                        var res=wahadatt(nbr[1])
                        return res
                    }
                    else{
                        var res=acharat[Number(nbr[0])-2]
                        if(nbr[1]=="0"){
                            if(nbr[0]=="9"){
                                res+="-dix"
                                return res
                            }else{
                                return res
                            }
                        }else{
                            if(nbr[0]=="8"){
                                res+="-"+wahadat[Number(nbr[1])]
                                return res
                            }
                            if(nbr[0]=="9"){
                                res+="-"+whadatMabin10w20[Number(nbr[1])]
                                return res
                            }
                            if(nbr[0]!="8" && nbr[0]!="9"){
                                if(nbr[1]=="1"){
                                    res+=" et un"
                                    return res
                                }else{
                                    res+=" "+wahadat[Number(nbr[1])]
                                    return res
                                }
                            }
                        }

                    }
                  }
            }
          function mi2at(nbr){
                if(nbr.length===3){
                    if(nbr[0]!="0"){
                        if(wahadatt(Number(nbr[0]))==="un"){var res =" cent"}
                        else{var res =wahadatt(Number(nbr[0]))+" cent"}
                    nb=nbr[1]+nbr[2]
                    res+=" "+acharatt(nb)
                    return res
                }
                    if(nbr[0]=="0"){var res=acharatt(nbr[1]+nbr[2])
                        return res
                    }
                    
                }
                if(nbr.length===2){
                  var res=acharatt(nbr)
                  return res
                }
                if(nbr.length===1){
                    var res=wahadatt(nbr)
                    return res
                }
            }
  function capitalizeFirstLetter(str) {
      if (typeof str !== 'string') return ''; // Handle edge case if str is not a string
      return str.charAt(0).toUpperCase() + str.slice(1);
  }
  function genererRecu(etat,raison_sociale,lieu_deroulement,datef,nbr_personne,prix,duree,nature,fix,tel,email,adresse,raison_sociale,payer) {
     //alert(tp1);
     var num_r = Math.floor(Math.random() * 10000) + 1;
    var entreprise = "OFPPT";
    var currentDate = new Date();
    var tva=Number(prix)*20/100
    var total=Number(tva)+Number(prix)
    var nbr_transferé=capitalizeFirstLetter(transfere_nbr_lettre(String(total)))
    // Get current day, month, and year
    var currentDay = currentDate.getDate();
    var currentMonth = currentDate.getMonth() + 1; // Month is zero-indexed, so we add 1
    var currentYear = currentDate.getFullYear();
    var fact=String(currentMonth)+"-"+String(currentDay)
    var date=String(currentYear)+"-"+fact
    var dateRecu = new Date().toLocaleDateString();
      var htmlRecu =`
    <style>
        .table {
            margin-left: 5%;
            width: 90%;
            border-collapse: collapse;
            border: 1px solid black;
            
        }
        tr{
            border: 1px solid black;
        }
        td {
            border: 1px solid black;
            text-align: center;
        }
        th{
            border: 1px solid black;
            text-align: center;   
        }
        
        .w{
            width: 60%;
            border-collapse: collapse;
            position:relative;
            left:35%;
        }
        img {
              width: 100%;
              height:150px;
              margin-right: 10px;
          }
    </style>
    <img src="../assets/img/imgOfpptt.png" alt="" >
    <table class="table" style="margin-top:2rem;">
        <tr>
            <th style="width: 30%;">Entreprise</th>
            <th style="width: 20%;">${etat}</th>
            <th style="width:50%;">Date</th>
        </tr>
        <tr>
            <th style="width: 30%;">${raison_sociale}<br>${adresse}<br>Tel:${tel}<br>Fix:${fix}</th>
            <th style="width: 20%;">${fact}</th>
            <th style="width:50%;">${date}</th>
        </tr>
        <tr>
            <td style="width: 30%;">Lieu de déroulement</td>
            <td colspan="2" style="width: 70%;">${lieu_deroulement}</td>
        </tr>
    </table>
    <table class="table" style="position: relative; bottom: 17px;">
        <tr>
            <th>Désignation</th>
            <th>Nombre de jours</th>
            <th>Date de réalisation</th>
            <th>Effectif de participants</th>
            <th>Montant HT</th>
        </tr>
        <tr class="td">
            <td style="width: 30%">${nature}</td>
            <th>${duree}</th>
            <th><span>${datef}</span></th>
            <th>${nbr_personne}</th>
            <th>${prix}</th>
        </tr>
    </table>
    <table class="w" style="position: relative; bottom: 34px;">
        <tr style="width:100%">
            <th style="max-width:100%">TOTAL H.T</th>
            <th  style="color: blue;max-width:0%">${prix}</th>
        </tr>
        <tr style="width:100%">
            <th style="max-width:100%">TVA 20%</th>
            <th style="color: blue;max-width:0%">${tva}</th>
        </tr>
        <tr style="width:100%">
            <th style="max-width:100%">TOTAL TTC</th>
            <th style="color: blue;max-width:0%" >${total}</th>
        </tr>
    </table>
    <p style="text-align:center;font-family:'Lucida Sans', 'Lucida Sans Regular', 'Lucida Grande', 'Lucida Sans Unicode', Geneva, Verdana, sans-serif">Aprés la présente facture à la somme de <span style="color: blue;">${nbr_transferé} Dirhams</span> TTC</p>
    <br><p style="text-align: center;"><strong>Mode de règlement : Chèque barré adressé à la Direction Régionale Marrakech Safi <br>
        Ou Virement Bancaire au Compte de la DRMS Marrakech : <br>
        N° 011 450 000005 210 01 60588 49 - BMCE - Centre d’Affaires Marrakech AV France <br>
        Direction Régionale Marrakech Safi <br>
        ICE: 001674314000081    IF: 1622622
        </strong></p>
      `
      html2pdf()
      .set({
        margin: [20, 20, 20, 20],
        html2canvas: { scale: 4 }
      })
      .from(htmlRecu)
      .save("recu.pdf");
   
  }
  
    
$(document).ready(function() {
  // Détruire l'instance existante de DataTables
  if ($.fn.DataTable.isDataTable('#example1')) {
    $('#example1').DataTable().destroy();
  }
$('#example1').DataTable({
  "language": {
    "sProcessing": "Traitement en cours ...",
    "sLengthMenu": "Afficher _MENU_ lignes",
    "sZeroRecords": "Aucun résultat trouvé",
    "sEmptyTable": "Aucune donnée disponible",
    "sInfo": "Lignes _START_ à _END_ sur _TOTAL_",
    "sInfoEmpty": "Aucune ligne affichée",
    "sInfoFiltered": "(Filtrer un maximum de_MAX_)",
    "sSearch": "Chercher:",
    "sInfoThousands": ",",
    "sLoadingRecords": "Chargement...",
    "oPaginate": {
      "sFirst": "Premier", "sLast": "Dernier", "sNext": "Suivant", "sPrevious": "Précédent"
    },
    "oAria": {
      "sSortAscending": ": Trier par ordre croissant", "sSortDescending": ": Trier par ordre décroissant"
    }
  },
  "lengthMenu": [25,50,100]
});
});
</script>
</body>
</html>
