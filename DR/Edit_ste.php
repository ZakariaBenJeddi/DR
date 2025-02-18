<?php

session_start();
try{
error_reporting(0);
include('includes/dbconnection.php');
if(isset($_POST['submit']))
{ 
  $sid=$_SESSION['edid']; 
  $no=htmlspecialchars($_POST['no']);
  $email=htmlspecialchars($_POST['email']);
  $adresse=htmlspecialchars($_POST['adresse']);
  $tel=htmlspecialchars(($_POST['tel']));
  $sec=htmlspecialchars($_POST['sec']);
  $ger=htmlspecialchars(($_POST['ger']));
  $fix=htmlspecialchars(($_POST['fix']));



  
 $sql = "UPDATE ste SET raison_sociale='$no',gerent='$ger', secteur='$sec', fix='$fix', tel='$tel', adresse='$adresse', email='$email' WHERE ids=".$sid;
  $query = $dbh->prepare($sql);
  $query->execute();
  if ($query->execute()) {
    echo "<script>alert('mise à jour réussie.');</script>";
    echo "<script>window.location.href ='list_ste.php'</script>";
  }else{
    echo "<script>alert('Quelque chose c'est mal passé. Merci d'essayer plus tard');</script>";
  }
}



if(isset($_POST['save']))
{
  $sid=$_SESSION['edid'];
  $formateurs_img=$_FILES["formateurs_img"]["name"];
  
  $img_f =mysqli_query($con,"select image from formateurs where id_form=".$sid);
  while ($i=mysqli_fetch_array($img_f)){
    if (file_exists("formateurs_img/".$i['image'])) {
        unlink("formateurs_img/".$i['image']);
    }
  }

  move_uploaded_file($_FILES["formateurs_img"]["tmp_name"],"formateurs_img/".$_FILES["formateurs_img"]["name"]);
  $sql="update formateurs set image=:formateurs_img where id_form=".$sid;
  $query = $dbh->prepare($sql);
  $query->bindParam(':formateurs_img',$formateurs_img,PDO::PARAM_STR);
  $query->execute();
  if ($query->execute()) {
    echo "<script>alert('mise à jour réussie.');</script>";
    echo "<script>window.location.href ='formateur_list.php'</script>";
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
       $ret=mysqli_query($con,"select * from ste where ids=".$eid);
       $cnt=1;
       while ($row=mysqli_fetch_array($ret))
       {
         $_SESSION['edid']=$row['ids']; 
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

              <h3 class="profile-username text-center"><?php  echo $row['raison_sociale'];?> </h3>



              <p class="text-muted text-center"><strong></strong></p>

              <ul class="list-group list-group-unbordered mb-3">
                <li class="list-group-item">
                  <b>Email</b> <a class="float-right"><?php  echo $row['email'];?></a>
                </li>
                <li class="list-group-item">
                  <b>Fix</b> <a class="float-right"><?php  echo $row['fix'];?> </a>
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
                <li class="nav-item"><a class="nav-link active" href="#for_info" data-toggle="tab">Détail de la société</a></li>
                 <li class="nav-item"><a class="nav-link" href="#img_ch" data-toggle="tab">Mettre à jour l'image</a></li>
              </ul>
            </div><!-- /.card-header -->
            <div class="card-body">
              <div class="tab-content">
                <div class="active tab-pane" id="for_info">
                  <form role="form" id=""  method="post" enctype="multipart/form-data" class="form-horizontal" >
                    <div class="row">
                    <div class="col-md-4">
                      <div class="form-group">
                        <label for="cin">Nom</label>
                        <input  class="form-control" name="no" id="cin" value="<?php  echo $row['raison_sociale'];?>" required>
                      </div>        
                    </div>
                      <div class="col-md-4">
                        <div class="form-group">
                         <label for="companyname">Secteur</label>
                         <input  class="form-control" name="sec" id="nom" value="<?php  echo $row['secteur'];?>" required>
                       </div>
                           
                    </div>
                    <div class="col-md-4">
                      <div class="form-group">
                        <label>Gerent</label>
                        <input class="form-control" name="ger" value="<?php  echo $row['gerent'];?>" required>
                      </div>  
                       <!-- /.form-group -->
                     </div>
                    
                    <!-- /.col -->
                  </div><!-- ./row -->

                  <div class="row">
                   
                    <div class="col-md-4">
                      <div class="form-group">
                        <label>Adresse</label>
                        <input class="form-control" name="adresse" value="<?php  echo $row['adresse'];?>" required>
                      </div>        
                    </div>

                    <div class="col-md-4">
                      <div class="form-group">
                        <label>Email</label>
                        <input class="form-control" name="email" value="<?php  echo $row['email'];?>" required>
                      </div>        
                    </div>
                    <div class="col-md-4">
                      <div class="form-group">
                        <label>tel</label>
                        <input class="form-control" name="tel" value="<?php  echo $row['tel'];?>" required>
                      </div>        
                    </div>
            
               
                    
                     
                       <div class="form-group col-md-3">
                          <label for="sf">Fix</label>
                          <input type="text" class="form-control" id="sf" name="fix" placeholder="Fix" value="<?php  echo $row['fix'];?>" >
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