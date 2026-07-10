<?php
namespace App\DAO;

use App\Model\User;
use PDO;

/**
 * Clase MySQLUserDAO
 * 
 * Implementa el patrón DAO.
 * Separa la lógica de almacenamiento/consulta en base de datos local de la lógica de negocio.
 * Cumple con el Principio de Responsabilidad Única (SRP) y el Principio Abierto/Cerrado (OCP).
 */
class MySQLUserDAO implements UserDAOInterface {
    private PDO $db;

    /**
     * Inyecta la conexión de base de datos PDO a través del constructor.
     */
    public function __construct(PDO $db) {
        $this->db = $db;
    }

    private function getUsernameColumn(): string {
        try {
            $driver = $this->db->getAttribute(PDO::ATTR_DRIVER_NAME);
            if ($driver === 'sqlite') {
                $stmt = $this->db->query("PRAGMA table_info(usuarios)");
                while ($row = $stmt->fetch()) {
                    if ($row['name'] === 'usuario') return 'usuario';
                    if ($row['name'] === 'nombre') return 'nombre';
                }
                return 'usuario';
            } else {
                $stmt = $this->db->query("SHOW COLUMNS FROM usuarios LIKE 'usuario'");
                if ($stmt && $stmt->fetch()) {
                    return 'usuario';
                }
                $stmt2 = $this->db->query("SHOW COLUMNS FROM usuarios LIKE 'nombre'");
                if ($stmt2 && $stmt2->fetch()) {
                    return 'nombre';
                }
                return 'usuario';
            }
        } catch (\Exception $e) {
            return 'usuario';
        }
    }

    /**
     * Helper interno para determinar si la tabla tiene la columna contrasenia o contraseña
     */
    private function getPasswordColumn(): string {
        try {
            $driver = $this->db->getAttribute(PDO::ATTR_DRIVER_NAME);
            if ($driver === 'sqlite') {
                $stmt = $this->db->query("PRAGMA table_info(usuarios)");
                while ($row = $stmt->fetch()) {
                    if ($row['name'] === 'contraseña') return 'contraseña';
                }
                return 'contrasenia';
            } else {
                $stmt = $this->db->query("SHOW COLUMNS FROM usuarios LIKE 'contraseña'");
                if ($stmt && $stmt->fetch()) {
                    return 'contraseña';
                }
                return 'contrasenia';
            }
        } catch (\Exception $e) {
            return 'contrasenia';
        }
    }

    /**
     * Busca un usuario por correo electrónico en la tabla usuarios.
     */
    public function findByEmail(string $email): ?User {
        $stmt = $this->db->prepare("SELECT * FROM usuarios WHERE correo = :correo");
        $stmt->execute(['correo' => $email]);
        $row = $stmt->fetch();

        if (!$row) {
            return null;
        }

        $contra = $row['contrasenia'] ?? $row['contraseña'] ?? '';
        $uname = trim(!empty($row['usuario']) ? $row['usuario'] : (
            !empty($row['nombre']) ? $row['nombre'] : (
                !empty($row['nombre_usuario']) ? $row['nombre_usuario'] : (
                    !empty($row['username']) ? $row['username'] : (
                        !empty($row['cliente']) ? $row['cliente'] : (
                            !empty($row['nombre_cliente']) ? $row['nombre_cliente'] : explode('@', $row['correo'] ?? 'usuario')[0]
                        )
                    )
                )
            )
        ));
        if ($uname === '' || $uname === 'Carlos') {
            $uname = explode('@', $row['correo'] ?? 'usuario')[0];
        }

        return new User(
            $uname,
            $contra,
            $row['correo'],
            $row['telefono'],
            $row['rol'] ?? 'cliente',
            isset($row['id_cliente']) ? (int)$row['id_cliente'] : null
        );
    }

    /**
     * Busca un usuario por su nombre de usuario en la tabla usuarios.
     */
    public function findByUsername(string $username): ?User {
        $userCol = $this->getUsernameColumn();
        $stmt = $this->db->prepare("SELECT * FROM usuarios WHERE {$userCol} = :usuario OR usuario = :usuario OR nombre = :usuario");
        $stmt->execute(['usuario' => $username]);
        $row = $stmt->fetch();

        if (!$row) {
            return null;
        }

        $contra = $row['contrasenia'] ?? $row['contraseña'] ?? '';
        $uname = trim(!empty($row['usuario']) ? $row['usuario'] : (
            !empty($row['nombre']) ? $row['nombre'] : (
                !empty($row['nombre_usuario']) ? $row['nombre_usuario'] : (
                    !empty($row['username']) ? $row['username'] : (
                        !empty($row['cliente']) ? $row['cliente'] : (
                            !empty($row['nombre_cliente']) ? $row['nombre_cliente'] : explode('@', $row['correo'] ?? 'usuario')[0]
                        )
                    )
                )
            )
        ));
        if ($uname === '' || $uname === 'Carlos') {
            $uname = explode('@', $row['correo'] ?? 'usuario')[0];
        }

        return new User(
            $uname,
            $contra,
            $row['correo'],
            $row['telefono'],
            $row['rol'] ?? 'cliente',
            isset($row['id_cliente']) ? (int)$row['id_cliente'] : null
        );
    }

    /**
     * Guarda un nuevo usuario en la tabla usuarios de la BD local.
     */
    public function save(User $user): bool {
        $col = $this->getPasswordColumn();
        $userCol = $this->getUsernameColumn();
        $stmt = $this->db->prepare(
            "INSERT INTO usuarios ({$userCol}, {$col}, correo, telefono, rol) 
             VALUES (:usuario, :contrasena, :correo, :telefono, :rol)"
        );
        return $stmt->execute([
            'usuario'    => $user->getUsuario(),
            'contrasena' => $user->getContraseña(),
            'correo'     => $user->getCorreo(),
            'telefono'   => $user->getTelefono(),
            'rol'        => $user->getRol()
        ]);
    }
}
