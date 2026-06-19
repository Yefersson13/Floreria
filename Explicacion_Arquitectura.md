# Guía de Arquitectura de Software: DAO, MVC, TDD y SOLID

Este documento explica de forma clara y detallada la arquitectura implementada en este proyecto. El objetivo es proporcionar un recurso de estudio que te permita entender cómo se estructuran las aplicaciones profesionales utilizando buenas prácticas de diseño de software en PHP.

---

## 1. Patrón MVC (Modelo-Vista-Controlador)

El patrón **MVC** separa la aplicación en tres componentes principales:

1. **Modelo (Model):** Representa los datos y las reglas de negocio de la aplicación.
   - **En el proyecto:** La clase [User.php](file:///c:/xampp/htdocs/vean/vean/PaginaWeb/src/Model/User.php) representa la entidad usuario (datos) y las clases DAO gestionan su persistencia.
2. **Vista (View):** Es la interfaz de usuario con la que interactúa el cliente.
   - **En el proyecto:** Los archivos HTML/PHP principales [index.html](file:///c:/xampp/htdocs/vean/vean/PaginaWeb/index.html) y [pagina.php](file:///c:/xampp/htdocs/vean/vean/PaginaWeb/pagina.php) actúan como la interfaz de usuario.
3. **Controlador (Controller):** Intermedia entre la Vista y el Modelo. Recibe las peticiones del usuario, invoca las reglas en el Modelo y decide qué responder.
   - **En el proyecto:** La clase [AuthController.php](file:///c:/xampp/htdocs/vean/vean/PaginaWeb/src/Controller/AuthController.php) procesa los datos de login y registro. Los archivos de entrada [login.php](file:///c:/xampp/htdocs/vean/vean/PaginaWeb/php/login.php) y [registro.php](file:///c:/xampp/htdocs/vean/vean/PaginaWeb/php/registro.php) actúan como despachadores para el controlador.

### Flujo de Trabajo en MVC:
```
[Vista (Formulario)] ---> Petición HTTP ---> [Despachador/Controlador (AuthController)]
                                                         |
                                                         v
                                                 [Modelo (User / DAO)]
                                                         |
[Vista (Redirección / Alerta)] <--- Respuesta <----------+
```

---

## 2. Patrón DAO (Data Access Object)

El patrón **DAO** se utiliza para separar la lógica de negocio (cómo funciona tu aplicación) de la lógica de acceso a datos (dónde y cómo se guardan las cosas).

### ¿Por qué lo usamos?
Antes, las consultas SQL de base de datos (`SELECT`, `INSERT`) estaban mezcladas directamente dentro de los archivos de lógica como `login.php` y `registro.php`. Si decidieras cambiar de MySQL a SQLite o PostgreSQL, tendrías que reescribir todo el código.

Con DAO, la lógica de negocio no sabe nada de SQL; solo llama a funciones de una interfaz.

### Componentes en este proyecto:
- **[UserDAOInterface.php](file:///c:/xampp/htdocs/vean/vean/PaginaWeb/src/DAO/UserDAOInterface.php):** Define el contrato (las funciones obligatorias).
  ```php
  interface UserDAOInterface {
      public function findByEmail(string $email): ?User;
      public function findByUsername(string $username): ?User;
      public function save(User $user): bool;
  }
  ```
- **[MySQLUserDAO.php](file:///c:/xampp/htdocs/vean/vean/PaginaWeb/src/DAO/MySQLUserDAO.php):** Implementa el acceso real utilizando sentencias preparadas de **PDO** para comunicarse de manera segura con MySQL.
- **[MockUserDAO.php](file:///c:/xampp/htdocs/vean/vean/PaginaWeb/src/DAO/MockUserDAO.php):** Implementa un almacenamiento temporal en memoria (un array), lo que nos permite probar el controlador al instante sin depender de una base de datos real.

---

## 3. Principios SOLID

SOLID es un conjunto de cinco principios de diseño orientado a objetos que hacen el software más mantenible y comprensible.

### S - Single Responsibility Principle (Principio de Responsabilidad Única)
*Una clase debe tener una sola razón para cambiar.*
- **Ejemplo en el proyecto:** La clase [Connection.php](file:///c:/xampp/htdocs/vean/vean/PaginaWeb/src/Database/Connection.php) solo se encarga de crear la conexión. El modelo [User.php](file:///c:/xampp/htdocs/vean/vean/PaginaWeb/src/Model/User.php) solo encapsula los datos del usuario. El controlador [AuthController.php](file:///c:/xampp/htdocs/vean/vean/PaginaWeb/src/Controller/AuthController.php) solo maneja la lógica de autenticación.

### O - Open/Closed Principle (Principio de Abierto/Cerrado)
*Las entidades de software deben estar abiertas para su extensión, pero cerradas para su modificación.*
- **Ejemplo en el proyecto:** Si queremos cambiar la base de datos a PostgreSQL, no modificamos el código de `AuthController`. Simplemente creamos una clase `PostgreSQLUserDAO` que implemente la interfaz `UserDAOInterface` y se la pasamos al controlador. El controlador está "cerrado a modificación" pero la aplicación está "abierta a extensiones".

### L - Liskov Substitution Principle (Principio de Sustitución de Liskov)
*Los objetos de una subclase o implementación deben poder sustituir a los objetos de la superclase o interfaz sin alterar el correcto funcionamiento del programa.*
- **Ejemplo en el proyecto:** El controlador espera un objeto que cumpla con `UserDAOInterface`. Podemos pasarle tanto `MySQLUserDAO` como `MockUserDAO`. Ambos se comportan de forma coherente según su contrato, lo que hace posible el testeo automatizado de la lógica del controlador.

### I - Interface Segregation Principle (Principio de Segregación de Interfaces)
*Es mejor tener muchas interfaces específicas que una sola interfaz de propósito general.*
- **Ejemplo en el proyecto:** `UserDAOInterface` solo define métodos para usuarios. Si tuviéramos productos en la tienda, en lugar de mezclarlos en una gran interfaz, crearíamos un `ProductDAOInterface` separado.

### D - Dependency Inversion Principle (Principio de Inversión de Dependencia)
*Los módulos de alto nivel no deben depender de módulos de bajo nivel. Ambos deben depender de abstracciones.*
- **Ejemplo en el proyecto:** `AuthController` (módulo de alto nivel) no hace un `new MySQLUserDAO()`. En su lugar, pide que le pasen un objeto que implemente `UserDAOInterface` en su constructor:
  ```php
  public function __construct(UserDAOInterface $userDAO) {
      $this->userDAO = $userDAO;
  }
  ```
  Esto es la técnica de **Inyección de Dependencias** (Dependency Injection).

---

## 4. TDD (Test-Driven Development / Desarrollo Guiado por Pruebas)

**TDD** es una metodología de desarrollo donde primero escribes las pruebas automatizadas que definen el comportamiento deseado, y luego escribes el código necesario para que la prueba pase.

### Ventajas de TDD en este diseño:
Gracias a la **Inversión de Dependencias** e interfaces, pudimos aislar el controlador.
En [tests/run_tests.php](file:///c:/xampp/htdocs/vean/vean/PaginaWeb/tests/run_tests.php), creamos pruebas automatizadas utilizando `MockUserDAO`. Estas pruebas se ejecutan de manera instantánea y no necesitan realizar consultas SQL reales.

### Ejemplo de Prueba Unitarias en el proyecto:
```php
// Instanciamos el DAO de pruebas
$mockDAO = new MockUserDAO();
$controller = new AuthController($mockDAO);

// Ejecutamos la acción
$response = $controller->register('Pedro', 'pedro@test.com', '911222333', 'ContrasenaSegura');

// Verificamos el resultado esperado
assertTrue($response['success'], 'El registro debe ser exitoso con datos correctos.');
```

Si ejecutas el archivo de pruebas en tu consola (`php tests/run_tests.php`), verás si el sistema se comporta exactamente como se diseñó. Si rompes algo en el controlador en el futuro, las pruebas fallarán inmediatamente, dándote seguridad al refactorizar.
