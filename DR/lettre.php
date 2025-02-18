<?php

function convertirMontantEnLettres($montant) {
    $unites = array('', 'un', 'deux', 'trois', 'quatre', 'cinq', 'six', 'sept', 'huit', 'neuf', 'dix', 'onze', 'douze', 'treize', 'quatorze', 'quinze', 'seize', 'dix-sept', 'dix-huit', 'dix-neuf');
    $dizaines = array('', '', 'vingt', 'trente', 'quarante', 'cinquante', 'soixante', 'soixante-dix', 'quatre-vingt', 'quatre-vingt-dix');
    
   
    $parties = explode('.', $montant);
    $montantEntier = intval($parties[0]);
    $montantDecimal = isset($parties[1]) ? intval($parties[1]) : 0;
    
 
    $enLettres = convertirNombre($montantEntier, $unites, $dizaines);
    

    if ($montantDecimal > 0) {
        $enLettres .= ' et ' . convertirNombre($montantDecimal, $unites, $dizaines);
    }
    
    return $enLettres;
}

function convertirNombre($nombre, $unites, $dizaines) {
    
    if ($nombre == 0) {
        return "zéro";
    }
    
    $enLettres = '';
    
   
    $milliers = floor($nombre / 1000);
    if ($milliers > 0) {
        $enLettres .= $unites[$milliers] . ' mille ';
        $nombre %= 1000;
    }
    
    
    $centaines = floor($nombre / 100);
    if ($centaines > 0) {
        $enLettres .= $unites[$centaines] . ' cent ';
        $nombre %= 100;
    }
    

    if ($nombre > 0) {
        if ($nombre <= 19) {
            $enLettres .= $unites[$nombre];
        } else {
            $dizaine = floor($nombre / 10);
            $enLettres .= $dizaines[$dizaine];
            $unite = $nombre % 10;
            if ($unite > 0) {
                if ($dizaine == 7 || $dizaine == 9) {
                    $enLettres .= '-' . $unites[10];
                }
                $enLettres .= '-' . $unites[$unite];
            }
        }
    }
    
    return $enLettres;
}


$montant = "2443";
echo convertirMontantEnLettres($montant).' Dirhams';

?>
