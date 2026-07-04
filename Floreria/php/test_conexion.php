<?php
/**
 * Script de prueba de conexión a la base de datos local (MySQL / phpMyAdmin)
 */

require_once __DIR__ . '/../src/autoload.php';

use App\Database\Connection;

$mysqli_status = [
    'success' => false,
    'message' => ''
];

$pdo_status = [
    'success' => false,
    'message' => ''
];

$table_status = [
    'exists' => false,
    'message' => ''
];

// 1. Probar conexión MySQLi (método heredado)
try {
    $mysqli_conn = @mysqli_connect('localhost', 'root', '', 'web');
    if ($mysqli_conn) {
        $mysqli_status['success'] = true;
        $mysqli_status['message'] = 'Conexión exitosa a MySQL (localhost) con MySQLi usando el usuario "root" y base de datos "web".';
        
        // Verificar si existe la tabla usuarios usando mysqli
        $table_check = mysqli_query($mysqli_conn, "SHOW TABLES LIKE 'usuarios'");
        if (mysqli_num_rows($table_check) > 0) {
            $table_status['exists'] = true;
            $table_status['message'] = 'La tabla "usuarios" existe correctamente.';
        } else {
            $table_status['exists'] = false;
            $table_status['message'] = 'La base de datos "web" está conectada, pero la tabla "usuarios" no existe.';
        }
        mysqli_close($mysqli_conn);
    } else {
        $mysqli_status['success'] = false;
        $mysqli_status['message'] = 'Error de conexión: ' . mysqli_connect_error();
    }
} catch (\Throwable $e) {
    $mysqli_status['success'] = false;
    $mysqli_status['message'] = 'Excepción capturada: ' . $e->getMessage();
}

// 2. Probar conexión PDO (Singleton Connection class)
try {
    $pdo_conn = Connection::getInstance();
    $pdo_status['success'] = true;
    $pdo_status['message'] = 'Conexión exitosa a MySQL (localhost) con PDO (Clase Connection) usando el usuario "root" y base de datos "web".';
    
    // Verificar si la tabla existe usando PDO
    if (!$table_status['exists']) {
        $stmt = $pdo_conn->query("SHOW TABLES LIKE 'usuarios'");
        if ($stmt->fetch()) {
            $table_status['exists'] = true;
            $table_status['message'] = 'La tabla "usuarios" existe correctamente.';
        }
    }
} catch (\Throwable $e) {
    $pdo_status['success'] = false;
    $pdo_status['message'] = 'Error en Clase Connection (PDO): ' . $e->getMessage();
}

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prueba de Conexión Local | Atelier</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Playfair+Display:ital,wght@0,600;1,400&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #7A6256;
            --primary-light: #BA9281;
            --bg-color: #FBF9F8;
            --card-bg: #FFFFFF;
            --text-color: #2C302E;
            --success: #2E7D32;
            --success-bg: #E8F5E9;
            --error: #C62828;
            --error-bg: #FFEBEE;
            --warning: #EF6C00;
            --warning-bg: #FFF3E0;
            --border-radius: 12px;
            --transition: all 0.3s ease;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--bg-color);
            color: var(--text-color);
            margin: 0;
            padding: 40px 20px;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            box-sizing: border-box;
        }

        .container {
            max-width: 800px;
            width: 100%;
            background: var(--card-bg);
            border-radius: var(--border-radius);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
            padding: 40px;
            border: 1px solid #F0ECE9;
        }

        header {
            text-align: center;
            margin-bottom: 40px;
        }

        h1 {
            font-family: 'Playfair Display', serif;
            font-size: 2.2rem;
            color: var(--primary);
            margin: 0 0 10px 0;
        }

        .subtitle {
            font-size: 1rem;
            color: #888;
            margin: 0;
            letter-spacing: 1px;
            text-transform: uppercase;
        }

        .section-title {
            font-size: 1.2rem;
            font-weight: 600;
            margin: 25px 0 15px 0;
            color: var(--primary);
            border-bottom: 1px solid #EEE;
            padding-bottom: 8px;
        }

        .status-card {
            padding: 20px;
            border-radius: var(--border-radius);
            margin-bottom: 20px;
            display: flex;
            align-items: flex-start;
            gap: 15px;
            transition: var(--transition);
        }

        .status-card.success {
            background-color: var(--success-bg);
            border-left: 5px solid var(--success);
        }

        .status-card.error {
            background-color: var(--error-bg);
            border-left: 5px solid var(--error);
        }

        .status-card.warning {
            background-color: var(--warning-bg);
            border-left: 5px solid var(--warning);
        }

        .status-icon {
            font-size: 1.5rem;
            line-height: 1;
        }

        .status-content h3 {
            margin: 0 0 5px 0;
            font-size: 1.05rem;
            font-weight: 600;
        }

        .status-content h3.success-text { color: var(--success); }
        .status-content h3.error-text { color: var(--error); }
        .status-content h3.warning-text { color: var(--warning); }

        .status-content p {
            margin: 0;
            font-size: 0.95rem;
            line-height: 1.5;
            opacity: 0.9;
        }

        .sql-container {
            background-color: #2D3139;
            color: #ABB2BF;
            padding: 20px;
            border-radius: var(--border-radius);
            font-family: 'Courier New', Courier, monospace;
            font-size: 0.9rem;
            overflow-x: auto;
            position: relative;
            margin-top: 15px;
            border: 1px solid #1E222B;
        }

        .sql-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
            color: #FFF;
            font-size: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            opacity: 0.7;
        }

        .btn-copy {
            background: rgba(255, 255, 255, 0.1);
            border: none;
            color: #FFF;
            padding: 5px 10px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 0.75rem;
            font-family: inherit;
            transition: var(--transition);
        }

        .btn-copy:hover {
            background: rgba(255, 255, 255, 0.2);
        }

        .footer {
            text-align: center;
            margin-top: 40px;
            font-size: 0.85rem;
            color: #999;
        }

        .btn-action {
            display: inline-block;
            background-color: var(--primary);
            color: white;
            text-decoration: none;
            padding: 12px 25px;
            border-radius: 25px;
            font-weight: 500;
            font-size: 0.95rem;
            transition: var(--transition);
            margin-top: 20px;
            border: none;
            cursor: pointer;
        }

        .btn-action:hover {
            background-color: var(--primary-light);
            box-shadow: 0 4px 15px rgba(186, 146, 129, 0.3);
        }
    </style>
