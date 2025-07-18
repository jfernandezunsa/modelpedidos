<?php include 'mensajes.php'; ?>
<?php
session_start();
require 'conexion.php';

// Obtener todas las empresas para el select
$query_empresas = "SELECT id, nom_empresa FROM empresas WHERE activa = 1";
$result_empresas = $conn->query($query_empresas);

// Obtener formatos (con filtros si existen)
$where = "";
$params = [];
$types = "";

if (isset($_GET['empresa_id'])) {
    $where .= " AND f.empresa_id = ?";
    $params[] = $_GET['empresa_id'];
    $types .= "i";
}

if (isset($_GET['busqueda'])) {
    $where .= " AND (f.titulo LIKE ? OR f.descripcion LIKE ?)";
    $params[] = "%" . $_GET['busqueda'] . "%";
    $params[] = "%" . $_GET['busqueda'] . "%";
    $types .= "ss";
}

$query = "SELECT f.id, f.titulo, f.descripcion, f.dimensiones, f.copias_por_juego, f.version, 
                 e.nom_empresa, GROUP_CONCAT(a.nombre SEPARATOR ', ') as acabados,
                 af_edicion.ruta_archivo as ruta_edicion,
                 af_edicion.nombre_archivo as nombre_edicion,
                 af_pdf.ruta_archivo as ruta_pdf,
                 af_pdf.nombre_archivo as nombre_pdf
          FROM formatos f
          JOIN empresas e ON f.empresa_id = e.id
          LEFT JOIN formatos_acabados fa ON f.id = fa.formato_id
          LEFT JOIN acabados a ON fa.acabado_id = a.id
          LEFT JOIN archivos_formatos af_edicion ON (f.id = af_edicion.formato_id AND af_edicion.tipo = 'edicion')
          LEFT JOIN archivos_formatos af_pdf ON (f.id = af_pdf.formato_id AND af_pdf.tipo = 'visualizacion')
          WHERE 1=1 $where
          GROUP BY f.id
          ORDER BY f.fecha_creacion DESC";

$stmt = $conn->prepare($query);
if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$result = $stmt->get_result();
$formatos = $result->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de Formatos</title>
    <!-- Google Fonts - Roboto -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    <!-- Tailwind CSS -->
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

    <div class="container p-4 max-w-2xl mx-auto">
        <div class="flex items-center mb-6 mt-2">
            <span class="material-icons text-destacado text-4xl">arrow_circle_right</span>
            <span class="pl-2">Reporte de Formatos</span>
        </div>

        <div class="max-w-2xl mx-auto p-6 bg-black/25 rounded-lg shadow-md">
            <!-- Filtro por empresa -->
            <form method="GET" action="reporte_formatos.php" class="mb-4">
                <label for="form_empresa" class="block text-sm mb-1">Los formatos pertenecen a la empresa:</label>
                <select id="form_empresa" name="empresa_id" onchange="this.form.submit()"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-advertencia bg-black/30">
                    <option value="">Todas las empresas</option>
                    <?php
                    $result_empresas->data_seek(0);
                    while ($empresa = $result_empresas->fetch_assoc()): ?>
                        <option value="<?= $empresa['id'] ?>"
                            <?= (isset($_GET['empresa_id']) && $_GET['empresa_id'] == $empresa['id']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($empresa['nom_empresa']) ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </form>

            <!-- Buscador -->
            <form method="GET" action="reporte_formatos.php" class="mb-6">
                <input type="hidden" name="empresa_id" value="<?= $_GET['empresa_id'] ?? '' ?>">
                <label for="nom_empresa" class="block text-sm mb-2">Buscar formato</label>
                <div class="flex">
                    <input type="text" id="busqueda" name="busqueda" value="<?= $_GET['busqueda'] ?? '' ?>"
                        class="w-full p-2 border border-gray-400 rounded focus:outline-none focus:ring-1 focus:ring-advertencia bg-black/30"
                        placeholder="Título o descripción">
                    <button type="submit"
                        class="w-12 bg-complemento text-white px-2 rounded-md hover:bg-destacado transition duration-300 focus:outline-none focus:ring-1 focus:ring-offset-1 focus:ring-destacado">
                        <span class="material-icons">search</span>
                    </button>
                </div>
            </form>

            <!-- Resultados de búsqueda -->
            <h4 class="mb-4">Resultados de la Búsqueda</h4>
            <div id="resp_busq_empresas">
                <?php if (count($formatos) > 0): ?>
                    <?php foreach ($formatos as $formato): ?>
                        <div class="flex justify-between items-center mb-4 border-b border-gray-300 pb-5">
                            <div>
                                <h5 class="font-medium"><?= htmlspecialchars($formato['titulo']) ?></h5>
                                <p class="text-xs">Empresa: <?= htmlspecialchars($formato['nom_empresa']) ?></p>
                                <p class="text-xs">Dimensiones: <?= htmlspecialchars($formato['dimensiones']) ?></p>
                                <p class="text-xs">Número de copias: <?= $formato['copias_por_juego'] ?></p>
                                <p class="text-xs">Acabados: <?= $formato['acabados'] ?? 'Ninguno' ?></p>
                                <p class="text-xs">Versión: <?= htmlspecialchars($formato['version']) ?></p>

                                <!-- Archivos asociados -->
                                <?php if (!empty($formato['ruta_edicion'])): ?>
                                    <p class="text-xs mt-2">
                                        Archivo de edición:
                                        <a href="<?= htmlspecialchars($formato['ruta_edicion']) ?>"
                                            class="text-blue-400 hover:underline"
                                            target="_blank"
                                            title="<?= htmlspecialchars($formato['nombre_edicion']) ?>">
                                            <?= htmlspecialchars($formato['nombre_edicion']) ?>
                                        </a>
                                    </p>
                                <?php endif; ?>

                                <?php if (!empty($formato['ruta_pdf'])): ?>
                                    <p class="text-xs">
                                        Archivo PDF:
                                        <a href="<?= htmlspecialchars($formato['ruta_pdf']) ?>"
                                            class="text-blue-400 hover:underline"
                                            target="_blank"
                                            title="<?= htmlspecialchars($formato['nombre_pdf']) ?>">
                                            <?= htmlspecialchars($formato['nombre_pdf']) ?>
                                        </a>
                                    </p>
                                <?php endif; ?>

                                <?php if (!empty($formato['descripcion'])): ?>
                                    <p class="text-xs mt-2">Descripción: <?= htmlspecialchars($formato['descripcion']) ?></p>
                                <?php endif; ?>
                            </div>
                            <div>
                                <a href="editar_formato.php?id=<?= $formato['id'] ?>"
                                    class="bg-complemento w-10 h-10 rounded-lg flex items-center justify-center cursor-pointer hover:bg-destacado transition duration-300">
                                    <span class="material-icons text-white">edit</span>
                                </a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p class="text-center py-4">No se encontraron formatos</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>

</html>
