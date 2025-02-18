<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');

$_SESSION['last_activity'] = time();

if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > 300)) {
    session_unset();
    session_destroy();
    header("location:logout.php");
    exit;
}

if (empty($_SESSION['sid'])) {
    header('location:logout.php');
}

// Si l'utilisateur a cliqué sur le bouton Chercher
if (isset($_POST['chercher'])) {
    $dd = $_POST['dd'];
    $ff = $_POST['df'];

    $fp = $dbh->prepare("SELECT COUNT(*) as p FROM fact WHERE payer = 1 AND facture = 1 AND datef BETWEEN :dd AND :ff");
    $fp->bindParam(':dd', $dd);
    $fp->bindParam(':ff', $ff);
    $fp->execute();

    $fnp = $dbh->prepare("SELECT COUNT(*) as np FROM fact WHERE payer = 0 AND facture = 1 AND datef BETWEEN :dd AND :ff");
    $fnp->bindParam(':dd', $dd);
    $fnp->bindParam(':ff', $ff);
    $fnp->execute();

    // Récupérer les résultats de la requête pour afficher dans le graphique
    if ($fp->rowCount() > 0 && $fnp->rowCount() > 0) {
        $p1 = $fp->fetch();
        $p = $p1['p'];

        $p2 = $fnp->fetch();
        $np = $p2['np'];
    } else {
        $p = 0;
        $np = 0;
    }
} else {
    // Par défaut, afficher les résultats sans filtrage par date
    $fp_default = $dbh->query("SELECT COUNT(*) as p FROM fact WHERE payer = 1 AND facture = 1");
    $fnp_default = $dbh->query("SELECT COUNT(*) as np FROM fact WHERE payer = 0 AND facture = 1");

    if ($fp_default->rowCount() > 0 && $fnp_default->rowCount() > 0) {
        $p1 = $fp_default->fetch();
        $p = $p1['p'];

        $p2 = $fnp_default->fetch();
        $np = $p2['np'];
    } else {
        $p = 0;
        $np = 0;
    }
}
?>

<!DOCTYPE html>
<html>
<style>
    canvas{
        width: 17rem !important;
        height: 17rem !important;
    }
    .exep {
        display: block;
    }
</style>
<?php @include("includes/head.php"); ?>

