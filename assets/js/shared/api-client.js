/**
 * ============================================================================
 * API CLIENT - CLIENTE CENTRALIZADO DE APIS
 * ============================================================================
 * Manejo centralizado de todas las llamadas API del sistema RespaldosChile
 * @version 1.0
 * @author RespaldosChile Team
 * @requires jQuery
 * ============================================================================
 */

const APIClient = (function($) {
    'use strict';

    // ========================================================================
    // CONFIGURACIÓN
    // ========================================================================
    
    const CONFIG = {
        baseURL: 'api/',
        timeout: 30000,
        retryAttempts: 3,
        retryDelay: 1000,
           endpoints: {
            // Pedidos
            extraerOrdenes: 'extraer_ordenes.php',
            detalleOrden: 'get_order_details.php',
            actualizarOrden: 'actualizar_orden.php',
            eliminarOrden: 'eliminar_orden.php',
            
            // Productos
            actualizarEstado: 'actualizar_estado.php',
            editarProducto: 'editar_producto.php',
                        updateorder: 'actualizar_orden.php',

            detalleProducto: 'get_producto_detalle.php',
            eliminarProducto: 'eliminar_producto.php',
            
            // Pagos
            buscarPagos: 'buscarPagos.php',
            anadirPago: 'anadirpago.php',
            asociarPago: 'asociar_pago.php',
            
            // Otros
            obtenerTapiceros: 'get_tapiceros.php',
            obtenerProcesos: 'get_procesos.php',
            subirArchivo: 'upload.php'
        },
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Cache-Control': 'no-cache'
        }
    };

    // ========================================================================
    // FUNCIONES AUXILIARES
    // ========================================================================
    
    function buildURL(endpoint) {
        return CONFIG.baseURL + endpoint;
    }

    function handleResponse(response, resolve, reject) {
        try {
            if (typeof response === 'string') {
                response = JSON.parse(response);
            }
            resolve(response);
        } catch (error) {
            console.error('Error parsing response:', error);
            reject(new Error('Error al procesar la respuesta del servidor'));
        }
    }

    function handleError(xhr, textStatus, errorThrown, reject) {
        let errorMessage = 'Error de conexión con el servidor';
        
        if (xhr.status === 404) {
            errorMessage = 'Endpoint no encontrado';
        } else if (xhr.status === 500) {
            errorMessage = 'Error interno del servidor';
        } else if (xhr.status === 403) {
            errorMessage = 'No tienes permisos para realizar esta acción';
        } else if (textStatus === 'timeout') {
            errorMessage = 'Tiempo de espera agotado';
        } else if (xhr.responseText) {
            try {
                const errorData = JSON.parse(xhr.responseText);
                errorMessage = errorData.message || errorMessage;
            } catch (e) {
                errorMessage = xhr.responseText;
            }
        }
        
        console.error('API Error:', {
            status: xhr.status,
            statusText: xhr.statusText,
            textStatus: textStatus,
            errorThrown: errorThrown,
            responseText: xhr.responseText
        });
        
        reject(new Error(errorMessage));
    }

    function makeRequest(config) {
        return new Promise((resolve, reject) => {
            const defaultConfig = {
                method: 'POST',
                dataType: 'json',
                timeout: CONFIG.timeout,
                headers: CONFIG.headers,
                success: (response) => handleResponse(response, resolve, reject),
                error: (xhr, textStatus, errorThrown) => handleError(xhr, textStatus, errorThrown, reject)
            };

            const requestConfig = $.extend(true, defaultConfig, config);
            
            // Log de la petición en desarrollo
            if (console.groupCollapsed) {
                console.groupCollapsed(`API Request: ${requestConfig.method} ${requestConfig.url}`);
                console.log('Config:', requestConfig);
                console.groupEnd();
            }

            $.ajax(requestConfig);
        });
    }

    function makeRequestWithRetry(config, attempt = 1) {
        return makeRequest(config).catch(error => {
            if (attempt < CONFIG.retryAttempts && error.message.includes('conexión')) {
                console.warn(`Reintentando petición (${attempt}/${CONFIG.retryAttempts})...`);
                return new Promise(resolve => {
                    setTimeout(() => {
                        resolve(makeRequestWithRetry(config, attempt + 1));
                    }, CONFIG.retryDelay * attempt);
                });
            }
            throw error;
        });
    }

    // ========================================================================
    // MÉTODOS DE PEDIDOS
    // ========================================================================
    
    function getOrders(modulo = 'dashboard', additionalData = {}) {
        const data = {
            modulo: modulo,
            ...additionalData
        };

        return makeRequestWithRetry({
            url: buildURL(CONFIG.endpoints.extraerOrdenes),
            method: 'POST',
            data: data
        });
    }

    function getOrderDetails(numOrden) {
        if (!numOrden) {
            return Promise.reject(new Error('Número de orden requerido'));
        }

        return makeRequestWithRetry({
            url: buildURL(CONFIG.endpoints.detalleOrden),
            method: 'POST',
            data: { num_orden: numOrden }
        });
    }

    function updateOrder(orderData) {
        if (!orderData || !orderData.num_orden) {
            return Promise.reject(new Error('Datos de orden inválidos'));
        }

        return makeRequestWithRetry({
            url: buildURL(CONFIG.endpoints.actualizarOrden),
            method: 'POST',
            data: orderData
        });
    }

    function deleteOrder(numOrden, motivo = '') {
        if (!numOrden) {
            return Promise.reject(new Error('Número de orden requerido'));
        }

        return makeRequestWithRetry({
            url: buildURL(CONFIG.endpoints.eliminarOrden),
            method: 'POST',
            data: {
                num_orden: numOrden,
                motivo: motivo
            }
        });
    }

    // ========================================================================
    // MÉTODOS DE PRODUCTOS
    // ========================================================================
    
    function updateProductStatus(productoId, estadoId, observacion = '') {
        if (!productoId || !estadoId) {
            return Promise.reject(new Error('ID de producto y estado requeridos'));
        }

        return makeRequestWithRetry({
            url: buildURL(CONFIG.endpoints.actualizarEstado),
            method: 'POST',
            data: {
                id_detalle: productoId,
                estado_id: estadoId,
                observacion: observacion
            }
        });
    }

    function updateProduct(productData) {
        if (!productData || !productData.id) {
            return Promise.reject(new Error('Datos de producto inválidos'));
        }

        return makeRequestWithRetry({
            url: buildURL(CONFIG.endpoints.editarProducto),
            method: 'POST',
            data: productData
        });
    }

    function deleteProduct(productoId, motivo = '') {
        if (!productoId) {
            return Promise.reject(new Error('ID de producto requerido'));
        }

        return makeRequestWithRetry({
            url: buildURL(CONFIG.endpoints.eliminarProducto),
            method: 'POST',
            data: {
                id: productoId,
                motivo: motivo
            }
        });
    }

    // ========================================================================
    // MÉTODOS DE PAGOS
    // ========================================================================
    
    function searchPayments(criterio, valor) {
        if (!criterio || !valor) {
            return Promise.reject(new Error('Criterio y valor de búsqueda requeridos'));
        }

        return makeRequestWithRetry({
            url: buildURL(CONFIG.endpoints.buscarPagos),
            method: 'GET',
            data: {
                criterio: criterio,
                valor: valor
            }
        });
    }

    function getPaymentsByOrder(numOrden) {
        if (!numOrden) {
            return Promise.reject(new Error('Número de orden requerido'));
        }

        return makeRequestWithRetry({
            url: buildURL(CONFIG.endpoints.buscarPagos),
            method: 'GET',
            data: {
                criterio: 'por_n_orden',
                valor: numOrden
            }
        });
    }

    function addPayment(paymentData) {
        if (!paymentData) {
            return Promise.reject(new Error('Datos de pago requeridos'));
        }

        return makeRequestWithRetry({
            url: buildURL(CONFIG.endpoints.anadirPago),
            method: 'GET',
            data: paymentData
        });
    }

    function associatePayment(idCartola, numOrden, paymentData) {
        if (!idCartola || !numOrden) {
            return Promise.reject(new Error('ID de cartola y número de orden requeridos'));
        }

        const data = {
            criterio: 'pagados',
            id_cartola: idCartola,
            funcion: 'asociarPago',
            num_orden: numOrden,
            ...paymentData
        };

        return makeRequestWithRetry({
            url: buildURL(CONFIG.endpoints.buscarPagos),
            method: 'GET',
            data: data
        });
    }

    function removePaymentAssociation(pagoId, idCartola) {
        if (!pagoId) {
            return Promise.reject(new Error('ID de pago requerido'));
        }

        return makeRequestWithRetry({
            url: buildURL(CONFIG.endpoints.buscarPagos),
            method: 'GET',
            data: {
                criterio: 'pagados',
                valor: pagoId,
                id_cartola: idCartola || '',
                funcion: 'eliminarPago'
            }
        });
    }

    // ========================================================================
    // MÉTODOS DE DATOS MAESTROS
    // ========================================================================
    
    function getTapiceros() {
        return makeRequestWithRetry({
            url: buildURL(CONFIG.endpoints.obtenerTapiceros),
            method: 'GET'
        });
    }

    function getProcesos() {
        return makeRequestWithRetry({
            url: buildURL(CONFIG.endpoints.obtenerProcesos),
            method: 'GET'
        });
    }

    // ========================================================================
    // MÉTODOS DE ARCHIVOS
    // ========================================================================
    
    function uploadFile(formData, progressCallback) {
        return new Promise((resolve, reject) => {
            const xhr = new XMLHttpRequest();
            
            // Configurar eventos
            xhr.upload.addEventListener('progress', function(e) {
                if (e.lengthComputable && progressCallback) {
                    const percentComplete = (e.loaded / e.total) * 100;
                    progressCallback(percentComplete);
                }
            });
            
            xhr.addEventListener('load', function() {
                if (xhr.status === 200) {
                    try {
                        const response = JSON.parse(xhr.responseText);
                        resolve(response);
                    } catch (error) {
                        reject(new Error('Error al procesar la respuesta del servidor'));
                    }
                } else {
                    reject(new Error(`Error del servidor: ${xhr.status}`));
                }
            });
            
            xhr.addEventListener('error', function() {
                reject(new Error('Error de conexión durante la subida'));
            });
            
            xhr.addEventListener('timeout', function() {
                reject(new Error('Tiempo de espera agotado durante la subida'));
            });
            
            // Configurar y enviar petición
            xhr.open('POST', buildURL(CONFIG.endpoints.subirArchivo));
            xhr.timeout = CONFIG.timeout;
            xhr.send(formData);
        });
    }

    // ========================================================================
    // MÉTODOS UTILITARIOS
    // ========================================================================
    
    function ping() {
        return makeRequest({
            url: buildURL('ping.php'),
            method: 'GET',
            timeout: 5000
        });
    }

    function getSystemInfo() {
        return makeRequest({
            url: buildURL('system_info.php'),
            method: 'GET'
        });
    }

    // ========================================================================
    // INTERCEPTORES (para logging, autenticación, etc.)
    // ========================================================================
    
    function addRequestInterceptor(callback) {
        // TODO: Implementar interceptores de petición
        console.log('Request interceptor agregado');
    }

    function addResponseInterceptor(callback) {
        // TODO: Implementar interceptores de respuesta
        console.log('Response interceptor agregado');
    }

    // ========================================================================
    // MANEJO DE CACHE
    // ========================================================================
    
    const cache = new Map();
    const CACHE_DURATION = 5 * 60 * 1000; // 5 minutos

    function getCachedData(key) {
        const cached = cache.get(key);
        if (cached && (Date.now() - cached.timestamp) < CACHE_DURATION) {
            return cached.data;
        }
        cache.delete(key);
        return null;
    }

    function setCachedData(key, data) {
        cache.set(key, {
            data: data,
            timestamp: Date.now()
        });
    }

    function clearCache() {
        cache.clear();
    }

    // Wrapper para métodos que pueden usar cache
    function withCache(key, requestFn) {
        const cached = getCachedData(key);
        if (cached) {
            return Promise.resolve(cached);
        }
        
        return requestFn().then(data => {
            setCachedData(key, data);
            return data;
        });
    }

    // Métodos con cache
    function getTapicerosWithCache() {
        return withCache('tapiceros', getTapiceros);
    }

    function getProcesosWithCache() {
        return withCache('procesos', getProcesos);
    }

    // ========================================================================
    // MANEJO DE ERRORES GLOBALES
    // ========================================================================
    
    function setupGlobalErrorHandling() {
        $(document).ajaxError(function(event, xhr, settings, thrownError) {
            if (xhr.status === 401) {
                // Sesión expirada
                console.warn('Sesión expirada, redirigiendo al login...');
                // window.location.href = 'login.php';
            } else if (xhr.status === 0) {
                // Error de conexión
                console.error('Error de conexión detectado');
                if (typeof UIComponents !== 'undefined') {
                    UIComponents.showAlert('Error de conexión. Verifica tu conexión a internet.', 'error');
                }
            }
        });
    }

    // ========================================================================
    // INICIALIZACIÓN
    // ========================================================================
    
    function init() {
        setupGlobalErrorHandling();
        console.log('✅ APIClient inicializado correctamente');
    }

    // ========================================================================
    // API PÚBLICA
    // ========================================================================
    
    return {
        // Inicialización
        init: init,
        
        // Pedidos
        getOrders: getOrders,
        getOrderDetails: getOrderDetails,
        updateOrder: updateOrder,
        deleteOrder: deleteOrder,
        
        // Productos
        updateProductStatus: updateProductStatus,
        updateProduct: updateProduct,
        deleteProduct: deleteProduct,
        
        // Pagos
        searchPayments: searchPayments,
        getPaymentsByOrder: getPaymentsByOrder,
        addPayment: addPayment,
        associatePayment: associatePayment,
        removePaymentAssociation: removePaymentAssociation,
        
        // Datos maestros
        getTapiceros: getTapiceros,
        getProcesos: getProcesos,
        getTapicerosWithCache: getTapicerosWithCache,
        getProcesosWithCache: getProcesosWithCache,
        
        // Archivos
        uploadFile: uploadFile,
        
        // Utilidades
        ping: ping,
        getSystemInfo: getSystemInfo,
        clearCache: clearCache,
        
        // Interceptores
        addRequestInterceptor: addRequestInterceptor,
        addResponseInterceptor: addResponseInterceptor,
        
        // Acceso a configuración (solo lectura)
        getConfig: () => Object.freeze({...CONFIG})
    };

})(jQuery);

// ========================================================================
// AUTO-INICIALIZACIÓN
// ========================================================================

$(document).ready(function() {
    APIClient.init();
});

// Exportar al namespace global
window.APIClient = APIClient;