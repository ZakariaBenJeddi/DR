<?php

session_start();
try{
error_reporting(0);
include('includes/dbconnection.php');
if(isset($_POST['submit']))
{
  $sid=$_SESSION['edid'];
  $code=htmlspecialchars($_POST['code']);
  $nom=htmlspecialchars($_POST['nom']);
  $adress=htmlspecialchars($_POST['adress']);
  $email=htmlspecialchars(($_POST['email']));
  $tel=htmlspecialchars($_POST['tel']);
  $sql = "UPDATE etablissement SET code='$code',nomE='$nom', adress='$adress', email='$email', tel='$tel' WHERE ide=".$sid;
  $query = $dbh->prepare($sql);
  $query->execute();
  if ($query->execute()) {
    echo "<script>alert('mise à jour réussie.');</script>";
    echo "<script>window.location.href ='list_etablissement.php'</script>";
  }else{
    echo "<script>alert('Quelque chose c'est mal passé. Merci d'essayer plus tard');</script>";
  }
}



if(isset($_POST['save']))
{
    $sid=$_SESSION['edid'];
    $img=$_FILES['img']['name'];
    $emp='etablissement_img/';
    $chemin=$emp.basename($_FILES['img']['name']);
    $sql="UPDATE etablissement SET imgetablissement='$img' WHERE ide='$sid'";
    $query = $dbh->prepare($sql);
    $query->execute();
    if ($query->execute()) {
      if(file_exists($chemin)){
      }
      else{
          move_uploaded_file($_FILES['img']['tmp_name'],$chemin);
      }
      echo "<script>alert('mise à jour réussie.');</script>";
      echo "<script>window.location.href ='list_etablissement.php'</script>";
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
       $ret=mysqli_query($con,"select * from etablissement where ide=".$eid);
       $cnt=1;
       while ($row=mysqli_fetch_array($ret))
       {
         $_SESSION['edid']=$row['ide'];
         ?>
         <div class="col-md-3">
           <!-- Profile Image -->
           <div class="card card-primary card-outline">
            <div class="card-body box-profile">
              <div class="text-center">
                <img class="img-circle"
                src="etablissement_img/<?php echo $row['imgetablissement'] ?>" width="150" height="150" class="user-image"
                alt="User profile picture">
              </div>

              <h3 class="profile-username text-center"><?php  echo $row['code'];?> </h3>



              <p class="text-muted text-center"><strong></strong></p>

              <ul class="list-group list-group-unbordered mb-3">
                <li class="list-group-item">
                  <b>Code</b> <a class="float-right"><?php  echo $row['code'];?></a>
                </li>
                <li class="list-group-item">
                  <b>Tel</b> <a class="float-right"><?php  echo $row['tel'];?> </a>
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
                <li class="nav-item"><a class="nav-link active" href="#for_info" data-toggle="tab">Détail de l'etablissement'</a></li>
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
                        <label for="code">Code</label>
                        <input  class="form-control" name="code" id="code" value="<?php  echo $row['code'];?>" required>
                      </div>        
                    </div>
                      <div class="col-md-4">
                        <div class="form-group">
                         <label for="nom">Nom</label>
                         <input  class="form-control" name="nom" id="nom" value="<?php  echo $row['nomE'];?>" required>
                       </div>
                           
                    </div>
                    <div class="col-md-4">
                      <div class="form-group">
                        <label>Adress</label>
                        <input class="form-control" name="adress" value="<?php  echo $row['adress'];?>" required>
                      </div>  
                       <!-- /.form-group -->
                     </div>
                    
                    <!-- /.col -->
                  </div><!-- ./row -->

                  <div class="row">
                   
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
                  <input type="file" class="" name="img" value="" required>
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