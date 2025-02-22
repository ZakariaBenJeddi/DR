<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');
include 'Controleur.php';
$c = new Controleur($dbh);
if (strlen($_SESSION['sid'] == 0)) {
  header('location:logout.php');
} else {
  if (isset($_POST['submit'])) {
    $dept = trim(mysqli_real_escape_string($con, $_POST['dept']));
    $dr = trim(mysqli_real_escape_string($con, $_POST['dr']));
    $code_efp = trim(mysqli_real_escape_string($con, $_POST['code_efp']));
    $efp = trim(mysqli_real_escape_string($con, $_POST['efp']));
    $niveau = trim(mysqli_real_escape_string($con, $_POST['niveau']));
    $code_filiere = trim(mysqli_real_escape_string($con, $_POST['code_filiere']));
    $filiere = trim(mysqli_real_escape_string($con, $_POST['filiere']));
    $type_formation = trim(mysqli_real_escape_string($con, $_POST['type_formation']));
    $prevu = trim(mysqli_real_escape_string($con, $_POST['prevue'])); // Correction du nom
    $annee_etude = trim(mysqli_real_escape_string($con, $_POST['annee_etude']));
    $stagiaires = trim(mysqli_real_escape_string($con, $_POST['stagiaire'])); // Correction du nom
    $actif = trim(mysqli_real_escape_string($con, $_POST['actif']));
    $transfert = trim(mysqli_real_escape_string($con, $_POST['transfert']));
    $desistement = trim(mysqli_real_escape_string($con, $_POST['desistement']));
    $redoublement = trim(mysqli_real_escape_string($con, $_POST['redoublent'])); // Correction du nom

    $stmt = $con->prepare("INSERT INTO cds_v2 (dep, dr, code_efp, efp, niveau, code_filiere, filiere, type_formation, prevu, annee_etude, stagiaires, actif, transfert, desistement, redoublement) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    
    $stmt->bind_param("sssssssssssssss", 
        $dept, 
        $dr, 
        $code_efp, 
        $efp, 
        $niveau, 
        $code_filiere, 
        $filiere,
        $type_formation, 
        $prevu, 
        $annee_etude, 
        $stagiaires, 
        $actif, 
        $transfert, 
        $desistement, 
        $redoublement
    );
    
    if ($stmt->execute()) {
        echo "<script>alert('Le cours du soir a été ajouté avec succès.');</script>";
        echo "<script>window.location.href = 'cds_v2.php'</script>";
    } else {
        echo "<script>alert('Erreur lors de l'ajout : " . $stmt->error . "');</script>";
    }

    $stmt->close();
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
      <?php
      if ($_SESSION['service'] == 'controle') {
        @include("includes/sidebar.php");
      }
      if ($_SESSION['service'] == 'formation') {
        @include("includes/sidebarf.php");
      }
      if ($_SESSION['service'] == 'RH') {
        @include("includes/sidebarRH.php");
      }
      if ($_SESSION['service'] == 'achat_dr') {
        @include("includes/sidebarachat_dr.php");
      }
      if ($_SESSION['service'] == 'achat_cmplx') {
        @include("includes/sidebarachat_cmplx.php");
      }
      if ($_SESSION['service'] == 'compta') {
        @include("includes/sidebarcompta.php");
      }
      if ($_SESSION['service'] == 'orientation') {
        @include("includes/sidebar_orientation.php");
      }
      ?>

      <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <div class="container-fluid">
            <div class="row mb-2">
              <div class="col-sm-6">
              </div>
              <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                  <li class="breadcrumb-item"><a href="list_budget_dr.php">Cours Du Soir</a></li>
                  <li class="breadcrumb-item active">Ajouter un Cours Du Soir</li>
                </ol>
              </div>
            </div>
          </div><!-- /.container-fluid -->
        </section>

        <!-- Main content -->
        <section class="content">
          <div class="container-fluid">
            <div class="row ">
              <div class="col-md-12">
                <!-- general form elements -->
                <div class="card card-primary">
                  <div class="card-header">
                    <h3 class="card-title">Ajouter un Cours Du Soir</h3>
                  </div>
                  <!-- /.card-header -->
                  <!-- form start -->
                  <form role="form" method="post" enctype="multipart/form-data">
                    <div class="card-body">
                      <span style="color: brown">
                        <h5>Détails Cours Du Soir</h5>
                      </span>
                      <hr>
                      <div class="row">
                        <div class="form-group col-md-3">
                          <label for="dept">DEPT</label>
                          <input type="text" id="dept" class="form-control" name="dept" placeholder="Departement" required>
                        </div>

                        <div class="form-group col-md-3">
                          <label for="dr">DR</label>
                          <input type="text" class="form-control" id="dr" name="dr" placeholder="Direction" required>
                        </div>
                        <div class="form-group col-md-3">
                          <label for="code_efp">Code EFP</label>
                          <input type="text" class="form-control" id="code_efp" name="code_efp" placeholder="Code EFP">
                        </div>
                        <div class="form-group col-md-3">
                          <label for="efp">EFP</label>
                          <input type="text" class="form-control" id="efp" name="efp" placeholder="EFP">
                        </div>
                      </div>
                      <div class="row">
                        <div class="form-group col-md-3">
                          <label for="niveau">Niveau</label>
                          <input type="text" id="niveau" class="form-control" name="niveau" placeholder="Niveau" required>
                        </div>

                        <div class="form-group col-md-3">
                          <label for="code_filiere">Code Filiere</label>
                          <input type="text" class="form-control" id="code_filiere" name="code_filiere" placeholder="Code Filiere" required>
                        </div>
                        <div class="form-group col-md-3">
                          <label for="filiere">Filiere</label>
                          <input type="text" class="form-control" id="filiere" name="filiere" placeholder="Filiere">
                        </div>
                        <div class="form-group col-md-3">
                          <label for="type_formation">Type Formation</label>
                          <input type="text" class="form-control" id="type_formation" name="type_formation" placeholder="Type Formation">
                        </div>
                      </div>
                      <div class="row">
                        <div class="form-group col-md-3">
                          <label for="prevue">Prevue</label>
                          <!-- <input type="number" id="prevue" class="form-control" name="prevue" placeholder="Prevue" required> -->
                          <input type="number" id="prevue" class="form-control" name="prevue" placeholder="Prevu">

                        </div>

                        <div class="form-group col-md-3">
                          <label for="annee_etude">Annee Etude</label>
                          <select name="annee_etude" id="annee_etude" class="form-select">
                            <option value="1ère année">1ère année</option>
                            <option value="2ème année">2ème année</option>
                            <option value="3ème année">3ème année</option>
                          </select>
                        </div>
                        <div class="form-group col-md-3">
                          <label for="stagiaire">Stagiaire</label>
                          <!-- <input type="number" class="form-control" id="stagiaire" name="stagiaire" placeholder="Stagiaire"> -->
                          <input type="number" class="form-control" id="stagiaire" name="stagiaire" placeholder="Stagiaires">
                        </div>
                        <div class="form-group col-md-3">
                          <label for="actif">Actif</label>
                          <input type="text" class="form-control" id="actif" name="actif" placeholder="Actif">
                        </div>
                      </div>
                      <div class="row">
                        <div class="form-group col-md-3">
                          <label for="transfert">Transfert</label>
                          <input type="number" id="transfert" class="form-control" name="transfert" placeholder="transfert" required>
                        </div>
                        <div class="form-group col-md-3">
                          <label for="desistement">Desistement</label>
                          <input type="number" class="form-control" id="desistement" name="desistement" placeholder="Desistement">
                        </div>
                        <div class="form-group col-md-3">
                          <label for="redoublent">Redoublent</label>
                          <!-- <input type="text" class="form-control" id="redoublent" name="redoublent" placeholder="Redoublent"> -->
                          <input type="text" class="form-control" id="redoublent" name="redoublent" placeholder="Redoublement">
                        </div>
                      </div>
                      <hr>
                    </div>
                    <!-- /.card-body -->
                    <div class="card-footer">
                      <center>
                        <button type="submit" name="submit" class="btn btn-primary">Soumettre</button>

                      </center>
                    </div>
                  </form>
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

    </div>
    <!-- ./wrapper -->
    <?php @include("includes/foot.php"); ?>
    <script>
      function removeOption() {
        var select = document.getElementById("mySelect");
        var chooseOption = select.options[0];
        if (chooseOption.value === "choose") {
          chooseOption.parentNode.removeChild(chooseOption);
        }
      }
    </script>
  </body>

  </html>
<?php
} ?>