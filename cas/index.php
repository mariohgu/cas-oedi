<?php
session_start();
// Control de acceso simple (en producción se recomienda un sistema más seguro)
$usuario_correcto = "rrhh1";
$password_correcto = "O3d12025.";

// Incluir funciones de administración
require_once(__DIR__ . '/funciones.php');

// Manejo de inicio de sesión
if (isset($_POST['login'])) {
    $usuario = isset($_POST['usuario']) ? $_POST['usuario'] : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';
    
    if ($usuario === $usuario_correcto && $password === $password_correcto) {
        $_SESSION['admin_autenticado'] = true;
        header("Location: index.php");
        exit;
    } else {
        $error_login = "Usuario o contraseña incorrectos";
    }
}

// Cerrar sesión
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: index.php");
    exit;
}

// Verificar si el usuario está autenticado
$autenticado = isset($_SESSION['admin_autenticado']) && $_SESSION['admin_autenticado'] === true;

// Cargar datos de convocatorias
$convocatorias = cargar_convocatorias();

// Procesar acciones solo si está autenticado
if ($autenticado) {
    // Verificar si se enviaron datos de formulario
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['accion'])) {
            switch ($_POST['accion']) {
                case 'agregar_convocatoria':
                    if (agregar_convocatoria($_POST)) {
                        generar_notificacion('exito', 'Convocatoria agregada con éxito');
                    } else {
                        generar_notificacion('error', 'Error al agregar la convocatoria');
                    }
                    break;
                case 'editar_convocatoria':
                    if (editar_convocatoria($_POST)) {
                        generar_notificacion('exito', 'Convocatoria actualizada con éxito');
                    } else {
                        generar_notificacion('error', 'Error al actualizar la convocatoria');
                    }
                    break;
                case 'eliminar_convocatoria':
                    if (eliminar_convocatoria($_POST['id'])) {
                        generar_notificacion('exito', 'Convocatoria eliminada con éxito');
                    } else {
                        generar_notificacion('error', 'Error al eliminar la convocatoria');
                    }
                    break;
                case 'agregar_base_anexo':
                    if (agregar_base_anexo($_POST)) {
                        generar_notificacion('exito', 'Base/Anexo agregado con éxito');
                    } else {
                        generar_notificacion('error', 'Error al agregar la base/anexo');
                    }
                    break;
                case 'eliminar_base_anexo':
                    if (eliminar_base_anexo($_POST['convocatoria_id'], $_POST['indice'])) {
                        generar_notificacion('exito', 'Base/Anexo eliminado con éxito');
                    } else {
                        generar_notificacion('error', 'Error al eliminar la base/anexo');
                    }
                    break;
                case 'agregar_resultado':
                    if (agregar_resultado($_POST)) {
                        generar_notificacion('exito', 'Resultado agregado con éxito');
                    } else {
                        generar_notificacion('error', 'Error al agregar el resultado');
                    }
                    break;
                case 'eliminar_resultado':
                    if (eliminar_resultado($_POST['convocatoria_id'], $_POST['indice'])) {
                        generar_notificacion('exito', 'Resultado eliminado con éxito');
                    } else {
                        generar_notificacion('error', 'Error al eliminar el resultado');
                    }
                    break;
            }
            
            // Recargar datos después de cualquier acción
            $convocatorias = cargar_convocatorias();
        }
    }
}

// Verificar si hay datos
$hayDatos = !empty($convocatorias);

// Si no hay datos, mostrar un mensaje de ayuda en las vistas de administración
$mostrarAyuda = !$hayDatos && $autenticado;

// Obtener categorías únicas
$categorias = [];
foreach ($convocatorias as $convocatoria) {
    if (isset($convocatoria['categoria']) && !in_array($convocatoria['categoria'], $categorias)) {
        $categorias[] = $convocatoria['categoria'];
    }
}
sort($categorias);

// Determinar acción actual
$accion = isset($_GET['accion']) ? $_GET['accion'] : 'listar';
$id = isset($_GET['id']) ? (int)$_GET['id'] : -1;
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administrador de Convocatorias CAS</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
        .btn-header {
            @apply hover:bg-blue-700 px-3 py-1 rounded transition-colors flex items-center;
        }
    </style>
</head>
<body class="bg-gray-100 min-h-screen">

