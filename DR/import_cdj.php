<?php
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
                if (count($row) === 16) {
                    $stmt = $con->prepare("INSERT INTO cdj_v2 (dep, dr, code_efp, efp, niveau, code_filiere, filiere, type_formation, prevu, annee_etude, stagiaires, actif, transfert, desistement, redoublement, passerelle) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                    
                    $stmt->bind_param("ssssssssssiiiiii", 
                        $row[0], $row[1], $row[2], $row[3], $row[4], 
                        $row[5], $row[6], $row[7], $row[8], $row[9], 
                        $row[10], $row[11], $row[12], $row[13], $row[14], $row[15]
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
