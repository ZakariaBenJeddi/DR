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
      <div class="card-header">
        <form method="post">
          <div class="row">
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            <div class="form-group col-md-3">
              <label for="names">Selectionner la Date de Debut</label>
              <input type="date" class="form-control" id="dd" name="dd" placeholder="Nom" required>
            </div>
            <div class="form-group col-md-3">
              <label for="prenom">Selectionner la Date De Fin</label>
              <input type="date" class="form-control" id="df" name="df" placeholder="Prenom" required>
            </div>
            <!-- </div> -->
            <div class="form-group col-md-3 mt-3">
              <button style="color:blue" class="btn mt-3" type="submit" name="chercher" id="ch"><i class="fa fa-search"></i> Chercher</button>
              <button style="color:blue" class="btn mt-3" type="submit" name="cht" id="ch1"><i class="fa fa-search"></i> afficher tous</button>
            </div>

          </div>
          <div class="form-group col-md-3">
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
      </div>
      <!-- Main content -->
      <section class="content">
        <div class="container-fluid">
          <div class="row">
            <div class="col-12">
              <div class="card">
                <div class="card-header">
                  <!-- <h3 class="card-title">Gérer les Factures payer</h3> -->
                  <h3 class="card-title"></h3>
                  <div class="card-tools">
                    <button type="button" class="btn btn-sm btn-primary" onclick="expo()" id='btnexp'><span style="color: #fff;"><i class="fas fa-download"></i> Exporter</span>
                    </button>
                  </div>
                </div>
                <!-- /.card-header -->
                <div id="editData" class="modal fade">
                  <div class="modal-dialog modal-xl">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h5 class="modal-title">Détails Demande de prix</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                        </button>
                      </div>
                      <div class="modal-body" id="info_update">
                        <?php
                        @include("Edit_demande_prix.php");
                        ?>
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
                <!--   end modal -->
                <div id="editData2" class="modal fade">
                  <div class="modal-dialog ">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h5 class="modal-title">Détails Demande de prix</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                        </button>
                      </div>
                      <div class="modal-body" id="info_update2">
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
                <!--   end modal -->
                <div class="card-body mt-2 ">
                  <?php if (!empty($messages)  && count($messages) > 0) : ?>
                    <h3 class="text-center mb-3">CDS</h3>
                    <div class="col-12 alert alert-danger text-white">
                      <ul style="list-style-type: disc;">
                        <?php foreach ($messages as $message) : ?>
                          <li><?php echo $message; ?></li>
                        <?php endforeach; ?>
                      </ul>
                    </div>
                  <?php endif; ?>

                  <?php if (!empty($messages_cdj)) : ?>
                    <h3 class="text-center my-3">CDJ</h3>
                    <div class="col-12 alert alert-danger text-white">
                      <ul style="list-style-type: disc;">
                        <?php foreach ($messages_cdj as $message_cdj) : ?>
                          <li><?php echo $message_cdj; ?></li>
                        <?php endforeach; ?>
                      </ul>
                    </div>
                  <?php endif; ?>
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