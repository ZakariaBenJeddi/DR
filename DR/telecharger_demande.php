<?php
    if (isset($_GET['path'])) {
        // $p  = $_GET['path'];
        // $p = str_replace('\\\\', '\\', $p);  // Pour le cas des chemins avec doubles barres obliques inverses
        // $p = str_replace('\\', '/', $p);     // Convertir les barres obliques inverses en barres obliques normales
        $p = 'besoin/' . $_GET['path'];
        if (file_exists($p)) {
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="' . basename($p) . '"');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($p));
            readfile($p);
            exit;
        } else {
            echo "Le fichier n'existe pas.";
        }
    }
?>