<?php
session_start();
require 'conexion.php';

// Verificar sesión
if (!isset($_SESSION['usuario'])) {
  header('Location: login.php');
  exit;
}

// Obtener pedidos con información de empresa
$query = "SELECT p.id, p.fecha_pedido, p.estado, 
                 e.nom_empresa, COUNT(pi.id) as items,
                 u.username as usuario
          FROM pedidos p
          JOIN empresas e ON p.empresa_id = e.id
          JOIN usuarios u ON p.usuario_id = u.id
          LEFT JOIN pedidos_items pi ON p.id = pi.pedido_id
          GROUP BY p.id
          ORDER BY p.fecha_pedido DESC";
$pedidos = $conn->query($query)->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Reporte de Pedidos</title>
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
  <link href="../public/css/output.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
</head>

<body class="font-roboto bg-black/65 text-white">
  <!-- Encabezado similar al de registro_pedidos.php -->

  <div class="container p-4 max-w-6xl mx-auto">
    <div class="flex items-center mb-6 mt-2">
      <span class="material-icons text-destacado text-4xl">arrow_circle_right</span>
      <span class="pl-2">Reporte de Pedidos</span>
    </div>

    <div class="overflow-x-auto">
      <table class="min-w-full bg-black/25 rounded-lg overflow-hidden">
        <thead class="bg-gray-800 text-white">
          <tr>
            <th class="py-2 px-4">ID</th>
            <th class="py-2 px-4">Fecha</th>
            <th class="py-2 px-4">Empresa</th>
            <th class="py-2 px-4">Items</th>
            <th class="py-2 px-4">Estado</th>
            <th class="py-2 px-4">Registrado por</th>
            <th class="py-2 px-4">Acciones</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($pedidos as $pedido): ?>
            <tr class="border-b border-gray-700 hover:bg-black/40">
              <td class="py-2 px-4"><?= $pedido['id'] ?></td>
              <td class="py-2 px-4"><?= date('d/m/Y H:i', strtotime($pedido['fecha_pedido'])) ?></td>
              <td class="py-2 px-4"><?= htmlspecialchars($pedido['nom_empresa']) ?></td>
              <td class="py-2 px-4"><?= $pedido['items'] ?></td>
              <td class="py-2 px-4">
                <span class="px-2 py-1 rounded-full text-xs 
                                    <?= $pedido['estado'] == 'entregado' ? 'bg-green-500' : ($pedido['estado'] == 'terminado' ? 'bg-blue-500' : ($pedido['estado'] == 'en_proceso' ? 'bg-yellow-500' : 'bg-gray-500')) ?>">
                  <?= ucfirst(str_replace('_', ' ', $pedido['estado'])) ?>
                </span>
              </td>
              <td class="py-2 px-4"><?= htmlspecialchars($pedido['usuario']) ?></td>
              <td class="py-2 px-4">
                <a href="detalle_pedido.php?id=<?= $pedido['id'] ?>"
                  class="text-blue-400 hover:underline mr-2">Ver</a>
                <a href="editar_pedido.php?id=<?= $pedido['id'] ?>"
                  class="text-yellow-400 hover:underline">Editar</a>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
</body>

</html>
