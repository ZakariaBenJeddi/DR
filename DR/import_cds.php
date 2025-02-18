<?php
// require 'vendor/autoload.php';
// use PhpOffice\PhpSpreadsheet\IOFactory;

// include('includes/dbconnection.php');

// if ($con->connect_error) {
//     die("Connexion échouée : " . $con->connect_error);
// }

// if (isset($_FILES['excel_file'])) {
//     $file = $_FILES['excel_file']['tmp_name'];

//     if (!empty($file)) {
//         try {
//             $spreadsheet = IOFactory::load($file);
//             $sheet = $spreadsheet->getActiveSheet();
//             $data = $sheet->toArray();

//             // Ignorer la première ligne (entêtes)
//             array_shift($data);

//             foreach ($data as $row) {
//                 if (count($row) === 15) {
//                     $stmt = $con->prepare("INSERT INTO cds_v2 (dep, dr, code_efp, efp, niveau, code_filiere, filiere, type_formation, prevu, annee_etude, stagiaires, actif, transfert, desistement, redoublement) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                    
//                     $stmt->bind_param("ssssssssssiiiii", 
//                         $row[0],  // Dept
//                         $row[1],  // DR
//                         $row[2],  // CodeEFP
//                         $row[3],  // EFP
//                         $row[4],  // Niv
//                         $row[5],  // CodeFilière
//                         $row[6],  // Filière
//                         $row[7],  // Type Formation
//                         $row[8],  // Prévu
//                         $row[9],  // Annee Etude
//                         $row[10], // Stagiaires
//                         $row[11], // Actif
//                         $row[12], // Transfert
//                         $row[13], // Desistement
//                         $row[14]  // Redoublement
//                     );

//                     if (!$stmt->execute()) {
//                         throw new Exception("Erreur SQL : " . $stmt->error);
//                     }
//                 } else {
//                     throw new Exception("Le fichier Excel ne contient pas exactement 15 colonnes.");
//                 }
//             }
//             echo "Importation réussie !";
//             header('Location:cds_v2.php');
//         } catch (Exception $e) {
//             echo "Erreur : " . $e->getMessage();
//         }
//     } else {
//         echo "Erreur : fichier vide.";
//     }
// } else {
//     echo "Aucun fichier reçu.";
// }
?>

<?php
    // import_cds.php
require 'vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\IOFactory;

include('includes/dbconnection.php');

if ($con->connect_error) {
    die("Connexion échouée : " . $con->connect_error);
}

if (isset($_FILES['excel_file'])) {
    $file = $_FILES['excel_file']['tmp_name'];
    $response = array('success' => false, 'message' => '');

    if (!empty($file)) {
        try {
            $spreadsheet = IOFactory::load($file);
            $sheet = $spreadsheet->getActiveSheet();
            $data = $sheet->toArray();

            array_shift($data);

            foreach ($data as $row) {
                if (count($row) === 15) {
                    $stmt = $con->prepare("INSERT INTO cds_v2 (dep, dr, code_efp, efp, niveau, code_filiere, filiere, type_formation, prevu, annee_etude, stagiaires, actif, transfert, desistement, redoublement) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                    
                    $stmt->bind_param("ssssssssssiiiii", 
                        $row[0], $row[1], $row[2], $row[3], $row[4], 
                        $row[5], $row[6], $row[7], $row[8], $row[9], 
                        $row[10], $row[11], $row[12], $row[13], $row[14]
                    );

                    if (!$stmt->execute()) {
                        throw new Exception("Erreur SQL : " . $stmt->error);
                    }
                } else {
                    throw new Exception("Le fichier Excel ne contient pas exactement 15 colonnes.");
                }
            }
            $response['success'] = true;
            $response['message'] = "Importation réussie !";
        } catch (Exception $e) {
            $response['message'] = "Erreur : " . $e->getMessage();
        }
    } else {
        $response['message'] = "Erreur : fichier vide.";
    }
} else {
    $response['message'] = "Aucun fichier reçu.";
}

// Renvoyer la réponse en JSON
header('Content-Type: application/json');
echo json_encode($response);
?>
