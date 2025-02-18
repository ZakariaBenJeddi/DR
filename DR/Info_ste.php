<?php

session_start();
error_reporting(0);
include('includes/dbconnection.php');

?>
<div class=" row card-body">
  <?php
  $eid2=$_POST['edit_id2'];
  $ret2=mysqli_query($con,"select * from  ste where ids=".$eid2);
  while ($row=mysqli_fetch_array($ret2))
  { 
    ?> 
    <div class="col-md-4">
      <img src="ste_img/ste1.jpg" width="100" height="100">
    </div>
    <div class="col-md-8">
      <table>
      <tr>
          <th>Nom</th>
          <td>&nbsp;<?php  echo $row['raison_sociale'];?></td>
        </tr>
        <tr>
          <th>Secteur</th>
          <td><?php  echo $row['secteur'];?></td>
        </tr>
        <tr>
          <th>Gerent</th>
          <td><?php  echo $row['gerent'];?></td>
        </tr>
        <tr>
          <th>Adresse</th>
          <td><?php  echo $row['adresse'];?></td>
        </tr>


        <tr>
          <th>Fix</th>
          <td><?php  echo $row['fix'];?></td>
        </tr>
      </table>
    </div>
    <?php 
  } ?>
</div>