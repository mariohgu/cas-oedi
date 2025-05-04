<?php
/*
 * Migración automática desde PowerApps a PHP + JS
 * Generado: 2025-05-04
 */

// Obtener la fecha y hora actual en formato español
setlocale(LC_TIME, 'es_PE.UTF-8', 'es_PE', 'esp');
$fechaActual = date("d-m-Y H:i");

// Incluir el archivo de datos de convocatorias
require_once('cas/datos_convocatorias.php');

// Obtener categorías únicas para el filtro
$categorias = [];
foreach ($convocatorias as $convocatoria) {
    if (isset($convocatoria['categoria']) && !in_array($convocatoria['categoria'], $categorias)) {
        $categorias[] = $convocatoria['categoria'];
    }
}
sort($categorias); // Ordenar alfabéticamente

// Buscar convocatorias si hay término de búsqueda o filtro de categoría
$searchTerm = isset($_GET['search']) ? trim($_GET['search']) : '';
$categoriaFiltro = isset($_GET['categoria']) ? trim($_GET['categoria']) : '';
$convocatoriasFiltradas = $convocatorias;

if (!empty($searchTerm) || !empty($categoriaFiltro)) {
    $convocatoriasFiltradas = array_filter($convocatorias, function($convocatoria) use ($searchTerm, $categoriaFiltro) {
        // Filtrar por término de búsqueda
        $coincideTermino = empty($searchTerm) || 
            stripos($convocatoria['numero'], $searchTerm) !== false || 
            stripos($convocatoria['puesto'], $searchTerm) !== false ||
            stripos($convocatoria['fecha_publicacion'], $searchTerm) !== false;
        
        // Filtrar por categoría
        $coincideCategoria = empty($categoriaFiltro) || 
            (isset($convocatoria['categoria']) && $convocatoria['categoria'] === $categoriaFiltro);
        
        // Debe cumplir ambas condiciones
        return $coincideTermino && $coincideCategoria;
    });
}

// Parámetros de paginación
$itemsPorPagina = 10;
$paginaActual = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
if ($paginaActual < 1) $paginaActual = 1;

$totalConvocatorias = count($convocatoriasFiltradas);
$totalPaginas = ceil($totalConvocatorias / $itemsPorPagina);
if ($paginaActual > $totalPaginas && $totalPaginas > 0) $paginaActual = $totalPaginas;

// Obtener las convocatorias para la página actual
$inicio = ($paginaActual - 1) * $itemsPorPagina;
$convocatoriasPagina = array_slice($convocatoriasFiltradas, $inicio, $itemsPorPagina);

// Función para determinar si mostrar una convocatoria como abierta inicialmente
function mostrarAbierta($index) {
    return $index === 0; // Solo el primero abierto por defecto
}
?><!DOCTYPE html>
<html lang="es">
<head>
<meta charset="utf-8">
<title>Convocatorias CAS OEDI 2025</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<script src="https://cdn.tailwindcss.com"></script>
<script>
  tailwind.config = {
    theme: {
      extend: {
        colors: {
          primary: '#0d6efd',
        }
      }
    }
  }
</script>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="styles.css" rel="stylesheet">
</head>
<body class="bg-gray-50">
<div class="max-w-6xl mx-auto px-4 py-8" id="app">
<!-- Barra de Notificaciones -->
<!-- <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-6 rounded-md shadow-sm">
  <div class="flex items-start">
    <div class="flex-shrink-0">
      <svg class="h-5 w-5 text-yellow-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
      </svg>
    </div>
    <div class="ml-3">
      <p class="text-sm text-yellow-700 font-medium">
        Aviso importante: Para cualquier consulta envie un correo a <a href="mailto:rrhh1@oedi.gob.pe" class="text-yellow-700 hover:text-yellow-900">rrhh1@oedi.gob.pe</a>. 
        
      </p>
    </div>
    <div class="ml-auto pl-3">
      <div class="-mx-1.5 -my-1.5">
        <button id="close-notification" type="button" class="inline-flex rounded-md p-1.5 text-yellow-500 hover:bg-yellow-100 focus:outline-none">
          <span class="sr-only">Cerrar</span>
          <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
          </svg>
        </button>
      </div>
    </div>
  </div>
