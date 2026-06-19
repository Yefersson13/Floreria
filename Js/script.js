document.addEventListener('DOMContentLoaded', () => {

    // 1. Mobile Menu Toggle
    const hamburger = document.querySelector('.hamburger');
    const navLinks = document.querySelector('.nav-links');
    const links = document.querySelectorAll('.nav-links li a');

    if (hamburger && navLinks) {
        hamburger.addEventListener('click', () => {
            hamburger.classList.toggle('active');
            navLinks.classList.toggle('active');
        });
    }

    links.forEach(link => {
        link.addEventListener('click', () => {
            if (hamburger && navLinks) {
                hamburger.classList.remove('active');
                navLinks.classList.remove('active');
            }
        });
    });

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

});
