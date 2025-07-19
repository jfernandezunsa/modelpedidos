<?php
session_start();
require 'conexion.php';

// Verificar sesión - CORRECCIÓN: Eliminé llave extra después del if
if (!isset($_SESSION['usuario'])) {
    header('Location: login.php');
    exit;
}

// Obtener empresas activas - CORRECCIÓN: Verifico que la consulta sea válida
$query_empresas = "SELECT id, nom_empresa FROM empresas WHERE activa = 1 ORDER BY nom_empresa";
$empresas = $conn->query($query_empresas);
if (!$empresas) {
    die("Error al obtener empresas: " . $conn->error);
}

// Procesar búsqueda de formatos - CORRECCIÓN: Sintaxis de arrays mejorada
$formatos = [];
if (isset($_GET['busqueda']) && !empty($_GET['busqueda'])) {
    $busqueda = "%" . $conn->real_escape_string($_GET['busqueda']) . "%";
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

// Procesar envío del pedido - CORRECCIÓN: Mejor manejo del array POST
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['items'])) {
    $conn->begin_transaction();

    try {
        // Validar empresa_id
        if (!isset($_POST['empresa_id']) || !is_numeric($_POST['empresa_id'])) {
            throw new Exception("Empresa no válida");
        }

        // 1. Crear el pedido principal
        $stmt = $conn->prepare("INSERT INTO pedidos (empresa_id, observaciones_generales, usuario_id) VALUES (?, ?, ?)");
        $stmt->bind_param(
            "isi",
            $_POST['empresa_id'],
            $_POST['observaciones_generales'],
            $_SESSION['usuario_id']
        );

        if (!$stmt->execute()) {
            throw new Exception("Error al crear pedido: " . $stmt->error);
        }

        $pedido_id = $conn->insert_id;

        // 2. Insertar items del pedido
        $stmt = $conn->prepare("INSERT INTO pedidos_items (pedido_id, formato_id, cantidad, observaciones) VALUES (?, ?, ?, ?)");

        foreach ($_POST['items'] as $item) {
            // Validar cada item
            if (
                !isset($item['formato_id'], $item['cantidad']) ||
                !is_numeric($item['formato_id']) ||
                !is_numeric($item['cantidad'])
            ) {
                throw new Exception("Datos de items no válidos");
            }

            $observaciones = isset($item['observaciones']) ? $item['observaciones'] : '';

            $stmt->bind_param(
                "iiis",
                $pedido_id,
                $item['formato_id'],
                $item['cantidad'],
                $observaciones
            );

            if (!$stmt->execute()) {
                throw new Exception("Error al agregar item: " . $stmt->error);
            }
        }

        $conn->commit();
        $_SESSION['exito'] = "Pedido registrado correctamente (ID: $pedido_id)";
        header('Location: reporte_pedidos.php');
        exit;
    } catch (Exception $e) {
        $conn->rollback();
        $error = "Error al registrar el pedido: " . $e->getMessage();
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
        // Función para manejar el carrito de pedidos
        let carrito = [];

        function agregarAlCarrito(formatoId, titulo, dimensiones, copias, acabados, descripcion) {
            // Verificar si ya existe en el carrito
            const existe = carrito.some(item => item.id === formatoId);
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
                acabados: acabados,
                descripcion: descripcion,
                cantidad: 1,
                observaciones: ''
            });

            actualizarCarrito();
        }

        function eliminarDelCarrito(index) {
            carrito.splice(index, 1);
            actualizarCarrito();
        }

        function actualizarCarrito() {
            const carritoHTML = document.getElementById('carrito-pedido');
            carritoHTML.innerHTML = '';

            if (carrito.length === 0) {
                carritoHTML.innerHTML = '<p class="text-center py-4">No hay items en el pedido</p>';
                return;
            }

            carrito.forEach((item, index) => {
                const itemHTML = `
                <div class="mb-4 border-b border-gray-300 pb-4">
                    <div class="flex items-center justify-between mb-2">
                        <div>
                            <p>ITEM ${index + 1}</p>
                            <h4 class="mb-2">${item.titulo}</h4>
                            <p class="text-xs">Dimensiones: ${item.dimensiones}</p>
                            <p class="text-xs">Copias por juego: ${item.copias}</p>
                            <p class="text-xs">Acabados: ${item.acabados || 'Ninguno'}</p>
                        </div>
                        <div>
                            <button type="button" onclick="eliminarDelCarrito(${index})" 
                                class="bg-complemento w-10 h-10 rounded-lg flex items-center justify-center cursor-pointer hover:bg-destacado transition duration-300" 
                                title="Eliminar del pedido">
                                <span class="material-icons text-white">delete</span>
                            </button>
                        </div>
                    </div>
                    
                    <input type="hidden" name="items[${index}][formato_id]" value="${item.id}">
                    
                    <div class="form-group text-white/70 mb-4">
                        <label for="cantidad-${index}" class="block text-sm mb-2">Cantidad</label>
                        <input type="number" id="cantidad-${index}" name="items[${index}][cantidad]" 
                            min="1" value="${item.cantidad}" required
                            class="w-full p-2 border border-gray-400 rounded focus:outline-none focus:ring-1 focus:ring-advertencia bg-black/30"
                            onchange="carrito[${index}].cantidad = this.value">
                    </div>
                    
                    <div class="mb-4">
                        <label for="observaciones-${index}" class="block text-sm mb-1">Observaciones</label>
                        <textarea id="observaciones-${index}" name="items[${index}][observaciones]" rows="2"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-advertencia bg-black/30"
                            onchange="carrito[${index}].observaciones = this.value">${item.observaciones}</textarea>
                    </div>
                </div>
            `;
                carritoHTML.innerHTML += itemHTML;
            });
        }
    </script>
</head>

<body class="font-roboto bg-black/65 text-white">
    <!--     <div class="bg-gray-100">
        <div class="mx-auto p-4 flex justify-between">
            <div><img src="/modelpedidos/public/images/logo-atlanta.png" alt="" class="h-5"></div>
            <div><span class="material-icons text-secundario text-4xl">menu</span></div>
        </div>
    </div> -->
    <?php include 'header.php'; ?>

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
                            <option value="<?= $empresa['id'] ?>" <?= isset($_GET['empresa_id']) && $_GET['empresa_id'] == $empresa['id'] ? 'selected' : '' ?>>
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
                            placeholder="Título o descripción">
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
                                        '<?= addslashes($formato['acabados'] ?? '') ?>', 
                                        '<?= addslashes($formato['descripcion'] ?? '') ?>'
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
                <h3 class="py-4">Nuevo Pedido</h3>

                <input type="hidden" name="empresa_id" value="<?= htmlspecialchars($_GET['empresa_id'] ?? '') ?>">

                <div id="carrito-pedido">
                    <p class="text-center py-4">No hay items en el pedido</p>
                </div>

                <div class="mb-4">
                    <label for="observaciones_generales" class="block text-sm mb-1">Observaciones generales</label>
                    <textarea id="observaciones_generales" name="observaciones_generales" rows="3"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-advertencia bg-black/30"></textarea>
                </div>

                <button type="submit" <?= empty($carrito) ? 'disabled' : '' ?>
                    class="w-full bg-destacado text-white py-2 px-4 rounded-md hover:bg-complemento transition duration-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-complemento">
                    Registrar Pedido
                </button>
            </form>
        </div>
    </div>
</body>

</html>
