// Cuadro pequeño de notificación elegante en la parte de abajo (Toast Global Atelier)
window.showAtelierBottomToast = function(message, isError = false) {
    let toastEl = document.getElementById('atelier-bottom-toast-el');
    if (!toastEl) {
        toastEl = document.createElement('div');
        toastEl.id = 'atelier-bottom-toast-el';
        document.body.appendChild(toastEl);
    }
    
    // Estilos del recuadro flotante en la parte inferior del navegador
    toastEl.style.cssText = `
        position: fixed;
        bottom: 30px;
        left: 50%;
        transform: translateX(-50%) translateY(120px);
        background: ${isError ? 'rgba(186, 5, 53, 0.96)' : 'rgba(15, 23, 42, 0.96)'};
        backdrop-filter: blur(14px);
        -webkit-backdrop-filter: blur(14px);
        color: #ffffff;
        padding: 14px 26px;
        border-radius: 50px;
        box-shadow: 0 16px 36px rgba(0, 0, 0, 0.38), 0 0 0 1.5px rgba(255, 255, 255, 0.18) inset;
        font-family: 'Inter', sans-serif;
        font-size: 0.95rem;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 12px;
        z-index: 999999;
        opacity: 0;
        transition: all 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
        max-width: 90vw;
        text-align: center;
    `;
    
    const icon = isError 
        ? '<i class="fa-solid fa-circle-exclamation" style="font-size:1.25rem; color:#FFD1DC;"></i>' 
        : '<i class="fa-solid fa-circle-check" style="font-size:1.25rem; color:#4ADE80;"></i>';
        
    toastEl.innerHTML = `${icon} <span>${message}</span>`;
    
    void toastEl.offsetWidth; // Forzar redibujado
    toastEl.style.transform = 'translateX(-50%) translateY(0)';
    toastEl.style.opacity = '1';
    
    if (window.atelierToastTimer) clearTimeout(window.atelierToastTimer);
    window.atelierToastTimer = setTimeout(() => {
        toastEl.style.transform = 'translateX(-50%) translateY(120px)';
        toastEl.style.opacity = '0';
    }, 4200);
};

