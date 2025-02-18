<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');
if (strlen($_SESSION['sid']==0)) {
  header('location:logout.php');
} else{
  if(isset($_POST['submit']))
  {
    $code = trim(mysqli_real_escape_string($con, $_POST['code']));
    $nom = trim(mysqli_real_escape_string($con, $_POST['nom']));
    $adress = trim(mysqli_real_escape_string($con, $_POST['adress']));
    $email = trim(mysqli_real_escape_string($con, $_POST['email']));
    $tel = trim(mysqli_real_escape_string($con, $_POST['tel']));
    $idcomplex=mysqli_query($con,"SELECT idcomplex FROM complexe1 WHERE id='$_SESSION[sid]'");
    $bow=mysqli_fetch_assoc($idcomplex);
    $Permission = "super user";
    $img=$_FILES['img']['name'];
    $emp='etablissement_img/';
    $chemin=$emp.basename($_FILES['img']['name']);
    $stmt = $con->prepare("INSERT INTO etablissement (code, nomE, adress, email, tel, idcomplex ,imgetablissement)  VALUES (?, ?, ?, ?, ?,? ,?)");
    $stmt->bind_param("sssssis", $code, $nom, $adress, $email, $tel, $bow['idcomplex'] ,$img);
    if ($stmt->execute()) {
      if(file_exists($chemin)){
      }
      else{
          move_uploaded_file($_FILES['img']['tmp_name'],$chemin);
      }
        echo "<script>alert(L'etablissement a été ajoutée.);</script>";
        echo "<script>window.location.href = 'list_etablissement.php'</script>";
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
     if($_SESSION['service']=='achat'){
    
      @include("includes/sidebarachat.php");
     }
     if($_SESSION['service']=='compta'){
    
      @include("includes/sidebarcompta.php");
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
                  <li class="breadcrumb-item"><a href="list_etablissement.php">Liste</a></li>
                  <li class="breadcrumb-item active">Ajouter une etablissement</li>
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
                    <h3 class="card-title">Ajouter une etablissement</h3>
                  </div>
                  <!-- /.card-header -->
                  <!-- form start -->
                  <form role="form" method="post" enctype="multipart/form-data">
                    <div class="card-body">
                      <span style="color: brown"><h5>Détails etablissement</h5></span>
                      <hr>
                      <div class="row">
                    
                        <div class="form-group col-md-3">
                          <label for="code">Code</label>
                          <input type="text" class="form-control" id="code" name="code" placeholder="....." required>
                        </div>

                        <div class="form-group col-md-3">
                          <label for="nom">Nom</label>
                          <input type="text" class="form-control" id="nom" name="nom" placeholder="...." required>
                        </div>
                        <div class="form-group col-md-3">
                          <label for="adress">Adress</label>
                          <input type="text" class="form-control" id="adress" name="adress" placeholder="....." required>
                        </div>
                     
                        <div class="form-group col-md-3">
                          &nbsp;<b><label for="email">Email</label></b>
                          <input type="text" class="form-control" id="email" name="email" placeholder="....." required>
                        </div>

                        <div class="form-group col-md-3">
                          <label for="tel">Tel</label>
                          <input type="number" class="form-control" id="tel" name="tel" placeholder="....." required>
                        </div>
                        <div class="form-group col-md-3">
                          <label for="tel">Image</label>
                          <input type="file" class="form-control" id="img" name="img" placeholder="....." required>
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
