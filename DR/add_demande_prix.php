<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');
include 'Controleur.php';
$c=new Controleur($dbh);
if (strlen($_SESSION['sid']==0)) {
  header('location:logout.php');
} else{
  if(isset($_POST['submit'])) {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      if (isset($_FILES['file'],$_POST['fornis'], $_POST['mnt'], $_POST['date']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
          $fileName = basename($_FILES['file']['name']);
          // echo htmlspecialchars($fileName)."<br>";
          $file = htmlspecialchars($fileName);
          $fornis = $_POST['fornis'];
          $mnt = $_POST['mnt'];
          $date = $_POST['date'];
          if ($con) {
            // $stmt = $con->prepare("INSERT INTO demande_prix(fornis, besoin ,montant, date) VALUES (?, ?, ?, ?)");
            $stmt = $con->prepare("INSERT INTO demande_prix(idfour, besoin ,montant, date,devis) VALUES (?, ?, ?, ?,1)");
            $stmt->bind_param("ssds",$fornis, $file, $mnt, $date);
            if ($stmt->execute()) {
                echo "<script>alert('La demande a été ajoutée.');</script>";
                // echo "<script>window.location.href = 'list_rest_payer.php'</script>";
                echo "<script>window.location.href = 'list_demande_prix.php'</script>";
              } else {
                  echo "<script>alert('Quelque chose a mal tourné. Veuillez réessayer.');</script>";
              }
          } else {
              echo "<script>alert('Erreur de connexion à la base de données.');</script>";
          }
        } else {
            echo "Il y a eu une erreur lors du téléchargement du fichier. Code d'erreur : " . $_FILES['file']['error'];
        }
      } else {
          echo "Aucun fichier téléchargé.";
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
        if($_SESSION['service']=='controle'){
          @include("includes/sidebar.php");
        }
        if($_SESSION['service']=='formation'){
          @include("includes/sidebarf.php");
        }
        if($_SESSION['service']=='RH'){
          @include("includes/sidebarRH.php");
        }
        if($_SESSION['service']=='achat_dr'){
          @include("includes/sidebarachat_dr.php");
        }
        if($_SESSION['service']=='achat_cmplx'){
          @include("includes/sidebarachat_cmplx.php");
        }
        if($_SESSION['service']=='compta'){
          @include("includes/sidebarcompta.php");
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
                  <li class="breadcrumb-item"><a href="dmd_prix.php">Liste</a></li>
                  <li class="breadcrumb-item active">Ajouter Demande de prix</li>
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
                    <h3 class="card-title">Ajouter une Demande</h3>
                  </div>
                  <!-- /.card-header -->
                  <!-- form start -->
                  <form role="form" method="post" enctype="multipart/form-data">
                    <div class="card-body">
                      <span style="color: brown"><h5>Détails Demande</h5></span>
                      <hr>
                      <div class="row">
                    
                    <div class="form-group col-md-3">
                        <label for="sex">Fornisseur</label>
                        <select type="select" class="form-control" id="fornis" name="fornis"required>
                            <option>Select Fornisseur</option>
                            <?php  echo $c->charger_fornis();?>
                        </select>
                        </div>
                        <div class="form-group col-md-3">
                            <label for="besoin">Besoin</label>
                            <input type="file" class="form-control" name="file" id="file">
                        </div>
                        <div class="form-group col-md-3">
                            <label for="mnt">Mantant</label>
                            <input type="number" class="form-control" id="mnt" name="mnt" placeholder="....." require>
                        </div>
                        <div class="form-group col-md-3">
                            &nbsp;<b><label for="date">Date</label></b>
                            <input type="date" class="form-control" id="date" name="date" require>
                        </div>
                        <div class="row"> 
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
  </body>
  </html>
  <?php
}?>
