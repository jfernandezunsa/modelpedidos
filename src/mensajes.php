<?php
if (isset($_SESSION['mensaje_exito'])):
  $mensaje = $_SESSION['mensaje_exito'];
  unset($_SESSION['mensaje_exito']);
?>
  <div class="fixed top-4 right-4 z-50 w-80 bg-green-100 border-l-4 border-green-500 text-green-700 p-4 shadow-lg" role="alert">
    <div class="flex items-center">
      <span class="material-icons text-green-500 mr-2">check_circle</span>
      <strong class="font-bold"><?= $mensaje['titulo'] ?></strong>
    </div>
    <p class="mt-1"><?= $mensaje['mensaje'] ?></p>
  </div>
<?php endif; ?>

<?php if (isset($_SESSION['mensaje_error'])):
  $mensaje = $_SESSION['mensaje_error'];
  unset($_SESSION['mensaje_error']);
?>
  <div class="fixed top-4 right-4 z-50 w-80 bg-red-100 border-l-4 border-red-500 text-red-700 p-4 shadow-lg" role="alert">
    <div class="flex items-center">
      <span class="material-icons text-red-500 mr-2">error</span>
      <strong class="font-bold"><?= $mensaje['titulo'] ?></strong>
    </div>
    <p class="mt-1"><?= $mensaje['mensaje'] ?></p>
  </div>
<?php endif; ?>
