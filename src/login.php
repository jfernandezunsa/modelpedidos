<?php
session_start();
require 'conexion.php'; // Asegúrate que la ruta sea correcta

// Procesar formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    // Consulta a la base de datos
    $stmt = $conn->prepare("SELECT * FROM usuarios WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $usuario = $stmt->get_result()->fetch_assoc();

    if ($usuario && password_verify($password, $usuario['password_hash'])) {
        $_SESSION['usuario'] = $usuario;
        header('Location: registro_pedidos.php');
        exit;
    } else {
        $error = "Usuario o contraseña incorrectos";
    }
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acceso al sistema</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    <link href="../public/css/output.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
</head>

<body class="font-roboto bg-black/65 text-white">
    <div class="container mx-auto p-4 max-w-2xl">
        <div class="flex items-center mb-6 mt-10">
            <span class="material-icons text-destacado text-4xl">lock</span>
            <span class="pl-2 text-2xl">Acceso al sistema</span>
        </div>

        <?php if (isset($error)): ?>
            <div class="bg-red-500 text-white p-3 mb-4 rounded">
                <?= $error ?>
            </div>
        <?php endif; ?>

        <form method="POST" class="max-w-2xl mx-auto p-6 bg-black/25 rounded-lg shadow-md">
            <div class="form-group text-white/70 mb-4">
                <label for="username" class="block text-sm mb-2">Usuario</label>
                <input type="text" id="username" name="username" required
                    class="w-full p-2 border border-gray-400 rounded focus:outline-none focus:ring-1 focus:ring-advertencia bg-black/30"
                    placeholder="administrador">
            </div>
            <div class="form-group text-white/70 mb-4">
                <label for="password" class="block text-sm mb-2">Contraseña</label>
                <input type="password" id="password" name="password" required
                    class="w-full p-2 border border-gray-400 rounded focus:outline-none focus:ring-1 focus:ring-advertencia bg-black/30"
                    placeholder="admin123">
            </div>
            <button type="submit"
                class="w-full bg-destacado text-white py-2 px-4 rounded-md hover:bg-complemento transition duration-300">
                Ingresar
            </button>
        </form>
    </div>
</body>

</html>
