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
  $eta=htmlspecialchars($_POST['eta']);
  $rubrique=htmlspecialchars($_POST['rubrique']);
  $date=htmlspecialchars($_POST['date']);
  $montant=htmlspecialchars(($_POST['montant']));
  $consome=htmlspecialchars($_POST['consome']);
  $reste= $montant - $consome;

    if ($consome <= $montant) {
      $query = $dbh->exec("UPDATE budget_dr SET id_e='$eta',rubrique='$rubrique', date='$date', montant = '$montant', consome='$consome', reste='$reste' WHERE id_dr = '$sid'" );
      if ($query) {
        echo "<script>alert('mise à jour réussie.');</script>";
        echo "<script>window.location.href ='list_budget_dr.php'</script>";
      }else{
          echo "<script>alert('Quelque chose c'est mal passé. Merci d'essayer plus tard');</script>";
      }
    }else{
      echo "<script>alert('Le prix consommé est supperieur au montant.');</script>";
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
          $ret=mysqli_query($con,"SELECT budget_dr.*, etablissement.nomE , etablissement.ide FROM budget_dr INNER JOIN etablissement ON etablissement.ide = budget_dr.id_e where id_dr=".$eid);
          $cnt=1;
          while ($row=mysqli_fetch_array($ret))
          {
            $_SESSION['edid']=$row['id_dr']; 
        ?>
          <!-- /.card -->
      <!-- </div> -->
        <!-- /.col -->
        <div class="col-md-12">
          <div class="card">
            <div class="card-header p-2">
              <ul class="nav nav-pills">
                <li class="nav-item"><a class="nav-link active" href="#for_info" data-toggle="tab">Détail de la société</a></li>
                 <!-- <li class="nav-item"><a class="nav-link" href="#img_ch" data-toggle="tab">Mettre à jour l'image</a></li> -->
              </ul>
            </div><!-- /.card-header -->
            <div class="card-body">
              <div class="tab-content">
                <div class="active tab-pane" id="for_info">
                  <form role="form" id=""  method="post" enctype="multipart/form-data" class="form-horizontal" >

                  <div class="row">
                        <div class="form-group col-md-3">
                          <label for="eta">Etablissement</label>
                            <select type="select" class="form-control" id="eta"  name="eta" required>
                                <option value="<?php echo $row['ide']?>"><?php echo $row['nomE']?></option>
                                <?php echo $c->charger_etablisement()?>
                            </select>
                        </div>

                        <div class="form-group col-md-3">
                          <label for="rubrique">Rubrique</label>
                          <input type="text" class="form-control" id="rubrique"  value="<?php  echo $row['rubrique'];?>" name="rubrique" placeholder="...." required>
                        </div>
                        
                        <div class="form-group col-md-3">
                            <label for="date">Date</label>
                            <input type="date" class="form-control" id="date"  value="<?php  echo $row['date'];?>" name="date">
                        </div>
                        <!-- <div class="row"> -->
                            <div class="form-group col-md-3">
                                <label for="montant">Montant</label>
                                <input type="number" class="form-control" id="montant"  value="<?php  echo $row['montant'];?>" name="montant" >
                            </div>
                            <div class="form-group col-md-3">
                                <label for="consome">Consome</label>
                                <input type="number" class="form-control" max="<?php $row['montant'] ?>" id="consome" name="consome" value="<?php  echo $row['consome'];?>" >
                            </div>
                        <!-- </div> -->
                    <!-- /.col --> 
                  </div>
                  <!-- /.card-body -->
                      <div class="modal-footer text-right">
                        <button type="submit" name="submit" class="btn btn-primary">Mettre à jour</button>
                      </div>
                </form>
              </div>
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
    <script>
        // function updateReste() {
        //     var montant = document.getElementById('montant').value;
        //     var consome = document.getElementById('consome').value;
        //     var reste = montant - consome;
        //     document.getElementById('reste').value = reste;
        // }

        // function updateConsome() {
        //     var montant = document.getElementById('montant').value;
        //     var reste = document.getElementById('reste').value;
        //     var consome = montant - reste;
        //     document.getElementById('consome').value = consome;
        // }
  </script>

  <?php
  
    }catch (Exception $e) {
    // Code to handle the exception
    echo "error: " . $e->getMessage();
}
  ?>