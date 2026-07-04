<?php
/**
 * Punto de entrada para el registro de usuarios (Controlador de MVC)
 */

// Cargar el cargador de clases
require_once __DIR__ . '/../src/autoload.php';

use App\Database\Connection;
use App\DAO\MySQLUserDAO;
use App\Controller\AuthController;

// Capturar parámetros de la petición POST
$usuario  = $_POST['nombre'] ?? '';
$correo   = $_POST['correo'] ?? '';
$telefono = $_POST['telefono'] ?? '';
$pass     = $_POST['contra'] ?? '';

try {
    // 1. Obtener la conexión a la base de datos (PDO)
    $db = Connection::getInstance();

    // 2. Instanciar el DAO
    $userDAO = new MySQLUserDAO($db);

    // 3. Instanciar el Controlador inyectando el DAO (Dependency Inversion)
    $authController = new AuthController($userDAO);

    // 4. Ejecutar la lógica de negocio
    $resultado = $authController->register($usuario, $correo, $telefono, $pass);

    // En ambos casos (éxito o fallo) mostramos una alerta y redirigimos
    echo '
    <script>
    alert("' . addslashes($resultado['message']) . '");
    window.location.href = "../index.html";
    </script>
    ';
} catch (Exception $e) {
    // Captura cualquier error de base de datos o sistema de forma limpia
    echo '
    <script>
    alert("Error de sistema: ' . addslashes($e->getMessage()) . '");
    window.location.href = "../index.html";
    </script>
    ';
}