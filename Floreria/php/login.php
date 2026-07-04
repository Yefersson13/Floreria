<?php
/**
 * Punto de entrada para el inicio de sesión (Controlador de MVC)
 */
session_start();

// Cargar el cargador de clases
require_once __DIR__ . '/../src/autoload.php';

use App\Database\Connection;
use App\DAO\MySQLUserDAO;
use App\Controller\AuthController;

// Capturar parámetros de la petición POST
$correo = $_POST['correo'] ?? '';
$contra = $_POST['contra'] ?? '';

try {
    // 1. Obtener la conexión a la base de datos (PDO)
    $db = Connection::getInstance();

    // 2. Instanciar el DAO
    $userDAO = new MySQLUserDAO($db);

    // 3. Instanciar el Controlador inyectando el DAO (Dependency Inversion)
    $authController = new AuthController($userDAO);

    // 4. Ejecutar la lógica de negocio
    $resultado = $authController->login($correo, $contra);

    if ($resultado['success']) {
        // Guardar sesión y redirigir a la página principal de clientes
        $_SESSION['cliente'] = $resultado['username'];
        header("Location: ../pagina.php");
        exit();
    } else {
        // Mostrar error en alerta Javascript
        echo '
        <script>
        alert("' . addslashes($resultado['message']) . '");
        window.location = "../index.html";
        </script>
        ';
    }
} catch (Exception $e) {
    // Captura cualquier error de base de datos o sistema de forma limpia
    echo '
    <script>
    alert("Error de sistema: ' . addslashes($e->getMessage()) . '");
    window.location = "../index.html";
    </script>
    ';
}