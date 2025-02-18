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
  $statut=1;
  $montant=$_POST['m'];
  $consom=$_POST['mc'];
  $rubrique=$_POST['r'];
  $nomE=$_POST['nomE'];
  $dateres=$_POST['datres'];
  $reste=$montant-$consom;
  if($reste<0){
    echo "<script>alert('consom est grand que montant');</script>";
  }else{
    $sql = "UPDATE budgetcomplex SET montant='$montant', ide=(SELECT ide from etablissement where nomE='$nomE'), rubrique='$rubrique', dateres='$dateres',statut='$statut' ,reste='$reste' ,montantConsom='$consom' WHERE idbudgetclx=".$sid;
    $query = $dbh->prepare($sql);
    $query->execute();
    if ($query->execute()) {
      echo "<script>alert('mise à jour réussie.');</script>";
      echo "<script>window.location.href ='list_budget_complex.php'</script>";
    }else{
      echo "<script>alert('Quelque chose c'est mal passé. Merci d'essayer plus tard');</script>";
    }
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
       $ret=mysqli_query($con,"select * from budgetcomplex where idbudgetclx={$eid}");
       $cnt=1;
       while ($row=mysqli_fetch_array($ret))
       {
         $_SESSION['edid']=$row['idbudgetclx'];
         ?>
        <!-- /.col -->
        <div class="col-md-12">
          <div class="card">
            <div class="card-header p-2">
              <ul class="nav nav-pills">
                <li class="nav-item"><a class="nav-link active" data-toggle="tab">Détail de budget</a></li>
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
                         <label for="companyname">Consom</label>
                         <input type="number" class="form-control" name="mc" id="mc" value="<?php  echo $row['montantConsom'];?>" required>
                       </div>
                           
                    </div>
                    <div class="col-md-4">
                      <div class="form-group">
                        <label>Date</label>
                        <input type="date"class="form-control" name="datres" value="<?php  echo $row['dateres'];?>" required>
                      </div>  
                       <!-- /.form-group -->
                     </div>
                    
                    <!-- /.col -->
                  </div><!-- ./row -->

                  <div class="row">
                   
                    <div class="col-md-4">
                      <div class="form-group">
                        <label>Rubrique</label>
                        <input class="form-control" name="r" value="<?php  echo $row['rubrique'];?>" required>
                      </div>        
                    </div>
                    <div class="col-md-4">
                      <div class="form-group">
                        <label for="cin">Etablissements</label>
                        <select name="nomE" id="nomE" class="form-control">
                          <option><?php $retT=mysqli_query($con,"SELECT nomE from etablissement where ide=$row[ide]");
                           while ($row3=mysqli_fetch_array($retT)){echo $row3['nomE'];}
                           ?></option>
                          <?php
                            $sql="SELECT nomE FROM etablissement WHERE idcomplex =(SELECT idcomplex FROM complexe WHERE id=$_SESSION[sid]) and ide!=$row[ide]";
                            $ret=mysqli_query($con,$sql);
                            $op='';
                            while ($row2=mysqli_fetch_array($ret)){
                              $op.="<option>$row2[nomE]</option>";
                            }
                            echo $op;
                          ?>
                        </select>
                      </div>        
                    </div>
                  </div>
                  <!-- /.card-body -->
                  <div class="modal-footer text-right">
                    <button type="submit" name="submit" class="btn btn-primary">Mettre à jour</button>
                  </div>
                </form>
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