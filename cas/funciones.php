<?php
/**
 * Funciones para gestionar las convocatorias CAS
 */

/**
 * Carga las convocatorias desde el archivo datos_convocatorias.php
 */
function cargar_convocatorias() {
    // Incluir el archivo de datos con ruta absoluta
    $rutaArchivo = __DIR__ . '/datos_convocatorias.php';
    
    // Variable para almacenar las convocatorias
    $convocatorias = [];
    
    // Intentamos cargar desde la carpeta cas
    if (file_exists($rutaArchivo)) {
        include($rutaArchivo);
        if (isset($convocatorias) && is_array($convocatorias)) {
            return $convocatorias;
        }
    }
    
    // Si no se encontró en ningún lado, devolver array vacío
    return [];
}

/**
 * Guarda el array de convocatorias en el archivo datos_convocatorias.php
 */
function guardar_convocatorias($convocatorias) {
    // Ordenar por número descendente
    usort($convocatorias, function($a, $b) {
        // Convertir números de convocatoria a enteros para comparación
        $numA = isset($a['numero']) ? intval($a['numero']) : 0;
        $numB = isset($b['numero']) ? intval($b['numero']) : 0;
        
        // Ordenar por año primero, luego por número
        if (isset($a['anio']) && isset($b['anio']) && $a['anio'] !== $b['anio']) {
            return $b['anio'] <=> $a['anio']; // Orden descendente por año
        }
        
        return $numB <=> $numA; // Orden descendente por número
    });
    
    // Preparar el contenido del archivo
    $contenido = "<?php\n/**\n * Archivo de datos para las convocatorias CAS\n *\n";
    $contenido .= " * Este archivo contiene todos los datos de las convocatorias CAS.\n";
    $contenido .= " * Para añadir una nueva convocatoria, simplemente agrega un nuevo elemento al array.\n";
    $contenido .= " * Para actualizar resultados, modifica el array 'resultados' de la convocatoria correspondiente.\n";
    $contenido .= " */\n\n";
    $contenido .= "// Array con la información de todas las convocatorias\n";
    $contenido .= "\$convocatorias = " . var_export($convocatorias, true) . ";\n";
    
    // Guardar el archivo
    $rutaArchivo = __DIR__ . '/datos_convocatorias.php';
    $resultado = file_put_contents($rutaArchivo, $contenido);
    
    return $resultado !== false;
}

/**
 * Agrega una nueva convocatoria al array
 */
function agregar_convocatoria($datos) {
    // Cargar las convocatorias existentes
    $convocatorias = cargar_convocatorias();
    
    // Generar un ID único para la nueva convocatoria
    $max_id = 0;
    foreach ($convocatorias as $convocatoria) {
        if (isset($convocatoria['id']) && $convocatoria['id'] > $max_id) {
            $max_id = $convocatoria['id'];
        }
    }
    $nuevo_id = $max_id + 1;
    
    // Crear la nueva convocatoria
    $nueva_convocatoria = [
        'id' => $nuevo_id,
        'numero' => trim($datos['numero']),
        'anio' => trim($datos['anio']),
        'fecha_publicacion' => trim($datos['fecha_publicacion']),
        'puesto' => trim($datos['puesto']),
        'categoria' => trim($datos['categoria']),
        'bases_anexos' => [],
        'resultados' => []
    ];
    
    // Agregar al inicio del array (para que aparezca primero)
    array_unshift($convocatorias, $nueva_convocatoria);
    
    // Guardar y retornar resultado
    return guardar_convocatorias($convocatorias);
}

/**
 * Actualiza una convocatoria existente
 */