</head>
<body>

<div class="container">
    <header>
        <h1>Atelier Florería</h1>
        <p class="subtitle">Diagnóstico de Conexión a Base de Datos</p>
    </header>

    <!-- Estado de MySQLi (Conexión Heredada) -->
    <div class="section-title">1. Conexión de PHP Tradicional (MySQLi)</div>
    <?php if ($mysqli_status['success']): ?>
        <div class="status-card success">
            <span class="status-icon">✓</span>
            <div class="status-content">
                <h3 class="success-text">Conexión MySQLi Exitosa</h3>
                <p><?php echo htmlspecialchars($mysqli_status['message']); ?></p>
            </div>
        </div>
    <?php else: ?>
        <div class="status-card error">
            <span class="status-icon">✗</span>
            <div class="status-content">
                <h3 class="error-text">Fallo de Conexión MySQLi</h3>
                <p><?php echo htmlspecialchars($mysqli_status['message']); ?></p>
            </div>
        </div>
    <?php endif; ?>

    <!-- Estado de PDO (Nueva Arquitectura MVC/SOLID) -->
    <div class="section-title">2. Conexión de Nueva Arquitectura (PDO / Singleton)</div>
    <?php if ($pdo_status['success']): ?>
        <div class="status-card success">
            <span class="status-icon">✓</span>
            <div class="status-content">
                <h3 class="success-text">Conexión PDO Exitosa</h3>
                <p><?php echo htmlspecialchars($pdo_status['message']); ?></p>
            </div>
        </div>
    <?php else: ?>
        <div class="status-card error">
            <span class="status-icon">✗</span>
            <div class="status-content">
                <h3 class="error-text">Fallo de Conexión PDO</h3>
                <p><?php echo htmlspecialchars($pdo_status['message']); ?></p>
            </div>
        </div>
    <?php endif; ?>

    <!-- Estado de la Tabla Usuarios -->
    <div class="section-title">3. Estado de la Tabla "usuarios"</div>
    <?php if ($table_status['exists']): ?>
        <div class="status-card success">
            <span class="status-icon">✓</span>
            <div class="status-content">
                <h3 class="success-text">Tabla Detectada</h3>
                <p><?php echo htmlspecialchars($table_status['message']); ?></p>
            </div>
        </div>
    <?php else: ?>
        <div class="status-card warning">
            <span class="status-icon">⚠</span>
            <div class="status-content">
                <h3 class="warning-text">Tabla no encontrada</h3>
                <p><?php echo htmlspecialchars($table_status['message']); ?></p>
                <p style="margin-top: 10px;">Para crear la base de datos <strong>web</strong> y la tabla <strong>usuarios</strong>, ejecuta la siguiente consulta SQL en tu <strong>phpMyAdmin</strong>:</p>
                <div class="sql-container">
                    <div class="sql-header">
                        <span>Código SQL</span>
                        <button class="btn-copy" onclick="copySql()">Copiar</button>
                    </div>
                    <pre id="sql-code">CREATE DATABASE IF NOT EXISTS `web`;
USE `web`;

CREATE TABLE IF NOT EXISTS `usuarios` (
  `id_cliente` INT AUTO_INCREMENT PRIMARY KEY,
  `usuario` VARCHAR(50) NOT NULL,
  `contraseña` VARCHAR(50) NOT NULL,
  `correo` VARCHAR(50) NOT NULL UNIQUE,
  `telefono` VARCHAR(20) NOT NULL,
  `rol` VARCHAR(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;</pre>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <div style="text-align: center;">
        <button class="btn-action" onclick="window.location.reload();">Volver a diagnosticar</button>
    </div>

    <div class="footer">
        Atelier Florería &copy; 2026 | Desarrollado con MVC & SOLID
    </div>
</div>

<script>
function copySql() {
    var copyText = document.getElementById("sql-code").innerText;
    navigator.clipboard.writeText(copyText).then(function() {
        alert("¡SQL Copiado al portapapeles!");
    }, function(err) {
        alert("No se pudo copiar: " + err);
    });
}
</script>
</body>
</html>
