<?php 
session_start();
error_reporting(0);
include('includes/dbconnection.php');
if (empty($_SESSION['sid'])) 
{
  header('location:logout.php');
} else
{ 
  if(isset($_POST['submit']))
  {
    $adminid=$_SESSION['sid'];
    $cpassword=($_POST['password']);
    $newpassword=($_POST['password1']);
    $sql ="SELECT id FROM tblusers WHERE id=:adminid and password=:cpassword";
    $query= $dbh -> prepare($sql);
    $query-> bindParam(':adminid', $adminid, PDO::PARAM_STR);
    $query-> bindParam(':cpassword', $cpassword, PDO::PARAM_STR);
    $query-> execute();
    $results = $query -> fetchAll(PDO::FETCH_OBJ);

    if($query -> rowCount() > 0)
    {
      $con="update tblusers set Password=:newpassword where id=:adminid";
      $chngpwd1 = $dbh->prepare($con);
      $chngpwd1-> bindParam(':adminid', $adminid, PDO::PARAM_STR);
      $chngpwd1-> bindParam(':newpassword', $newpassword, PDO::PARAM_STR);
      $chngpwd1->execute();

      echo '<script>alert("Votre mot de passe a bien été modifié")</script>';
      echo "<script>window.location.href ='profile.php'</script>";
    } else {
      echo '<script>alert("Votre mot de passe actuel est erroné")</script>';

    }
  }
  ?>

  <?php @include("includes/head.php"); ?>
  <body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">
      <!-- Navbar -->
      
      <!-- /.navbar -->
      <!-- Side bar and Menu -->
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
      <!-- /.sidebar and menu -->
      
      <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">
        <br>
        <div class="card">
          <div class="col-md-10">
            <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title">Changer le mot de passe</h3>
              </div>
              <div class="card-body">
                <!-- Date -->

                <form role="form" id=""  method="post" enctype="multipart/form-data" class="form-horizontal">

                  <div class="card-body">
                    <div class="form-group  ">
                      <label for="exampleInputPassword1">Ancien mot de passe</label>
                      <input type="password" name="password" class="form-control" id="exampleInputPassword1"  required>
                    </div>
                    <div class="form-group  ">
                      <label for="exampleInputPassword1">Nouveau mot de passe</label>
                      <input type="password" name="password1"  class="form-control" id="exampleInputPassword1" required>
                    </div>
                    <div class="form-group ">
                      <label for="exampleInputPassword1">Confirmez le mot de passe</label>
                      <input type="password" name="password2" class="form-control" id="exampleInputPassword1"  >
                    </div>
                  </div>
                </div>
                <div class="modal-footer text-right">
                  <button type="submit" name="submit" class="btn btn-primary">Soumettre</button>
                </div>

              </form> 

            </div>

            <!-- /.card-body -->
          </div>
          <!-- /.card -->
        </div><!-- /.container-fluid -->
      </div>
      <!-- /.content-header -->
    </div>
    <!-- /.content-wrapper -->
    <?php @include("includes/foot.php"); ?>
  </body>
  </html>
  <?php
} ?>
