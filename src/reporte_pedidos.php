<?php
session_start();
require 'conexion.php';

include 'mensajes.php';


// Verificar sesión y obtener usuario_id
if (!isset($_SESSION['usuario_id'])) {
  header('Location: login.php');
  exit;
}

// Obtener empresas activas
$empresas = $conn->query("SELECT id, nom_empresa FROM empresas WHERE activa = 1 ORDER BY nom_empresa");
if (!$empresas) {
  die("Error al obtener empresas: " . $conn->error);
}

// Procesar búsqueda de formatos
$formatos = [];
if (isset($_GET['busqueda']) && !empty(trim($_GET['busqueda']))) {
  $busqueda = "%" . $conn->real_escape_string(trim($_GET['busqueda'])) . "%";
  $empresa_id = isset($_GET['empresa_id']) ? (int)$_GET['empresa_id'] : 0;

  $query = "SELECT f.id, f.titulo, f.descripcion, f.dimensiones, f.copias_por_juego, 
                     e.nom_empresa, GROUP_CONCAT(a.nombre SEPARATOR ', ') as acabados
              FROM formatos f
              JOIN empresas e ON f.empresa_id = e.id
              LEFT JOIN formatos_acabados fa ON f.id = fa.formato_id
              LEFT JOIN acabados a ON fa.acabado_id = a.id
              WHERE (f.titulo LIKE ? OR f.descripcion LIKE ?)";

  if ($empresa_id > 0) {
    $query .= " AND f.empresa_id = ?";
  }

  $query .= " GROUP BY f.id ORDER BY f.titulo";

  $stmt = $conn->prepare($query);
  if (!$stmt) {
    die("Error al preparar consulta: " . $conn->error);
  }

  if ($empresa_id > 0) {
    $stmt->bind_param("ssi", $busqueda, $busqueda, $empresa_id);
  } else {
    $stmt->bind_param("ss", $busqueda, $busqueda);
  }

  if (!$stmt->execute()) {
    die("Error al ejecutar consulta: " . $stmt->error);
  }

  $result = $stmt->get_result();
  $formatos = $result->fetch_all(MYSQLI_ASSOC);
}

