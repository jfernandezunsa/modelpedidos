<?php
session_start();
if (!isset($_SESSION['usuario'])) {
  header('Location: login.php');
  exit;
}

require 'conexion.php';

// 1. Obtener ID del formato a editar
$id_formato = $_GET['id'] ?? null;
if (!$id_formato || !is_numeric($id_formato)) {
  die("ID de formato no válido");
}

// 2. Obtener datos actuales del formato
$stmt = $conn->prepare("SELECT f.*, e.nom_empresa 
                       FROM formatos f 
                       JOIN empresas e ON f.empresa_id = e.id 
                       WHERE f.id = ?");
$stmt->bind_param("i", $id_formato);
$stmt->execute();
$formato = $stmt->get_result()->fetch_assoc();

if (!$formato) {
  die("Formato no encontrado");
}

// 3. Obtener acabados seleccionados
$stmt = $conn->prepare("SELECT a.nombre 
                       FROM acabados a 
                       JOIN formatos_acabados fa ON a.id = fa.acabado_id 
                       WHERE fa.formato_id = ?");
$stmt->bind_param("i", $id_formato);
$stmt->execute();
$acabados_seleccionados = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$acabados_seleccionados = array_column($acabados_seleccionados, 'nombre');

// 4. Obtener todos los acabados disponibles
$acabados = $conn->query("SELECT nombre FROM acabados")->fetch_all(MYSQLI_ASSOC);

// 5. Obtener archivos asociados
$archivos = $conn->query("SELECT tipo, nombre_archivo, ruta_archivo 
                         FROM archivos_formatos 
                         WHERE formato_id = $id_formato")->fetch_all(MYSQLI_ASSOC);
$archivos = array_column($archivos, null, 'tipo');
?>

<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Editar Formato</title>
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
  <link href="../public/css/output.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
</head>

<body class="font-roboto bg-black/65 text-white">
  <div class="bg-gray-100">
    <div class="mx-auto p-4 flex justify-between">
      <div><img src="/modelpedidos/public/images/logo-atlanta.png" alt="" class="h-5"></div>
      <div><span class="material-icons text-secundario text-4xl">menu</span></div>
    </div>
  </div>

  <div class="container mx-auto p-4 max-w-2xl">
    <div class="flex items-center mb-6 mt-2">
      <span class="material-icons text-destacado text-4xl">arrow_circle_right</span>
      <span class="pl-2">Editar Formato: <?= htmlspecialchars($formato['titulo']) ?></span>
    </div>

    <?php if (isset($_GET['error'])): ?>
      <div class="bg-red-500 text-white p-3 mb-4 rounded"><?= htmlspecialchars($_GET['error']) ?></div>
    <?php endif; ?>

    <form action="procesar_edicion_formato.php" method="POST" enctype="multipart/form-data" class="max-w-2xl mx-auto p-6 bg-black/25 rounded-lg shadow-md">
      <input type="hidden" name="formato_id" value="<?= $id_formato ?>">

      <!-- Título -->
      <div class="form-group text-white/70 mb-4">
        <label for="titulo" class="block text-sm mb-2">Título <span class="text-advertencia">*</span></label>
        <input type="text" id="titulo" name="titulo" required
          value="<?= htmlspecialchars($formato['titulo']) ?>"
          class="w-full p-2 border border-gray-400 rounded focus:outline-none focus:ring-1 focus:ring-advertencia bg-black/30">
      </div>

      <!-- Descripción -->
      <div class="mb-4">
        <label for="descripcion" class="block text-sm mb-1">Descripción</label>
        <textarea id="descripcion" name="descripcion" rows="4"
          class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-advertencia bg-black/30"><?= htmlspecialchars($formato['descripcion']) ?></textarea>
      </div>

      <!-- Dimensiones -->
      <div class="mb-4">
        <label for="dimensiones" class="block text-sm mb-1">Dimensiones</label>
        <select id="dimensiones" name="dimensiones" required
          class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-advertencia bg-black/30">
          <option value="A6" <?= $formato['dimensiones'] == 'A6' ? 'selected' : '' ?>>A6 (10.5cm x 14.7cm)</option>
          <option value="A5" <?= $formato['dimensiones'] == 'A5' ? 'selected' : '' ?>>A5 (14.7cm x 21cm)</option>
          <option value="A4" <?= $formato['dimensiones'] == 'A4' ? 'selected' : '' ?>>A4 (21cm x 29.7cm)</option>
          <option value="Carta" <?= $formato['dimensiones'] == 'Carta' ? 'selected' : '' ?>>Carta (21.6cm x 27.9cm)</option>
          <option value="Oficio" <?= $formato['dimensiones'] == 'Oficio' ? 'selected' : '' ?>>Oficio (21.6cm x 35.6cm)</option>
        </select>
      </div>

      <!-- Copias por juego -->
      <div class="mb-4">
        <label for="cpxjuego" class="block text-sm mb-1">Copias por juego</label>
        <select id="cpxjuego" name="cpxjuego" required
          class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-advertencia bg-black/30">
          <?php for ($i = 1; $i <= 5; $i++): ?>
            <option value="<?= $i ?>" <?= $formato['copias_por_juego'] == $i ? 'selected' : '' ?>><?= $i ?></option>
          <?php endfor; ?>
        </select>
      </div>

      <!-- Acabados -->
      <fieldset class="mb-4">
        <legend class="text-sm mb-1">Acabados</legend>
        <div class="space-y-2 flex flex-col ml-4">
          <?php foreach ($acabados as $acabado): ?>
            <label class="inline-flex items-center">
              <input type="checkbox" name="acabados[]" value="<?= htmlspecialchars($acabado['nombre']) ?>"
                class="h-4 w-4 text-azul-oscuro focus:ring-destacado border-gray-300 rounded"
                <?= in_array($acabado['nombre'], $acabados_seleccionados) ? 'checked' : '' ?>>
              <span class="ml-2"><?= htmlspecialchars($acabado['nombre']) ?></span>
            </label>
          <?php endforeach; ?>
        </div>
      </fieldset>

      <!-- Archivo edición -->
      <div class="mb-6">
        <label for="archivo_edicion" class="block text-sm mb-1">Archivo de edición</label>
        <?php if (isset($archivos['edicion'])): ?>
          <div class="mb-2 text-xs">
            Archivo actual:
            <a href="<?= htmlspecialchars($archivos['edicion']['ruta_archivo']) ?>"
              target="_blank" class="text-blue-400 hover:underline">
              <?= htmlspecialchars($archivos['edicion']['nombre_archivo']) ?>
            </a>
            <input type="hidden" name="archivo_edicion_actual" value="<?= htmlspecialchars($archivos['edicion']['ruta_archivo']) ?>">
          </div>
        <?php endif; ?>
        <input type="file" id="archivo_edicion" name="archivo_edicion"
          accept=".pdf,.doc,.docx,.jpg,.png,.cdr,.ai"
          class="block w-full text-sm text-destacado file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-azul-claro file:text-complemento hover:file:bg-azul-oscuro">
      </div>

      <!-- Archivo PDF -->
      <div class="mb-6">
        <label for="archivo_pdf" class="block text-sm mb-1">Archivo PDF</label>
        <?php if (isset($archivos['visualizacion'])): ?>
          <div class="mb-2 text-xs">
            Archivo actual:
            <a href="<?= htmlspecialchars($archivos['visualizacion']['ruta_archivo']) ?>"
              target="_blank" class="text-blue-400 hover:underline">
              <?= htmlspecialchars($archivos['visualizacion']['nombre_archivo']) ?>
            </a>
            <input type="hidden" name="archivo_pdf_actual" value="<?= htmlspecialchars($archivos['visualizacion']['ruta_archivo']) ?>">
          </div>
        <?php endif; ?>
        <input type="file" id="archivo_pdf" name="archivo_pdf" accept=".pdf"
          class="block w-full text-sm text-destacado file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-azul-claro file:text-complemento hover:file:bg-azul-oscuro">
      </div>

      <!-- Versión -->
      <div class="form-group text-white/70 mb-4">
        <label for="version" class="block text-sm mb-2">Versión <span class="text-advertencia">*</span></label>
        <input type="text" id="version" name="version" required
          value="<?= htmlspecialchars($formato['version']) ?>"
          class="w-full p-2 border border-gray-400 rounded focus:outline-none focus:ring-1 focus:ring-advertencia bg-black/30">
      </div>

      <!-- Empresa -->
      <div class="mb-4">
        <label for="empresa_id" class="block text-sm mb-1">Empresa</label>
        <select id="empresa_id" name="empresa_id" required
          class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-advertencia bg-black/30">
          <?php
          $empresas = $conn->query("SELECT id, nom_empresa FROM empresas WHERE activa = 1");
          while ($empresa_option = $empresas->fetch_assoc()): ?>
            <option value="<?= $empresa_option['id'] ?>"
              <?= $empresa_option['id'] == $formato['empresa_id'] ? 'selected' : '' ?>>
              <?= htmlspecialchars($empresa_option['nom_empresa']) ?>
            </option>
          <?php endwhile; ?>
        </select>
      </div>

      <div class="flex justify-between mt-6">
        <a href="reporte_formatos.php" class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600 transition">
          Cancelar
        </a>
        <button type="submit" class="px-4 py-2 bg-destacado text-white rounded hover:bg-complemento transition">
          Guardar Cambios
        </button>
      </div>
    </form>
  </div>
</body>

</html>
