<?php

session_start();
error_reporting(0);
include('includes/dbconnection.php');

?>
<div class=" row card-body">
  <?php
  $eid2=$_POST['edit_id2'];
  $ret2=mysqli_query($con,"SELECT * FROM cds_v2 where id=".$eid2);
  while ($row=mysqli_fetch_array($ret2))
  { 
    ?> 
    <div class="col-md-12">
      <table>
      <tr>
          <th>DEPARTEMENT</th>
          <td>&nbsp;<?php  echo $row['dep'];?></td>
        </tr>
        <tr>
          <th>DR</th>
          <td><?php  echo $row['dr'];?></td>
        </tr>
        <tr>
          <th>Code EFP</th>
          <td><?php  echo $row['code_efp'];?></td>
        </tr>

        <tr>
          <th>EFP</th>
          <td><?php  echo $row['efp'];?></td>
        </tr>
        <tr>
          <th>Niveau</th>
          <td><?php  echo $row['niveau'];?></td>
        </tr>
        <tr>
          <th>Code Filiere</th>
          <td><?php  echo $row['code_filiere'];?></td>
        </tr>
        <tr>
          <th>Filiere</th>
          <td><?php  echo $row['filiere'];?></td>
        </tr>
        <tr>
          <th>Prevue</th>
          <td><?php  echo $row['prevu'];?></td>
        </tr>
        <tr>
          <th>Annee Etude</th>
          <td><?php  echo $row['annee_etude'];?></td>
        </tr>
        <tr>
          <th>Stagiaires</th>
          <td><?php  echo $row['stagiaires'];?></td>
        </tr>
        <tr>
          <th>Actif</th>
          <td><?php  echo $row['actif'];?></td>
        </tr>
        <tr>
          <th>Transfert</th>
          <td><?php  echo $row['transfert'];?></td>
        </tr>
        <tr>
          <th>Desistement</th>
          <td><?php  echo $row['desistement'];?></td>
        </tr>
        <tr>
          <th>Redoublement</th>
          <td><?php  echo $row['redoublement'];?></td>
        </tr>
      </table>
    </div>
    <?php 
  } ?>
</div>