// Procesar envío del pedido
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $conn->begin_transaction();

  try {
    // Validar datos básicos
    if (!isset($_POST['empresa_id']) || !is_numeric($_POST['empresa_id'])) {
      throw new Exception("Seleccione una empresa válida");
    }

    if (!isset($_POST['items']) || !is_array($_POST['items']) || count($_POST['items']) === 0) {
      throw new Exception("Debe agregar al menos un formato al pedido");
    }

    // 1. Crear el pedido principal
    $stmt = $conn->prepare("INSERT INTO pedidos (empresa_id, observaciones_generales, usuario_id) VALUES (?, ?, ?)");
    if (!$stmt) {
      throw new Exception("Error al preparar consulta: " . $conn->error);
    }

    $observaciones = isset($_POST['observaciones_generales']) ? $_POST['observaciones_generales'] : '';

    $stmt->bind_param(
      "isi",
      $_POST['empresa_id'],
      $observaciones,
      $_SESSION['usuario_id']
    );

    if (!$stmt->execute()) {
      throw new Exception("Error al crear pedido: " . $stmt->error);
    }

    $pedido_id = $conn->insert_id;

    // 2. Insertar items del pedido
    $stmt = $conn->prepare("INSERT INTO pedidos_items (pedido_id, formato_id, cantidad, observaciones) VALUES (?, ?, ?, ?)");
    if (!$stmt) {
      throw new Exception("Error al preparar consulta: " . $conn->error);
    }

    foreach ($_POST['items'] as $item) {
      // Validar cada item
      if (
        !isset($item['formato_id'], $item['cantidad']) ||
        !is_numeric($item['formato_id']) ||
        !is_numeric($item['cantidad']) ||
        $item['cantidad'] <= 0
      ) {
        throw new Exception("Datos de formato no válidos");
      }

      $obs = isset($item['observaciones']) ? $item['observaciones'] : '';

      $stmt->bind_param(
        "iiis",
        $pedido_id,
        $item['formato_id'],
        $item['cantidad'],
        $obs
      );

      if (!$stmt->execute()) {
        throw new Exception("Error al agregar item: " . $stmt->error);
      }
    }

    $conn->commit();
    $_SESSION['exito'] = "Pedido #$pedido_id registrado correctamente";
    header('Location: reporte_pedidos.php');
    exit;
  } catch (Exception $e) {
    $conn->rollback();
    $error = $e->getMessage();
  }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Registro de Pedidos</title>
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
  <link href="../public/css/output.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
  <script>
    // Carrito de pedidos
    let carrito = [];

    function agregarAlCarrito(formatoId, titulo, dimensiones, copias, acabados) {
      // Verificar si ya existe
      const existe = carrito.some(item => item.id == formatoId);
      if (existe) {
        alert('Este formato ya está en el pedido');
        return;
      }

      // Agregar nuevo item
      carrito.push({
        id: formatoId,
        titulo: titulo,
        dimensiones: dimensiones,
        copias: copias,
        acabados: acabados || 'Ninguno',
        cantidad: 1,
        observaciones: ''
      });

      actualizarCarrito();
    }

    function eliminarDelCarrito(index) {
      if (confirm('¿Eliminar este formato del pedido?')) {
        carrito.splice(index, 1);
        actualizarCarrito();
      }
    }

    function actualizarCarrito() {
      const carritoHTML = document.getElementById('carrito-pedido');
      const btnRegistrar = document.querySelector('button[type="submit"]');

      if (carrito.length === 0) {
        carritoHTML.innerHTML = '<p class="text-center py-4">No hay items en el pedido</p>';
        btnRegistrar.disabled = true;
        return;
      }

      carritoHTML.innerHTML = '';
      btnRegistrar.disabled = false;

      carrito.forEach((item, index) => {
        const itemHTML = `
                <div class="mb-4 border-b border-gray-300 pb-4">
                    <div class="flex items-center justify-between mb-2">
                        <div>
                            <p class="text-sm">ITEM ${index + 1}</p>
                            <h4 class="font-medium">${escapeHtml(item.titulo)}</h4>
                            <p class="text-xs">Dimensiones: ${escapeHtml(item.dimensiones)}</p>
                            <p class="text-xs">Copias: ${item.copias}</p>
                            <p class="text-xs">Acabados: ${escapeHtml(item.acabados)}</p>
                        </div>
                        <div>
                            <button type="button" onclick="eliminarDelCarrito(${index})" 
                                class="bg-red-500 w-10 h-10 rounded-lg flex items-center justify-center cursor-pointer hover:bg-red-600 transition">
                                <span class="material-icons text-white">delete</span>
                            </button>
                        </div>
                    </div>
                    
                    <input type="hidden" name="items[${index}][formato_id]" value="${item.id}">
                    
                    <div class="form-group mb-4">
                        <label class="block text-sm mb-1">Cantidad</label>
                        <input type="number" name="items[${index}][cantidad]" min="1" value="${item.cantidad}" required
                            class="w-full p-2 border border-gray-400 rounded bg-black/30"
                            onchange="carrito[${index}].cantidad = this.value">
                    </div>
                    
                    <div class="mb-2">
                        <label class="block text-sm mb-1">Observaciones</label>
                        <textarea name="items[${index}][observaciones]" rows="2"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md bg-black/30"
                            onchange="carrito[${index}].observaciones = this.value">${escapeHtml(item.observaciones)}</textarea>
                    </div>
                </div>
            `;
        carritoHTML.innerHTML += itemHTML;
      });
    }

    // Función para escapar HTML
    function escapeHtml(unsafe) {
      if (!unsafe) return '';
      return unsafe.toString()
        .replace(/&/g, "&amp;")
        .replace(/</g, "&lt;")
        .replace(/>/g, "&gt;")
        .replace(/"/g, "&quot;")
        .replace(/'/g, "&#039;");
    }
  </script>
</head>

<body class="font-roboto bg-black/65 text-white">
  <div class="bg-gray-100">
    <div class="mx-auto p-4 flex justify-between">
      <div><img src="/modelpedidos/public/images/logo-atlanta.png" alt="" class="h-5"></div>
      <div><span class="material-icons text-secundario text-4xl">menu</span></div>
    </div>
  </div>

  <div class="container p-4 max-w-2xl mx-auto">
    <div class="flex items-center mb-6 mt-2">
      <span class="material-icons text-destacado text-4xl">arrow_circle_right</span>
      <span class="pl-2">Registro de Pedidos</span>
    </div>

    <?php if (isset($error)): ?>
      <div class="bg-red-500 text-white p-3 mb-4 rounded"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <div class="max-w-2xl mx-auto p-6 bg-black/25 rounded-lg shadow-md">
      <form method="GET" action="registro_pedidos.php" class="mb-6">
        <div class="mb-4">
          <label for="empresa_id" class="block text-sm mb-1">Seleccione empresa:</label>
          <select id="empresa_id" name="empresa_id" required
            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-advertencia bg-black/30">
            <option value="">Todas las empresas</option>
            <?php while ($empresa = $empresas->fetch_assoc()): ?>
              <option value="<?= $empresa['id'] ?>"
                <?= isset($_GET['empresa_id']) && $_GET['empresa_id'] == $empresa['id'] ? 'selected' : '' ?>>
                <?= htmlspecialchars($empresa['nom_empresa']) ?>
              </option>
            <?php endwhile; ?>
          </select>
        </div>

        <div class="form-group text-white/70 mb-4">
          <label for="busqueda" class="block text-sm mb-2">Buscar formato</label>
          <div class="flex">
            <input type="text" id="busqueda" name="busqueda" value="<?= htmlspecialchars($_GET['busqueda'] ?? '') ?>"
              class="w-full p-2 border border-gray-400 rounded focus:outline-none focus:ring-1 focus:ring-advertencia bg-black/30"
              placeholder="Título o descripción" required>
            <button type="submit"
              class="w-12 bg-complemento text-white px-2 rounded-md hover:bg-destacado transition duration-300 focus:outline-none focus:ring-1 focus:ring-offset-1 focus:ring-destacado">
              <span class="material-icons">search</span>
            </button>
          </div>
        </div>
      </form>

      <?php if (!empty($formatos)): ?>
        <h4 class="mb-4">Resultados de la Búsqueda</h4>
        <div id="resp_busq_empresas" class="mb-8">
          <?php foreach ($formatos as $formato): ?>
            <div class="flex justify-between items-center mb-4 border-b border-gray-300 pb-5">
              <div>
                <h5 class="font-medium"><?= htmlspecialchars($formato['titulo']) ?></h5>
                <p class="text-xs">Empresa: <?= htmlspecialchars($formato['nom_empresa']) ?></p>
                <p class="text-xs">Dimensiones: <?= htmlspecialchars($formato['dimensiones']) ?></p>
                <p class="text-xs">Copias por juego: <?= $formato['copias_por_juego'] ?></p>
                <p class="text-xs">Acabados: <?= $formato['acabados'] ?? 'Ninguno' ?></p>
                <?php if (!empty($formato['descripcion'])): ?>
                  <p class="text-xs mt-2">Descripción: <?= htmlspecialchars($formato['descripcion']) ?></p>
                <?php endif; ?>
              </div>
              <div>
                <button type="button"
                  onclick="agregarAlCarrito(
                                        <?= $formato['id'] ?>, 
                                        '<?= addslashes($formato['titulo']) ?>', 
                                        '<?= addslashes($formato['dimensiones']) ?>', 
                                        '<?= $formato['copias_por_juego'] ?>', 
                                        '<?= addslashes($formato['acabados'] ?? '') ?>'
                                    )"
                  class="bg-complemento w-10 h-10 rounded-lg flex items-center justify-center cursor-pointer hover:bg-destacado transition duration-300"
                  title="Añadir al pedido">
                  <span class="material-icons text-white">add</span>
                </button>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>

      <form method="POST" action="registro_pedidos.php" class="bg-black/25 rounded-lg p-4">
        <h3 class="text-lg font-medium mb-4">Detalles del Pedido</h3>

        <input type="hidden" name="empresa_id" value="<?= htmlspecialchars($_GET['empresa_id'] ?? '') ?>">

        <div id="carrito-pedido">
          <p class="text-center py-4">No hay items en el pedido</p>
        </div>

        <div class="mb-4">
          <label for="observaciones_generales" class="block text-sm mb-1">Observaciones Generales</label>
          <textarea id="observaciones_generales" name="observaciones_generales" rows="3"
            class="w-full px-3 py-2 border border-gray-300 rounded-md bg-black/30"></textarea>
        </div>

        <button type="submit" disabled
          class="w-full bg-green-600 text-white py-2 px-4 rounded-md hover:bg-green-700 transition disabled:bg-gray-500">
          Registrar Pedido
        </button>
      </form>
    </div>
  </div>
</body>

</html>