function editar_convocatoria($datos) {
    // Cargar las convocatorias existentes
    $convocatorias = cargar_convocatorias();
    
    // ID de la convocatoria a editar
    $id = isset($datos['id']) ? (int)$datos['id'] : -1;
    
    // Buscar la convocatoria por su ID
    $encontrado = false;
    foreach ($convocatorias as $key => $convocatoria) {
        if (isset($convocatoria['id']) && $convocatoria['id'] == $id) {
            // Actualizar los datos manteniendo el ID original
            $convocatorias[$key]['numero'] = trim($datos['numero']);
            $convocatorias[$key]['anio'] = trim($datos['anio']);
            $convocatorias[$key]['fecha_publicacion'] = trim($datos['fecha_publicacion']);
            $convocatorias[$key]['puesto'] = trim($datos['puesto']);
            $convocatorias[$key]['categoria'] = trim($datos['categoria']);
            
            $encontrado = true;
            break;
        }
    }
    
    // Si no se encontró la convocatoria, retornar falso
    if (!$encontrado) {
        return false;
    }
    
    // Guardar y retornar resultado
    return guardar_convocatorias($convocatorias);
}

/**
 * Elimina una convocatoria por su ID
 */
function eliminar_convocatoria($id) {
    // Cargar las convocatorias existentes
    $convocatorias = cargar_convocatorias();
    
    // Buscar la convocatoria por su ID
    $indice_a_eliminar = -1;
    foreach ($convocatorias as $key => $convocatoria) {
        if (isset($convocatoria['id']) && $convocatoria['id'] == $id) {
            $indice_a_eliminar = $key;
            break;
        }
    }
    
    // Si no se encontró la convocatoria, retornar falso
    if ($indice_a_eliminar == -1) {
        return false;
    }
    
    // Eliminar la convocatoria
    array_splice($convocatorias, $indice_a_eliminar, 1);
    
    // Guardar y retornar resultado
    return guardar_convocatorias($convocatorias);
}

/**
 * Agrega un nuevo elemento a bases_anexos de una convocatoria
 */
function agregar_base_anexo($datos) {
    // Cargar las convocatorias existentes
    $convocatorias = cargar_convocatorias();
    
    // ID de la convocatoria
    $id = isset($datos['convocatoria_id']) ? (int)$datos['convocatoria_id'] : -1;
    
    // Buscar la convocatoria por su ID
    $encontrado = false;
    foreach ($convocatorias as $key => $convocatoria) {
        if (isset($convocatoria['id']) && $convocatoria['id'] == $id) {
            // Crear el nuevo elemento
            $nuevo_base_anexo = [
                'titulo' => trim($datos['titulo']),
                'url' => trim($datos['url'])
            ];
            
            // Agregar al array de bases_anexos
            if (!isset($convocatorias[$key]['bases_anexos'])) {
                $convocatorias[$key]['bases_anexos'] = [];
            }
            $convocatorias[$key]['bases_anexos'][] = $nuevo_base_anexo;
            
            $encontrado = true;
            break;
        }
    }
    
    // Si no se encontró la convocatoria, retornar falso
    if (!$encontrado) {
        return false;
    }
    
    // Guardar y retornar resultado
    return guardar_convocatorias($convocatorias);
}

/**
 * Elimina un elemento de bases_anexos de una convocatoria
 */
function eliminar_base_anexo($convocatoria_id, $indice) {
    // Cargar las convocatorias existentes
    $convocatorias = cargar_convocatorias();
    
    // Buscar la convocatoria por su ID
    $encontrado = false;
    foreach ($convocatorias as $key => $convocatoria) {
        if (isset($convocatoria['id']) && $convocatoria['id'] == $convocatoria_id) {
            // Verificar que existe el elemento a eliminar
            if (!isset($convocatorias[$key]['bases_anexos']) || !isset($convocatorias[$key]['bases_anexos'][$indice])) {
                return false;
            }
            
            // Eliminar el elemento
            array_splice($convocatorias[$key]['bases_anexos'], $indice, 1);
            
            $encontrado = true;
            break;
        }
    }
    
    // Si no se encontró la convocatoria, retornar falso
    if (!$encontrado) {
        return false;
    }
    
    // Guardar y retornar resultado
    return guardar_convocatorias($convocatorias);
}

/**
 * Agrega un nuevo elemento a resultados de una convocatoria
 */
