<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');
if (strlen($_SESSION['sid']==0)) {
  header('location:logout.php');
} else{
  if(isset($_POST['submit']))
  {
    $montant = (int)trim(mysqli_real_escape_string($con, $_POST['montant']));
    $etablissement = trim(mysqli_real_escape_string($con, $_POST['nomE']));
    $rubrique = trim(mysqli_real_escape_string($con, $_POST['rubrique']));
    $statut=1;
    $montantConsom=0;
    $ide="-1";
    $sql="SELECT nomE,ide from etablissement where idcomplex =(select idcomplex from complexe1 where id=$_SESSION[sid])";
    $query=mysqli_query($con,$sql);
    if(mysqli_num_rows($query)>0){
        while($row=mysqli_fetch_assoc($query)){
            if($row['nomE']==$etablissement){
                $ide=$row['ide'];
            }
        }
        if($ide=="-1"){
            echo "<script>alert('etablissement introuvable')</script>";
            echo "<script>window.location.href = 'list_budget_complex.php'</script>";
        }
        else{
             $Permission = "super user";
             $stmt = $con->prepare("INSERT INTO budgetcomplex (montant,ide, rubrique, dateres, statut, reste, montantConsom) VALUES (?, ?, ?, NOW(), ?, ?, ?)");
             $stmt->bind_param("iisiii", $montant, $ide, $rubrique, $statut, $montant,  $montantConsom);
             
             if ($stmt->execute()) {
                 echo "<script>alert('Le budget a été ajoutée.');</script>";
                 echo "<script>window.location.href = 'list_budget_complex.php'</script>";
             } else {
                 echo "<script>alert('Quelque chose a mal tourné. Veuillez réessayer.');</script>";
             }
            
        }

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
     if($_SESSION['service']=='achat_dr'){
        @include("includes/sidebarachat_dr.php");
      }
      if($_SESSION['service']=='achat_cmplx'){
        @include("includes/sidebarachat_cmplx.php");
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
                  <li class="breadcrumb-item"><a href="List_budget_complex.php">Liste</a></li>
                  <li class="breadcrumb-item active">Ajouter un budget</li>
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
                    <h3 class="card-title">Ajouter un budget</h3>
                  </div>
                  <!-- /.card-header -->
                  <!-- form start -->
                  <form role="form" method="post" enctype="multipart/form-data">
                    <div class="card-body">
                      <span style="color: brown"><h5>Détails budget</h5></span>
                      <hr>
                      <div class="row">
                    
                        <div class="form-group col-md-3">
                          <label for="names">Montant</label>
                          <input type="text" class="form-control" id="montant" name="montant" placeholder=".... " required>
                        </div>

                        <div class="form-group col-md-3">
                          <label for="prenom">Etablissement</label>
                          <input type="text" class="form-control" id="nomE" name="nomE" placeholder="...." required>
                        </div>
                        <div class="form-group col-md-3">
                          <label for="age">Rubrique</label>
                          <input type="text" class="form-control" id="rubrique" name="rubrique" placeholder="....." >
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
