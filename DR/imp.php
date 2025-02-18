<?php
use PhpOffice\PhpSpreadsheet\IOFactory;
session_start();
error_reporting(0);
ini_set("max_execution_time", "1000");
include('includes/dbconnection.php'); // Assurez-vous que la connexion à la base de données est correcte

if (empty($_SESSION['sid'])) {
    header('location:logout.php');
} else {

    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES["excel_file"])) {
        $uploadDir = "uploads/";
        $tempFileName = $_FILES["excel_file"]["tmp_name"];

        require 'vendor/autoload.php';

        try {
            $spreadsheet = IOFactory::load($tempFileName);
            $worksheet = $spreadsheet->getActiveSheet();

            // Initialisez la connexion à la base de données ici, par exemple :
            // $dbh = new PDO('mysql:host=localhost;dbname=ma_base_de_donnees', 'nom_utilisateur', 'mot_de_passe');

            foreach ($worksheet->getRowIterator(2) as $row) {
                $cellIterator = $row->getCellIterator();
                $cellIterator->setIterateOnlyExistingCells(false);

                $Complexe = $cellIterator->current()->getValue();
                $cellIterator->next();

                $CodeEFP = $cellIterator->current()->getValue();
                $cellIterator->next();

                $EFP = $cellIterator->current()->getValue();
                $cellIterator->next();

                $Niveau = $cellIterator->current()->getValue();
                $cellIterator->next();
                $codef = $cellIterator->current()->getValue();
                $cellIterator->next();
                $Typef = $cellIterator->current()->getValue();
                $cellIterator->next();
                $annee = $cellIterator->current()->getValue();
                $cellIterator->next();

                $Creneau = $cellIterator->current()->getValue();
                $cellIterator->next();

                $Groupe = $cellIterator->current()->getValue();
                $cellIterator->next();

                $Mode = $cellIterator->current()->getValue();
                $cellIterator->next();

                $CodeModule = $cellIterator->current()->getValue();
                $cellIterator->next();
				
                $f = $cellIterator->current()->getValue();
                $cellIterator->next();
				
                $module = $cellIterator->current()->getValue();
                $cellIterator->next();

                $MHPT = $cellIterator->current()->getValue();
                $cellIterator->next();

                $MHST = $cellIterator->current()->getValue();
				$cellIterator->next();
				$MHGR = $cellIterator->current()->getValue();

                // Vous devez effectuer les calculs pour $mhptr, $mhstr, $mhttr ici
                // Par exemple :
                $t = '';
                $newString = substr($CodeModule,0, 1);

                if ($newString == 'M' || $newString == 'm') {
                    $t = 'TEC';
                } else {
                    $t = 'EGT';
                }

                $mhptr = 0;

                if ($t == "EGT") {
                    $mhptr = $MHPT;
                } elseif ($Mode == "Alterné" | $Mode == "Alterne") {
                    $mhptr = $MHPT * 0.5;
                } else {
                    $mhptr = $MHPT;
                }

                $mhstr = 0;

                if ($t == "EGT") {
                    $mhstr = $MHST;
                } elseif ($Mode == "Alterné" || $Mode == "Alterne") {
                    $mhstr = $MHST * 0.5;
                } else {
                    $mhstr = $MHST;
                }

                $mhttr = $mhptr + $mhstr;

                // Préparez la requête SQL
     $stmt = $dbh->prepare("INSERT INTO `drh`(`Complexe`, `CodeEFP`, `EFP`, `Niveau`,codef,`Typef`,annee,`Creneau`, `Groupe`, `Mode`, `CodeModule`,f,module, `MHPT`, `MHST`, `MHPTR`, `MHSTR`, `MHTTR`, `MHRG`, `type`, `dater`) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,CURDATE())");

                // Exécutez la requête SQL avec les valeurs correspondantes
 $stmt->execute([$Complexe, $CodeEFP, $EFP, $Niveau,$codef,$Typef,$annee,$Creneau, $Groupe, $Mode, $CodeModule,$f,$module,$MHPT,$MHST, $mhptr, $mhstr, $mhttr,$MHGR, $t]);

                // Réinitialisez les variables pour la prochaine ligne de données
          $Complexe = $CodeEFP = $EFP = $Niveau = $Typef = $Creneau = $Groupe = $Mode = $CodeModule =$f=$module=$annee= $MHPT = $MHST = $t = $newString = '';
            }

			  echo '<script>alert("Importation réussie !")</script>';
      echo "<script>window.location.href ='aff.php'</script>";
			
			
        } catch (Exception $e) {
            echo "Erreur : " . $e->getMessage();
        }

        // Fermez la connexion à la base de données
        $dbh = null;
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
              <h1>Importer depuis Excel</h1>
            </div>
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="#">Accueil</a></li>
                <li class="breadcrumb-item active">Importer depuis Excel</li>
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
                  <h3 class="card-title">Importer depuis Excel</h3>
                  <div class="card-tools">
				<form method="post" enctype="multipart/form-data">
                <input type="file" name="excel_file" accept=".xlsx, .xls" class="btn btn-sm btn-primary" >
                    <button  type="submit" class="btn btn-sm btn-primary" ><span style="color: #fff;"><i class="fas fa-download" ></i> Importer</span>
                    </button>
					</form>
                  </div>
                </div>
                <!-- /.card-header -->
         
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
  <?php
  
              
                    
@include("includes/foot.php"); ?>


</body>

</html>