</div> -->

<!-- Logo y Cabecera -->
<div class="flex justify-center mb-6">
  <img src="logo.png" alt="Logo OEDI" class="h-24 object-contain">
</div>

<!-- CABECERA -->
<div class="bg-white shadow-sm rounded-lg p-6 mb-8">
  <h1 class="text-3xl font-bold text-center text-blue-800">Convocatorias CAS 2025 OEDI</h1>
  <!-- Opción 1: Usando PHP (fecha generada en el servidor) -->
  <p class="text-sm text-right text-gray-500 mt-2">Última Actualización: <?php echo $fechaActual; ?></p>
  
  <!-- Opción 2: Usando JavaScript (fecha del cliente - comentada) -->
  <!-- <p class="text-sm text-right text-gray-500 mt-2">Última Actualización: <span id="fechaActualizacion"></span></p> -->
</div>

<!-- SISTEMA DE BÚSQUEDA -->
<div class="bg-white shadow-md rounded-lg p-6 mb-8">
  <h2 class="text-xl font-semibold text-blue-800 mb-4">Buscar Convocatorias</h2>
  <form id="search-form" method="GET" action="" class="flex flex-col md:flex-row gap-4">
    <div class="flex-grow">
      <input type="text" id="search-input" name="search" value="<?php echo htmlspecialchars($searchTerm); ?>" 
             placeholder="Buscar por puesto o número de convocatoria..." 
             class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
    </div>
    <div class="w-full md:w-64">
      <select name="categoria" id="categoria-select" 
              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
        <option value="">Todas las categorías</option>
        <?php foreach ($categorias as $categoria): ?>
          <option value="<?php echo htmlspecialchars($categoria); ?>" <?php echo $categoriaFiltro === $categoria ? 'selected' : ''; ?>>
            <?php echo htmlspecialchars($categoria); ?>
          </option>
        <?php endforeach; ?>
      </select>
    </div>
    <div class="flex gap-2">
      <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors duration-200">
        Buscar
      </button>
      <a href="index.php" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors duration-200 text-center flex items-center justify-center">
        Limpiar
      </a>
    </div>
  </form>
  <?php if (!empty($searchTerm) || !empty($categoriaFiltro)): ?>
    <div class="mt-4 text-sm">
      <p>
        <?php if (!empty($searchTerm)): ?>
          Término: <span class="font-semibold">"<?php echo htmlspecialchars($searchTerm); ?>"</span>
        <?php endif; ?>
        
        <?php if (!empty($categoriaFiltro)): ?>
          <?php echo !empty($searchTerm) ? ' | ' : ''; ?>
          Categoría: <span class="font-semibold"><?php echo htmlspecialchars($categoriaFiltro); ?></span>
        <?php endif; ?>
        
        (<?php echo count($convocatoriasFiltradas); ?> resultados)
      </p>
    </div>
  <?php endif; ?>
</div>

