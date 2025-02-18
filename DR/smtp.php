<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');
if (empty($_SESSION['sid'])) {
 header('location:logout.php');
} 
if(isset($_GET['del']))
{
  mysqli_query($con,"delete from slide where id = ".$_GET['id']);
  $_SESSION['delmsg']="slide supprimé  !!";
}

$resultt = mysqli_query($con,"SELECT * FROM contacte where id=1");
$roww = mysqli_fetch_array($resultt);

if(isset($_POST['publise'])){


    $titre1=mysqli_real_escape_string($con, $_POST['title']);
    $titre2=mysqli_real_escape_string($con, $_POST['title1']);
    $phone=intval($_POST['phone']);
    $email=$_POST['email'];
    $adresse= $_POST['adresse'];
    $localisation = mysqli_real_escape_string($con,$_POST['loc']);
    

   
    $facebook=$_POST['facebook'];
    $twitter=$_POST['twitter'];
    $instagram=$_POST['instagram'];
    $linkedin=$_POST['linkedin'];

    
   
    $insertdata = mysqli_query($con, "UPDATE contacte SET titre='$titre1', site_titre='$titre2', telephone=$phone, email='$email', facebook='$facebook', twitter='$twitter', instagram='$instagram', linkedin='$linkedin', adresse='$adresse', loc='$localisation' WHERE id=1");

    if($insertdata){
        echo "<script>
        alert('Modifié avec succésy');
        window.location.href = 'smtp.php'
        </script>";
    
        
    }
    
    if(!empty($_FILES['header_logo']['name'])){
        $header_logo=$_FILES['header_logo']['name']; 
        unlink("../assets/logo/".$roww['header_logo']);
        move_uploaded_file($_FILES["header_logo"]["tmp_name"], "../assets/logo/" .  $header_logo);
        $insertdata = mysqli_query($con,"UPDATE contacte SET logo='$header_logo' where id=1");
        echo "<script>alert('Modifié avec succésy');
        window.location.href = 'smtp.php'</script>";
   
    }
   
    
    
    }


