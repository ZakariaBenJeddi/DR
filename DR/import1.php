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

                $code= $cellIterator->current()->getValue();
                $cellIterator->next();

                $nom = $cellIterator->current()->getValue();
                $cellIterator->next();

                $prenom = $cellIterator->current()->getValue();
                $cellIterator->next();
                
                $genre = $cellIterator->current()->getValue();
                $cellIterator->next();
                $daten= $cellIterator->current()->getValue();
                $cellIterator->next();
                $lieu = $cellIterator->current()->getValue();
                $cellIterator->next();
                $classe = $cellIterator->current()->getValue();
               //      $dateObj = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($daten);

                // Maintenant, $dateObj contient la date sous forme d'objet DateTime
                        $date =''; 




$email=$prenom.'.'.$nom.'@gmail.com';
$mp= $prenom.'@@'.$nom;

  
  
    $r1=mysqli_query($con,"select * from groupes where nom_groupe='$classe'");
 
  $row1=mysqli_fetch_array($r1);
  $idg=$row1['id_g'];
  $idop=$row1['id_op'];

echo $code."/".$nom."/".$prenom."/".$email."/".$idop."/".$idg;

 $stmt = $dbh->prepare("INSERT INTO students (studentno,StudentName,prenom, gender, email,studentImage,id_op, id_g,password, status, auto, date_i, date_n,lieu) 
            VALUES (?,?,?,?,'$email','100.jpg',$idop,$idg,'$mp', 1,1, CURDATE(),?,?)");
            
            $stmt->execute([$code,$nom, $prenom,$genre,$date,$lieu]);
            }

           
	 echo '<script>alert("Importation réussie !")</script>';
      echo "<script>window.location.href ='aff1.php'</script>";
			
			
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
