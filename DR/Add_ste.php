<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');
if (strlen($_SESSION['sid']==0)) {
  header('location:logout.php');
} else{
  if(isset($_POST['submit']))
  {
    
  
   
    $no = trim(mysqli_real_escape_string($con, $_POST['no']));
    $ger = trim(mysqli_real_escape_string($con, $_POST['ger']));
    $sec = trim(mysqli_real_escape_string($con, $_POST['sec']));
    $fix = trim(mysqli_real_escape_string($con, $_POST['fix']));
    $adresse = trim(mysqli_real_escape_string($con, $_POST['adresse']));
    $tel = trim(mysqli_real_escape_string($con, $_POST['tel']));
    $email = trim(mysqli_real_escape_string($con, $_POST['email']));
    
    $Permission = "super user";
    
   
    $stmt = $con->prepare("INSERT INTO ste (raison_sociale, adresse, secteur, gerent, email, tel, fix) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssss", $no, $adresse, $sec, $ger, $email, $tel, $fix);
    
    if ($stmt->execute()) {
        echo "<script>alert('La société a été ajoutée.');</script>";
        echo "<script>window.location.href = 'List_ste.php'</script>";
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
                  <li class="breadcrumb-item"><a href="List_ste.php">Liste</a></li>
                  <li class="breadcrumb-item active">Ajouter une société</li>
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
                    <h3 class="card-title">Ajouter une société</h3>
                  </div>
                  <!-- /.card-header -->
                  <!-- form start -->
                  <form role="form" method="post" enctype="multipart/form-data">
                    <div class="card-body">
                      <span style="color: brown"><h5>Détails société</h5></span>
                      <hr>
                      <div class="row">
                    
                        <div class="form-group col-md-3">
                          <label for="names">Nom</label>
                          <input type="text" class="form-control" id="names" name="no" placeholder="Nom" required>
                        </div>

                        <div class="form-group col-md-3">
                          <label for="prenom">Secteur</label>
                          <input type="text" class="form-control" id="prenom" name="sec" placeholder="...." required>
                        </div>
                        <div class="form-group col-md-3">
                          <label for="age">Gerent</label>
                          <input type="text" class="form-control" id="age" name="ger" placeholder="....." >
                        </div>
                     
                        <div class="form-group col-md-3">
                          <label for="adresse">Adresse</label>
                          <input type="text" class="form-control" id="adresse" name="adresse" placeholder="adresse" >
                        </div>

                        <div class="form-group col-md-3">
                          <label for="email">Email</label>
                          <input type="text" class="form-control" id="email" name="email" placeholder="email" required>
                        </div>
                        
                        
                        <div class="form-group col-md-3">
                          <label for="num">Tel</label>
                          <input type="text" class="form-control" id="num" name="tel" placeholder="Tel">
                        </div>
                      
                        <div class="form-group col-md-3">
                          <label for="num">Fix</label>
                          <input type="text" class="form-control" id="num" name="fix" placeholder="Fix" >
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
  </body>
  </html>
  <?php
}?>