if(isset($_POST['publise1'])){
    $port=$_POST['port'];
    $host=$_POST['host'];
    $smtp_email=$_POST['smtp_email'];
    $password=$_POST['password'];
  
    $updateData = mysqli_query($con,"UPDATE contacte SET port=$port,host='$host',email_smtp='$smtp_email',password='$password' where id=1");

    if($updateData){
        echo "<script>alert('Modifié avec succésy');
        window.location.href = 'smtp.php'</script>";
  
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
              <h1>Détails contactez nous</h1>
            </div>
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="dashboard.php">Accueil</a></li>
                <li class="breadcrumb-item active">Gérer contactez nous</li>
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
                  <h3 class="card-title">Gérer la partie contactez nous</h3>
                  
                </div>
                <!-- /.card-header -->
             
                <!--   end modal -->
               
        
                
                
                <div class="card-body mt-2 " >
                <div class="card card-info">
            <div class="card-header">
              <h3 class="card-title">Parametre du site</h3>

              <div class="card-tools">
                <button type="button" class="btn btn-tool" data-card-widget="collapse" data-toggle="tooltip" title="Collapse">
                  <i class="fas fa-minus"></i></button>
              </div>
            </div>
            <br>
            <div class="card-body p-0">
            <div class="card-body p-0">
    <section class="content">
 <form action="" method="post" enctype="multipart/form-data">
      <div class="row">
        <div class="col-md-6">
	
          <div class="card card-outline">
			<div class="card-header">
             <div class="form-group">
                  <label>Nom du institut</label>
                  <input type="text" name="title" placeholder="Entrer ..." value="<?php echo $roww["titre"]; ?>" class="form-control">
                </div>
            </div>
            <div class="card-header">
             <div class="form-group">
                  <label>Nom du site</label>
                  <input type="text" name="title1" placeholder="Entrer ..." value="<?php echo $roww["site_titre"]; ?>" class="form-control">
                </div>
            </div>
            <div class="card-header">
             <div class="form-group">
                  <label>Logo du Site</label>
                 <input type="file" class="form-control" name="header_logo">
                </div>
            </div>
            	<div class="card-header">
             <div class="form-group">
                  <label>Phone</label>
                 <input name="phone" value="0<?php echo $roww["telephone"]; ?>" type="number" class="form-control" placeholder="Entrer ...">
                </div>
            </div>
            	<div class="card-header">
             <div class="form-group">
                  <label>Email</label>
                 <input name="email" value="<?php echo $roww["email"]; ?>" type="text" class="form-control" placeholder="Entrer ...">
                </div>
            </div>
            
            <div class="card-header">
             <div class="form-group">
                  <label>adresse</label>
                 <input name="adresse" value="<?php echo $roww["adresse"]; ?>" type="text" class="form-control" placeholder="Entrer ...">
                </div>
            </div>

            <div class="card-header">
             <div class="form-group">
                  <label>localisation</label>
                 <input name="loc" value="<?php echo $roww["loc"]; ?>" type="text" class="form-control" placeholder="Entrer ...">
                </div>
            </div>

          </div>
		 
        </div>
        
        <div class="col-md-6">
          <div class="card card-outline ">
			<div class="card-header">
             <div class="form-group">
                  <label>Facebook</label>
                 <input name="facebook" value="<?php echo $roww["facebook"]; ?>" type="text" class="form-control" placeholder="URL">
                </div>
            </div>
            	<div class="card-header">
             <div class="form-group">
                  <label>Twitter</label>
                 <input name="twitter" value="<?php echo $roww["twitter"]; ?>" type="text" class="form-control" placeholder="URL">
                </div>
            </div>
            
            
            	<div class="card-header">
             <div class="form-group">
                  <label>Instagram</label>
                 <input name="instagram" value="<?php echo $roww["instagram"]; ?>" type="text" class="form-control" placeholder="URL">
                </div>
            </div>
            	
            <div class="card-header">
             <div class="form-group">
                  <label>linkedin</label>
                 <input name="linkedin" value="<?php echo $roww["linkedin"]; ?>" type="text" class="form-control" placeholder="URL">
                </div>
            </div>
            

          </div>
		 
        </div>
        
         <div class="col-md-12">
         
        	<div class="card-header">
             <div class="form-group">
					<div class="row">
                    <div class="col-sm-12">
                      <div class="form-group">
				<center><input type="submit" name="publise" class="btn btn-success btn-lg" value="Publier"/></center>
                      </div>
                    </div>
                  </div>
                </div>
            </div>
          
          </div>
        
      </div>
       </form>
    </section>
    </div>
          </div>
  </div>
    <div class="col-md-12">
          <div class="card card-info collapsed-card">
            <div class="card-header">
              <h3 class="card-title">Smtp email</h3>

              <div class="card-tools">
                <button type="button" class="btn btn-tool" data-card-widget="collapse" data-toggle="tooltip" title="Collapse">
                  <i class="fas fa-plus"></i></button>
              </div>
            </div>
            <br>
            <div class="card-body p-0">
            <section class="content">
            <form  method="post" enctype="multipart/form-data">
              <div class="row">
              <div class="col-md-6">
                
                <div class="card card-outline">
                  <div class="card-header">
                    <div class="form-group">
                  <label>Host</label>
                 <input name="host" value="<?php echo $roww["host"]; ?>" type="text" class="form-control" placeholder="Host">
                </div>
            </div>
          
            
            <div class="card-header">
             <div class="form-group">
                  <label>Port</label>
                 <input name="port" value="<?php echo $roww["port"]; ?>" type="number" class="form-control" placeholder="Port">
                </div>
            </div>
           

              </div>
              </div>
      
            <div class="col-md-6">
                
             <div class="card card-outline">
            <div class="card-header">
             <div class="form-group">
                  <label>email</label>
                 <input name="smtp_email" value="<?php echo $roww["email_smtp"]; ?>" type="email" class="form-control" placeholder="Email">
                </div>
            </div>

            <div class="card-header">
             <div class="form-group">
                  <label>Password</label>
                 <input name="password" value="<?php echo $roww["password"]; ?>" type="text" class="form-control" placeholder="Password">
                </div>
            </div>
            </div>
            </div>

            <div class="col-md-12">
         
         <div class="card-header">
            <div class="form-group">
         <div class="row">
                   <div class="col-sm-12">
                     <div class="form-group">
       <center><input type="submit" name="publise1" class="btn btn-success btn-lg" value="Publier"/></center>
                     </div>
                   </div>
                 </div>
               </div>
           </div>
         
         </div>
        </div>
            </form>
            </section>
            </div>
          </div>
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

  <!-- ./wrapper -->
  <?php @include("includes/foot.php"); ?>
  
</body>
</html>
