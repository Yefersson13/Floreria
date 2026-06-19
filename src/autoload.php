<?php
/**
 * Cargador automático simple para clases del espacio de nombres App\
 */
spl_autoload_register(function ($class) {
    // Prefijo del espacio de nombres del proyecto
    $prefix = 'App\\';

    // Directorio base para el espacio de nombres
    $base_dir = __DIR__ . '/';

    // ¿La clase utiliza este prefijo de espacio de nombres?
    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        // No, pasar al siguiente cargador registrado
        return;
    }

    // Obtener el nombre relativo de la clase
    $relative_class = substr($class, $len);

    // Reemplazar los separadores de espacio de nombres por separadores de directorio
    // y añadir la extensión .php
    $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';

    // Si el archivo existe, requerirlo
    if (file_exists($file)) {
        require $file;
    }
});