<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">
        <!-- Navbar -->
        <?php @include("includes/header.php"); ?>
        <!-- Main Sidebar Container -->
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
        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0 text-dark">Tableau de bord</h1>
                        </div><!-- /.col -->
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="#">Accueil</a></li>
                            </ol>
                        </div><!-- /.col -->
                    </div><!-- /.row -->
                </div><!-- /.container-fluid -->
                <!-- /.content-header -->
                <form method="post">
                    <div class="row">
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <div class="form-group col-md-3">
                            <label for="names">Selectionner la Date de Debut</label>
                            <input type="date" class="form-control" id="dd" name="dd" placeholder="Nom" required>
                        </div>
                        <div class="form-group col-md-3">
                            <label for="prenom">Selectionner la Date De Fin</label>
                            <input type="date" class="form-control" id="df" name="df" placeholder="Prenom" required>
                        </div>
                        <button type="submit" name="chercher" style="border: none; outline: none; width: 100px; height: 95px;background-color: transparent;color: blue;" onclick="f1()"><i class="fa fa-search"></i> Chercher</button>
                        <button type="submit" name="chercher1" style="border: none; outline: none; width: 115px; height: 95px;background-color: transparent;color: blue;" onclick="f2()"><i class="fa fa-search"></i> afficher tous</button>
                        <script>
                            // Obtenez la balise input type date par son ID
                            const dd = document.getElementById("dd");
                            const df = document.getElementById("df");

                            // Obtenez la date d'aujourd'hui sous forme de chaîne (AAAA-MM-JJ)
                            const today = new Date().toISOString().split('T')[0];

                            // Définir la valeur par défaut de la balise sur la date d'aujourd'hui
                            dd.value = today;
                            df.value = today;
                        </script>
                    </div>
                </form>

                <input type="hidden" id="valueP" value="<?php echo $b[0] ?>">
                <input type="hidden" id="valueNP" value="<?php echo $b[1] ?>">
                
                <!-- Main content -->
                <section class="content">
                    <div class="container-fluid">
                        <!-- Small boxes (Stat box) -->
                        <div class="row">
                            <div class="col-lg-4 col-6">
                                <!-- small box -->
                                <div class="small-box bg-info">
                                    <?php $query1 = mysqli_query($con, "SELECT * FROM formation");
                                    $nbr_t = mysqli_num_rows($query1);
                                    ?>
                                    <div class="inner">
                                        <h3><?php echo $nbr_t; ?></h3>
                                        <p>Le total des Formations</p>
                                    </div>
                                    <div class="icon">
                                        <i class="price-icon fa fa-chalkboard-teacher"></i>
                                    </div>
                                    <a href="list_formation.php" class="small-box-footer">Plus d'infos <i class="fas fa-arrow-circle-right"></i></a>
                                </div>
                            </div>
                            <div class="col-lg-4 col-6">
                                <!-- small box -->
                                <div class="small-box bg-info">
                                    <?php $query1 = mysqli_query($con, "SELECT * FROM `ste`");
                                    $nbr_f = mysqli_num_rows($query1);
                                    ?>
                                    <div class="inner">
                                        <h3><?php echo $nbr_f; ?></h3>
                                        <p>Le total des sociétés</p>
                                    </div>
                                    <div class="icon">
                                        <i class="fas fa-building"></i>
                                    </div>
                                    <a href="list_ste.php" class="small-box-footer">Plus d'infos <i class="fas fa-arrow-circle-right"></i></a>
                                </div>
                            </div>
                            <div class="col-lg-4 col-6">
                                <!-- small box -->
                                <div class="small-box bg-info">
                                    <?php $query1 = mysqli_query($con, "SELECT * FROM `fact` where facture=1");
                                    $nbr_f = mysqli_num_rows($query1);
                                    ?>
                                    <div class="inner">
                                        <h3><?php echo $nbr_f; ?></h3>
                                        <p>Le total des factures</p>
                                    </div>
                                    <div class="icon">
                                        <i class="price-icon fa fa-dollar-sign"></i>
                                    </div>
                                    <a href="Facture_ste.php" class="small-box-footer">Plus d'infos <i class="fas fa-arrow-circle-right"></i></a>
                                </div>
                            </div>
                            <div class="col-lg-4 col-6">
                                <div class="small-box bg-danger">
                                    <div class="inner">
                                        <h3><?php echo $np; ?></h3>
                                        <p>Le total des factures non payées</p>
                                    </div>
                                    <div class="icon">
                                        <i class="price-icon fa fa-dollar-sign"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 col-6">
                                <div class="small-box bg-success">
                                    <div class="inner">
                                        <h3><?php echo $p; ?></h3>
                                        <p>Le total des factures payées</p>
                                    </div>
                                    <div class="icon">
                                        <i class="price-icon fa fa-dollar-sign"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 col-6"></div>
                            <div class="col-lg-4 col-6">
                                <canvas id="myPieChartXX"></canvas>
                            </div>
                        </div>
                        <!-- /.row (main row) -->
                    </div>
                    <!-- /.container-fluid -->
                </section>
            </div>
            <!-- /.content-wrapper -->
            <!-- Control Sidebar -->
            <aside class="control-sidebar control-sidebar-dark">
                <!-- Control sidebar content goes here -->
            </aside>
            <!-- /.control-sidebar -->
        </div>
        <!-- ./wrapper -->

        <!-- JavaScript -->
        <?php @include("includes/foot.php"); ?>
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels"></script>
        
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.7.0/chart.min.js"></script>
        
        <script src="https://cdnjs.cloudflare.com/ajax/libs/chartjs-plugin-datalabels/2.0.0/chartjs-plugin-datalabels.min.js" integrity="sha512-R/QOHLpV1Ggq22vfDAWYOaMd5RopHrJNMxi8/lJu8Oihwi4Ho4BRFeiMiCefn9rasajKjnx9/fTQ/xkWnkDACg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
        <script src="https://unpkg.com/chart.js-plugin-labels-dv/dist/chartjs-plugin-labels.min.js"></script>
        <script>
            // JavaScript pour le graphique
            var np = <?php echo $np; ?>;
            var p = <?php echo $p; ?>;
            document.addEventListener('DOMContentLoaded', function () {
                var ctx = document.getElementById('myPieChartXX').getContext('2d');
                
                var myPieChart = new Chart(ctx, {
                    type: 'pie',
                    data: {
                        labels: ['Facture non payée', 'Facture payée'],
                        datasets: [{
                            data: [np, p],
                            backgroundColor: ['#dc3545', '#28a745']
                        }]
                    },
                    options: {
                        plugins: {
                            labels: {
                                display: false,
                                position: 'bottom',
                                render:'percentage',
                                fontStyle:'bolder',
                                position:'inside',//outside
                                textMargin:6,
                                fontColor: 'white',
                                fontSize: 20,
                            }
                        }
                    },
                    // plugins:[ChartDataLabels] //affiche les nbr (np et n) dans le graphe
                });
            });
</script>