<?php
/**
 * API: Guardar compra en la tabla `productos`
 * Recibe JSON con: usuario, correo, items (arreglo de productos del carrito)
 * Campos de la tabla: usuario, correo, producto, cantidad, monto_uni, monto_tot
 */

header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

// Preflight CORS
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Método no permitido.']);
    exit();
}

// Leer JSON del body
$body = file_get_contents('php://input');
$data = json_decode($body, true);

if (!$data) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Datos inválidos o vacíos.']);
    exit();
}

$usuario  = trim($data['usuario']  ?? '');
$correo   = trim($data['correo']   ?? '');
$items    = $data['items']         ?? [];

if (empty($items)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'El carrito está vacío.']);
    exit();
}

// Conexión a la base de datos
require_once __DIR__ . '/../php/conexion.php';

$insertados = 0;
$errores    = [];

foreach ($items as $item) {
    $producto   = trim($item['name']     ?? '');
    $cantidad   = intval($item['quantity'] ?? 1);
    $monto_uni  = floatval($item['price']   ?? 0);
    $monto_tot  = round($monto_uni * $cantidad, 2);

    if (empty($producto) || $cantidad <= 0 || $monto_uni <= 0) {
        $errores[] = "Producto inválido omitido: " . htmlspecialchars($producto);
        continue;
    }

    $stmt = mysqli_prepare(
        $conexion,
        "INSERT INTO productos (usuario, correo, producto, cantidad, monto_uni, monto_tot)
         VALUES (?, ?, ?, ?, ?, ?)"
    );

    if (!$stmt) {
        $errores[] = "Error al preparar consulta: " . mysqli_error($conexion);
        continue;
    }

    mysqli_stmt_bind_param($stmt, 'sssids', $usuario, $correo, $producto, $cantidad, $monto_uni, $monto_tot);

    if (mysqli_stmt_execute($stmt)) {
        $insertados++;
    } else {
        $errores[] = "Error al insertar " . htmlspecialchars($producto) . ": " . mysqli_stmt_error($stmt);
    }

    mysqli_stmt_close($stmt);
}

mysqli_close($conexion);

if ($insertados > 0) {
    echo json_encode([
        'success'    => true,
        'message'    => "Se guardaron $insertados producto(s) correctamente.",
        'insertados' => $insertados,
        'errores'    => $errores
    ]);
} else {
    http_response_code(500);
    echo json_encode([
        'success'  => false,
        'message'  => 'No se pudo guardar ningún producto.',
        'errores'  => $errores
    ]);
}
