<?php
session_start();
require 'conexion.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);

// 1. Guardar datos básicos del formato
$stmt = $conn->prepare("INSERT INTO formatos (titulo, descripcion, dimensiones, copias_por_juego, version, empresa_id) VALUES (?, ?, ?, ?, ?, ?)");
$stmt->bind_param("sssssi", $_POST['titulo'], $_POST['descripcion'], $_POST['dimensiones'], $_POST['cpxjuego'], $_POST['versión'], $_POST['form_empresa']);
$stmt->execute();
$formato_id = $conn->insert_id;

// 2. Procesar acabados seleccionados
if (isset($_POST['acabados'])) {
  foreach ($_POST['acabados'] as $acabado) {
    $stmt = $conn->prepare("INSERT INTO formatos_acabados (formato_id, acabado_id) SELECT ?, id FROM acabados WHERE nombre = ?");
    $stmt->bind_param("is", $formato_id, $acabado);
    $stmt->execute();
  }
}

// 3. Subir archivos
function subirArchivo($input_name, $formato_id, $tipo)
{
  // Ruta absoluta desde la raíz del proyecto
  $baseDir = realpath(__DIR__ . '/../'); // Apunta a D:\xampp\htdocs\modelpedidos\
  $uploadDir = $baseDir . '/uploads/formatos/';

  // Crea la carpeta si no existe (con permisos 0777)
  if (!file_exists($uploadDir)) {
    mkdir($uploadDir, 0777, true); // "true" crea subcarpetas recursivamente
  }

  // Verifica errores de subida
  if (!isset($_FILES[$input_name]) || $_FILES[$input_name]['error'] !== UPLOAD_ERR_OK) {
    die("Error al subir el archivo: " . $_FILES[$input_name]['error']);
  }

  // Obtiene la extensión
  $fileExt = strtolower(pathinfo($_FILES[$input_name]['name'], PATHINFO_EXTENSION));
  $allowedTypes = ['pdf', 'doc', 'docx', 'jpg', 'png', 'cdr', 'ai'];

  if (!in_array($fileExt, $allowedTypes)) {
    die("Tipo de archivo no permitido.");
  }

  // Genera nombre único
  $fileName = "formato_{$formato_id}_{$tipo}_" . time() . ".{$fileExt}";
  $rutaFinal = $uploadDir . $fileName;

  // Debug: Muestra la ruta absoluta
  error_log("Intentando guardar en: " . $rutaFinal);

  // Mueve el archivo
  if (!move_uploaded_file($_FILES[$input_name]['tmp_name'], $rutaFinal)) {
    error_log("Error al mover. Ruta: " . $rutaFinal . ", Permisos: " . decoct(fileperms($uploadDir)));
    die("Error al mover el archivo. Verifica logs del servidor.");
  }

  return "uploads/formatos/" . $fileName; // Ruta relativa para la BD
}

// 4. Registrar archivos en BD
$stmt = $conn->prepare("INSERT INTO archivos_formatos (formato_id, tipo, nombre_archivo, ruta_archivo) VALUES (?, 'edicion', ?, ?), (?, 'visualizacion', ?, ?)");
$stmt->bind_param("ississ", $formato_id, $_FILES['archivo_edicion']['name'], $ruta_edicion, $formato_id, $_FILES['archivo_pdf']['name'], $ruta_pdf);
$stmt->execute();

header('Location: exito.php');

if ($stmt->execute()) {
  $_SESSION['mensaje'] = "Formato registrado correctamente.";
} else {
  $_SESSION['error'] = "Error al registrar el formato: " . $conn->error;
}
header('Location: exito_formato.php'); // Crea esta página para mostrar feedback.
