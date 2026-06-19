<?php
/**
 * Script de Pruebas Unitarias - Demostración TDD
 * 
 * Este script ejecuta pruebas unitarias rápidas utilizando el cargador automático
 * y una implementación simulada (Mock) de la base de datos.
 */

// Habilitar la visualización de errores
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Cargar cargador automático
require_once __DIR__ . '/../src/autoload.php';

use App\Model\User;
use App\DAO\MockUserDAO;
use App\Controller\AuthController;

$successes = 0;
$failures = 0;

/**
 * Función de afirmación personalizada.
 */
function assertEquals($expected, $actual, $message = '') {
    global $successes, $failures;
    if ($expected === $actual) {
        $successes++;
        echo "\033[32m[PASÓ]\033[0m $message\n";
    } else {
        $failures++;
        echo "\033[31m[FALLÓ]\033[0m $message (Esperado: " . var_export($expected, true) . ", Obtenido: " . var_export($actual, true) . ")\n";
    }
}

function assertTrue($condition, $message = '') {
    assertEquals(true, $condition, $message);
}

function assertFalse($condition, $message = '') {
    assertEquals(false, $condition, $message);
}

// ----------------------------------------------------
// CASOS DE PRUEBA
// ----------------------------------------------------

echo "=== INICIANDO PRUEBAS UNITARIAS (TDD) ===\n";

// 1. Probar el modelo User
echo "\n--- Probando Modelo User ---\n";
$user = new User('Carlos', 'Pass456', 'carlos@test.com', '987654321', 'cliente');
assertEquals('Carlos', $user->getUsuario(), 'El nombre del usuario debe ser Carlos.');
assertEquals('carlos@test.com', $user->getCorreo(), 'El correo electrónico debe ser carlos@test.com.');
assertEquals('987654321', $user->getTelefono(), 'El teléfono debe ser 987654321.');
assertEquals('cliente', $user->getRol(), 'El rol por defecto debe ser cliente.');

// 2. Probar Inicio de Sesión Exitoso
echo "\n--- Probando AuthController: Login Exitoso ---\n";
$mockDAO = new MockUserDAO();
$mockDAO->save(new User('Ana', '123456', 'ana@test.com', '999888777'));

$controller = new AuthController($mockDAO);
$response = $controller->login('ana@test.com', '123456');

assertTrue($response['success'], 'El login debe ser exitoso con credenciales correctas.');
assertEquals('Ana', $response['username'], 'La respuesta debe incluir el nombre de usuario "Ana".');

// 3. Probar Inicio de Sesión Fallido (Contraseña Incorrecta)
echo "\n--- Probando AuthController: Login Fallido ---\n";
$response = $controller->login('ana@test.com', 'wrongpassword');
assertFalse($response['success'], 'El login debe fallar si la contraseña es incorrecta.');
assertEquals('El correo o la contraseña son inválidos.', $response['message'], 'El mensaje de error debe indicar credenciales inválidas.');

// 4. Probar Registro Exitoso
echo "\n--- Probando AuthController: Registro Exitoso ---\n";
$mockDAO->clear(); // Limpiar base de datos mock
$controller = new AuthController($mockDAO);
$response = $controller->register('Pedro', 'pedro@test.com', '911222333', 'ContrasenaSegura');

assertTrue($response['success'], 'El registro debe ser exitoso con datos correctos.');
assertEquals('Registro Exitoso.', $response['message'], 'Debe mostrar el mensaje de éxito correcto.');

// Verificar que se guardó en el mock DAO
$savedUser = $mockDAO->findByUsername('Pedro');
assertTrue($savedUser !== null, 'El usuario Pedro debe estar guardado en el almacén de datos (Mock).');
assertEquals('pedro@test.com', $savedUser->getCorreo(), 'El correo guardado debe coincidir.');

// 5. Probar Registro Fallido por Nombre de Usuario Duplicado
echo "\n--- Probando AuthController: Registro Duplicado ---\n";
$response = $controller->register('Pedro', 'otro@test.com', '988888888', 'OtraContra');
assertFalse($response['success'], 'El registro debe fallar si el nombre de usuario ya existe.');
assertEquals('El nombre de Usuario ya existe.', $response['message'], 'El mensaje de error de nombre de usuario duplicado es correcto.');

// 6. Probar Registro Fallido por Correo Duplicado
echo "\n--- Probando AuthController: Registro con Correo Duplicado ---\n";
$response = $controller->register('Jose', 'pedro@test.com', '988888888', 'OtraContra');
assertFalse($response['success'], 'El registro debe fallar si el correo electrónico ya está registrado.');
assertEquals('El correo electrónico ya está registrado.', $response['message'], 'El mensaje de error de correo duplicado es correcto.');

echo "\n=========================================\n";
echo "RESULTADOS: $successes pruebas exitosas, $failures fallidas.\n";

if ($failures > 0) {
    exit(1); // Error
} else {
    exit(0); // Éxito
}
