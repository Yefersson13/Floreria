<?php
namespace App\Database;

use PDO;
use PDOException;

/**
 * Clase Connection (Patrón Singleton)
 * 
 * Cumple con SOLID: Single Responsibility Principle (SRP). 
 * Se encarga únicamente de configurar e instanciar la conexión a la base de datos local (MySQL o SQLite local).
 */
class Connection {
    private static ?PDO $instance = null;

    // El constructor es privado para evitar la instanciación externa
    private function __construct() {}

    /**
     * Obtiene la única instancia de la conexión PDO.
     * Si MySQL no está configurado, o la BD 'web' no existe, la crea automáticamente.
     * Si el servidor MySQL no responde, utiliza una base de datos local SQLite (floreria.sqlite).
     * 
     * @return PDO
     * @throws PDOException
     */
    public static function getInstance(): PDO {
        if (self::$instance === null) {
            $host = 'localhost';
            $db   = 'web';
            $user = 'root';
            $pass = '';
            $charset = 'utf8mb4';

            $options = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // Lanzar excepciones en errores
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,       // Resultados como arreglos asociativos
                PDO::ATTR_EMULATE_PREPARES   => false,                  // Usar prepared statements reales para seguridad
            ];

            try {
                // 1. Intentar conectar a MySQL con el nombre de BD
                $dsn = "mysql:host=$host;dbname=$db;charset=$charset";
                self::$instance = new PDO($dsn, $user, $pass, $options);
                self::ensureMySQLSchema(self::$instance);
            } catch (PDOException $e) {
                // Si el error es 1049 (base de datos no existe), intentamos crear la base de datos
                if ($e->getCode() == 1049 || strpos($e->getMessage(), '1049') !== false || strpos($e->getMessage(), 'Unknown database') !== false) {
                    try {
                        $dsnNoDb = "mysql:host=$host;charset=$charset";
                        $pdoNoDb = new PDO($dsnNoDb, $user, $pass, $options);
                        $pdoNoDb->exec("CREATE DATABASE IF NOT EXISTS `$db` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;");
                        self::$instance = new PDO($dsn, $user, $pass, $options);
                        self::ensureMySQLSchema(self::$instance);
                    } catch (PDOException $ex) {
                        self::fallbackToSQLite($options);
                    }
                } else {
                    // Si no hay conexión al servidor MySQL local, usamos la BD local SQLite de respaldo
                    self::fallbackToSQLite($options);
                }
            }
        }
        return self::$instance;
    }

    /**
     * Asegura la estructura y esquema correctos en MySQL.
     */
    private static function ensureMySQLSchema(PDO $pdo): void {
        try {
            $pdo->exec("CREATE TABLE IF NOT EXISTS usuarios (
                id_cliente INT AUTO_INCREMENT PRIMARY KEY,
                usuario VARCHAR(100) NOT NULL,
                contrasenia VARCHAR(255) NOT NULL,
                correo VARCHAR(100) NOT NULL UNIQUE,
                telefono VARCHAR(30) NOT NULL,
                rol VARCHAR(50) DEFAULT 'cliente'
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");

            // Asegurar compatibilidad de columna contrasenia vs contraseña si la tabla existía previamente
            $stmt = $pdo->query("SHOW COLUMNS FROM usuarios LIKE 'contraseña'");
            if ($stmt && !$stmt->fetch()) {
                // Verificar si existe contrasenia
                $stmt2 = $pdo->query("SHOW COLUMNS FROM usuarios LIKE 'contrasenia'");
                if ($stmt2 && !$stmt2->fetch()) {
                    $pdo->exec("ALTER TABLE usuarios ADD COLUMN contrasenia VARCHAR(255) NOT NULL DEFAULT '' AFTER usuario;");
                }
            }
            
            // Intentar asegurar que id_cliente sea AUTO_INCREMENT si no lo era
            try {
                $stmtPk = $pdo->query("SHOW COLUMNS FROM usuarios WHERE Field = 'id_cliente' AND Extra LIKE '%auto_increment%'");
                if ($stmtPk && !$stmtPk->fetch()) {
                    $pdo->exec("ALTER TABLE usuarios MODIFY id_cliente INT AUTO_INCREMENT PRIMARY KEY;");
                }
            } catch (\Exception $eIgnored) {}

        } catch (\Exception $e) {
            // Log silente o manejo controlado
        }
    }

    /**
     * Conecta a una base de datos SQLite autocontenida y crea la tabla si es necesario.
     */
    private static function fallbackToSQLite(array $options): void {
        try {
            $sqlitePath = __DIR__ . '/floreria.sqlite';
            $dsn = "sqlite:" . $sqlitePath;
            self::$instance = new PDO($dsn, null, null, [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
            ]);

            self::$instance->exec("CREATE TABLE IF NOT EXISTS usuarios (
                id_cliente INTEGER PRIMARY KEY AUTOINCREMENT,
                usuario VARCHAR(100) NOT NULL,
                contrasenia VARCHAR(255) NOT NULL,
                correo VARCHAR(100) NOT NULL UNIQUE,
                telefono VARCHAR(30) NOT NULL,
                rol VARCHAR(50) DEFAULT 'cliente'
            );");
        } catch (PDOException $e) {
            throw new PDOException("Error fatal al inicializar la base de datos local: " . $e->getMessage(), (int)$e->getCode());
        }
    }

    // Evitar la clonación del objeto
    private function __clone() {}
}