<!-- BASES Y ANEXOS COMUNES -->
<div class="bg-blue-100 rounded-lg p-6 mb-8 shadow-md">
  <h2 class="text-xl font-semibold text-blue-900 mb-4">BASES Y ANEXOS COMUNES PROCESOS CAS</h2>
  <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
    <a href="https://oedigob-my.sharepoint.com/:b:/g/personal/tecnologia_oedi_gob_pe/Ed4h3Cnkl8lMl2QdfNLw6r8B5Lygtu5wMDvNWQefJObUuQ" target="_blank" class="text-blue-600 hover:text-blue-800 hover:underline transition-colors duration-200">BASES ESTÁNDAR</a>
    <a href="https://oedigob-my.sharepoint.com/:w:/g/personal/tecnologia_oedi_gob_pe/EY0divT-JbVCjOH8XeYACXcBKefGmYCXP2Nxg7uoqwrCjg" target="_blank" class="text-blue-600 hover:text-blue-800 hover:underline transition-colors duration-200">ANEXO 1 CAS</a>
    <a href="https://oedigob-my.sharepoint.com/:x:/g/personal/tecnologia_oedi_gob_pe/EQjSMFv0cpRIsYpX5n0ibH0BN_T4X5iF62fNfMFVhVFKFg" target="_blank" class="text-blue-600 hover:text-blue-800 hover:underline transition-colors duration-200">ANEXO 1.1 EVALUACION EXPERIENCIA</a>
    <a href="https://oedigob-my.sharepoint.com/:w:/g/personal/tecnologia_oedi_gob_pe/EbQhKpOgqMZNtbMRdTzrgH0BsbO3InaYXk93FJ96aFMpQQ" target="_blank" class="text-blue-600 hover:text-blue-800 hover:underline transition-colors duration-200">ANEXO 2 CAS</a>
    <a href="https://oedigob-my.sharepoint.com/:w:/g/personal/tecnologia_oedi_gob_pe/Edl5ZUW1Hb1JodLDbYJbT14BtqoiGA5HjyKuQ-CRHcvxHw" target="_blank" class="text-blue-600 hover:text-blue-800 hover:underline transition-colors duration-200">ANEXO 3 CAS</a>
    <a href="https://oedigob-my.sharepoint.com/:b:/g/personal/tecnologia_oedi_gob_pe/Eb21a6hbfEpCjZexsxVTVtMBieP-n_EdwtGz99Rag9HLNg" target="_blank" class="text-blue-600 hover:text-blue-800 hover:underline transition-colors duration-200">ANEXO 4 CAS</a>
  </div>
</div>


<!-- CONVOCATORIAS INDIVIDUALES -->
<div class="space-y-6">
  <?php foreach ($convocatoriasPagina as $index => $convocatoria): ?>
  <?php 
    // Determinar el estado de la convocatoria
    $estadoConvocatoria = 'Abierto';
    $colorEstado = 'bg-green-100 text-green-800';
    
    // Buscar si existe resultado final
    if (!empty($convocatoria['resultados'])) {
      foreach ($convocatoria['resultados'] as $resultado) {
        if (stripos($resultado['titulo'], 'FINAL') !== false) {
          $estadoConvocatoria = 'Cerrado';
          $colorEstado = 'bg-gray-100 text-gray-800';
          break;
        }
      }
    }
  ?>
  <div class="accordion-item bg-white rounded-lg shadow-md overflow-hidden">
    <div class="accordion-header bg-blue-700 text-white p-4 flex justify-between items-center cursor-pointer">
      <div>
        <div class="flex items-center gap-2 mb-1">
          <h2 class="text-xl font-bold">CAS N° <?php echo $convocatoria['numero']; ?>-<?php echo $convocatoria['anio']; ?>-OEDI</h2>
          <span class="<?php echo $colorEstado; ?> text-xs font-medium px-2.5 py-0.5 rounded">
            <?php echo $estadoConvocatoria; ?>
          </span>
        </div>
        <p class="text-sm text-blue-100">Fecha de publicación: <?php echo $convocatoria['fecha_publicacion']; ?></p>
      </div>
      <div class="transform transition-transform duration-300 accordion-icon <?php if(mostrarAbierta($index)) echo 'rotate-180'; ?>">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
        </svg>
      </div>
    </div>
    <div class="accordion-content p-5 <?php if(!mostrarAbierta($index)) echo 'hidden'; ?>">
      <div class="flex flex-wrap items-center gap-3 mb-3">
        <p class="text-lg">Puesto: <span class="font-semibold"><?php echo $convocatoria['puesto']; ?></span></p>
        <?php if(isset($convocatoria['categoria'])): ?>
          <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded">
            <?php echo htmlspecialchars($convocatoria['categoria']); ?>
          </span>
        <?php endif; ?>
      </div>
      
      <!-- Bases y anexos -->
      <h3 class="font-semibold mb-2 text-gray-700">Bases y Anexos:</h3>
      <?php if(!empty($convocatoria['bases_anexos'])): ?>
        <?php foreach($convocatoria['bases_anexos'] as $base): ?>
          <a href="<?php echo $base['url']; ?>" target="_blank" 
             class="block mb-4 text-blue-600 hover:text-blue-800 hover:underline transition-colors duration-200">
            <?php echo $base['titulo']; ?>
          </a>
        <?php endforeach; ?>
      <?php endif; ?>
      
      <!-- Resultados -->
      <h3 class="font-semibold mb-2 text-gray-700">Resultados:</h3>
      <?php if(!empty($convocatoria['resultados'])): ?>
        <?php foreach($convocatoria['resultados'] as $resultado): ?>
          <a href="<?php echo $resultado['url']; ?>" target="_blank" 
             class="block mb-2 text-blue-600 hover:text-blue-800 hover:underline transition-colors duration-200">
            <?php echo $resultado['titulo']; ?>
          </a>
        <?php endforeach; ?>
      <?php else: ?>
        <p class="text-gray-500 italic">Pendiente de publicación</p>
      <?php endif; ?>
    </div>
  </div>
  <?php endforeach; ?>