function agregar_resultado($datos) {
    // Cargar las convocatorias existentes
    $convocatorias = cargar_convocatorias();
    
    // ID de la convocatoria
    $id = isset($datos['convocatoria_id']) ? (int)$datos['convocatoria_id'] : -1;
    
    // Buscar la convocatoria por su ID
    $encontrado = false;
    foreach ($convocatorias as $key => $convocatoria) {
        if (isset($convocatoria['id']) && $convocatoria['id'] == $id) {
            // Crear el nuevo elemento
            $nuevo_resultado = [
                'titulo' => trim($datos['titulo']),
                'url' => trim($datos['url'])
            ];
            
            // Agregar al array de resultados
            if (!isset($convocatorias[$key]['resultados'])) {
                $convocatorias[$key]['resultados'] = [];
            }
            $convocatorias[$key]['resultados'][] = $nuevo_resultado;
            
            $encontrado = true;
            break;
        }
    }
    
    // Si no se encontró la convocatoria, retornar falso
    if (!$encontrado) {
        return false;
    }
    
    // Guardar y retornar resultado
    return guardar_convocatorias($convocatorias);
}

/**
 * Elimina un elemento de resultados de una convocatoria
 */
function eliminar_resultado($convocatoria_id, $indice) {
    // Cargar las convocatorias existentes
    $convocatorias = cargar_convocatorias();
    
    // Buscar la convocatoria por su ID
    $encontrado = false;
    foreach ($convocatorias as $key => $convocatoria) {
        if (isset($convocatoria['id']) && $convocatoria['id'] == $convocatoria_id) {
            // Verificar que existe el elemento a eliminar
            if (!isset($convocatorias[$key]['resultados']) || !isset($convocatorias[$key]['resultados'][$indice])) {
                return false;
            }
            
            // Eliminar el elemento
            array_splice($convocatorias[$key]['resultados'], $indice, 1);
            
            $encontrado = true;
            break;
        }
    }
    
    // Si no se encontró la convocatoria, retornar falso
    if (!$encontrado) {
        return false;
    }
    
    // Guardar y retornar resultado
    return guardar_convocatorias($convocatorias);
}

/**
 * Obtiene una convocatoria por su ID
 */
function obtener_convocatoria($id) {
    // Cargar las convocatorias
    $convocatorias = cargar_convocatorias();
    
    // Buscar por la propiedad 'id'
    foreach ($convocatorias as $convocatoria) {
        if (isset($convocatoria['id']) && $convocatoria['id'] == $id) {
            return $convocatoria;
        }
    }
    
    // No se encontró la convocatoria
    return null;
}

/**
 * Genera un mensaje de notificación
 */
function generar_notificacion($tipo, $mensaje) {
    $_SESSION['notificacion'] = [
        'tipo' => $tipo,
        'mensaje' => $mensaje
    ];
}

/**
 * Muestra una notificación guardada en la sesión
 */
function mostrar_notificacion() {
    if (isset($_SESSION['notificacion'])) {
        $notificacion = $_SESSION['notificacion'];
        $tipo = $notificacion['tipo'];
        $mensaje = $notificacion['mensaje'];
        
        // Determinar clases de estilo según el tipo
        $clases = '';
        switch ($tipo) {
            case 'exito':
                $clases = 'bg-green-100 border-l-4 border-green-500 text-green-700';
                break;
            case 'error':
                $clases = 'bg-red-100 border-l-4 border-red-500 text-red-700';
                break;
            case 'info':
                $clases = 'bg-blue-100 border-l-4 border-blue-500 text-blue-700';
                break;
            case 'advertencia':
                $clases = 'bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700';
                break;
        }
        
        // Mostrar notificación
        echo '<div class="' . $clases . ' p-4 mb-4" role="alert">';
        echo '<p>' . $mensaje . '</p>';
        echo '</div>';
        
        // Limpiar notificación
        unset($_SESSION['notificacion']);
    }
} 