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


$sql_types_formation = $dbh->query("SELECT DISTINCT type_formation FROM cds_v2");
$sql_types_formation->execute();
$types_formation = $sql_types_formation->fetchAll(PDO::FETCH_ASSOC);

$sql_niveau = $dbh->query("SELECT DISTINCT niveau FROM cds_v2");
$sql_niveau->execute();
$niveau = $sql_niveau->fetchAll(PDO::FETCH_ASSOC);

$sql_efp = $dbh->query("SELECT DISTINCT efp FROM cds_v2");
$sql_efp->execute();
$efp = $sql_efp->fetchAll(PDO::FETCH_ASSOC);

$sql_filiere = $dbh->query("SELECT DISTINCT filiere FROM cds_v2");
$sql_filiere->execute();
$filiere = $sql_filiere->fetchAll(PDO::FETCH_ASSOC);

if (isset($_POST['chercher'])) {
  $dd = $_POST['dd'];
  $ff = $_POST['df'];
  $formation = !empty($_POST['formation']) ? $_POST['formation'] : null;
  $niveau = !empty($_POST['niveau']) ? $_POST['niveau'] : null;
  $efp = !empty($_POST['efp']) ? $_POST['efp'] : null;
  $filiere = !empty($_POST['filiere']) ? $_POST['filiere'] : null;

  // Construire la requête de base
  $query = "SELECT 
      SUM(prevu) as total_prevu,
      SUM(stagiaires) as total_stagiaires,
      SUM(actif) as total_actif,
      SUM(transfert) as total_transfert,
      SUM(desistement) as total_desistement,
      SUM(redoublement) as total_redoublement
  FROM cds_v2 
  WHERE date_creation BETWEEN :dd AND :ff";

  // Ajouter les conditions de filtrage si elles sont définies
  $params = [':dd' => $dd, ':ff' => $ff];

  if ($formation) {
    $query .= " AND type_formation = :formation";
    $params[':formation'] = $formation;
  }
  if ($niveau) {
    $query .= " AND niveau = :niveau";
    $params[':niveau'] = $niveau;
  }
  if ($efp) {
    $query .= " AND efp = :efp";
    $params[':efp'] = $efp;
  }
  if ($filiere) {
    $query .= " AND filiere = :filiere";
    $params[':filiere'] = $filiere;
  }

  // Préparer et exécuter la requête
  $stmt = $dbh->prepare($query);
  $stmt->execute($params);
  $results = $stmt->fetch(PDO::FETCH_ASSOC);

  // Stocker les résultats dans des variables
  $total_prevu = $results['total_prevu'] ?? 0;
  $total_stagiaires = $results['total_stagiaires'] ?? 0;
  $total_actif = $results['total_actif'] ?? 0;
  $total_transfert = $results['total_transfert'] ?? 0;
  $total_desistement = $results['total_desistement'] ?? 0;
  $total_redoublement = $results['total_redoublement'] ?? 0;

} else {
  // Requête par défaut sans filtrage par date
  $query_default = "SELECT 
      SUM(prevu) as total_prevu,
      SUM(stagiaires) as total_stagiaires,
      SUM(actif) as total_actif,
      SUM(transfert) as total_transfert,
      SUM(desistement) as total_desistement,
      SUM(redoublement) as total_redoublement
  FROM cds_v2";

  $stmt_default = $dbh->query($query_default);
  $results_default = $stmt_default->fetch(PDO::FETCH_ASSOC);

  // Stocker les résultats par défaut
  $total_prevu = $results_default['total_prevu'] ?? 0;
  $total_stagiaires = $results_default['total_stagiaires'] ?? 0;
  $total_actif = $results_default['total_actif'] ?? 0;
  $total_transfert = $results_default['total_transfert'] ?? 0;
  $total_desistement = $results_default['total_desistement'] ?? 0;
  $total_redoublement = $results_default['total_redoublement'] ?? 0;
}
?>

<!DOCTYPE html>
<html>
<!-- <style>
  canvas {
    width: 17rem !important;
    height: 17rem !important;
  }

  .exep {
    display: block;
  }
