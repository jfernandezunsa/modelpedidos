<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Pedidos</title>
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
            <span class="pl-2">Registro de Pedidos</span>
        </div>

        <div class="max-w-2xl mx-auto p-6 bg-black/25 rounded-lg shadow-md">
            <div class="bg-black/25 rounded-lg p-4 ">
                <!-- Dimensiones (Select) -->
                <div class="mb-4">
                    <label for="form_empresa" class="block text-sm mb-1 ">Seleccione empresa:</label>
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
                            <div class="flex">
                                <a href=""
                                    class="bg-complemento w-10 h-10 rounded-lg flex items-center justify-center cursor-pointer hover:bg-destacado transition duration-300" alt="ver formato">
                                    <span class="material-icons text-white">visibility</span>
                                </a>
                                <a href=""
                                    class="bg-complemento ml-2 w-10 h-10 rounded-lg flex items-center justify-center cursor-pointer hover:bg-destacado transition duration-300" alt="añadir al pedido">
                                    <span class="material-icons text-white">add</span>
                                </a>
                            </div>
                        </div>
                    </div>

                    <h3 class="py-4">Pedido correlativo - dd-mm-aaaa</h3>
                    <div class="mb-2">
                        <div class="flex items-center justify-between mb-2">
                            <div>
                                <p>ITEM 01</p>
                                <h4 class="mb-2">Titulo Formato</h4>
                            </div>
                            <div> <a href=""
                                    class="bg-complemento ml-2 w-10 h-10 rounded-lg flex items-center justify-center cursor-pointer hover:bg-destacado transition duration-300" alt="eliminar del pedido">
                                    <span class="material-icons text-white">delete</span>
                                </a></div>
                        </div>
                        <!-- Cantidad pedido formato -->
                        <div class="form-group text-white/70 mb-4">
                            <label for="cant_pedido_formato" class="block text-sm mb-2">Cantidad</label>
                            <input type="text" id="cant_pedido_formato" name="cant_pedido_formato"
                                class="w-full p-2 border border-gray-400 rounded focus:outline-none focus:ring-1 focus:ring-advertencia bg-black/30" placeholder="Cantidad solicitada">
                        </div>
                        <!-- observaciones adicionales pedido -->
                        <div class="mb-4">
                            <label for="observ_pedido_formato" class="block text-sm mb-1">Observación pedido</label>
                            <textarea id="observ_pedido_formato" name="observ_pedido_formato" rows="4" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-advertencia bg-black/30"></textarea>
                        </div>
                    </div>
                    <div class=" mb-2">
                        <div class="flex items-center justify-between mb-2">
                            <div>
                                <p>ITEM 02</p>
                                <h4 class="mb-2">Titulo Formato</h4>
                            </div>
                            <div> <a href=""
                                    class="bg-complemento ml-2 w-10 h-10 rounded-lg flex items-center justify-center cursor-pointer hover:bg-destacado transition duration-300" alt="eliminar del pedido">
                                    <span class="material-icons text-white">delete</span>
                                </a></div>
                        </div>
                        <!-- Cantidad pedido formato -->
                        <div class="form-group text-white/70 mb-4">
                            <label for="cant_pedido_formato" class="block text-sm mb-2">Cantidad</label>
                            <input type="text" id="cant_pedido_formato" name="cant_pedido_formato"
                                class="w-full p-2 border border-gray-400 rounded focus:outline-none focus:ring-1 focus:ring-advertencia bg-black/30" placeholder="Cantidad solicitada">
                        </div>
                        <!-- observaciones adicionales pedido -->
                        <div class="mb-4">
                            <label for="observ_pedido_formato" class="block text-sm mb-1">Observación pedido</label>
                            <textarea id="observ_pedido_formato" name="observ_pedido_formato" rows="4" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-advertencia bg-black/30"></textarea>
                        </div>
                    </div>
                    <button type="submit"
                        class="w-full bg-destacado text-white py-2 px-4 rounded-md hover:bg-complemento transition duration-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-complemento">
                        Registrar Pedido
                    </button>
                </div>
            </div>
        </div>
    </div>

</body>

</html>
