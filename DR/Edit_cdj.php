<?php

session_start();
// try {
error_reporting(0);
include('includes/dbconnection.php');

if (isset($_POST['ajouter'])) {
  $sid = $_POST['id'];
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
  $passerelle = $_POST['passerelle'];

  $sql = "UPDATE cdj_v2 SET 
        dep=:dep,
        dr=:dr,
        code_efp=:code_efp,
        efp=:efp,
        niveau=:niveau,
        code_filiere=:code_filiere,
        filiere=:filiere,
        type_formation=:type_formation,
        prevu=:prevu,
        annee_etude=:annee_etude,
        stagiaires=:stagiaires,
        actif=:actif,
        transfert=:transfert,
        desistement=:desistement,
        redoublement=:redoublement,
        passerelle=:passerelle
        WHERE id=:sid";

  $query = $dbh->prepare($sql);
  $query->bindParam(':dep', $dep);
  $query->bindParam(':dr', $dr);
  $query->bindParam(':code_efp', $code_efp);
  $query->bindParam(':efp', $efp);
  $query->bindParam(':niveau', $niveau);
  $query->bindParam(':code_filiere', $code_filiere);
  $query->bindParam(':filiere', $filiere);
  $query->bindParam(':type_formation', $type_formation);
  $query->bindParam(':prevu', $prevu);
  $query->bindParam(':annee_etude', $annee_etude);
  $query->bindParam(':stagiaires', $stagiaires);
  $query->bindParam(':actif', $actif);
  $query->bindParam(':transfert', $transfert);
  $query->bindParam(':desistement', $desistement);
  $query->bindParam(':redoublement', $redoublement);
  $query->bindParam(':passerelle', $passerelle);
  $query->bindParam(':sid', $sid);

  try {
    if ($query->execute()) {
        echo "<script>alert('Mise à jour réussie.');</script>";
        echo "<script>window.location.href ='cdj_v2.php'</script>";
    } else {
        echo "<script>alert('Une erreur s'est produite lors de l'importation.');</script>";
    }
} catch (PDOException $e) {
    echo "Erreur SQL : " . $e->getMessage();
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
        $ret = mysqli_query($con, "SELECT * FROM cdj_v2 WHERE id=" . $eid);
        $cnt = 1;
        while ($row = mysqli_fetch_array($ret)) {
          $_SESSION['edid'] = $row['id'];
        ?>
          <!-- /.col -->
          <div class="col-md-12">
            <div class="card">
              <div class="card-header p-2">
                <ul class="nav nav-pills">
                  <li class="nav-item"><a class="nav-link active" href="#for_info" data-toggle="tab">Détail Cours Du Jour</a></li>
                </ul>
              </div><!-- /.card-header -->
              <div class="card-body">
                <div class="tab-content">
                  <div class="active tab-pane" id="for_info">
                    <form enctype="multipart/form-data" method="post">
                      <?php
                        $iddd =  $row['id'] ;
                      ?>
                      <div class="row">
                        <div class="col-md-4">
                          <div class="form-group">
                            <label for="dep">DEPARTEMENT</label>
                            <input readonly hidden name="id" id="id" value="<?php echo $_SESSION['edid']; ?>">
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
                            <select class="form-control" name="annee_etude" id="annee_etude" required>
                              <option value="1ère année" <?php echo ($row['annee_etude'] == '1ère année') ? 'selected' : ''; ?>>1ère année</option>
                              <option value="2ème année" <?php echo ($row['annee_etude'] == '2ème année') ? 'selected' : ''; ?>>2ème année</option>
                              <option value="3ème année" <?php echo ($row['annee_etude'] == '3ème année') ? 'selected' : ''; ?>>3ème année</option>
                            </select>
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

                        <div class="col-md-4">
                          <div class="form-group">
                            <label for="passerelle">Passerelle</label>
                            <input class="form-control" name="passerelle" id="passerelle" value="<?php echo $row['passerelle']; ?>" required>
                          </div>
                        </div>

                        <div>
                          <div class="modal-footer text-right">
                            <input type="submit" name="ajouter" value="Submit" class="btn btn-primary" >
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