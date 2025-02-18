<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');
include 'Controleur.php';

$c=new Controleur($dbh);
if (strlen($_SESSION['sid']==0)) {
 header('location:logout.php');
} 
if(isset($_GET['del']))
{

  mysqli_query($con,"delete from demande_prix_complex where id_demande = '".$_GET['id']."'");
  
}
if(isset($_GET['bon'])) {
  $id = intval($_GET['id']);
  $stmt = $dbh->prepare("UPDATE demande_prix_complex SET deviis = 0, bcmd = 1, facture = 0 WHERE id_demande = :id");
  $stmt->execute(['id' => $id]);
  header("location:reste_payer_clx.php");
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
     if($_SESSION['service']=='achat'){
    
      @include("includes/sidebarachat.php");
     }
     if($_SESSION['service']=='compta'){
    
      @include("includes/sidebarcompta.php");
     }
     if($_SESSION['service']=='achat_dr'){
        @include("includes/sidebarachat_dr.php");
      }
      if($_SESSION['service']=='achat_cmplx'){
        @include("includes/sidebarachat_cmplx.php");
      }
     
     ?>
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
      <!-- Content Header (Page header) -->
      <section class="content-header">
        
        <div class="container-fluid">
          <div class="row mb-2">
            <div class="col-sm-6">
              <h1>Détails demandes</h1>
            </div>
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="dash_achat_cmplx.php">Accueil</a></li>
                <li class="breadcrumb-item active">Gérer les demandes</li>
              </ol>
            </div>
          </div>
        </div><!-- /.container-fluid -->
      </section>
      <!-- Main content -->
      <section class="content">
        <div class="container-fluid">
          <div class="row">
            <div class="col-12">
              <div class="card">
                <div class="card-header">
                <form method="post">
              <div class="row">
    &nbsp;&nbsp;&nbsp;   <div class="form-group col-md-3">
                          <label for="names">Selectionner la Date de Debut</label>
                          <input type="date" class="form-control" id="dd" name="dd" placeholder="Nom" required>
                        </div>
                        <div class="form-group col-md-3">
                          <label for="prenom">Selectionner la Date De Fin</label>
                          <input type="date" class="form-control" id="df" name="df" placeholder="Prenom" required>
                           </div>
<button  type="submit" name="chercher" style="border: none; outline: none; width: 100px; height: 95px;background-color: transparent;color: blue;"><i class="fa fa-search" ></i> Chercher</button>
<button  type="submit" name="cht" id="ch1" style="border: none; outline: none; width: 150px; height: 95px;background-color: transparent;color: blue;" ><i class="fa fa-search" ></i> afficher tous</button>   

<div class="col-12">
  <div class="card">
  <div class="card-header">
                  <h3 class="card-title">Gérer les demandes</h3>
                  <div class="card-tools row">
                  <a href="add_demande_prix_clx.php" id='btnf' class="btn btn-sm btn-primary" style="margin-right: 2px;"><span style="color: #fff;"><i class="fas fa-plus" ></i>Demande</span>
                </a>
                <button type="button" class="btn btn-sm btn-primary" onclick="expo()" id='btnexp' ><span style="color: #fff;"><i class="fas fa-download" ></i> Exporter</span></button>

                  </div>
                </div>
  </div>
</div>
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
                 
                </div>
                <!-- /.card-header -->
                <div id="editData" class="modal fade">
                  <div class="modal-dialog modal-xl">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h5 class="modal-title">Détails demande</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                        </button>
                      </div>
                      <div class="modal-body" id="info_update">
                        <?php 
                        @include("edit_demende_prix_clx.php");
                        ?>
                      </div>
                      <div class="modal-footer ">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Annuler</button>
                      </div>
                      <!-- /.modal-content -->
                    </div>
                    <!-- /.modal-dialog -->
                  </div>
                  <!-- /.modal -->
                </div>
                <!--   end modal -->
               
                <div id="editData2" class="modal fade">
                  <div class="modal-dialog ">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h5 class="modal-title">Détails demandes</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                        </button>
                      </div>
                      <div class="modal-body" id="info_update2">
                      </div>
                      <div class="modal-footer ">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Annuler</button>
                      </div>
                      <!-- /.modal-content -->
                    </div>
                    <!-- /.modal-dialog -->
                  </div>
                  <!-- /.modal -->
                </div>
                <!--   end modal -->

                <div class="card-body mt-2 " >
                    <div class="table-responsive">
                  <table id="example1" class="table table-bordered table-hover">
                    <thead> 
                      <tr> 
                        <th>#</th>
                        <th>Fournisseur</th>
                        <th>Besoin</th>
                        <th>Devis</th>
                        <th>B.command</th>
                        <th>Montant</th>
                        <th>Date</th>
                        <th>Action</th>
                      </tr> 
                    </thead> 
                    <tbody>
                      <?php
$query = mysqli_query($con, "SELECT * FROM demande_prix_complex WHERE DATE_FORMAT(dated,'%Y-%m') >= DATE_FORMAT(DATE_SUB(NOW(), INTERVAL 12 MONTH),'%Y-%m') and idcomplex=(select idcomplex from complexe1 where id=$_SESSION[sid]) and deviis=1");
if(isset($_POST['cht'])){
                        $query=mysqli_query($con,"SELECT * FROM demande_prix_complex where idcomplex=(select idcomplex from complexe1 where id=$_SESSION[sid]) and deviis=1");
                      }
                      if(isset($_POST['chercher'])){
                        $dd=$_POST['dd'];
                        $ff=$_POST['df'];
                        $query=mysqli_query($con,"SELECT * FROM demande_prix_complex WHERE dated between '$dd' AND '$ff' and idcomplex=(select idcomplex from complexe1 where id=$_SESSION[sid]) and deviis=1");    
                }
                
                      $cnt=1;
                      while($row=mysqli_fetch_assoc($query)){
                      if($row['deviis']==0){
                        $style1="style=background-color:red";
                      } else{
                        $style1="style=background-color:green";
                      }  
                      if($row['bcmd']==0){
                        $style2="style=background-color:red";
                      } else{
                        $style2="style=background-color:green";
                      }
                      
                        ?>                 
                        <tr>
                          <td><a href="javascript:void(0);" onclick="submite(<?php echo htmlentities($row['id_demande']);?>);"><?php echo htmlentities($row['id_demande']);?></a></td>
                          <td><?php 
                            $queryy=mysqli_query($con,"SELECT raison_sociale from fournisseur where idfour=$row[idfour]");
                            $roww=mysqli_fetch_assoc($queryy);
                            echo $roww['raison_sociale'];
                          ?></td>
                          <td>
                            <center><a href="viewPdf.php?besoin=<?php echo $row['besoin'] ?>" class="btn btn-success btn-xs w-75" target="_blank">Voir</a></center>
                          </td>
                          <td  <?php echo $style1; ?>>
    <span style="color: transparent;"><?php echo htmlentities($row['devis']); ?></span>
    <span>Devis</span>

    </td>

    <td <?php echo $style2; ?>>
    <span style="color: transparent;"><?php echo htmlentities($row['bcmd']); ?></span>
    <a href="liste_demande_prix_clx.php?id=<?php echo $row['id_demande']?>&bon=delete" onClick="return confirm('Etes-vous sûr que vous voulez Modifier?')" style="color: black;">
    B.commande</a>
    </td>
                          <td><?php echo htmlentities($row['montant']);?></td>
                          <td><?php echo htmlentities($row['dated']);?></td>
                          <td>
                           <center> <button  class=" btn btn-primary btn-xs edit_dataa" id="<?php echo  $row['id_demande']; ?>" title="click for edit">Modifier</i></button>&nbsp;&nbsp;
                            <a href="liste_demande_prix_clx.php?id=<?php echo $row['id_demande']?>&del=delete" onClick="return confirm('Etes-vous sûr que vous voulez supprimer?')" class=" btn btn-danger btn-xs ">Supprimer</a>
                            </center>
                          </td>
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
   <script src="https://cdnjs.cloudflare.com/ajax/libs/exceljs/4.3.0/exceljs.min.js"></script>
   <script type="text/javascript">
        function expo(){

// Capturez le texte saisi dans l'input de recherche
var searchValue = $('#example1_filter input').val();

// Appliquez le filtre à la DataTable
var table = $('#example1').DataTable();
table.search(searchValue).draw();

// Créez un tableau pour stocker les données
var data = [];

// Obtenez les en-têtes de colonne de la DataTable
var headers = [];
table.columns().every(function () {
     
     if(this.header().textContent !="#" && this.header().textContent !="Action" && this.header().textContent !="Besoin" && this.header().textContent !="B.command" ){
      if(this.header().textContent=="Devis"){headers.push("Etat")}
       else{
        headers.push(this.header().textContent);
       }
    }
    
});


data.push(headers);

// Obtenez les données filtrées
var filteredData = table.rows({ filter: 'applied' }).data();

// Parcourez les données filtrées et ajoutez-les au tableau
filteredData.each(function (valueArray) {
    var rowData = [];
    valueArray.forEach(function (value,index) {
        
      
         if (index !=0 && index !=2 && index !=7 && index !=4) {
           if(index==3){var v="devis" 
            rowData.push(v);
           }
            else{rowData.push(value);}
           
        }
           
        
    });
    
    
     data.push(rowData);
});

// Créez un nouveau classeur Excel
var workbook = new ExcelJS.Workbook();
var worksheet = workbook.addWorksheet('CALEDRIER');

// Ajoutez les données au classeur Excel
data.forEach(function (row) {
    worksheet.addRow(row);
});

// Génèrez un blob de données Excel
workbook.xlsx.writeBuffer().then(function (buffer) {
    // Créez un objet blob
    var blob = new Blob([buffer], { type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' });

    // Créez un objet URL pour le blob
    var url = window.URL.createObjectURL(blob);

    // Créez un lien pour le téléchargement du fichier Excel
    var a = document.createElement('a');
    a.href = url;
    a.download = 'devis.xlsx';

    // Ajoutez le lien à la page et déclenchez le téléchargement
    document.body.appendChild(a);
    a.click();

    // Libérez l'URL de l'objet blob
    window.URL.revokeObjectURL(url);
});
}
   </script>
      <script type="text/javascript">

async function submite(id_demande) {

 //ajouterEvenementsClic();
 event.preventDefault();

// Envoyer une requête AJAX au serveur pour récupérer les données
const response = await fetch('getData_clx.php?id_demande=' + id_demande);
const data = await response.json();

// Vérifier si les données sont correctement récupérées
if (data) {
  var raison_sociale=data.raison_sociale
  var adresse=data.adresse
  var email=data.email
  var tel=data.tel
  var fix=data.fix
  //var duree=data.duree
  var prix=data.montant
  //var nbr_personne=data.nbr_personne
  var dated=data.dated
  //var lieu_deroulement=data.lieu_deroulement
  var payer=data.payer
  var etat=""
  if(data.bcmd==1){etat="B.command"}
  if(data.deviis==1){etat="Devis"}
  if(data.facture==1 && data.payer==0){etat="Facture non payée"}
  if(data.facture==1 && data.payer==1){etat="Facture payée"}
  genererRecu(etat,raison_sociale,dated,prix,fix,tel,email,adresse,raison_sociale,payer)
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
  function genererRecu(etat,raison_sociale,dated,prix,fix,tel,email,adresse,raison_sociale,payer) {
     //alert(tp1);
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
    <img src="../assets/logo/imgOfpptt.png" alt="" >
    <table class="table">
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
    </table>
    <table class="table" style="position: relative; bottom: 17px;">
        <tr>
            
            
            <th>Date de réalisation</th>
            <th>Montant HT</th>
        </tr>
        <tr class="td">
            
            
            <th><span>${dated}</span></th>
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
  
    

</script>
  <script type="text/javascript">

    $(document).ready(function(){
      $(document).on('click','.edit_dataa',function(){
        var edit_id=$(this).attr('id');
        $.ajax({
          url:"edit_demende_prix_clx.php",
          type:"post",
          data:{edit_id:edit_id},
          success:function(data){
            $("#info_update").html(data);
            $("#editData").modal('show');
          }
        });
      });
    });
  </script>
  <script type="text/javascript">
      
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
  }
});
});
  </script>
</body>
</html>
