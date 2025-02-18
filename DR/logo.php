<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');
if (strlen($_SESSION['sid']==0)) {
 header('location:logout.php');
} 
if(isset($_POST['submit']))
  {
    $eid=$_SESSION['sid'];
    $userimage=$_FILES["userimage"]["name"];
    if(isset($_SESSION['logo'])){
        unlink('logo/'.$_SESSION['logo']);
  
      }
    move_uploaded_file($_FILES["userimage"]["tmp_name"],"logo/".$_FILES["userimage"]["name"]);
    $sql="update setting_site set logo=:userimage where id=1";
    $query=$dbh->prepare($sql);
    $query->bindParam(':userimage',$userimage,PDO::PARAM_STR);
    $query->execute();
    if ($query->execute()) {
      echo '<script>alert("mis à jour avec succès")</script>';
      echo "<script>window.location.href ='logo.php'</script>";
    }else{
      echo '<script>alert("Quelque chose c est mal passé. Merci d essayer plus tard")</script>';
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
    <?php @include("includes/sidebar.php"); ?>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
      <!-- Content Header (Page header) -->
      <section class="content-header">
        <div class="container-fluid">
          <div class="row mb-2">
            <div class="col-sm-6">
              <h1>modifier logo</h1>
            </div>
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="dashboard.php">Accueil</a></li>
                <li class="breadcrumb-item active">Gérer le site</li>
              </ol>
            </div>
          </div>
        </div><!-- /.container-fluid -->
      </section>

      <!-- Main content -->
      <section class="content">
        <div class="container-fluid">
   
      <div class="col-lg-12">
        <div class="card mb-4">
         
          <div class="card-body">
            <form method="post"enctype="multipart/form-data">
              <?php
        
              $sql="SELECT * from setting_site where id=1 ";                                    
              $query = $dbh -> prepare($sql);
              $query->execute();
              $results=$query->fetchAll(PDO::FETCH_OBJ);

              $cnt=1;
              if($query->rowCount() > 0)
              {
                foreach($results as $row)
                {    
                    $_SESSION['logo']=$row->logo;
                  ?>
                  <!-- <div class="control-group">
                    <label class="control-label" for="basicinput">Nom</label>
                    <div  class="col-6">
                      <input type="text"   class="form-control" name="productName"  readonly value="<?php  //echo $row->name;?>&nbsp;<?php  //echo $row->lastname;?>" class="span6 tip" required>
                    </div>
                  </div> -->
                  <br>
                  <div class="control-group"> 
                    <label class="control-label" for="basicinput">logo actuelle</label>
                    <div class="controls">
                  
                        <img class="" src="logo/<?php echo $row->logo;?>" alt="" width="100" height="100">
                    
                    </div>
                  </div>
                  <br>
                  <div class="form-group col-md-6">
                    <label>Nouveaux logo</label>
                    <input type="file" name="userimage" id="userimage" class="file-upload-default">
                  </div>
                  <?php 
                }
              } ?>
              <div class="form-group row">
                <div class="col-12">
                  <button type="submit" class="btn btn-primary " name="submit">
                    <i class="fa fa-plus "></i> Mettre à jour
                  </button>
                </div>
              </div>
            </form>
          </div>
        </div>
   

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

  <!-- ./wrapper -->
  <?php @include("includes/foot.php"); ?>
 
 
</body>
</html>
