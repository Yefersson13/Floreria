<?php
namespace App\Controller;

use App\DAO\UserDAOInterface;
use App\Model\User;

/**
 * Clase AuthController (Controlador)
 * 
 * Cumple con MVC: Representa la lógica del controlador que decide el flujo basándose en la petición y los modelos.
 * Cumple con SOLID:
 * - Single Responsibility Principle (SRP): Su única responsabilidad es procesar las peticiones de autenticación.
 * - Dependency Inversion Principle (DIP): Depende de la abstracción `UserDAOInterface` en lugar de una clase de persistencia MySQL concreta.
 */
class AuthController {
    private UserDAOInterface $userDAO;

    /**
     * Inyectamos el DAO a través del constructor (Inyección de Dependencias).
     */
    public function __construct(UserDAOInterface $userDAO) {
        $this->userDAO = $userDAO;
    }

    /**
     * Procesa el inicio de sesión.
     * 
     * @param string $correo
     * @param string $contra
     * @return array Resultado con 'success' y mensaje/datos.
     */
    public function login(string $correo, string $contra): array {
        // Validación básica
        if (empty($correo) || empty($contra)) {
            return [
                'success' => false,
                'message' => 'El correo y la contraseña son requeridos.'
            ];
        }

        // Consultamos al DAO (Abstracción)
        $user = $this->userDAO->findByEmail($correo);

        // Validamos la contraseña (en producción usar hash con password_verify)
        if ($user && $user->getContraseña() === $contra) {
            return [
                'success' => true,
                'username' => $user->getUsuario()
            ];
        }

        return [
            'success' => false,
            'message' => 'El correo o la contraseña son inválidos.'
        ];
    }

    /**
     * Procesa el registro de un nuevo usuario.
     * 
     * @param string $usuario
     * @param string $correo
     * @param string $telefono
     * @param string $contra
     * @return array Resultado con 'success' y mensaje.
     */
    public function register(string $usuario, string $correo, string $telefono, string $contra): array {
        // Validación básica
        if (empty($usuario) || empty($correo) || empty($telefono) || empty($contra)) {
            return [
                'success' => false,
                'message' => 'Todos los campos son obligatorios.'
            ];
        }

        // Validar si el nombre de usuario ya existe
        if ($this->userDAO->findByUsername($usuario)) {
            return [
                'success' => false,
                'message' => 'El nombre de Usuario ya existe.'
            ];
        }

        // Validar si el correo ya existe
        if ($this->userDAO->findByEmail($correo)) {
            return [
                'success' => false,
                'message' => 'El correo electrónico ya está registrado.'
            ];
        }

        // Crear una instancia de la entidad modelo User
        $user = new User($usuario, $contra, $correo, $telefono, 'cliente');

        // Guardar el usuario mediante el DAO
        $success = $this->userDAO->save($user);

        if ($success) {
            return [
                'success' => true,
                'message' => 'Registro Exitoso.'
            ];
        }

        return [
            'success' => false,
            'message' => 'Error al procesar el registro.'
        ];
    }
}
