<!-- Título de la sección -->
<h2 class="text-xl font-bold mb-4">Agregar Nueva Convocatoria</h2>

<!-- Formulario para agregar nueva convocatoria -->
<div class="bg-white p-6 rounded-lg shadow-md">
    <form method="post" action="">
        <input type="hidden" name="accion" value="agregar_convocatoria">
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label for="numero" class="block text-sm font-medium text-gray-700 mb-1">Número:</label>
                <input type="text" id="numero" name="numero" required
                       class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50"
                       placeholder="Ej. 001">
            </div>
            
            <div>
                <label for="anio" class="block text-sm font-medium text-gray-700 mb-1">Año:</label>
                <input type="text" id="anio" name="anio" required
                       class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50"
                       value="<?php echo date('Y'); ?>" placeholder="Ej. 2025">
            </div>
            
            <div>
                <label for="fecha_publicacion" class="block text-sm font-medium text-gray-700 mb-1">Fecha de Publicación:</label>
                <input type="text" id="fecha_publicacion" name="fecha_publicacion" required
                       class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50"
                       value="<?php echo date('d/m/Y'); ?>" placeholder="DD/MM/AAAA">
                <small class="text-gray-500">Formato: DD/MM/AAAA</small>
            </div>
            
            <div>
                <label for="categoria" class="block text-sm font-medium text-gray-700 mb-1">Categoría:</label>
                <select id="categoria" name="categoria" required
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                    <option value="">Seleccione una categoría</option>
                    <?php if (!empty($categorias)): ?>
                        <?php foreach ($categorias as $categoria): ?>
                            <option value="<?php echo $categoria; ?>"><?php echo $categoria; ?></option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                    <!-- Opciones predefinidas si no hay categorías existentes -->
                    <?php if (empty($categorias)): ?>
                        <option value="Administración">Administración</option>
                        <option value="Legal">Legal</option>
                        <option value="Finanzas">Finanzas</option>
                        <option value="Tecnología">Tecnología</option>
                        <option value="Gestión">Gestión</option>
                        <option value="Recursos Humanos">Recursos Humanos</option>
                        <option value="Comunicaciones">Comunicaciones</option>
                        <option value="Otro">Otro</option>
                    <?php endif; ?>
                </select>
            </div>
        </div>
        
        <div class="mt-6">
            <label for="puesto" class="block text-sm font-medium text-gray-700 mb-1">Nombre del Puesto:</label>
            <input type="text" id="puesto" name="puesto" required
                   class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50"
                   placeholder="Ej. UN/A ANALISTA EN TECNOLOGÍAS DE INFORMACIÓN">
        </div>
        
        <div class="mt-6 flex justify-end">
            <a href="index.php" class="bg-gray-500 hover:bg-gray-600 text-white py-2 px-4 rounded mr-2">
                Cancelar
            </a>
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 rounded">
                Guardar Convocatoria
            </button>
        </div>
    </form>
</div> 