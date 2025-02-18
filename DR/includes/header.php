<?php
session_start();
error_reporting(0);
include('dbconnection.php');
include('head.php');
if (strlen($_SESSION['sid']==0)) {
  header('location:logout.php');
}
$nbr = 0;
$badge = "";
if($_SESSION['service']=="achat_dr"){
  $rec = $dbh->query("SELECT * FROM rest_payer WHERE facture=1 AND DATEDIFF(date_delai,CURDATE()) <= 10");
if ($rec->rowCount()>0) {
  $nbr = $rec->rowCount();
}
}
if($_SESSION['service']=="achat_cmplx"){
  $rec = $dbh->query("SELECT * FROM demande_prix_complex WHERE facture=1 AND payer=0 AND DATEDIFF(NOW(), jour_delai) >= 50");
if ($rec->rowCount()>0) {
  $nbr = $rec->rowCount();
}
}
?>
<!-- Navbar -->
<nav class="main-header navbar navbar-expand navbar-white navbar-light">
  <!-- Left navbar links -->
  <ul class="navbar-nav">
    <li class="nav-item">
      <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
    </li>
    <li class="nav-item d-none d-sm-inline-block">
      <a href="#" class="nav-link">Accueil</a>
    </li>
  </ul>

  <!-- Right navbar links -->
  <ul class="navbar-nav ml-auto">
  <li class="nav-item dropdown">
    <a class="nav-link dropdown-toggle" href="#" id="notification-link" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
      <?php if ($nbr == 0)  {$badge='';  }else{ $badge = "<span class='position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger'>+$nbr</span>" ;}?>
        <i class="fas fa-bell"></i><?php echo $badge;?>
        <span id="notification-count" class="notification-counter">2</span>
    </a>
    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="notification-link" id="noti">
  <ul id="notification-list">
    <!-- Les notifications seront ajoutées ici -->
  </ul>
  <div class="dropdown-divider"></div>
  <p class="nott"><a href=<?php if($_SESSION['service']=="achat_dr"){echo "./notification.php";} 
  if($_SESSION['service']=="achat_cmplx"){
    echo "./notification_clx.php";
  }?>>View All Notifications</a></p>
  <!-- <button  class="btn btn-primary btn-xs nott" title="click for edit">View All Notifications</button> -->

</div>

</li>

    <li class="nav-item">
      <a class="nav-link" data-toggle="dropdown" href="#"><i class="fas fa-th-large"></i> </a>
      <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
        <div class="dropdown-divider"></div>

        <a href="profile.php" class="dropdown-item">
          <i class="fas fa-envelope mr-2"></i> profil
        </a>
        <div class="dropdown-divider"></div>
        <a href="changepassword.php" class="dropdown-item">
          <i class="fas fa-users mr-2"></i> paramètres 
        </a>
        <div class="dropdown-divider"></div>
        <a href="logout.php" class="dropdown-item">
          <i class="fas fa-file mr-2"></i> Se déconnecter
 
        </a>
      </div>
    </li>
   
  </ul>
</nav>

<div id="not" class="modal fade">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Détails notification</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="hii">
                <?php include("view_noti.php"); ?>
            </div>
            <div class="modal-footer ">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Annuler</button>
                      </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->
</div>

<style>

    .notification-counter {
        color: blue;
        padding: 2px 4px;
        border-radius: 50%;
        font-size: 18px;
        position: absolute;
        top: -30px;
        right: 40px;
    }
    ul{
      list-style-type: none;
      padding-left:0px;
    }
    li{
      margin-left:0px;
    }

    .nott {
    text-align: center;
    cursor: pointer;
    color:blue;
  }

  .p {
    text-align: center;
    cursor: pointer;
  }
</style>

<script>
// Fonction pour récupérer le compteur de notification via Ajax
function getNotificationCount() {
    var xhr = new XMLHttpRequest();
    xhr.onreadystatechange = function() {
        if (xhr.readyState === XMLHttpRequest.DONE) {
            if (xhr.status === 200) {
                document.getElementById('notification-count').innerText = xhr.responseText;
            } else {
                console.log('Une erreur s\'est produite.');
            }
        }
    };
    xhr.open('GET', 'sii.php', true);
    xhr.send();
}

getNotificationCount();
setInterval(getNotificationCount, 5000);

</script>

<script>

function displayNotifications(notifications) {
  var notificationList = document.getElementById('notification-list');
  notificationList.innerHTML = ''; // Effacer le contenu précédent

  notifications.forEach(function(notification) {
    var listItem = document.createElement('li');
    listItem.innerHTML = '<p class="dropdown-item p">' + notification.message + '</p>';
    notificationList.appendChild(listItem);
  });
}

function postData() {
  var xhr = new XMLHttpRequest();

  xhr.onreadystatechange = function() {
    if (xhr.readyState === XMLHttpRequest.DONE) {
      if (xhr.status === 200) {
        // Traiter la réponse JSON contenant les notifications
        var notifications = JSON.parse(xhr.responseText);
        displayNotifications(notifications);
      } else {
        console.error('Une erreur s\'est produite.');
      }
    }
  };

  xhr.open('POST', 'sii.php', true);
  xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
  xhr.send();

  setTimeout(postData, 5000);
}

window.addEventListener('load', postData);
</script>
    <!-- /.navbar -->