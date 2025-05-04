<?php
// Obtener la convocatoria
$convocatoria = obtener_convocatoria($id);

// Si no se encontró la convocatoria, mostrar mensaje de error
if (!$convocatoria) {
    echo '<div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4" role="alert">';
    echo '<p>La convocatoria solicitada no existe o el ID no es válido.</p>';
    echo '</div>';
    return;
}
?>

<!-- Título de la sección -->
<h2 class="text-xl font-bold mb-4">
    Gestionar Bases y Anexos - Convocatoria <?php echo $convocatoria['numero']; ?>/<?php echo $convocatoria['anio']; ?>
</h2>
<p class="mb-6 text-gray-600">
    <?php echo $convocatoria['puesto']; ?>
</p>

<!-- Listado de bases y anexos existentes -->
<div class="mb-6">
    <h3 class="text-lg font-semibold mb-3">Bases y Anexos Actuales</h3>
    
    <?php if (empty($convocatoria['bases_anexos'])): ?>
        <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-4">
            <p class="text-yellow-800">
                No hay bases o anexos registrados para esta convocatoria.
            </p>
        </div>
    <?php else: ?>
        <div class="overflow-x-auto rounded-lg shadow">
            <table class="min-w-full bg-white">
                <thead class="bg-gray-100 border-b">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Título</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">URL</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    <?php foreach ($convocatoria['bases_anexos'] as $indice => $base_anexo): ?>
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 text-sm text-gray-900">
                                <?php echo $base_anexo['titulo']; ?>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-900">
                                <a href="<?php echo $base_anexo['url']; ?>" target="_blank" class="text-blue-600 hover:underline">
                                    <?php echo substr($base_anexo['url'], 0, 50) . (strlen($base_anexo['url']) > 50 ? '...' : ''); ?>
                                </a>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <form method="post" action="" class="inline" 
                                      onsubmit="return confirm('¿Está seguro de que desea eliminar este elemento?');">
                                    <input type="hidden" name="accion" value="eliminar_base_anexo">
                                    <input type="hidden" name="convocatoria_id" value="<?php echo $convocatoria['id']; ?>">
                                    <input type="hidden" name="indice" value="<?php echo $indice; ?>">
                                    <button type="submit" class="text-red-600 hover:text-red-900" title="Eliminar base/anexo">
                                        <i class="fas fa-trash-alt"></i> Eliminar
                                    </button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<!-- Formulario para agregar nuevo base/anexo -->
<div class="bg-white p-6 rounded-lg shadow-md">
    <h3 class="text-lg font-semibold mb-4">Agregar Nuevo Base/Anexo</h3>
    
    <form method="post" action="">
        <input type="hidden" name="accion" value="agregar_base_anexo">
        <input type="hidden" name="convocatoria_id" value="<?php echo $convocatoria['id']; ?>">
        
        <div class="grid grid-cols-1 gap-6">
            <div>
                <label for="titulo" class="block text-sm font-medium text-gray-700 mb-1">Título:</label>
                <input type="text" id="titulo" name="titulo" required
                       class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50"
                       placeholder="Ej. ANEXO A BASES DEL PROCESO DE SELECCIÓN">
            </div>
            
            <div>
                <label for="url" class="block text-sm font-medium text-gray-700 mb-1">URL del documento:</label>
                <input type="url" id="url" name="url" required
                       class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50"
                       placeholder="https://example.com/documento.pdf">
                <small class="text-gray-500">Ingrese la URL completa donde se encuentra publicado el documento.</small>
            </div>
        </div>
        
        <div class="mt-6 flex justify-end">
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 rounded">
                Agregar Base/Anexo
            </button>
        </div>
    </form>
</div>

<!-- Botón para volver al listado -->
<div class="mt-6">
    <a href="index.php" class="inline-block bg-gray-500 hover:bg-gray-600 text-white py-2 px-4 rounded">
        <i class="fas fa-arrow-left mr-2"></i> Volver al Listado
    </a>
</div> 