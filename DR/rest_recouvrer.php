<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');
include 'Controleur.php';
$c=new Controleur($dbh);
    if (strlen($_SESSION['sid']==0)) {
    header('location:logout.php');
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
          <form method="post">
              <div class="row"> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <div class="form-group col-md-3">
                  <label for="names">Selectionner la Date de Debut</label>
                  <input type="date" class="form-control" id="dd" name="dd" placeholder="Nom" required>
                </div>
                <div class="form-group col-md-3">
                  <label for="prenom">Selectionner la Date De Fin</label>
                  <input type="date" class="form-control" id="df" name="df" placeholder="Prenom" required>
                </div>
                <!-- <button type="button" class="btn btn-sm btn-primary" onclick="expo()" id='btnexp'><span style="color: #fff;"><i class="fas fa-download" ></i> Exporter</span> -->
                <div class="form-group col-md-3">
                  <button type="button" style="margin-top: 2.2rem !important;"  class="btn btn-sm btn-primary" onclick="expo()" id='btnexp'><span style="color: #fff;">
                    <i class="fas fa-download"></i>&nbsp; Exporter</span>
                  </button>
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
                        <th>Date</th>
                        <th>Prix</th>
                      </tr> 
                    </thead> 
                    <tbody>
                      <?php
                        $t=0;
                        $f1=0;
                        $s=0;
                        $query=mysqli_query($con,"SELECT * , ste.raison_sociale AS raison_sociale  FROM fact INNER JOIN ste ON fact.ids = ste.ids WHERE  payer=0 AND DATE_FORMAT(datef,'%Y-%m') >= DATE_FORMAT(DATE_SUB(NOW(), INTERVAL 1 MONTH),'%Y-%m')");
                        $query1=mysqli_query($con,"SELECT SUM(prix) as total  FROM fact WHERE payer=0 AND DATE_FORMAT(datef,'%Y-%m') >= DATE_FORMAT(DATE_SUB(NOW(), INTERVAL 1 MONTH),'%Y-%m')");
                        if(isset($_POST['chercher'])){
                          $dd=$_POST['dd'];
                          $ff=$_POST['df'];
                          $query=mysqli_query($con,"SELECT * , ste.raison_sociale AS raison_sociale  FROM fact INNER JOIN ste ON fact.ids = ste.ids WHERE payer=0 AND datef between '$dd' and '$ff'"); 
                          $query1=mysqli_query($con,"SELECT SUM(prix) as total  FROM fact WHERE payer=0 AND datef between '$dd' and '$ff'"); 
                        }
                        if(isset($_POST['cht'])) {
                          $query=mysqli_query($con,"SELECT * , ste.raison_sociale AS raison_sociale  FROM fact INNER JOIN ste ON fact.ids = ste.ids WHERE payer=0"); 
                          $query1=mysqli_query($con,"SELECT SUM(prix) as total  FROM fact WHERE payer=0");
                        }
                      $d='';$b='';$fa='';
                      while($row=mysqli_fetch_array($query)) { ?>
                        <tr>
                          <td><?php echo htmlentities($row['iddevis']);?></td>
                          <td><?php echo htmlentities($row['raison_sociale']); ?></td>
                          <td><?php echo htmlentities($row['nature']); ?></td>
                          <td><?php echo htmlentities($row['datef']); ?></td>
                          <td><?php echo htmlentities($row['prix']);?></td>
                          <input type="hidden" name="id" value="<?php echo htmlentities($row['iddevis']); ?>">
                              <?php $cnt=$cnt+1;
                            } ?>
                        </tr>
                    </tbody>
                    <?php
                          $row1 = mysqli_fetch_array($query1);
                          if (htmlentities($row1['total']) !='') {
                        ?>
                          <tr>
                            <td colspan="3"></td>
                            <td class="text-info">Total</td>
                            <td class="text-info">
                              <?php
                                $hp = htmlentities($row1['total']);
                                echo htmlentities($row1['total']);
                              ?>
                            </td>
                          </tr>
                        <?php } ?>
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
            
          
             if (index !=0 && index !=5 ) {
               
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
        a.download = 'Rest a recouvrer.xlsx';

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

