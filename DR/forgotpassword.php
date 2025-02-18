<?php
use PHPMailer\PHPMailer\PHPMailer;

session_start();
error_reporting(0);
include('includes/dbconnection.php');
if(isset($_POST['login']))
{

 
    $email=$_POST['email'];
    $sql ="SELECT email FROM tblusers WHERE email=:email";
    $query= $dbh -> prepare($sql);
    $query-> bindParam(':email', $email, PDO::PARAM_STR);
    $query-> execute();
    $results = $query -> fetchAll(PDO::FETCH_OBJ);
    if($query -> rowCount() > 0)
    {
      function generateRandomPassword($length = 8) {
        $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $password = '';
    
        for ($i = 0; $i < $length; $i++) {
            $index = rand(0, strlen($characters) - 1);
            $password .= $characters[$index];
        }
    
        return $password;
    }
    
      $randomPassword = generateRandomPassword();
      
      $newpassword=password_hash($randomPassword, PASSWORD_DEFAULT);
      $upd="update tblusers set password=:newpassword where email=:email";
      $chngpwd1 = $dbh->prepare($upd);
      $chngpwd1-> bindParam(':email', $email, PDO::PARAM_STR);
      $chngpwd1-> bindParam(':newpassword', $newpassword, PDO::PARAM_STR);
      $chngpwd1->execute();

      require '../phpmailer/vendor/autoload.php';
      $resultt = mysqli_query($con,"SELECT port,host,email_smtp,password,email
      FROM contacte where id=1");
      $roww = mysqli_fetch_array($resultt);

      $mail = new PHPMailer;
      $mail->isSMTP();
      $mail->SMTPDebug = 2;
      $mail->Host = $roww['host'];
      $mail->Port = $roww['port'];
      $mail->SMTPSecure = 'ssl';
      $mail->SMTPAuth = true;
      $mail->Username = $roww['email_smtp'];
      $mail->Password = $roww['password'];
      $mail->subject = "Réinitialisation du mot de passe";
      $mail->isHTML(true);
      $mail->addAddress($email);
      
      $mail->Body = <<<EOT
      Cher utilisateur,

      Nous avons reçu une demande de réinitialisation du mot de passe pour votre compte. Veuillez trouver ci-dessous votre nouveau mot de passe :
      <br/>
      Nouveau mot de passe : <b>{$randomPassword}</b>
      <br/>

      Nous vous recommandons de vous connecter à votre compte en utilisant ce nouveau mot de passe. Après vous être connecté, nous vous encourageons à le modifier dans les paramètres de votre compte pour des raisons de sécurité.
      <br/>

      L'équipe de <b> ISGI Marrakech</b>
EOT;

      if (!$mail->send()) {
          $msg = 'Désolé, Veuillez réessayer plus tard..';
          $mail->ErrorInfo;
      } else {
          
          header("location:./index.php");
          
      }

      echo "<script>alert('le nouveau mot de passe a été envoyer a l'email '".$email.");</script>";
      echo "<script>window.location.href = 'index.php'</script>";
    }
    else {
      echo "<script>alert('L\'identifiant de messagerie n\'est pas valide');</script>"; 
    }
  
}

?>

<?php @include("includes/head.php"); ?>
<body class="hold-transition login-page">
  <!-- Logo box -->
  <div class="login-box">  
    <!-- /.login-logo -->
    <div class="card">

      <div class="card-body login-card-body">
        
        <div class="login-logo">
          <center>
          <?php
    $s=mysqli_query($con,"select * from contacte where id=1");
    $cnt=1;
    while($row=mysqli_fetch_array($s))
    {
      echo '
      <img src="../assets/logo/'.$row['logo'].'" width="150" height="130" class="user-image" alt="User Image"/>


      ';

    }
  ?>
          </center>
        </div>
        <p class="login-box-msg"><strong style="color: blue">Ne vous inquiétez pas, nous vous avons récupéré</strong></p>
        <form action="" method="post">
          <div class="input-group mb-3">
            <input type="text" name="email" class="form-control" placeholder="Email" required >
            <div class="input-group-append">
              <div class="input-group-text">
                <span class="fas fa-envelope"></span>
              </div>
            </div>
          </div>
          
         
          <div class="row">
            <div class="col-4">
              <button type="submit" name="login" class="btn btn-primary btn-block" data-toggle="modal" data-taget="#modal-default">Réstaurer</button>
            </div>
            <!-- /.col -->
          </div>
        </form>
        <br>
        <p class="mb-1">
          <a href="index.php">Se connecter</a>
        </p>
      </div>
      <!-- /.login-card-body -->
    </div>
  </div>
  <?php @include("includes/foot.php"); ?>
</body>
</html>