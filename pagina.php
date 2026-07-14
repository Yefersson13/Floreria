<?php
session_start();
$nombreCliente = $_SESSION['cliente'] ?? null;
if (is_string($nombreCliente)) {
    $nombreCliente = trim($nombreCliente);
    if ($nombreCliente === '' || $nombreCliente === 'Carlos' || $nombreCliente === 'undefined' || $nombreCliente === 'null') {
        $nombreCliente = null;
    }
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description"
        content="Atelier - Florería Premium. Diseños florales exclusivos y de alta calidad para cada ocasión.">
    <title>Atelier | Florería Premium<?= $nombreCliente ? ' - Hola, ' . htmlspecialchars($nombreCliente) : '' ?></title>
    <!-- Google Fonts: Playfair Display for headings, Inter for body -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&family=Playfair+Display:ital,wght@0,400;0,600;0,700;1,400&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <!-- Local CSS -->
    <link rel="stylesheet" href="./CSS/index.css">
</head>

<body>

    <!-- Header & Navigation -->
    <header class="navbar" id="navbar">
        <div class="container nav-container">
            <div class="nav-left">
                <div class="hamburger">
                    <span class="bar"></span>
                    <span class="bar"></span>
                    <span class="bar"></span>
                </div>
                <a href="#nosotros" class="nav-text-link">Nosotros</a>
            </div>

            <div class="nav-center">
                <a href="#" class="logo-brand">
                    <svg viewBox="0 0 240 70" class="brand-logo-svg" style="height: 48px; width: 160px;">
                        <!-- Left flourish ornament -->
                        <path d="M20,35 C30,20 45,20 55,35 C60,42 70,42 75,35 C80,30 85,25 95,35" fill="none"
                            stroke="#BA9281" stroke-width="1.2" />
                        <path d="M30,38 C40,28 50,38 60,28" fill="none" stroke="#BA9281" stroke-width="0.8"
                            opacity="0.7" />
                        <circle cx="45" cy="28" r="1.5" fill="#BA9281" />

                        <!-- Central A -->
                        <text x="120" y="46" font-family="'Playfair Display', serif" font-weight="600" font-size="34"
                            fill="#7A6256" text-anchor="middle">A</text>

                        <!-- Right flourish ornament -->
                        <path d="M145,35 C155,25 160,30 165,35 C170,42 180,42 185,35 C195,20 210,20 220,35" fill="none"
                            stroke="#BA9281" stroke-width="1.2" />
                        <path d="M180,28 C190,38 200,28 210,38" fill="none" stroke="#BA9281" stroke-width="0.8"
                            opacity="0.7" />
                        <circle cx="195" cy="28" r="1.5" fill="#BA9281" />
                    </svg>
                </a>
            </div>

            <div class="nav-right">
                <a href="#cart" class="nav-icon" id="cart-icon">
                    <svg viewBox="0 0 24 24">
                        <path d="M5 9h14a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-8a2 2 0 0 1 2-2z" />
                        <path d="M9 9V6a3 3 0 0 1 6 0v3" />
                    </svg>
                </a>
                <?php if ($nombreCliente && trim($nombreCliente) !== ''): ?>
                    <a href="#" class="nav-user-logged cerrar_ses" id="user-icon"
                        title="Sesión activa: <?= htmlspecialchars($nombreCliente) ?>">
                        <div class="user-pill">
                            <svg viewBox="0 0 24 24" class="user-pill-svg">
                                <circle cx="12" cy="7" r="4" />
                                <path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2" />
                            </svg>
                            <span class="user-pill-name"><?= htmlspecialchars($nombreCliente) ?></span>
                        </div>
                    </a>
                <?php else: ?>
                    <a href="#" class="nav-icon" id="user-icon">
                        <svg viewBox="0 0 24 24">
                            <circle cx="12" cy="7" r="4" />
                            <path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2" />
                        </svg>
                    </a>
                <?php endif; ?>
                <a href="#wishlist" class="nav-icon" id="heart-icon">
                    <svg viewBox="0 0 24 24">
                        <path
                            d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z" />
                    </svg>
                </a>
                <!-- Mobile Menu Dropdown -->
                <div class="mobile-menu-dropdown" id="mobileMenuDropdown">
                    <div class="mobile-menu-section">
                        <h3>Fechas especiales</h3>
                        <ul>
                            <li><a href="pages/dia_de_la_madre.php">Día de la madre</a></li>
                            <li><a href="pages/dia_del_padre.php">Día del padre</a></li>
                            <li><a href="pages/dia_del_maestro.php">Día del maestro</a></li>
                            <li><a href="#">Día del niño</a></li>
                            <li><a href="#">Ramos de novia</a></li>
                        </ul>
                    </div>
                    <div class="mobile-menu-section">
                        <h3>Ocasiones</h3>
                        <ul>
                            <li><a href="#">Cumpleaños</a></li>
                            <li><a href="#">Graduaciones</a></li>
                            <li><a href="#">Románticos</a></li>
                            <li><a href="#">Nacimiento</a></li>
                            <li><a href="#">Agradecimiento</a></li>
                            <li><a href="#">Condolencias</a></li>
                        </ul>
                    </div>
                </div>
            </div>
    </header>

    <!-- Hero Section -->
    <section class="hero" id="inicio">
        <div class="hero-card fade-in">
            <img src="assets/images/mama_bouquet.png" class="hero-bg-img" alt="Atelier Arreglo Floral">
            <div class="hero-text-side">
                <h1 class="hero-title">
                    <span class="light-text">Los</span> <span class="highlight-text">MEJORES</span><br>
                    <span class="white-text">DETALLES</span> <span class="para-text">para</span><br>
                    <span class="highlight-text mama-text">MAMÁ</span>
                </h1>
            </div>
        </div>
    </section>

    <!-- Mother's Day Promo Slider -->
    <section class="promo-section section" id="promo-mama">
        <div class="container grid promo-grid">
            <div class="promo-info fade-in">
                <h2 class="promo-title">Para: <span class="promo-highlight">MAMÁ</span></h2>
                <div class="promo-price">129.90</div>
                <p class="promo-description">
                    Recordamos sus manos cuidándonos y su voz dándonos calma. Hoy es el momento de cuidar su corazón con
                    un detalle que hable de amor infinito y gratitud eterna. En Atelier, creamos cada arreglo pensando
                    en la magia única que solo mamá posee.
                </p>
                <a href="pages/dia_de_la_madre.php" class="btn btn-capsule btn-pink">Ver más</a>
            </div>

            <div class="promo-slider-container fade-in delay-1">
                <button class="slider-arrow arrow-left" id="prev-promo"><i class="fa-solid fa-arrow-left"></i></button>
                <div class="promo-slider">
                    <!-- Card 1 -->
                    <div class="slider-card">
                        <div class="slider-card-img-wrapper">
                            <img src="assets/images/arreglo1.jpeg" alt="Ramo para Mamá" class="slider-img">
                            <div class="slider-card-overlay">
                                <span class="ver-detalles-text">Ver detalles</span>
                            </div>
                        </div>
                    </div>
                    <!-- Card 2 -->
                    <div class="slider-card">
                        <div class="slider-card-img-wrapper">
                            <img src="assets/images/arreglo2.png" alt="Arreglo con Globo Mamá" class="slider-img">
                            <div class="slider-card-overlay">
                                <span class="ver-detalles-text">Ver detalles</span>
                            </div>
                        </div>
                    </div>
                </div>
                <button class="slider-arrow arrow-right" id="next-promo"><i
                        class="fa-solid fa-arrow-right"></i></button>
            </div>
        </div>
    </section>

    <!-- Catalog Section -->
    <section class="collection section" id="coleccion">
        <div class="container">
            <div class="section-header text-center fade-in">
                <h2>Nuestro Catálogo</h2>
            </div>

            <!-- Session Confirm Logout Box -->
            <div class="opcion_sesion">
                <p><i class="fa-solid fa-circle-user"
                        style="color: var(--primary); font-size: 1.5rem; margin-bottom: 8px; display: block;"></i>¿Desea
                    cerrar su sesión<?= $nombreCliente ? ' (' . htmlspecialchars($nombreCliente) . ')' : '' ?>?</p>
                <div class="opc_btn">
                    <button class="opc_ses btn_cerSi">Cancelar</button>
                    <button class="opc_ses btn_cerNo"
                        onclick="if(typeof window.atelierLogout==='function'){window.atelierLogout(event);}else{window.location.href='php/logout.php';}return false;">Cerrar
                        Sesión</button>
                </div>
            </div>

            <!-- Register Form Overlay -->
            <div class="form-overlay-container">
                <form id="Regis_for" action="./php/registro.php" method="post" class="Inicio">
                    <div class="form-header">
                        <h3>Registrarse</h3>
                        <span class="close-form-btn">&times;</span>
                    </div>
                    <div class="input-group">
                        <label for="name">Nombre</label>
                        <input type="text" id="name" required placeholder="Tu nombre completo" name="nombre">
                    </div>
                    <div class="input-group">
                        <label for="email">Correo Electrónico</label>
                        <input type="email" id="email" required placeholder="ejemplo@email.com" name="correo">
                    </div>
                    <div class="input-group" s>
                        <label for="telefono">Teléfono</label>
                        <input type="tel" id="telefono" required name="telefono">
                    </div>
                    <div class="input-group">
                        <label for="contra">Contraseña</label>
                        <input type="password" id="contra" required name="contra">
                    </div>
                    <button type="submit" class="btn btn-primary" style="width: 100%;">Registrarse</button>
                    <p>¿Ya tienes cuenta? <a id="regist" href="#">Iniciar Sesión</a></p>
                </form>
            </div>

            <!-- Login Form Overlay -->
            <div class="form-overlay-container">
                <form id="Inicio_for" action="./php/login.php" method="post" class="Inicio-F">
                    <div class="form-header">
                        <h3>Iniciar Sesión</h3>
                        <span class="close-form-btn">&times;</span>
                    </div>
                    <div class="input-group">
                        <label for="login-email">Correo Electrónico</label>
                        <input type="email" id="login-email" required placeholder="ejemplo@email.com" name="correo">
                    </div>
                    <div class="input-group">
                        <label for="login-password">Contraseña</label>
                        <input type="password" id="login-password" required name="contra">
                    </div>
                    <p>¿No tienes cuenta? <a class="btn-inicio" id="regist" href="#">Registrarse</a></p>
                    <button type="submit" class="btn btn-primary" style="width: 100%;">Iniciar Sesión</button>
                </form>
            </div>

            <!-- Catalog Product Grid -->
            <div class="grid collection-grid">
                <!-- Product 1 -->
                <div class="product-card fade-in">
                    <div class="product-img-wrapper">
                        <button class="wishlist-btn"><i class="fa-regular fa-heart"></i></button>
                        <img src="assets/images/cesto_lilas.png" alt="Cesto Lilas" class="product-img">
                        <div class="product-overlay">
                            <span class="ver-detalles-text">Ver detalles</span>
                        </div>
                    </div>
                    <div class="product-info">
                        <h3>Cesto Lilas</h3>
                        <p class="desc">Cesto elegante de flores en tonos lilas y blancos</p>
                        <p class="price">S/ 149.90</p>
                        <button class="btnComprar btn btn-agregar">Agregar</button>
                    </div>
                </div>

                <!-- Product 2 -->
                <div class="product-card fade-in delay-1">
                    <div class="product-img-wrapper">
                        <button class="wishlist-btn"><i class="fa-regular fa-heart"></i></button>
                        <img src="assets/images/florero_encanto.png" alt="Florero Encanto" class="product-img">
                        <div class="product-overlay">
                            <span class="ver-detalles-text">Ver detalles</span>
                        </div>
                    </div>
                    <div class="product-info">
                        <h3>Florero Encanto</h3>
                        <p class="desc">Cesto elegante de flores en tonos lilas y blancos</p>
                        <p class="price">S/ 149.90</p>
                        <button class="btnComprar btn btn-agregar">Agregar</button>
                    </div>
                </div>

                <!-- Product 3 -->
                <div class="product-card fade-in delay-2">
                    <div class="product-img-wrapper">
                        <button class="wishlist-btn"><i class="fa-regular fa-heart"></i></button>
                        <img src="assets/images/box_rosas_claveles.png" alt="Box Rosas y Claveles" class="product-img">
                        <div class="product-overlay">
                            <span class="ver-detalles-text">Ver detalles</span>
                        </div>
                    </div>
                    <div class="product-info">
                        <h3>Box Rosas y Claveles</h3>
                        <p class="desc">Cesto elegante de flores en tonos lilas y blancos</p>
                        <p class="price">S/ 129.90</p>
                        <button class="btnComprar btn btn-agregar">Agregar</button>
                    </div>
                </div>

                <!-- Product 4 -->
                <div class="product-card fade-in delay-3">
                    <div class="product-img-wrapper">
                        <button class="wishlist-btn"><i class="fa-regular fa-heart"></i></button>
                        <img src="assets/images/ramo_coreano_lilas.png" alt="Ramo Coreano Lilas" class="product-img">
                        <div class="product-overlay">
                            <span class="ver-detalles-text">Ver detalles</span>
                        </div>
                    </div>
                    <div class="product-info">
                        <h3>Ramo Coreano Lilas</h3>
                        <p class="desc">Cesto elegante de flores en tonos lilas y blancos</p>
                        <p class="price">S/ 129.90</p>
                        <button class="btnComprar btn btn-agregar">Agregar</button>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- About / Why Us Section -->
    <section class="about-why-us section" id="nosotros">
        <div class="container why-us-wrapper fade-in">
            <div class="why-us-card">
                <img src="assets/images/backgorundAtlr.jpg" class="why-us-bg-img" alt="¿Por qué elegirnos?">
                <div class="why-us-content">
                    <h2 class="why-us-title"><em>¿Por qué</em> <span class="highlight">ELEGIRNOS?</span></h2>
                    <p class="why-us-description">
                        Recordamos sus manos cuidándonos y su voz dándonos calma. Hoy es el momento de cuidar su corazón
                        con un detalle que hable de amor infinito y gratitud eterna. En Atelier, creamos cada arreglo
                        pensando en la magia única que solo mamá posee.
                    </p>
                    <div class="why-us-logo"></div>
                </div>
            </div>
        </div>
    </section>


    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <!-- Footer Logo / Brand at Top -->
            <div class="footer-brand">
                <div class="footer-logo-brand">
                    <span class="logo-title">ATELIER</span>
                    <span class="logo-subtitle">FLORERÍA</span>
                </div>
            </div>

            <!-- Main Content Grid -->
            <div class="footer-grid">
                <!-- Column 1: ATELIER -->
                <div class="footer-col">
                    <h3>ATELIER</h3>
                    <ul>
                        <li><a href="#nosotros">Nosotros</a></li>
                        <li><a href="#inicio">Atelier Florería</a></li>
                        <li><a href="#">Certificación OEA</a></li>
                        <li><a href="#">Tiendas</a></li>
                    </ul>
                </div>

                <!-- Column 2: Atención al cliente -->
                <div class="footer-col">
                    <h3>Atención al cliente</h3>
                    <ul>
                        <li><a href="#">Preguntas frecuentes</a></li>
                        <li><a href="#">Términos y condiciones</a></li>
                        <li><a href="#">Cambios y devoluciones</a></li>
                        <li><a href="#">Bases legales</a></li>
                        <li><a href="#" style="font-weight: 700;">Libro de reclamaciones</a></li>
                    </ul>
                </div>

                <!-- Column 3: Contáctanos -->
                <div class="footer-col">
                    <h3>Contáctanos</h3>
                    <p style="color: #666; font-size: 0.95rem; line-height: 1.6; margin-bottom: 20px;">
                        Horario de atención:<br>
                        <span style="color: #666; font-weight: 400;">Lun-Vie 9:00 am a 6:00 pm</span>
                    </p>
                    <p style="font-weight: 500; margin-bottom: 8px; color: #2C302E; font-size: 0.95rem;">Social media
                    </p>
                    <div class="social-links">
                        <a href="#"><i class="fa-brands fa-instagram"></i></a>
                        <a href="#"><i class="fa-brands fa-facebook-f"></i></a>
                        <a href="#"><i class="fa-brands fa-whatsapp"></i></a>
                    </div>
                </div>

                <!-- Column 4: Información -->
                <div class="footer-col">
                    <h3>Información</h3>
                    <p style="color: #2C302E; font-size: 0.95rem; margin-bottom: 8px; line-height: 1.6;">
                        Razón Social: <span style="color: #666;">TRADING FASHION LINE S.A.</span>
                    </p>
                    <p style="color: #2C302E; font-size: 0.95rem; margin-bottom: 8px; line-height: 1.6;">
                        RUC: <span style="color: #666;">20501057682</span>
                    </p>
                    <p style="color: #2C302E; font-size: 0.95rem; margin-bottom: 8px; line-height: 1.6;">
                        Dirección Legal: <span style="color: #666;">Av. Santuario Nro. 1323</span>
                    </p>
                </div>
            </div>
        </div>

        <!-- Bottom copyright bar -->
        <div class="footer-bottom">
            <div class="container footer-bottom-container">
                <span class="footer-copy">ATELIER &copy;2026</span>
                <div class="footer-bottom-links">
                    <a href="#">Términos & Condiciones y Políticas de Privacidad</a>
                </div>
            </div>
        </div>
    </footer>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"
        integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.1/jquery.validate.min.js"></script>


    <!-- Carrito Flotante (solo pagina.php) -->
    <div class="floating-cart" id="floating-cart">
        <i class="fa-solid fa-bag-shopping"></i>
        <span class="floating-cart-badge" id="floating-cart-badge">0</span>
    </div>

    <!-- Local Script -->
    <script src="./Js/script.js"></script>
    <script type="text/javascript" src="./Js/funciones.js"></script>
</body>

</html>