$(document).ready(function(){
    // Revisar si hay toasts pendientes en sessionStorage tras recargar/redirigir
    const storedError = sessionStorage.getItem('atelier_toast_error');
    if (storedError) {
        sessionStorage.removeItem('atelier_toast_error');
        setTimeout(() => window.showAtelierBottomToast(storedError, true), 300);
    }
    const storedSuccess = sessionStorage.getItem('atelier_toast_success');
    if (storedSuccess) {
        sessionStorage.removeItem('atelier_toast_success');
        setTimeout(() => window.showAtelierBottomToast(storedSuccess, false), 300);
    }

    jQuery.validator.addMethod("mayus", function(value, element) {
        return this.optional(element) || /^[A-Z].*/.test(value);
    }, 'La primera letra debe ser Mayúscula');

    // Validación y envío inteligente del formulario de Registro (#Regis_for)
    $("#Regis_for").validate({
        rules:{
            nombre:{
                required: true,
                minlength: 4,
                mayus:true
            },
            contra: {
                required:true,
                minlength: 6,
                mayus:true
            },
            correo: {
                required:true,
                email: true
            },
            telefono: {
                required:true,
                digits: true,
                minlength: 9,
                maxlength: 11
            }
        },
        messages:{
            nombre: {
                required: "El nombre de usuario es requerido",
                minlength: "Mínimo 4 caracteres"
            },
            contra: {
                required: "La contraseña es requerida",
                minlength: "Mínimo 6 caracteres"
            },
            correo: {
                required: "El correo es requerido",
                email: "El correo no es válido"
            },
            telefono: {
                required: "El teléfono es requerido",
                digits: "Solo números",
                minlength: "Mínimo 9 dígitos"
            }
        },
        submitHandler: function(form) {
            // Si se ejecuta bajo un servidor PHP normal (XAMPP o servidor embebido) enviamos de forma estándar
            const isLocalStatic = window.location.protocol === 'file:' || window.location.port === '5500' || window.location.port === '3000';
            if (!isLocalStatic) {
                form.submit();
                return;
            }

            // Respaldo offline / Live Server (Local Database en Navegador)
            const nombre = $(form).find("input[name='nombre']").val().trim();
            const correo = $(form).find("input[name='correo']").val().trim();
            const telefono = $(form).find("input[name='telefono']").val().trim();
            const contra = $(form).find("input[name='contra']").val().trim();

            let users = JSON.parse(localStorage.getItem('atelier_local_db_users') || '[]');
            if (users.some(u => u.correo.toLowerCase() === correo.toLowerCase())) {
                window.showAtelierBottomToast("El correo electrónico ya está registrado.", true);
                return;
            }

            users.push({ nombre, correo, telefono, contra });
            localStorage.setItem('atelier_local_db_users', JSON.stringify(users));
            localStorage.setItem('atelier_current_user', nombre);

            sessionStorage.setItem('atelier_toast_success', "¡Registro Exitoso! Bienvenido a Atelier, " + nombre + ".");
            // En servidores estáticos (Live Server), recargamos index.html para evitar la descarga del archivo .php
            const regPath = window.location.pathname.toLowerCase();
            const regRedirect = (regPath.includes('/pages/') || regPath.includes('\\pages\\')) ? '../index.html' : 'index.html';
            window.location.href = regRedirect;
        }
    });

    // Envío inteligente del formulario de Inicio de Sesión (#Inicio_for)
    $("#Inicio_for").on("submit", function(e) {
        const isLocalStatic = window.location.protocol === 'file:' || window.location.port === '5500' || window.location.port === '3000';
        if (!isLocalStatic) {
            return true; // Permitir que el form haga submit al PHP login.php si estamos en XAMPP
        }

        e.preventDefault();
        const correo = $(this).find("input[name='correo']").val().trim();
        const contra = $(this).find("input[name='contra']").val().trim();

        let users = JSON.parse(localStorage.getItem('atelier_local_db_users') || '[]');
        const user = users.find(u => u.correo.toLowerCase() === correo.toLowerCase() && u.contra === contra);

        if (user) {
            localStorage.setItem('atelier_current_user', user.nombre);
            sessionStorage.setItem('atelier_toast_success', "¡Bienvenido de vuelta, " + user.nombre + "!");
            // En Live Server o archivo local, redirigimos/recargamos en index.html para evitar descargar .php
            const loginPath = window.location.pathname.toLowerCase();
            const loginRedirect = (loginPath.includes('/pages/') || loginPath.includes('\\pages\\')) ? '../index.html' : 'index.html';
            window.location.href = loginRedirect;
        } else {
            window.showAtelierBottomToast("Credenciales incorrectas. Verifique su correo y contraseña.", true);
        }
    });

    // Sincronización visual del usuario logueado entre sesiones PHP y LocalDB en cualquier página
    checkLocalUserDisplay();
});

