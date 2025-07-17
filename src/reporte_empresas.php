<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de Empresas</title>
    <!-- Google Fonts - Roboto -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    <!-- Tailwind CSS -->
    <link href="../public/css/output.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
</head>

<body class="font-roboto bg-black/65 text-white">
    <?php
    require 'conexion.php'; // Asegúrate de que la ruta sea correcta
    
    // Consulta a la base de datos
    $sql = "SELECT * FROM empresas";
    $resultado = $conn->query($sql);
    ?>

    <div class="bg-gray-100">
        <div class="mx-auto p-4 flex justify-between">
            <div><img src="/modelpedidos/public/images/logo-atlanta.png" alt="" class="h-5"></div>
            <div><span class="material-icons text-secundario text-4xl">menu</span></div>
        </div>
    </div>
    
    <div class="container p-4 max-w-2xl mx-auto">
        <div class="flex items-center mb-6 mt-2">
            <span class="material-icons text-destacado text-4xl">arrow_circle_right</span>
            <span class="pl-2">Reporte de Empresas</span>
        </div>
        
        <div class="max-w-2xl mx-auto p-6 bg-black/25 rounded-lg shadow-md">
            <h4 class="mb-4">Datos de la empresa</h4>
            
            <!-- Buscador -->
            <div class="form-group text-white/70 mb-4">
                <label for="nom_empresa" class="block text-sm mb-2">Buscar empresa</label>
                <div class="flex">
                    <input type="text" id="busqueda" name="busqueda"
                        class="w-full p-2 border border-gray-400 rounded focus:outline-none focus:ring-1 focus:ring-advertencia bg-black/30"
                        placeholder="Nombre de la empresa">
                    <button type="button" onclick="buscarEmpresas()"
                        class="w-12 bg-complemento text-white px-2 rounded-md hover:bg-destacado transition duration-300 focus:outline-none focus:ring-1 focus:ring-offset-1 focus:ring-destacado">
                        <span class="material-icons">search</span>
                    </button>
                </div>
            </div>
            
            <h4 class="mb-4">Resultados de la Búsqueda</h4>
            <div id="resp_busq_empresas">
                <?php if ($resultado->num_rows > 0): ?>
                    <?php while($empresa = $resultado->fetch_assoc()): ?>
                        <div class="flex justify-between items-center mb-4 border-b border-gray-300 pb-5">
                            <div>
                                <h5><?php echo htmlspecialchars($empresa['nom_empresa']); ?></h5>
                                <p class="text-xs">RUC: <?php echo htmlspecialchars($empresa['RUC'] ?? 'No registrado'); ?></p>
                                <p class="text-xs">Dirección: <?php echo htmlspecialchars($empresa['lugares_entrega'] ?? 'No registrada'); ?></p>
                                <p class="text-xs">Contacto: <?php echo htmlspecialchars($empresa['nom_comprador']); ?></p>
                            </div>
                            <div>
                                <a href="editar_empresa.php?id=<?php echo $empresa['id']; ?>"
                                   class="bg-complemento w-10 h-10 rounded-lg flex items-center justify-center cursor-pointer hover:bg-destacado transition duration-300">
                                    <span class="material-icons text-white">edit</span>
                                </a>
                            </div>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <p class="text-center py-4">No hay empresas registradas</p>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script>
    function buscarEmpresas() {
        const busqueda = document.getElementById('busqueda').value;
        fetch(`buscar_empresas.php?q=${encodeURIComponent(busqueda)}`)
            .then(response => response.text())
            .then(data => {
                document.getElementById('resp_busq_empresas').innerHTML = data;
            });
    }
    </script>
</body>
</html>