<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
//require 'conexion.php';
if (!isset($_SESSION['usuario'])) {
    header('Location: login.php');
    exit;
}
?>


<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi Proyecto con Tailwind</title>
    <!-- Google Fonts - Roboto -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    <!-- Tailwind CSS -->
    <link href="../public/css/output.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
</head>

<body class="font-roboto bg-black/65 text-white">
    <!--     <div class="bg-gray-100">
        <div class="mx-auto p-4 flex justify-between">
            <div><img src="/modelpedidos/public/images/logo-atlanta.png" alt="" class="h-5"></div>
            <div><span class="material-icons text-secundario text-8xl">menu</span></div>
        </div>
    </div> -->
    <?php include 'header.php'; ?>
    <div class="container mx-auto p-4 max-w-2xl">
        <div class="flex items-center mb-6 mt-2">
            <span class="material-icons text-destacado text-8xl material-symbols-outlined">arrow_circle_right</span>
            <span class="pl-2">Registro de formatos</span>
        </div>
        <form action="procesar_formato.php" method="POST" enctype="multipart/form-data"
            class="max-w-2xl mx-auto p-6 bg-black/25 rounded-lg shadow-md">
            <!-- Titulo -->
            <div class="form-group text-white/70 mb-4">
                <label for="titulo" class="block text-sm mb-2">
                    Título del formato <span class="text-advertencia">*</span>
                </label>
                <input type="text" id="titulo" name="titulo" required
                    class="w-full p-2 border border-gray-400 rounded focus:outline-none focus:ring-1 focus:ring-advertencia bg-black/30"
                    placeholder="Ingrese el título del formato">
            </div>

            <!-- Descripción -->
            <div class="mb-4">
                <label for="descripcion" class="block text-sm mb-1">Descripción</label>
                <textarea id="descripcion" name="descripcion" rows="4" required
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-advertencia bg-black/30"></textarea>
            </div>

            <!-- Dimensiones (Select) -->
            <div class="mb-4">
                <label for="dimensiones" class="block text-sm mb-1 ">Dimensiones</label>
                <select id="dimensiones" name="dimensiones" required
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-advertencia bg-black/30">
                    <option class="bg-gray-600" value="" disabled selected>Seleccione...</option>
                    <option class="bg-gray-600" value="A6">A6 (10.5cm x 14.7cm)</option>
                    <option class="bg-gray-600" value="A5">A5 (14.7cm x 21cm)</option>
                    <option class="bg-gray-600" value="A4">A4 (21cm x 29.7cm)</option>
                    <option class="bg-gray-600" value="Carta">Carta (21.6cm x 27.9cm)</option>
                    <option class="bg-gray-600" value="Oficio">Oficio (21.6cm x 35.6cm)</option>
                </select>
            </div>

            <!-- Copias por Juego (Select) -->
            <div class="mb-4">
                <label for="cpxjuego" class="block text-sm mb-1 ">Copias por Juego</label>
                <select id="cpxjuego" name="cpxjuego" required
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-advertencia bg-black/30">
                    <option class="bg-gray-600" value="" disabled selected>Seleccione...</option>
                    <option class="bg-gray-600" value="1">1</option>
                    <option class="bg-gray-600" value="2">2</option>
                    <option class="bg-gray-600" value="3">3</option>
                    <option class="bg-gray-600" value="4">4</option>
                    <option class="bg-gray-600" value="5">5</option>
                </select>
            </div>

            <!-- Acabados (Checkboxes) -->
            <fieldset class="mb-4">
                <legend class="text-sm mb-1">Acabados</legend>
                <div class="space-y-2 flex flex-col ml-4">
                    <label class="inline-flex items-center">
                        <input type="checkbox" name="acabados[]" value="numeracion"
                            class="h-4 w-4 text-azul-oscuro focus:ring-destacado border-gray-300 rounded">
                        <span class="ml-2">Numeración</span>
                    </label>
                    <label class="inline-flex items-center">
                        <input type="checkbox" name="acabados[]" value="anillado"
                            class="h-4 w-4 text-azul-oscuro focus:ring-destacado border-gray-300 rounded">
                        <span class="ml-2">Anillado</span>
                    </label>
                    <label class="inline-flex items-center">
                        <input type="checkbox" name="acabados[]" value="enmicado"
                            class="h-4 w-4 text-azul-oscuro focus:ring-destacado border-gray-300 rounded">
                        <span class="ml-2">Enmicado</span>
                    </label>
                    <label class="inline-flex items-center">
                        <input type="checkbox" name="acabados[]" value="plastificado"
                            class="h-4 w-4 text-azul-oscuro focus:ring-destacado border-gray-300 rounded">
                        <span class="ml-2">Plastificado</span>
                    </label>
                    <!-- Repetir para otros checkboxes -->
                </div>
            </fieldset>

            <!-- Archivo Edicion -->
            <div class="mb-6">
                <label for="archivo_edicion" class="block text-sm mb-1">Subir archivo de Edición *</label>
                <input type="file" id="archivo_edicion" name="archivo_edicion" accept=".pdf,.doc,.docx,.jpg,.png,.cdr,.ai" required
                    class="block w-full text-sm text-destacado file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-azul-claro file:text-complemento hover:file:bg-azul-oscuro">
            </div>
            <!-- Archivo visualizacion  -->
            <div class="mb-6">
                <label for="archivo_pdf" class="block text-sm mb-1">Subir archivo de Visualizacion PDF*</label>
                <input type="file" id="archivo_pdf" name="archivo_pdf" accept=".pdf" required
                    class="block w-full text-sm text-destacado file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-azul-claro file:text-complemento hover:file:bg-azul-oscuro">
            </div>
            <!-- Versión -->
            <div class="form-group text-white/70 mb-4">
                <label for="versión" class="block text-sm mb-2">
                    Version del formato <span class="text-advertencia">*</span>
                </label>
                <input type="text" id="versión" name="versión" required
                    class="w-full p-2 border border-gray-400 rounded focus:outline-none focus:ring-1 focus:ring-advertencia bg-black/30"
                    placeholder="00.000.00">
            </div>
            <!-- Empresa -->
            <?php
            require 'conexion.php';
            $query = "SELECT id, nom_empresa FROM empresas WHERE activa = 1";
            $result = $conn->query($query);
            ?>
            <div class="mb-4">
                <label for="form_empresa" class="block text-sm mb-1 ">Seleccionar empresa</label>
                <select id="form_empresa" name="form_empresa" required
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-advertencia bg-black/30">
                    <?php while ($empresa = $result->fetch_assoc()): ?>
                        <option class="bg-gray-600" value="<?= $empresa['id'] ?>"><?= htmlspecialchars($empresa['nom_empresa']) ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            <!-- Botón -->
            <button type="submit"
                class="w-full bg-destacado text-white py-2 px-4 rounded-md hover:bg-complemento transition duration-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-azul-claro">
                Enviar formato
            </button>
        </form>

    </div>
</body>

</html>
