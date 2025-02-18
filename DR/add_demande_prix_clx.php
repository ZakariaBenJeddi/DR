<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');
if (strlen($_SESSION['sid']==0)) {
  header('location:logout.php');
} else{
  if(isset($_POST['submit']))
  {
    $raison_sociale = trim(mysqli_real_escape_string($con, $_POST['four']));
    $montant = trim(mysqli_real_escape_string($con, $_POST['m']));
    $besoin = $_FILES['besoin']['name'];
    $rr=mysqli_query($con,"SELECT idfour FROM fournisseur WHERE raison_sociale='$raison_sociale'");
    $bow=mysqli_fetch_assoc($rr);
    $Permission = "super user";
    $emp='besoins/';
    $rrr=mysqli_query($con,"select idcomplex from complexe1 where id=$_SESSION[sid]");
    $kow=mysqli_fetch_assoc($rrr);
    $devis=1;
    $bcmd=0;
    $facture=0;
    $payer=0;
    $chemin=$emp.basename($_FILES['besoin']['name']);
    $stmt = $con->prepare("INSERT INTO demande_prix_complex (idfour,besoin,dated,montant,deviis,bcmd,facture,payer,idcomplex)  VALUES (?, ?,  NOW(), ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("isiiiiii", $bow['idfour'],$besoin,$montant,$devis,$bcmd,$facture,$payer,$kow['idcomplex']);
    if ($stmt->execute()) {
      if(file_exists($chemin)){
      }
      else{
          move_uploaded_file($_FILES['besoin']['tmp_name'],$chemin);
      }
        echo "<script>alert(L'etablissement a été ajoutée.);</script>";
        echo "<script>window.location.href = 'liste_demande_prix_clx.php'</script>";
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
                  <li class="breadcrumb-item"><a href="liste_demande_prix_clx.php">Liste</a></li>
                  <li class="breadcrumb-item active">Ajouter un demande</li>
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
                    <h3 class="card-title">Ajouter un demand</h3>
                  </div>
                  <!-- /.card-header -->
                  <!-- form start -->
                  <form role="form" method="post" enctype="multipart/form-data">
                    <div class="card-body">
                      <span style="color: brown"><h5>Détails demande</h5></span>
                      <hr>
                      <div class="row">
                    
                        <div class="form-group col-md-3">
                          <label for="code">Fournisseurs</label>
                          <select class="form-control" name="four" id="four">
                            <?php
                             $sql="SELECT raison_sociale from fournisseur";
                             $ret=mysqli_query($con,$sql);
                             $op='';
                             while ($row2=mysqli_fetch_array($ret)){
                               $op.="<option>$row2[raison_sociale]</option>";
                             }
                             echo $op;
                           ?>
                             ?>
                          </select>
                        </div>
                        <div class="form-group col-md-3">
                          <label for="nom">Besoin</label>
                          <input type="file" class="form-control" id="besoin" name="besoin" placeholder="...." required>
                        </div>
                        <div class="form-group col-md-3">
                          &nbsp;<b><label for="email">Montant</label></b>
                          <input type="number" class="form-control" id="m" name="m" placeholder="....." required>
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
