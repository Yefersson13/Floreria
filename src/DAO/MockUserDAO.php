<?php
namespace App\DAO;

use App\Model\User;

/**
 * Clase MockUserDAO
 * 
 * Cumple con SOLID:
 * - Liskov Substitution Principle (LSP): Puede reemplazar a MySQLUserDAO sin romper el comportamiento esperado de la aplicación.
 * 
 * Permite TDD:
 * - Facilita escribir pruebas rápidas y deterministas para los controladores sin necesidad de conectarse a MySQL.
 */
class MockUserDAO implements UserDAOInterface {
    /**
     * @var User[]
     */
    private array $users = [];

    public function findByEmail(string $email): ?User {
        foreach ($this->users as $user) {
            if ($user->getCorreo() === $email) {
                return $user;
            }
        }
        return null;
    }

    public function findByUsername(string $username): ?User {
        foreach ($this->users as $user) {
            if ($user->getUsuario() === $username) {
                return $user;
            }
        }
        return null;
    }

    public function save(User $user): bool {
        $this->users[] = $user;
        return true;
    }

    /**
     * Método auxiliar exclusivo para testing: limpia el estado en memoria.
     */
    public function clear(): void {
        $this->users = [];
    }
}
