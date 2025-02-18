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
  mysqli_query($con,"delete from formation where idf = '".$_GET['id']."'");
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
  #btnf{
margin-top: 30px;
margin-left: 270px;

}

#btnexp{
margin-top: 30px;
margin-left: -200px;
height: 30px;
width: 100px;
}


}

/* Style pour les écrans mobiles */
@media (max-width: 700px) {
  #btnf{
    margin-top: 33px;
margin-left: 30px;


}

#btnexp{
margin-top: -61px;
margin-left: 250px;
height: 32px;
width: 100px;
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
              <h1>Détails formations</h1>
            </div>
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="dash.php">Accueil</a></li>
                <li class="breadcrumb-item active">Gérer les formations</li>
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
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                          <div class="form-group col-md-3">
                            <label for="names">Selectionner la Date de Debut</label>
                            <input type="date" class="form-control" id="dd" name="dd" placeholder="Nom" required>
                          </div>
                          <div class="form-group col-md-3">
                            <label for="prenom">Selectionner la Date De Fin</label>
                            <input type="date" class="form-control" id="df" name="df" placeholder="Prenom" required>
                          </div>
                          <button  type="submit" name="chercher" style="border: none; outline: none; width: 100px; height: 95px;background-color: transparent;color: blue;"><i class="fa fa-search" ></i> Chercher</button>

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
                          <a href="Add_formation.php" id='btnf'><button type="button" class="btn btn-sm btn-primary"  ><span style="color: #fff;"><i class="fas fa-plus" ></i>&nbsp;Formation</span>
                            </button>
                          </a> 
                          <button type="button" class="btn btn-sm btn-primary" onclick="expo()" id='btnexp'><span style="color: #fff;"><i class="fas fa-download" ></i> Exporter</span>
                        </button>					
                    </div>
                  </form> <!-- # -->
                </div>
                <!-- /.card-header -->
                <div id="editData" class="modal fade">
                  <div class="modal-dialog modal-xl">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h5 class="modal-title">Détails formations</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                        </button>
                      </div>
                      <div class="modal-body" id="info_update">
                        <?php 
                        @include("Edit_formation.php");
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
                        <h5 class="modal-title">Détails formations</h5>
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
                        <th>Société</th>
                        <th>Nature</th>
                        <th>Prix</th>
                        <th>Date</th>
                        <th>Duree</th>
                        <th>Action</th>
                      </tr> 
                    </thead> 
                    <tbody>
                      <?php
                      
                      
                      if(isset($_POST['chercher'])){
                        $dd = $_POST['dd'];
                        $ff = $_POST['df'];
                        $stmt = $con->prepare("SELECT * FROM formation WHERE datef BETWEEN ? AND ? ");
                        $stmt->bind_param("ss", $dd, $ff);
                        $stmt->execute();
                        $query = $stmt->get_result();
                  
                    } else { 
                        $stmt = $con->prepare("SELECT * FROM formation WHERE DATE_FORMAT(datef, '%Y-%m') >= DATE_FORMAT(DATE_SUB(NOW(), INTERVAL 1 MONTH), '%Y-%m')");
                        $stmt->execute();
                        $query = $stmt->get_result();
                    }
                      $cnt=1;
                      while($row=mysqli_fetch_array($query))
                      {
                          $nom=$c->chercher_ste_ids($row['ids']);

                        ?>                  
                        <tr>
                          <td><?php echo htmlentities($cnt);?></td>
                          <td><?php echo htmlentities($nom);?></td>
                          
                          <td><?php echo htmlentities($row['nature']);?></td>
                          <td><?php echo htmlentities($row['prix']);?></td>
                          <td><?php echo htmlentities($row['datef']);?></td>
                          <td><?php echo htmlentities($row['duree']);?></td>
                        
                          <td>
                           <center> <button  class=" btn btn-primary btn-xs edit_data" id="<?php echo  $row['idf']; ?>" title="click for edit">Modifier</i></button>&nbsp;&nbsp;
                            <a href="List_formation.php?id=<?php echo $row['idf']?>&del=delete" onClick="return confirm('Etes-vous sûr que vous voulez supprimer?')" class=" btn btn-danger btn-xs ">Supprimer</a>
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
         
         if(this.header().textContent !="#" && this.header().textContent !="Action" ){
        headers.push(this.header().textContent);
        }
        
    });
    
    
    data.push(headers);

    // Obtenez les données filtrées
    var filteredData = table.rows({ filter: 'applied' }).data();

    // Parcourez les données filtrées et ajoutez-les au tableau
    filteredData.each(function (valueArray) {
        var rowData = [];
        valueArray.forEach(function (value,index) {
            
          
             if (index !=0 && index !=6 ) {
               
                rowData.push(value);
               
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
        a.download = 'formations.xlsx';

        // Ajoutez le lien à la page et déclenchez le téléchargement
        document.body.appendChild(a);
        a.click();

        // Libérez l'URL de l'objet blob
        window.URL.revokeObjectURL(url);
    });
}
    $(document).ready(function(){
      $(document).on('click','.edit_data',function(){
        var edit_id=$(this).attr('id');
        $.ajax({
          url:"Edit_formation.php",
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
    $(document).ready(function(){
      $(document).on('click','.edit_data2',function(){
        var edit_id2=$(this).attr('id');
        $.ajax({
          url:"Info_ste.php",
          type:"post",
          data:{edit_id2:edit_id2},
          success:function(data){
            $("#info_update2").html(data);
            $("#editData2").modal('show');
          }
        });
      });
    });
      
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
