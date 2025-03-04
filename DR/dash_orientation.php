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

// TABS
$activeTab = isset($_POST['active_tab']) ? $_POST['active_tab'] : 'cds';



// Initialize all required variables at the start of your script or when handling form submission
$messages = [];
$messages_cdj = [];
$ratio_actif = 0;
$ratio_actif_cdj = 0;

// AUTO SELECT (chercher dans le select box)
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

$sql_annee_etude = $dbh->query("SELECT DISTINCT annee_etude FROM cds_v2");
$sql_annee_etude->execute();
$annee_etude = $sql_annee_etude->fetchAll(PDO::FETCH_ASSOC);

include("./cds_cdj_data.php");

$ratio_actif = ($total_stagiaires > 0) ? ($total_actif / $total_stagiaires * 100) : 0;
$ratio_actif_cdj = ($total_stagiaires_cdj > 0) ? ($total_actif_cdj / $total_stagiaires_cdj * 100) : 0;
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
          <input type="hidden" name="active_tab" id="active_tab" value="cds">
          <div class="container-fluid bg-white mb-5 rounded py-2 mb-4">
            <div class="row align-items-center">
              <div class="form-group col-md-3">
                <label for="dd" class="form-label text-muted">Sélectionner la Date de Début</label>
                <!-- <input type="date" class="form-control" id="dd" name="dd" placeholder="Nom" required value="2025-02-28"> -->
                <select name="dd2" id="dd2" class="form-select">
                  <option value="">Sélectionner une année</option>
                </select>
              </div>
              <!-- <div class="form-group col-md-3">
                <label for="df" class="form-label text-muted">Sélectionner la Date De Fin</label>
                <input type="date" class="form-control" id="df" name="df" placeholder="Prenom" required value="2025-02-28">
              </div> -->
              <div class="col-lg-3 col-12 mb-lg-0">
                <label for="filiere" class="form-label text-muted">Annee Etude</label>
                <input class="form-control" name="annee_etude" list="annee_etude_datalist" placeholder="Annee Etude">
                <datalist id="annee_etude_datalist">
                  <?php foreach ($annee_etude as $ann_etu) { ?>
                    <option><?= $ann_etu['annee_etude'] ?></option>
                  <?php } ?>
                </datalist>
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
              <!-- <div class="col-lg-3 col-12 mb-lg-0">
                <label for="filiere" class="form-label text-muted">Annee Etude</label>
                <input class="form-control" name="annee_etude" list="annee_etude_datalist" placeholder="Annee Etude">
                <datalist id="annee_etude_datalist">
                  <?php //foreach ($annee_etude as $ann_etu) { 
                  ?>
                    <option><?php //$ann_etu['annee_etude'] 
                            ?></option>
                  <?php //} 
                  ?>
                </datalist>
              </div> -->
              <div class="row">
                <div class="form-group col-md-3 d-flex justify-content-around align-items-center mt-5">
                  <button type="submit" name="chercher" class="btn bg-blue text-white rounded-sm p-1" style="display: flex; align-items: center;">
                    <i class="fa fa-search" style="margin-right: 5px;"></i> Chercher
                  </button>
                  <button type="submit" name="chercher1" class="btn bg-blue text-white rounded-sm p-1" style="display: flex; align-items: center;">
                    <i class="fa fa-search" style="margin-right: 5px;"></i> Afficher tous
                  </button>
                </div>
              </div>
            </div>
          </div>
        </form>

        <input type="hidden" id="valueP" value="<?php echo $b[0] ?>">
        <input type="hidden" id="valueNP" value="<?php echo $b[1] ?>">

        <!-- Main content -->
        <section class="content bg-light">
          <div class="container-fluid bg-white shadow-sm mb-5 rounded p-4">
            <div class=""> <!-- card -->
              <div class="card-header p-2">
                <ul class="nav nav-pills display-flex justify-content-center align-items-center gap-5">
                  <li class="nav-item"><a class="nav-link active" href="#cdj" data-toggle="tab">Cours Du Jour</a></li>
                  <li class="nav-item"><a class="nav-link" href="#cds" data-toggle="tab">Cours Du Soir</a></li>
                </ul>
              </div><!-- /.card-header -->
              <div class=""> <!-- card-body -->
                <div class="tab-content">
                  <!-- COURS DU JOUR SECTION -->
                  <div class="active tab-pane" id="cdj">
                    <div class=""> <!-- container-fluid bg-white shadow-sm mb-5 rounded p-4 -->
                      <div class="row py-4 rounded">
                        <h1 class="text-center mb-5 fs-bold">
                          <strong>Section Cours Du Jour</strong>
                        </h1>
                        <div class="col-lg-3 col-6">
                          <div class="small-box bg-info">
                            <div class="inner">
                              <h4 class="text-bold"><strong><?= $total_prevu_cdj ?></strong></h4>
                              <p>Stagiaire Prévu</p>
                            </div>
                            <div class="icon">
                              <i class="fas fa-calendar-check text-white"></i>
                            </div>
                            <!-- <a href="list_demande_prix.php?mnt=red" class="small-box-footer">Plus d'infos <i class="fas fa-arrow-circle-right"></i></a> -->
                            <a href="cdj_v2.php" class="small-box-footer"><i class="fas fa-arrow-circle-right"></i></a>
                          </div>
                        </div>

                        <div class="col-lg-3 col-6">
                          <div class="small-box bg-info">
                            <div class="inner">
                              <h4 class="text-bold"><strong><?= $total_stagiaires_cdj ?></strong></h4>
                              <p>Stagiaire Inscrit</p>
                            </div>
                            <div class="icon">
                              <i class="fas fa-user-check text-white"></i>
                            </div>
                            <!-- <a href="list_demande_prix.php?mnt=red" class="small-box-footer">Plus d'infos <i class="fas fa-arrow-circle-right"></i></a> -->
                            <a href="cdj_v2.php" class="small-box-footer"><i class="fas fa-arrow-circle-right"></i></a>
                          </div>
                        </div>

                        <div class="col-lg-3 col-6">
                          <div class="small-box bg-info">
                            <div class="inner">
                              <h4 class="text-bold"><strong><?= $total_actif_cdj ?></strong></h4>
                              <p>Stagiaire Actif</p>
                            </div>
                            <div class="icon">
                              <i class="fas fa-user-graduate text-white"></i>
                            </div>
                            <!-- <a href="list_demande_prix.php?mnt=red" class="small-box-footer">Plus d'infos <i class="fas fa-arrow-circle-right"></i></a> -->
                            <a href="cdj_v2.php" class="small-box-footer"><i class="fas fa-arrow-circle-right"></i></a>
                          </div>
                        </div>

                        <div class="col-lg-3 col-6">
                          <div class="small-box bg-info">
                            <div class="inner">
                              <h4 class="text-bold"><strong><?= $total_desistement_cdj ?></strong></h4>
                              <p>Stagiaire Désistement</p>
                            </div>
                            <div class="icon">
                              <i class="fas fa-user-times text-white"></i>
                            </div>
                            <!-- <a href="list_demande_prix.php?mnt=red" class="small-box-footer">Plus d'infos <i class="fas fa-arrow-circle-right"></i></a> -->
                            <a href="cdj_v2.php" class="small-box-footer"><i class="fas fa-arrow-circle-right"></i></a>
                          </div>
                        </div>
                        <?php if (!empty($messages_cdj)) : ?>
                          <div class="col-12 alert alert-warning text-white">
                            <ul style="list-style-type: disc;">
                              <?php foreach ($messages_cdj as $message_cdj) : ?>
                                <li><?php echo $message_cdj; ?></li>
                              <?php endforeach; ?>
                            </ul>
                          </div>
                        <?php endif; ?>

                        <div class="col-lg-4 col-12 mt-lg-5 mt-0">
                          <div id="container4" style="width: 100%; height: 400px;"></div>
                        </div>
                        <div class="col-lg-4 col-12 mt-lg-5 mt-0">
                          <div id="container5" style="width: 100%; height: 400px;"></div>
                        </div>
                        <div class="col-lg-4 col-12 mt-lg-5 mt-0">
                          <figure class="highcharts-figure">
                            <div id="container6"></div>
                          </figure>
                        </div>
                      </div>
                    </div>
                  </div>
                  <!-- COURS DU SOIR SECTION -->
                  <div class="tab-pane" id="cds">
                    <div class=""> <!-- container-fluid bg-white shadow-sm mb-5 rounded p-4 -->
                      <div class="row py-4 rounded">
                        <h1 class="text-center mb-5 fs-bold">
                          <strong>Section Cours Du Soir</strong>
                        </h1>
                        <div class="col-lg-3 col-6">
                          <!-- small box -->
                          <div class="small-box bg-info">
                            <div class="inner">
                              <h4 class="text-bold"><strong><?= $total_prevu ?></strong></h4>
                              <p>Stagiaire Prévu</p>
                            </div>
                            <div class="icon">
                              <i class="fas fa-calendar-check text-white"></i>
                            </div>
                            <!-- <a href="list_demande_prix.php?mnt=red" class="small-box-footer">Plus d'infos <i class="fas fa-arrow-circle-right"></i></a> -->
                            <a href="cds_v2.php" class="small-box-footer"><i class="fas fa-arrow-circle-right"></i></a>
                          </div>
                        </div>
                        <div class="col-lg-3 col-6">
                          <!-- small box -->
                          <div class="small-box bg-info">
                            <div class="inner">
                              <h4 class="text-bold"><strong><?= $total_stagiaires ?></strong></h4>
                              <p>Stagiaire Inscrit</p>
                            </div>
                            <div class="icon">
                              <i class="fas fa-user-check text-white"></i>
                            </div>
                            <!-- <a href="list_demande_prix.php?mnt=red" class="small-box-footer">Plus d'infos <i class="fas fa-arrow-circle-right"></i></a> -->
                            <a href="cds_v2.php" class="small-box-footer"><i class="fas fa-arrow-circle-right"></i></a>
                          </div>
                        </div>
                        <div class="col-lg-3 col-6">
                          <!-- small box -->
                          <div class="small-box bg-info">
                            <div class="inner">
                              <h4 class="text-bold"><strong><?= $total_actif ?></strong></h4>
                              <p>Stagiaire Actif</p>
                            </div>
                            <div class="icon">
                              <i class="fas fa-user-graduate text-white"></i>
                            </div>
                            <!-- <a href="list_demande_prix.php?mnt=red" class="small-box-footer">Plus d'infos <i class="fas fa-arrow-circle-right"></i></a> -->
                            <a href="cds_v2.php" class="small-box-footer"><i class="fas fa-arrow-circle-right"></i></a>
                          </div>
                        </div>
                        <div class="col-lg-3 col-6">
                          <!-- small box -->
                          <div class="small-box bg-info">
                            <div class="inner">
                              <h4 class="text-bold"><strong><?= $total_desistement ?></strong></h4>
                              <p>Stagiaire Desistement</p>
                            </div>
                            <div class="icon">
                              <i class="fas fa-user-times text-white"></i>
                            </div>
                            <!-- <a href="list_demande_prix.php?mnt=red" class="small-box-footer">Plus d'infos <i class="fas fa-arrow-circle-right"></i></a> -->
                            <!-- <a href="cds_v2.php" class="small-box-footer"><i class="fas fa-arrow-circle-right"></i></a> -->
                            <a href="cds_v2.php" class="small-box-footer"><i class="fas fa-arrow-circle-right"></i></a>
                          </div>
                        </div>
                        <?php if (!empty($messages)  && count($messages) > 0) : ?>
                          <div class="col-12 alert alert-warning text-white">
                            <ul style="list-style-type: disc;">
                              <?php foreach ($messages as $message) : ?>
                                <li><?php echo $message; ?></li>
                              <?php endforeach; ?>
                            </ul>
                          </div>
                        <?php endif; ?>

                        <div class="col-lg-4 col-12 mt-lg-5 mt-0">
                          <div id="container" style="width: 100%; height: 400px;"></div>
                        </div>
                        <div class="col-lg-4 col-12 mt-lg-5 mt-0">
                          <div id="container3" style="width: 100%; height: 400px;"></div>
                        </div>
                        <div class="col-lg-4 col-12 mt-lg-5 mt-0">
                          <figure class="highcharts-figure">
                            <div id="container2"></div>
                          </figure>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
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

    <script>
      let select = document.getElementById("dd2");
      let anneeActuelle = new Date().getFullYear();

      for (let i = anneeActuelle; i >= anneeActuelle - 10; i--) {
        let option = document.createElement("option");
        option.value = i;
        option.textContent = i;
        select.appendChild(option);
      }
    </script>

    <!-- DISPLAY ACTIVE TABS APRES LA REFRECHE ET LE FILTRAGE -->
    <script>
      // Mettre à jour le champ caché quand on change d'onglet
      $('a[data-toggle="tab"]').on('shown.bs.tab', function(e) {
        const targetId = $(e.target).attr("href").substring(1); // enlève le #
        $("#active_tab").val(targetId);
      });

      // Au chargement de la page, activer le bon onglet
      $(document).ready(function() {
        // Récupérer l'onglet actif depuis PHP
        const activeTab = "<?php echo isset($_POST['active_tab']) ? $_POST['active_tab'] : 'cdj'; ?>";

        // Activer l'onglet correspondant
        $('a[href="#' + activeTab + '"]').tab('show');
      });
    </script>

    <!-- PieChartAnimation 1-->
    <script>
      Highcharts.chart('container', {
        chart: {
          type: 'bar'
        },
        title: {
          text: 'Cours Du Soir Statistique'
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
            text: 'Cours Du Soir',
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
          layout: 'horizontal', // Mettre en horizontal
          align: 'right', // Aligné à droite
          verticalAlign: 'bottom', // Aligné en bas
          x: 0, // Ajustement horizontal
          y: 0, // Ajustement vertical
          floating: false, // Ne pas flotter pour bien se placer
          borderWidth: 1,
          backgroundColor: Highcharts.defaultOptions.legend.backgroundColor || '#FFFFFF',
          shadow: true
        },
        credits: {
          enabled: false
        },
        series: [{
          name: 'Year 2023',
          data: [632, 727, 3202, 721, 100]
        }, {
          name: 'Year 2024',
          data: [814, 841, 3714, 726, 100]
        }, {
          name: 'Year 2025',
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

    <!-- PieChartAnimation 5 cdj-->
    <script>
      Highcharts.chart('container4', {
        chart: {
          type: 'bar'
        },
        title: {
          text: 'Cours Du Jour Statistique'
        },
        xAxis: {
          categories: ['Prevu', 'Inscription', 'Actif', 'Desistement', 'Deperdition', "Passerelle"],
          title: {
            text: null
          },
          gridLineWidth: 1,
          lineWidth: 0
        },
        yAxis: {
          min: 0,
          title: {
            text: 'Cours Du Jour',
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
          name: 'Year 2023',
          data: [632, 727, 3202, 721, 300, 432]
        }, {
          name: 'Year 2024',
          data: [814, 841, 3714, 726, 120, 492]
        }, {
          name: 'Year 2025',
          data: [
            <?= $total_prevu_cdj ?>,
            <?= $total_stagiaires_cdj ?>,
            <?= $total_actif_cdj ?>,
            <?= $total_desistement_cdj ?>,
            <?= $total_redoublement_cdj ?>,
            <?= $total_passerelle_cdj ?>
          ]
        }]
      });
    </script>

    <!-- PieChartAnimation 3-->
    <script>
      Highcharts.chart('container3', {
        chart: {
          type: 'column'
        },
        title: {
          text: 'Prévu vs Inscription CDS'
        },
        xAxis: {
          categories: ['Stagiaires'],
          crosshair: true,
          accessibility: {
            description: 'Countries'
          }
        },
        yAxis: {
          min: 0,
          title: {
            text: 'Prévu / Inscription'
          }
        },
        tooltip: {
          pointFormatter: function() {
            let prevu = <?php echo $total_prevu ?>;
            let inscription = <?php echo $total_stagiaires ?>;
            let percentage = (inscription / prevu * 100).toFixed(2);
            if (this.series.name === 'Prévu') {
              return `<b>${this.series.name}:</b> ${prevu} Stagiaires (100%)`;
            } else {
              return `<b>${this.series.name}:</b> ${inscription} Stagiaires (${percentage}%)`;
            }
          }
        },
        plotOptions: {
          column: {
            pointPadding: 0.2,
            borderWidth: 0,
            dataLabels: {
              enabled: true,
              formatter: function() {
                let prevu = <?php echo $total_prevu ?>;
                let inscription = <?php echo $total_stagiaires ?>;
                let percentage = (inscription / prevu * 100).toFixed(2);
                if (this.series.name === 'Prévu') {
                  return `${prevu} (100%)`;
                } else {
                  return `${inscription} (${percentage}%)`;
                }
              }
            }
          }
        },
        series: [{
            name: 'Prévu',
            data: [<?php echo $total_prevu ?>]
          },
          {
            name: 'Inscription',
            data: [<?php echo $total_stagiaires ?>]
          }
        ]
      });
    </script>

    <!-- PieChartAnimation 2 CDS-->
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
                    '<div style="font-size:14px">Prévu : <strong><?php echo $total_prevu ?></strong></br>' +
                    'Inscription : <strong><?php echo $total_stagiaires ?></strong></div>'
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
          text: 'Distribution des Stagiaires CDS'
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
            name: 'Actifs',
            y: <?php echo $total_actif ?>,
            color: (<?php echo $ratio_actif; ?> < 50) ? '#f1c40f' : (<?php echo $ratio_actif; ?> < 94) ? '#3498db' : '#2ecc71'
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

    <!-- PieChartAnimation 6 CDJ-->
    <script>
      Highcharts.chart('container6', {
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
                    '<div style="font-size:14px">Prévu : <strong><?php echo $total_prevu_cdj ?></strong></br>' +
                    'Inscription : <strong><?php echo $total_stagiaires_cdj ?></strong></div>'
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
          text: 'Distribution des Stagiaires CDJ'
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
            name: 'Actifs',
            y: <?php echo $total_actif_cdj ?>,
            // color: '#2ecc71'
            color: (<?php echo $ratio_actif_cdj; ?> < 50) ? '#f1c40f' : (<?php echo $ratio_actif_cdj; ?> < 94) ? '#3498db' : '#2ecc71'
          }, {
            name: 'Transferts',
            y: <?php echo $total_transfert_cdj ?>,
            color: '#f1c40f'
          }, {
            name: 'Désistements',
            y: <?php echo $total_desistement_cdj ?>,
            color: '#e74c3c'
          }, {
            name: 'Redoublements',
            y: <?php echo $total_redoublement_cdj ?>,
            color: '#f39c12'
          }, {
            name: 'Passerelle',
            y: <?php echo $total_passerelle_cdj ?>,
            color: '#e67e22'
          }]
        }]
      });
    </script>


    <!-- PieChartAnimation 5 CDJ-->
    <script>
      Highcharts.chart('container5', {
        chart: {
          type: 'column'
        },
        title: {
          text: 'Prévu vs Inscription CDJ'
        },
        xAxis: {
          categories: ['Stagiaires'],
          crosshair: true,
          accessibility: {
            description: 'Countries'
          }
        },
        yAxis: {
          min: 0,
          title: {
            text: 'Prévu / Inscription'
          }
        },
        tooltip: {
          pointFormatter: function() {
            let prevu = <?php echo $total_prevu_cdj ?>;
            let inscription = <?php echo $total_stagiaires_cdj ?>;
            let percentage = (inscription / prevu * 100).toFixed(2);

            if (this.series.name === 'Prévu') {
              return `<b>${this.series.name}:</b> ${prevu} Stagiaires (100%)`;
            } else {
              return `<b>${this.series.name}:</b> ${inscription} Stagiaires (${percentage}%)`;
            }
          }
        },
        plotOptions: {
          column: {
            pointPadding: 0.2,
            borderWidth: 0,
            dataLabels: {
              enabled: true,
              formatter: function() {
                let prevu = <?php echo $total_prevu_cdj ?>;
                let inscription = <?php echo $total_stagiaires_cdj ?>;
                let percentage = (inscription / prevu * 100).toFixed(2);

                if (this.series.name === 'Prévu') {
                  return `${prevu} (100%)`;
                } else {
                  return `${inscription} (${percentage}%)`;
                }
              }
            }
          }
        },
        series: [{
            name: 'Prévu',
            data: [<?php echo $total_prevu_cdj ?>]
          },
          {
            name: 'Inscription',
            data: [<?php echo $total_stagiaires_cdj ?>]
          }
        ]
      });
    </script>