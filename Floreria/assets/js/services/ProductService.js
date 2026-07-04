/**
 * @file ProductService.js
 * @description Servicio encargado de consumir los datos de los productos desde el backend PHP.
 * Responsabilidad: Centralizar las peticiones HTTP y convertirlas en instancias de ProductModel.
 */

import ProductModel from '../models/ProductModel.js';

export default class ProductService {
    /**
     * Obtiene todos los productos de la API.
     * @returns {Promise<ProductModel[]>} Lista de modelos de productos.
     */
    static async getAllProducts() {
        const response = await fetch('api/products.php');
        
        if (!response.ok) {
            throw new Error(`Error HTTP: ${response.status} al obtener productos.`);
        }

        const data = await response.json();
        
        if (data.error) {
            throw new Error(`Error en API: ${data.error}`);
        }

        return data.map(item => new ProductModel(item));
    }

    /**
     * Obtiene la información de un producto individual por su ID.
     * @param {number} id - ID del producto a buscar.
     * @returns {Promise<ProductModel>} Instancia del modelo de producto.
     */
    static async getProductById(id) {
        const response = await fetch(`api/products.php?id=${id}`);
        
        if (!response.ok) {
            throw new Error(`Error HTTP: ${response.status} al obtener detalles del producto ${id}.`);
        }

        const data = await response.json();

        if (data.error) {
            throw new Error(`Error en API: ${data.error}`);
        }

        return new ProductModel(data);
    }
}
