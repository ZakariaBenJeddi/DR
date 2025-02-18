<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');
include 'Controleur.php';
$c=new Controleur($dbh);
if (strlen($_SESSION['sid']==0)) {
  header('location:logout.php');
} else{
  if(isset($_POST['submit'])){
    
    $eta = trim(mysqli_real_escape_string($con, $_POST['eta']));
    $rubrique = trim(mysqli_real_escape_string($con, $_POST['rubrique']));
    $montant = trim(mysqli_real_escape_string($con, $_POST['montant']));
    $date = trim(mysqli_real_escape_string($con, $_POST['date']));
    
    $Permission = "super user";
    
    $stmt = $con->prepare("INSERT INTO budget_dr (id_e,rubrique,date,montant,consome,reste) VALUES (?,?,?,?,0,0)");
    $stmt->bind_param("isss",$eta,$rubrique,$date,$montant);
    if ($stmt->execute()) {
        echo "<script>alert('La société a été ajoutée.');</script>";
        echo "<script>window.location.href = 'list_budget_dr.php'</script>";
    } else {
        echo "<script>alert('Quelque chose a mal tourné. Veuillez réessayer.');</script>";
    }
    
    $stmt->close();
    $con->close();


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
    if($_SESSION['service']=='orientation'){
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
                  <li class="breadcrumb-item"><a href="list_budget_dr.php">Budget DR</a></li>
                  <li class="breadcrumb-item active">Ajouter un Budget DR</li>
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
                    <h3 class="card-title">Ajouter un Budget DR</h3>
                  </div>
                  <!-- /.card-header -->
                  <!-- form start -->
                  <form role="form" method="post" enctype="multipart/form-data">
                    <div class="card-body">
                      <span style="color: brown"><h5>Détails  Budget DR</h5></span>
                      <hr>
                      <div class="row">
                        <div class="form-group col-md-3">
                          <label for="eta">Etablissement</label>
                          <select type="select" class="form-control" id="mySelect" name="eta" required onclick="removeOption()">
                            <option value="choose">Selectionner Etablissement</option>
                            <?php  echo $c->charger_etablisement();?>
                            </select>
                          <!-- <input type="text" class="form-control" id="eta" name="eta" required> -->
                        </div>

                        <div class="form-group col-md-3">
                          <label for="rubrique">Rubrique</label>
                          <input type="text" class="form-control" id="rubrique" name="rubrique" placeholder="...." required>
                        </div>
                        <div class="form-group col-md-3">
                          <label for="montant">Montant</label>
                          <input type="number" class="form-control" id="montant" name="montant" placeholder="....." >
                        </div>
                        <div class="form-group col-md-3">
                          <label for="date">Date</label>
                          <input type="date" class="form-control" id="date" name="date" >
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
}?>
