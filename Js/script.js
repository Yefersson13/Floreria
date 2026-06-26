document.addEventListener('DOMContentLoaded', () => {

    // 1. Mobile Menu Toggle
    const hamburger = document.querySelector('.hamburger');
    const mobileMenu = document.getElementById('mobileMenuDropdown');

    if (hamburger && mobileMenu) {
        hamburger.addEventListener('click', (e) => {
            e.stopPropagation();
            hamburger.classList.toggle('active');
            mobileMenu.classList.toggle('active');
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

    // Mostrar registro al hacer clic en "Agregar"
    botones.forEach(boton => {
        boton.addEventListener("click", (e) => {
            e.preventDefault();
            openRegisterForm();
        });
    });

    // Iniciar Sesión desde el Icono de la cabecera
    if (userIcon) {
        userIcon.addEventListener("click", (e) => {
            e.preventDefault();
            openLoginForm();
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

    if (cerrar_ses && logoutBox) {
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

    // Cargar conteo inicial de carrito
    let totalCartQty = parseInt(localStorage.getItem('atelier_cart_qty') || '0', 10);
    updateBadgeCount(cartBadge, totalCartQty);

    // --- MANEJO DE WISHLIST ("ME ENCANTA") ---
    const wishlistBtns = document.querySelectorAll('.wishlist-btn');
    let totalFavs = 0;

    wishlistBtns.forEach(btn => {
        const card = btn.closest('.product-card, .slider-card');
        if (!card) return;
        
        const titleEl = card.querySelector('h3');
        const prodName = titleEl ? titleEl.textContent.trim() : 'Producto Florería';
        const favKey = 'atelier_fav_' + prodName;
        const icon = btn.querySelector('i');

        // Restaurar estado guardado
        if (localStorage.getItem(favKey) === 'true') {
            btn.classList.add('active');
            if (icon) {
                icon.classList.remove('fa-regular');
                icon.classList.add('fa-solid');
            }
            totalFavs++;
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

            if (isFav) {
                localStorage.setItem(favKey, 'true');
                totalFavs++;
                showLuxuryToast('❤️ <span>¡<strong>' + prodName + '</strong> te encanta! Añadido a favoritos.</span>');
            } else {
                localStorage.removeItem(favKey);
                totalFavs = Math.max(0, totalFavs - 1);
                showLuxuryToast('🤍 <span>Se eliminó <strong>' + prodName + '</strong> de favoritos.</span>');
            }

            updateBadgeCount(heartBadge, totalFavs);
        });
    });

    updateBadgeCount(heartBadge, totalFavs);

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
        let qty = parseInt(pmQtyVal.value || '1', 10);
        totalCartQty += qty;
        localStorage.setItem('atelier_cart_qty', String(totalCartQty));
        updateBadgeCount(cartBadge, totalCartQty);
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
