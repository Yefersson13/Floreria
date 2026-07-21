document.addEventListener('DOMContentLoaded', () => {

    // 0. Detectar si estamos en la landing pública (index.html).
    // En esta página NO existe carrito de compras: los botones de compra
    // deben invitar a iniciar sesión o registrarse (el carrito real vive en pagina.php).
    const currentPath = window.location.pathname.toLowerCase();
    const isIndexLanding = currentPath.endsWith('index.html') || currentPath === '/' || currentPath.endsWith('/');

    // Páginas de fechas especiales (Día de la Madre / Padre / Maestro): igual que en index.html,
    // NO tienen carrito propio. El ícono de compras permanece oculto y los botones de compra
    // invitan a iniciar sesión o registrarse.
    const specialDatePages = ['dia_de_la_madre', 'dia_del_padre', 'dia_del_maestro'];
    const isSpecialDatePage = specialDatePages.some(p => currentPath.includes(p));
    const isLoginGatedPage = isIndexLanding || isSpecialDatePage;

    // Determina si hay un usuario con sesión iniciada (LocalDB offline o sesión PHP reflejada en localStorage)
    function isAtelierUserLoggedIn() {
        const u = localStorage.getItem('atelier_current_user');
        return !!(u && u.trim() !== '' && u.trim() !== 'undefined' && u.trim() !== 'null' && u.trim() !== 'Carlos');
    }

    // 1. Mobile Menu Toggle (Anclado exactamente debajo del icono |||)
    const hamburger = document.querySelector('.hamburger');
    const mobileMenu = document.getElementById('mobileMenuDropdown');

    if (hamburger && mobileMenu) {
        const updateMenuPosition = () => {
            const rect = hamburger.getBoundingClientRect();
            mobileMenu.style.position = 'fixed';
            mobileMenu.style.top = (rect.bottom + 15) + 'px';
            mobileMenu.style.left = Math.max(10, rect.left - 10) + 'px';
        };

        hamburger.addEventListener('click', (e) => {
            e.stopPropagation();
            if (!mobileMenu.classList.contains('active')) {
                updateMenuPosition();
            }
            hamburger.classList.toggle('active');
            mobileMenu.classList.toggle('active');
        });

        window.addEventListener('resize', () => {
            if (mobileMenu.classList.contains('active')) {
                updateMenuPosition();
            }
        });

        // Close menu when clicking outside
        document.addEventListener('click', (e) => {
            if (!mobileMenu.contains(e.target) && !hamburger.contains(e.target)) {
                hamburger.classList.remove('active');
                mobileMenu.classList.remove('active');
            }
        });

        // Close menu when clicking a link inside it
        const menuLinks = mobileMenu.querySelectorAll('a');
        menuLinks.forEach(link => {
            link.addEventListener('click', () => {
                hamburger.classList.remove('active');
                mobileMenu.classList.remove('active');
            });
        });
    }

    // 2. Navbar Scroll
    const navbar = document.getElementById('navbar');
    if (navbar) {
        window.addEventListener('scroll', () => {
            if (window.scrollY > 50) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }
        });
    }

    // 3. Animación inicial
    setTimeout(() => {
        const heroElements = document.querySelectorAll('.animate-fade-up');
        heroElements.forEach(el => el.classList.add('active'));
    }, 100);

    // 4. Scroll Animations
    const observerOptions = {
        root: null,
        rootMargin: '0px',
        threshold: 0.15
    };

    const scrollObserver = new IntersectionObserver((entries, observer) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('appear');
                observer.unobserve(entry.target);
            }
        });
    }, observerOptions);

    const fadeElements = document.querySelectorAll('.fade-in');
    fadeElements.forEach(el => scrollObserver.observe(el));

    // 5. Formulario contacto
    const contactForm = document.getElementById('contactForm');
    if (contactForm) {
        contactForm.addEventListener('submit', (e) => {
            e.preventDefault();
            const btn = contactForm.querySelector('button[type="submit"]');
            const originalText = btn.innerText;
            
            btn.innerText = 'Enviando...';
            btn.style.opacity = '0.7';

            setTimeout(() => {
                contactForm.reset();
                btn.innerText = '¡Mensaje Enviado!';
                btn.style.backgroundColor = 'var(--primary-dark)';
                btn.style.opacity = '1';
                
                setTimeout(() => {
                    btn.innerText = originalText;
                    btn.style.backgroundColor = '';
                }, 3000);
            }, 1500);
        });
    }


    // 6. Carrusel Promocional "Para: MAMÁ"
    const promoSlider = document.querySelector('.promo-slider');
    const prevBtn = document.getElementById('prev-promo');
    const nextBtn = document.getElementById('next-promo');
    
    if (promoSlider && prevBtn && nextBtn) {
        const cards = document.querySelectorAll('.promo-slider .slider-card');
        let currentIndex = 0;
        
        function updateSlider() {
            if (window.innerWidth <= 991) {
                cards.forEach((card, idx) => {
                    if (idx === currentIndex) {
                        card.style.display = 'block';
                    } else {
                        card.style.display = 'none';
                    }
                });
            } else {
                cards.forEach(card => {
                    card.style.display = 'block';
                });
            }
        }
        
        prevBtn.addEventListener('click', () => {
            if (currentIndex > 0) {
                currentIndex--;
            } else {
                currentIndex = cards.length - 1;
            }
            updateSlider();
        });
        
        nextBtn.addEventListener('click', () => {
            if (currentIndex < cards.length - 1) {
                currentIndex++;
            } else {
                currentIndex = 0;
            }
            updateSlider();
        });
        
        window.addEventListener('resize', updateSlider);
        updateSlider(); // Inicializar
    }

    // 7. Modales de Formularios de Autenticación
    const botones = document.querySelectorAll(".btnComprar");
    const userIcon = document.getElementById("user-icon");
    const formOverlays = document.querySelectorAll(".form-overlay-container");
    const formulario = document.querySelector(".Inicio");
    const formularioin = document.querySelector(".Inicio-F");
    const closeFormBtns = document.querySelectorAll(".close-form-btn");

    function openRegisterForm() {
        closeAllForms();
        const parentOverlay = formulario.closest(".form-overlay-container");
        if (parentOverlay) {
            parentOverlay.style.display = "flex";
        }
        formulario.style.display = "block";
        formulario.classList.remove("activo");
        void formulario.offsetWidth;
        formulario.classList.add("activo");
    }

    function openLoginForm() {
        closeAllForms();
        const parentOverlay = formularioin.closest(".form-overlay-container");
        if (parentOverlay) {
            parentOverlay.style.display = "flex";
        }
        formularioin.style.display = "block";
        formularioin.classList.remove("activo");
        void formularioin.offsetWidth;
        formularioin.classList.add("activo");
    }

    function closeAllForms() {
        formOverlays.forEach(overlay => overlay.style.display = "none");
        formulario.style.display = "none";
        formularioin.style.display = "none";
    }

    // =========================================================================
    // ATELIER SIDE DRAWER CART & FLOATING CART MANAGER (PANEL LATERAL Y CARRO FLOTANTE)
    // =========================================================================
    window.atelierSyncCartBadges = function() {
        let cartItems = JSON.parse(localStorage.getItem('atelier_cart_items') || '[]');
        let totalQty = cartItems.reduce((acc, item) => acc + (parseInt(item.quantity, 10) || 1), 0);
        localStorage.setItem('atelier_cart_qty', String(totalQty));

        const topCartBadge = document.getElementById('cart-badge');
        if (topCartBadge) {
            topCartBadge.textContent = totalQty;
            topCartBadge.style.display = totalQty > 0 ? 'flex' : 'none';
        }

        const floatingBadge = document.getElementById('floating-cart-badge');
        if (floatingBadge) {
            floatingBadge.textContent = totalQty;
            floatingBadge.classList.toggle('visible', totalQty > 0);
        }
    };

    window.atelierAddToCart = function(name, priceStr, img, subtitle = "Colección Exclusiva", qty = 1) {
        if (isLoginGatedPage) {
            // En index.html y en las páginas de fechas especiales no existe carrito propio:
            // cualquier intento de agregar productos (venga de donde venga) invita a iniciar sesión/registrarse.
            openLoginForm();
            showLuxuryToast('🔒 <span>Inicia sesión o regístrate para agregar productos a tu carrito</span>');
            return;
        }
        let cartItems = JSON.parse(localStorage.getItem('atelier_cart_items') || '[]');
        let priceNum = typeof priceStr === 'number' ? priceStr : parseFloat(String(priceStr).replace(/[^\d.]/g, '')) || 149.90;
        
        let existing = cartItems.find(item => item.name === name);
        if (existing) {
            existing.quantity = (parseInt(existing.quantity, 10) || 1) + qty;
        } else {
            cartItems.push({
                id: Date.now(),
                name: name,
                subtitle: subtitle,
                price: priceNum,
                img: img || 'https://images.unsplash.com/photo-1561181286-d3fee7d55364?auto=format&fit=crop&w=600&q=80',
                quantity: qty
            });
        }
        localStorage.setItem('atelier_cart_items', JSON.stringify(cartItems));
        window.atelierSyncCartBadges();

        const floatingCart = document.getElementById('floating-cart');
        if (floatingCart) {
            floatingCart.classList.remove('pop');
            void floatingCart.offsetWidth;
            floatingCart.classList.add('pop');
        }

        if (document.getElementById('atelier-side-cart-drawer')?.classList.contains('open')) {
            window.atelierRenderSideCart();
        }
    };

    window.atelierEnsureSideCartDrawer = function() {
        if (document.getElementById('atelier-side-cart-drawer')) return;

        const backdrop = document.createElement('div');
        backdrop.className = 'atelier-cart-backdrop';
        backdrop.id = 'atelier-side-cart-backdrop';

        const drawer = document.createElement('div');
        drawer.className = 'atelier-side-drawer';
        drawer.id = 'atelier-side-cart-drawer';
        drawer.innerHTML = `
            <div class="atelier-drawer-header">
                <h3><i class="fa-solid fa-bag-shopping"></i> Tu Carrito (<span id="atelier-drawer-count">0</span>)</h3>
                <button class="atelier-drawer-close" id="atelier-drawer-close-btn" title="Cerrar"><i class="fa-solid fa-xmark"></i></button>
            </div>
            <div class="atelier-drawer-body" id="atelier-drawer-items-list"></div>
            <div class="atelier-drawer-footer">
                <div class="atelier-drawer-total-row">
                    <span>Subtotal / Total:</span>
                    <span class="atelier-drawer-total-amount" id="atelier-drawer-total">S/ 0.00</span>
                </div>
                <button class="atelier-drawer-btn-checkout" id="atelier-drawer-checkout-btn">
                    Ir a la Pasarela de Compra <i class="fa-solid fa-arrow-right"></i>
                </button>
                <button class="atelier-drawer-btn-continue" id="atelier-drawer-continue-btn">
                    Seguir Explorando
                </button>
            </div>
        `;

        document.body.appendChild(backdrop);
        document.body.appendChild(drawer);

        // Eventos de cierre y checkout del drawer
        document.getElementById('atelier-drawer-close-btn')?.addEventListener('click', window.closeAtelierSideCart);
        document.getElementById('atelier-drawer-continue-btn')?.addEventListener('click', window.closeAtelierSideCart);
        backdrop.addEventListener('click', window.closeAtelierSideCart);

        document.getElementById('atelier-drawer-checkout-btn')?.addEventListener('click', () => {
            const isPHP = window.location.pathname.endsWith('.php');
            window.location.href = isPHP ? 'pasarela.php' : 'pasarela.html';
        });
    };

    window.atelierRenderSideCart = function() {
        window.atelierEnsureSideCartDrawer();
        let cartItems = JSON.parse(localStorage.getItem('atelier_cart_items') || '[]');
        const listEl = document.getElementById('atelier-drawer-items-list');
        const totalEl = document.getElementById('atelier-drawer-total');
        const countEl = document.getElementById('atelier-drawer-count');

        let totalQty = cartItems.reduce((acc, item) => acc + (parseInt(item.quantity, 10) || 1), 0);
        if (countEl) countEl.textContent = totalQty;

        if (cartItems.length === 0) {
            listEl.innerHTML = `
                <div class="atelier-drawer-empty">
                    <i class="fa-solid fa-bag-shopping"></i>
                    <h4>Tu carrito está vacío</h4>
                    <p>Descubre nuestros exclusivos arreglos florales y añade tus favoritos aquí.</p>
                </div>
            `;
            if (totalEl) totalEl.textContent = 'S/ 0.00';
            return;
        }

        let totalAmount = 0;
        let html = '';
        cartItems.forEach(item => {
            let qty = parseInt(item.quantity, 10) || 1;
            let price = parseFloat(item.price) || 149.90;
            totalAmount += (qty * price);

            html += `
                <div class="atelier-drawer-item">
                    <img src="${item.img}" class="atelier-drawer-item-img" alt="${item.name}">
                    <div class="atelier-drawer-item-info">
                        <h4>${item.name}</h4>
                        <div class="atelier-drawer-item-price">S/ ${price.toFixed(2)}</div>
                        <div class="atelier-drawer-qty-box">
                            <button class="atelier-drawer-qty-btn drawer-minus" data-id="${item.id}">-</button>
                            <span>${qty}</span>
                            <button class="atelier-drawer-qty-btn drawer-plus" data-id="${item.id}">+</button>
                        </div>
                    </div>
                    <button class="atelier-drawer-item-del drawer-del" data-id="${item.id}" title="Eliminar del carrito">
                        <i class="fa-solid fa-trash-can"></i>
                    </button>
                </div>
            `;
        });

        listEl.innerHTML = html;
        if (totalEl) totalEl.textContent = `S/ ${totalAmount.toFixed(2)}`;

        // Eventos de botones + / - / del dentro del panel lateral
        listEl.querySelectorAll('.drawer-plus').forEach(btn => {
            btn.addEventListener('click', () => {
                const id = Number(btn.getAttribute('data-id'));
                let items = JSON.parse(localStorage.getItem('atelier_cart_items') || '[]');
                let item = items.find(x => x.id === id);
                if (item) {
                    item.quantity = (parseInt(item.quantity, 10) || 1) + 1;
                    localStorage.setItem('atelier_cart_items', JSON.stringify(items));
                    window.atelierSyncCartBadges();
                    window.atelierRenderSideCart();
                }
            });
        });

        listEl.querySelectorAll('.drawer-minus').forEach(btn => {
            btn.addEventListener('click', () => {
                const id = Number(btn.getAttribute('data-id'));
                let items = JSON.parse(localStorage.getItem('atelier_cart_items') || '[]');
                let idx = items.findIndex(x => x.id === id);
                if (idx !== -1) {
                    if (items[idx].quantity > 1) {
                        items[idx].quantity--;
                    } else {
                        items.splice(idx, 1);
                    }
                    localStorage.setItem('atelier_cart_items', JSON.stringify(items));
                    window.atelierSyncCartBadges();
                    window.atelierRenderSideCart();
                }
            });
        });

        listEl.querySelectorAll('.drawer-del').forEach(btn => {
            btn.addEventListener('click', () => {
                const id = Number(btn.getAttribute('data-id'));
                let items = JSON.parse(localStorage.getItem('atelier_cart_items') || '[]');
                items = items.filter(x => x.id !== id);
                localStorage.setItem('atelier_cart_items', JSON.stringify(items));
                window.atelierSyncCartBadges();
                window.atelierRenderSideCart();
            });
        });
    };

    window.openAtelierSideCart = function() {
        window.atelierRenderSideCart();
        const backdrop = document.getElementById('atelier-side-cart-backdrop');
        const drawer = document.getElementById('atelier-side-cart-drawer');
        if (backdrop && drawer) {
            backdrop.classList.add('active');
            drawer.classList.add('open');
        }
    };

    window.closeAtelierSideCart = function() {
        const backdrop = document.getElementById('atelier-side-cart-backdrop');
        const drawer = document.getElementById('atelier-side-cart-drawer');
        if (backdrop && drawer) {
            backdrop.classList.remove('active');
            drawer.classList.remove('open');
        }
    };

    // =========================================================================
    // ATELIER SIDE DRAWER WISHLIST MANAGER (PANEL LATERAL DERECHO DE FAVORITOS)
    // =========================================================================
    window.atelierEnsureSideWishlistDrawer = function() {
        if (document.getElementById('atelier-side-wishlist-drawer')) return;

        const backdrop = document.createElement('div');
        backdrop.className = 'atelier-cart-backdrop';
        backdrop.id = 'atelier-side-wishlist-backdrop';

        const drawer = document.createElement('div');
        drawer.className = 'atelier-side-drawer wishlist-drawer';
        drawer.id = 'atelier-side-wishlist-drawer';
        drawer.innerHTML = `
            <div class="atelier-drawer-header wishlist-header">
                <h3><i class="fa-solid fa-heart" style="color: #D32F2F;"></i> Mis Favoritos (<span id="atelier-wishlist-count">0</span>)</h3>
                <button class="atelier-drawer-close" id="atelier-wishlist-close-btn" title="Cerrar"><i class="fa-solid fa-xmark"></i></button>
            </div>
            <div class="atelier-drawer-body" id="atelier-wishlist-items-list"></div>
            <div class="atelier-drawer-footer">
                <button class="atelier-drawer-btn-checkout btn-add-all-favs" id="atelier-wishlist-add-all-btn">
                    <i class="fa-solid fa-bag-shopping"></i> Mover todos al Carrito
                </button>
                <button class="atelier-drawer-btn-continue" id="atelier-wishlist-continue-btn">
                    Seguir Explorando
                </button>
            </div>
        `;

        document.body.appendChild(backdrop);
        document.body.appendChild(drawer);

        document.getElementById('atelier-wishlist-close-btn')?.addEventListener('click', window.closeAtelierSideWishlist);
        document.getElementById('atelier-wishlist-continue-btn')?.addEventListener('click', window.closeAtelierSideWishlist);
        backdrop.addEventListener('click', window.closeAtelierSideWishlist);

        document.getElementById('atelier-wishlist-add-all-btn')?.addEventListener('click', () => {
            let favItems = JSON.parse(localStorage.getItem('atelier_fav_items') || '[]');
            if (favItems.length === 0) return;
            favItems.forEach(item => {
                window.atelierAddToCart(item.name, parseFloat(item.price || 149.90), item.img, item.subtitle || "Colección Exclusiva", 1);
            });
            showLuxuryToast('🛍️ ¡Todos tus favoritos se añadieron al carrito!');
            window.closeAtelierSideWishlist();
            setTimeout(() => {
                window.openAtelierSideCart();
            }, 300);
        });
    };

    window.atelierRenderSideWishlist = function() {
        window.atelierEnsureSideWishlistDrawer();
        let favItems = JSON.parse(localStorage.getItem('atelier_fav_items') || '[]');
        const listEl = document.getElementById('atelier-wishlist-items-list');
        const countEl = document.getElementById('atelier-wishlist-count');
        if (countEl) countEl.textContent = favItems.length;

        const addAllBtn = document.getElementById('atelier-wishlist-add-all-btn');
        if (addAllBtn) addAllBtn.style.display = favItems.length > 0 ? 'flex' : 'none';

        if (favItems.length === 0) {
            listEl.innerHTML = `
                <div class="atelier-drawer-empty">
                    <i class="fa-regular fa-heart" style="color: #CBD5E1;"></i>
                    <h4>Tu lista de favoritos está vacía</h4>
                    <p>Explora nuestras exclusivas colecciones florales y pulsa el corazón (♡) para guardarlas aquí.</p>
                </div>
            `;
            return;
        }

        let html = '';
        favItems.forEach(item => {
            let price = parseFloat(item.price) || 149.90;
            html += `
                <div class="atelier-drawer-item">
                    <img src="${item.img}" class="atelier-drawer-item-img" alt="${item.name}">
                    <div class="atelier-drawer-item-info">
                        <h4>${item.name}</h4>
                        <div class="atelier-drawer-item-price">S/ ${price.toFixed(2)}</div>
                        <button class="btn-fav-to-cart" data-name="${item.name}" data-price="${price}" data-img="${item.img}" data-sub="${item.subtitle || 'Colección Exclusiva'}">
                            <i class="fa-solid fa-cart-plus"></i> Añadir al Carrito
                        </button>
                    </div>
                    <button class="atelier-drawer-item-del wishlist-drawer-del" data-name="${item.name}" title="Eliminar de favoritos">
                        <i class="fa-solid fa-trash-can"></i>
                    </button>
                </div>
            `;
        });

        listEl.innerHTML = html;

        listEl.querySelectorAll('.btn-fav-to-cart').forEach(btn => {
            btn.addEventListener('click', () => {
                const name = btn.getAttribute('data-name');
                const price = parseFloat(btn.getAttribute('data-price')) || 149.90;
                const img = btn.getAttribute('data-img');
                const sub = btn.getAttribute('data-sub');
                window.atelierAddToCart(name, price, img, sub, 1);
                showLuxuryToast('🛍️ ¡Añadido <strong>' + name + '</strong> al carrito!');
            });
        });

        listEl.querySelectorAll('.wishlist-drawer-del').forEach(btn => {
            btn.addEventListener('click', () => {
                const name = btn.getAttribute('data-name');
                let items = JSON.parse(localStorage.getItem('atelier_fav_items') || '[]');
                items = items.filter(x => x.name !== name);
                localStorage.setItem('atelier_fav_items', JSON.stringify(items));
                localStorage.removeItem('atelier_fav_' + name);

                document.querySelectorAll('.wishlist-btn').forEach(wBtn => {
                    const card = wBtn.closest('.product-card, .slider-card');
                    if (card && card.querySelector('h3')?.textContent.trim() === name) {
                        wBtn.classList.remove('active');
                        const icon = wBtn.querySelector('i');
                        if (icon) {
                            icon.classList.remove('fa-solid');
                            icon.classList.add('fa-regular');
                        }
                    }
                });

                window.atelierSyncFavBadges();
                window.atelierRenderSideWishlist();
                showLuxuryToast('🤍 Se eliminó <strong>' + name + '</strong> de favoritos.');
            });
        });
    };

    window.openAtelierSideWishlist = function() {
        window.atelierRenderSideWishlist();
        const backdrop = document.getElementById('atelier-side-wishlist-backdrop');
        const drawer = document.getElementById('atelier-side-wishlist-drawer');
        if (backdrop && drawer) {
            backdrop.classList.add('active');
            drawer.classList.add('open');
        }
    };

    window.closeAtelierSideWishlist = function() {
        const backdrop = document.getElementById('atelier-side-wishlist-backdrop');
        const drawer = document.getElementById('atelier-side-wishlist-drawer');
        if (backdrop && drawer) {
            backdrop.classList.remove('active');
            drawer.classList.remove('open');
        }
    };

    window.atelierSyncFavBadges = function() {
        let favItems = JSON.parse(localStorage.getItem('atelier_fav_items') || '[]');
        let totalFavs = favItems.length;

        const heartBadgeEl = document.getElementById('heart-badge');
        if (heartBadgeEl) {
            heartBadgeEl.textContent = totalFavs;
            if (totalFavs > 0) {
                heartBadgeEl.style.display = 'flex';
                heartBadgeEl.classList.remove('pulse');
                void heartBadgeEl.offsetWidth;
                heartBadgeEl.classList.add('pulse');
            } else {
                heartBadgeEl.style.display = 'none';
            }
        }

        if (document.getElementById('atelier-side-wishlist-drawer')?.classList.contains('open')) {
            window.atelierRenderSideWishlist();
        }
    };

    if (isLoginGatedPage) {
        // En index.html y en las páginas de fechas especiales NO hay carrito de compras:
        // los botones "Agregar"/"Comprar" invitan a iniciar sesión o registrarse.
        // El carrito real vive en pagina.php.
        botones.forEach(boton => {
            boton.addEventListener('click', (e) => {
                e.preventDefault();
                e.stopPropagation();
                openLoginForm();
                showLuxuryToast('🔒 <span>Inicia sesión o regístrate para agregar productos a tu carrito</span>');
            });
        });
    } else if (!window.location.pathname.toLowerCase().includes('pasarela')) {
        // Asegurar que exista el botón circular en la parte inferior derecha (excepto pasarela e index)
        let floatingCart = document.getElementById('floating-cart');
        if (!floatingCart) {
            floatingCart = document.createElement('div');
            floatingCart.className = 'floating-cart';
            floatingCart.id = 'floating-cart';
            floatingCart.innerHTML = `
                <i class="fa-solid fa-bag-shopping"></i>
                <span class="floating-cart-badge" id="floating-cart-badge">0</span>
            `;
            document.body.appendChild(floatingCart);
        }

        // Conectar evento de clic al botón flotante para abrir el panel lateral derecho del carrito
        floatingCart.addEventListener('click', (e) => {
            e.preventDefault();
            window.openAtelierSideCart();
        });

        // Conectar adición rápida de productos para todas las tarjetas ("Agregar al Carrito")
        botones.forEach(boton => {
            boton.addEventListener('click', (e) => {
                e.preventDefault();
                e.stopPropagation();

                const card = boton.closest('.product-card, .slider-card');
                let prodName = "Arreglo Floral Exclusivo";
                let prodPrice = 149.90;
                let prodImg = 'https://images.unsplash.com/photo-1561181286-d3fee7d55364?auto=format&fit=crop&w=600&q=80';
                let prodSubtitle = "Colección Exclusiva";

                if (card) {
                    const titleEl = card.querySelector('h3');
                    if (titleEl) prodName = titleEl.textContent.trim();

                    const priceEl = card.querySelector('.promo-price') || card.querySelector('.price');
                    if (priceEl) prodPrice = parseFloat(priceEl.textContent.replace(/[^\d.]/g, '')) || 149.90;

                    const imgEl = card.querySelector('img.product-img, img.slider-img, img');
                    if (imgEl && imgEl.src) prodImg = imgEl.src;

                    const subEl = card.querySelector('.badge');
                    if (subEl) prodSubtitle = subEl.textContent.trim();
                }

                window.atelierAddToCart(prodName, prodPrice, prodImg, prodSubtitle, 1);
                showLuxuryToast('🛍️ <span>¡Se añadió <strong>' + prodName + '</strong> a tu carrito!</span>');
            });
        });
    }

    // Sincronizar conteo en badges al cargar
    window.atelierSyncCartBadges();

    // Iniciar Sesión o Cerrar Sesión desde el Icono de la cabecera
    if (userIcon) {
        userIcon.addEventListener("click", (e) => {
            e.preventDefault();
            const logoutBox = document.querySelector(".opcion_sesion");
            const isLoggedIn = userIcon.classList.contains("nav-user-logged") || userIcon.classList.contains("cerrar_ses") || (localStorage.getItem('atelier_current_user') && localStorage.getItem('atelier_current_user').trim() !== '');
            if (isLoggedIn) {
                if (logoutBox) {
                    logoutBox.style.display = "flex";
                    logoutBox.classList.remove("active");
                    void logoutBox.offsetWidth;
                    logoutBox.classList.add("active");
                }
            } else {
                openLoginForm();
            }
        });
    }

    // Cerrar modales con la X
    closeFormBtns.forEach(btn => {
        btn.addEventListener("click", closeAllForms);
    });

    // Cerrar al clickear fuera del recuadro del formulario
    formOverlays.forEach(overlay => {
        overlay.addEventListener("click", (e) => {
            if (e.target === overlay) {
                closeAllForms();
            }
        });
    });

    // Enlace de "Iniciar Sesión" dentro del formulario de registro
    const btnRegist = document.getElementById("regist");
    if (btnRegist) {
        btnRegist.addEventListener("click", (e) => {
            e.preventDefault();
            openLoginForm();
        });
    }

    // Enlace de "Registrarse" dentro del formulario de login
    const botones2 = document.querySelectorAll(".btn-inicio");
    botones2.forEach(boton2 => {
        boton2.addEventListener("click", (e) => {
            e.preventDefault();
            openRegisterForm();
        });
    });

    // Cuadro de confirmación de logout
    const cerrar_ses = document.querySelector(".cerrar_ses");
    const logoutBox = document.querySelector(".opcion_sesion");
    const cancelLogoutBtn = document.querySelector(".btn_cerSi");
    const confirmLogoutBtns = document.querySelectorAll(".btn_cerNo");

    if (cerrar_ses && logoutBox && cerrar_ses !== userIcon) {
        cerrar_ses.addEventListener("click", (e) => {
            e.preventDefault();
            logoutBox.style.display = "flex";
            logoutBox.classList.remove("active");
            void logoutBox.offsetWidth;
            logoutBox.classList.add("active");
        });
    }

    if (cancelLogoutBtn && logoutBox) {
        cancelLogoutBtn.addEventListener("click", (e) => {
            e.preventDefault();
            logoutBox.style.display = "none";
            logoutBox.classList.remove("active");
        });
    }

    confirmLogoutBtns.forEach(btn => {
        btn.addEventListener("click", (e) => {
            if (typeof window.atelierLogout === 'function') {
                window.atelierLogout(e);
            } else {
                localStorage.removeItem('atelier_current_user');
                localStorage.removeItem('atelier_user_logged');
                sessionStorage.clear();
                const path = window.location.pathname.toLowerCase();
                const logoutUrl = path.includes('/pages/') ? '../php/logout.php' : 'php/logout.php';
                const redirectUrl = path.includes('/pages/') ? '../index.html' : 'index.html';
                if (window.location.protocol.startsWith('http')) {
                    fetch(logoutUrl, { method: 'GET' }).catch(() => {}).finally(() => window.location.href = redirectUrl);
                } else {
                    window.location.href = redirectUrl;
                }
            }
        });
    });

    /* ==========================================================================
       8. Lógica Interactiva WOW: Wishlist ("Me Encanta") & Modal Detalles
       ========================================================================== */

    // Base de datos de detalles de productos para el modal premium
    const productCatalogDB = {
        "Cesto Rosas": {
            desc: "Cesto elegante artesanal cargado de frescas rosas importadas en tonos lilas y blancos, acompañadas de follaje selecto de eucalipto baby. Un detalle delicado y cautivador que expresa ternura, admiración profunda y gratitud eterna.",
            badge: "💖 Edición Exclusiva",
            features: ["✨ Rosas 100% Frescas", "🌿 Base de Cesto Tejido", "🚚 Envío Express Dispon."],
            thumbs: ["cesto_lilas.png", "product1.png", "product2.png", "product3.png"]
        },
        "Cesto Lilas": {
            desc: "Cesto elegante artesanal cargado de frescas rosas importadas en tonos lilas y blancos, acompañadas de follaje selecto de eucalipto baby. Un detalle delicado y cautivador que expresa ternura, admiración profunda y gratitud eterna.",
            badge: "💖 Edición Exclusiva",
            features: ["✨ Rosas 100% Frescas", "🌿 Base de Cesto Tejido", "🚚 Envío Express Dispon."],
            thumbs: ["cesto_lilas.png", "product1.png", "product2.png", "product3.png"]
        },
        "Florero Encanto": {
            desc: "Espectacular arreglo floral presentado en un florero de vidrio decorativo de alto grosor. Combina armoniosamente rosas importadas en tonos lilas, blancas y acentos verdes de temporada para iluminar cualquier estancia.",
            badge: "⭐ Top Ventas",
            features: ["✨ Florero de Vidrio Incl.", "🌸 Aroma Duradero", "🎀 Lazo Satinado Premium"],
            thumbs: ["florero_encanto.png", "product2.png", "product3.png", "product1.png"]
        },
        "Box Rosas y Claveles": {
            desc: "Hermosa caja redonda de lujo estilo boutique europea con un exuberante ramillete de rosas rosadas y claveles frescos seleccionados a mano. Coronada con un elegante lazo de seda satinada.",
            badge: "🎁 Regalo Ideal",
            features: ["✨ Caja Sombrerera Rosa", "🌸 Claveles & Rosas Selectas", "💳 Tarjeta Dedicatoria Gratis"],
            thumbs: ["box_rosas_claveles.png", "tulipanes_caja.png", "product1.png", "product3.png"]
        },
        "Ramo Coreano Lilas": {
            desc: "Moderno ramo envuelto en papel coreano impermeable multitono, con una selección premium de rosas blancas y lilas. El estilo en tendencia que combina sofisticación juvenil con elegancia clásica.",
            badge: "🔥 En Tendencia",
            features: ["✨ Envoltura Coreana De Lujo", "💐 Flores de Corte del Día", "🌿 Conservante Floral Incl."],
            thumbs: ["ramo_coreano_lilas.png", "product3.png", "product1.png", "product2.png"]
        }
    };

    // Función auxiliar para obtener la ruta relativa correcta para las imágenes según la página actual
    function resolveImgPath(sampleSrc, fileName) {
        if (!sampleSrc) return fileName;
        if (sampleSrc.startsWith('../')) {
            return '../assets/images/' + fileName;
        } else if (sampleSrc.startsWith('./')) {
            return './assets/images/' + fileName;
        } else if (sampleSrc.startsWith('assets/')) {
            return 'assets/images/' + fileName;
        }
        return fileName;
    }

    // Sistema de Notificaciones Toast Premium
    let toastTimeout;
    function showLuxuryToast(message) {
        let toastEl = document.getElementById('atelier-toast-el');
        if (!toastEl) {
            toastEl = document.createElement('div');
            toastEl.id = 'atelier-toast-el';
            toastEl.className = 'atelier-toast';
            document.body.appendChild(toastEl);
        }
        toastEl.innerHTML = message;
        toastEl.classList.add('show');
        
        clearTimeout(toastTimeout);
        toastTimeout = setTimeout(() => {
            toastEl.classList.remove('show');
        }, 3200);
    }

    // Asegurar badges en navbar
    const navHeart = document.getElementById('heart-icon');
    const navCart = document.getElementById('cart-icon');

    function createNavBadge(parentEl, id) {
        if (!parentEl) return null;
        parentEl.style.position = 'relative';
        let badge = parentEl.querySelector('.nav-badge');
        if (!badge) {
            badge = document.createElement('span');
            badge.className = 'nav-badge';
            badge.id = id;
            badge.textContent = '0';
            parentEl.appendChild(badge);
        }
        return badge;
    }

    const heartBadge = createNavBadge(navHeart, 'heart-badge');
    const cartBadge = createNavBadge(navCart, 'cart-badge');

    // En las páginas de fechas especiales el ícono de compras permanece oculto (igual que en
    // index.html) mientras no haya una sesión iniciada; se muestra automáticamente al loguearse.
    function updateCartIconVisibility() {
        if (!navCart) return;
        if (isSpecialDatePage) {
            navCart.style.display = isAtelierUserLoggedIn() ? 'flex' : 'none';
        }
    }
    updateCartIconVisibility();
    window.atelierUpdateCartIconVisibility = updateCartIconVisibility;

    function updateBadgeCount(badgeEl, count) {
        if (!badgeEl) return;
        badgeEl.textContent = count;
        if (count > 0) {
            badgeEl.style.display = 'flex';
            badgeEl.classList.remove('pulse');
            void badgeEl.offsetWidth;
            badgeEl.classList.add('pulse');
        } else {
            badgeEl.style.display = 'none';
        }
    }

    // Cargar y sincronizar conteo al inicio
    if (window.atelierSyncCartBadges) window.atelierSyncCartBadges();

    // Redirigir a la Pasarela o abrir panel lateral al hacer clic en el carrito superior del menú
    if (navCart) {
        navCart.addEventListener('click', (e) => {
            e.preventDefault();
            if (isLoginGatedPage) {
                openLoginForm();
                showLuxuryToast('🔒 <span>Inicia sesión o regístrate para ver tu carrito</span>');
                return;
            }
            if (typeof window.openAtelierSideCart === 'function') {
                window.openAtelierSideCart();
            } else {
                const isPHP = window.location.pathname.endsWith('.php');
                window.location.href = isPHP ? 'pasarela.php' : 'pasarela.html';
            }
        });
    }

    // Abrir panel lateral de favoritos al hacer clic en el corazón superior del menú (#heart-icon)
    if (navHeart) {
        navHeart.addEventListener('click', (e) => {
            e.preventDefault();
            if (typeof window.openAtelierSideWishlist === 'function') {
                window.openAtelierSideWishlist();
            }
        });
    }

    // --- MANEJO DE WISHLIST ("ME ENCANTA") ---
    const wishlistBtns = document.querySelectorAll('.wishlist-btn');
    let favItems = JSON.parse(localStorage.getItem('atelier_fav_items') || '[]');

    wishlistBtns.forEach(btn => {
        const card = btn.closest('.product-card, .slider-card');
        if (!card) return;
        
        const titleEl = card.querySelector('h3');
        const prodName = titleEl ? titleEl.textContent.trim() : 'Producto Florería';
        const favKey = 'atelier_fav_' + prodName;
        const icon = btn.querySelector('i');

        const priceEl = card.querySelector('.promo-price') || card.querySelector('.price');
        let rawPriceText = priceEl ? priceEl.textContent.replace(/[^\d.]/g, '') : '149.90';
        const prodPrice = parseFloat(rawPriceText) || 149.90;

        const imgEl = card.querySelector('img.product-img, img.slider-img, img');
        const prodImg = (imgEl && imgEl.src) ? imgEl.src : 'https://images.unsplash.com/photo-1561181286-d3fee7d55364?auto=format&fit=crop&w=600&q=80';

        const subEl = card.querySelector('.badge');
        const prodSubtitle = subEl ? subEl.textContent.trim() : "Colección Exclusiva";

        // Restaurar estado guardado desde favKey o atelier_fav_items
        let existsInArray = favItems.some(x => x.name === prodName);
        if (localStorage.getItem(favKey) === 'true' || existsInArray) {
            btn.classList.add('active');
            if (icon) {
                icon.classList.remove('fa-regular');
                icon.classList.add('fa-solid');
            }
            if (!existsInArray) {
                favItems.push({ id: Date.now(), name: prodName, price: prodPrice, img: prodImg, subtitle: prodSubtitle });
                localStorage.setItem('atelier_fav_items', JSON.stringify(favItems));
            }
        }

        btn.addEventListener('click', (e) => {
            e.preventDefault();
            e.stopPropagation();

            const isFav = btn.classList.toggle('active');
            if (icon) {
                if (isFav) {
                    icon.classList.remove('fa-regular');
                    icon.classList.add('fa-solid');
                } else {
                    icon.classList.remove('fa-solid');
                    icon.classList.add('fa-regular');
                }
            }

            let currentFavs = JSON.parse(localStorage.getItem('atelier_fav_items') || '[]');
            if (isFav) {
                localStorage.setItem(favKey, 'true');
                if (!currentFavs.some(x => x.name === prodName)) {
                    currentFavs.push({ id: Date.now(), name: prodName, price: prodPrice, img: prodImg, subtitle: prodSubtitle });
                    localStorage.setItem('atelier_fav_items', JSON.stringify(currentFavs));
                }
                showLuxuryToast('❤️ <span>¡<strong>' + prodName + '</strong> te encanta! Añadido a favoritos.</span>');
            } else {
                localStorage.removeItem(favKey);
                currentFavs = currentFavs.filter(x => x.name !== prodName);
                localStorage.setItem('atelier_fav_items', JSON.stringify(currentFavs));
                showLuxuryToast('🤍 <span>Se eliminó <strong>' + prodName + '</strong> de favoritos.</span>');
            }

            if (typeof window.atelierSyncFavBadges === 'function') {
                window.atelierSyncFavBadges();
            } else {
                updateBadgeCount(heartBadge, currentFavs.length);
            }
        });
    });

    if (typeof window.atelierSyncFavBadges === 'function') {
        window.atelierSyncFavBadges();
    } else {
        updateBadgeCount(heartBadge, favItems.length);
    }

    // --- MANEJO DEL MODAL DE DETALLES DEL PRODUCTO ---
    // Crear contenedor del modal en DOM
    let productModalOverlay = document.getElementById('product-modal-overlay-el');
    if (!productModalOverlay) {
        productModalOverlay = document.createElement('div');
        productModalOverlay.id = 'product-modal-overlay-el';
        productModalOverlay.className = 'product-modal-overlay';
        productModalOverlay.innerHTML = `
            <div class="product-modal-card">
                <button class="product-modal-close" aria-label="Cerrar modal">&times;</button>
                <div class="product-modal-body">
                    <div class="product-modal-gallery">
                        <div class="product-modal-main-img-box">
                            <img class="product-modal-main-img" id="pm-main-img" src="" alt="Detalle de producto">
                        </div>
                        <div class="product-modal-thumbs" id="pm-thumbs-box"></div>
                    </div>
                    <div class="product-modal-info">
                        <div class="product-modal-badge" id="pm-badge">✨ Colección Atelier</div>
                        <h2 class="product-modal-title" id="pm-title">Arreglo Floral</h2>
                        <div class="product-modal-price" id="pm-price">S/ 149.90</div>
                        <p class="product-modal-desc-label">Descripción del Diseño</p>
                        <p class="product-modal-desc" id="pm-desc"></p>
                        
                        <div class="product-modal-features" id="pm-features-box"></div>

                        <div class="product-modal-actions">
                            <div class="pm-qty-selector">
                                <button class="pm-qty-btn" id="pm-qty-dec" type="button" aria-label="Menos">-</button>
                                <input class="pm-qty-input" id="pm-qty-val" type="number" value="1" min="1" max="99" readonly>
                                <button class="pm-qty-btn" id="pm-qty-inc" type="button" aria-label="Más">+</button>
                            </div>
                            <button class="pm-add-btn" id="pm-add-btn" type="button">
                                <i class="fa-solid fa-bag-shopping"></i> Agregar al Carrito
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        `;
        document.body.appendChild(productModalOverlay);
    }

    const pmCloseBtn = productModalOverlay.querySelector('.product-modal-close');
    const pmMainImg = document.getElementById('pm-main-img');
    const pmThumbsBox = document.getElementById('pm-thumbs-box');
    const pmBadge = document.getElementById('pm-badge');
    const pmTitle = document.getElementById('pm-title');
    const pmPrice = document.getElementById('pm-price');
    const pmDesc = document.getElementById('pm-desc');
    const pmFeaturesBox = document.getElementById('pm-features-box');
    const pmQtyVal = document.getElementById('pm-qty-val');
    const pmQtyDec = document.getElementById('pm-qty-dec');
    const pmQtyInc = document.getElementById('pm-qty-inc');
    const pmAddBtn = document.getElementById('pm-add-btn');

    let activeModalProdName = '';

    function closeProductModal() {
        productModalOverlay.classList.remove('active');
    }

    pmCloseBtn.addEventListener('click', closeProductModal);
    productModalOverlay.addEventListener('click', (e) => {
        if (e.target === productModalOverlay) {
            closeProductModal();
        }
    });

    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && productModalOverlay.classList.contains('active')) {
            closeProductModal();
        }
    });

    // Control de cantidad dentro del modal
    pmQtyDec.addEventListener('click', () => {
        let v = parseInt(pmQtyVal.value || '1', 10);
        if (v > 1) pmQtyVal.value = v - 1;
    });

    pmQtyInc.addEventListener('click', () => {
        let v = parseInt(pmQtyVal.value || '1', 10);
        if (v < 99) pmQtyVal.value = v + 1;
    });

    // Botón Agregar al Carrito dentro del modal
    pmAddBtn.addEventListener('click', () => {
        if (isLoginGatedPage) {
            closeProductModal();
            openLoginForm();
            showLuxuryToast('🔒 <span>Inicia sesión o regístrate para agregar productos a tu carrito</span>');
            return;
        }
        let qty = parseInt(pmQtyVal.value || '1', 10);
        let priceNum = parseFloat(pmPrice.textContent.replace(/[^\d.]/g, '')) || 149.90;
        window.atelierAddToCart(activeModalProdName, priceNum, pmMainImg.src, pmBadge ? pmBadge.textContent : "Colección Exclusiva", qty);
        closeProductModal();
        showLuxuryToast('🛍️ <span>¡Se agregaron <strong>' + qty + ' x ' + activeModalProdName + '</strong> a tu carrito!</span>');
    });

    // Enlazar gatillos para abrir modal ("Ver detalles" y click en imágenes)
    const detailTriggers = document.querySelectorAll('.product-overlay, .ver-detalles-text, .slider-card-overlay, .product-img, .slider-img');

    detailTriggers.forEach(trigger => {
        trigger.style.cursor = 'pointer';
        trigger.addEventListener('click', (e) => {
            e.preventDefault();
            e.stopPropagation();

            const card = trigger.closest('.product-card, .slider-card');
            if (!card) return;

            const titleEl = card.querySelector('h3');
            const priceEl = card.querySelector('.price, .promo-price');
            const imgEl = card.querySelector('img.product-img, img.slider-img');

            const prodName = titleEl ? titleEl.textContent.trim() : (imgEl ? imgEl.getAttribute('alt') : 'Diseño Floral');
            let prodPrice = priceEl ? priceEl.textContent.trim() : 'S/ 149.90';
            if (!prodPrice.startsWith('S/') && !isNaN(parseFloat(prodPrice))) {
                prodPrice = 'S/ ' + prodPrice;
            }

            const currentSrc = imgEl ? imgEl.getAttribute('src') : '';
            activeModalProdName = prodName;

            // Datos de catálogo
            const dataData = productCatalogDB[prodName] || {
                desc: "Exclusivo arreglo floral artesanal elaborado con las flores más frescas importadas por Atelier. Diseñado minuciosamente para cautivar los sentidos y brindar durabilidad excepcional.",
                badge: "✨ Diseño Premium",
                features: ["✨ Calidad Premium", "🌸 Duración Prolongada", "🚚 Envío Seguro"],
                thumbs: ["cesto_lilas.png", "florero_encanto.png", "box_rosas_claveles.png", "ramo_coreano_lilas.png"]
            };

            pmTitle.textContent = prodName;
            pmPrice.textContent = prodPrice;
            pmDesc.textContent = dataData.desc;
            pmBadge.textContent = dataData.badge;
            pmQtyVal.value = '1';

            pmMainImg.src = currentSrc;

            // Renderizar características (features)
            pmFeaturesBox.innerHTML = (dataData.features || []).map(f => `<span class="product-modal-feature-pill">${f}</span>`).join('');

            // Renderizar miniaturas (thumbs)
            const resolvedThumbs = (dataData.thumbs || []).map(t => resolveImgPath(currentSrc, t));
            // Aseguramos que la imagen actual de la tarjeta sea el primer thumb
            if (currentSrc && !resolvedThumbs.includes(currentSrc)) {
                resolvedThumbs.unshift(currentSrc);
            } else if (currentSrc) {
                // Colocar la actual primero
                const idx = resolvedThumbs.indexOf(currentSrc);
                if (idx > 0) {
                    resolvedThumbs.splice(idx, 1);
                    resolvedThumbs.unshift(currentSrc);
                }
            }

            pmThumbsBox.innerHTML = resolvedThumbs.slice(0, 4).map((thSrc, i) => `
                <button class="product-modal-thumb ${i === 0 ? 'active' : ''}" type="button" data-src="${thSrc}">
                    <img src="${thSrc}" alt="${prodName} vista">
                </button>
            `).join('');

            const thumbBtns = pmThumbsBox.querySelectorAll('.product-modal-thumb');
            thumbBtns.forEach(tBtn => {
                tBtn.addEventListener('click', () => {
                    thumbBtns.forEach(b => b.classList.remove('active'));
                    tBtn.classList.add('active');
                    pmMainImg.src = tBtn.getAttribute('data-src');
                });
            });

            // Mostrar overlay
            productModalOverlay.classList.add('active');
        });
    });

});
