<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);

$host = "localhost";
$user = "root";          // Usuario por defecto en XAMPP
$password = "";          // Contraseña por defecto (vacía)
$database = "modelPedidos_bd";

$conn = new mysqli($host, $user, $password, $database);

if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}
