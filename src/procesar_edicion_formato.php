<?php
session_start();
if (!isset($_SESSION['usuario'])) {
  header('Location: login.php');
  exit;
}

require 'conexion.php';

// Validar ID del formato
if (!isset($_POST['formato_id']) || !is_numeric($_POST['formato_id'])) {
  header('Location: reporte_formatos.php?error=ID+de+formato+inválido');
  exit;
}

$formato_id = (int)$_POST['formato_id'];

// Función para subir archivos
function subirArchivo($input_name, $formato_id, $tipo, $ruta_actual = null)
{
  // Si no se subió archivo, mantener el existente
  if (!isset($_FILES[$input_name]['error']) || $_FILES[$input_name]['error'] === UPLOAD_ERR_NO_FILE) {
    return $ruta_actual;
  }

  // Validar error en subida
  if ($_FILES[$input_name]['error'] !== UPLOAD_ERR_OK) {
    throw new Exception("Error al subir el archivo");
  }

  // Configurar directorio
  $uploadDir = __DIR__ . '/../uploads/formatos/';
  if (!file_exists($uploadDir)) {
    mkdir($uploadDir, 0777, true);
  }

  // Validar tipos de archivo
  $allowedTypes = [
    'edicion' => ['pdf', 'doc', 'docx', 'jpg', 'png', 'cdr', 'ai'],
    'visualizacion' => ['pdf']
  ];

  $fileExt = strtolower(pathinfo($_FILES[$input_name]['name'], PATHINFO_EXTENSION));
  if (!in_array($fileExt, $allowedTypes[$tipo])) {
    throw new Exception("Tipo de archivo no permitido");
  }

  // Generar nombre único
  $fileName = "formato_{$formato_id}_{$tipo}_" . time() . ".{$fileExt}";
  $rutaFinal = $uploadDir . $fileName;

  // Mover archivo
  if (!move_uploaded_file($_FILES[$input_name]['tmp_name'], $rutaFinal)) {
    throw new Exception("Error al mover el archivo");
  }

  // Eliminar archivo anterior si existe
  if ($ruta_actual && file_exists($ruta_actual)) {
    unlink($ruta_actual);
  }

  return "uploads/formatos/" . $fileName;
}

try {
  // 1. Actualizar datos básicos del formato
  $stmt = $conn->prepare("UPDATE formatos SET 
                          titulo = ?, 
                          descripcion = ?, 
                          dimensiones = ?, 
                          copias_por_juego = ?, 
                          version = ?, 
                          empresa_id = ? 
                          WHERE id = ?");

  $stmt->bind_param(
    "sssssii",
    $_POST['titulo'],
    $_POST['descripcion'],
    $_POST['dimensiones'],
    $_POST['cpxjuego'],
    $_POST['version'],
    $_POST['empresa_id'],
    $formato_id
  );
  $stmt->execute();

  // 2. Procesar acabados seleccionados
  // Eliminar todos los acabados existentes
  $conn->query("DELETE FROM formatos_acabados WHERE formato_id = $formato_id");

  // Insertar nuevos acabados
  if (isset($_POST['acabados'])) {
    $stmt = $conn->prepare("INSERT INTO formatos_acabados (formato_id, acabado_id) 
                              SELECT ?, id FROM acabados WHERE nombre = ?");

    foreach ($_POST['acabados'] as $acabado) {
      $stmt->bind_param("is", $formato_id, $acabado);
      $stmt->execute();
    }
  }

  // 3. Procesar archivos
  $ruta_edicion = isset($_POST['archivo_edicion_actual']) ? $_POST['archivo_edicion_actual'] : null;
  $ruta_pdf = isset($_POST['archivo_pdf_actual']) ? $_POST['archivo_pdf_actual'] : null;

  // Subir nuevos archivos si se proporcionaron
  $ruta_edicion = subirArchivo('archivo_edicion', $formato_id, 'edicion', $ruta_edicion);
  $ruta_pdf = subirArchivo('archivo_pdf', $formato_id, 'visualizacion', $ruta_pdf);

  // Actualizar registros de archivos
  $conn->query("DELETE FROM archivos_formatos WHERE formato_id = $formato_id");

  if ($ruta_edicion) {
    $nombre_edicion = isset($_FILES['archivo_edicion']) ?
      $_FILES['archivo_edicion']['name'] :
      basename($ruta_edicion);

    $conn->query("INSERT INTO archivos_formatos (formato_id, tipo, nombre_archivo, ruta_archivo) 
                     VALUES ($formato_id, 'edicion', '$nombre_edicion', '$ruta_edicion')");
  }

  if ($ruta_pdf) {
    $nombre_pdf = isset($_FILES['archivo_pdf']) ?
      $_FILES['archivo_pdf']['name'] :
      basename($ruta_pdf);

    $conn->query("INSERT INTO archivos_formatos (formato_id, tipo, nombre_archivo, ruta_archivo) 
                     VALUES ($formato_id, 'visualizacion', '$nombre_pdf', '$ruta_pdf')");
  }

  // Redirección exitosa
  header('Location: reporte_formatos.php?success=1');
  exit;
} catch (Exception $e) {
  // Redirección con error
  header("Location: editar_formato.php?id=$formato_id&error=" . urlencode($e->getMessage()));
  exit;
}
