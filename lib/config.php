<?php
$servername = "localhost";
$username = "zackbyte";
$password = "regina2003";

$connect=mysqli_connect($servername,$username,$password,"despacho");
// Check connection
if (mysqli_connect_errno())
  {
  echo "Failed to connect to MySQL: " . mysqli_connect_error();
  }
?>