<?php if (!$autenticado): ?>
    <!-- Formulario de Inicio de Sesión -->
    <div class="min-h-screen flex items-center justify-center">
        <div class="bg-white p-8 rounded-lg shadow-md w-full max-w-md">
            <h1 class="text-2xl font-bold text-center mb-6 text-blue-800">Acceso al Panel de Administración</h1>
            
            <?php if (isset($error_login)): ?>
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4" role="alert">
                    <p><?php echo $error_login; ?></p>
                </div>
            <?php endif; ?>
            
            <form method="post" action="">
                <div class="mb-4">
                    <label for="usuario" class="block text-gray-700 text-sm font-bold mb-2">Usuario:</label>
                    <input type="text" id="usuario" name="usuario" required
                           class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                </div>
                
                <div class="mb-6">
                    <label for="password" class="block text-gray-700 text-sm font-bold mb-2">Contraseña:</label>
                    <input type="password" id="password" name="password" required
                           class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                </div>
                
                <div class="flex items-center justify-between">
                    <button type="submit" name="login" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline w-full">
                        Iniciar Sesión
                    </button>
                </div>
            </form>
        </div>
    </div>
<?php else: ?>
    <!-- Panel de Administración -->
    <div class="flex flex-col h-screen">
        <!-- Encabezado -->
        <header class="bg-blue-700 text-white">
            <div class="container mx-auto px-4 py-3 flex justify-between items-center">
                <h1 class="text-xl font-bold">Panel de Administración - Convocatorias CAS</h1>
                <div class="flex items-center space-x-2">
                    <a href="../index.php" target="_blank" class="btn-header">
                        <i class="fas fa-eye mr-1"></i> Ver Sitio
                    </a>
                    <a href="index.php?logout=1" class="btn-header">
                        <i class="fas fa-sign-out-alt mr-1"></i> Cerrar Sesión
                    </a>
                </div>
            </div>
        </header>
        
        <!-- Contenido Principal -->
        <main class="flex-grow container mx-auto p-4">
            <!-- Notificaciones -->
            <?php mostrar_notificacion(); ?>
            
            <div class="flex flex-col md:flex-row gap-4 mt-2">
                <!-- Menú Lateral -->
                <div class="w-full md:w-48 flex-shrink-0 bg-white rounded-md shadow">
                    <div class="p-3 border-b border-gray-200">
                        <h2 class="text-lg font-semibold">Menú</h2>
                    </div>
                    <nav class="p-2">
                        <ul class="space-y-1">
                            <li>
                                <a href="index.php" class="<?php echo $accion === 'listar' ? 'bg-blue-100 text-blue-800' : 'text-gray-700 hover:text-blue-600'; ?> block px-3 py-2 rounded transition-colors">
                                    <i class="fas fa-list mr-2"></i> Listar Convocatorias
                                </a>
                            </li>
                            <li>
                                <a href="index.php?accion=nueva" class="<?php echo $accion === 'nueva' ? 'bg-blue-100 text-blue-800' : 'text-gray-700 hover:text-blue-600'; ?> block px-3 py-2 rounded transition-colors">
                                    <i class="fas fa-plus-circle mr-2"></i> Nueva Convocatoria
                                </a>
                            </li>
                        </ul>
                    </nav>
                </div>
                
                <!-- Contenido Dinámico -->
                <div class="flex-grow bg-white rounded-md shadow p-4">
                    <?php if ($mostrarAyuda): ?>
                        <!-- Mensaje de ayuda si no hay datos -->
                        <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-3 mb-4">
                            <p class="font-bold">No hay convocatorias registradas</p>
                            <p>Para empezar, haga clic en "Nueva Convocatoria" para agregar una nueva convocatoria al sistema.</p>
                        </div>
                    <?php endif; ?>
                    
                    <?php 
                    // Cargar la vista correspondiente según la acción
                    switch ($accion) {
                        case 'nueva':
                            include(__DIR__ . '/vistas/nueva_convocatoria.php');
                            break;
                        case 'editar':
                            include(__DIR__ . '/vistas/editar_convocatoria.php');
                            break;
                        case 'bases_anexos':
                            include(__DIR__ . '/vistas/bases_anexos.php');
                            break;
                        case 'resultados':
                            include(__DIR__ . '/vistas/resultados.php');
                            break;
                        default:
                            include(__DIR__ . '/vistas/listar_convocatorias.php');
                            break;
                    }
                    ?>
                </div>
            </div>
        </main>
        
        <!-- Pie de Página -->
        <footer class="bg-gray-800 text-white py-2">
            <div class="container mx-auto px-4 text-center text-sm">
                <p>Panel de Administración - Convocatorias CAS © <?php echo date('Y'); ?></p>
            </div>
        </footer>
    </div>
<?php endif; ?>

</body>
</html>
