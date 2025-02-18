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
    if (isset($_POST['ste'], $_POST['nat'], $_POST['p'], $_POST['datef'], $_POST['d'])) {
        $ste = filter_var($_POST['ste'], FILTER_SANITIZE_NUMBER_INT);
        $nat = filter_var($_POST['nat'], FILTER_SANITIZE_STRING);
        $p = filter_var($_POST['p'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $datef = filter_var($_POST['datef'], FILTER_SANITIZE_STRING);
        $d = filter_var($_POST['d'], FILTER_SANITIZE_STRING);
        if ($con) {
            $stmt = $con->prepare("INSERT INTO formation (ids, nature, prix, datef, duree) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("isdss", $ste, $nat, $p, $datef, $d);
    
            if ($stmt->execute()) {
              $stmt1 = $con->query("INSERT INTO fact (ids, nature, prix, devis, bcmd, facture, datef) VALUES ('{$ste}', '{$nat}',{$p}, 1, 0, 0,'{$datef}')");
            
                echo "<script>alert('La formation a été ajoutée.');</script>";
                echo "<script>window.location.href = 'List_formation.php'</script>";
            } else {
                echo "<script>alert('Quelque chose a mal tourné. Veuillez réessayer.');</script>";
            }
        } else {
            echo "<script>alert('Erreur de connexion à la base de données.');</script>";
        }
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
                  <li class="breadcrumb-item"><a href="List_formation.php">Liste</a></li>
                  <li class="breadcrumb-item active">Ajouter une formation</li>
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
                    <h3 class="card-title">Ajouter une formation</h3>
                  </div>
                  <!-- /.card-header -->
                  <!-- form start -->
                  <form role="form" method="post" enctype="multipart/form-data">
                    <div class="card-body">
                      <span style="color: brown"><h5>Détails formation</h5></span>
                      <hr>
                      <div class="row">
                    
                      <div class="form-group col-md-3">
                          <label for="sex">Société</label>
                          <select type="select" class="form-control" id="ste" name="ste"required>
                            <option>Select Société</option>
                      <?php  echo $c->charger_ste();       ?>
                          </select>
                        </div>

                        <div class="form-group col-md-3">
                          <label for="prenom">Nature</label>
                          <input type="text" class="form-control" id="prenom" name="nat" placeholder="...." required>
                        </div>
                        <div class="form-group col-md-3">
                          <label for="age">Prix</label>
                          <input type="number" class="form-control" id="age" name="p" placeholder="....." >
                        </div>
                     
                        <div class="form-group col-md-3">
                          &nbsp;<b><label for="age">Date de Formation:</label></b>
                          <input type="date" class="form-control" id="daten" name="datef" placeholder="Naissance" >
                        </div>

                        <div class="form-group col-md-3">
                          <label for="age">Duree</label>
                          <input type="number" class="form-control" id="age" name="d" placeholder="....." >
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
