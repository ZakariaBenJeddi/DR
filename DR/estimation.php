
<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');
if (empty($_SESSION['sid'])) {
 header('location:logout.php');
} 
if(isset($_GET['del']))
{

  mysqli_query($con,"truncate table rnda");
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
              <h1>CALEDRIER</h1>
            </div>
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="#">Accueil</a></li>
                <li class="breadcrumb-item active">CALEDRIER</li>
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
                
              
                  <div class="card-tools">
                  <form method="post"> 
              <a href="aff1.php?del=delete" onClick="return confirm('Etes-vous sûr que vous voulez supprimer?')" class="btn btn-sm btn-primary ">Vider</a>
                    <button type="button" class="btn btn-sm btn-primary" onclick="expo()" ><span style="color: #fff;"><i class="fas fa-download" ></i> Exporter</span>
                    </button>
                    
 <button  type="submit" name="ACT" style="border: none; outline: none; width: 100px; height: 95px;background-color: transparent;color: blue;"><i class="fa fa-search" ></i> Chercher</button>
                  </div>
                
                  <div class="form-group col-md-7">
                          <input type="date" class="form-control" id="dd" name="dd" placeholder="Nom" required>
                          <input type="date" class="form-control" id="df" name="df" placeholder="Prenom" required>
</div>
        </div>
                <!-- /.card-header -->
                <div id="editData" class="modal fade">
                  <div class="modal-dialog modal-xl">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h5 class="modal-title">CALEDRIER</h5>
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
                        <h5 class="modal-title">TCD-AP</h5>
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


                <div class="card-body mt-2 ">
                    <div class="table-responsive">
                  <table id="example1" class="table table-bordered table-hover" >
                    <thead> 
                      <tr> 
                        <th>#</th>
                        <th>Secteur</th>
                        <th>Code Filière</th>
                        <th>Niveau</th>
                        <th>Module</th>
						<th>Creneau</th>
						<th>Annee</th>
                        <th>Type Examen</th>
						<th>Type Epreuve</th>
                        <th>Total variante</th>
						<th>Nbre/séance</th>
						<th>Nbre/jours</th>
            <th>Duree</th>
            <th>Date</th>
            <th>Horaire</th>
                         </tr> 
                    </thead> 
                    <tbody>
                      <?php 
          if(isset($_POST['ACT']))
          {   

         $horaire="";
       $query=mysqli_query($con,"SELECT * FROM rnd");
                      $cnt=1;
                      while($row=mysqli_fetch_array($query))
                      {
                        $sh=0; 
                        $snbj=0;       
                        $dd=$_POST['dd'];
                        $ff=$_POST['df'];        
                        $dateDebut = new DateTime($dd);
          
                        // Date de fin
                        $dateFin = new DateTime($ff);
                        
                        // Générer une date aléatoire entre la date de début et la date de fin
                        $interval = $dateDebut->diff($dateFin);
                        $joursTotals = $interval->days;
                        $joursAleatoires = rand(0, $joursTotals);
                        
                        $dateAleatoire = $dateDebut->add(new DateInterval('P' . $joursAleatoires . 'D'));

                 $d=0;
                 $ss=0;
                 $cpt=0;
                 for($k=0;$k<$row['nbrj'];$k++){
         $ss=($row['nbrs']/$row['nbrj']);
       
			for( $i=0;$i<$ss;$i++){
          $cpt=$cpt+1;
                if($row['duree']==4  and $row['nbrj']==1){
                  if($i%2==0){
                $horaire="08h30-12h30";}
                else{
                  $horaire="13h30-17h30";

                }
              
              }

              if($row['duree']==4  and $row['nbrj']==2){
                
                if($i%2==0){
                  $horaire="08h30-12h30";}
                  else{
                    $horaire="13h30-17h30";
    
                  }
             
            
            }


              if($row['duree']==5 and $row['nbrj']==1){
                if($i%2==0){
              $horaire="08h30-13h30";}
              else{
                $horaire="13h30-18h30";

              }

            }


            if($row['duree']==5 and $row['nbrj']==2){
              if($i%2==0){
            $horaire="08h30-13h30";
              }else{
   
                $horaire="13h30-18h30";
              }

          }



            
        if($row['duree']==2.5){
          if($i<2){
                if($i%2==0){
                
              $horaire="08h30-11h00";}
              else{
                $horaire="11h00-13h30";

              }
            
            }else{

              if($i%2==0){
                
                $horaire="13h30-16h00";}
                else{
                  $horaire="16h00-18h30";
  
                }



            }
          }
              

            



            if($row['duree']==3){
              if($i%2==0){
                $horaire="08h30-11h30";}
                else{
                  $horaire="13h30-16h30";

                }
            
            
            }
              
    

            
           $dd= $dateAleatoire->format('Y-m-d');
       if($cpt<=$row['nbrs']){

$stmt = $dbh->prepare("INSERT INTO rnda (sec, codef, niveau, module, creneau, annee, type_ex, type_ep, nbrv, nbrs, nbrj, duree, date_d, horaire)
 VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

                        // Exécutez la requête SQL avec les valeurs correspondantes
 $stmt->execute([$row['sec'], $row['codef'], $row['niveau'], $row['module'], $row['creneau'], $row['annee'], $row['type_ex'], $row['type_ep'], $row['nbrv'], $row['nbrs'], $row['nbrj'], $row['duree'], $dd,$horaire]);

       }
                        }


                        $dateAleatoire->add(new DateInterval('P1D'));
                      }
                    }



                    $query1=mysqli_query($con,"SELECT * FROM rnda");
                    $cnt1=1;
                    while($row1=mysqli_fetch_array($query1))
                    {
						    
                        ?>                  
                        <tr>
                          <td><?php echo htmlentities($cnt1);?></td>
                          <td class="align-middle"><?php echo htmlentities($row1['sec']);?></td>
                          <td><?php echo htmlentities($row1['codef']);?></td>
                          <td><?php echo htmlentities($row1['niveau']);?></td>
						  <td><?php echo htmlentities($row1['module']);?></td>
                           <td><?php echo htmlentities($row1['creneau']);?></td>
						   <td><?php echo htmlentities($row1['annee']);?></td>
						  <td><?php echo htmlentities($row1['type_ex']);?></td>
						  <td><?php echo htmlentities($row1['type_ep']);?></td>
							 <td><?php echo htmlentities($row1['nbrv']);?></td>
							  <td><?php echo htmlentities($row1['nbrs']);?></td>
							  <td><?php echo htmlentities($row1['nbrj']);?></td>
							   <td><?php echo htmlentities($row1['duree']);?></td>
                 <td><?php echo htmlentities($row1['date_d']);?></td>
                 <td><?php echo htmlentities($row1['horaire']);?></td>
                        </tr>
                        <?php $cnt1=$cnt1+1;
                      }} ?>
                    </tbody>
                  </table>
                    </form>
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
        a.download = 'Exporter_CALEDRIER.xlsx';

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
  