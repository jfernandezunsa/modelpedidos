<?php
session_start();
require 'conexion.php';

// Debug (opcional)
error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $username = $_POST['username'] ?? '';
  $password = $_POST['password'] ?? '';

  // 1. Validar campos vacíos
  if (empty($username) || empty($password)) {
    die("Por favor complete todos los campos");
  }

  // 2. Buscar usuario
  $stmt = $conn->prepare("SELECT * FROM usuarios WHERE username = ?");
  $stmt->bind_param("s", $username);
  $stmt->execute();
  $usuario = $stmt->get_result()->fetch_assoc();

  // 3. Verificar credenciales
  if ($usuario && password_verify($password, $usuario['password_hash'])) {
    $_SESSION['usuario'] = [
      'id' => $usuario['id'],
      'username' => $usuario['username'],
      'rol' => $usuario['rol']
    ];
    header('Location: reporte_empresas.php');
    exit;
  } else {
    header('Location: login.php?error=1');
    exit;
  }
}
