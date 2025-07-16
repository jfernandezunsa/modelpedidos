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
            <div><span class="material-icons text-secundario text-8xl">menu</span></div>
        </div>
    </div>
    <div class="container mx-auto p-4 max-w-2xl mx-auto">
        <div class="flex items-center mb-6 mt-2">
            <span class="material-icons text-destacado text-8xl material-symbols-outlined">arrow_circle_right</span>
            <span class="pl-2">Reporte de Empresas</span>
        </div>
        <form action="procesar.php" method="POST" enctype="multipart/form-data"
            class="max-w-2xl mx-auto p-6 bg-black/25 rounded-lg shadow-md">
            <h4 class="mb-4">Datos de la empresa</h4>
            <!-- Nombre empresa -->
            <div class="form-group text-white/70 mb-4">
                <label for="nom_empresa" class="block text-sm mb-2">
                    Buscar empresa
                </label>
                <div class="flex">
                    <input type="text" id="titulo" name="titulo" required
                        class="w-full p-2 border border-gray-400 rounded focus:outline-none focus:ring-1 focus:ring-advertencia bg-black/30"
                        placeholder="nombre de la empresa">
                    <button type="button"
                        class="w-12 bg-complemento text-white px-2 rounded-md hover:bg-destacado transition duration-300 focus:outline-none focus:ring-1 focus:ring-offset-1 focus:ring-destacado">
                        <span class="material-icons text-destacado text-8xl material-symbols-outlined">search</span>
                    </button>
                </div>
            </div>
            <h4 class="mb-4">Resultados de la Busqueda</h4>
            <div id="resp_busq_empresas">
                <div class="flex justify-between items-center mb-4 border-b border-gray-300 pb-5">
                    <div>
                        <h5>Nombre de la Empresa</h5>
                        <p class="text-xs">RUC. 1111111</p>
                        <p class="text-xs">Direccion Principal: dato</p>
                        <p class="text-xs">Volumen de Pedidos: 1000</p>
                    </div>
                    <div>
                        <div
                            class="bg-complemento w-10 h-10 rounded-lg flex items-center justify-center cursor-pointer hover:bg-destacado transition duration-300">
                            <span class="material-icons text-white text-8xl material-symbols-outlined">edit</span>
                        </div>
                    </div>
                </div>
                <div class="flex justify-between items-center mb-4 border-b border-gray-300 pb-5">
                    <div>
                        <h5>Nombre de la Empresa</h5>
                        <p class="text-xs">RUC. 1111111</p>
                        <p class="text-xs">Direccion Principal: dato</p>
                        <p class="text-xs">Volumen de Pedidos: 1000</p>
                    </div>
                    <div>
                        <div
                            class="bg-complemento w-10 h-10 rounded-lg flex items-center justify-center cursor-pointer hover:bg-destacado transition duration-300">
                            <span class="material-icons text-white text-8xl material-symbols-outlined">edit</span>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</body>

</html>