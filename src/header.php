<div class="bg-gray-100">
  <div class="mx-auto p-4 flex justify-between">
    <div><img src="/modelpedidos/public/images/logo-atlanta.png" alt="" class="h-5"></div>
    <div>
      <button id="menuButton" class="focus:outline-none">
        <span class="material-icons text-secundario text-4xl">menu</span>
      </button>
    </div>
  </div>
  <div id="dropdownMenu" class="absolute right-0 top-15 hidden">
    <ul>
      <li class="px-4 py-2 bg-white/60 hover:bg-gray-200 border-b border-destacado w-52"><a href="registro_pedidos.php" class="text-secundario block">Registro de Pedidos</a></li>
      <li class="px-4 py-2 bg-white/60 hover:bg-gray-200 border-b border-destacado w-52"><a href="registro_empresa.php" class="text-secundario block">Registro de Empresas</a></li>
      <li class="px-4 py-2 bg-white/60 hover:bg-gray-200 border-b border-destacado w-52"><a href="registro_formato.php" class="text-secundario block">Registro de Formatos</a></li>
      <li class="px-4 py-2 bg-white/60 hover:bg-gray-200 border-b border-destacado w-52"><a href="reporte_empresas.php" class="text-secundario block">Reporte de Empresas</a></li>
      <li class="px-4 py-2 bg-white/60 hover:bg-gray-200 border-b border-destacado w-52"><a href="reporte_formatos.php" class="text-secundario block">Reporte de Formatos</a></li>
      <!--       <li class="px-4 py-2 bg-white/60 hover:bg-gray-200 border-b border-destacado w-52"><a href="reporte_pedidos.php" class="text-secundario block">Reporte de Pedidos</a></li> -->
      <li class="px-4 py-2 bg-white/60 hover:bg-gray-200 border-b border-destacado w-52"><a href="logout.php" class="text-secundario block">Salir</a></li>
    </ul>
  </div>
</div>

<script>
  document.getElementById('menuButton').addEventListener('click', function() {
    const menu = document.getElementById('dropdownMenu');
    menu.classList.toggle('hidden');
  });
</script>
