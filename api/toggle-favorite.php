<?php
/**
 * @file toggle-favorite.php
 * @description API Endpoint para registrar o remover favoritos en el servidor (opcional DB).
 * Responsabilidad: Usar prepared statements de PDO y fallback en sesión PHP.
 */

header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET");
header("Access-Control-Allow-Headers: Content-Type");

$productId = isset($_REQUEST['product_id']) ? (int)$_REQUEST['product_id'] : null;

if (!$productId) {
    http_response_code(400);
    echo json_encode(["error" => "ID de producto faltante o no válido."]);
    exit;
}

$host = 'localhost';
$dbname = 'web';
$username = 'root';
$password = '';
$savedInDb = false;
$status = "unknown";

try {
    // Intento de conexión PDO con prepared statements
    $dsn = "mysql:host=$host;dbname=$dbname;charset=utf8mb4";
    $db = new PDO($dsn, $username, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);

    // Asumimos un ID de usuario fijo para la simulación
    $userId = 1;

    // Verificar si ya existe en favoritos
    $stmt = $db->prepare("SELECT id FROM favorites WHERE user_id = :user_id AND product_id = :product_id");
    $stmt->execute(['user_id' => $userId, 'product_id' => $productId]);
    
    if ($stmt->fetch()) {
        // Remover de favoritos
        $deleteStmt = $db->prepare("DELETE FROM favorites WHERE user_id = :user_id AND product_id = :product_id");
        $deleteStmt->execute(['user_id' => $userId, 'product_id' => $productId]);
        $status = "removed";
    } else {
        // Insertar en favoritos
        $insertStmt = $db->prepare("INSERT INTO favorites (user_id, product_id) VALUES (:user_id, :product_id)");
        $insertStmt->execute(['user_id' => $userId, 'product_id' => $productId]);
        $status = "added";
    }
    $savedInDb = true;

} catch (PDOException $e) {
    // Fallback: Si no hay base de datos, persistimos temporalmente en la sesión PHP
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    
    if (!isset($_SESSION['favorites'])) {
        $_SESSION['favorites'] = [];
    }

    if (in_array($productId, $_SESSION['favorites'])) {
        $_SESSION['favorites'] = array_diff($_SESSION['favorites'], [$productId]);
        $status = "removed";
    } else {
        $_SESSION['favorites'][] = $productId;
        $status = "added";
    }
}

// Retorna la respuesta en formato estructurado JSON
echo json_encode([
    "success" => true,
    "product_id" => $productId,
    "status" => $status,
    "saved_in_db" => $savedInDb
]);
?>
