<?php
/*
 * Migración automática desde PowerApps a PHP + JS
 * Generado: 2025-05-04
 */

// Obtener la fecha y hora actual en formato español
setlocale(LC_TIME, 'es_ES.UTF-8', 'es_ES', 'esp');
$fechaActual = date("d-m-Y H:i");

// Incluir el archivo de datos de convocatorias
require_once('datos_convocatorias.php');

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
<!-- CABECERA -->
<div class="bg-white shadow-sm rounded-lg p-6 mb-8">
  <h1 class="text-3xl font-bold text-center text-blue-800">Convocatorias CAS 2025 OEDI</h1>
  <!-- Opción 1: Usando PHP (fecha generada en el servidor) -->
  <p class="text-sm text-right text-gray-500 mt-2">Última Actualización: <?php echo $fechaActual; ?></p>
  
  <!-- Opción 2: Usando JavaScript (fecha del cliente - comentada) -->
  <!-- <p class="text-sm text-right text-gray-500 mt-2">Última Actualización: <span id="fechaActualizacion"></span></p> -->
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
  <?php foreach ($convocatorias as $index => $convocatoria): ?>
  <div class="accordion-item bg-white rounded-lg shadow-md overflow-hidden">
    <div class="accordion-header bg-blue-700 text-white p-4 flex justify-between items-center cursor-pointer">
      <div>
        <h2 class="text-xl font-bold">CAS N° <?php echo $convocatoria['numero']; ?>-<?php echo $convocatoria['anio']; ?>-OEDI</h2>
        <p class="text-sm text-blue-100">Fecha de publicación: <?php echo $convocatoria['fecha_publicacion']; ?></p>
      </div>
      <div class="transform transition-transform duration-300 accordion-icon <?php if(mostrarAbierta($index)) echo 'rotate-180'; ?>">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
        </svg>
      </div>
    </div>
    <div class="accordion-content p-5 <?php if(!mostrarAbierta($index)) echo 'hidden'; ?>">
      <p class="text-lg mb-3">Puesto: <span class="font-semibold"><?php echo $convocatoria['puesto']; ?></span></p>
      
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

<script src="script.js"></script>
</body>
</html>
