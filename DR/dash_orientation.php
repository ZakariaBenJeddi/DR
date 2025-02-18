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

if (isset($_POST['chercher'])) {
  $dd = $_POST['dd'];
  $ff = $_POST['df'];

  $fp = $dbh->prepare("SELECT COUNT(*) as p FROM facture_payer WHERE facture = 1 AND date_payment BETWEEN :dd AND :ff");
  $fp->bindParam(':dd', $dd);
  $fp->bindParam(':ff', $ff);
  $fp->execute();

  $fnp = $dbh->prepare("SELECT COUNT(*) as np FROM rest_payer WHERE payer = 0 AND facture = 1 AND date BETWEEN :dd AND :ff");
  $fnp->bindParam(':dd', $dd);
  $fnp->bindParam(':ff', $ff);
  $fnp->execute();

  if ($fp->rowCount() > 0 && $fnp->rowCount() > 0) {
    $p1 = $fp->fetch();
    $p = $p1['p'];

    $p2 = $fnp->fetch();
    $np = $p2['np'];
  } else {
    $p = 0;
    $np = 0;
  }

  // Le total des Demande de prix 
  $query_nbr_dmd_prix2 = mysqli_query($con, "SELECT * FROM demande_prix WHERE date BETWEEN '$dd' AND '$ff' ");
  $nbr_t2 = mysqli_num_rows($query_nbr_dmd_prix2);

  // Le total des prix demandé
  $query_somme_total2 = mysqli_query($con, "SELECT SUM(montant)  AS total_montant FROM  `demande_prix` WHERE date BETWEEN '$dd' AND '$ff' ");
  $t_mnt2 = mysqli_fetch_assoc($query_somme_total2);
  $total_montant2 = $t_mnt2['total_montant'];

  $query_nbr_budget_dr2 = mysqli_query($con, "SELECT * FROM `budget_dr` WHERE date BETWEEN '$dd' AND '$ff' ");
  $nbr_budget_dr2 = mysqli_num_rows($query_nbr_budget_dr2);
} else {
  // Par défaut, afficher les résultats sans filtrage par date
  $fp_default = $dbh->query("SELECT COUNT(*) as p FROM facture_payer WHERE facture = 1");
  $fnp_default = $dbh->query("SELECT COUNT(*) as np FROM rest_payer WHERE payer = 0 AND facture = 1 ");

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
              <!-- <div class="form-group col-md-3 d-flex align-items-center">
                <button type="submit" name="chercher" class="btn btn-link text-blue" style="display: flex; align-items: center; padding: 0;">
                  <i class="fa fa-search" style="margin-right: 5px;"></i> Chercher
                </button>
                <button type="submit" name="chercher1" class="btn btn-link text-blue" style="display: flex; align-items: center; padding: 0;">
                  <i class="fa fa-search" style="margin-right: 5px;"></i> Afficher tous
                </button>
              </div> -->
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
                <select class="form-select form-select-sm" id="formation" aria-label="Formation">
                  <option value="">Sélectionner une formation</option>
                  <option value="qualifiante">Formation Qualifiante</option>
                  <option value="diplomante">Formation Diplômante</option>
                </select>
              </div>
              <div class="col-lg-3 col-12 mb-3 mb-lg-0">
                <label for="formation" class="form-label text-muted">Chercher Par Formation</label>
                <input class="form-control" list="europe-countries" placeholder="Start typing...">
                <datalist id="europe-countries">
                  <option>Russia</option>
                  <option>Germany</option>
                  <option>United Kingdom</option>
                  <option>France</option>
                  <option>Italy</option>
                  <option>Spain</option>
                  <option>Ukraine</option>
                  <option>Poland</option>
                  <option>Romania</option>
                  <option>Netherlands</option>
                  <option>Belgium</option>
                  <option>Czech Republic</option>
                  <option>Greece</option>
                  <option>Portugal</option>
                  <option>Sweden</option>
                  <option>Hungary</option>
                  <option>Belarus</option>
                  <option>Austria</option>
                  <option>Serbia</option>
                  <option>Switzerland</option>
                  <option>Bulgaria</option>
                  <option>Denmark</option>
                  <option>Finland</option>
                  <option>Slovakia</option>
                  <option>Norway</option>
                  <option>Ireland</option>
                  <option>Croatia</option>
                  <option>Moldova</option>
                  <option>Bosnia and Herzegovina</option>
                  <option>Albania</option>
                  <option>Lithuania</option>
                  <option>North Macedonia</option>
                  <option>Slovenia</option>
                  <option>Latvia</option>
                  <option>Estonia</option>
                  <option>Montenegro</option>
                  <option>Luxembourg</option>
                  <option>Malta</option>
                  <option>Iceland</option>
                  <option>Andorra</option>
                  <option>Monaco</option>
                  <option>Liechtenstein</option>
                  <option>San Marino</option>
                  <option>Holy See</option>
                </datalist>
              </div>

              <div class="col-lg-3 col-12 mb-3 mb-lg-0">
                <label for="niveau" class="form-label text-muted">Niveau</label>
                <select class="form-select form-select-sm" id="niveau" aria-label="Niveau">
                  <option value="">Sélectionner un niveau</option>
                  <option value="debutant">Débutant</option>
                  <option value="intermediaire">Intermédiaire</option>
                  <option value="avance">Avancé</option>
                </select>
              </div>

              <div class="col-lg-3 col-12 mb-3 mb-lg-0">
                <label for="efp" class="form-label text-muted">EFP</label>
                <select class="form-select form-select-sm" id="efp" aria-label="EFP">
                  <option value="">Sélectionner un EFP</option>
                  <option value="efp1">EFP de Casablanca</option>
                  <option value="efp2">EFP de Rabat</option>
                  <option value="efp3">EFP de Marrakech</option>
                </select>
              </div>

              <div class="col-lg-3 col-12  mb-lg-0">
                <label for="filiere" class="form-label text-muted">Filière</label>
                <select class="form-select form-select-sm" id="filiere" aria-label="Filière">
                  <option value="">Sélectionner une filière</option>
                  <option value="informatique">Informatique</option>
                  <option value="mecanique">Mécanique</option>
                  <option value="electronique">Électronique</option>
                  <option value="gestion">Gestion</option>
                </select>
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
              <div class="col-lg-3 col-6">
                <!-- small box -->
                <div class="small-box bg-info">
                  <div class="inner">
                    12
                    <p>Le total des prix demandé</p>
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
                    12
                    <p>Le total des prix demandé</p>
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
                    12
                    <p>Le total des prix demandé</p>
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
                    12
                    <p>Le total des prix demandé</p>
                  </div>
                  <div class="icon">
                    <i class="fas fa-building"></i>
                  </div>
                  <a href="list_demande_prix.php?mnt=red" class="small-box-footer">Plus d'infos <i class="fas fa-arrow-circle-right"></i></a>
                </div>
              </div>

              <div class="col-lg-6 col-12">
                <div id="chart-spline" style="width: 100%; height: 400px;"></div>
              </div>
              <div class="col-lg-6 col-12">
                <div id="chart-pie" style="width: 100%; height: 400px;"></div>
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