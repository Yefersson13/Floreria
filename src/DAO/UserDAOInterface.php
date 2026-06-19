<?php
namespace App\DAO;

use App\Model\User;

/**
 * Interfaz UserDAOInterface
 * 
 * Cumple con SOLID:
 * - Interface Segregation Principle (ISP): Define un contrato exclusivo para el acceso a datos de usuarios.
 * - Dependency Inversion Principle (DIP): Los controladores dependerán de esta interfaz abstracción, no de una base de datos específica.
 */
interface UserDAOInterface {
    /**
     * Busca un usuario por su correo electrónico.
     */
    public function findByEmail(string $email): ?User;

    /**
     * Busca un usuario por su nombre de usuario.
     */
    public function findByUsername(string $username): ?User;

    /**
     * Guarda un nuevo usuario en el almacén de datos.
     */
    public function save(User $user): bool;
}
