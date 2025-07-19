<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header('Location: src/login.php');
    exit;
}
?>

<?php
require 'conexion.php';

// 1. Obtener ID de la empresa a editar
$id_empresa = $_GET['id'] ?? null;
if (!$id_empresa || !is_numeric($id_empresa)) {
    die("ID de empresa no válido");
}

// 2. Obtener datos actuales de la empresa
$stmt = $conn->prepare("SELECT * FROM empresas WHERE id = ?");
$stmt->bind_param("i", $id_empresa);
$stmt->execute();
$empresa = $stmt->get_result()->fetch_assoc();

if (!$empresa) {
    die("Empresa no encontrada");
}

// 3. Procesar actualización (POST)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom_empresa = $conn->real_escape_string($_POST['nom_empresa']);
    $RUC = $conn->real_escape_string($_POST['RUC'] ?? null);
    $lugares_entrega = $conn->real_escape_string($_POST['lugares_entrega'] ?? null);
    $nom_comprador = $conn->real_escape_string($_POST['nom_comprador']);
    $celular_comprador = $conn->real_escape_string($_POST['celular_comprador'] ?? null);
    $correo_comprador = $conn->real_escape_string($_POST['correo_comprador'] ?? null);

    $stmt = $conn->prepare("UPDATE empresas SET 
                          nom_empresa = ?,
                          RUC = ?,
                          lugares_entrega = ?,
                          nom_comprador = ?,
                          celular_comprador = ?,
                          correo_comprador = ?
                          WHERE id = ?");

    $stmt->bind_param(
        "ssssssi",
        $nom_empresa,
        $RUC,
        $lugares_entrega,
        $nom_comprador,
        $celular_comprador,
        $correo_comprador,
        $id_empresa
    );

    if ($stmt->execute()) {
        header('Location: reporte_empresas.php?success=1');
        exit;
    } else {
        $error = "Error al actualizar: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Empresa</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    <link href="../public/css/output.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
</head>

<body class="font-roboto bg-black/65 text-white">
    <!--     <div class="bg-gray-100">
        <div class="mx-auto p-4 flex justify-between">
            <div><img src="/modelpedidos/public/images/logo-atlanta.png" alt="" class="h-5"></div>
            <div><span class="material-icons text-secundario text-4xl">menu</span></div>
        </div>
    </div> -->
    <?php include 'header.php'; ?>
    <div class="container mx-auto p-4 max-w-2xl">
        <div class="flex items-center mb-6 mt-2">
            <span class="material-icons text-destacado text-4xl">arrow_circle_right</span>
            <span class="pl-2">Editar Empresa: <?= htmlspecialchars($empresa['nom_empresa']) ?></span>
        </div>

        <?php if (isset($error)): ?>
            <div class="bg-red-500 text-white p-3 mb-4 rounded"><?= $error ?></div>
        <?php endif; ?>

        <form method="POST" class="max-w-2xl mx-auto p-6 bg-black/25 rounded-lg shadow-md">
            <h4 class="mb-4">Datos de la empresa</h4>

            <!-- Nombre empresa -->
            <div class="form-group text-white/70 mb-4">
                <label for="nom_empresa" class="block text-sm mb-2">
                    Nombre / Razón Social <span class="text-advertencia">*</span>
                </label>
                <input type="text" id="nom_empresa" name="nom_empresa" required
                    value="<?= htmlspecialchars($empresa['nom_empresa']) ?>"
                    class="w-full p-2 border border-gray-400 rounded focus:outline-none focus:ring-1 focus:ring-advertencia bg-black/30">
            </div>

            <!-- RUC -->
            <div class="form-group text-white/70 mb-4">
                <label for="RUC" class="block text-sm mb-2">Número de RUC</label>
                <input type="text" id="RUC" name="RUC"
                    value="<?= htmlspecialchars($empresa['RUC'] ?? '') ?>"
                    class="w-full p-2 border border-gray-400 rounded focus:outline-none focus:ring-1 focus:ring-advertencia bg-black/30">
            </div>

            <!-- Lugar de entrega -->
            <div class="form-group text-white/70 mb-4">
                <label for="lugares_entrega" class="block text-sm mb-2">Lugares de entrega</label>
                <input type="text" id="lugares_entrega" name="lugares_entrega"
                    value="<?= htmlspecialchars($empresa['lugares_entrega'] ?? '') ?>"
                    class="w-full p-2 border border-gray-400 rounded focus:outline-none focus:ring-1 focus:ring-advertencia bg-black/30">
            </div>

            <h4 class="mb-4 mt-6">Datos del comprador</h4>

            <!-- Nombre comprador -->
            <div class="form-group text-white/70 mb-4">
                <label for="nom_comprador" class="block text-sm mb-2">
                    Nombre Apellidos <span class="text-advertencia">*</span>
                </label>
                <input type="text" id="nom_comprador" name="nom_comprador" required
                    value="<?= htmlspecialchars($empresa['nom_comprador']) ?>"
                    class="w-full p-2 border border-gray-400 rounded focus:outline-none focus:ring-1 focus:ring-advertencia bg-black/30">
            </div>

            <!-- Celular -->
            <div class="form-group text-white/70 mb-4">
                <label for="celular_comprador" class="block text-sm mb-2">Celular</label>
                <input type="tel" id="celular_comprador" name="celular_comprador"
                    value="<?= htmlspecialchars($empresa['celular_comprador'] ?? '') ?>"
                    class="w-full p-2 border border-gray-400 rounded focus:outline-none focus:ring-1 focus:ring-advertencia bg-black/30">
            </div>

            <!-- Correo -->
            <div class="form-group text-white/70 mb-4">
                <label for="correo_comprador" class="block text-sm mb-2">Correo electrónico</label>
                <input type="email" id="correo_comprador" name="correo_comprador"
                    value="<?= htmlspecialchars($empresa['correo_comprador'] ?? '') ?>"
                    class="w-full p-2 border border-gray-400 rounded focus:outline-none focus:ring-1 focus:ring-advertencia bg-black/30">
            </div>

            <div class="flex justify-between mt-6">
                <a href="reporte_empresas.php" class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600 transition">
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
