<?php

session_start();
error_reporting(0);
include('includes/dbconnection.php');

?>
<div class=" row card-body">
  <?php
  $eid2=$_POST['edit_id2'];
  $ret2=mysqli_query($con,"SELECT demande_prix.*, fournisseur.raison_sociale FROM demande_prix INNER JOIN fournisseur ON fournisseur.idfour = demande_prix.idfour where id_d=".$eid2);
  while ($row=mysqli_fetch_array($ret2))
  { 
    ?> 
    <div class="col-md-4">
      <img src="ste_img/ste1.jpg" width="100" height="100">
    </div>
    <div class="col-md-8">
      <table>
      <tr>
          <th>Fornisseur</th>
          <td>&nbsp;<?php  echo $row['raison_sociale'];?></td>
        </tr>
        <tr>
          <th>Montant</th>
          <td><?php  echo $row['montant'];?></td>
        </tr>

        <tr>
            <th>Besoin</th>
            <td>
                <a href="telecharger_demande.php?path=<?php echo htmlentities($row['besoin']);?>"><button class="btn btn-warning text-light btn-xs text-center">Telecharger</button></a>
            </td>
        </tr>
        <tr>
          <th>Date</th>
          <td><?php  echo $row['date'];?></td>
        </tr>
      </table>
    </div>
    <?php 
  } ?>
</div>