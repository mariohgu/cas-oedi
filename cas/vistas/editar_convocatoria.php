<?php
// Obtener la convocatoria a editar
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
<h2 class="text-xl font-bold mb-4">Editar Convocatoria</h2>

<!-- Formulario para editar convocatoria -->
<div class="bg-white p-6 rounded-lg shadow-md">
    <form method="post" action="">
        <input type="hidden" name="accion" value="editar_convocatoria">
        <input type="hidden" name="id" value="<?php echo $convocatoria['id']; ?>">
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label for="numero" class="block text-sm font-medium text-gray-700 mb-1">Número:</label>
                <input type="text" id="numero" name="numero" required
                       class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50"
                       value="<?php echo $convocatoria['numero']; ?>" placeholder="Ej. 001">
            </div>
            
            <div>
                <label for="anio" class="block text-sm font-medium text-gray-700 mb-1">Año:</label>
                <input type="text" id="anio" name="anio" required
                       class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50"
                       value="<?php echo $convocatoria['anio']; ?>" placeholder="Ej. 2025">
            </div>
            
            <div>
                <label for="fecha_publicacion" class="block text-sm font-medium text-gray-700 mb-1">Fecha de Publicación:</label>
                <input type="text" id="fecha_publicacion" name="fecha_publicacion" required
                       class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50"
                       value="<?php echo $convocatoria['fecha_publicacion']; ?>" placeholder="DD/MM/AAAA">
                <small class="text-gray-500">Formato: DD/MM/AAAA</small>
            </div>
            
            <div>
                <label for="categoria" class="block text-sm font-medium text-gray-700 mb-1">Categoría:</label>
                <select id="categoria" name="categoria" required
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                    <option value="">Seleccione una categoría</option>
                    <?php 
                    // Categorías predefinidas más las existentes
                    $todas_categorias = array_merge(
                        [
                            "Administración",
                            "Legal",
                            "Finanzas",
                            "Tecnología",
                            "Gestión",
                            "Recursos Humanos",
                            "Comunicaciones",
                            "Otro"
                        ], 
                        $categorias
                    );
                    $todas_categorias = array_unique($todas_categorias);
                    sort($todas_categorias);
                    
                    foreach ($todas_categorias as $cat): 
                    ?>
                        <option value="<?php echo $cat; ?>" <?php echo ($convocatoria['categoria'] === $cat) ? 'selected' : ''; ?>>
                            <?php echo $cat; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
        
        <div class="mt-6">
            <label for="puesto" class="block text-sm font-medium text-gray-700 mb-1">Nombre del Puesto:</label>
            <input type="text" id="puesto" name="puesto" required
                   class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50"
                   value="<?php echo $convocatoria['puesto']; ?>" 
                   placeholder="Ej. UN/A ANALISTA EN TECNOLOGÍAS DE INFORMACIÓN">
        </div>
        
        <div class="mt-6 flex justify-end">
            <a href="index.php" class="bg-gray-500 hover:bg-gray-600 text-white py-2 px-4 rounded mr-2">
                Cancelar
            </a>
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 rounded">
                Guardar Cambios
            </button>
        </div>
    </form>
</div> 