<?php

session_start();
try{
error_reporting(0);
include('includes/dbconnection.php');
include 'Controleur.php';
$c=new Controleur($dbh);
if(isset($_POST['submit']))
{ 
    $sid=$_SESSION['edid'];
    $fornisseur=htmlspecialchars($_POST['fornisseur']);
    $montant=htmlspecialchars(($_POST['montant']));
    $date=htmlspecialchars($_POST['date']);

    // $query = $dbh->exec("UPDATE demande_prix SET fornis='$fornisseur' , montant = '$montant' , date='$date' WHERE id_d = '$sid'" );
    $query = $dbh->exec("UPDATE demande_prix SET idfour='$fornisseur' , montant = '$montant' , date='$date' WHERE id_d = '$sid'" );
    $query1 = $dbh->exec("UPDATE rest_payer SET fornis='$fornisseur' , montant = '$montant' , date='$date' WHERE id_d = '$sid'" );
    $query2 = $dbh->exec("UPDATE facture_payer SET idfour='$fornisseur' , montant = '$montant' WHERE id_f = '$sid' " );
    if ($query && $query1 && $query1) {
    echo "<script>alert('mise à jour réussie.');</script>";
    echo "<script>window.location.href ='list_demande_prix.php'</script>";
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

if(isset($_POST['save_besoin'])){
  $sid=$_SESSION['edid'];
  $img=$_FILES['besoin']['name'];
  $emp='besoin/';
  $chemin=$emp.basename($_FILES['besoin']['name']);
  $sql="UPDATE demande_prix SET besoin='$img' WHERE id_d='$sid'";
  $query2 = $dbh->exec("UPDATE rest_payer SET besoin='$img' WHERE id_d = '$sid' " );
  $query3 = $dbh->exec("UPDATE facture_payer SET besoin='$img' WHERE id_f = '$sid' " );
  $query = $dbh->prepare($sql);
  $query2 = $dbh->prepare($sql);
  $query3 = $dbh->prepare($sql);
  $query->execute();
  if ($query->execute() && $query2->execute() && $query3->execute()) {
    if(file_exists($chemin)){
    }
    else{
        move_uploaded_file($_FILES['besoin']['tmp_name'],$chemin);
    }
    echo "<script>alert('mise à jour réussie.');</script>";
    echo "<script>window.location.href ='list_demande_prix.php'</script>";
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
      //  $ret=mysqli_query($con,"SELECT demande_prix.*, fornisseur.raison_sociale FROM demande_prix INNER JOIN fornisseur ON fornisseur.idfour = demande_prix.fornis where id_d=".$eid);
       $ret=mysqli_query($con,"SELECT demande_prix.*, fournisseur.raison_sociale FROM demande_prix INNER JOIN fournisseur ON fournisseur.idfour = demande_prix.idfour where id_d=".$eid);
       $cnt=1;
       while ($row=mysqli_fetch_array($ret))
       {
         $_SESSION['edid']=$row['id_d'];
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
                  <b>Fornissseur</b> <a class="float-right"><?php  echo $row['raison_sociale'];?></a>
                </li>
                <li class="list-group-item">
                  <b>Montant</b> <a class="float-right"><?php  echo $row['montant'];?> </a>
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
                <li class="nav-item"><a class="nav-link active" href="#for_info" data-toggle="tab">Détail de la Demande</a></li>
                <li class="nav-item"><a class="nav-link" href="#besoin" data-toggle="tab">Mettre à jour Besoin</a></li>
              </ul>
            </div><!-- /.card-header -->
            <div class="card-body">
              <div class="tab-content">
                <div class="active tab-pane" id="for_info">
                  <form role="form" id=""  method="post" enctype="multipart/form-data" class="form-horizontal" >

                  <div class="row">
                        <div class="form-group col-md-3">
                          <label for="eta">Fornisseur</label>
                            <select type="select" class="form-control" id="fornisseur"  name="fornisseur" required>
                                <!-- <option value="<?php //echo $row['fornis']?>"><?php //echo $row['raison_sociale']?></option> -->
                                <option value="<?php echo $row['idfour']?>"><?php echo $row['raison_sociale']?></option>
                                <?php echo $c->charger_fornis()?>
                            </select>
                        </div>

                        <div class="form-group col-md-3">
                          <label for="montant">Montant</label>
                          <input type="number" class="form-control" id="montant"  value="<?php  echo $row['montant'];?>" name="montant" placeholder="...." required>
                        </div>
                        <!-- <div class="form-group col-md-3">
                          <label for="montant">Montant</label>
                          <input type="text" class="form-control" id="montant"  value="<?php  //echo $row['montant'];?>" name="rubrique" placeholder="...." required>
                        </div> -->
                        
                        <div class="form-group col-md-3">
                            <label for="date">Date</label>
                            <input type="date" class="form-control" id="date"  value="<?php  echo $row['date'];?>" name="date">
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
         
          
            <div class=" tab-pane" id="besoin">
              <div class="row">
                <form role="form" id=""  method="post" enctype="multipart/form-data" class="form-horizontal" >
                  <div class="form-group">
                    <label>Télécharger un besoin</label>
                    <input type="file" class="" name="besoin" value="" required>
                  </div>  

                  <div class="modal-footer text-right">
                    <button type="submit" name="save_besoin" class="btn btn-primary">Mettre à jour</button>
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