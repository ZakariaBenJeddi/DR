<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');
if (strlen($_SESSION['sid']==0)) {
 header('location:logout.php');
}

if(isset($_GET['dev'])) {
  $id = intval($_GET['id']);
  $stmt = $dbh->prepare("UPDATE rest_payer SET devis = 1, bcmd = 0, facture = 0 WHERE id_d = :id");
  $stmt->execute(['id' => $id]);
}

if(isset($_GET['bon'])) {
  $id = intval($_GET['id']);
  $stmt = $dbh->prepare("UPDATE rest_payer SET devis = 0, bcmd = 1, facture = 0 WHERE id_d = :id");
  $stmt->execute(['id' => $id]);
}

if(isset($_GET['fac'])) {
  $id = intval($_GET['id']);
  $stmt = $dbh->prepare("UPDATE rest_payer SET devis = 0, bcmd = 0, facture = 1 WHERE id_d = :id");
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

<!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
      <!-- Content Header (Page header) -->
      <section class="content-header">
        <div class="container-fluid">
          <div class="row mb-2">
            <div class="col-sm-6">
              <h1 class="text-danger">Veuillez payer vos factures dès que possible.</h1>
            </div>
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="dash_dr.php">Accueil</a></li>
                <li class="breadcrumb-item active">Gérer les Demande de prix</li>
              </ol>
            </div>
          </div>
        </div><!-- /.container-fluid -->
      </section>
      <div class="card-header">
                <form method="post">
                    <div class="row">
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <div class="form-group col-md-3">
                            <label for="names">Selectionner la Date de Debut</label>
                            <input type="date" class="form-control" id="dd" name="dd" placeholder="Nom" required>
                        </div>
                        <div class="form-group col-md-3">
                            <label for="prenom">Selectionner la Date De Fin</label>
                            <input type="date" class="form-control" id="df" name="df" placeholder="Prenom" required>
                        </div>
                    <!-- </div> -->
                        <div class="form-group col-md-3 mt-3">
                            <button style="color:blue" class="btn mt-3" type="submit" name="chercher" id="ch"><i class="fa fa-search"></i> Chercher</button>
                            <button style="color:blue" class="btn mt-3" type="submit" name="cht" id="ch1"><i class="fa fa-search"></i> afficher tous</button>
                        </div>

                    </div>
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
                </form>
            </div>
      <!-- Main content -->
      <section class="content">
        <div class="container-fluid">
          <div class="row">
            <div class="col-12">
              <div class="card">
                <div class="card-header">
                  <!-- <h3 class="card-title">Gérer les Factures payer</h3> -->
                  <h3 class="card-title"></h3>
                  <div class="card-tools">
                      <button type="button" class="btn btn-sm btn-primary" onclick="expo()" id='btnexp'><span style="color: #fff;"><i class="fas fa-download" ></i> Exporter</span>
                      </button>		
                  </div>
                  </div>
                <!-- /.card-header -->
                <div id="editData" class="modal fade">
                  <div class="modal-dialog modal-xl">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h5 class="modal-title">Détails Demande de prix</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                        </button>
                      </div>
                      <div class="modal-body" id="info_update">
                        <?php
                        @include("Edit_demande_prix.php");
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
                        <h5 class="modal-title">Détails Demande de prix</h5>
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
                        <th>Fornisseur</th>
                        <th>Montant</th>
                        <th>B.commande</th>
                        <th>Facture</th>
                        <th>Date</th>
                        <th>Besoin</th>
                        <th>Payment</th>
                        <th>Delai</th>
                      </tr>
                    </thead> 
                    <tbody>
                        
                      <?php $query=mysqli_query($con,"SELECT rest_payer.*, DATEDIFF(date_delai,CURDATE()) AS nombre_jours_delai, fournisseur.raison_sociale FROM rest_payer INNER JOIN fournisseur ON fournisseur.idfour = rest_payer.fornis WHERE facture=1 AND DATEDIFF(date_delai,CURDATE()) <= 10 AND DATE_FORMAT(date,'%Y-%m') >= DATE_FORMAT(DATE_SUB(NOW(), INTERVAL 1 MONTH),'%Y-%m') ;");
                      if (isset($_POST['chercher'])) {
                        $dd = $_POST['dd'];
                        $ff = $_POST['df'];
                        $query = mysqli_query($con, "SELECT rest_payer.*, DATEDIFF(date_delai,CURDATE()) AS nombre_jours_delai , fournisseur.raison_sociale FROM rest_payer INNER JOIN fournisseur ON fournisseur.idfour = rest_payer.fornis  and date between '$dd' and '$ff' WHERE facture=1 AND DATEDIFF(date_delai,CURDATE()) <= 10 ; ");
                      }
                      if (isset($_POST['cht'])) {
                          $query = mysqli_query($con, "SELECT rest_payer.*, DATEDIFF(date_delai,CURDATE()) AS nombre_jours_delai , fournisseur.raison_sociale FROM rest_payer INNER JOIN fournisseur ON fournisseur.idfour = rest_payer.fornis WHERE facture=1 AND DATEDIFF(date_delai,CURDATE()) <= 10 ;");
                      }
                      
                      $cnt=1;
                    if(isset($_GET['payer'])){
                        $req="UPDATE rest_payer SET payer = 1 WHERE id_d='$_GET[payer]'";
                        $query=mysqli_query($con,$req);
                        echo "<script>window.location.href = 'facture_payer.php'</script>";
                        
                    }
                    $delai='';
                    if (isset($_GET['factt'])) {
                        $rec = $dbh->query("SELECT * FROM rest_payer WHERE id_d = '$_GET[factt]'");
                        $payer = 0; // Initialiser la variable payer
                        if ($rec) {
                            $result = $rec->fetch(PDO::FETCH_ASSOC);
                            if ($result) {
                                $payer = $result['payer'];
                            }
                        }
                        if ($p == 0) {
                            $req="UPDATE rest_payer SET date_facture = CURDATE(), date_delai = CURDATE()+ INTERVAL 60 DAY WHERE id_d='$_GET[factt]'";
                            $dbh->query($req);
                        }
                        // echo "<script>window.location.href = 'list_rest_payer.php'</script>";
                    }
                      while($row=mysqli_fetch_array($query))
                      {
                        if($row['devis']==0){
                          $style1="style=background-color:red";
                        } else{
                          $style1="style=background-color:green";
                        }  
                        if($row['facture']==0){
                          $style3="style=background-color:red";
                            if ($row['payer']==0) {
                                $style4="style=background-color:#ced4da";
                            }else{
                                $style4="style=background-color:white";
                            }
                        } else{
                            $style3="style=background-color:green";
                            $style4="style=background-color:#ced4da";
                        } 
                        if($row['bcmd']==0){
                          $style2="style=background-color:red";
                        } else{
                          $style2="style=background-color:green";
                        } 
                        ?>
                        <tr>
                          <td><?php echo htmlentities($cnt);?></td>
                          <td><?php echo htmlentities($row['raison_sociale']);?></td>
                          <td><?php echo htmlentities($row['montant']);?></td>

<td <?php echo $style2; ?>>
<span style="color: transparent;"><?php echo htmlentities($row['bcmd']); ?></span>
<a href="list_rest_payer.php?id=<?php echo $row['id_d']?>&bon=delete" onClick="return confirm('Etes-vous sûr que vous voulez Modifier?')" style="color: black;">
B.commande</a>
</td>

<td  <?php echo $style3; ?>>
<span style="color: transparent;"><?php echo htmlentities($row['facture']); ?></span>
<a href="list_rest_payer.php?id=<?php echo $row['id_d']?>&factt=<?php echo $row['id_d']?>&fac=delete" onClick="return confirm('Etes-vous sûr que vous voulez Modifier?') && appendDateToLink()" style="color: black;">
Facture</a>
</td>



                          <td><?php echo htmlentities($row['date']);?></td>
                          <td>
                            <center>
                              <a href="telecharger_demande.php?path=<?php echo htmlentities($row['besoin']);?>"><button class="btn btn-warning text-light btn-xs text-center">Telecharger</button></a>
                            </center>
                          </td>
<form action="" method="post">
<td><a class="btn w-100  btn-sm <?php if($row['payer']==1 || $row['facture']!=1){if($row['payer']==1){echo 'disabled btn-danger';}else{echo 'disabled btn-info';}}else{echo' btn-primary';} ?>" 
onClick="return confirm('Etes-vous sûr que vous voulez payer cette facture?')" id="factureLink" name='pp'
href="list_rest_payer.php?payer=<?php echo $row['id_d']?>"><?php if($row['payer']==1){echo '<small>Facture payée</small> ';}else{if($row['facture']==1 and $row['payer']==0){echo '<small>Payer</small>';}else{if($row['payer']==0){echo '<small>Attend Validation</small>';}}} ?>
</a>
</td>
</form>
<?php
    if (is_null($row['nombre_jours_delai']) && $row['payer']==0) { ?>
        <td <?php echo $style4;?>>
        </td>
    <?php } else{ ?>
        <td class="text-danger">
            <?php echo $row['nombre_jours_delai']." jrs";?>
        </td>
    <?php }
?>
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
    function expo() {
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
            if (this.header().textContent !="#" && this.header().textContent !="Besoin" && this.header().textContent !="Payment") {
                headers.push(this.header().textContent);
            }
        });
        data.push(headers);
        // Obtenez les données filtrées
        var filteredData = table.rows({ filter: 'applied' }).data();
        // Parcourez les données filtrées et ajoutez-les au tableau
        filteredData.each(function (valueArray) {
            var rowData = [];
            valueArray.forEach(function (value, index) {
                if (index !== 0 && index !== 6 && index !== 7) {
                    var cleanedValue;
                    // Vérifiez si la cellule contient une balise <a>
                    if (/<a[^>]*>([^<]+)<\/a>/.test(value)) {
                      // Extraire le texte de la balise <a>
                      cleanedValue = $(value).text();
                    } else {
                      cleanedValue = value;
                    }
                    // Supprimer "Facture" et "B.commande" des valeurs
                    cleanedValue = cleanedValue.replace("B.commande", "").replace("Facture", "").trim();
                    rowData.push(cleanedValue);
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
            a.download = 'Notification.xlsx';
            // Ajoutez le lien à la page et déclenchez le téléchargement
            document.body.appendChild(a);
            a.click();

            // Libérez l'URL de l'objet blob
            window.URL.revokeObjectURL(url);
        });
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
  }
});
});
  </script>
</body>
</html>