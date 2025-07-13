// constants.js 
/**
 * ============================================================================
 * CONSTANTS.JS - CONFIGURACIÓN CENTRALIZADA DEL SISTEMA
 * ============================================================================
 * Constantes, configuraciones y mapeos centralizados para RespaldosChile
 * @version 1.0
 * @author RespaldosChile Team
 * ============================================================================
 */

const RespaldosChileConstants = (function() {
    'use strict';

    // ========================================================================
    // INFORMACIÓN DEL SISTEMA
    // ========================================================================
    
    const SYSTEM_INFO = {
        name: 'RespaldosChile ERP',
        version: '2.1.0',
        environment: 'production', // development, staging, production
        author: 'RespaldosChile Team',
        buildDate: '2024-07-12',
        apiVersion: 'v1'
    };

    // ========================================================================
    // CONFIGURACIÓN DE APIS
    // ========================================================================
    
    const API_CONFIG = {
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
            validarPagos: 'validar_pagos.php',
            
            // Productos y Estados
            actualizarEstado: 'actualizar_estado.php',
            editarProducto: 'editar_producto.php',
            eliminarProducto: 'eliminar_producto.php',
            obtenerHistorial: 'get_historial.php',
            
            // Pagos
            buscarPagos: 'buscarPagos.php',
            anadirPago: 'anadirpago.php',
            asociarPago: 'asociar_pago.php',
            eliminarPago: 'eliminar_pago.php',
            obtenerCartola: 'obtener_curl.php',
            
            // Datos maestros
            obtenerTapiceros: 'get_tapiceros.php',
            obtenerProcesos: 'get_procesos.php',
            obtenerClientes: 'get_clientes.php',
            obtenerProductos: 'get_productos.php',
            
            // Sistema
            ping: 'ping.php',
            sistemInfo: 'system_info.php',
            notificaciones: 'notificaciones.php',
            subirArchivo: 'upload.php'
        }
    };

    // ========================================================================
    // ESTADOS Y PROCESOS DEL SISTEMA
    // ========================================================================
    
    const ESTADOS_PEDIDO = {
        0: {
            id: 0,
            nombre: 'No Aceptado',
            descripcion: 'Pedido ingresado pero no aceptado',
            color: 'warning',
            bgColor: 'bg-warning',
            textColor: 'text-warning',
            icon: 'fa-clock',
            categoria: 'pendiente',
            permiteEdicion: true,
            siguientesEstados: [1, 20]
        },
        1: {
            id: 1,
            nombre: 'Aceptado',
            descripcion: 'Pedido aceptado y validado',
            color: 'info',
            bgColor: 'bg-info',
            textColor: 'text-info',
            icon: 'fa-check',
            categoria: 'aceptado',
            permiteEdicion: true,
            siguientesEstados: [2]
        },
        2: {
            id: 2,
            nombre: 'Por Fabricar',
            descripcion: 'Enviado a fabricación',
            color: 'warning',
            bgColor: 'bg-warning',
            textColor: 'text-warning',
            icon: 'fa-tools',
            categoria: 'produccion',
            permiteEdicion: true,
            siguientesEstados: [3, 20],
            esUrgente: true
        },
        3: {
            id: 3,
            nombre: 'Tela Cortada',
            descripcion: 'Tela cortada y preparada',
            color: 'primary',
            bgColor: 'bg-primary',
            textColor: 'text-primary',
            icon: 'fa-cut',
            categoria: 'produccion',
            permiteEdicion: true,
            siguientesEstados: [4]
        },
        4: {
            id: 4,
            nombre: 'Armando Esqueleto',
            descripcion: 'Armado de estructura',
            color: 'primary',
            bgColor: 'bg-primary',
            textColor: 'text-primary',
            icon: 'fa-hammer',
            categoria: 'produccion',
            permiteEdicion: true,
            siguientesEstados: [5]
        },
        5: {
            id: 5,
            nombre: 'Fabricando',
            descripcion: 'En proceso de fabricación',
            color: 'info',
            bgColor: 'bg-info',
            textColor: 'text-info',
            icon: 'fa-cogs',
            categoria: 'fabricando',
            permiteEdicion: true,
            siguientesEstados: [6, 20],
            requiereTapicero: true
        },
        6: {
            id: 6,
            nombre: 'Producto Listo',
            descripcion: 'Fabricación completada',
            color: 'success',
            bgColor: 'bg-success',
            textColor: 'text-success',
            icon: 'fa-check-circle',
            categoria: 'listo',
            permiteEdicion: true,
            siguientesEstados: [7]
        },
        7: {
            id: 7,
            nombre: 'En Despacho',
            descripcion: 'Preparando despacho',
            color: 'success',
            bgColor: 'bg-success',
            textColor: 'text-success',
            icon: 'fa-truck',
            categoria: 'despacho',
            permiteEdicion: true,
            siguientesEstados: [8]
        },
        8: {
            id: 8,
            nombre: 'En Camión',
            descripcion: 'Cargado en vehículo',
            color: 'success',
            bgColor: 'bg-success',
            textColor: 'text-success',
            icon: 'fa-shipping-fast',
            categoria: 'despacho',
            permiteEdicion: true,
            siguientesEstados: [9]
        },
        9: {
            id: 9,
            nombre: 'Entregado',
            descripcion: 'Producto entregado al cliente',
            color: 'success',
            bgColor: 'bg-success',
            textColor: 'text-success',
            icon: 'fa-thumbs-up',
            categoria: 'completado',
            permiteEdicion: false,
            siguientesEstados: [],
            esFinal: true
        },
        20: {
            id: 20,
            nombre: 'Reemitido',
            descripcion: 'Producto reemitido por defecto',
            color: 'danger',
            bgColor: 'bg-danger',
            textColor: 'text-danger',
            icon: 'fa-redo',
            categoria: 'reemision',
            permiteEdicion: true,
            siguientesEstados: [2, 3, 4, 5]
        },
        100: {
            id: 100,
            nombre: 'Eliminado',
            descripcion: 'Producto eliminado del sistema',
            color: 'dark',
            bgColor: 'bg-dark',
            textColor: 'text-dark',
            icon: 'fa-trash',
            categoria: 'eliminado',
            permiteEdicion: false,
            siguientesEstados: [],
            oculto: true
        },
        404: {
            id: 404,
            nombre: 'Error',
            descripcion: 'Estado de error',
            color: 'danger',
            bgColor: 'bg-danger',
            textColor: 'text-danger',
            icon: 'fa-exclamation-triangle',
            categoria: 'error',
            permiteEdicion: false,
            siguientesEstados: [],
            oculto: true
        }
    };

    // ========================================================================
    // CONFIGURACIÓN DE ROLES Y PRIVILEGIOS
    // ========================================================================
    
    const PRIVILEGIOS = {
        0: {
            id: 0,
            nombre: 'Tapicero',
            descripcion: 'Personal de producción',
            permisos: [
                'ver_produccion_propia',
                'actualizar_estados_produccion',
                'ver_productos_asignados'
            ],
            color: 'secondary',
            icon: 'fa-hammer'
        },
        4: {
            id: 4,
            nombre: 'Vendedor',
            descripcion: 'Personal de ventas',
            permisos: [
                'gestionar_ventas',
                'ver_stock',
                'retiro_clientes',
                'ver_pedidos',
                'buscar_clientes'
            ],
            color: 'primary',
            icon: 'fa-user-tie'
        },
        5: {
            id: 5,
            nombre: 'Supervisor',
            descripcion: 'Supervisor de producción',
            permisos: [
                'ver_produccion_general',
                'asignar_tapiceros',
                'cortar_telas',
                'gestionar_esqueletos',
                'ver_reportes_produccion'
            ],
            color: 'info',
            icon: 'fa-user-cog'
        },
        20: {
            id: 20,
            nombre: 'Administrador',
            descripcion: 'Administrador del sistema',
            permisos: [
                'acceso_completo',
                'gestionar_pedidos',
                'control_bodega',
                'logistica',
                'administrar_clientes',
                'reportes_generales'
            ],
            color: 'warning',
            icon: 'fa-crown'
        },
        21: {
            id: 21,
            nombre: 'Super Admin',
            descripcion: 'Administrador con permisos financieros',
            permisos: [
                'acceso_total',
                'gestion_costos',
                'contabilidad_avanzada',
                'reportes_financieros',
                'configuracion_sistema',
                'gestion_usuarios'
            ],
            color: 'danger',
            icon: 'fa-user-shield'
        }
    };

    // ========================================================================
    // CONFIGURACIÓN DE UI
    // ========================================================================
    
    const UI_CONFIG = {
        theme: {
            colors: {
                primary: '#007bff',
                secondary: '#6c757d',
                success: '#28a745',
                warning: '#ffc107',
                danger: '#dc3545',
                info: '#17a2b8',
                light: '#f8f9fa',
                dark: '#343a40'
            },
            breakpoints: {
                xs: 0,
                sm: 576,
                md: 768,
                lg: 992,
                xl: 1200,
                xxl: 1400
            },
            animations: {
                duration: 300,
                easing: 'ease-in-out'
            }
        },
        dataTables: {
            language: {
                lengthMenu: "Mostrar _MENU_ registros",
                zeroRecords: "No se encontraron resultados",
                info: "Mostrando _START_ a _END_ de _TOTAL_ registros",
                infoEmpty: "No hay registros disponibles",
                infoFiltered: "(filtrado de _MAX_ registros totales)",
                search: "Buscar:",
                paginate: {
                    first: "Primero",
                    last: "Último",
                    next: "Siguiente",
                    previous: "Anterior"
                },
                processing: "Procesando..."
            },
            pageLength: 25,
            lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "Todos"]],
            responsive: true,
            order: [[1, 'desc']]
        },
        toast: {
            duration: 4000,
            position: 'top-end',
            showProgressBar: true
        },
        modal: {
            backdrop: 'static',
            keyboard: false,
            focus: true
        }
    };

    // ========================================================================
    // MAPEOS DE DATOS
    // ========================================================================
    
    const MAPEOS = {
        metodosEntrega: {
            'Despacho': {
                nombre: 'Despacho a Domicilio',
                icon: 'fa-truck',
                color: 'primary',
                requiereRuta: true,
                permiteAgencia: true
            },
            'Retiro en tienda': {
                nombre: 'Retiro en Tienda',
                icon: 'fa-store',
                color: 'success',
                requiereRuta: false,
                permiteAgencia: false
            },
            'Starken': {
                nombre: 'Envío por Starken',
                icon: 'fa-shipping-fast',
                color: 'warning',
                requiereRuta: false,
                permiteAgencia: false,
                esAgencia: true
            }
        },
        
        metodosPago: {
            'Efectivo': {
                nombre: 'Efectivo',
                icon: 'fa-money-bill',
                color: 'success',
                requiereValidacion: false,
                esElectronico: false
            },
            'Transbank': {
                nombre: 'Tarjeta (Transbank)',
                icon: 'fa-credit-card',
                color: 'primary',
                requiereValidacion: true,
                esElectronico: true
            },
            'Transferencia': {
                nombre: 'Transferencia Bancaria',
                icon: 'fa-university',
                color: 'info',
                requiereValidacion: true,
                esElectronico: true
            }
        },
        
        tiposBotones: {
            'Normal': {
                nombre: 'Botones Normales',
                icon: 'fa-circle',
                color: 'secondary'
            },
            'B Color': {
                nombre: 'Botones de Colores',
                icon: 'fa-palette',
                color: 'primary'
            },
            'B D': {
                nombre: 'Botón Diamante',
                icon: 'fa-gem',
                color: 'warning'
            }
        },
        
        tiposAnclaje: {
            'no': {
                nombre: 'Sin Anclaje',
                icon: 'fa-times',
                color: 'secondary'
            },
            'si': {
                nombre: 'Con Anclaje',
                icon: 'fa-anchor',
                color: 'info'
            },
            'patas': {
                nombre: 'Patas de Madera',
                icon: 'fa-grip-lines-vertical',
                color: 'success'
            }
        },
        
        regiones: {
            1: 'Tarapacá',
            2: 'Antofagasta', 
            3: 'Atacama',
            4: 'Coquimbo',
            5: 'Valparaíso',
            6: 'O\'Higgins',
            7: 'Maule',
            8: 'Biobío',
            9: 'Araucanía',
            10: 'Los Lagos',
            11: 'Aysén',
            12: 'Magallanes',
            13: 'Metropolitana',
            14: 'Los Ríos',
            15: 'Arica y Parinacota',
            16: 'Ñuble'
        }
    };

    // ========================================================================
    // VALIDACIONES Y PATRONES
    // ========================================================================
    
    const VALIDACIONES = {
        rut: {
            pattern: /^[0-9]{1,2}\.[0-9]{3}\.[0-9]{3}-[0-9kK]{1}$/,
            message: 'Formato: 12.345.678-9'
        },
        telefono: {
            pattern: /^(\+56)?[2-9][0-9]{8}$/,
            message: 'Formato: 987654321 o +56987654321'
        },
        email: {
            pattern: /^[^\s@]+@[^\s@]+\.[^\s@]+$/,
            message: 'Formato de email inválido'
        },
        moneda: {
            pattern: /^[0-9]{1,3}(\.[0-9]{3})*$/,
            message: 'Formato: 1.000.000'
        }
    };

    // ========================================================================
    // CONFIGURACIÓN DE NOTIFICACIONES
    // ========================================================================
    
    const NOTIFICACIONES = {
        tipos: {
            'pedido_nuevo': {
                titulo: 'Nuevo Pedido',
                icon: 'fa-shopping-cart',
                color: 'primary',
                sonido: true
            },
            'pago_recibido': {
                titulo: 'Pago Recibido',
                icon: 'fa-credit-card',
                color: 'success',
                sonido: true
            },
            'producto_listo': {
                titulo: 'Producto Listo',
                icon: 'fa-check-circle',
                color: 'success',
                sonido: false
            },
            'estado_actualizado': {
                titulo: 'Estado Actualizado',
                icon: 'fa-sync-alt',
                color: 'info',
                sonido: false
            },
            'sistema': {
                titulo: 'Notificación del Sistema',
                icon: 'fa-cog',
                color: 'secondary',
                sonido: false
            }
        },
        configuracion: {
            intervaloActualizacion: 30000, // 30 segundos
            maxNotificacionesVisible: 10,
            autoOcultarDespues: 5000,
            habilitarSonidos: true
        }
    };

    // ========================================================================
    // CONFIGURACIÓN DE EXPORTACIÓN
    // ========================================================================
    
    const EXPORTACION = {
        formatos: {
            'excel': {
                nombre: 'Excel (.xlsx)',
                icono: 'fa-file-excel',
                color: 'success',
                extension: '.xlsx'
            },
            'pdf': {
                nombre: 'PDF (.pdf)',
                icono: 'fa-file-pdf',
                color: 'danger',
                extension: '.pdf'
            },
            'csv': {
                nombre: 'CSV (.csv)',
                icono: 'fa-file-csv',
                color: 'info',
                extension: '.csv'
            }
        },
        configuracion: {
            maxRegistros: 10000,
            formatoFecha: 'DD/MM/YYYY',
            separadorCSV: ';',
            codificacion: 'UTF-8'
        }
    };

    // ========================================================================
    // CONFIGURACIÓN DE BÚSQUEDA
    // ========================================================================
    
    const BUSQUEDA = {
        tiposBusqueda: {
            'global': {
                nombre: 'Búsqueda Global',
                placeholder: 'Buscar en todo el sistema...',
                minCaracteres: 2,
                delay: 300
            },
            'pedidos': {
                nombre: 'Buscar Pedidos',
                placeholder: 'Número de orden, RUT cliente...',
                minCaracteres: 1,
                delay: 200
            },
            'clientes': {
                nombre: 'Buscar Clientes',
                placeholder: 'Nombre, RUT, teléfono...',
                minCaracteres: 2,
                delay: 300
            },
            'productos': {
                nombre: 'Buscar Productos',
                placeholder: 'Modelo, color, material...',
                minCaracteres: 2,
                delay: 300
            }
        }
    };

    // ========================================================================
    // FUNCIONES UTILITARIAS
    // ========================================================================
    
    const UTILS = {
        formatearRut: function(rut) {
            if (!rut) return '';
            const cleaned = rut.replace(/[^0-9kK]/g, '');
            if (cleaned.length < 8) return rut;
            
            const body = cleaned.slice(0, -1);
            const dv = cleaned.slice(-1);
            return body.replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1.') + '-' + dv;
        },
        
        validarRut: function(rut) {
            if (!/^\d{1,3}\.\d{3}\.\d{3}-[\dkK]$/i.test(rut)) return false;
            const [body, dv] = rut.split('-');
            const cleanBody = body.replace(/\./g, '');
            return this.calcularDV(cleanBody) === dv.toLowerCase();
        },
        
        calcularDV: function(rut) {
            let suma = 0;
            let multiplicador = 2;
            
            for (let i = rut.length - 1; i >= 0; i--) {
                suma += parseInt(rut[i]) * multiplicador;
                multiplicador = multiplicador === 7 ? 2 : multiplicador + 1;
            }
            
            const resto = suma % 11;
            const dv = 11 - resto;
            
            if (dv === 11) return '0';
            if (dv === 10) return 'k';
            return dv.toString();
        },
        
        formatearMoneda: function(valor) {
            if (!valor && valor !== 0) return '$0';
            const numero = parseFloat(valor.toString().replace(/[^0-9.-]/g, ''));
            return '$' + numero.toLocaleString('es-CL');
        },
        
        formatearFecha: function(fecha, formato = 'DD/MM/YYYY HH:mm') {
            if (!fecha) return '';
            const date = new Date(fecha);
            return date.toLocaleDateString('es-CL') + ' ' + date.toLocaleTimeString('es-CL', {
                hour: '2-digit',
                minute: '2-digit'
            });
        },
        
        obtenerEstadoPorId: function(id) {
            return ESTADOS_PEDIDO[id] || ESTADOS_PEDIDO[404];
        },
        
        obtenerPrivilegioPorId: function(id) {
            return PRIVILEGIOS[id] || PRIVILEGIOS[0];
        },
        
        esMobile: function() {
            return window.innerWidth <= UI_CONFIG.theme.breakpoints.md;
        },
        
        generarId: function() {
            return Date.now().toString(36) + Math.random().toString(36).substr(2);
        }
    };

    // ========================================================================
    // API PÚBLICA
    // ========================================================================
    
    return {
        // Información del sistema
        SYSTEM_INFO: Object.freeze(SYSTEM_INFO),
        
        // Configuraciones
        API_CONFIG: Object.freeze(API_CONFIG),
        UI_CONFIG: Object.freeze(UI_CONFIG),
        
        // Estados y procesos
        ESTADOS_PEDIDO: Object.freeze(ESTADOS_PEDIDO),
        PRIVILEGIOS: Object.freeze(PRIVILEGIOS),
        
        // Mapeos de datos
        MAPEOS: Object.freeze(MAPEOS),
        VALIDACIONES: Object.freeze(VALIDACIONES),
        NOTIFICACIONES: Object.freeze(NOTIFICACIONES),
        EXPORTACION: Object.freeze(EXPORTACION),
        BUSQUEDA: Object.freeze(BUSQUEDA),
        
        // Utilidades
        UTILS: UTILS,
        
        // Métodos de acceso rápido
        getEstado: function(id) {
            return UTILS.obtenerEstadoPorId(id);
        },
        
        getPrivilegio: function(id) {
            return UTILS.obtenerPrivilegioPorId(id);
        },
        
        // Método para obtener configuración completa
        getConfig: function() {
            return {
                system: SYSTEM_INFO,
                api: API_CONFIG,
                ui: UI_CONFIG,
                estados: ESTADOS_PEDIDO,
                privilegios: PRIVILEGIOS,
                mapeos: MAPEOS,
                validaciones: VALIDACIONES,
                notificaciones: NOTIFICACIONES,
                exportacion: EXPORTACION,
                busqueda: BUSQUEDA
            };
        }
    };

})();