</div>

<!-- Navegación inferior de la paginación -->
<?php if ($totalPaginas > 1): ?>
<div class="flex justify-between items-center bg-white rounded-lg p-4 mt-8 shadow-md">
  <div class="text-gray-700">
    Mostrando <?php echo count($convocatoriasPagina); ?> de <?php echo $totalConvocatorias; ?> convocatorias
  </div>
  <div class="flex space-x-2">
    <?php 
      // Preparar los parámetros de URL para los enlaces de paginación
      $urlParams = [];
      if (!empty($searchTerm)) $urlParams[] = 'search=' . urlencode($searchTerm);
      if (!empty($categoriaFiltro)) $urlParams[] = 'categoria=' . urlencode($categoriaFiltro);
      $urlParamsStr = !empty($urlParams) ? '&' . implode('&', $urlParams) : '';
    ?>
    
    <?php if ($paginaActual > 1): ?>
      <a href="?pagina=1<?php echo $urlParamsStr; ?>" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition-colors duration-200">
        Inicio
      </a>
      <a href="?pagina=<?php echo ($paginaActual - 1) . $urlParamsStr; ?>" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition-colors duration-200">
        Anterior
      </a>
    <?php endif; ?>
    
    <!-- Números de página -->
    <div class="flex space-x-1">
      <?php 
        $inicio = max(1, $paginaActual - 2);
        $fin = min($totalPaginas, $paginaActual + 2);
        
        if ($inicio > 1) echo '<span class="px-3 py-2 text-gray-500">...</span>';
        
        for ($i = $inicio; $i <= $fin; $i++) {
          if ($i == $paginaActual) {
            echo '<span class="px-4 py-2 bg-blue-800 text-white rounded">' . $i . '</span>';
          } else {
            echo '<a href="?pagina=' . $i . $urlParamsStr . '" class="px-4 py-2 bg-gray-200 text-gray-700 rounded hover:bg-gray-300 transition-colors duration-200">' . $i . '</a>';
          }
        }
        
        if ($fin < $totalPaginas) echo '<span class="px-3 py-2 text-gray-500">...</span>';
      ?>
    </div>
    
    <?php if ($paginaActual < $totalPaginas): ?>
      <a href="?pagina=<?php echo ($paginaActual + 1) . $urlParamsStr; ?>" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition-colors duration-200">
        Siguiente
      </a>
      <a href="?pagina=<?php echo $totalPaginas . $urlParamsStr; ?>" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition-colors duration-200">
        Final
      </a>
    <?php endif; ?>
  </div>
</div>
<?php endif; ?>

<script src="script.js"></script>
</body>
</html>
