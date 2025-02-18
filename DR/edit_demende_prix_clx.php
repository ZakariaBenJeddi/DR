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
  $montant=$_POST['m'];
  $fournisseur=$_POST['four'];
  $dated=$_POST['date'];
  $sql = "UPDATE demande_prix_complex SET montant='$montant',idfour=(select idfour FROM fournisseur where raison_sociale='$fournisseur'),dated='$dated' WHERE id_demande=".$sid;
  $query = $dbh->prepare($sql);
  $query->execute();
  if ($query->execute()) {
      echo "<script>alert('mise à jour réussie.');</script>";
      echo "<script>window.location.href ='liste_demande_prix_clx.php'</script>";
    }else{
      echo "<script>alert('Quelque chose c'est mal passé. Merci d'essayer plus tard');</script>";
  }
}
if(isset($_POST['save_devis'])){
    $sid=$_SESSION['edid'];
    $img=$_FILES['devis']['name'];
    $emp='devis/';
    $chemin=$emp.basename($_FILES['devis']['name']);
    $sql="UPDATE demande_prix_complex SET devis='$img' WHERE id_demande='$sid'";
    $query = $dbh->prepare($sql);
    $query->execute();
    if ($query->execute()) {
      if(file_exists($chemin)){
      }
      else{
          move_uploaded_file($_FILES['devis']['tmp_name'],$chemin);
      }
      echo "<script>alert('mise à jour réussie.');</script>";
      echo "<script>window.location.href ='liste_demande_prix_clx.php'</script>";
    }else{
      echo "<script>alert('Quelque chose c'est mal passé. Merci d'essayer plus tard');</script>";
    }
}
if(isset($_POST['save_besoin'])){
    $sid=$_SESSION['edid'];
    $img=$_FILES['besoin']['name'];
    $emp='besoins/';
    $chemin=$emp.basename($_FILES['besoin']['name']);
    $sql="UPDATE demande_prix_complex SET besoin='$img' WHERE id_demande='$sid'";
    $query = $dbh->prepare($sql);
    $query->execute();
    if ($query->execute()) {
      if(file_exists($chemin)){
      }
      else{
          move_uploaded_file($_FILES['besoin']['tmp_name'],$chemin);
      }
      echo "<script>alert('mise à jour réussie.');</script>";
      echo "<script>window.location.href ='liste_demande_prix_clx.php'</script>";
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
       $ret=mysqli_query($con,"select * from demande_prix_complex where id_demande={$eid}");
       $cnt=1;
       while ($row=mysqli_fetch_array($ret))
       {
         $_SESSION['edid']=$row['id_demande'];
         ?>
        <!-- /.col -->
        <div class="col-md-12">
          <div class="card">
            <div class="card-header p-2">
              <ul class="nav nav-pills">
                <li class="nav-item"><a class="nav-link active" href="#for_info" data-toggle="tab">Détail de demande</a></li>
                <li class="nav-item"><a class="nav-link" href="#besoin" data-toggle="tab">Mettre à jour Besoin</a></li>
                <li class="nav-item"><a class="nav-link" href="#devis" data-toggle="tab">Mettre à jour Devis</a></li>
              </ul>
            </div>
            <div class="card-body">
              <div class="tab-content">
                <div class="active tab-pane" id="for_info">
                  <form role="form" id=""  method="post" enctype="multipart/form-data" class="form-horizontal" >
                    <div class="row">
                    <div class="col-md-4">
                      <div class="form-group">
                        <label for="cin">Montant</label>
                        <input type="number"  class="form-control" name="m" id="m" value="<?php  echo $row['montant'];?>" required>
                      </div>        
                    </div>
                    <div class="col-md-4">
                      <div class="form-group">
                        <label for="cin">Fournisseurs</label>
                        <select name="four" id="four" class="form-control">
                          <option><?php $retT=mysqli_query($con,"SELECT raison_sociale from fournisseur where idfour=$row[idfour]");
                           while ($row3=mysqli_fetch_array($retT)){echo $row3['raison_sociale'];}
                           ?></option>
                          <?php
                            $sql="SELECT raison_sociale FROM fournisseur WHERE idfour!=$row[idfour]";
                            $ret=mysqli_query($con,$sql);
                            $op='';
                            while ($row2=mysqli_fetch_array($ret)){
                              $op.="<option>$row2[raison_sociale]</option>";
                            }
                            echo $op;
                          ?>
                        </select>
                      </div>        
                    </div>
                    <div class="col-md-4">
                      <div class="form-group">
                        <label>Date</label>
                        <input type="date" class="form-control" name="date" value="<?php  echo $row['dated'];?>" required>
                      </div>        
                    </div>
                    
                    <!-- /.col -->
                  </div><!-- ./row -->

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
          <div class=" tab-pane" id="devis">
             <div class="row">
              <form role="form" id=""  method="post" enctype="multipart/form-data" class="form-horizontal" >
                <div class="form-group">
                  <label>Télécharger un devis</label>
                  <input type="file" class="" name="devis" value="" required>
                </div>  

                <div class="modal-footer text-right">
                  <button type="submit" name="save_devis" class="btn btn-primary">Mettre à jour</button>
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