function checkLocalUserDisplay() {
    let localUser = localStorage.getItem('atelier_current_user');
    if (localUser === 'undefined' || localUser === 'null' || localUser === 'Carlos') {
        localUser = null;
        localStorage.removeItem('atelier_current_user');
    }

    const userIcon = document.getElementById("user-icon");
    if (!userIcon) return;

    // Verificar si hay una pastilla pre-generada en el HTML (ej. desde PHP en pagina.php o pasarela.php)
    const existingPillName = userIcon.querySelector(".user-pill-name, #localUser");
    if (existingPillName) {
        let pillText = existingPillName.textContent.trim();
        if (pillText !== '' && pillText !== 'Carlos' && pillText !== 'undefined' && pillText !== 'null') {
            localUser = pillText;
            localStorage.setItem('atelier_current_user', localUser);
        }
    }

    // Si tenemos usuario activo en LocalDB o en sesión con nombre real válido, mostrar cápsula elegante
    if (localUser && localUser.trim() !== '' && localUser.trim() !== 'undefined' && localUser.trim() !== 'null') {
        localUser = localUser.trim();
        userIcon.style.display = "flex";
        userIcon.classList.remove("nav-icon");
        userIcon.classList.add("cerrar_ses", "nav-user-logged");
        userIcon.style.width = "auto";
        userIcon.style.height = "auto";
        userIcon.style.margin = "0 4px";
        userIcon.title = "Sesión activa: " + localUser;
        userIcon.innerHTML = `
            <div class="user-pill">
                <svg viewBox="0 0 24 24" class="user-pill-svg">
                    <circle cx="12" cy="7" r="4"></circle>
                    <path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"></path>
                </svg>
                <span class="user-pill-name" id="localUser">${localUser}</span>
            </div>
        `;

        // Actualizar el texto del modal de confirmación de cierre de sesión en cualquier página
        const msgSesion = document.getElementById("msg_sesion");
        if (msgSesion) {
            msgSesion.textContent = `¿Desea cerrar su sesión (${localUser})?`;
        }

        // Actualizar el campo de nombre del cliente si está en el Paso 2 de la Pasarela
        const pNombre = document.getElementById("p-nombre");
        if (pNombre && (pNombre.value === '' || pNombre.value === 'Carlos')) {
            pNombre.value = localUser;
        }

        if (typeof window.atelierUpdateCartIconVisibility === 'function') {
            window.atelierUpdateCartIconVisibility();
        }
    } else {
        // No hay sesión activa: mostrar ícono normal de silueta sin cápsula
        userIcon.style.display = "flex";
        userIcon.classList.remove("cerrar_ses", "nav-user-logged");
        userIcon.classList.add("nav-icon");
        userIcon.style.width = "";
        userIcon.style.height = "";
        userIcon.style.margin = "";
        userIcon.title = "Iniciar Sesión / Registrarse";
        userIcon.innerHTML = `
            <svg viewBox="0 0 24 24" style="width: 20px; height: 20px; stroke: currentColor; fill: none; stroke-width: 1.8; stroke-linecap: round; stroke-linejoin: round;">
                <circle cx="12" cy="7" r="4"></circle>
                <path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"></path>
            </svg>
        `;

        if (typeof window.atelierUpdateCartIconVisibility === 'function') {
            window.atelierUpdateCartIconVisibility();
        }
    }
}

// Función global de cierre de sesión infalible
window.atelierLogout = function(e) {
    if (e && typeof e.preventDefault === 'function') e.preventDefault();
    if (e && typeof e.stopPropagation === 'function') e.stopPropagation();

    // 1. Limpiar inmediatamente toda la memoria local y de sesión en el navegador
    try {
        localStorage.removeItem('atelier_current_user');
        localStorage.removeItem('atelier_user_logged');
        sessionStorage.clear();
    } catch(err) {}

    // 2. Ocultar el cuadro de confirmación y refrescar la barra de navegación en vivo
    const logoutBox = document.querySelector(".opcion_sesion");
    if (logoutBox) {
        logoutBox.style.display = "none";
        logoutBox.classList.remove("active");
    }

    if (typeof checkLocalUserDisplay === 'function') {
        try { checkLocalUserDisplay(); } catch(err) {}
    }

    // 3. Determinar las rutas
    const path = window.location.pathname.toLowerCase();
    let logoutUrl = "php/logout.php";
    let redirectUrl = "index.html";

    if (path.includes("/pages/") || path.includes("\\pages\\")) {
        logoutUrl = "../php/logout.php";
        redirectUrl = "../index.html";
    } else if (path.includes("php/") || path.includes("php\\")) {
        logoutUrl = "logout.php";
        redirectUrl = "../index.html";
    }

    // 4. Detectar si estamos en un servidor estático (como Live Server de VS Code en puerto 5500/3000 o file:///) o en XAMPP Apache
    const isStaticOrLiveServer = window.location.protocol === 'file:' || 
                                 window.location.port === '5500' || 
                                 window.location.port === '3000' || 
                                 window.location.port === '8080';

    if (isStaticOrLiveServer) {
        // En servidores estáticos o file:// NO navegamos al .php para evitar que el navegador descargue el archivo ("logout (3).php")
        if (path.endsWith('index.html') || path === '/' || path.endsWith('/floreria/') || path.endsWith('/floreria')) {
            window.location.reload();
        } else {
            window.location.href = redirectUrl;
        }
    } else {
        // En XAMPP / Apache (puerto 80/443 o localhost normal) SÍ navegamos a logout.php para que PHP destruya $_SESSION['cliente'] en el servidor
        window.location.href = logoutUrl;
    }
};
