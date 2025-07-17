<?php
require 'conexion.php';

$busqueda = $_GET['q'] ?? '';
$sql = "SELECT * FROM empresas WHERE nom_empresa LIKE ? OR RUC LIKE ?";
$stmt = $conn->prepare($sql);
$param = "%$busqueda%";
$stmt->bind_param("ss", $param, $param);
$stmt->execute();
$resultado = $stmt->get_result();

if ($resultado->num_rows > 0) {
    while($empresa = $resultado->fetch_assoc()) {
        echo '
        <div class="flex justify-between items-center mb-4 border-b border-gray-300 pb-5">
            <div>
                <h5>'.htmlspecialchars($empresa['nom_empresa']).'</h5>
                <p class="text-xs">RUC: '.htmlspecialchars($empresa['RUC'] ?? 'No registrado').'</p>
                <p class="text-xs">Dirección: '.htmlspecialchars($empresa['lugares_entrega'] ?? 'No registrada').'</p>
                <p class="text-xs">Contacto: '.htmlspecialchars($empresa['nom_comprador']).'</p>
            </div>
            <div>
                <a href="editar_empresa.php?id='.$empresa['id'].'"
                   class="bg-complemento w-10 h-10 rounded-lg flex items-center justify-center cursor-pointer hover:bg-destacado transition duration-300">
                    <span class="material-icons text-white">edit</span>
                </a>
            </div>
        </div>';
    }
} else {
    echo '<p class="text-center py-4">No se encontraron resultados</p>';
}
?>