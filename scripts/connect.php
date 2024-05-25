<?php
$servername="localhost";
$username="root";
$password="";
$dbname="projektinz";

try{
    $conn = new mysqli($servername, $username, $password, $dbname);
  if ($conn->connect_error) {
    throw new Exception("Nie udało się połączyć: " . $conn->connect_error);
  }
  echo "Połączenie udane";
}
catch (Exception $e) {
echo "Błąd: " . $e->getMessage();
}