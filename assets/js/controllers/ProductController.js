/**
 * @file ProductController.js
 * @description Controlador principal (Mediador) del MVC de productos.
 * Responsabilidad: Coordinar el flujo de datos entre el servicio API, los modelos, y las vistas.
 */

import ProductService from '../services/ProductService.js';
import favoriteService from '../services/FavoriteService.js';
import { ProductCardFactory } from '../views/ProductCardView.js';
import ProductModalView from '../views/ProductModalView.js';

export default class ProductController {
    /**
     * @constructor
     * @param {HTMLElement} container - Contenedor del DOM donde se insertarán las tarjetas.
     */
    constructor(container) {
        this.container = container;
        /** @type {ProductModel[]} Cache local de modelos cargados */
        this.products = [];

        // Instancia el modal pasando los callbacks correspondientes
        this.modalView = new ProductModalView({
            onAddToCart: (id, qty) => this.handleAddToCart(id, qty)
        });
    }

    /**
     * Inicializa la carga de productos de la API y los renderiza en la UI.
     * @async
     */
    async init() {
        try {
            this.products = await ProductService.getAllProducts();
            this.renderProducts();
        } catch (error) {
            console.error('Error al inicializar el controlador de productos:', error);
            this.container.innerHTML = `
                <div class="product-error">
                    <p>No pudimos cargar los productos de la florería en este momento.</p>
                </div>
            `;
        }
    }

    /**
     * Renderiza las tarjetas de producto en el contenedor utilizando la fábrica.
     */
    renderProducts() {
        this.container.innerHTML = '';

        // Callbacks unificados para evitar lógica dispersa en el DOM
        const handlers = {
            onViewDetails: (id) => this.handleViewDetails(id),
            onToggleFavorite: (id) => this.handleToggleFavorite(id),
            onAddToCart: (id, qty) => this.handleAddToCart(id, qty)
        };

        this.products.forEach(product => {
            // Aplicación de Factory Pattern para instanciación limpia
            const cardView = ProductCardFactory.createCard(product, handlers);
            this.container.appendChild(cardView.element);
        });
    }

    /**
     * Abre el modal con el detalle del producto seleccionado.
     * @param {number} productId - ID del producto.
     */
    handleViewDetails(productId) {
        const product = this.products.find(p => p.id === productId);
        if (product) {
            this.modalView.open(product);
        }
    }

    /**
     * Ejecuta el toggle de favorito mediante el servicio correspondiente.
     * @param {number} productId - ID del producto.
     */
    handleToggleFavorite(productId) {
        favoriteService.toggleFavorite(productId);
    }

    /**
     * Registra la adición de artículos al carrito de compras y notifica globalmente.
     * @param {number} productId - ID del producto.
     * @param {number} quantity - Cantidad de artículos.
     */
    handleAddToCart(productId, quantity) {
        const product = this.products.find(p => p.id === productId);
        if (product) {
            // Emitir evento personalizado para el carrito global
            const event = new CustomEvent('cartUpdated', {
                detail: { productId, quantity, product }
            });
            window.dispatchEvent(event);
            
            console.log(`[Carrito] Se agregaron ${quantity} unidades de "${product.name}" (ID: ${productId}).`);
        }
    }
}
