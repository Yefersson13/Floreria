<?php
session_start();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Atelier - Florería Premium. Diseños florales exclusivos y de alta calidad para cada ocasión.">
    <title>Atelier | Florería Premium</title>
    <!-- Google Fonts: Playfair Display for headings, Inter for body -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&family=Playfair+Display:ital,wght@0,400;0,600;0,700;1,400&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <!-- Local CSS -->
    <link rel="stylesheet" href="./CSS/index.css">
</head>
<body>

    <!-- Header & Navigation -->
    <header class="navbar" id="navbar">
        <div class="container nav-container">
            <a href="#" class="logo">Atelier.</a>
            <nav>
                <ul class="nav-links">
                    <li><a href="#inicio">Inicio</a></li>
                    <li><a href="#coleccion">Colección</a></li>
                    <li><a href="#nosotros">Nosotros</a></li>
                    <li><a href="#contacto">Contacto</a></li>
                    <li><a href="#"><i class="fa-solid fa-user"></i>
        <?php
        if (isset($_SESSION['cliente'])) {
            echo $_SESSION['cliente'];
        } else {
            echo "Usuario";
        }
        ?></a>
        </li>
        
            <li><a href="#" class="cerrar_ses"> <i class="fa-solid fa-right-to-bracket"></i> Cerrar Sesion</a></li>

                </ul>
            </nav>
            <div class="hamburger">
                <span class="bar"></span>
                <span class="bar"></span>
                <span class="bar"></span>
            </div>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="hero" id="inicio">
        <div class="hero-content">
            <h1 class="hero-title animate-fade-up">Arte floral, emociones inolvidables</h1>
            <p class="hero-subtitle animate-fade-up delay-1">Diseños exclusivos creados con las flores más frescas de la temporada, perfectos para celebrar los momentos que importan.</p>
            <a href="#coleccion" class="btn btn-primary animate-fade-up delay-2">Ver Colección</a>
        </div>
    </section>

    <!-- Featured Collection -->
    <section class="collection section" id="coleccion">
        <div class="container">
            <div class="section-header text-center fade-in">
                <h2>Nuestra Colección</h2>
                <p>Descubre nuestros arreglos más solicitados, creados con amor y detalle.</p>
            </div>


            <div class="opcion_sesion">
                <p>Desea Salir de la Sesion?</p>
                <div class="opc_btn">
                <button class="opc_ses btn_cerSi">Cancelar</button>
                <button class="opc_ses btn_cerNo"onclick="window.location.href='./index.html'">Cerrar Sesion</button>
                </div>
            </div> 


            <div class="grid collection-grid">
                <!-- Product 1 -->
                <div class="product-card fade-in">
                    <div class="product-img-wrapper">
                        <img src="assets/images/product1.png" alt="Ramo de Peonías Pastel" class="product-img">
                        <div class="product-overlay">
                            <button class="btnComprar btn btn-outline">Comprar</button>
                        </div>
                    </div>
                    <div class="product-info">
                        <h3>Sueño de Peonías</h3>
                        <p class="price">$85.00</p>
                    </div>
                </div>

                <!-- Product 2 -->
                <div class="product-card fade-in delay-1">
                    <div class="product-img-wrapper">
                        <img src="assets/images/product2.png" alt="Orquídeas Minimalistas" class="product-img">
                        <div class="product-overlay">
                            <button class="btnComprar btn btn-outline">Comprar</button>
                        </div>
                    </div>
                    <div class="product-info">
                        <h3>Elegancia Minimal</h3>
                        <p class="price">$120.00</p>
                    </div>
                </div>

                <!-- Product 3 -->
                <div class="product-card fade-in delay-2">
                    <div class="product-img-wrapper">
                        <img src="assets/images/product3.png" alt="Rosas Románticas" class="product-img">
                        <div class="product-overlay">
                            <button class="btnComprar btn btn-outline">Comprar</button>
                        </div>
                    </div>
                    <div class="product-info">
                        <h3>Pasión Absoluta</h3>
                        <p class="price">$95.00</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section class="about section" id="nosotros">
        <div class="container grid about-grid">
            <div class="about-text fade-in">
                <h2>La esencia de Atelier</h2>
                <p>En Atelier creemos que cada flor tiene una historia que contar. Nuestro equipo de floristas expertos selecciona cuidadosamente cada tallo para crear arreglos que no solo decoran espacios, sino que también transmiten emociones profundas.</p>
                <p>Nuestra misión es llevar la belleza de la naturaleza a tu vida diaria con diseños modernos, elegantes y completamente únicos.</p>
            </div>
            <div class="about-image fade-in delay-1">
                <div class="image-frame">
                    <img src="assets/images/hero.png" alt="Floristas trabajando en Atelier">
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section class="contact section" id="contacto">
        <div class="container border-top text-center" style="max-width: 600px; margin: 0 auto;">
            <div class="section-header fade-in">
                <h2>Ponte en contacto</h2>
                <p style="margin-bottom: 2rem; color: var(--text-muted)">¿Tienes un pedido especial o necesitas asesoramiento? Escríbenos.</p>
            </div>
            
            <form id="contactForm" class="contact-form fade-in delay-1">
                <div class="input-group">
                    <label for="name">Nombre</label>
                    <input type="text" id="name" required placeholder="Tu nombre completo">
                </div>
                <div class="input-group">
                    <label for="email">Correo Electrónico</label>
                    <input type="email" id="email" required placeholder="tu@email.com">
                </div>
                <div class="input-group">
                    <label for="message">Mensaje</label>
                    <textarea id="message" rows="4" required placeholder="¿Te gustaría personalizar tu pedido?"></textarea>
                </div>
                <button type="submit" class="btn btn-primary" style="width: 100%;">Enviar Mensaje</button>
            </form>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer section">
        <div class="container">
             <div class="flex-footer">
                <div class="footer-logo">
                    <h2>Atelier.</h2>
                    <p>El arte floral llevado al siguiente nivel.</p>
                </div>
                <div class="footer-contact">
                    <h4>Contacto</h4>
                    <p>+1 (555) 123-4567<br>hola@atelier.com<br>Av. Primaveras 123, Ciudad Floral</p>
                </div>
                <div class="footer-links">
                    <h4>Navegación</h4>
                    <a href="#inicio">Inicio</a>
                    <a href="#coleccion">Colección</a>
                    <a href="#nosotros">Nosotros</a>
                    <a href="#contacto">Contacto</a>
                </div>
             </div>
             <p class="copyright text-center">&copy; 2026 Atelier. Todos los derechos reservados.</p>
        </div>
    </footer>


    <!-- Local Script -->
    <script src="../PaginaWeb/Js/script.js"></script>
    <script src="./Js/script.js"></script>
</body>
</html>