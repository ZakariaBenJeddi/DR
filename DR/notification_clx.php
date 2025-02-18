<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');
include 'Controleur.php';

$c=new Controleur($dbh);
if (strlen($_SESSION['sid']==0)) {
 header('location:logout.php');
} 
if(isset($_GET['fact'])) {
    $id = intval($_GET['id']);
    $stmt = $dbh->prepare("UPDATE demande_prix_complex SET deviis = 0, bcmd = 0, facture = 1, jour_delai=NOW() WHERE id_demande = :id");
    $stmt->execute(['id' => $id]);

  }
if(isset($_GET['payer'])){
    $id = intval($_GET['payer']);
    $stmt = $dbh->prepare("UPDATE demande_prix_complex SET payer=1 WHERE id_demande = :id");
    $stmt->execute(['id' => $id]);
    header('location:facture_payé_clx.php');
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
<style>

</style>
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
      <!-- Content Header (Page header) -->
      <section class="content-header">
        
        <div class="container-fluid">
          <div class="row mb-2">
            <div class="col-sm-6">
              <h1>Détails budgets</h1>
            </div>
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="dash_achat_cmplx.php">Accueil</a></li>
                <li class="breadcrumb-item active">Gérer les budgets</li>
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
                  <button type="button" class="btn btn-sm btn-primary" onclick="expo()" id='btnexp' ><span style="color: #fff;"><i class="fas fa-download" ></i> Exporter</span>

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
                        <th>B.command</th>
                        <th>Facture</th>
                        <th>Montant</th>
                        <th>Date</th>
                        <th>Délai</th>
                        <th>Action</th>
                      </tr> 
                    </thead> 
                    <tbody>
                      <?php
$query = mysqli_query($con, "SELECT * FROM demande_prix_complex WHERE facture=1 AND payer=0 AND DATEDIFF(NOW(), jour_delai) >= 50 AND DATE_FORMAT(dated,'%Y-%m') >= DATE_FORMAT(DATE_SUB(NOW(), INTERVAL 12 MONTH),'%Y-%m') and idcomplex=(select idcomplex from complexe1 where id=$_SESSION[sid]) ");
if(isset($_POST['cht'])){
                        $query=mysqli_query($con,"SELECT * FROM demande_prix_complex WHERE facture=1 AND payer=0 AND DATEDIFF(NOW(), jour_delai) >= 50 AND idcomplex=(select idcomplex from complexe1 where id=$_SESSION[sid]) ");
                      }
                      if(isset($_POST['chercher'])){
                        $dd=$_POST['dd'];
                        $ff=$_POST['df'];
                        $query=mysqli_query($con,"SELECT * FROM demande_prix_complex WHERE facture=1 AND payer=0 AND DATEDIFF(NOW(), jour_delai) >= 50 AND dated between '$dd' AND '$ff' and idcomplex=(select idcomplex from complexe1 where id=$_SESSION[sid]) and deviis=0 and payer=0");    
                }
                      $cnt=1;
                      while($row=mysqli_fetch_assoc($query)){
                      if($row['facture']==0){
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
                          <td><?php echo htmlentities($row['id_demande']);?></td>
                          <td><?php 
                            $queryy=mysqli_query($con,"SELECT raison_sociale from fournisseur where idfour=$row[idfour]");
                            $roww=mysqli_fetch_assoc($queryy);
                            echo $roww['raison_sociale'];
                          ?></td>
                          <td>
                            <center><a href="viewPdf.php?besoin=<?php echo $row['besoin'] ?>" class="btn btn-success btn-xs w-75" target="_blank">Voir</a></center>
                          </td>
                          <td  <?php echo $style2; ?>>
    <span style="color: transparent;"><?php echo htmlentities($row['devis']); ?></span><span>B.commande</span>

    </td>

    <td <?php echo $style1; ?>>
    <span style="color: transparent;"><?php echo htmlentities($row['bcmd']); ?></span><a href="reste_payer_clx.php?id=<?php echo $row['id_demande']?>&fact=delete" onClick="return confirm('Etes-vous sûr que vous voulez Modifier?')" style="color: black;">Facture</a>
    </td>
                          <td><?php echo htmlentities($row['montant']);?></td>
                          <td><?php echo htmlentities($row['dated']);?></td>
                          <td><?php
                          if($row['facture']==1 and $row['payer']==0){
                            $date_value = new DateTime(date('Y-m-d')); // Current date
$jour_delai = new DateTime($row['jour_delai']); // Date from the database

// Calculate the difference
$interval = $date_value->diff($jour_delai);

$days_difference = $interval->days;

// Calculate the remaining days in the 60-day period
$res = 60 - $days_difference;


echo $res."jrs"; // This will output the remaining days in the 60-day period
                          } 
                          if($row['bcmd']==1){
                            echo "---";
                          }
                           ?></td>
                          <td><a class="btn w-100  btn-sm <?php if($row['payer']==1 || $row['facture']!=1){if($row['payer']==1){echo 'disabled btn-danger';}else{echo 'disabled btn-info';}}else{echo' btn-primary';} ?>" 
        onClick="return confirm('Etes-vous sûr que vous voulez payer cette facture?')" 
        href="reste_payer_clx.php?payer=<?php echo $row['id_demande']?>"><?php if($row['payer']==1){echo '<small>Facture payée</small> ';}else{if($row['facture']==1 and $row['payer']==0){echo '<small>Payer</small>';}else{if($row['payer']==0){echo '<small>Attend Validation</small>';}}} ?>
      </a></td>
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
      if(this.header().textContent=="Facture"){headers.push("Etat")}
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

         if (index !=0 && index !=2 && index !=4 && index !=8) {
           
          rowData.push(value);
        }

           
        
    });
    if(rowData[1]=='<span style="color: transparent;"></span><span>B.commande</span>')
    {
      rowData[1]="B.command";
    }
    else{
      rowData[1]="Facture non payée";
    }
    
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
    a.download = 'Notifications .xlsx';

    // Ajoutez le lien à la page et déclenchez le téléchargement
    document.body.appendChild(a);
    a.click();

    // Libérez l'URL de l'objet blob
    window.URL.revokeObjectURL(url);
});
}
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
