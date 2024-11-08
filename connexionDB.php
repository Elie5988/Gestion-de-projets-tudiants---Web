
<?php
  
  $hostName = "localhost";
  $userName = "root";
  $passWord = "";
  $dataBaseName = "nos_projet";
  $port = 3307 ;
  $con = mysqli_connect($hostName,$userName,"",$dataBaseName,$port);

  if (!$con) {
     echo "Vous n'êtes pas connecté à la base de données";
  }
?>
