 <!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
  <!-- Brand Logo -->
  <a href="#" class="brand-link">
    <?php

      $query = $dbh->prepare("SELECT * from setting_site where id=1 ");
      $query->execute();
      $results = $query->fetchAll(PDO::FETCH_OBJ);
      $cnt = 1;

      foreach ($results as $row) {
        echo '
      <img src="logo/' . $row->logo . '" alt="Leading Estate" class="brand-image img-circle elevation-3"
          
          ';
      }
      ?>
    style="opacity: .8">
    <span class="brand-text font-weight-light">Gestion Comptabilite</span>
  </a>

  <!-- Sidebar -->
  <div class="sidebar">
    <!-- Sidebar user panel (optional) -->
    <div class="user-panel mt-3 pb-3 mb-3 d-flex">
      <?php
        $eid = $_SESSION['sid'];
        $sql = "SELECT * from tblusers where id=:eid ";
        $query = $dbh->prepare($sql);
        $query->bindParam(':eid', $eid, PDO::PARAM_STR);
        $query->execute();
        $results = $query->fetchAll(PDO::FETCH_OBJ);

        $cnt = 1;
        if ($query->rowCount() > 0) {
          foreach ($results as $row) {
        ?>
          <div class="info">
            <a href="profile.php" class="d-block"><?php echo ($row->name); ?> <?php echo ($row->lastname); ?></a>
          </div>
      <?php
          }
        }
        ?>

    </div>

    <!-- Sidebar Menu -->
    <nav class="mt-2">
      <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
        <li class="nav-item has-treeview menu-open">
          <a href="imp1.php" class="nav-link active">
            &nbsp;<i class="fas fa-upload"></i>
            <p>
              &nbsp;&nbsp;&nbsp;&nbsp;Importer DATA
            </p>
          </a>
        </li>
        <li class="nav-item has-treeview menu-open">
          <a href="dash_orientation.php" class="nav-link active">
            <i class="nav-icon fas fa-tachometer-alt"></i>
            <p>
              Tableau de bord
            </p>
          </a>
        </li>
        <li class="nav-item">
          <a href="admission_formation.php" class="nav-link">
            <i class="fa-solid fa-user-graduate"></i>
            <p>Admission Formation</p>
          </a>
        </li>
        <li class="nav-item">
          <a href="cds_v2.php" class="nav-link">
            <i class="fa-solid fa-folder-open"></i>
            <p>CDS V2</p>
          </a>
        </li>
        <li class="nav-item">
          <a href="inscription_formation.php" class="nav-link">
            <i class="fa-solid fa-file-signature"></i>
            <p>Inscription</p>
          </a>
        </li>
        <li class="nav-item">
          <a href="presence_orientation.php" class="nav-link">
            <i class="fa-solid fa-user-check"></i>
            <p>Présence</p>
          </a>
        </li>
        <li class="nav-item">
          <a href="desistement_orientation.php" class="nav-link">
            <i class="fa-solid fa-user-slash"></i>
            <p>Désistement</p>
          </a>
        </li>
        <li class="nav-item">
          <a href="deperdition_orientation.php" class="nav-link">
          <i class="fa-solid fa-chart-line"></i>
            <p>Déperdition</p>
          </a>
        </li>
        <li class="nav-item">
          <a href="awrach.php" class="nav-link">
            <i class="fa-solid fa-briefcase"></i>
            <p>AWRACH</p>
          </a>
        </li>
        <li class="nav-item">
          <a href="cop_orientation.php" class="nav-link">
            <i class="fa-solid fa-users"></i>
            <p>C.O.P</p>
          </a>
        </li>
        <li class="nav-item">
          <a href="activite_parascolaire.php" class="nav-link">
          <i class="fa-solid fa-theater-masks"></i>
            <p>Activité Parascolaire</p>
          </a>
        </li>
        <li class="nav-item">
          <a href="changepassword.php" class="nav-link">
            <i class="fas fa-key"></i>
            <p>
              Changer le mot de passe
            </p>
          </a>
        </li>
        <li class="nav-item">
          <a href="logout.php" class="nav-link">
            <i class="fas fa-sign-out-alt"></i>
            <p>
              Se déconnecter
            </p>
          </a>
        </li>
      </ul>
    </nav>
    <!-- /.sidebar-menu -->
  </div>
  <!-- /.sidebar -->
</aside>