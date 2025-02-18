<?php

use PhpOffice\PhpSpreadsheet\IOFactory;

session_start();
error_reporting(0);
ini_set("max_execution_time", "1000");
include('includes/dbconnection.php');

if (empty($_SESSION['sid'])) {
  header('location:logout.php');
} else {
  if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES["excel_file"])) {
    $uploadDir = "uploads/";
    $tempFileName = $_FILES["excel_file"]["tmp_name"];

    require 'vendor/autoload.php';

    try {
      $spreadsheet = IOFactory::load($tempFileName);
      $worksheet = $spreadsheet->getActiveSheet();



      foreach ($worksheet->getRowIterator(2) as $row) {
        $cellIterator = $row->getCellIterator();
        $cellIterator->setIterateOnlyExistingCells(false);

        $sec = $cellIterator->current()->getValue();
        $cellIterator->next();

        $codef = $cellIterator->current()->getValue();
        $cellIterator->next();

        $niveau = $cellIterator->current()->getValue();
        $cellIterator->next();

        $module = $cellIterator->current()->getValue();
        $cellIterator->next();
        $creneau = $cellIterator->current()->getValue();
        $cellIterator->next();
        $annee = $cellIterator->current()->getValue();
        $cellIterator->next();
        $type_ex = $cellIterator->current()->getValue();
        $cellIterator->next();

        $type_ep = $cellIterator->current()->getValue();
        $cellIterator->next();

        $nbrv = $cellIterator->current()->getValue();
        $cellIterator->next();

        $nbrs = $cellIterator->current()->getValue();
        $cellIterator->next();

        $nbrj = $cellIterator->current()->getValue();
        $cellIterator->next();

        $duree = $cellIterator->current()->getValue();
        $cellIterator->next();



        $stmt = $dbh->prepare("INSERT INTO `rnd`(`sec`, `codef`, `niveau`, `module`, `creneau`, `annee`, `type_ex`, `type_ep`, `nbrv`, `nbrs`, `nbrj`, `duree`) VALUES (?,?,?,?,?,?,?,?,?,?,?,?)");


        $stmt->execute([$sec, $codef, $niveau, $module, $creneau, $annee, $type_ex, $type_ep, $nbrv, $nbrs, $nbrj, $duree]);


        $sec = $codef = $niveau = $module = $creneau = $annee = $type_ex = $type_ep = $nbrv = $nbrs = $nbrj = $duree = '';
      }


      echo '<script>alert("Importation réussie !")</script>';
      echo "<script>window.location.href ='aff1.php'</script>";
    } catch (Exception $e) {
      echo "Erreur : " . $e->getMessage();
    }

    // Fermez la connexion à la base de données
    $dbh = null;
  }
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
              <h1>Importer depuis Excel</h1>
            </div>
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="#">Accueil</a></li>
                <li class="breadcrumb-item active">Importer depuis Excel</li>
              </ol>
            </div>
          </div>

        </div><!-- /.container-fluid -->
      </section>

      <!-- Main content -->
      <section class="content">
        <div class="container-fluid">
          <div class="row">
            <div class="col-12">
              <div class="card">
                <div class="card-header">
                  <h3 class="card-title">Importer depuis Excel</h3>
                  <div class="card-tools">
                    <form method="post" enctype="multipart/form-data">
                      <input type="file" name="excel_file" accept=".xlsx, .xls" class="btn btn-sm btn-primary">
                      <button type="submit" class="btn btn-sm btn-primary"><span style="color: #fff;"><i class="fas fa-download"></i> Importer</span>
                      </button>
                    </form>
                  </div>
                </div>
                <!-- /.card-header -->

              </div>
            </div>
            <!-- /.card-body -->
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

  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
  </aside>
  <!-- /.control-sidebar -->
  </div>
  <?php



  @include("includes/foot.php"); ?>


</body>

</html>