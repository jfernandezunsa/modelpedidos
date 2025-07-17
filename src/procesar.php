<?php
require 'conexion.php';

// Valores obligatorios
$nom_empresa = $conn->real_escape_string($_POST['nom_empresa'] ?? '');
$nom_comprador = $conn->real_escape_string($_POST['nom_comprador'] ?? '');

// Valores opcionales (con validación)
$lugares_entrega = isset($_POST['lugares_entrega']) ? $conn->real_escape_string($_POST['lugares_entrega']) : null;
$celular_comprador = isset($_POST['celular_comprador']) ? $conn->real_escape_string($_POST['celular_comprador']) : null;
$correo_comprador = isset($_POST['correo_comprador']) ? $conn->real_escape_string($_POST['correo_comprador']) : null;

// Validar campos requeridos
if (empty($nom_empresa) || empty($nom_comprador)) {
    die("Error: Nombre de empresa y comprador son obligatorios");
}

// Validar formato de email si se proporcionó
if ($correo_comprador && !filter_var($correo_comprador, FILTER_VALIDATE_EMAIL)) {
    die("Error: Formato de email inválido");
}

// Insertar en la BD con TODOS los campos
$sql = "INSERT INTO empresas (nom_empresa, RUC, lugares_entrega, nom_comprador, celular_comprador, correo_comprador) 
        VALUES ('$nom_empresa', 
                '".($conn->real_escape_string($_POST['RUC'] ?? ''))."', 
                ".($lugares_entrega ? "'$lugares_entrega'" : "NULL").", 
                '$nom_comprador', 
                ".($celular_comprador ? "'$celular_comprador'" : "NULL").", 
                ".($correo_comprador ? "'$correo_comprador'" : "NULL").")";

if ($conn->query($sql)) {
    header('Location: exito.html');
    exit;
} else {
    die("Error al registrar: " . $conn->error);
}
?>