// ========================================================================
// EXPORTAR A NAMESPACE GLOBAL
// ========================================================================

// Hacer disponibles las constantes globalmente
window.RespaldosChileConstants = RespaldosChileConstants;

// Aliases para acceso rápido
window.RC_ESTADOS = RespaldosChileConstants.ESTADOS_PEDIDO;
window.RC_PRIVILEGIOS = RespaldosChileConstants.PRIVILEGIOS;
window.RC_CONFIG = RespaldosChileConstants.UI_CONFIG;
window.RC_UTILS = RespaldosChileConstants.UTILS;

// ========================================================================
// INICIALIZACIÓN
// ========================================================================

$(document).ready(function() {
    console.log('✅ Constantes del sistema cargadas correctamente');
    console.log(`📊 Sistema: ${RespaldosChileConstants.SYSTEM_INFO.name} v${RespaldosChileConstants.SYSTEM_INFO.version}`);
    
    // Configurar defaults globales para jQuery AJAX
    $.ajaxSetup({
        timeout: RespaldosChileConstants.API_CONFIG.timeout,
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    });
    
    // Configurar defaults para DataTables si está disponible
    if ($.fn.DataTable) {
        $.extend(true, $.fn.dataTable.defaults, {
            language: RespaldosChileConstants.UI_CONFIG.dataTables.language,
            pageLength: RespaldosChileConstants.UI_CONFIG.dataTables.pageLength,
            lengthMenu: RespaldosChileConstants.UI_CONFIG.dataTables.lengthMenu,
            responsive: RespaldosChileConstants.UI_CONFIG.dataTables.responsive,
            order: RespaldosChileConstants.UI_CONFIG.dataTables.order
        });
    }
    
    // Configurar tema si hay preferencias guardadas
    const darkMode = localStorage.getItem('darkMode');
    if (darkMode === 'true') {
        document.body.classList.add('dark-mode');
    }
});

