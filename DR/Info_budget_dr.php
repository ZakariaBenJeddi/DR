<?php

session_start();
error_reporting(0);
include('includes/dbconnection.php');

?>
<div class=" row card-body">
  <?php
  $eid2=$_POST['edit_id2'];
  $ret2=mysqli_query($con,"SELECT budget_dr.*, etablissement.nomE FROM budget_dr INNER JOIN etablissement ON etablissement.ide = budget_dr.id_e where id_dr=".$eid2);
  while ($row=mysqli_fetch_array($ret2))
  { 
    ?> 
    <div class="col-md-4">
      <img src="ste_img/ste1.jpg" width="100" height="100">
    </div>
    <div class="col-md-8">
      <table>
      <tr>
          <th>Etablissement</th>
          <td>&nbsp;<?php  echo $row['nomE'];?></td>
        </tr>
        <tr>
          <th>Rubrique</th>
          <td><?php  echo $row['rubrique'];?></td>
        </tr>
        <tr>
          <th>Montant</th>
          <td><?php  echo $row['montant'];?></td>
        </tr>

        <tr>
          <th>Consome</th>
          <td><?php  echo $row['consome'];?></td>
        </tr>
        <tr>
          <th>Reste</th>
          <td><?php  echo $row['reste'];?></td>
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