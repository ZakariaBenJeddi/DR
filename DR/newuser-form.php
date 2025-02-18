<?php 
session_start();
error_reporting(0);
include('includes/dbconnection.php');
if(isset($_POST['submit']))
{
  $password1=($_POST['password']); 
  $password2=($_POST['password1']); 

  if($password1 != $password2) {
    echo "<script>alert('Le mot de passe et le champ de confirmation du mot de passe ne correspondent pas  !!');</script>";
  }else
  {
    $name=$_POST['name'];
    $lastname=$_POST['lastname'];
    $username=$_POST['username'];
    $email=$_POST['email'];
    $permission=$_POST['permission'];
    $sex=$_POST['sex'];
    $mobile=$_POST['mobile'];
    $password=md5($_POST['password']);
    $status=1; 
    $sql="INSERT INTO  tblusers(name,username,email,password,status,mobile,sex,lastname,permission) VALUES(:name,:username,:email,:password,:status,:mobile,:sex,:lastname,:permission)";
    $query = $dbh->prepare($sql);
    $query->bindParam(':name',$name,PDO::PARAM_STR);
    $query->bindParam(':lastname',$lastname,PDO::PARAM_STR);
    $query->bindParam(':sex',$sex,PDO::PARAM_STR);
    $query->bindParam(':mobile',$mobile,PDO::PARAM_STR);
    $query->bindParam(':status',$status,PDO::PARAM_STR);
    $query->bindParam(':username',$username,PDO::PARAM_STR);
    $query->bindParam(':email',$email,PDO::PARAM_STR);
    $query->bindParam(':permission',$permission,PDO::PARAM_STR);
    $query->bindParam(':password',$password,PDO::PARAM_STR);
    $query->execute();
    $lastInsertId = $dbh->lastInsertId();
    if($lastInsertId)
    {
      echo "<script>alert('Enregistré avec succès');</script>";
       echo "<script>window.location.href ='userregister.php'</script>";
      
    }
    else 
    {
      echo "<script>alert('Quelque chose s'est mal passé. Veuillez réessayer');</script>";
    }
  }
}
?>

<form role="form" id=""  method="post" enctype="multipart/form-data" class="form-horizontal"  >
  <div class="card-body">
    <div class="row">
      <div class="form-group col-md-4">
        <label for="feFirstName">Prénom</label>
        <input type="text" name="name" class="form-control"  placeholder="Prénom" value="" required>
      </div>
      <div class="form-group col-md-4">
        <label for="feLastName">Nom</label>
        <input type="text" name="lastname" class="form-control"  placeholder="Nom" value="" required>
      </div>
      <div class="form-group col-md-4">
        <label for="feLastName">Username</label>
        <input type="text" name="username" class="form-control"  placeholder="Username" value="" required>
      </div>
    </div>
    <div class="row">
    <div class="form-group col-md-4">
        <label for="Permission">Permission</label>
        <input type="text" name="permission" class="form-control"  placeholder="Permission" value="admin" readonly required>
      </div>
      
      <div class="form-group col-4">
        <label for="exampleInputEmail1">Email addresse</label>
        <input type="email" name="email" class="form-control"  placeholder="Enter email" value="" required>
      </div>
      <div class="form-group col-4">
        <label for="exampleInputEmail1">Téléphone</label>
        <input type="text" name="mobile" class="form-control"  placeholder="Entrer tel" value="" required>
      </div>
    </div>
    
    <div class="row">
      <div class="form-group col-md-4">
        <label for="feFirstName">mot de passe</label>
        <input type="password" name="password" class="form-control" placeholder="mot de passe" value="" required>
      </div>
      <div class="form-group col-md-4">
        <label for="feLastName">confirmer le mot de passe</label>
        <input type="password" name="password1" class="form-control" placeholder="confirmer mdp" value="" required>
      </div>
      <div class="form-group col-4">
        <label class="" for="register1-email">Genre:</label>
        <select name="sex" class="form-control" required>
          <option value="">Select Genre</option>
          <option value="Male">Homme</option>
          <option value="Female">Femme</option>
        </select>
      </div>
    </div>
   
  </div>  
  <!-- /.card-body -->
  <div class="modal-footer text-right">
    <button type="button" class="btn btn-default" data-dismiss="modal">Annuler</button>
    <button type="submit" name="submit" class="btn btn-primary">Soumettre</button>
  </div>
</form>