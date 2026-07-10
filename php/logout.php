<?php
/**
 * Controlador de Cierre de Sesión (PHP + LocalStorage Sync)
 */
session_start();
$_SESSION = [];
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}
session_destroy();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Cerrando sesión...</title>
</head>
<body style="background: #FFF; font-family: sans-serif;">
    <script>
        try {
            localStorage.removeItem('atelier_current_user');
            localStorage.removeItem('atelier_user_logged');
            sessionStorage.clear();
        } catch(e) {}
        window.location.replace('../index.html');
    </script>
</body>
</html>
