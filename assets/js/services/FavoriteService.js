/**
 * @file FavoriteService.js
 * @description Servicio encargado del control de favoritos y persistencia en localStorage.
 * Aplica el Observer Pattern para notificar de forma sincronizada a múltiples componentes.
 */

class FavoriteService {
    constructor() {
        /** @type {Array<Object>} */
        this.observers = [];
    }

    /**
     * Registra un observador para escuchar cambios de favoritos.
     * @param {Object} observer - Instancia que implementa la función `update(productId, isFavorite)`.
     */
    addObserver(observer) {
        if (observer && typeof observer.update === 'function') {
            this.observers.push(observer);
        }
    }

    /**
     * Remueve un observador previamente registrado.
     * @param {Object} observer - Observador a eliminar.
     */
    removeObserver(observer) {
        this.observers = this.observers.filter(o => o !== observer);
    }

    /**
     * Notifica a todos los observadores registrados sobre el cambio en el estado del favorito.
     * @param {number} productId - ID del producto.
     * @param {boolean} isFavorite - Nuevo estado.
     */
    notify(productId, isFavorite) {
        this.observers.forEach(observer => {
            observer.update(productId, isFavorite);
        });

        // Emitir CustomEvent global en window para que otros componentes de la UI reaccionen
        const event = new CustomEvent('favoriteToggled', {
            detail: { productId, isFavorite }
        });
        window.dispatchEvent(event);
    }

    /**
     * Comprueba si un producto está marcado como favorito.
     * @param {number} productId - ID del producto.
     * @returns {boolean} True si es favorito.
     */
    isFavorite(productId) {
        const itemKey = `favorites_${productId}`;
        return localStorage.getItem(itemKey) === 'true';
    }

    /**
     * Cambia el estado de favorito de un producto y lo persiste.
     * @param {number} productId - ID del producto.
     * @returns {boolean} El nuevo estado asignado.
     */
    toggleFavorite(productId) {
        const nextState = !this.isFavorite(productId);
        const itemKey = `favorites_${productId}`;
        
        if (nextState) {
            localStorage.setItem(itemKey, 'true');
        } else {
            localStorage.removeItem(itemKey);
        }

        this.notify(productId, nextState);
        return nextState;
    }
}

// Instancia única (Singleton) exportada para mantener un único sujeto en toda la aplicación
const favoriteService = new FavoriteService();
export default favoriteService;
