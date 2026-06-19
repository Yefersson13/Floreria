<?php
namespace App\Database;

use PDO;
use PDOException;

/**
 * Clase Connection (Patrón Singleton)
 * 
 * Cumple con SOLID: Single Responsibility Principle (SRP). 
 * Se encarga únicamente de configurar e instanciar la conexión a la base de datos MySQL.
 */
class Connection {
    private static ?PDO $instance = null;

    // El constructor es privado para evitar la instanciación externa
    private function __construct() {}

    /**
     * Obtiene la única instancia de la conexión PDO.
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

            // DSN para conectar usando PDO
            $dsn = "mysql:host=$host;dbname=$db;charset=$charset";
            $options = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // Lanzar excepciones en errores
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,       // Resultados como arreglos asociativos
                PDO::ATTR_EMULATE_PREPARES   => false,                  // Usar prepared statements reales para seguridad
            ];

            try {
                self::$instance = new PDO($dsn, $user, $pass, $options);
            } catch (PDOException $e) {
                throw new PDOException("Error de conexión a la base de datos: " . $e->getMessage(), (int)$e->getCode());
            }
        }
        return self::$instance;
    }

    // Evitar la clonación del objeto
    private function __clone() {}
}
