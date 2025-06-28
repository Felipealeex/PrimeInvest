<?php
$servername = "localhost";
$username = "club1409_Barci_biel";
$password = "Ga31082004.";
$dbname = "club1409_clubedeinvestimentosprime";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Erro na conexão com o banco de dados: " . $conn->connect_error);
}
?>
