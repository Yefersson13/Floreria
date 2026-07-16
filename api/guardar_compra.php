<?php
// Suprimir warnings/notices para evitar que rompan el JSON
error_reporting(0);
ini_set('display_errors', '0');

header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(array('success' => false, 'message' => 'Metodo no permitido.'));
    exit();
}

$body = file_get_contents('php://input');
$data = json_decode($body, true);

if (empty($data)) {
    http_response_code(400);
    echo json_encode(array('success' => false, 'message' => 'Datos invalidos o vacios.'));
    exit();
}

$usuario = isset($data['usuario']) ? trim($data['usuario']) : '';
$correo  = isset($data['correo'])  ? trim($data['correo'])  : '';
$items   = isset($data['items'])   ? $data['items']         : array();

if (empty($items)) {
    http_response_code(400);
    echo json_encode(array('success' => false, 'message' => 'El carrito esta vacio.'));
    exit();
}

// Conexion a InfinityFree
$conexion = mysqli_connect(
    'sql100.infinityfree.com',
    'if0_41664272',
    'pruebasutp123',
    'if0_41664272_productos',
    3306
);

if (!$conexion) {
    http_response_code(503);
    echo json_encode(array(
        'success' => false,
        'message' => 'Error DB: ' . mysqli_connect_error()
    ));
    exit();
}

mysqli_set_charset($conexion, 'utf8mb4');

$insertados = 0;
$errores    = array();

foreach ($items as $item) {
    $producto  = isset($item['name'])     ? trim((string)$item['name'])       : '';
    $cantidad  = isset($item['quantity']) ? (int)$item['quantity']             : 1;
    $monto_uni = isset($item['price'])    ? (float)$item['price']              : 0.0;
    $monto_tot = round($monto_uni * $cantidad, 2);

    if ($producto === '' || $cantidad <= 0 || $monto_uni <= 0) {
        $errores[] = 'Item invalido: ' . $producto;
        continue;
    }

    $stmt = mysqli_prepare($conexion,
        'INSERT INTO productos (usuario, correo, producto, cantidad, monto_uni, monto_tot) VALUES (?, ?, ?, ?, ?, ?)'
    );

    if (!$stmt) {
        $errores[] = 'Prepare error: ' . mysqli_error($conexion);
        continue;
    }

    mysqli_stmt_bind_param($stmt, 'sssids', $usuario, $correo, $producto, $cantidad, $monto_uni, $monto_tot);

    if (mysqli_stmt_execute($stmt)) {
        $insertados++;
    } else {
        $errores[] = 'Execute error: ' . mysqli_stmt_error($stmt);
    }

    mysqli_stmt_close($stmt);
}

mysqli_close($conexion);

if ($insertados > 0) {
    echo json_encode(array(
        'success'    => true,
        'message'    => 'Se guardaron ' . $insertados . ' producto(s).',
        'insertados' => $insertados,
        'errores'    => $errores
    ));
} else {
    http_response_code(500);
    echo json_encode(array(
        'success' => false,
        'message' => 'No se guardo ningun producto.',
        'errores' => $errores
    ));
}
