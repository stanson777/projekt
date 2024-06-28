<?php
    $hostName='localhost';
    $dbUser='root';
    $dbPassword="";
    $dbName='lekarze';

    $conn=mysqli_connect($hostName,$dbUser,$dbPassword,$dbName);
    if(!$conn){
        die("Cos poszlo nie tak");
    }



?>