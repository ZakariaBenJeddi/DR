<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');
if (strlen($_SESSION['sid'] == 0)) {
  header('location:logout.php');
}

require("./cds_cdj_data.php");
$t  =  count($messages);

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
      <section class="content-header">
        <div class="container-fluid">
          <div class="row mb-2">
            <div class="col-sm-6">
              <h1 class="text-danger">problème Statistique</h1>
            </div>
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="dash_dr.php">Accueil</a></li>
                <li class="breadcrumb-item active">Gérer les Demande de prix</li>
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
                <div class="card-body mt-2 ">
                  <div class="d-flex justify-content-between align-items-start">
                    <div class="notif-cds d-flex flex-column w-50 me-2">
                      <h3 class="text-center mb-3">CDS</h3>
                      <div class="col-12 alert <?php echo !empty($messages) ? 'alert-danger text-white' : 'alert-success text-dark'; ?>">
                        <ul style="list-style-type: disc;">
                          <?php if (!empty($messages)) : ?>
                            <?php foreach ($messages as $message) : ?>
                              <li><?php echo $message; ?></li>
                            <?php endforeach; ?>
                          <?php else : ?>
                            Aucun message pour CDS.
                          <?php endif; ?>
                        </ul>
                      </div>
                    </div>

                    <div class="notif-cdj d-flex flex-column w-50 ms-2">
                      <h3 class="text-center mb-3">CDJ</h3>
                      <div class="col-12 alert <?php echo !empty($messages_cdj) ? 'alert-danger text-white' : 'alert-success text-dark'; ?>">
                        <ul style="list-style-type: disc;">
                          <?php if (!empty($messages_cdj)) : ?>
                            <?php foreach ($messages_cdj as $message_cdj) : ?>
                              <li><?php echo $message_cdj; ?></li>
                            <?php endforeach; ?>
                          <?php else : ?>
                            Aucun message pour CDJ.
                          <?php endif; ?>
                        </ul>
                      </div>
                    </div>
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

  <!-- ./wrapper -->
  <?php @include("includes/foot.php"); ?>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/exceljs/4.3.0/exceljs.min.js"></script>
  <script type="text/javascript">
    function expo() {
      // Capturez le texte saisi dans l'input de recherche
      var searchValue = $('#example1_filter input').val();

      // Appliquez le filtre à la DataTable
      var table = $('#example1').DataTable();
      table.search(searchValue).draw();
      // Créez un tableau pour stocker les données
      var data = [];
      // Obtenez les en-têtes de colonne de la DataTable
      var headers = [];
      table.columns().every(function() {
        if (this.header().textContent != "#" && this.header().textContent != "Besoin" && this.header().textContent != "Payment") {
          headers.push(this.header().textContent);
        }
      });
      data.push(headers);
      // Obtenez les données filtrées
      var filteredData = table.rows({
        filter: 'applied'
      }).data();
      // Parcourez les données filtrées et ajoutez-les au tableau
      filteredData.each(function(valueArray) {
        var rowData = [];
        valueArray.forEach(function(value, index) {
          if (index !== 0 && index !== 6 && index !== 7) {
            var cleanedValue;
            // Vérifiez si la cellule contient une balise <a>
            if (/<a[^>]*>([^<]+)<\/a>/.test(value)) {
              // Extraire le texte de la balise <a>
              cleanedValue = $(value).text();
            } else {
              cleanedValue = value;
            }
            // Supprimer "Facture" et "B.commande" des valeurs
            cleanedValue = cleanedValue.replace("B.commande", "").replace("Facture", "").trim();
            rowData.push(cleanedValue);
          }
        });
        data.push(rowData);
      });

      // Créez un nouveau classeur Excel
      var workbook = new ExcelJS.Workbook();
      var worksheet = workbook.addWorksheet('CALEDRIER');

      // Ajoutez les données au classeur Excel
      data.forEach(function(row) {
        worksheet.addRow(row);
      });

      // Génèrez un blob de données Excel
      workbook.xlsx.writeBuffer().then(function(buffer) {
        // Créez un objet blob
        var blob = new Blob([buffer], {
          type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
        });
        // Créez un objet URL pour le blob
        var url = window.URL.createObjectURL(blob);
        // Créez un lien pour le téléchargement du fichier Excel
        var a = document.createElement('a');
        a.href = url;
        a.download = 'Notification.xlsx';
        // Ajoutez le lien à la page et déclenchez le téléchargement
        document.body.appendChild(a);
        a.click();

        // Libérez l'URL de l'objet blob
        window.URL.revokeObjectURL(url);
      });
    }

    $(document).ready(function() {
      // Détruire l'instance existante de DataTables
      if ($.fn.DataTable.isDataTable('#example1')) {
        $('#example1').DataTable().destroy();
      }
      $('#example1').DataTable({
        "language": {
          "sProcessing": "Traitement en cours ...",
          "sLengthMenu": "Afficher _MENU_ lignes",
          "sZeroRecords": "Aucun résultat trouvé",
          "sEmptyTable": "Aucune donnée disponible",
          "sInfo": "Lignes _START_ à _END_ sur _TOTAL_",
          "sInfoEmpty": "Aucune ligne affichée",
          "sInfoFiltered": "(Filtrer un maximum de_MAX_)",
          "sSearch": "Chercher:",
          "sInfoThousands": ",",
          "sLoadingRecords": "Chargement...",
          "oPaginate": {
            "sFirst": "Premier",
            "sLast": "Dernier",
            "sNext": "Suivant",
            "sPrevious": "Précédent"
          },
          "oAria": {
            "sSortAscending": ": Trier par ordre croissant",
            "sSortDescending": ": Trier par ordre décroissant"
          }
        }
      });
    });
  </script>
</body>

</html>