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
            <!-- Dimensiones (Select) -->
            <div class="mb-4">
                <label for="form_empresa" class="block text-sm mb-1 ">Los formatos pertenecen a la empresa:</label>
                <select id="form_empresa" name="form_empresa" required
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-advertencia bg-black/30">
                    <option class="bg-gray-600" value="" disabled selected>Seleccione la empresa</option>
                    <option class="bg-gray-600" value="">aaaa</option>
                    <option class="bg-gray-600" value="">aaaa</option>
                    <option class="bg-gray-600" value="">aaaa</option>
                    <option class="bg-gray-600" value="">aaaa</option>
                    <option class="bg-gray-600" value="">aaaa</option>
                </select>
            </div>

            <!-- Buscador -->
            <div class="form-group text-white/70 mb-4">
                <label for="nom_empresa" class="block text-sm mb-2">Buscar formato</label>
                <div class="flex">
                    <input type="text" id="busqueda" name="busqueda"
                        class="w-full p-2 border border-gray-400 rounded focus:outline-none focus:ring-1 focus:ring-advertencia bg-black/30"
                        placeholder="Nombre de la empresa">
                    <button type="button"
                        class="w-12 bg-complemento text-white px-2 rounded-md hover:bg-destacado transition duration-300 focus:outline-none focus:ring-1 focus:ring-offset-1 focus:ring-destacado">
                        <span class="material-icons">search</span>
                    </button>
                </div>
            </div>



            <!-- Resultados de busqueda -->

            <h4 class="mb-4">Resultados de la Búsqueda</h4>
            <div id="resp_busq_empresas">
                <div class="flex justify-between items-center mb-4 border-b border-gray-300 pb-5">
                    <div>
                        <h5>Titulo del formato</h5>
                        <p class="text-xs">Dimensiones: </p>
                        <p class="text-xs">Numero de copias: </p>
                        <p class="text-xs">Acabados: </p>
                        <p class="text-xs">Descripción: </p>
                    </div>
                    <div>
                        <a href=""
                            class="bg-complemento w-10 h-10 rounded-lg flex items-center justify-center cursor-pointer hover:bg-destacado transition duration-300">
                            <span class="material-icons text-white">edit</span>
                        </a>
                    </div>
                </div>

                <p class="text-center py-4">No hay empresas registradas</p>
            </div>
        </div>
    </div>

</body>

</html>
