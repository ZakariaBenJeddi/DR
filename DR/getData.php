<?php
include 'includes/dbconnection.php';
header('Content-Type: application/json');
if (isset($_GET['iddevis'])) {
    $id = intval($_GET['iddevis']);
      $req="SELECT ste.raison_sociale,ste.adresse,ste.email,ste.tel,ste.fix,fact.nature,formation.duree,fact.prix,fact.nbr_personne,fact.datef,fact.lieu_deroulement,fact.facture,fact.devis,fact.bcmd FROM ste JOIN formation ON ste.ids = formation.ids JOIN fact ON ste.ids = fact.ids WHERE fact.iddevis = $_GET[iddevis] GROUP BY fact.iddevis;";
      $query=mysqli_query($con,$req);
      $row=mysqli_fetch_array($query);
      $result=$row;
      echo json_encode($result);
}else if (isset($_GET['id_d'])) {
    $id = intval($_GET['id_d']);
    $req="SELECT demande_prix.*, fournisseur.raison_sociale FROM demande_prix INNER JOIN fournisseur ON fournisseur.idfour = demande_prix.idfour WHERE demande_prix.id_d = $_GET[id_d]";
    $query=mysqli_query($con,$req);
    $row=mysqli_fetch_array($query);
    $result=$row;
    echo json_encode($result);
}else if (isset($_GET['id_d2'])) {
  $id = intval($_GET['id_d2']);
  $req="SELECT rest_payer.*, fournisseur.raison_sociale FROM rest_payer INNER JOIN fournisseur ON fournisseur.idfour = rest_payer.idfour WHERE rest_payer.id_d = $_GET[id_d2]";
  $query=mysqli_query($con,$req);
  $row=mysqli_fetch_array($query);
  $result=$row;
  echo json_encode($result);
}
else if (isset($_GET['id_f'])) {
  $id = intval($_GET['id_f']);
  $req="SELECT facture_payer.*, fournisseur.raison_sociale FROM facture_payer INNER JOIN fournisseur ON fournisseur.idfour = facture_payer.idfour WHERE facture_payer.id_f = $_GET[id_f]";
  $query=mysqli_query($con,$req);
  $row=mysqli_fetch_array($query);
  $result=$row;
    echo json_encode($result);
} else {
    echo json_encode(['error' => 'ID manquant']);
}
?>