<?php
/**
 * @file products.php
 * @description API Endpoint que devuelve la información de los productos en formato JSON.
 * Responsabilidad: Implementar Repository Pattern para consultar productos vía PDO de forma segura.
 */

header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

/**
 * Entidad de Producto que mapea los datos del backend.
 */
class Product {
    public int $id;
    public string $name;
    public string $shortDescription;
    public string $longDescription;
    public float $price;
    public array $images;

    public function __construct(int $id, string $name, string $shortDescription, string $longDescription, float $price, array $images) {
        $this->id = $id;
        $this->name = $name;
        $this->shortDescription = $shortDescription;
        $this->longDescription = $longDescription;
        $this->price = $price;
        $this->images = $images;
    }

    public function toArray(): array {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'shortDescription' => $this->shortDescription,
            'longDescription' => $this->longDescription,
            'price' => $this->price,
            'images' => $this->images
        ];
    }
}

/**
 * Interfaz del repositorio de productos para desacoplar el acceso a datos.
 */
interface ProductRepositoryInterface {
    public function getAll(): array;
    public function getById(int $id): ?Product;
}

/**
 * Implementación del Repositorio utilizando PDO.
 * Cuenta con un mecanismo de fallback con mock data por si la base de datos no está configurada.
 */
class PDOProductRepository implements ProductRepositoryInterface {
    private ?PDO $db = null;
    private bool $useMock = false;

    public function __construct() {
        $this->initializeConnection();
    }

    /**
     * Inicializa la conexión PDO utilizando la configuración estándar.
     */
    private function initializeConnection() {
        $host = 'localhost';
        $dbname = 'web';
        $username = 'root';
        $password = '';
        $charset = 'utf8mb4';

        try {
            $dsn = "mysql:host=$host;dbname=$dbname;charset=$charset";
            $options = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ];
            $this->db = new PDO($dsn, $username, $password, $options);
        } catch (PDOException $e) {
            // Si la conexión falla, se activa el fallback de datos locales
            $this->useMock = true;
        }
    }

    /**
     * Retorna todos los productos disponibles.
     * @return Product[]
     */
    public function getAll(): array {
        if ($this->useMock) {
            return $this->getMockProducts();
        }

        try {
            $stmt = $this->db->query("SELECT * FROM products");
            $products = [];
            while ($row = $stmt->fetch()) {
                $products[] = $this->mapRowToProduct($row);
            }
            return $products;
        } catch (PDOException $e) {
            // Si la tabla no existe o falla la query, fallback a mock
            return $this->getMockProducts();
        }
    }

    /**
     * Busca y retorna un producto por su ID.
     * @param int $id
     * @return Product|null
     */
    public function getById(int $id): ?Product {
        if ($this->useMock) {
            return $this->findMockProductById($id);
        }

        try {
            $stmt = $this->db->prepare("SELECT * FROM products WHERE id = :id");
            $stmt->execute(['id' => $id]);
            $row = $stmt->fetch();
            
            return $row ? $this->mapRowToProduct($row) : $this->findMockProductById($id);
        } catch (PDOException $e) {
            return $this->findMockProductById($id);
        }
    }

    /**
     * Mapea una fila de base de datos a una entidad Product.
     * @param array $row
     * @return Product
     */
    private function mapRowToProduct(array $row): Product {
        return new Product(
            (int)$row['id'],
            $row['name'],
            $row['short_description'],
            $row['long_description'],
            (float)$row['price'],
            json_decode($row['images'], true) ?? []
        );
    }

    /**
     * Busca en los datos mock de respaldo por ID.
     * @param int $id
     * @return Product|null
     */
    private function findMockProductById(int $id): ?Product {
        foreach ($this->getMockProducts() as $product) {
            if ($product->id === $id) {
                return $product;
            }
        }
        return null;
    }

    /**
     * Datos mock premium para garantizar el funcionamiento inmediato de la demo.
     * @return Product[]
     */
    private function getMockProducts(): array {
        return [
            new Product(
                1,
                "Ramo de Rosas Preservadas",
                "Rosas rojas premium que duran hasta 3 años conservando su frescura natural.",
                "Un ramo espectacular de 12 rosas rojas importadas y preservadas mediante procesos ecológicos avanzados. Perfectas para aniversarios o para decorar espacios de forma elegante sin preocuparse por el riego diario. Incluye florero de vidrio decorativo.",
                145000,
                [
                    "https://images.unsplash.com/photo-1561181286-d3fee7d55364?auto=format&fit=crop&q=80&w=600",
                    "https://images.unsplash.com/photo-1526047932273-341f2a7631f9?auto=format&fit=crop&q=80&w=600",
                    "https://images.unsplash.com/photo-1562690868-60bbe7293e94?auto=format&fit=crop&q=80&w=600"
                ]
            ),
            new Product(
                2,
                "Arreglo de Tulipanes Coloridos",
                "Frescos tulipanes de Holanda en una base de cerámica artesanal.",
                "Un vibrante arreglo floral con 15 tulipanes de diversos colores (amarillos, rosados y morados). Acompañado de follaje fino en una hermosa base de cerámica blanca hecha a mano por artesanos locales. Llena tu hogar de alegría primaveral.",
                120000,
                [
                    "https://images.unsplash.com/photo-1520763185298-1b434c919102?auto=format&fit=crop&q=80&w=600",
                    "https://images.unsplash.com/photo-1455642300327-88e6bc0f189e?auto=format&fit=crop&q=80&w=600",
                    "https://images.unsplash.com/photo-1587334274328-64186a80aeee?auto=format&fit=crop&q=80&w=600"
                ]
            ),
            new Product(
                3,
                "Cesta de Orquídeas Phalaenopsis",
                "Elegante orquídea de dos varas en cesta de mimbre rústica.",
                "La orquídea Phalaenopsis blanca es el máximo símbolo de la elegancia y sofisticación. Esta planta viva viene con dos varas florales llenas de capullos listos para abrir y durar meses con mínimos cuidados. Presentada en cesta ecológica tejida a mano.",
                180000,
                [
                    "https://images.unsplash.com/photo-1525310072745-f49212b5ac6d?auto=format&fit=crop&q=80&w=600",
                    "https://images.unsplash.com/photo-1567306226416-28f0efdc88ce?auto=format&fit=crop&q=80&w=600",
                    "https://images.unsplash.com/photo-1508784932243-09406f7092e3?auto=format&fit=crop&q=80&w=600"
                ]
            )
        ];
    }
}

// Inicialización de la lógica del controlador API
$repository = new PDOProductRepository();

if (isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    $product = $repository->getById($id);
    if ($product) {
        echo json_encode($product->toArray());
    } else {
        http_response_code(404);
        echo json_encode(["error" => "Producto no encontrado"]);
    }
} else {
    $products = $repository->getAll();
    $output = array_map(fn($p) => $p->toArray(), $products);
    echo json_encode($output);
}
?>
