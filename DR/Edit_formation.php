<?php

session_start();
try{
error_reporting(0);
require_once ('includes/dbconnection.php');
require_once 'Controleur.php';
$c=new Controleur($dbh);
if(isset($_POST['submit']))
{ 
  $sid=$_SESSION['edid']; 
  $na=htmlspecialchars($_POST['na']);
  $p=htmlspecialchars($_POST['p']);
  $d=htmlspecialchars($_POST['d']);
  $datef=htmlspecialchars(($_POST['datef']));




  
  $sql = "UPDATE formation SET nature='$na', prix='$p', datef='$datef', duree='$d' WHERE idf=".$sid;

  $query = $dbh->prepare($sql);
  $query->execute();
  if ($query->execute()) {
    echo "<script>alert('mise à jour réussie.');</script>";
    echo "<script>window.location.href ='list_formation.php'</script>";
  }else{
    echo "<script>alert('Quelque chose c'est mal passé. Merci d'essayer plus tard');</script>";
  }
}



?>


<!-- Content Wrapper. Contains page content -->
<div class="card-body">
  <!-- Main content -->
  <section class="content">
    <div class="container-fluid">
      <div class="row">
       <?php
       $eid=intval($_POST['edit_id']);
       $ret=mysqli_query($con,"select * from formation where idf={$eid}");
       $cnt=1;
       while ($row=mysqli_fetch_array($ret))
       {
         $_SESSION['edid']=$row['idf']; 
          $ns=$c->cherchertt_ste_ids($row['ids']);
          


         ?>
         <div class="col-md-3">
           <!-- Profile Image -->
           <div class="card card-primary card-outline">
            <div class="card-body box-profile">
              <div class="text-center">
                <img class="img-circle"
                src="ste_img/ste1.jpg" width="150" height="150" class="user-image"
                alt="User profile picture">
              </div>

              <h3 class="profile-username text-center"><?php  echo $ns[1];?> </h3>



              <p class="text-muted text-center"><strong></strong></p>

              <ul class="list-group list-group-unbordered mb-3">
                <li class="list-group-item">
                  <b>Email</b> <a class="float-right"><?php  echo $ns[5];?></a>
                </li>
                <li class="list-group-item">
                  <b>Fix</b> <a class="float-right"><?php  echo $ns[7];?> </a>
                </li>
                
              </ul>

            </div>
            <!-- /.card-body -->
          </div>
          <!-- /.card -->
        </div>
        <!-- /.col -->
        <div class="col-md-9">
          <div class="card">
            <div class="card-header p-2">
              <ul class="nav nav-pills">
                <li class="nav-item"><a class="nav-link active" href="#for_info" data-toggle="tab">Détail de la Formation</a></li>
              </ul>
            </div><!-- /.card-header -->
            <div class="card-body">
              <div class="tab-content">
                <div class="active tab-pane" id="for_info">
                  <form role="form" id=""  method="post" enctype="multipart/form-data" class="form-horizontal" >
                    <div class="row">
                    <div class="col-md-4">
                      <div class="form-group">
                        <label for="cin">Formation</label>
                        <input  class="form-control" name="na" id="cin" value="<?php  echo $row['nature'];?>" required>
                      </div>        
                    </div>
                      <div class="col-md-4">
                        <div class="form-group">
                         <label for="companyname">Prix</label>
                         <input  class="form-control" name="p" id="p" value="<?php  echo $row['prix'];?>" required>
                       </div>
                           
                    </div>
                    <div class="col-md-4">
                      <div class="form-group">
                        <label>Date</label>
                        <input type="date"class="form-control" name="datef" value="<?php  echo $row['datef'];?>" required>
                      </div>  
                       <!-- /.form-group -->
                     </div>
                    
                    <!-- /.col -->
                  </div><!-- ./row -->

                  <div class="row">
                   
                    <div class="col-md-4">
                      <div class="form-group">
                        <label>Duree</label>
                        <input class="form-control" name="d" value="<?php  echo $row['duree'];?>" required>
                      </div>        
                    </div>

                  
            
               
                  
                 
                    <!-- /.col -->
                   
                    <!-- /.col --> 
                  </div>
                  <!-- /.card-body -->
                  <div class="modal-footer text-right">
                    <button type="submit" name="submit" class="btn btn-primary">Mettre à jour</button>
                  </div>
                </form>
              </div>
         
          
            <div class=" tab-pane" id="img_ch">
             <div class="row">
              <form role="form" id=""  method="post" enctype="multipart/form-data" class="form-horizontal" >
                <div class="form-group">
                  <label>Télécharger une image</label>
                  <input type="file" class="" name="formateurs_img" value="" required>
                </div>  

                <div class="modal-footer text-right">
                  <button type="submit" name="save" class="btn btn-primary">Mettre à jour</button>
                
                </div>

              </form>
             
            </div>
          
          </div>

          <?php  
        }?>
      </div>
      <!-- /.tab-content -->
    </div><!-- /.card-body -->
  </section>
  <!-- /.content -->
</div>
  <!-- /.content-wrapper -->


  <?php
  
    }catch (Exception $e) {
    // Code to handle the exception
    echo "error: " . $e->getMessage();
}
  ?>