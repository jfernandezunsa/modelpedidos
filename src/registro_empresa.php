<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de empresa</title>
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
            <div><span class="material-icons text-secundario text-3xl">menu</span></div>
        </div>
    </div>
    <div class="container mx-auto p-4 max-w-2xl">
        <div class="flex items-center mb-6 mt-2">
            <span class="material-icons text-destacado text-8xl material-symbols-outlined">arrow_circle_right</span>
            <span class="pl-2">Registro de Empresa</span>
        </div>
        <form action="procesar.php" method="POST" enctype="multipart/form-data"
            class="max-w-2xl mx-auto p-6 bg-black/25 rounded-lg shadow-md">
            <h4 class="mb-4">Datos de la empresa</h4>
            <!-- Nombre empresa -->
            <div class="form-group text-white/70 mb-4">
                <label for="nom_empresa" class="block text-sm mb-2">
                    Nombre / Razón Social <span class="text-advertencia">*</span>
                </label>
                <input type="text" id="nom_empresa" name="nom_empresa" required
                    class="w-full p-2 border border-gray-400 rounded focus:outline-none focus:ring-1 focus:ring-advertencia bg-black/30"
                    placeholder="Ingrese el nombre de la empresa">
            </div>
            <!-- RUC -->
            <div class="form-group text-white/70 mb-4">
                <label for="RUC" class="block text-sm mb-2">
                    Número de RUC 
                </label>
                <input type="text" id="RUC" name="RUC" required
                    class="w-full p-2 border border-gray-400 rounded focus:outline-none focus:ring-1 focus:ring-advertencia bg-black/30"
                    placeholder="...">
            </div>
            <!-- Lugar de entrega -->
            <div class="form-group text-white/70 mb-4">
                <label for="lugares_entrega" class="block text-sm mb-2">
                    Lugares de entrega 
                </label>
                <input type="text" id="lugares_entrega" name="lugares_entrega" required
                    class="w-full p-2 border border-gray-400 rounded focus:outline-none focus:ring-1 focus:ring-advertencia bg-black/30"
                    placeholder="...">
            </div>
<!--             <div class="flex justify-end mb-4">
                <button type="button"
                class="w-12 bg-complemento text-white py-2 px-4 rounded-md hover:bg-destacado transition duration-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-destacado">
                +
            </button>
            </div> -->

            <h4 class="mb-4">Datos de los compradores</h4>

            <!-- Nombre comprador -->
            <div class="form-group text-white/70 mb-4">
                <label for="nom_comprador" class="block text-sm mb-2">
                    Nombre Apellidos <span class="text-advertencia">*</span>
                </label>
                <input type="text" id="nom_comprador" name="nom_comprador" required
                    class="w-full p-2 border border-gray-400 rounded focus:outline-none focus:ring-1 focus:ring-advertencia bg-black/30"
                    placeholder="Nombres y apellidos">
            </div>
            <!-- Celular Comprador -->
            <div class="form-group text-white/70 mb-4">
                <label for="celular_comprador" class="block text-sm mb-2">
                    Celular
                </label>
                <input type="text" id="celular_comprador" name="celular_comprador" required
                    class="w-full p-2 border border-gray-400 rounded focus:outline-none focus:ring-1 focus:ring-advertencia bg-black/30"
                    placeholder="...">
            </div>
            <!-- Correo comprador -->
            <div class="form-group text-white/70 mb-4">
                <label for="correo_comprador" class="block text-sm mb-2">
                    Correo comprador
                </label>
                <input type="email" id="correo_comprador" name="correo_comprador" required
                    class="w-full p-2 border border-gray-400 rounded focus:outline-none focus:ring-1 focus:ring-advertencia bg-black/30"
                    placeholder="...">
            </div>
<!--             <div class="flex justify-end mb-4">
                <button type="button"
                class="w-12 bg-complemento text-white py-2 px-4 rounded-md hover:bg-destacado transition duration-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-destacado">
                +
            </button>
            </div> -->
            <!-- Botón -->
            <button type="submit"
                class="w-full bg-destacado text-white py-2 px-4 rounded-md hover:bg-complemento transition duration-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-complemento">
                Registrar Empresa
            </button>
        </form>

    </div>
</body>

</html>