</style> -->
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
    if ($_SESSION['service'] == 'achat_dr') {
      @include("includes/sidebarachat_dr.php");
    }
    if ($_SESSION['service'] == 'achat_cmplx') {
      @include("includes/sidebarachat_cmplx.php");
    }
    if ($_SESSION['service'] == 'compta') {
      @include("includes/sidebarcompta.php");
    }
    if ($_SESSION['service'] == 'orientation') {
      @include("includes/sidebar_orientation.php");
    }
    ?>
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
      <!-- Content Header (Page header) -->
      <div class="content-header">
        <div class="container-fluid">
          <div class="row mb-4">
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
          <div class="container-fluid bg-white mb-5 rounded py-2 mb-4">
            <div class="row align-items-center">
              <div class="form-group col-md-3">
                <label for="dd" class="form-label text-muted">Sélectionner la Date de Début</label>
                <input type="date" class="form-control" id="dd" name="dd" placeholder="Nom" required value="2025-02-12">
              </div>
              <div class="form-group col-md-3">
                <label for="df" class="form-label text-muted">Sélectionner la Date De Fin</label>
                <input type="date" class="form-control" id="df" name="df" placeholder="Prenom" required value="2025-02-12">
              </div>
            </div>
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
            <div class="row">
              <div class="col-lg-3 col-12 mb-3 mb-lg-0">
                <label for="formation" class="form-label text-muted">Chercher Par Formation</label>
                <input class="form-control" name="formation" list="types_formation_datalist" placeholder="Formation">
                <datalist id="types_formation_datalist">
                  <?php foreach ($types_formation as $type_formation) { ?>
                    <option><?= $type_formation['type_formation'] ?></option>
                  <?php } ?>
                </datalist>
              </div>

              <div class="col-lg-3 col-12 mb-3 mb-lg-0">
                <label for="niveau" class="form-label text-muted">Niveau</label>
                <input class="form-control" name="niveau" list="niveaux_datalist" placeholder="Niveau">
                <datalist id="niveaux_datalist">
                  <?php foreach ($niveau as $nive) { ?>
                    <option><?= $nive['niveau'] ?></option>
                  <?php } ?>
                </datalist>
              </div>

              <div class="col-lg-3 col-12 mb-3 mb-lg-0">
                <label for="efp" class="form-label text-muted">EFP</label>
                <input class="form-control" name="efp" list="efps_datalist" placeholder="EFP">
                <datalist id="efps_datalist">
                  <?php foreach ($efp as $efp_value) { ?>
                    <option><?= $efp_value['efp'] ?></option>
                  <?php } ?>
                </datalist>
              </div>

              <div class="col-lg-3 col-12 mb-lg-0">
                <label for="filiere" class="form-label text-muted">Filière</label>
                <input class="form-control" name="filiere" list="filieres_datalist" placeholder="Filiere">
                <datalist id="filieres_datalist">
                  <?php foreach ($filiere as $fil) { ?>
                    <option><?= $fil['filiere'] ?></option>
                  <?php } ?>
                </datalist>
              </div>
              <div class="form-group col-md-3 d-flex justify-content-around align-items-center mt-5">
                <button type="submit" name="chercher" class="btn btn-link text-blue" style="display: flex; align-items: center; padding: 0;">
                  <i class="fa fa-search" style="margin-right: 5px;"></i> Chercher
                </button>
                <button type="submit" name="chercher1" class="btn btn-link text-blue" style="display: flex; align-items: center; padding: 0;">
                  <i class="fa fa-search" style="margin-right: 5px;"></i> Afficher tous
                </button>
              </div>
            </div>
          </div>
        </form>

        <input type="hidden" id="valueP" value="<?php echo $b[0] ?>">
        <input type="hidden" id="valueNP" value="<?php echo $b[1] ?>">

        <!-- Main content -->
        <section class="content bg-light">
          <div class="container-fluid bg-white shadow-sm mb-5 rounded p-4">
            <!-- Small boxes (Stat box) -->
            <div class="row">
              <div class="col-lg-4 col-6">
                <!-- small box -->
                <div class="small-box bg-info">
                  <?php $query_nbr_dmd_prix = mysqli_query($con, "SELECT * FROM demande_prix WHERE date BETWEEN DATE_SUB(NOW(), INTERVAL 12 MONTH) and NOW()");
                  if (isset($_POST['chercher1'])) {
                    $query_nbr_dmd_prix = mysqli_query($con, "SELECT * FROM demande_prix ");
                  }
                  $nbr_t = mysqli_num_rows($query_nbr_dmd_prix);
                  ?>
                  <div class="inner">
                    <?php
                    if ($nbr_t2) { ?>
                      <h3><?php echo $nbr_t2; ?></h3><?php
                                                    } else { ?>
                      <h3><?php echo $nbr_t; ?></h3><?php
                                                    }
                                                    ?>
                    <p>Taux d'inscription</p>
                  </div>

                  <div class="icon">
                    <i class="price-icon fa fa-chalkboard-teacher"></i>
                  </div>
                  <a href="list_demande_prix.php" class="small-box-footer">Plus d'infos <i class="fas fa-arrow-circle-right"></i></a>
                </div>
              </div>
              <div class="col-lg-4 col-6">
                <!-- small box -->
                <div class="small-box bg-info">
                  <?php $query1 = mysqli_query($con, "SELECT SUM(montant)  AS total_montant FROM  `demande_prix` WHERE date BETWEEN DATE_SUB(NOW(), INTERVAL 12 MONTH) and NOW()");
                  if (isset($_POST['chercher1'])) {
                    $query1 = mysqli_query($con, "SELECT SUM(montant)  AS total_montant FROM  `demande_prix` ");
                  }
                  $t_mnt = mysqli_fetch_assoc($query1);
                  $total_montant = $t_mnt['total_montant'];
                  ?>
                  <div class="inner">
                    <?php
                    if ($total_montant2) { ?>
                      <h3><?php echo number_format($t_mnt2['total_montant'], 2, ',', ' ') . " DHs"; ?></h3><?php
                                                                                                          } else { ?>
                      <h3><?php echo number_format($t_mnt['total_montant'], 2, ',', ' ') . " DHs"; ?></h3><?php
                                                                                                          }
                                                                                                          ?>
                    <p>Taux De presence</p>
                  </div>

                  <div class="icon">
                    <i class="fas fa-building"></i>
                  </div>
                  <a href="list_demande_prix.php?mnt=red" class="small-box-footer">Plus d'infos <i class="fas fa-arrow-circle-right"></i></a>
                </div>
              </div>
              <div class="col-lg-4 col-6">
                <!-- small box -->
                <div class="small-box bg-info">
                  <?php $query1 = mysqli_query($con, "SELECT * FROM `budget_dr` WHERE date BETWEEN DATE_SUB(NOW(), INTERVAL 12 MONTH) and NOW()");
                  if (isset($_POST['chercher1'])) {
                    $query1 = mysqli_query($con, "SELECT * FROM `budget_dr` ");
                  }
                  $nbr_budget_dr = mysqli_num_rows($query1);
                  ?>

                  <div class="inner">
                    <?php
                    if ($nbr_budget_dr2) { ?>
                      <h3>
                        <?php echo $nbr_budget_dr2; ?>
                      </h3>
                    <?php } else { ?>
                      <h3>
                        <?php echo $nbr_budget_dr; ?>
                      </h3>
                    <?php } ?>
                    <p>Le total des Budget dr</p>
                  </div>

                  <div class="icon">
                    <i class="price-icon fa fa-dollar-sign"></i>
                  </div>
                  <a href="list_budget_dr.php" class="small-box-footer">Plus d'infos <i class="fas fa-arrow-circle-right"></i></a>
                </div>
              </div>
              <div class="col-lg-6 col-6">
                <div class="small-box bg-danger">
                  <div class="inner">
                    <h3><?php echo $np; ?></h3>
                    <p>Taux De Desistement</p>
                  </div>
                  <div class="icon">
                    <i class="price-icon fa fa-dollar-sign"></i>
                  </div>
                </div>
              </div>
              <div class="col-lg-6 col-6">
                <div class="small-box bg-success">
                  <div class="inner">
                    <h3><?php echo $p; ?></h3>
                    <p>Taux De Deperdition</p>
                  </div>
                  <div class="icon">
                    <i class="price-icon fa fa-dollar-sign"></i>
                  </div>
                </div>
              </div>
              <!-- <div class="col-lg-4 col-6"></div> -->
              <div class="col-lg-6 col-12">
                <label for="taux_inscription" class="form-label text-muted">Facture</label>
                <div style="width: 24rem; height: 24rem;">
                  <canvas id="myPieChartXX"></canvas>
                </div>
              </div>
              <div class="col-lg-6 col-12 mt-lg-0 mt-5">
                <label for="taux_inscription" class="form-label text-muted">Taux D'inscription</label>
                <div id="chart"></div>
              </div>
            </div>
            <!-- /.row (main row) -->
          </div>
          <div class="container-fluid bg-white shadow-sm mb-5 rounded p-4">
            <div class="row py-4 rounded">
              <h1 class="text-center mb-5">Section Cours Du Soir</h1>
              <div class="col-lg-3 col-6">
                <!-- small box -->
                <div class="small-box bg-info">
                  <div class="inner">
                    <?= $total_prevu ?>
                    <p>Stagiaire Prévu</p>
                  </div>
                  <div class="icon">
                    <i class="fas fa-building"></i>
                  </div>
                  <a href="list_demande_prix.php?mnt=red" class="small-box-footer">Plus d'infos <i class="fas fa-arrow-circle-right"></i></a>
                </div>
              </div>
              <div class="col-lg-3 col-6">
                <!-- small box -->
                <div class="small-box bg-info">
                  <div class="inner">
                    <?= $total_stagiaires ?>
                    <p>Stagiaire Inscrit</p>
                  </div>
                  <div class="icon">
                    <i class="fas fa-building"></i>
                  </div>
                  <a href="list_demande_prix.php?mnt=red" class="small-box-footer">Plus d'infos <i class="fas fa-arrow-circle-right"></i></a>
                </div>
              </div>
              <div class="col-lg-3 col-6">
                <!-- small box -->
                <div class="small-box bg-info">
                  <div class="inner">
                    <?= $total_actif ?>
                    <p>Stagiaire Actif</p>
                  </div>
                  <div class="icon">
                    <i class="fas fa-building"></i>
                  </div>
                  <a href="list_demande_prix.php?mnt=red" class="small-box-footer">Plus d'infos <i class="fas fa-arrow-circle-right"></i></a>
                </div>
              </div>
              <div class="col-lg-3 col-6">
                <!-- small box -->
                <div class="small-box bg-info">
                  <div class="inner">
                    <?= $total_desistement ?>
                    <p>Stagiaire Desistement</p>
                  </div>
                  <div class="icon">
                    <i class="fas fa-building"></i>
                  </div>
                  <a href="list_demande_prix.php?mnt=red" class="small-box-footer">Plus d'infos <i class="fas fa-arrow-circle-right"></i></a>
                </div>
              </div>

              <div class="col-lg-6 col-12 mt-lg-5 mt-0">
                <div id="container" style="width: 100%; height: 400px;"></div>
              </div>
              <div class="col-lg-6 col-12 mt-lg-5 mt-0">
                <figure class="highcharts-figure">
                  <div id="container2"></div>
                </figure>
              </div>
            </div>
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

    <!-- PieChartAnimation 1-->
    <script>
      Highcharts.chart('container', {
        chart: {
          type: 'bar'
        },
        title: {
          text: 'Historic World Population by Region'
        },
        subtitle: {
          text: 'Source: <a ' +
            'href="https://en.wikipedia.org/wiki/List_of_continents_and_continental_subregions_by_population"' +
            'target="_blank">Wikipedia.org</a>'
        },
        xAxis: {
          categories: ['Prevu', 'Inscription', 'Actif', 'Desistement', 'Deperdition'],
          title: {
            text: null
          },
          gridLineWidth: 1,
          lineWidth: 0
        },
        yAxis: {
          min: 0,
          title: {
            text: 'Population (millions)',
            align: 'high'
          },
          labels: {
            overflow: 'justify'
          },
          gridLineWidth: 0
        },
        tooltip: {
          valueSuffix: ' Stagiaires'
        },
        plotOptions: {
          bar: {
            borderRadius: '50%',
            dataLabels: {
              enabled: true
            },
            groupPadding: 0.1
          }
        },
        legend: {
          layout: 'vertical',
          align: 'right',
          verticalAlign: 'top',
          x: -40,
          y: 80,
          floating: true,
          borderWidth: 1,
          backgroundColor: Highcharts.defaultOptions.legend.backgroundColor || '#FFFFFF',
          shadow: true
        },
        credits: {
          enabled: false
        },
        series: [{
          name: 'Year 1990',
          data: [632, 727, 3202, 721, 100]
        }, {
          name: 'Year 2000',
          data: [814, 841, 3714, 726, 100]
        }, {
          name: 'Year 2021',
          data: [
            <?= $total_prevu ?>,
            <?= $total_stagiaires ?>,
            <?= $total_actif ?>,
            <?= $total_desistement ?>,
            <?= $total_redoublement ?>
          ]
        }]
      });
    </script>

    <!-- PieChartAnimation 2-->
    <script>
      Highcharts.chart('container2', {
        chart: {
          type: 'pie',
          custom: {},
          events: {
            render() {
              const chart = this,
                series = chart.series[0];
              let customLabel = chart.options.chart.custom.label;

              if (!customLabel) {
                customLabel = chart.options.chart.custom.label =
                  chart.renderer.label(
                    'Prévu<br/>' +
                    '<strong><?php echo $total_prevu ?></strong>'
                  )
                  .css({
                    color: '#000',
                    textAnchor: 'middle',
                    fontWeight: 'bold'
                  })
                  .add();
              }

              const x = series.center[0] + chart.plotLeft,
                y = series.center[1] + chart.plotTop -
                (customLabel.attr('height') / 2);

              customLabel.attr({
                x,
                y
              });
              customLabel.css({
                fontSize: `${series.center[2] / 12}px`
              });
            }
          }
        },
        accessibility: {
          point: {
            valueSuffix: '%'
          }
        },
        title: {
          text: 'Distribution des Stagiaires'
        },
        tooltip: {
          pointFormat: '{series.name}: <b>{point.y}</b> ({point.percentage:.1f}%)'
        },
        legend: {
          enabled: false
        },
        plotOptions: {
          series: {
            allowPointSelect: true,
            cursor: 'pointer',
            borderRadius: 8,
            dataLabels: [{
              enabled: true,
              distance: 20,
              format: '{point.name}'
            }, {
              enabled: true,
              distance: -35,
              format: '{point.y}<br>{point.percentage:.0f}%',
              style: {
                fontSize: '0.9em',
                textAlign: 'center',
                fontWeight: 'bold'
              },
              filter: {
                property: 'percentage',
                operator: '>',
                value: 4
              }
            }],
            showInLegend: true
          }
        },
        series: [{
          name: 'Stagiaires',
          colorByPoint: true,
          innerSize: '75%',
          data: [{
            name: 'Inscription',
            y: <?php echo $total_stagiaires ?>,
            color: '#90ED7D'
          }, {
            name: 'Actifs',
            y: <?php echo $total_actif ?>,
            color: '#2ecc71'
          }, {
            name: 'Transferts',
            y: <?php echo $total_transfert ?>,
            color: '#f1c40f'
          }, {
            name: 'Désistements',
            y: <?php echo $total_desistement ?>,
            color: '#e74c3c'
          }, {
            name: 'Redoublements',
            y: <?php echo $total_redoublement ?>,
            color: '#3498db'
          }]
        }]
      });
    </script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/chartjs-plugin-datalabels/2.0.0/chartjs-plugin-datalabels.min.js" integrity="sha512-R/QOHLpV1Ggq22vfDAWYOaMd5RopHrJNMxi8/lJu8Oihwi4Ho4BRFeiMiCefn9rasajKjnx9/fTQ/xkWnkDACg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://unpkg.com/chart.js-plugin-labels-dv/dist/chartjs-plugin-labels.min.js"></script>
    <script>
      // JavaScript pour le graphique
      var np = <?php echo $np; ?>;
      var p = <?php echo $p; ?>;
      document.addEventListener('DOMContentLoaded', function() {
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
                render: 'percentage',
                fontStyle: 'bolder',
                position: 'inside', //outside
                textMargin: 6,
                fontColor: 'white',
                fontSize: 20,
              }
            }
          },
          // plugins:[ChartDataLabels] //affiche les nbr (np et n) dans le graphe
        });
      });
    </script>
    <script>
      var options = {
        series: [{
          name: 'series1',
          data: [31, 40, 28, 51, 42, 109, 100]
        }, {
          name: 'series2',
          data: [11, 32, 45, 32, 34, 52, 41]
        }],
        chart: {
          height: 350,
          type: 'area'
        },
        dataLabels: {
          enabled: false
        },
        stroke: {
          curve: 'smooth'
        },
        xaxis: {
          type: 'datetime',
          categories: ["2018-09-19T00:00:00.000Z", "2018-09-19T01:30:00.000Z", "2018-09-19T02:30:00.000Z", "2018-09-19T03:30:00.000Z", "2018-09-19T04:30:00.000Z", "2018-09-19T05:30:00.000Z", "2018-09-19T06:30:00.000Z"]
        },
        tooltip: {
          x: {
            format: 'dd/MM/yy HH:mm'
          },
        },
      };
      var chart = new ApexCharts(document.querySelector("#chart"), options);
      chart.render();
    </script>
    <script>
      // Chart 1: Spline Chart
      Highcharts.chart('chart-spline', {
        chart: {
          type: 'spline'
        },
        legend: {
          symbolWidth: 40
        },
        title: {
          text: 'Most common desktop screen readers',
          align: 'left'
        },
        subtitle: {
          text: 'Source: WebAIM. Click on points to visit official screen reader website',
          align: 'left'
        },
        yAxis: {
          title: {
            text: 'Percentage usage'
          }
        },
        xAxis: {
          title: {
            text: 'Time'
          },
          categories: ['Dec. 2010', 'May 2012', 'Jan. 2014', 'July 2015', 'Oct. 2017', 'Sep. 2019']
        },
        tooltip: {
          valueSuffix: '%',
          stickOnContact: true
        },
        plotOptions: {
          series: {
            point: {
              events: {
                click: function() {
                  window.location.href = this.series.options.website;
                }
              }
            },
            cursor: 'pointer',
            lineWidth: 2
          }
        },
        series: [{
            name: 'NVDA',
            data: [34.8, 43.0, 51.2, 41.4, 64.9, 72.4],
            website: 'https://www.nvaccess.org'
          },
          {
            name: 'JAWS',
            data: [69.6, 63.7, 63.9, 43.7, 66.0, 61.7],
            website: 'https://www.freedomscientific.com/Products/Blindness/JAWS',
            dashStyle: 'ShortDashDot'
          },
          {
            name: 'VoiceOver',
            data: [20.2, 30.7, 36.8, 30.9, 39.6, 47.1],
            website: 'http://www.apple.com/accessibility/osx/voiceover',
            dashStyle: 'ShortDot'
          },
          {
            name: 'Narrator',
            data: [null, null, null, null, 21.4, 30.3],
            website: 'https://support.microsoft.com/en-us/help/22798/windows-10-complete-guide-to-narrator',
            dashStyle: 'Dash'
          }
        ]
      });

      // Chart 2: Pie Chart
      const colors = Highcharts.getOptions().colors;
      const pieColors = [colors[2], colors[0], colors[3], colors[1], colors[4]];

      Highcharts.chart('chart-pie', {
        chart: {
          type: 'pie'
        },
        title: {
          text: 'Primary desktop/laptop screen readers',
          align: 'left'
        },
        subtitle: {
          text: 'Source: WebAIM. Click on point to visit official website',
          align: 'left'
        },
        colors: pieColors,
        tooltip: {
          valueSuffix: '%',
          borderColor: '#8ae',
          shape: 'rect',
          backgroundColor: 'rgba(255, 255, 255, 0.94)'
        },
        plotOptions: {
          series: {
            dataLabels: {
              enabled: true,
              format: '<b>{point.name}</b>: {point.percentage:.1f} %'
            },
            point: {
              events: {
                click: function() {
                  window.location.href = this.website;
                }
              }
            },
            cursor: 'pointer'
          }
        },
        series: [{
          name: 'Screen reader usage',
          data: [{
              name: 'NVDA',
              y: 40.6,
              website: 'https://www.nvaccess.org'
            },
            {
              name: 'JAWS',
              y: 40.1,
              website: 'https://www.freedomscientific.com/Products/Blindness/JAWS'
            },
            {
              name: 'VoiceOver',
              y: 12.9,
              website: 'http://www.apple.com/accessibility/osx/voiceover'
            },
            {
              name: 'ZoomText',
              y: 2,
              website: 'http://www.zoomtext.com/products/zoomtext-magnifierreader'
            },
            {
              name: 'Other',
              y: 4.4,
              website: 'http://www.disabled-world.com/assistivedevices/computer/screen-readers.php'
            }
          ]
        }]
      });
    </script>