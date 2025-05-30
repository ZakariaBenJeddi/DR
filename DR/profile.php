<?php 
session_start();
error_reporting(0);
$cmplx="";
include('includes/dbconnection.php');
if (strlen($_SESSION['sid']==0)) {
  header('location:logout.php');
} else{
  $sql="select nomcomplex from complexe1 where id=$_SESSION[sid]";
  $query=mysqli_query($con,$sql);
  $row=mysqli_fetch_array($query);
  $cmplx=$row['nomcomplex'];
  if(isset($_POST['submit']))
  {
    $eid=$_SESSION['sid'];
    $email=$_POST['email'];
    $mobile=$_POST['mobile'];
    $name=$_POST['firstname'];
    $lastname=$_POST['lastname'];
    $username=$_POST['username'];
    $nomcomplex=$_POST['nomcomplex'];
    if($_SESSION['service']=='achat_cmplx'){
    $sql = "UPDATE complexe1 SET nomcomplex = :nomcomplex WHERE id = :siid";
    $query = $dbh->prepare($sql);
    $query->bindParam(':nomcomplex', $nomcomplex, PDO::PARAM_STR);
    $query->bindParam(':siid', $_SESSION['sid'], PDO::PARAM_INT);
    $query->execute();
    }
    $sql="update tblusers set name=:name,username=:username,mobile=:mobile,email=:email,lastname=:lastname where id=:eid";
    $query=$dbh->prepare($sql);
    $query->bindParam(':name',$name,PDO::PARAM_STR);
    $query->bindParam(':lastname',$lastname,PDO::PARAM_STR);
    $query->bindParam(':username',$username,PDO::PARAM_STR);
    $query->bindParam(':email',$email,PDO::PARAM_STR);
    $query->bindParam(':mobile',$mobile,PDO::PARAM_STR);
    $query->bindParam(':eid',$eid,PDO::PARAM_STR);
    $query->execute();
    echo '<script>alert("mis à jour avec succès")</script>';
    echo "<script>window.location.href ='profile.php'</script>";
  }
  ?>

  <?php @include("includes/head.php"); ?>
  <body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">
     <!-- Navbar -->
     <?php @include("includes/header.php"); ?>
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
     if($_SESSION['service']=='orientation'){
    
      @include("includes/sidebar_orientation.php");
     }
     
     ?>
     <!-- /.sidebar and menu -->

     <!-- Content Wrapper. Contains page content -->
     <div class="content-wrapper">
      <br>
      <div class="col-lg-12">
        <div class="card mb-4">
          <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">Mettre à jour le profil utilisateur</h6>
          </div>
          <div class="card-body">
            <form method="post">
              <?php
              $eid=$_SESSION['sid'];
              $sql="SELECT * from tblusers   where id=:eid ";                                    
              $query = $dbh -> prepare($sql);
              $query-> bindParam(':eid', $eid, PDO::PARAM_STR);
              $query->execute();
              $results=$query->fetchAll(PDO::FETCH_OBJ);

              $cnt=1;
              if($query->rowCount() > 0)
              {
                foreach($results as $row)
                {    
                  ?>
                  <div class="container rounded bg-white mt-5">
                    <div class="row">
                      <div class="col-md-4 border-right">
                        <div class="d-flex flex-column align-items-center text-center p-3 py-5">
                         <?php 
                         if($row->userimage=="avatar15.jpg"){ ?>
                          <img class="rounded-circle mt-5" src="staff_images/avatar15.jpg"  width="90">
                          <?php 
                        } else { ?>
                          <img class="rounded-circle mt-5"  src="staff_images/<?php  echo $row->userimage;?>" width="90">
                          <?php 
                        } ?><span class="font-weight-bold"><?php  echo $row->name;?> <?php  echo $row->lastname;?></span><span class="text-black-50"><?php  echo $row->email;?></span><span><?php  echo $row->mobilenumber;?></span>
                        <div class="mt-3">
                          <a href="update_userimage.php?id=<?php echo $id;?>">Mettre à jour l'image</a>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-8">
                      <div class="p-3 py-5">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                          <div class="d-flex flex-row align-items-center back"><i class="fa fa-long-arrow-left mr-1 mb-1"></i>
                          </div>
                          <h6 class="text-right">Mettre à jour le profil</h6>
                        </div>
                        <div class="row mt-2">
                          <div class="col-md-6"><input type="text" class="form-control" name="firstname" value="<?php echo $row->name; ?>" required='true'></div>
                          <div class="col-md-6"><input type="text" class="form-control" value="<?php echo $row->lastname; ?> " name="lastname" required></div>
                        </div>
                        <div class="row mt-3">
                          <div class="col-md-6"><input type="text" class="form-control" name="email" value="<?php  echo $row->email;?>" required></div>
                          <div class="col-md-6"><input type="text" class="form-control" value="0<?php echo $row->mobile; ?>" name="mobile" required></div>
                        </div>
                        <div class="row mt-3">
                          <div class="col-md-6">
                            <label class="form-group">Nom Utilisateur</label>
                            <input type="text" class="form-control" name="username" value="<?php  echo $row->username;?>" required></div>
                            <div class="col-md-6">
                              <label class="form-group">Permission</label>
                              <input type="text" class="form-control"name="permission" value="<?php  echo $row->permission;?>" readonly="true"></div>
                        </div>
                        <?php if($_SESSION['service']=='achat_cmplx'){?>
                        <div class="row mt-3">
                        <div class="col-md-6 mx-auto">
                            <label class="form-group">Nom Complexe:</label>
                            <input type="text" class="form-control" name="nomcomplex" value="<?php  echo $cmplx;?>" required></div>
                        </div>
                        <?php }?>
                           <div class="mt-5 text-right"><button class="btn btn-primary profile-button" type="submit" name="submit">Mettre à jour</button></div>
                         </div>
                       </div>
                     </div>
                   </div>
                   <?php 
                 }
               } ?>
             </form>
           </div>
         </div>
       </div>

       <!-- /.content-header -->
     </div>
     <!-- /.content-wrapper -->
     <?php @include("includes/foot.php"); ?>
   </body>
   </html>
   <?php 
 } ?>
