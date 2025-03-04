<?php

if (isset($_POST['chercher'])) {
  $dd = $_POST['dd'];
  $dd2 = $_POST['dd2'];
  $ff = $_POST['df'];
  $formation = !empty($_POST['formation']) ? $_POST['formation'] : null;
  $niveau = !empty($_POST['niveau']) ? $_POST['niveau'] : null;
  $efp = !empty($_POST['efp']) ? $_POST['efp'] : null;
  $filiere = !empty($_POST['filiere']) ? $_POST['filiere'] : null;
  $annee_etude = !empty($_POST['annee_etude']) ? $_POST['annee_etude'] : null;

  // Construire la requête de base
  $query = "SELECT 
      SUM(prevu) as total_prevu,
      SUM(stagiaires) as total_stagiaires,
      SUM(actif) as total_actif,
      SUM(transfert) as total_transfert,
      SUM(desistement) as total_desistement,
      SUM(redoublement) as total_redoublement
  FROM cds_v2 
  WHERE YEAR(date_creation) = :dd";
  //WHERE date_creation BETWEEN :dd AND :ff";

  // Construire la requête pour les cours du jour
  $query_cdj = "SELECT 
    SUM(prevu) as total_prevu_cdj,
    SUM(stagiaires) as total_stagiaires_cdj,
    SUM(actif) as total_actif_cdj,
    SUM(transfert) as total_transfert_cdj,
    SUM(desistement) as total_desistement_cdj,
    SUM(redoublement) as total_redoublement_cdj,
    SUM(passerelle) as total_passerelle_cdj
FROM cdj_v2 
WHERE YEAR(date_creation) = :dd";
//WHERE date_creation BETWEEN :dd AND :ff";

  // Ajouter les conditions de filtrage si elles sont définies
  $params = [':dd' => $dd2];
  $params_cdj = [':dd' => $dd2];
  // $params = [':dd' => $dd2, ':ff' => $ff];
  // $params_cdj = [':dd' => $dd2, ':ff' => $ff];

  if ($formation) {
    $query .= " AND type_formation = :formation";
    $params[':formation'] = $formation;

    $query_cdj .= " AND type_formation = :formation";
    $params_cdj[':formation'] = $formation;
  }
  if ($niveau) {
    $query .= " AND niveau = :niveau";
    $params[':niveau'] = $niveau;

    $query_cdj .= " AND niveau = :niveau";
    $params_cdj[':niveau'] = $niveau;
  }
  if ($efp) {
    $query .= " AND efp = :efp";
    $params[':efp'] = $efp;

    $query_cdj .= " AND efp = :efp";
    $params_cdj[':efp'] = $efp;
  }
  if ($filiere) {
    $query .= " AND filiere = :filiere";
    $params[':filiere'] = $filiere;

    $query_cdj .= " AND filiere = :filiere";
    $params_cdj[':filiere'] = $filiere;
  }
  if ($annee_etude) {
    $query .= " AND annee_etude = :annee_etude";
    $params[':annee_etude'] = $annee_etude;

    $query_cdj .= " AND annee_etude = :annee_etude";
    $params_cdj[':annee_etude'] = $annee_etude;
  }

  // Préparer et exécuter la requête
  $stmt = $dbh->prepare($query);
  $stmt->execute($params);
  $results = $stmt->fetch(PDO::FETCH_ASSOC);

  // Exécuter la requête
  $stmt_cdj = $dbh->prepare($query_cdj);
  $stmt_cdj->execute($params_cdj);
  $results_cdj = $stmt_cdj->fetch(PDO::FETCH_ASSOC);

  // Stocker les résultats dans des variables
  $total_prevu = $results['total_prevu'] ?? 0;
  $total_stagiaires = $results['total_stagiaires'] ?? 0;
  $total_actif = $results['total_actif'] ?? 0;
  $total_transfert = $results['total_transfert'] ?? 0;
  $total_desistement = $results['total_desistement'] ?? 0;
  $total_redoublement = $results['total_redoublement'] ?? 0;

  // Stocker les résultats
  $total_prevu_cdj = $results_cdj['total_prevu_cdj'] ?? 0;
  $total_stagiaires_cdj = $results_cdj['total_stagiaires_cdj'] ?? 0;
  $total_actif_cdj = $results_cdj['total_actif_cdj'] ?? 0;
  $total_transfert_cdj = $results_cdj['total_transfert_cdj'] ?? 0;
  $total_desistement_cdj = $results_cdj['total_desistement_cdj'] ?? 0;
  $total_redoublement_cdj = $results_cdj['total_redoublement_cdj'] ?? 0;
  $total_passerelle_cdj = $results_cdj['total_passerelle_cdj'] ?? 0;
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



  // messages

  $messages = [];

  // 1. Nombre d'actifs très inférieur au nombre d'inscriptions
  if ($total_actif < ($total_stagiaires * 0.5)) {
    $messages[] = "Attention : le nombre d'actifs ($total_actif) est très inférieur au nombre d'inscriptions ($total_stagiaires). Vérifiez les données.";
  }

  // 2. Désistements élevés
  if ($total_desistement > ($total_stagiaires * 0.3)) {
    $messages[] = "Le taux de désistement est élevé : $total_desistement désistements sur $total_stagiaires inscrits. Analysez les raisons possibles.";
  }

  // 3. Redoublements anormalement élevés
  if ($total_redoublement > ($total_stagiaires * 0.2)) {
    $messages[] = "Un nombre important de redoublements a été détecté : $total_redoublement redoublements. Une enquête est nécessaire.";
  }

  // 4. Transferts supérieurs aux actifs
  if ($total_transfert > $total_actif) {
    $messages[] = "Incohérence détectée : plus de transferts ($total_transfert) que de stagiaires actifs ($total_actif).";
  }

  // 5. Nombre de stagiaires supérieur au prévu
  if ($total_stagiaires > ($total_prevu * 1.2)) {
    $messages[] = "Le nombre de stagiaires inscrits ($total_stagiaires) dépasse largement la capacité prévue ($total_prevu). Risque de surcharge détecté.";
  }

  // 6. Vérification de l'équilibre des données
  $total_calculated = $total_actif + $total_desistement + $total_redoublement + $total_transfert;
  if ($total_calculated != $total_stagiaires) {
    $messages[] = "Déséquilibre détecté : la somme des actifs ($total_actif), désistements ($total_desistement), redoublements ($total_redoublement) et transferts ($total_transfert) ne correspond pas au nombre total d'inscrits ($total_stagiaires).";
  }







  // Requête par défaut
  $query_default_cdj = "SELECT 
      SUM(prevu) as total_prevu_cdj,
      SUM(stagiaires) as total_stagiaires_cdj,
      SUM(actif) as total_actif_cdj,
      SUM(transfert) as total_transfert_cdj,
      SUM(desistement) as total_desistement_cdj,
      SUM(redoublement) as total_redoublement_cdj,
      SUM(passerelle) as total_passerelle_cdj
  FROM cdj_v2";

  $stmt_default_cdj = $dbh->query($query_default_cdj);
  $results_default_cdj = $stmt_default_cdj->fetch(PDO::FETCH_ASSOC);

  $total_prevu_cdj = $results_default_cdj['total_prevu_cdj'] ?? 0;
  $total_stagiaires_cdj = $results_default_cdj['total_stagiaires_cdj'] ?? 0;
  $total_actif_cdj = $results_default_cdj['total_actif_cdj'] ?? 0;
  $total_transfert_cdj = $results_default_cdj['total_transfert_cdj'] ?? 0;
  $total_desistement_cdj = $results_default_cdj['total_desistement_cdj'] ?? 0;
  $total_redoublement_cdj = $results_default_cdj['total_redoublement_cdj'] ?? 0;
  $total_passerelle_cdj = $results_default_cdj['total_passerelle_cdj'] ?? 0;

  // Messages d'alerte
  $messages_cdj = [];

  if ($total_actif_cdj < ($total_stagiaires_cdj * 0.5)) {
    $messages_cdj[] = "Attention : le nombre d'actifs ($total_actif_cdj) est très inférieur au nombre d'inscriptions ($total_stagiaires_cdj).";
  }
  if ($total_desistement_cdj > ($total_stagiaires_cdj * 0.3)) {
    $messages_cdj[] = "Taux de désistement élevé : $total_desistement_cdj sur $total_stagiaires_cdj inscrits.";
  }
  if ($total_redoublement_cdj > ($total_stagiaires_cdj * 0.2)) {
    $messages_cdj[] = "Nombre important de redoublements : $total_redoublement_cdj.";
  }
  if ($total_transfert_cdj > $total_actif_cdj) {
    $messages_cdj[] = "Incohérence : plus de transferts ($total_transfert_cdj) que d'actifs ($total_actif_cdj).";
  }
  if ($total_stagiaires_cdj > ($total_prevu_cdj * 1.2)) {
    $messages_cdj[] = "Surcharge détectée : $total_stagiaires_cdj inscrits pour $total_prevu_cdj prévus.";
  }
  $total_calculated_cdj = $total_actif_cdj + $total_desistement_cdj + $total_redoublement_cdj + $total_transfert_cdj;
  if ($total_calculated_cdj != $total_stagiaires_cdj) {
    $messages_cdj[] = "Déséquilibre détecté : somme des catégories ($total_calculated_cdj) ≠ total inscrits ($total_stagiaires_cdj).";
  }
}
