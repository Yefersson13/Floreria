/**
 * @file ProductCardView.js
 * @description Vista encargada de renderizar la tarjeta de producto en el DOM.
 * Aplica el Factory Pattern para instanciarse dinámicamente y el Observer Pattern para favoritos.
 */

import favoriteService from '../services/FavoriteService.js';

export class ProductCardView {
    /**
     * @constructor
     * @param {ProductModel} productModel - Instancia del modelo con los datos del producto.
     * @param {Object} handlers - Callbacks delegados por el controlador para interacción.
     * @param {Function} handlers.onViewDetails - Callback para ver detalles.
     * @param {Function} handlers.onToggleFavorite - Callback para cambiar favorito.
     * @param {Function} handlers.onAddToCart - Callback para agregar al carrito.
     */
    constructor(productModel, handlers) {
        this.product = productModel;
        this.handlers = handlers;
        
        // Elemento DOM raíz de esta tarjeta
        this.element = this.createCardElement();
        
        // Registrar esta tarjeta como observador de cambios en favoritos
        favoriteService.addObserver(this);
    }

    /**
     * Genera el marcado HTML de la tarjeta y configura los eventos.
     * @returns {HTMLElement} Elemento article estructurado.
     */
    createCardElement() {
        const card = document.createElement('article');
        card.className = 'product-card';
        card.setAttribute('data-id', this.product.id);

        const isFav = favoriteService.isFavorite(this.product.id);
        const activeClass = isFav ? 'product-card__favorite-btn--active' : '';

        card.innerHTML = `
            <div class="product-card__image-wrapper">
                <button class="product-card__favorite-btn ${activeClass}" aria-label="Añadir a favoritos" type="button">
                    <svg class="product-card__favorite-icon" viewBox="0 0 24 24">
                        <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
                    </svg>
                </button>
                <img class="product-card__image" src="${this.product.images[0]}" alt="${this.product.name}" loading="lazy">
                <div class="product-card__overlay">
                    <button class="product-card__btn-details" type="button">Ver detalles</button>
                </div>
            </div>
            <div class="product-card__info">
                <h3 class="product-card__title">${this.product.name}</h3>
                <p class="product-card__description">${this.product.shortDescription}</p>
                <div class="product-card__footer">
                    <span class="product-card__price">${this.product.getFormattedPrice()}</span>
                    <button class="product-card__btn-cart" type="button">Agregar al carrito</button>
                </div>
            </div>
        `;

        this.bindEvents(card);
        return card;
    }

    /**
     * Vincula los elementos del DOM con las funciones de devolución del controlador.
     * @param {HTMLElement} card - Elemento raíz de la tarjeta.
     */
    bindEvents(card) {
        const favBtn = card.querySelector('.product-card__favorite-btn');
        const detailsBtn = card.querySelector('.product-card__btn-details');
        const cartBtn = card.querySelector('.product-card__btn-cart');

        favBtn.addEventListener('click', (event) => {
            event.stopPropagation();
            this.handlers.onToggleFavorite(this.product.id);
        });

        detailsBtn.addEventListener('click', () => {
            this.handlers.onViewDetails(this.product.id);
        });

        cartBtn.addEventListener('click', () => {
            this.handlers.onAddToCart(this.product.id, 1);
        });
    }

    /**
     * Implementación de la interfaz Observer. Actualiza el estado visual del corazón.
     * @param {number} productId - ID del producto modificado.
     * @param {boolean} isFavorite - Si el producto es marcado como favorito o no.
     */
    update(productId, isFavorite) {
        if (this.product.id === productId) {
            const favBtn = this.element.querySelector('.product-card__favorite-btn');
            if (favBtn) {
                favBtn.classList.toggle('product-card__favorite-btn--active', isFavorite);
            }
        }
    }
}

/**
 * Clase fábrica para instanciar las tarjetas de producto de forma dinámica.
 * Aplica el Factory Pattern.
 */
export class ProductCardFactory {
    /**
     * Crea y devuelve una instancia de ProductCardView.
     * @param {ProductModel} productModel - Datos del producto.
     * @param {Object} handlers - Manejadores de eventos del controlador.
     * @returns {ProductCardView} La tarjeta de producto instanciada.
     */
    static createCard(productModel, handlers) {
        return new ProductCardView(productModel, handlers);
    }
}
