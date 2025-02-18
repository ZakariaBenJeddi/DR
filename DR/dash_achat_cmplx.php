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

?>
<!DOCTYPE html>
<html>
<style>
    canvas{
        width: 17rem !important;
        height: 17rem !important;
    }
    .exep{
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
        if ($_SESSION['service'] == 'controle') {
            @include("includes/sidebar.php");
        }
        if ($_SESSION['service'] == 'formation') {
            @include("includes/sidebarf.php");
        }
        if ($_SESSION['service'] == 'RH') {
            @include("includes/sidebarRH.php");
        }
        if ($_SESSION['service'] == 'achat') {
            @include("includes/sidebarachat.php");
        }
        if ($_SESSION['service'] == 'compta') {
            @include("includes/sidebarcompta.php");
        }
        if($_SESSION['service']=='achat_dr'){
            @include("includes/sidebarachat_dr.php");
          }
          if($_SESSION['service']=='achat_cmplx'){
            @include("includes/sidebarachat_cmplx.php");
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
                <!-- Main content -->
                <section class="content">
                    <div class="container-fluid">
                        <!-- Small boxes (Stat box) -->
                        <div class="row">
                            <div class="col-lg-4 col-6">
                                <!-- small box -->
                                <div class="small-box bg-info">
                                    <?php 
                                    $sql="select idcomplex from complexe1 where id=$_SESSION[sid]";
                                    $query=mysqli_query($con,$sql);
                                    $row=mysqli_fetch_array($query);
                                    $cmplx=$row['idcomplex'];
                                    $query2 = mysqli_query($con, "SELECT * FROM budgetcomplex where ide in(select ide from etablissement where idcomplex=$cmplx)");
                                    $nbr_t = mysqli_num_rows($query2);
                                    ?>
                                    <div class="inner">
                                        <h3><?php echo $nbr_t; ?></h3>
                                        <p>Le total des budgets complexe</p>
                                    </div>
                                    <div class="icon">
                                    <i class="price-icon fa fa-dollar-sign"></i>
                                        
                                    </div>
                                    <a href="list_budget_complex.php" class="small-box-footer">Plus d'infos <i class="fas fa-arrow-circle-right"></i></a>
                                </div>
                            </div>
                            <div class="col-lg-4 col-6">
                                <!-- small box -->
                                <div class="small-box bg-info">
                                    <?php $query1 = mysqli_query($con, "SELECT * FROM `fournisseur`");
                                    $nbr_four = mysqli_num_rows($query1);
                                    ?>
                                    <div class="inner">
                                        <h3><?php echo $nbr_four; ?></h3>
                                        <p>Le total des fournisseurss</p>
                                    </div>
                                    <div class="icon">
                                    <i class="price-icon fa fa-chalkboard-teacher"></i>
                                    </div>
                                    <a href="list_fournisseur.php" class="small-box-footer">Plus d'infos <i class="fas fa-arrow-circle-right"></i></a>
                                </div>
                            </div>
                            <div class="col-lg-4 col-6">
                                <!-- small box -->
                                <div class="small-box bg-info">
                                    <?php
                                    $sql="select idcomplex from complexe1 where id=$_SESSION[sid]";
                                    $query=mysqli_query($con,$sql);
                                    $row=mysqli_fetch_array($query);
                                    $cmplx=$row['idcomplex'];
                                    $query1 = mysqli_query($con, "SELECT * FROM `etablissement` where idcomplex=$cmplx");
                                    $nbr_e = mysqli_num_rows($query1);
                                    ?>
                                    <div class="inner">
                                        <h3><?php echo $nbr_e; ?></h3>
                                        <p>Le total des etablissements</p>
                                    </div>
                                    <div class="icon">
                                        <i class="fas fa-building"></i>
                                    </div>
                                    <a href="list_etablissement.php" class="small-box-footer">Plus d'infos <i class="fas fa-arrow-circle-right"></i></a>
                                </div>
                            </div>
                            <div class="col-lg-4 col-6">
                                <!-- small box -->
                                <div class="small-box bg-success">
                                    <?php
                                    $sql="select idcomplex from complexe1 where id=$_SESSION[sid]";
                                    $query=mysqli_query($con,$sql);
                                    $row=mysqli_fetch_array($query);
                                    $cmplx=$row['idcomplex'];
                                    $query1 = mysqli_query($con, "SELECT * FROM `demande_prix_complex` where idcomplex=$cmplx AND facture=1 AND payer=1");
                                    $nbr_ff = mysqli_num_rows($query1);
                                    ?>
                                    <div class="inner">
                                        <h3><?php echo $nbr_ff; ?></h3>
                                        <p>Le total des factures payées</p>
                                    </div>
                                    <div class="icon">
                                    <i class="price-icon fa fa-dollar-sign"></i>
                                    </div>
                                    <a href="facture_payé_clx.php" class="small-box-footer">Plus d'infos <i class="fas fa-arrow-circle-right"></i></a>
                                </div>
                            </div>
                            <div class="col-lg-4 col-6">
                              <div class="small-box bg-danger">
                                    <?php
                                    $sql="select idcomplex from complexe1 where id=$_SESSION[sid]";
                                    $query=mysqli_query($con,$sql);
                                    $row=mysqli_fetch_array($query);
                                    $cmplx=$row['idcomplex'];
                                    $query1 = mysqli_query($con, "SELECT * FROM `demande_prix_complex` where idcomplex=$cmplx AND facture=1 AND payer=0");
                                    $nbr_f = mysqli_num_rows($query1);
                                    ?>
                                    <div class="inner">
                                        <h3><?php echo $nbr_f; ?></h3>
                                        <p>Le total des factures non payées</p>
                                    </div>
                                    <div class="icon">
                                    <i class="price-icon fa fa-dollar-sign"></i>
                                    </div>
                                    <a href="reste_payer_clx.php" class="small-box-footer">Plus d'infos <i class="fas fa-arrow-circle-right"></i></a>
                                </div>
                            </div>
                         </div>
                         <div class="col-lg-4 col-6"></div>
                         <div class="col-lg-4 col-6">
                                <!-- small box -->
                                <div class="small-box bg-info">
                                </div>
                                <?php
                                    $p=$nbr_f;
                                    $np=$nbr_ff;
                                ?>
                                <canvas id="myPieChartXX" style="width: 100px !important;height: 100px !important"></canvas>
                            </div>
                        </div>
                        <!-- /.row (main row) -->
                    
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
        <?php @include("includes/foot.php"); ?>
        <!-- JavaScript -->
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