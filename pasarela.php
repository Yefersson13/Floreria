<?php
session_start();
$nombreUsuario = $_SESSION['cliente'] ?? $_SESSION['usuario_nombre'] ?? $_SESSION['usuario'] ?? null;
if (is_string($nombreUsuario)) {
    $nombreUsuario = trim($nombreUsuario);
    if ($nombreUsuario === '' || $nombreUsuario === 'Carlos' || $nombreUsuario === 'undefined' || $nombreUsuario === 'null') {
        $nombreUsuario = null;
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pasarela de Compra — Atelier Florería</title>
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400..900;1,400..900&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- CSS Principal -->
    <link rel="stylesheet" href="CSS/index.css">
    <style>
        /* Estilos Específicos para la Pasarela de Compra (Fiel a la Captura y WOW) */
        body {
            background-color: #FBF9F8;
            font-family: 'Inter', sans-serif;
            color: #2C302E;
            padding-top: var(--nav-height, 70px);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .pasarela-header-title {
            text-align: center;
            font-family: 'Playfair Display', serif;
            font-size: 2rem;
            color: #BA9281;
            letter-spacing: 2px;
            text-transform: uppercase;
            margin: 35px 0 30px;
            font-weight: 500;
        }

        /* Barra de navegación por pasos de la Pasarela */
        .pasarela-steps-bar {
            background: #BA9281;
            border-radius: 8px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0;
            overflow: hidden;
            box-shadow: 0 6px 20px rgba(186, 146, 129, 0.25);
            margin-bottom: 40px;
        }

        .pasarela-step-item {
            flex: 1;
            text-align: center;
            padding: 18px 12px;
            color: #FFFFFF;
            font-size: 1.05rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            border-right: 1px solid rgba(255, 255, 255, 0.15);
        }

        .pasarela-step-item:last-child {
            border-right: none;
        }

        .pasarela-step-item:hover {
            background: rgba(255, 255, 255, 0.15);
        }

        .pasarela-step-item.active {
            font-weight: 700;
            background: #9c7566;
            box-shadow: inset 0 3px 6px rgba(0,0,0,0.15);
        }

        /* Contenedor Grid Principal */
        .pasarela-grid {
            display: grid;
            grid-template-columns: 1fr 390px;
            gap: 35px;
            align-items: start;
            margin-bottom: 60px;
        }

        @media (max-width: 992px) {
            .pasarela-grid {
                grid-template-columns: 1fr;
                gap: 25px;
            }
            .pasarela-resumen-card {
                position: static;
            }
            .pasarela-steps-bar {
                flex-wrap: wrap;
            }
            .pasarela-step-item {
                flex: 1 1 45%;
                font-size: 0.95rem;
                padding: 14px 8px;
            }
        }

        @media (max-width: 600px) {
            body {
                padding-top: var(--nav-height, 65px);
            }
            .pasarela-header-title {
                font-size: 1.5rem;
                margin: 20px 0;
            }
            .pasarela-steps-bar {
                flex-direction: column;
                border-radius: 12px;
            }
            .pasarela-step-item {
                width: 100%;
                border-right: none;
                border-bottom: 1px solid rgba(255, 255, 255, 0.15);
                padding: 14px 10px;
                font-size: 0.95rem;
            }
            .pasarela-step-item:last-child {
                border-bottom: none;
            }
            .pasarela-item-card {
                flex-direction: column;
                align-items: flex-start;
                padding: 18px;
                gap: 16px;
            }
            .pasarela-item-left {
                width: 100%;
                flex-direction: row;
                gap: 15px;
            }
            .pasarela-item-img {
                width: 90px;
                height: 90px;
            }
            .pasarela-item-cols {
                width: 100%;
                justify-content: space-between;
                padding-right: 0;
                border-top: 1px dashed #CBD5E1;
                padding-top: 14px;
                margin-top: 4px;
            }
            .pasarela-btn-close {
                top: 12px;
                right: 12px;
            }
            .pasarela-step-view {
                padding: 20px 15px;
            }
            .payment-options-grid {
                grid-template-columns: 1fr;
                gap: 12px;
            }
        }

        /* Tarjetas Horizontales de Producto en Carrito */
        .pasarela-item-card {
            background: #FFFFFF;
            border: 1px solid #EFEFEF;
            border-radius: 12px;
            padding: 22px;
            box-shadow: 0 4px 18px rgba(0, 0, 0, 0.03);
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: relative;
            margin-bottom: 22px;
            transition: transform 0.25s ease, box-shadow 0.25s ease;
        }

        .pasarela-item-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.06);
        }

        .pasarela-item-left {
            display: flex;
            align-items: center;
            gap: 22px;
            flex: 1.2;
        }

        .pasarela-item-img {
            width: 125px;
            height: 125px;
            object-fit: cover;
            border-radius: 8px;
            background-color: #f8fafc;
            box-shadow: 0 2px 8px rgba(0,0,0,0.06);
        }

        .pasarela-item-info h3 {
            font-size: 1.35rem;
            font-weight: 700;
            color: #1e293b;
            margin: 0 0 4px 0;
            font-family: 'Inter', sans-serif;
        }

        .pasarela-item-info p {
            font-size: 0.95rem;
            color: #64748b;
            margin: 0;
        }

        .pasarela-item-cols {
            display: flex;
            align-items: center;
            justify-content: space-around;
            flex: 1.5;
            padding-right: 30px;
        }

        .pasarela-col {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 6px;
        }

        .pasarela-col-label {
            font-size: 0.85rem;
            font-weight: 600;
            color: #475569;
        }

        .pasarela-col-val {
            font-size: 1.08rem;
            color: #64748b;
            font-weight: 500;
        }

        .pasarela-col-total {
            font-size: 1.22rem;
            color: #BA0535;
            font-weight: 700;
        }

        /* Selector de Cantidad Estilo Captura */
        .pasarela-qty-select {
            background: #F8FAFC;
            border: 1px solid #CBD5E1;
            border-radius: 6px;
            padding: 6px 12px;
            font-size: 1rem;
            font-weight: 600;
            color: #334155;
            cursor: pointer;
            outline: none;
            transition: border-color 0.2s;
        }

        .pasarela-qty-select:hover {
            border-color: #BA9281;
        }

        /* Botón Cerrar [X] naranja/taupe en esquina superior derecha de tarjeta */
        .pasarela-btn-close {
            position: absolute;
            top: 18px;
            right: 18px;
            background: #BA9281;
            color: #FFFFFF;
            width: 30px;
            height: 30px;
            border-radius: 6px;
            border: none;
            font-size: 0.95rem;
            font-weight: 700;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s ease;
        }

        .pasarela-btn-close:hover {
            background: #BA0535;
            transform: scale(1.08);
        }

        /* Tarjeta Resumen de Compra */
        .pasarela-resumen-card {
            background: #FFFFFF;
            border: 1px solid #EFEFEF;
            border-radius: 12px;
            padding: 32px 28px;
            box-shadow: 0 4px 18px rgba(0, 0, 0, 0.03);
            position: sticky;
            top: 95px;
        }

        .pasarela-resumen-title {
            font-size: 1.25rem;
            font-weight: 700;
            color: #64748b;
            text-align: center;
            margin: 0 0 28px 0;
            font-family: 'Inter', sans-serif;
        }

        .pasarela-resumen-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 18px;
            font-size: 1.02rem;
            color: #94a3b8;
        }

        .pasarela-resumen-row.strong {
            font-weight: 600;
            color: #64748b;
        }

        .pasarela-resumen-divider {
            height: 1px;
            background: #E2E8F0;
            margin: 22px 0;
        }

        .pasarela-resumen-total {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 1.22rem;
            font-weight: 700;
            color: #64748b;
            margin-bottom: 35px;
        }

        .pasarela-resumen-total .total-amount {
            color: #BA0535;
            font-size: 1.35rem;
        }

        .pasarela-btn-comprar {
            background: #BA9281;
            color: #FFFFFF;
            border: none;
            width: 100%;
            padding: 15px 25px;
            border-radius: 8px;
            font-size: 1.12rem;
            font-weight: 600;
            cursor: pointer;
            display: block;
            transition: all 0.3s ease;
            box-shadow: 0 6px 18px rgba(186, 146, 129, 0.3);
            text-align: center;
        }

        .pasarela-btn-comprar:hover {
            background: #7A6256;
            transform: translateY(-2px);
            box-shadow: 0 8px 22px rgba(122, 98, 86, 0.4);
        }

        /* Vistas de los Pasos (Datos personales, Forma de entrega, Métodos de pago) */
        .pasarela-step-view {
            display: none;
            background: #FFFFFF;
            border: 1px solid #EFEFEF;
            border-radius: 12px;
            padding: 35px;
            box-shadow: 0 4px 18px rgba(0, 0, 0, 0.03);
            animation: fadeInStep 0.35s ease;
        }

        .pasarela-step-view.active {
            display: block;
        }

        @keyframes fadeInStep {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .form-section-title {
            font-family: 'Playfair Display', serif;
            font-size: 1.5rem;
            color: #7A6256;
            margin-bottom: 25px;
            border-bottom: 2px solid #FBF9F8;
            padding-bottom: 12px;
        }

        .form-group {
            margin-bottom: 22px;
        }

        .form-group label {
            display: block;
            font-weight: 600;
            font-size: 0.95rem;
            color: #475569;
            margin-bottom: 8px;
        }

        .form-control {
            width: 100%;
            padding: 13px 16px;
            border: 1.5px solid #CBD5E1;
            border-radius: 8px;
            font-size: 1rem;
            color: #1e293b;
            transition: border-color 0.2s;
            outline: none;
        }

        .form-control:focus {
            border-color: #BA9281;
        }

        .payment-options-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .payment-option-card {
            border: 2px solid #CBD5E1;
            border-radius: 12px;
            padding: 22px;
            text-align: center;
            cursor: pointer;
            transition: all 0.25s ease;
            background: #FBF9F8;
        }

        .payment-option-card:hover, .payment-option-card.selected {
            border-color: #BA9281;
            background: #FFF;
            box-shadow: 0 6px 20px rgba(186, 146, 129, 0.15);
        }

        .payment-option-card i {
            font-size: 2.2rem;
            color: #BA9281;
            margin-bottom: 12px;
        }

        .payment-option-card h4 {
            margin: 0 0 6px 0;
            font-size: 1.1rem;
            color: #1e293b;
        }

        .payment-option-card p {
            margin: 0;
            font-size: 0.85rem;
            color: #64748b;
        }

        /* Empty State */
        .pasarela-empty-state {
            text-align: center;
            padding: 60px 20px;
            background: #FFF;
            border-radius: 12px;
            border: 1px solid #EFEFEF;
        }

        .pasarela-empty-state i {
            font-size: 3.5rem;
            color: #BA9281;
            margin-bottom: 18px;
        }

        .pasarela-empty-state h3 {
            font-size: 1.5rem;
            color: #334155;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>

    <!-- 1. HEADER & NAVBAR -->
    <header class="navbar">
        <div class="nav-container container">
            <div class="nav-left">
                <div class="hamburger">
                    <span class="bar"></span>
                    <span class="bar"></span>
                    <span class="bar"></span>
                </div>
                <a href="pagina.php#nosotros" class="nav-text-link">Nosotros</a>
            </div>

            <div class="nav-center">
                <a href="pagina.php" class="logo-brand">
                    <img src="assets/images/logo.png" alt="Atelier Logo" class="brand-logo" onerror="this.outerHTML='<span style=\'font-family:Playfair Display,serif;font-size:1.8rem;color:#7A6256;font-weight:700;\'>ATELIER</span>'">
                </a>
            </div>

            <div class="nav-right">
                <a href="pasarela.php" class="nav-icon" id="cart-icon" aria-label="Carrito de compras">
                    <i class="fa-solid fa-bag-shopping"></i>
                </a>
                <?php if ($nombreUsuario && trim($nombreUsuario) !== ''): ?>
                <a href="#" class="nav-user-logged cerrar_ses" id="user-icon" title="Sesión activa: <?= htmlspecialchars($nombreUsuario) ?>">
                    <div class="user-pill">
                        <svg viewBox="0 0 24 24" class="user-pill-svg">
                            <circle cx="12" cy="7" r="4" />
                            <path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2" />
                        </svg>
                        <span class="user-pill-name" id="localUser"><?= htmlspecialchars($nombreUsuario) ?></span>
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
                <a href="pagina.php#wishlist" class="nav-icon" id="heart-icon" aria-label="Lista de deseos">
                    <i class="fa-regular fa-heart"></i>
                </a>
                <!-- Mobile Menu Dropdown -->
                <div class="mobile-menu-dropdown" id="mobileMenuDropdown">
                    <div class="mobile-menu-section">
                        <h3>Fechas especiales</h3>
                        <a href="pages/dia_de_la_madre.php">Día de la madre</a>
                        <a href="pages/dia_del_padre.php">Día del padre</a>
                        <a href="#">Día del maestro</a>
                        <a href="#">Día del niño</a>
                        <a href="#">Ramos de novia</a>
                    </div>
                    <div class="mobile-menu-section">
                        <h3>Ocasiones</h3>
                        <a href="#">Cumpleaños</a>
                        <a href="#">Graduaciones</a>
                        <a href="#">Románticos</a>
                        <a href="#">Nacimiento</a>
                        <a href="#">Agradecimiento</a>
                        <a href="#">Condolencias</a>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Modal Cierre de Sesión -->
    <div class="opcion_sesion" style="display:none;">
        <div class="cuadro_sesion">
            <p id="msg_sesion">¿Desea cerrar su sesión (<?php echo htmlspecialchars($nombreUsuario); ?>)?</p>
            <div class="botones_sesion">
                <button class="btn_cerSi">Cancelar</button>
                <button class="btn_cerNo" onclick="if(typeof window.atelierLogout==='function'){window.atelierLogout(event);}else{window.location.href='php/logout.php';}return false;">Cerrar Sesión</button>
            </div>
        </div>
    </div>

    <!-- 2. CONTENIDO DE PASARELA -->
    <main class="container" style="flex:1; width: 100%; max-width: 1250px; margin: 0 auto; padding: 0 20px;">
        
        <!-- Título Principal -->
        <h1 class="pasarela-header-title">Pasarella de Compra</h1>

        <!-- Barra de Navegación por Pasos -->
        <div class="pasarela-steps-bar">
            <div class="pasarela-step-item active" data-step="1">
                <span>Carrito de compra</span>
            </div>
            <div class="pasarela-step-item" data-step="2">
                <span>Datos personales</span>
            </div>
            <div class="pasarela-step-item" data-step="3">
                <span>Forma de entrega</span>
            </div>
            <div class="pasarela-step-item" data-step="4">
                <span>Métodos de pago</span>
            </div>
            <a href="pagina.php" class="pasarela-step-item" style="text-decoration:none;">
                <span>Seguir comprando</span>
            </a>
        </div>

        <!-- Contenedor Grid Principal -->
        <div class="pasarela-grid">
            
            <!-- COLUMNA IZQUIERDA: VISTAS POR PASO -->
            <div class="pasarela-main-col">
                
                <!-- PASO 1: CARRITO DE COMPRA -->
                <div class="pasarela-step-view active" id="step-view-1">
                    <div id="pasarela-items-list"></div>
                </div>

                <!-- PASO 2: DATOS PERSONALES -->
                <div class="pasarela-step-view" id="step-view-2">
                    <h2 class="form-section-title">Datos Personales del Cliente</h2>
                    <form id="form-datos-personales" onsubmit="return false;">
                        <div class="form-group">
                            <label for="p-nombre">Nombre y Apellidos *</label>
                            <input type="text" id="p-nombre" class="form-control" value="<?php echo htmlspecialchars($nombreUsuario); ?>" placeholder="Ej. Juan Pérez" required>
                        </div>
                        <div class="form-group">
                            <label for="p-correo">Correo Electrónico *</label>
                            <input type="email" id="p-correo" class="form-control" placeholder="juan@ejemplo.com" required>
                        </div>
                        <div class="form-group">
                            <label for="p-telefono">Teléfono / WhatsApp de Contacto *</label>
                            <input type="tel" id="p-telefono" class="form-control" placeholder="+51 987 654 321" required>
                        </div>
                        <div class="form-group">
                            <label for="p-dni">DNI / RUC o Documento de Identidad</label>
                            <input type="text" id="p-dni" class="form-control" placeholder="Número de documento">
                        </div>
                        <button type="button" class="pasarela-btn-comprar" onclick="switchPasarelaStep(3)" style="margin-top:25px;">
                            Continuar a Forma de Entrega &rarr;
                        </button>
                    </form>
                </div>

                <!-- PASO 3: FORMA DE ENTREGA -->
                <div class="pasarela-step-view" id="step-view-3">
                    <h2 class="form-section-title">Forma de Entrega y Dedicatoria</h2>
                    <div class="payment-options-grid" style="margin-bottom:25px;">
                        <div class="payment-option-card selected" onclick="selectDeliveryOption(this, 0)">
                            <i class="fa-solid fa-truck-fast"></i>
                            <h4>Envío a Domicilio</h4>
                            <p>Entrega express y programada (Gratis por Promoción)</p>
                        </div>
                        <div class="payment-option-card" onclick="selectDeliveryOption(this, 0)">
                            <i class="fa-solid fa-shop"></i>
                            <h4>Recojo en Tienda</h4>
                            <p>Atelier Central — Miraflores / Lima (Gratis)</p>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="e-direccion">Dirección exacta de Entrega *</label>
                        <input type="text" id="e-direccion" class="form-control" placeholder="Av. Principal 1234, Dpto. 501, Miraflores">
                    </div>
                    <div class="form-group">
                        <label for="e-fecha">Fecha Programada de Entrega *</label>
                        <input type="date" id="e-fecha" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="e-dedicatoria">Mensaje para la Tarjeta de Dedicatoria (Incluida) 💌</label>
                        <textarea id="e-dedicatoria" class="form-control" rows="3" placeholder="Escribe un mensaje conmovedor y especial para esa persona..."></textarea>
                    </div>
                    <button type="button" class="pasarela-btn-comprar" onclick="switchPasarelaStep(4)" style="margin-top:20px;">
                        Continuar a Métodos de Pago &rarr;
                    </button>
                </div>

                <!-- PASO 4: MÉTODOS DE PAGO -->
                <div class="pasarela-step-view" id="step-view-4">
                    <h2 class="form-section-title">Selecciona tu Método de Pago</h2>
                    <div class="payment-options-grid">
                        <div class="payment-option-card selected" onclick="selectPaymentMethod(this, 'tarjeta')">
                            <i class="fa-regular fa-credit-card"></i>
                            <h4>Tarjeta de Crédito / Débito</h4>
                            <p>Visa, Mastercard, American Express o Diners</p>
                        </div>
                        <div class="payment-option-card" onclick="selectPaymentMethod(this, 'yape')">
                            <i class="fa-solid fa-qrcode"></i>
                            <h4>Yape / Plin QR</h4>
                            <p>Confirmación instantánea al número Atelier</p>
                        </div>
                        <div class="payment-option-card" onclick="selectPaymentMethod(this, 'transferencia')">
                            <i class="fa-solid fa-building-columns"></i>
                            <h4>Transferencia Bancaria</h4>
                            <p>BCP, BBVA, Interbank o Scotiabank</p>
                        </div>
                    </div>

                    <!-- Datos para Tarjeta -->
                    <div id="pago-tarjeta-box" style="background:#F8FAFC; padding:25px; border-radius:12px; border:1px solid #CBD5E1;">
                        <div class="form-group">
                            <label>Número de Tarjeta</label>
                            <input type="text" class="form-control" placeholder="4557 9900 1234 5678" maxlength="19">
                        </div>
                        <div style="display:grid; grid-template-columns:1fr 1fr; gap:15px;">
                            <div class="form-group">
                                <label>Vencimiento (MM/YY)</label>
                                <input type="text" class="form-control" placeholder="08/28" maxlength="5">
                            </div>
                            <div class="form-group">
                                <label>CVV (Código de seguridad)</label>
                                <input type="password" class="form-control" placeholder="123" maxlength="4">
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Titular de la Tarjeta</label>
                            <input type="text" class="form-control" placeholder="Nombre como aparece en la tarjeta">
                        </div>
                    </div>

                    <button type="button" class="pasarela-btn-comprar" onclick="finalizarPedidoAtelier()" style="margin-top:30px; background:#BA0535; font-size:1.2rem;">
                        💐 Pagar y Confirmar Pedido <span id="btn-final-total-text">S/ 249.80</span>
                    </button>
                </div>

            </div>

            <!-- COLUMNA DERECHA: RESUMEN DE COMPRA -->
            <div class="pasarela-sidebar-col">
                <div class="pasarela-resumen-card">
                    <h3 class="pasarela-resumen-title">Resumen de Compra</h3>
                    
                    <div class="pasarela-resumen-row">
                        <span>Subtotal</span>
                        <span id="resumen-subtotal-val">S/ 249.80</span>
                    </div>

                    <div class="pasarela-resumen-row strong">
                        <span>Gastos del envío</span>
                        <span style="color:#2C302E;">Gratis</span>
                    </div>

                    <div class="pasarela-resumen-divider"></div>

                    <div class="pasarela-resumen-total">
                        <span>Total</span>
                        <span class="total-amount" id="resumen-total-val">S/ 249.80</span>
                    </div>

                    <button type="button" class="pasarela-btn-comprar" id="resumen-main-btn" onclick="switchPasarelaStep(2)">
                        Ir a comprar
                    </button>
                </div>
            </div>

        </div>

    </main>

    <!-- Modal de Celebración de Pedido Exitoso -->
    <div id="modal-pedido-exitoso" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.65); z-index:9999; align-items:center; justify-content:center; padding:20px; backdrop-filter:blur(5px);">
        <div style="background:#FFF; max-width:500px; width:100%; border-radius:20px; padding:40px 30px; text-align:center; box-shadow:0 25px 60px rgba(0,0,0,0.3); animation: fadeInStep 0.4s ease;">
            <div style="width:80px; height:80px; background:#e6f4ea; color:#137333; border-radius:50%; display:flex; align-items:center; justify-content:center; font-size:2.5rem; margin:0 auto 20px;">
                <i class="fa-solid fa-check"></i>
            </div>
            <h2 style="font-family:'Playfair Display',serif; color:#7A6256; font-size:1.8rem; margin-bottom:10px;">¡Pedido Confirmado!</h2>
            <p style="color:#64748b; font-size:1.05rem; line-height:1.6; margin-bottom:25px;">
                Muchas gracias por confiar en <strong>Atelier Florería</strong>. Tu código de pedido es <span style="color:#BA0535; font-weight:700;">#ATL-2026-894</span>. Hemos enviado los detalles a tu correo electrónico y te contactaremos por WhatsApp.
            </p>
            <a href="pagina.php" class="pasarela-btn-comprar" style="text-decoration:none; background:#7A6256;">
                Volver a la Florería
            </a>
        </div>
    </div>

    <!-- 3. FOOTER -->
    <footer class="footer">
        <div class="container footer-grid">
            <div class="footer-col">
                <span class="footer-brand">Atelier</span>
                <p class="footer-desc">Creando momentos inolvidables a través de arte floral e inspiración botánica premium.</p>
            </div>
            <div class="footer-col">
                <h4>Enlaces Rápidos</h4>
                <ul>
                    <li><a href="pagina.php#nosotros">Nosotros</a></li>
                    <li><a href="pages/dia_de_la_madre.php">Fechas Especiales</a></li>
                    <li><a href="pasarela.php">Mi Bolso</a></li>
                </ul>
            </div>
            <div class="footer-col">
                <h4>Atención al cliente</h4>
                <ul>
                    <li><a href="#">Preguntas Frecuentes</a></li>
                    <li><a href="#">Envíos y Entregas</a></li>
                    <li><a href="#">Términos y Condiciones</a></li>
                </ul>
            </div>
            <div class="footer-col">
                <h4>Contáctanos</h4>
                <p><i class="fa-solid fa-phone"></i> +51 987 654 321</p>
                <p><i class="fa-solid fa-envelope"></i> pedidos@atelierfloreria.com</p>
            </div>
        </div>
        <div class="footer-bottom">
            <div class="container footer-bottom-container">
                <span class="footer-copy">ATELIER &copy; 2026 Todos los derechos reservados.</span>
            </div>
        </div>
    </footer>

    <!-- Scripts de la Pasarela -->
    <script src="Js/funciones.js"></script>
    <script src="Js/script.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const fechaInput = document.getElementById('e-fecha');
            if (fechaInput) {
                const mañana = new Date();
                mañana.setDate(mañana.getDate() + 1);
                fechaInput.value = mañana.toISOString().split('T')[0];
            }

            const loggedName = localStorage.getItem('atelier_current_user') || '<?php echo !empty($nombreUsuario) ? addslashes($nombreUsuario) : ""; ?>';
            if (loggedName) {
                const nombreEl = document.getElementById('p-nombre');
                if (nombreEl) nombreEl.value = loggedName;
            }

            renderPasarelaItems();
        });

        function getPasarelaCartItems() {
            let items = JSON.parse(localStorage.getItem('atelier_cart_items') || '[]');
            if (items.length === 0) {
                items = [
                    {
                        id: 101,
                        name: "Cesto Rosas",
                        subtitle: "Rosas lilas",
                        price: 79.92,
                        quantity: 1,
                        img: "assets/images/cesto_lilas.png"
                    },
                    {
                        id: 102,
                        name: "Encanto",
                        subtitle: "Rosas y follaje premium",
                        price: 169.88,
                        quantity: 1,
                        img: "assets/images/florero_encanto.png"
                    }
                ];
                localStorage.setItem('atelier_cart_items', JSON.stringify(items));
                localStorage.setItem('atelier_cart_qty', '2');
            }
            return items;
        }

        function renderPasarelaItems() {
            const container = document.getElementById('pasarela-items-list');
            if (!container) return;

            const items = getPasarelaCartItems();
            let subtotal = 0;

            if (items.length === 0) {
                container.innerHTML = `
                    <div class="pasarela-empty-state">
                        <i class="fa-solid fa-basket-shopping"></i>
                        <h3>Tu carrito de compra está vacío</h3>
                        <p style="color:#64748b; margin-bottom:25px;">Agrega hermosos arreglos florales para continuar con tu pedido.</p>
                        <a href="pagina.php" class="pasarela-btn-comprar" style="max-width:250px; margin:0 auto; text-decoration:none;">Explorar Arreglos</a>
                    </div>
                `;
                document.getElementById('resumen-subtotal-val').textContent = 'S/ 0.00';
                document.getElementById('resumen-total-val').textContent = 'S/ 0.00';
                const btnFinal = document.getElementById('btn-final-total-text');
                if (btnFinal) btnFinal.textContent = 'S/ 0.00';
                return;
            }

            container.innerHTML = items.map((item) => {
                const itemTotal = item.price * item.quantity;
                subtotal += itemTotal;
                return `
                    <div class="pasarela-item-card">
                        <div class="pasarela-item-left">
                            <img src="${item.img}" alt="${item.name}" class="pasarela-item-img" onerror="this.src='assets/images/product1.png'">
                            <div class="pasarela-item-info">
                                <h3>${item.name}</h3>
                                <p>${item.subtitle || 'Diseño floral artesanal'}</p>
                            </div>
                        </div>

                        <div class="pasarela-item-cols">
                            <div class="pasarela-col">
                                <span class="pasarela-col-label">Precio</span>
                                <span class="pasarela-col-val">S/ ${item.price.toFixed(2)}</span>
                            </div>

                            <div class="pasarela-col">
                                <span class="pasarela-col-label">Cantidad</span>
                                <select class="pasarela-qty-select" onchange="updatePasarelaQty(${item.id}, this.value)">
                                    ${[1,2,3,4,5,6,7,8,9,10].map(q => `<option value="${q}" ${q === item.quantity ? 'selected' : ''}>${q}</option>`).join('')}
                                </select>
                            </div>

                            <div class="pasarela-col">
                                <span class="pasarela-col-label">Total</span>
                                <span class="pasarela-col-total">S/ ${itemTotal.toFixed(2)}</span>
                            </div>
                        </div>

                        <button class="pasarela-btn-close" onclick="removePasarelaItem(${item.id})" title="Eliminar producto">
                            X
                        </button>
                    </div>
                `;
            }).join('');

            document.getElementById('resumen-subtotal-val').textContent = `S/ ${subtotal.toFixed(2)}`;
            document.getElementById('resumen-total-val').textContent = `S/ ${subtotal.toFixed(2)}`;
            const btnFinal = document.getElementById('btn-final-total-text');
            if (btnFinal) btnFinal.textContent = `S/ ${subtotal.toFixed(2)}`;
        }

        function updatePasarelaQty(itemId, newQty) {
            let items = getPasarelaCartItems();
            const idx = items.findIndex(i => i.id === itemId);
            if (idx !== -1) {
                items[idx].quantity = parseInt(newQty, 10);
                localStorage.setItem('atelier_cart_items', JSON.stringify(items));
                
                const totalQty = items.reduce((acc, i) => acc + i.quantity, 0);
                localStorage.setItem('atelier_cart_qty', String(totalQty));
                
                renderPasarelaItems();
            }
        }

        function removePasarelaItem(itemId) {
            let items = getPasarelaCartItems();
            items = items.filter(i => i.id !== itemId);
            localStorage.setItem('atelier_cart_items', JSON.stringify(items));
            
            const totalQty = items.reduce((acc, i) => acc + i.quantity, 0);
            localStorage.setItem('atelier_cart_qty', String(totalQty));
            
            renderPasarelaItems();
        }

        let currentStepNum = 1;
        function switchPasarelaStep(stepNum) {
            currentStepNum = stepNum;
            const stepItems = document.querySelectorAll('.pasarela-step-item[data-step]');
            stepItems.forEach(item => {
                const n = parseInt(item.getAttribute('data-step'), 10);
                if (n === stepNum) {
                    item.classList.add('active');
                } else {
                    item.classList.remove('active');
                }
            });

            const stepViews = document.querySelectorAll('.pasarela-step-view');
            stepViews.forEach(v => v.classList.remove('active'));
            const targetView = document.getElementById(`step-view-${stepNum}`);
            if (targetView) targetView.classList.add('active');

            const resumenBtn = document.getElementById('resumen-main-btn');
            if (resumenBtn) {
                if (stepNum === 1) {
                    resumenBtn.textContent = "Ir a comprar";
                    resumenBtn.onclick = () => switchPasarelaStep(2);
                    resumenBtn.style.display = 'block';
                } else if (stepNum === 2) {
                    resumenBtn.textContent = "Siguiente: Forma de entrega";
                    resumenBtn.onclick = () => switchPasarelaStep(3);
                    resumenBtn.style.display = 'block';
                } else if (stepNum === 3) {
                    resumenBtn.textContent = "Siguiente: Métodos de pago";
                    resumenBtn.onclick = () => switchPasarelaStep(4);
                    resumenBtn.style.display = 'block';
                } else if (stepNum === 4) {
                    resumenBtn.style.display = 'none';
                }
            }

            window.scrollTo({ top: 120, behavior: 'smooth' });
        }

        document.querySelectorAll('.pasarela-step-item[data-step]').forEach(tab => {
            tab.addEventListener('click', () => {
                const step = parseInt(tab.getAttribute('data-step'), 10);
                switchPasarelaStep(step);
            });
        });

        function selectDeliveryOption(cardEl, extraCost) {
            document.querySelectorAll('.payment-options-grid .payment-option-card').forEach(c => {
                if (c.parentElement === cardEl.parentElement) c.classList.remove('selected');
            });
            cardEl.classList.add('selected');
        }

        function selectPaymentMethod(cardEl, type) {
            document.querySelectorAll('#step-view-4 .payment-option-card').forEach(c => c.classList.remove('selected'));
            cardEl.classList.add('selected');

            const tarjetaBox = document.getElementById('pago-tarjeta-box');
            if (tarjetaBox) {
                tarjetaBox.style.display = (type === 'tarjeta') ? 'block' : 'none';
            }
        }

        function finalizarPedidoAtelier() {
            const modal = document.getElementById('modal-pedido-exitoso');
            if (modal) {
                modal.style.display = 'flex';
                localStorage.removeItem('atelier_cart_items');
                localStorage.setItem('atelier_cart_qty', '0');
            }
        }
    </script>
</body>
</html>
