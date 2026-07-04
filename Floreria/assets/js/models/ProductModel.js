/**
 * @file ProductModel.js
 * @description Model representing the product data structure in the e-commerce application.
 * Responsabilidad: Definir la estructura de datos del producto y su validación.
 */

export default class ProductModel {
    /**
     * @constructor
     * @param {Object} data - Raw data for the product.
     * @param {number|string} data.id - Unique identifier of the product.
     * @param {string} data.name - Name of the product.
     * @param {string} data.shortDescription - Short summary for the catalog card.
     * @param {string} data.longDescription - Detailed description for the detail modal.
     * @param {number|string} data.price - Selling price.
     * @param {string[]} data.images - List of image file paths or URLs.
     */
    constructor({ id, name, shortDescription, longDescription, price, images }) {
        this.id = Number(id);
        this.name = String(name);
        this.shortDescription = String(shortDescription);
        this.longDescription = String(longDescription);
        this.price = Number(price);
        this.images = Array.isArray(images) ? images : [];
        this.validate();
    }

    /**
     * Validates that essential product information is present and correct.
     * @throws {Error} If validation fails.
     */
    validate() {
        if (!this.id || isNaN(this.id)) {
            throw new Error('ProductModel validation failed: Invalid ID.');
        }
        if (!this.name || this.name.trim() === '') {
            throw new Error('ProductModel validation failed: Missing product name.');
        }
        if (isNaN(this.price) || this.price < 0) {
            throw new Error('ProductModel validation failed: Invalid price.');
        }
    }

    /**
     * Formats the price to COP (Colombian Peso) currency format.
     * @returns {string} Formatted currency string.
     */
    getFormattedPrice() {
        return new Intl.NumberFormat('es-CO', {
            style: 'currency',
            currency: 'COP',
            minimumFractionDigits: 0,
            maximumFractionDigits: 0
        }).format(this.price);
    }
}
