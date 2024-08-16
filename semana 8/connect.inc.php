<?php 

$host = "localhost";
$db_name = "SIN143";
$username = "root";
$password = "";

// Criar conexão
$conn = new mysqli($host, $username, $password, $db_name);

// Verificar conexão
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>