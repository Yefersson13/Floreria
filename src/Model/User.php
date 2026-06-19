<?php
namespace App\Model;

/**
 * Clase User (Modelo)
 * 
 * Cumple con MVC: Representa la entidad de datos (Model).
 * Cumple con SOLID: Single Responsibility Principle (SRP).
 * Solo se encarga de contener los datos y estructura de un usuario.
 */
class User {
    private ?int $id;
    private string $usuario;
    private string $contraseña;
    private string $correo;
    private string $telefono;
    private string $rol;

    public function __construct(
        string $usuario,
        string $contraseña,
        string $correo,
        string $telefono,
        string $rol = 'cliente',
        ?int $id = null
    ) {
        $this->id = $id;
        $this->usuario = $usuario;
        $this->contraseña = $contraseña;
        $this->correo = $correo;
        $this->telefono = $telefono;
        $this->rol = $rol;
    }

    // --- GETTERS & SETTERS ---

    public function getId(): ?int {
        return $this->id;
    }

    public function getUsuario(): string {
        return $this->usuario;
    }

    public function setUsuario(string $usuario): void {
        $this->usuario = $usuario;
    }

    public function getContraseña(): string {
        return $this->contraseña;
    }

    public function setContraseña(string $contraseña): void {
        $this->contraseña = $contraseña;
    }

    public function getCorreo(): string {
        return $this->correo;
    }

    public function setCorreo(string $correo): void {
        $this->correo = $correo;
    }

    public function getTelefono(): string {
        return $this->telefono;
    }

    public function setTelefono(string $telefono): void {
        $this->telefono = $telefono;
    }

    public function getRol(): string {
        return $this->rol;
    }

    public function setRol(string $rol): void {
        $this->rol = $rol;
    }
}
