<!-- Título de la sección -->
<h2 class="text-xl font-bold mb-4">Listado de Convocatorias</h2>

<!-- Tabla de convocatorias -->
<div class="overflow-x-auto">
    <table class="min-w-full bg-white">
        <thead class="bg-gray-100 border-b">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">N°</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Puesto</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fecha</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Categoría</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-200">
            <?php if (!empty($convocatorias)): ?>
                <?php foreach ($convocatorias as $indice => $convocatoria): ?>
                    <tr class="hover:bg-gray-50">
                        <td class="py-3 px-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            <?php echo $convocatoria['numero']; ?>/<?php echo $convocatoria['anio']; ?>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-900">
                            <?php echo $convocatoria['puesto']; ?>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            <?php echo $convocatoria['fecha_publicacion']; ?>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            <?php if (!empty($convocatoria['categoria'])): ?>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium <?php echo $colorEstado; ?>">
                                    <?php echo htmlspecialchars($convocatoria['categoria']); ?>
                                </span>
                            <?php else: ?>
                                <span class="text-gray-400">Sin categoría</span>
                            <?php endif; ?>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex space-x-2">
                                <a href="index.php?accion=editar&id=<?php echo $convocatoria['id']; ?>" 
                                   class="text-blue-600 hover:text-blue-900" title="Editar convocatoria">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="index.php?accion=bases_anexos&id=<?php echo $convocatoria['id']; ?>" 
                                   class="text-green-600 hover:text-green-900" title="Gestionar bases y anexos">
                                    <i class="fas fa-file-alt"></i>
                                </a>
                                <a href="index.php?accion=resultados&id=<?php echo $convocatoria['id']; ?>" 
                                   class="text-purple-600 hover:text-purple-900" title="Gestionar resultados">
                                    <i class="fas fa-clipboard-list"></i>
                                </a>
                                <form method="post" action="" class="inline" 
                                      onsubmit="return confirm('¿Está seguro de que desea eliminar esta convocatoria?');">
                                    <input type="hidden" name="accion" value="eliminar_convocatoria">
                                    <input type="hidden" name="id" value="<?php echo $convocatoria['id']; ?>">
                                    <button type="submit" class="text-red-600 hover:text-red-900" title="Eliminar convocatoria">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="5" class="py-3 px-4 text-center text-sm text-gray-500">
                        No hay convocatorias registradas
                    </td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div> 