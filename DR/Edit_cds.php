<?php

session_start();
// try {
error_reporting(0);
include('includes/dbconnection.php');
if (isset($_POST['submit'])) {
  $sid = $_SESSION['edid'];
  $dep = $_POST['dep'];
  $dr = $_POST['dr'];
  $code_efp = $_POST['code_efp'];
  $efp = $_POST['efp'];
  $niveau = $_POST['niveau'];
  $code_filiere = $_POST['code_filiere'];
  $filiere = $_POST['filiere'];
  $type_formation = $_POST['type_formation'];
  $prevu = $_POST['prevu'];
  $annee_etude = $_POST['annee_etude'];
  $stagiaires = $_POST['stagiaires'];
  $actif = $_POST['actif'];
  $transfert = $_POST['transfert'];
  $desistement = $_POST['desistement'];
  $redoublement = $_POST['redoublement'];

  $sql = "UPDATE cds_v2 SET 
        dep=:dep,
        -- dr=:dr,
        -- code_efp=:code_efp,
        -- efp=:efp,
        -- niveau=:niveau,
        -- code_filiere=:code_filiere,
        -- filiere=:filiere,
        -- type_formation=:type_formation,
        -- prevu=:prevu,
        -- annee_etude=:annee_etude,
        -- stagiaires=:stagiaires,
        -- actif=:actif,
        -- transfert=:transfert,
        -- desistement=:desistement,
        -- redoublement=:redoublement
        WHERE id=:sid";

  $query = $dbh->prepare($sql);
  $query->bindParam(':dep', $dep);
  // $query->bindParam(':dr', $dr);
  // $query->bindParam(':code_efp', $code_efp);
  // $query->bindParam(':efp', $efp);
  // $query->bindParam(':niveau', $niveau);
  // $query->bindParam(':code_filiere', $code_filiere);
  // $query->bindParam(':filiere', $filiere);
  // $query->bindParam(':type_formation', $type_formation);
  // $query->bindParam(':prevu', $prevu);
  // $query->bindParam(':annee_etude', $annee_etude);
  // $query->bindParam(':stagiaires', $stagiaires);
  // $query->bindParam(':actif', $actif);
  // $query->bindParam(':transfert', $transfert);
  // $query->bindParam(':desistement', $desistement);
  // $query->bindParam(':redoublement', $redoublement);
  $query->bindParam(':sid', $sid);

  if ($query->execute()) {
    echo "<script>alert('mise à jour réussie.');</script>";
    echo "<script>window.location.href ='cds_v2.php'</script>";
  } else {
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
        $eid = intval($_POST['edit_id']);
        $ret = mysqli_query($con, "SELECT * FROM cds_v2 WHERE id=" . $eid);
        $cnt = 1;
        while ($row = mysqli_fetch_array($ret)) {
          $_SESSION['edid'] = $row['idfour'];
        ?>
          <div class="col-md-3">
            <!-- Profile Image -->
            <div class="card card-primary card-outline">
              <div class="card-body box-profile">
                <div class="text-center">
                  <img class="img-circle" src="fournisseur_img/<?php echo $row['imgefour'] ?>" width="150" height="150" class="user-image" alt="User profile picture">
                </div>

                <h3 class="profile-username text-center"><?php echo $row['raison_sociale']; ?> </h3>



                <p class="text-muted text-center"><strong></strong></p>

                <ul class="list-group list-group-unbordered mb-3">
                  <li class="list-group-item">
                    <b>Email</b> <a class="float-right"><?php echo $row['email']; ?></a>
                  </li>
                  <li class="list-group-item">
                    <b>Fix</b> <a class="float-right"><?php echo $row['fix']; ?> </a>
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
                    <form enctype="multipart/form-data" method="post">
                      <div class="row">
                        <div class="col-md-4">
                          <div class="form-group">
                            <label for="dep">DEPARTEMENT</label>
                            <input class="form-control" name="dep" id="dep" value="<?php echo $row['dep']; ?>" required>
                          </div>
                        </div>
                        <div class="col-md-4">
                          <div class="form-group">
                            <label for="dr">DR</label>
                            <input class="form-control" name="dr" id="dr" value="<?php echo $row['dr']; ?>" required>
                          </div>

                        </div>
                        <div class="col-md-4">
                          <div class="form-group">
                            <label for="code_efp">Code EFP</label>
                            <input class="form-control" name="code_efp" id="code_efp" value="<?php echo $row['code_efp']; ?>" required>
                          </div>
                        </div>

                      </div>
                      <div class="row">
                        <div class="col-md-4">
                          <div class="form-group">
                            <label for="efp">EFP</label>
                            <input class="form-control" name="efp" id="efp" value="<?php echo $row['efp']; ?>" required>
                          </div>
                        </div>
                        <div class="col-md-4">
                          <div class="form-group">
                            <label for="niveau">Niveau</label>
                            <input class="form-control" name="niveau" id="niveau" value="<?php echo $row['niveau']; ?>" required>
                          </div>

                        </div>
                        <div class="col-md-4">
                          <div class="form-group">
                            <label for="code_filiere">Code Filiere</label>
                            <input class="form-control" name="code_filiere" id="code_filiere" value="<?php echo $row['code_filiere']; ?>" required>
                          </div>
                        </div>

                      </div>
                      <div class="row">
                        <div class="col-md-4">
                          <div class="form-group">
                            <label for="filiere">Filiere</label>
                            <input class="form-control" name="filiere" id="filiere" value="<?php echo $row['filiere']; ?>" required>
                          </div>
                        </div>
                        <div class="col-md-4">
                          <div class="form-group">
                            <label for="type_formation">Type Formation</label>
                            <input class="form-control" name="type_formation" id="type_formation" value="<?php echo $row['type_formation']; ?>" required>
                          </div>

                        </div>
                        <div class="col-md-4">
                          <div class="form-group">
                            <label for="prevu">Prevue</label>
                            <input class="form-control" name="prevu" id="prevu" value="<?php echo $row['prevu']; ?>" required>
                          </div>
                        </div>

                      </div>
                      <div class="row">
                        <div class="col-md-4">
                          <div class="form-group">
                            <label for="annee_etude">Annee Etude</label>
                            <input class="form-control" name="annee_etude" id="annee_etude" value="<?php echo $row['annee_etude']; ?>" required>
                          </div>
                        </div>
                        <div class="col-md-4">
                          <div class="form-group">
                            <label for="stagiaires">Stagiaires</label>
                            <input class="form-control" name="stagiaires" id="stagiaires" value="<?php echo $row['stagiaires']; ?>" required>
                          </div>

                        </div>
                        <div class="col-md-4">
                          <div class="form-group">
                            <label for="actif">Actif</label>
                            <input class="form-control" name="actif" id="actif" value="<?php echo $row['actif']; ?>" required>
                          </div>
                        </div>

                      </div>
                      <div class="row">
                        <div class="col-md-4">
                          <div class="form-group">
                            <label for="transfert">Transfert</label>
                            <input class="form-control" name="transfert" id="transfert" value="<?php echo $row['transfert']; ?>" required>
                          </div>
                        </div>
                        <div class="col-md-4">
                          <div class="form-group">
                            <label for="desistement">Desistement</label>
                            <input class="form-control" name="desistement" id="desistement" value="<?php echo $row['desistement']; ?>" required>
                          </div>

                        </div>
                        <div class="col-md-4">
                          <div class="form-group">
                            <label for="redoublement">Redoublement</label>
                            <input class="form-control" name="redoublement" id="redoublement" value="<?php echo $row['redoublement']; ?>" required>
                          </div>
                        </div>

                        <div>
                          <div class="modal-footer text-right">
                            <!-- <button type="submit" name="submit" class="btn btn-primary">Mettre à jour</button> -->
                            <input type="submit" value="Submit" class="btn btn-primary" >
                          </div>
                    </form>
                  </div>
                <?php
              } ?>
                </div>
                <!-- /.tab-content -->
              </div><!-- /.card-body -->
  </section>
  <!-- /.content -->
</div>
<!-- /.content-wrapper -->


<?php

// } catch (Exception $e) {
//   // Code to handle the exception
//   echo "error: " . $e->getMessage();
// }
?>