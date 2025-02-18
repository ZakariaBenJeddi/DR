
<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');
if (empty($_SESSION['sid'])) {
 header('location:logout.php');
} 
if(isset($_GET['del']))
{
   mysqli_query($con,"truncate table drh");
  $_SESSION['delmsg']="formateur supprimé  !!";

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
    <?php @include("includes/sidebar.php"); ?>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
      <!-- Content Header (Page header) -->
      <section class="content-header">
        <div class="container-fluid">
          <div class="row mb-2">
            <div class="col-sm-6">
              <h1>Avancement De Programme</h1>
            </div>
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="#">Accueil</a></li>
                <li class="breadcrumb-item active">Avancement De Programme</li>
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
                  <h3 class="card-title">Avancement De Programme</h3>
                  <div class="card-tools">
              <a href="aff.php?del=delete" onClick="return confirm('Etes-vous sûr que vous voulez supprimer?')" class="btn btn-sm btn-primary ">Vider</a>
                    <button type="button" class="btn btn-sm btn-primary" onclick="expo()" ><span style="color: #fff;"><i class="fas fa-download" ></i> Exporter</span>
                    </button>
                  </div>
                </div>
                <!-- /.card-header -->
                <div id="editData" class="modal fade">
                  <div class="modal-dialog modal-xl">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h5 class="modal-title">Avancement De Programme</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                        </button>
                      </div>
                      <div class="modal-body" id="info_update">
                        <?php @include("edit_student.php");?>
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
                        <h5 class="modal-title">Avancement De Programme</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                        </button>
                      </div>
                      <div class="modal-body" id="info_update2">
                        <?php @include("view_student_info.php");?>
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


                <div class="card-body mt-2 ">
                    <div class="table-responsive">
                  <table id="example1" class="table table-bordered table-hover" >
                    <thead> 
                      <tr> 
                        <th>#</th>
                        <th>Complexe</th>
                        <th>Code EFP</th>
                        <th>EFP</th>
						 <th>Niveau</th>
						<th>Annee</th>
                        <th>Creneau</th>
                        <th>Mode</th>
						<th>Code Filière</th>
						<th>Code Module</th>
						 <th>Formateur</th>
						 <th>Module</th>
						<th>type</th>
						<th>Groupe</th>
						<th>MHPT</th>
                        <th>MHST</th>
						<th>MHPTR</th>
						<th>MHSTR</th>
						<th>MHTTR</th>
						<th>MHRG</th>
                      </tr> 
                    </thead> 
                    <tbody>
                      <?php 
                      
                      
                      $query=mysqli_query($con,"select * from drh");
                      $cnt=1;
                      while($row=mysqli_fetch_array($query))
                      {
                        ?>                  
                        <tr>
                          <td><?php echo htmlentities($cnt);?></td>
                          <td class="align-middle"><?php echo htmlentities($row['Complexe']);?></td>
                          <td><?php echo htmlentities($row['CodeEFP']);?></td>
                          <td><?php echo htmlentities($row['EFP']);?></td>
						  <td><?php echo htmlentities($row['Niveau']);?></td>
						  <td><?php echo htmlentities($row['annee']);?></td>
                           <td><?php echo htmlentities($row['Creneau']);?></td>
						    <td><?php echo htmlentities($row['Mode']);?></td>
							 <td><?php echo htmlentities($row['codef']);?></td>
							 <td><?php echo htmlentities($row['CodeModule']);?></td>
							 <td><?php echo htmlentities($row['f']);?></td>
							 <td><?php echo htmlentities($row['module']);?></td>
							<td><?php echo htmlentities($row['type']);?></td>
							 <td><?php echo htmlentities($row['Groupe']);?></td>
							  <td><?php echo htmlentities($row['MHPT']);?></td>
							  <td><?php echo htmlentities($row['MHST']);?></td>
							  <td><?php echo htmlentities($row['MHPTR']);?></td>
							  <td><?php echo htmlentities($row['MHSTR']);?></td>
							  <td><?php echo htmlentities($row['MHTTR']);?></td>
							  <td><?php echo htmlentities($row['MHRG']);?></td>
                      
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
  <?php
  
              
                    
@include("includes/foot.php"); ?>
 <script src="https://cdnjs.cloudflare.com/ajax/libs/exceljs/4.3.0/exceljs.min.js"></script>

  <!-- ./wrapper -->
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
         if(this.header().textContent !="#" ){
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
            
           if (index !=0  ) {
               
                rowData.push(value);
               
            }
        });
        
        
         data.push(rowData);
    });

    // Créez un nouveau classeur Excel
    var workbook = new ExcelJS.Workbook();
    var worksheet = workbook.addWorksheet('AvancementProgramme');

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
        a.download = 'Exporter_AVC.xlsx';

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
                "sSortAscending": ": Trier par ordre croissant",
                "sSortDescending": ": Trier par ordre décroissant"
            }
        },
        "pageLength": 25 // Définir par défaut 25 lignes par page
});
});


    $(document).ready(function(){
      $(document).on('click','.edit_data',function(){
        var edit_id=$(this).attr('id');
        $.ajax({
          url:"edit_student.php",
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
          url:"view_student_info.php",
          type:"post",
          data:{edit_id2:edit_id2},
          success:function(data){
            $("#info_update2").html(data);
            $("#editData2").modal('show');
          }
        });
      });
    });

  </script>

</body>

</html>
