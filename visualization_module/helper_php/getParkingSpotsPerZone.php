<?php 
include_once('../parking.php');
$parking = new Parking();
$parking->retrieveAvailableSpotsPerZone("A");

?>