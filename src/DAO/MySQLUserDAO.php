<?php
namespace App\DAO;

use App\Model\User;
use PDO;

/**
 * Clase MySQLUserDAO
 * 
 * Implementa el patrón DAO.
 * Separa la lógica de almacenamiento/consulta en MySQL de la lógica de negocio.
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

        return new User(
            $row['usuario'],
            $row['contraseña'],
            $row['correo'],
            $row['telefono'],
            $row['rol'] ?? 'cliente',
            isset($row['id']) ? (int)$row['id'] : null
        );
    }

    /**
     * Busca un usuario por su nombre de usuario en la tabla usuarios.
     */
    public function findByUsername(string $username): ?User {
        $stmt = $this->db->prepare("SELECT * FROM usuarios WHERE usuario = :usuario");
        $stmt->execute(['usuario' => $username]);
        $row = $stmt->fetch();

        if (!$row) {
            return null;
        }

        return new User(
            $row['usuario'],
            $row['contraseña'],
            $row['correo'],
            $row['telefono'],
            $row['rol'] ?? 'cliente',
            isset($row['id']) ? (int)$row['id'] : null
        );
    }

    /**
     * Guarda un nuevo usuario en la tabla usuarios de MySQL.
     */
    public function save(User $user): bool {
        $stmt = $this->db->prepare(
            "INSERT INTO usuarios (usuario, contraseña, correo, telefono, rol) 
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
