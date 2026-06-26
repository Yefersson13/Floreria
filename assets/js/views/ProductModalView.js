/**
 * @file ProductModalView.js
 * @description Vista encargada de renderizar y controlar el modal de detalle de producto.
 * Responsabilidad: Manejo de la galería interactiva, selector de cantidad y cierre del modal.
 */

export default class ProductModalView {
    /**
     * @constructor
     * @param {Object} handlers - Callbacks de acciones del controlador.
     * @param {Function} handlers.onAddToCart - Callback para agregar una cantidad al carrito.
     */
    constructor(handlers) {
        this.handlers = handlers;
        this.overlay = null;
        this.currentProduct = null;
        this.escKeyListener = null;
        this.createModalContainer();
    }

    /**
     * Crea el overlay contenedor del modal en el cuerpo del documento si no existe.
     */
    createModalContainer() {
        let overlay = document.getElementById('product-detail-modal');
        if (!overlay) {
            overlay = document.createElement('div');
            overlay.id = 'product-detail-modal';
            overlay.className = 'modal-overlay';
            document.body.appendChild(overlay);
        }
        this.overlay = overlay;
    }

    /**
     * Abre el modal y carga los detalles del producto especificado.
     * @param {ProductModel} productModel - Instancia de los datos del producto.
     */
    open(productModel) {
        this.currentProduct = productModel;
        this.render();
        this.overlay.classList.add('modal-overlay--open');
        this.bindEvents();
    }

    /**
     * Cierra el modal y limpia el estado activo.
     */
    close() {
        this.overlay.classList.remove('modal-overlay--open');
        if (this.escKeyListener) {
            document.removeEventListener('keydown', this.escKeyListener);
            this.escKeyListener = null;
        }
        this.currentProduct = null;
    }

    /**
     * Renderiza la estructura interna del modal basada en el producto cargado.
     */
    render() {
        const product = this.currentProduct;
        const thumbnailsHtml = this.generateThumbnailsHtml(product.images, product.name);

        this.overlay.innerHTML = `
            <div class="modal-container">
                <button class="modal-close-btn" aria-label="Cerrar modal">&times;</button>
                <div class="modal-body">
                    <div class="modal-gallery">
                        <div class="modal-gallery__main-container">
                            <img class="modal-gallery__main-img" src="${product.images[0]}" alt="${product.name}">
                        </div>
                        <div class="modal-gallery__thumbnails">
                            ${thumbnailsHtml}
                        </div>
                    </div>
                    <div class="modal-details">
                        <h2 class="modal-details__title">${product.name}</h2>
                        <span class="modal-details__price">${product.getFormattedPrice()}</span>
                        <h4 class="modal-details__desc-title">Descripción</h4>
                        <p class="modal-details__description">${product.longDescription}</p>
                        
                        <div class="modal-actions">
                            <div class="quantity-selector">
                                <button class="quantity-btn quantity-btn--decrease" type="button" aria-label="Disminuir cantidad">-</button>
                                <input class="quantity-input" type="number" value="1" min="1" max="99" readonly>
                                <button class="quantity-btn quantity-btn--increase" type="button" aria-label="Aumentar cantidad">+</button>
                            </div>
                            <button class="modal-actions__add-btn" type="button">Agregar al carrito</button>
                        </div>
                    </div>
                </div>
            </div>
        `;
    }

    /**
     * Genera el marcado HTML para las miniaturas de imágenes.
     * @param {string[]} images - Lista de rutas de imágenes.
     * @param {string} name - Nombre del producto.
     * @returns {string} Markup HTML.
     */
    generateThumbnailsHtml(images, name) {
        return images.map((img, index) => `
            <button class="modal-gallery__thumbnail ${index === 0 ? 'modal-gallery__thumbnail--active' : ''}" data-index="${index}" type="button">
                <img src="${img}" alt="${name} miniatura ${index + 1}">
            </button>
        `).join('');
    }

    /**
     * Registra las escuchas de eventos principales para el modal.
     */
    bindEvents() {
        const closeBtn = this.overlay.querySelector('.modal-close-btn');
        closeBtn.addEventListener('click', () => this.close());

        this.overlay.addEventListener('click', (event) => {
            if (event.target === this.overlay) {
                this.close();
            }
        });

        // Configuración de Escape Key
        this.escKeyListener = (event) => {
            if (event.key === 'Escape') {
                this.close();
            }
        };
        document.addEventListener('keydown', this.escKeyListener);

        this.bindGalleryEvents();
        this.bindQuantityEvents();
        this.bindCartEvents();
    }

    /**
     * Enlaza eventos para el visor interactivo de fotos.
     */
    bindGalleryEvents() {
        const mainImg = this.overlay.querySelector('.modal-gallery__main-img');
        const thumbs = this.overlay.querySelectorAll('.modal-gallery__thumbnail');

        thumbs.forEach(thumb => {
            thumb.addEventListener('click', () => {
                const index = thumb.getAttribute('data-index');
                mainImg.src = this.currentProduct.images[index];
                
                thumbs.forEach(t => t.classList.remove('modal-gallery__thumbnail--active'));
                thumb.classList.add('modal-gallery__thumbnail--active');
            });
        });
    }

    /**
     * Enlaza el funcionamiento del selector de cantidad.
     */
    bindQuantityEvents() {
        const input = this.overlay.querySelector('.quantity-input');
        const decBtn = this.overlay.querySelector('.quantity-btn--decrease');
        const incBtn = this.overlay.querySelector('.quantity-btn--increase');
        const MIN_VAL = 1;
        const MAX_VAL = 99;

        decBtn.addEventListener('click', () => {
            const current = parseInt(input.value, 10);
            if (current > MIN_VAL) {
                input.value = current - 1;
            }
        });

        incBtn.addEventListener('click', () => {
            const current = parseInt(input.value, 10);
            if (current < MAX_VAL) {
                input.value = current + 1;
            }
        });
    }

    /**
     * Enlaza la llamada del botón de agregar al carrito.
     */
    bindCartEvents() {
        const addBtn = this.overlay.querySelector('.modal-actions__add-btn');
        const input = this.overlay.querySelector('.quantity-input');

        addBtn.addEventListener('click', () => {
            const qty = parseInt(input.value, 10);
            this.handlers.onAddToCart(this.currentProduct.id, qty);
            this.close();
        });
    }
}