// ========================================================================
// DEFINICIÓN DE TIPOS (Para documentación)
// ========================================================================

/**
 * @typedef {Object} EstadoPedido
 * @property {number} id - ID del estado
 * @property {string} nombre - Nombre del estado
 * @property {string} descripcion - Descripción del estado
 * @property {string} color - Color Bootstrap del estado
 * @property {string} bgColor - Clase de fondo del estado
 * @property {string} textColor - Clase de texto del estado
 * @property {string} icon - Icono FontAwesome del estado
 * @property {string} categoria - Categoría del estado
 * @property {boolean} permiteEdicion - Si permite edición
 * @property {number[]} siguientesEstados - Estados siguientes permitidos
 * @property {boolean} [esUrgente] - Si es estado urgente
 * @property {boolean} [requiereTapicero] - Si requiere tapicero asignado
 * @property {boolean} [esFinal] - Si es estado final
 * @property {boolean} [oculto] - Si está oculto en las vistas
 */

/**
 * @typedef {Object} Privilegio
 * @property {number} id - ID del privilegio
 * @property {string} nombre - Nombre del rol
 * @property {string} descripcion - Descripción del rol
 * @property {string[]} permisos - Lista de permisos
 * @property {string} color - Color Bootstrap del rol
 * @property {string} icon - Icono FontAwesome del rol
 */