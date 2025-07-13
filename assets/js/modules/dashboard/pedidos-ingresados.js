/**
 * ============================================================================
 * MÓDULO: PEDIDOS INGRESADOS v2.0 - MANTENIENDO ESTRUCTURA ORIGINAL
 * ============================================================================
 * Gestión completa de pedidos ingresados en RespaldosChile
 * @version 2.0 - SOLO removiendo +/- y adaptando colores, MANTENIENDO fila padre/hija
 * @author RespaldosChile Team
 * @requires jQuery, DataTables, Bootstrap 5
 * ============================================================================
 */

const PedidosIngresados = (function($) {
    'use strict';

    // ========================================================================
    // CONFIGURACIÓN Y VARIABLES - MANTENIENDO ORIGINAL
    // ========================================================================
    
    const CONFIG = {
        apiEndpoints: {
            extraerOrdenes: 'api/extraer_ordenes.php',
            detalleOrden: 'api/get_order_details.php',
            actualizarEstado: 'api/actualizar_estado.php',
            buscarPagos: 'api/buscarPagos.php',
            obtenerHistorial: 'api/get_historial.php'
        },
        tableConfig: {
            pageLength: 25,
            language: {
                lengthMenu: "Mostrar _MENU_ pedidos",
                zeroRecords: "No se encontraron pedidos",
                info: "Mostrando _START_ a _END_ de _TOTAL_ pedidos",
                infoEmpty: "No hay pedidos disponibles",
                infoFiltered: "(filtrado de _MAX_ pedidos totales)",
                search: "Buscar:",
                paginate: {
                    first: "Primero",
                    last: "Último",
                    next: "Siguiente",
                    previous: "Anterior"
                },
                processing: "Cargando pedidos..."
            }
        },
        estados: {
            0: { nombre: 'No Aceptado', color: 'warning', icon: 'fa-clock' },
            1: { nombre: 'Aceptado', color: 'info', icon: 'fa-check' },
            2: { nombre: 'Por Fabricar', color: 'warning', icon: 'fa-tools' },
            3: { nombre: 'Tela Cortada', color: 'primary', icon: 'fa-cut' },
            4: { nombre: 'Armando Esqueleto', color: 'primary', icon: 'fa-hammer' },
            5: { nombre: 'Fabricando', color: 'info', icon: 'fa-cogs' },
            6: { nombre: 'Producto Listo', color: 'success', icon: 'fa-check-circle' },
            7: { nombre: 'En Despacho', color: 'success', icon: 'fa-truck' },
            8: { nombre: 'En Camión', color: 'success', icon: 'fa-shipping-fast' },
            9: { nombre: 'Entregado', color: 'success', icon: 'fa-thumbs-up' },
            20: { nombre: 'Reemitido', color: 'danger', icon: 'fa-redo' }
        }
    };

    let tablaPedidos = null;
    let detallesExpandidos = localStorage.getItem('detallesExpandidos') === 'true';
    let cache = {
        tapiceros: {},
        ultimaActualizacion: 0
    };

    // ========================================================================
    // INICIALIZACIÓN - MANTENIENDO ORIGINAL
    // ========================================================================
    
    function init() {
        console.log('🚀 Inicializando módulo Pedidos Ingresados v2.0...');
        
        try {
            initEventListeners();
            initDataTable();
            initControles();
            loadInitialData();
            
            console.log('✅ Módulo Pedidos Ingresados v2.0 cargado correctamente');
        } catch (error) {
            console.error('❌ Error inicializando módulo:', error);
            if (typeof UIComponents !== 'undefined') {
                UIComponents.showAlert('Error al inicializar el módulo de pedidos', 'error');
            }
        }
    }

    // ========================================================================
    // INICIALIZACIÓN DE DATATABLE - SIN COLUMNA DE CONTROL, MANTENIENDO TODO LO DEMÁS
    // ========================================================================
    
    function initDataTable() {
        if ($.fn.DataTable.isDataTable('#pedidosTable')) {
            $('#pedidosTable').DataTable().destroy();
        }

        tablaPedidos = $('#pedidosTable').DataTable({
            ...CONFIG.tableConfig,
            processing: true,
            serverSide: false,
            ajax: {
                url: CONFIG.apiEndpoints.extraerOrdenes,
                type: "POST",
                data: function(d) {
                    d.modulo = "dashboard";
                },
                dataSrc: function(json) {
                    processTableData(json);
                    return json.data || [];
                },
                error: function(xhr, error, thrown) {
                    console.error("Error cargando datos:", error);
                    if (typeof UIComponents !== 'undefined') {
                        UIComponents.showAlert('Error al cargar los pedidos', 'error');
                    }
                }
            },
            columns: getTableColumns(), // ⭐ SIN COLUMNA DE CONTROL
            order: [[0, 'desc']], // ⭐ AHORA ES COLUMNA 0 (antes era 1)
            dom: '<"table-controls"<"left-controls"l><"center-controls"B><"right-controls"f>>rtip',
            buttons: getTableButtons(),
            rowCallback: function(row, data) {
                // ⭐ AGREGAR CLASE PARA CLIC EN FILA
                $(row).addClass('clickable-row');
                
                // ⭐ NUEVO: DETERMINAR COLOR DE FILA SEGÚN ESTADOS
                const rowClass = determineRowClass(data.detalles);
                if (rowClass) {
                    $(row).addClass(rowClass);
                }
            },
            initComplete: function() {
                console.log('✅ DataTable inicializada');
                updateStatsCards();
                
                // ⭐ CONFIGURAR CLIC EN FILAS
                setupRowClickHandler();
                
                if (detallesExpandidos) {
                    toggleAllDetails(true);
                }
            },
            drawCallback: function() {
                updateStatsCards();
                initTooltips();
                
                // ⭐ RECONFIGURAR CLIC DESPUÉS DE CADA REDIBUJADO
                setupRowClickHandler();
            }
        });
    }

    // ========================================================================
    // CONFIGURACIÓN DE COLUMNAS - SIN COLUMNA DE CONTROL
    // ========================================================================
    
    function getTableColumns() {
        return [
            // ⭐ YA NO HAY COLUMNA DE CONTROL - EMPEZAMOS DIRECTO CON ORDEN
            {
                data: "num_orden",
                title: "Orden",
                render: function(data, type, row) {
                    const badge = row.orden_ext ? 
                        '<span class="badge bg-warning ms-2">ONLINE</span>' : 
                        '<span class="badge bg-primary ms-2">TIENDA</span>';
                    return `<span class="order-id">#${data}</span>${badge}`;
                }
            },
            {
                data: null,
                title: "Estado",
                orderable: false,
                width: "120px",
                render: function(data, type, row) {
                    return getOrderStatusBadge(row.detalles);
                }
            },
            {
                data: "rut_cliente",
                title: "RUT Cliente",
                render: function(data, type, row) {
                    const instagramIcon = row.instagram ? 
                        ` <i class="fab fa-instagram text-pink" title="Instagram: ${row.instagram}"></i>` : '';
                    
                    return `<a href="../online/dashboard/ver_ordenes_cliente.php?rut=${data}" 
                               target="_blank" 
                               class="text-decoration-none fw-bold">
                               ${data}${instagramIcon}
                            </a>`;
                }
            },
            {
                data: "nombre",
                title: "Cliente",
                render: function(data, type, row) {
                    return `<div class="client-info">
                                <div class="client-name">${data}</div>
                                <small class="text-muted">${row.telefono || 'Sin teléfono'}</small>
                            </div>`;
                }
            },
            {
                data: null,
                title: "Dirección",
                render: function(data, type, row) {
                    const direccion = `${row.direccion || ''} ${row.numero || ''}`.trim();
                    return `<div class="contact-address">
                                <span class="fw-medium">${direccion}</span>
                                <br><small class="text-muted">${row.comuna || ''}</small>
                            </div>`;
                }
            },
           {
    data: "telefono",
    title: "Contacto",
    render: function(data, type, row) {
        if (!data) {
            return '<span class="text-muted no-phone">Sin teléfono</span>';
        }
        
        const whatsappUrl = `https://api.whatsapp.com/send/?phone=+56${data}&text=Hola! Te escribimos de RespaldosChile sobre tu pedido`;
        
        // 🎯 OPCIÓN 1: TODO EN UNO (RECOMENDADA)
        return `<a href="${whatsappUrl}" target="_blank" class="whatsapp-integrated">
                    <i class="fab fa-whatsapp"></i>
                    <span class="phone-display">${data}</span>
                </a>`;
        
        // 🎯 OPCIÓN 2: COMPACTO Y ELEGANTE (ALTERNATIVA)
        /*
        return `<div class="contact-compact">
                    <span class="phone-number">${data}</span>
                    <a href="${whatsappUrl}" target="_blank" class="whatsapp-btn-compact">
                        <i class="fab fa-whatsapp"></i>
                    </a>
                </div>`;
        */
        
        // 🎯 OPCIÓN 3: MINIMALISTA (ALTERNATIVA)
        /*
        return `<div class="contact-minimal">
                    <span class="phone-minimal">${data}</span>
                    <a href="${whatsappUrl}" target="_blank" class="whatsapp-btn-minimal">
                        <i class="fab fa-whatsapp"></i>
                    </a>
                </div>`;
        */
    }
},
            {
                data: null,
                title: "Total",
                render: function(data, type, row) {
                    const total = calculateOrderTotal(row.detalles);
                    return `<span class="order-total">${formatNumber(total)}</span>`;
                }
            },
            {
                data: "mododepago",
                title: "Forma Pago",
                render: function(data, type, row) {
                    return formatPaymentMethod(data);
                }
            },
            {
                data: null,
                title: "Acciones",
                orderable: false,
                width: "150px",
                render: function(data, type, row) {
                    return `
                        <div class="table-actions">
                            <button type="button" class="btn btn-view btn-gestionar-orden" 
                                    data-orden="${row.num_orden}" title="Gestionar Orden">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button type="button" class="btn btn-history btn-ver-historial" 
                                    data-orden="${row.num_orden}" title="Ver Historial">
                                <i class="fas fa-history"></i>
                            </button>
                            <button type="button" class="btn btn-edit btn-marcar-completo" 
                                    data-orden="${row.num_orden}" title="Marcar Completo">
                                <i class="fas fa-check-circle"></i>
                            </button>
                        </div>`;
                }
            }
        ];
    }

    // ========================================================================
    // ⭐ NUEVO: MANEJO DE CLIC EN FILAS PARA EXPANDIR
    // ========================================================================
    
    function setupRowClickHandler() {
        // Remover listeners previos para evitar duplicados
        $('#pedidosTable tbody').off('click', 'tr.clickable-row');
        
        // ⭐ CONFIGURAR CLIC EN FILA PARA EXPANDIR/CONTRAER
        $('#pedidosTable tbody').on('click', 'tr.clickable-row', function(e) {
            // No expandir si se hace clic en botones de acción
            if ($(e.target).closest('.table-actions').length > 0) {
                return;
            }
            
            const tr = $(this);
            const row = tablaPedidos.row(tr);

            if (row.child.isShown()) {
                // ⭐ CONTRAER FILA HIJA
                row.child.hide();
                tr.removeClass('shown');
            } else {
                // ⭐ EXPANDIR FILA HIJA CON PRODUCTOS
                row.child(formatDetalles(row.data())).show(); // ⭐ MANTENER TU FUNCIÓN ORIGINAL
                tr.addClass('shown');
            }
        });
    }

    // ========================================================================
    // FUNCIONES DE FORMATO - ACTUALIZADAS PARA NUEVOS COLORES
    // ========================================================================

    function formatPaymentMethod(data) {
        if (!data) return '<span class="payment-method">No especificado</span>';
        
        const methodLower = data.toLowerCase();
        let className = 'payment-method';
        let text = data;
        
        if (methodLower.includes('transferencia') || methodLower.includes('transfer')) {
            className += ' transferencia';
            text = 'Transferencia';
        } else if (methodLower.includes('efectivo') || methodLower.includes('cash')) {
            className += ' efectivo';
            text = 'Efectivo';
        } else if (methodLower.includes('transbank') || methodLower.includes('tarjeta')) {
            className += ' tarjeta';
            text = 'Tarjeta';
        }
        
        return `<span class="${className}">${text}</span>`;
    }

    function getOrderStatusBadge(detalles) {
        if (!detalles || detalles.length === 0) {
            return '<span class="status-badge">Sin productos</span>';
        }

        const estados = detalles.map(d => parseInt(d.estadopedido));
        const todosEntregados = estados.every(e => e === 9);
        const algunosFabricando = estados.some(e => e === 5);
        const algunosPendientes = estados.some(e => [0, 1, 2].includes(e));
        const algunosNoAceptados = estados.some(e => e === 0);
        const algunosPorFabricar = estados.some(e => e === 2);
        
        if (todosEntregados) {
            return '<span class="status-badge entregado">Entregado</span>';
        } else if (algunosNoAceptados) {
            return '<span class="status-badge no-aceptado">No Aceptado</span>';
        } else if (algunosPorFabricar) {
            return '<span class="status-badge por-fabricar">Por Fabricar</span>';
        } else if (algunosFabricando) {
            return '<span class="status-badge fabricando">Fabricando</span>';
        } else if (algunosPendientes) {
            return '<span class="status-badge pendiente">Pendiente</span>';
        } else {
            return '<span class="status-badge fabricando">En Proceso</span>';
        }
    }

    // ========================================================================
    // FUNCIÓN formatDetalles - MANTENIENDO 100% ORIGINAL
    // ========================================================================
    
    function formatDetalles(d) {
        if (!d || !d.detalles || d.detalles.length === 0) {
            return `<div class="alert alert-info m-2">
                        <i class="fas fa-info-circle"></i> No hay productos en este pedido
                    </div>`;
        }

        let html = `
            <div class="row mx-2 my-3">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header bg-light">
                            <h6 class="mb-0">
                                <i class="fas fa-list-alt text-primary"></i> 
                                Productos del Pedido #${d.num_orden}
                                <span class="badge bg-primary ms-2">${d.detalles.length} producto(s)</span>
                            </h6>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover mb-0" style="font-size: 0.9rem;">
                                    <thead class="table-light">
                                        <tr>
                                            <th width="60">ID</th>
                                            <th width="150">Producto</th>
                                            <th width="100">Tamaño</th>
                                            <th width="100">Material</th>
                                            <th width="100">Color</th>
                                            <th width="80">Cant.</th>
                                            <th width="100">Precio</th>
                                            <th width="150">Estado</th>
                                            <th width="200">Observaciones</th>
                                            <th width="120">Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>`;

        d.detalles.forEach(detalle => {
            const estadoInfo = CONFIG.estados[detalle.estadopedido] || 
                             { nombre: 'Desconocido', color: 'secondary', icon: 'fa-question' };
            
            html += `
                <tr class="detalle-row" data-estado="${detalle.estadopedido}">
                    <td>
                        <span class="badge bg-secondary">${detalle.id}</span>
                    </td>
                    <td>
                        <strong>${detalle.modelo || 'N/A'}</strong>
                        ${getProductIcons(detalle)}
                    </td>
                    <td>${detalle.tamano || 'N/A'}</td>
                    <td>${detalle.material || detalle.tipotela || 'N/A'}</td>
                    <td>
                        <span class="badge bg-light text-dark">${detalle.color || 'N/A'}</span>
                    </td>
                    <td class="text-center">${detalle.cantidad || 1}</td>
                    <td class="text-end">
                        <strong>${formatNumber(detalle.precio)}</strong>
                    </td>
                    <td>
                        <span class="badge bg-${estadoInfo.color}">
                            <i class="fas ${estadoInfo.icon}"></i> ${estadoInfo.nombre}
                        </span>
                    </td>
                    <td>
                        <small class="text-muted">
                            ${detalle.comentarios || 'Sin observaciones'}
                        </small>
                    </td>
                    <td>
                        ${getProductActionButtons(detalle)}
                    </td>
                </tr>`;
        });

        html += `
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>`;

        return html;
    }

    // ========================================================================
    // CONFIGURACIÓN DE BOTONES - MANTENIENDO ORIGINAL
    // ========================================================================
    
    function getTableButtons() {
        return [
            {
                text: '<i class="fas fa-sync-alt"></i> Actualizar',
                className: 'btn btn-outline-primary btn-sm',
                action: function() {
                    refreshTable();
                }
            },
            {
                text: '<i class="fas fa-expand-arrows-alt"></i> Expandir Todo',
                className: 'btn btn-outline-secondary btn-sm',
                attr: { id: 'btnToggleAll' },
                action: function() {
                    toggleAllDetails();
                }
            },
            {
                text: '<i class="fas fa-download"></i> Exportar',
                className: 'btn btn-outline-success btn-sm',
                action: function() {
                    exportTableData();
                }
            }
        ];
    }

    // ========================================================================
    // TODAS LAS DEMÁS FUNCIONES - MANTENIENDO 100% ORIGINALES
    // ========================================================================

    function processTableData(json) {
        if (!json.data) return;
        
        json.data.forEach(pedido => {
            if (pedido.detalles) {
                pedido.detalles.forEach(detalle => {
                    if (CONFIG.estados[detalle.estadopedido]) {
                        detalle.estado_info = CONFIG.estados[detalle.estadopedido];
                    }
                    detalle.precio_formateado = formatNumber(detalle.precio);
                });
            }
        });
    }

    function getProductIcons(detalle) {
        let icons = '';
        
        if (detalle.tipo_boton === "B Color") {
            icons += '<i class="fas fa-palette text-primary ms-1" title="Botones de Colores"></i>';
        }
        if (detalle.tipo_boton === "B D") {
            icons += '<i class="fas fa-gem text-warning ms-1" title="Botón Diamante"></i>';
        }
        if (detalle.anclaje === "si") {
            icons += '<i class="fas fa-anchor text-info ms-1" title="Con Anclaje"></i>';
        }
        if (detalle.anclaje === "patas") {
            icons += '<i class="fas fa-grip-lines-vertical text-success ms-1" title="Patas de madera"></i>';
        }
        
        return icons;
    }

    function getProductActionButtons(detalle) {
        const estadoId = detalle.estadopedido;
        let buttons = `
            <div class="btn-group btn-group-sm" role="group">
                <button type="button" class="btn btn-outline-warning btn-editar-producto" 
                        data-id="${detalle.id}" title="Editar Producto">
                    <i class="fas fa-edit"></i>
                </button>
                <button type="button" class="btn btn-outline-info btn-cambiar-estado" 
                        data-id="${detalle.id}" data-estado="${estadoId}" title="Cambiar Estado">
                    <i class="fas fa-exchange-alt"></i>
                </button>`;
        
        if (estadoId !== "9") {
            buttons += `
                <button type="button" class="btn btn-outline-success btn-marcar-entregado" 
                        data-id="${detalle.id}" title="Marcar Entregado">
                    <i class="fas fa-check-circle"></i>
                </button>`;
        }
        
        buttons += `</div>`;
        return buttons;
    }

    // ========================================================================
    // ⭐ NUEVA FUNCIÓN: DETERMINAR COLOR DE FILA SEGÚN ESTADOS
    // ========================================================================
    
    function determineRowClass(detalles) {
        if (!detalles) return '';
        
        const estados = detalles.map(d => parseInt(d.estadopedido));
        
        // Prioridad: el estado "más relevante" define el color de la fila
        if (estados.some(e => e === 0)) return 'table-no-aceptado';          // Amarillo suave
        if (estados.some(e => e === 2)) return 'table-por-fabricar';         // Amarillo medio
        if (estados.some(e => [3,4,5].includes(e))) return 'table-fabricando'; // Celeste claro
        if (estados.some(e => e === 6)) return 'table-producto-listo';       // Turquesa
        if (estados.some(e => [7,8].includes(e))) return 'table-en-despacho'; // Verde lima
        if (estados.every(e => e === 9)) return 'table-entregado';           // Verde esmeralda
        
        return ''; // Sin clase = blanco (aceptados sin otros estados)
    }

    function hasUrgentStatus(detalles) {
        if (!detalles) return false;
        return detalles.some(d => d.estadopedido === "2");
    }

    function calculateOrderTotal(detalles) {
        if (!detalles) return 0;
        return detalles.reduce((total, detalle) => {
            const precio = parseFloat(detalle.precio?.toString().replace(/[$,.]/g, '') || 0);
            return total + precio;
        }, 0);
    }

    function formatNumber(number) {
        if (!number) return '0';
        const num = parseFloat(number.toString().replace(/[$,.]/g, ''));
        return num.toLocaleString('es-CL');
    }

    // ========================================================================
    // EVENT LISTENERS - MANTENIENDO ORIGINAL PERO SIN dt-control
    // ========================================================================
    
    function initEventListeners() {
        // ⭐ YA NO HAY LISTENER PARA dt-control - SE MANEJA EN setupRowClickHandler
        
        $(document).on('click', '.btn-gestionar-orden', handleGestionarOrden);
        $(document).on('click', '.btn-ver-historial', handleVerHistorial);
        $(document).on('click', '.btn-marcar-completo', handleMarcarCompleto);
        
        // ⭐ NUEVOS EVENT LISTENERS PARA EDITAR PRODUCTOS ⭐
        $(document).on('click', '.btn-editar-producto', handleEditarProducto);
        $(document).on('click', '.btn-cambiar-estado', handleCambiarEstado);
        $(document).on('click', '.btn-marcar-entregado', handleMarcarEntregado);
        
        $('#alwaysExpandDetails').on('change', function() {
            const isChecked = $(this).is(':checked');
            toggleAllDetails(isChecked);
            localStorage.setItem('detallesExpandidos', isChecked);
        });

        // ⭐ EVENTO PERSONALIZADO PARA ACTUALIZAR CUANDO SE EDITA UN PRODUCTO ⭐
        $(document).on('productoActualizado', function(event, productoId) {
            console.log('Producto actualizado:', productoId);
            refreshTable();
        });

        $(document).on('ordenActualizada', function(event, numOrden) {
            console.log('Orden actualizada:', numOrden);
            refreshTable();
        });
    }

    // ========================================================================
    // MANEJADORES DE EVENTOS - MANTENIENDO 100% ORIGINALES
    // ========================================================================
    
    function handleGestionarOrden() {
        const numOrden = $(this).data('orden');
        
        if (typeof UIComponents !== 'undefined') {
            UIComponents.showLoading(true, 'Cargando información de la orden...');
        }
        
        // ⭐ USAR EL MODAL EXISTENTE DE GESTIÓN ⭐
        if (typeof abrirModalGestionOrden === 'function') {
            abrirModalGestionOrden(numOrden);
            if (typeof UIComponents !== 'undefined') {
                UIComponents.showLoading(false);
            }
        } else {
            // Fallback usando APIClient
            if (typeof APIClient !== 'undefined') {
                APIClient.getOrderDetails(numOrden)
                    .then(response => {
                        if (response.success) {
                            openOrderManagementModal(response.data);
                        } else {
                            UIComponents.showAlert('Error al cargar los detalles de la orden', 'error');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        UIComponents.showAlert('Error de conexión', 'error');
                    })
                    .finally(() => {
                        UIComponents.showLoading(false);
                    });
            } else {
                console.warn('Modal de gestión de orden no disponible');
                if (typeof UIComponents !== 'undefined') {
                    UIComponents.showLoading(false);
                    UIComponents.showAlert('Modal de gestión no disponible', 'warning');
                }
            }
        }
    }

    function handleVerHistorial() {
        const numOrden = $(this).data('orden');
        
        // Verificar que el modal esté disponible
        if (typeof ModalHistorialOrden === 'undefined') {
            console.error('ModalHistorialOrden no está disponible. Asegurar que modal-historial-orden.js esté incluido.');
            if (typeof UIComponents !== 'undefined') {
                UIComponents.showAlert('Error: Modal de historial no disponible', 'error');
            } else {
                alert('Error: Modal de historial no disponible');
            }
            return;
        }
        
        // Usar el modal separado para cargar el historial
        ModalHistorialOrden.cargar(numOrden);
    }

    function handleMarcarCompleto() {
        const numOrden = $(this).data('orden');
        
        if (typeof UIComponents !== 'undefined') {
            UIComponents.showConfirm(
                `¿Marcar todos los productos de la orden #${numOrden} como entregados?`,
                'Confirmar Orden Completa'
            ).then(result => {
                if (result.isConfirmed) {
                    marcarOrdenCompleta(numOrden);
                }
            });
        } else {
            if (confirm(`¿Marcar todos los productos de la orden #${numOrden} como entregados?`)) {
                marcarOrdenCompleta(numOrden);
            }
        }
    }

    // ⭐ NUEVOS MANEJADORES PARA EDITAR PRODUCTOS ⭐
    function handleEditarProducto() {
        const productoId = $(this).data('id');
        
        // Verificar que el modal esté disponible
        if (typeof abrirModalEditarProducto === 'function') {
            abrirModalEditarProducto(productoId);
        } else {
            console.error('Modal de editar producto no encontrado. Asegurar que modal_editar_producto.php esté incluido.');
            if (typeof UIComponents !== 'undefined') {
                UIComponents.showAlert('Error: Modal de edición no disponible', 'error');
            }
        }
    }

    function handleCambiarEstado() {
        const productoId = $(this).data('id');
        const estadoActual = $(this).data('estado');
        
        mostrarModalCambioEstado(productoId, estadoActual);
    }
    
    /**
     * ⭐ NUEVO: Mostrar modal simple para cambio de estado ⭐
     */
    function mostrarModalCambioEstado(productoId, estadoActual) {
        const estadosOptions = {
            '0': 'No Aceptado',
            '1': 'Aceptado', 
            '2': 'Por Fabricar',
            '3': 'Tela Cortada',
            '4': 'Armando Esqueleto',
            '5': 'Fabricando',
            '6': 'Producto Listo',
            '7': 'En Despacho',
            '8': 'En Camión',
            '9': 'Entregado',
            '20': 'Reemitido'
        };
        
        if (typeof UIComponents !== 'undefined') {
            UIComponents.showCustomConfirm({
                title: 'Cambiar Estado del Producto',
                html: `
                    <div class="mb-3">
                        <label for="nuevoEstadoSelect" class="form-label">Seleccionar nuevo estado:</label>
                        <select id="nuevoEstadoSelect" class="form-select">
                            ${Object.entries(estadosOptions).map(([value, label]) => 
                                `<option value="${value}" ${value === estadoActual ? 'selected' : ''}>${label}</option>`
                            ).join('')}
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="observacionEstado" class="form-label">Observación (opcional):</label>
                        <textarea id="observacionEstado" class="form-control" rows="2" 
                                  placeholder="Comentario sobre el cambio de estado..."></textarea>
                    </div>
                `,
                confirmText: 'Cambiar Estado',
                cancelText: 'Cancelar'
            }).then(result => {
                if (result.isConfirmed) {
                    const nuevoEstado = $('#nuevoEstadoSelect').val();
                    const observacion = $('#observacionEstado').val();
                    
                    if (nuevoEstado !== estadoActual) {
                        cambiarEstadoProducto(productoId, nuevoEstado, observacion);
                    } else {
                        UIComponents.showToast('No se realizaron cambios', 'info');
                    }
                }
            });
        } else {
            // Fallback simple
            const nuevoEstado = prompt(`Nuevo estado para producto ${productoId}:\n${Object.entries(estadosOptions).map(([k,v]) => `${k}: ${v}`).join('\n')}`);
            if (nuevoEstado && nuevoEstado !== estadoActual) {
                cambiarEstadoProducto(productoId, nuevoEstado);
            }
        }
    }
    
    /**
     * ⭐ NUEVO: Cambiar estado del producto via API ⭐
     */
    // ========================================================================
// 🎯 ACTUALIZACIONES OPTIMIZADAS - SOLO EL PRODUCTO QUE CAMBIÓ
// ========================================================================

/**
 * ⭐ NUEVO: Cambiar estado del producto CON actualización local inmediata
 */
function cambiarEstadoProducto(productoId, nuevoEstado, observacion = '') {
    // 1. 🎯 ACTUALIZAR UI INMEDIATAMENTE (Usuario ve el cambio al instante)
    actualizarEstadoEnUI(productoId, nuevoEstado);
    
    // 2. 💾 GUARDAR EN SERVIDOR (En background)
    if (typeof APIClient !== 'undefined') {
        APIClient.updateProductStatus(productoId, nuevoEstado, observacion)
            .then(response => {
                if (response.status === 'success') {
                    // ✅ TODO OK - Confirmar visualmente
                    mostrarConfirmacionCambio(productoId, nuevoEstado);
                    
                    // 📊 Actualizar solo las estadísticas (sin recargar tabla)
                    updateStatsCards();
                } else {
                    // ❌ ERROR DEL SERVIDOR - Revertir cambio en UI
                    console.error('Error del servidor:', response.message);
                    revertirCambioEnUI(productoId);
                    
                    if (typeof UIComponents !== 'undefined') {
                        UIComponents.showAlert('Error al actualizar: ' + response.message, 'error');
                    }
                }
            })
            .catch(error => {
                // ❌ ERROR DE CONEXIÓN - Revertir cambio en UI
                console.error('Error de conexión:', error);
                revertirCambioEnUI(productoId);
                
                if (typeof UIComponents !== 'undefined') {
                    UIComponents.showAlert('Error de conexión al actualizar el estado', 'error');
                }
            });
    } else {
        console.warn('APIClient no disponible');
        revertirCambioEnUI(productoId);
    }
}

/**
 * ⭐ NUEVO: Actualizar SOLO el estado en la UI (sin tocar servidor)
 */
function actualizarEstadoEnUI(productoId, nuevoEstado) {
    // Buscar el producto en las filas expandidas
    $('#pedidosTable tbody tr.shown').each(function() {
        const fila = $(this);
        const filaHija = fila.next('tr').find(`[data-id="${productoId}"]`);
        
        if (filaHija.length > 0) {
            // Encontramos el producto, actualizar su estado
            const estadoInfo = CONFIG.estados[nuevoEstado] || 
                             { nombre: 'Desconocido', color: 'secondary', icon: 'fa-question' };
            
            // 🎨 Actualizar el badge de estado
            const nuevoBadge = `
                <span class="badge bg-${estadoInfo.color}">
                    <i class="fas ${estadoInfo.icon}"></i> ${estadoInfo.nombre}
                </span>`;
            
            filaHija.closest('tr').find('td:nth-child(8)').html(nuevoBadge);
            
            // 🎯 RESALTAR VISUALMENTE QUE SE ACTUALIZÓ
            filaHija.closest('tr').addClass('producto-actualizado');
            
            // 📝 Actualizar el data-estado del botón
            filaHija.attr('data-estado', nuevoEstado);
            
            // 🔄 Actualizar también el estado en la fila padre si es necesario
            actualizarEstadoFilaPadre(fila);
            
            console.log(`✅ Estado actualizado en UI: Producto ${productoId} → ${estadoInfo.nombre}`);
            return false; // Salir del each
        }
    });
}

/**
 * ⭐ NUEVO: Actualizar estado de la fila padre basado en sus productos
 */
function actualizarEstadoFilaPadre(filaPadre) {
    const row = tablaPedidos.row(filaPadre);
    const orderData = row.data();
    
    if (orderData && orderData.detalles) {
        // Recalcular el estado general de la orden
        const nuevoBadgeEstado = getOrderStatusBadge(orderData.detalles);
        
        // Actualizar la columna de estado en la fila padre
        filaPadre.find('td:nth-child(2)').html(nuevoBadgeEstado);
        
        // Actualizar clase de la fila según los nuevos estados
        const nuevaClase = determineRowClass(orderData.detalles);
        
        // Limpiar clases anteriores y aplicar nueva
        filaPadre.removeClass('table-no-aceptado table-por-fabricar table-fabricando table-producto-listo table-en-despacho table-entregado');
        if (nuevaClase) {
            filaPadre.addClass(nuevaClase);
        }
    }
}

/**
 * ⭐ NUEVO: Revertir cambio en UI si el servidor falló
 */
function revertirCambioEnUI(productoId) {
    // Obtener el estado original desde la base de datos
    // Por ahora, simplemente mostrar que hubo error y sugerir refresh
    $('#pedidosTable tbody tr.shown').each(function() {
        const fila = $(this);
        const filaHija = fila.next('tr').find(`[data-id="${productoId}"]`);
        
        if (filaHija.length > 0) {
            // Remover la clase de actualizado y agregar clase de error
            filaHija.closest('tr').removeClass('producto-actualizado').addClass('producto-error');
            
            // Mostrar mensaje de error
            if (typeof UIComponents !== 'undefined') {
                UIComponents.showToast('Error al guardar. Actualiza la página para ver el estado real.', 'error');
            }
            
            return false;
        }
    });
}

/**
 * ⭐ NUEVO: Mostrar confirmación visual del cambio exitoso
 */
function mostrarConfirmacionCambio(productoId, nuevoEstado) {
    const estadoInfo = CONFIG.estados[nuevoEstado];
    
    $('#pedidosTable tbody tr.shown').each(function() {
        const fila = $(this);
        const filaHija = fila.next('tr').find(`[data-id="${productoId}"]`);
        
        if (filaHija.length > 0) {
            // ✅ Animación de confirmación
            filaHija.closest('tr').addClass('producto-confirmado');
            
            setTimeout(() => {
                filaHija.closest('tr').removeClass('producto-actualizado producto-confirmado');
            }, 3000);
            
            // 🎉 Toast de éxito
            if (typeof UIComponents !== 'undefined') {
                UIComponents.showToast(`Estado cambiado a: ${estadoInfo.nombre}`, 'success');
            }
            
            return false;
        }
    });
}

// ========================================================================
// 🔄 MODIFICAR FUNCIONES EXISTENTES PARA USAR ACTUALIZACIONES OPTIMIZADAS
// ========================================================================

/**
 * ⭐ MODIFICADO: Marcar producto entregado SIN recargar tabla
 */
function marcarProductoEntregado(productoId) {
    // Usar la nueva función optimizada
    cambiarEstadoProducto(productoId, 9, 'Marcado como entregado desde dashboard');
}

/**
 * ⭐ MODIFICADO: Marcar orden completa CON actualizaciones granulares
 */
function marcarOrdenCompleta(numOrden) {
    if (typeof UIComponents !== 'undefined') {
        UIComponents.showLoading(true, 'Marcando orden como completa...');
    }
    
    if (typeof APIClient !== 'undefined') {
        APIClient.getOrderDetails(numOrden)
            .then(response => {
                if (response.success && response.data.detalles) {
                    const productosNoEntregados = response.data.detalles.filter(p => p.estadopedido !== '9');
                    
                    if (productosNoEntregados.length === 0) {
                        if (typeof UIComponents !== 'undefined') {
                            UIComponents.showToast('Todos los productos ya están entregados', 'info');
                        }
                        return;
                    }
                    
                    // 🎯 ACTUALIZAR CADA PRODUCTO INDIVIDUALMENTE (sin reload)
                    productosNoEntregados.forEach(producto => {
                        actualizarEstadoEnUI(producto.id, 9);
                    });
                    
                    // Guardar cambios en servidor
                    const promesasActualizacion = productosNoEntregados.map(p => 
                        APIClient.updateProductStatus(p.id, 9, 'Marcado como completo desde dashboard')
                    );
                    
                    return Promise.all(promesasActualizacion);
                } else {
                    throw new Error('No se pudieron obtener los detalles de la orden');
                }
            })
            .then(resultados => {
                const exitosos = resultados ? resultados.filter(r => r.status === 'success').length : 0;
                
                if (exitosos > 0) {
                    if (typeof UIComponents !== 'undefined') {
                        UIComponents.showToast(`Orden #${numOrden} marcada como completa (${exitosos} productos)`, 'success');
                    }
                    
                    // 📊 Solo actualizar estadísticas, NO la tabla completa
                    updateStatsCards();
                }
            })
            .catch(error => {
                console.error('Error marcando orden completa:', error);
                if (typeof UIComponents !== 'undefined') {
                    UIComponents.showAlert('Error al marcar la orden como completa', 'error');
                }
            })
            .finally(() => {
                if (typeof UIComponents !== 'undefined') {
                    UIComponents.showLoading(false);
                }
            });
    }
}

/**
 * ⭐ NUEVO: Actualizar estadísticas sin recargar tabla
 */
function updateStatsCards() {
    if (!tablaPedidos) return;
    
    const data = tablaPedidos.data();
    let stats = {
        total: 0,
        pendientes: 0,
        fabricando: 0,
        listos: 0,
        entregados: 0
    };
    
    data.each(function(pedido) {
        stats.total++;
        
        if (pedido.detalles) {
            const estados = pedido.detalles.map(d => parseInt(d.estadopedido));
            
            if (estados.every(e => e === 9)) {
                stats.entregados++;
            } else if (estados.some(e => e === 5)) {
                stats.fabricando++;
            } else if (estados.some(e => [6, 7, 8].includes(e))) {
                stats.listos++;
            } else {
                stats.pendientes++;
            }
        }
    });
    
    // 🎯 ACTUALIZAR SOLO LOS NÚMEROS (con animación)
    animarCambioEstadistica('#stat-total', stats.total);
    animarCambioEstadistica('#stat-pendientes', stats.pendientes);
    animarCambioEstadistica('#stat-fabricando', stats.fabricando);
    animarCambioEstadistica('#stat-listos', stats.listos);
    animarCambioEstadistica('#stat-entregados', stats.entregados);
}

/**
 * ⭐ NUEVO: Animar cambios en las estadísticas
 */
function animarCambioEstadistica(selector, nuevoValor) {
    const elemento = $(selector);
    const valorActual = parseInt(elemento.text()) || 0;
    
    if (valorActual !== nuevoValor) {
        elemento.addClass('stat-changing');
        elemento.text(nuevoValor);
        
        setTimeout(() => {
            elemento.removeClass('stat-changing');
        }, 500);
    }
}

// ========================================================================
// 🎨 CSS ADICIONAL PARA LAS ANIMACIONES (agregar al CSS)
// ========================================================================
/*
.producto-actualizado {
    background-color: #fff3cd !important;
    border-left: 4px solid #ffc107 !important;
    animation: pulseYellow 1s ease-in-out;
}

.producto-confirmado {
    background-color: #d1e7dd !important;
    border-left: 4px solid #198754 !important;
    animation: pulseGreen 1s ease-in-out;
}

.producto-error {
    background-color: #f8d7da !important;
    border-left: 4px solid #dc3545 !important;
    animation: pulseRed 1s ease-in-out;
}

.stat-changing {
    animation: statChange 0.5s ease-in-out;
    color: #0d6efd !important;
    font-weight: bold !important;
}

@keyframes pulseYellow {
    0% { transform: scale(1); }
    50% { transform: scale(1.02); }
    100% { transform: scale(1); }
}

@keyframes pulseGreen {
    0% { transform: scale(1); }
    50% { transform: scale(1.02); }
    100% { transform: scale(1); }
}

@keyframes pulseRed {
    0% { transform: scale(1); }
    50% { transform: scale(1.02); }
    100% { transform: scale(1); }
}

@keyframes statChange {
    0% { transform: scale(1); }
    50% { transform: scale(1.1); }
    100% { transform: scale(1); }
}
*/
    function handleMarcarEntregado() {
        const productoId = $(this).data('id');
        
        if (typeof UIComponents !== 'undefined') {
            UIComponents.showConfirm(
                '¿Marcar este producto como entregado?',
                'Confirmar Entrega'
            ).then(result => {
                if (result.isConfirmed) {
                    marcarProductoEntregado(productoId);
                }
            });
        } else {
            if (confirm('¿Marcar este producto como entregado?')) {
                marcarProductoEntregado(productoId);
            }
        }
    }

    // ========================================================================
    // FUNCIONES IMPLEMENTADAS - YA NO SON PLACEHOLDER - MANTENIENDO ORIGINALES
    // ========================================================================
    
    /**
     * Abre el modal de gestión usando el modal existente en modal_gestion_orden.php
     */
    function openOrderManagementModal(orderData) {
        console.log('Abriendo modal de gestión para orden:', orderData.num_orden);
        
        // CONECTAR CON EL MODAL EXISTENTE
        if (typeof abrirModalGestionOrden === 'function') {
            abrirModalGestionOrden(orderData.num_orden);
        } else {
            console.error('Función abrirModalGestionOrden no encontrada. Asegurar que modal_gestion_orden.php esté incluido.');
            if (typeof UIComponents !== 'undefined') {
                UIComponents.showAlert('Error: Modal de gestión no disponible', 'error');
            }
        }
    }

    /**
     * Marca todos los productos de una orden como entregados
     */
    function marcarOrdenCompleta(numOrden) {
        if (typeof UIComponents !== 'undefined') {
            UIComponents.showLoading(true, 'Marcando orden como completa...');
        }
        
        // Obtener todos los productos de la orden y marcarlos como entregados
        if (typeof APIClient !== 'undefined') {
            APIClient.getOrderDetails(numOrden)
                .then(response => {
                    if (response.success && response.data.detalles) {
                        const productos = response.data.detalles;
                        const promesasActualizacion = productos
                            .filter(p => p.estadopedido !== '9') // Solo los no entregados
                            .map(p => APIClient.updateProductStatus(p.id, 9, 'Marcado como completo desde dashboard'));
                        
                        return Promise.all(promesasActualizacion);
                    } else {
                        throw new Error('No se pudieron obtener los detalles de la orden');
                    }
                })
                .then(resultados => {
                    const exitosos = resultados.filter(r => r.status === 'success').length;
                    
                    if (exitosos > 0) {
                        if (typeof UIComponents !== 'undefined') {
                            UIComponents.showToast(`Orden #${numOrden} marcada como completa (${exitosos} productos actualizados)`, 'success');
                        }
                        refreshTable(); // Actualizar la tabla
                    } else {
                        if (typeof UIComponents !== 'undefined') {
                            UIComponents.showAlert('No se pudieron actualizar los productos', 'warning');
                        }
                    }
                })
                .catch(error => {
                    console.error('Error marcando orden completa:', error);
                    if (typeof UIComponents !== 'undefined') {
                        UIComponents.showAlert('Error al marcar la orden como completa', 'error');
                    }
                })
                .finally(() => {
                    if (typeof UIComponents !== 'undefined') {
                        UIComponents.showLoading(false);
                    }
                });
        } else {
            console.warn('APIClient no disponible');
            if (typeof UIComponents !== 'undefined') {
                UIComponents.showLoading(false);
                UIComponents.showAlert('Error: APIClient no disponible', 'error');
            }
        }
    }

    function marcarProductoEntregado(productoId) {
        if (typeof UIComponents !== 'undefined') {
            UIComponents.showLoading(true, 'Actualizando estado...');
        }
        
        if (typeof APIClient !== 'undefined') {
            APIClient.updateProductStatus(productoId, 9)
                .then(response => {
                    if (response.status === 'success') {
                        if (typeof UIComponents !== 'undefined') {
                            UIComponents.showToast('Producto marcado como entregado', 'success');
                        }
                        refreshTable();
                    } else {
                        if (typeof UIComponents !== 'undefined') {
                            UIComponents.showAlert('Error al actualizar el estado: ' + response.message, 'error');
                        }
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    if (typeof UIComponents !== 'undefined') {
                        UIComponents.showAlert('Error de conexión', 'error');
                    }
                })
                .finally(() => {
                    if (typeof UIComponents !== 'undefined') {
                        UIComponents.showLoading(false);
                    }
                });
        } else {
            console.warn('APIClient no disponible');
            if (typeof UIComponents !== 'undefined') {
                UIComponents.showLoading(false);
                UIComponents.showAlert('Error: APIClient no disponible', 'error');
            }
        }
    }

    // ========================================================================
    // FUNCIONES DE CONTROL - MANTENIENDO ORIGINALES
    // ========================================================================
    
    function initControles() {
        $('#alwaysExpandDetails').prop('checked', detallesExpandidos);
    }

    function toggleAllDetails(expand = null) {
        if (expand === null) {
            detallesExpandidos = !detallesExpandidos;
        } else {
            detallesExpandidos = expand;
        }
        
        if (!tablaPedidos) return;
        
        tablaPedidos.rows().every(function() {
            const tr = $(this.node());
            const row = tablaPedidos.row(tr);
            
            if (detallesExpandidos && !row.child.isShown()) {
                row.child(formatDetalles(row.data())).show();
                tr.addClass('shown');
            } else if (!detallesExpandidos && row.child.isShown()) {
                row.child.hide();
                tr.removeClass('shown');
            }
        });
        
        localStorage.setItem('detallesExpandidos', detallesExpandidos);
        
        const btnText = detallesExpandidos ? 
            '<i class="fas fa-compress-arrows-alt"></i> Colapsar Todo' : 
            '<i class="fas fa-expand-arrows-alt"></i> Expandir Todo';
        $('#btnToggleAll').html(btnText);
    }

    function refreshTable() {
        if (tablaPedidos) {
            if (typeof UIComponents !== 'undefined') {
                UIComponents.showToast('Actualizando datos...', 'info');
            }
            tablaPedidos.ajax.reload();
        }
    }

    function exportTableData() {
        if (typeof UIComponents !== 'undefined') {
            UIComponents.showToast('Función de exportación en desarrollo', 'info');
        }
    }

    function updateStatsCards() {
        if (!tablaPedidos) return;
        
        const data = tablaPedidos.data();
        let stats = {
            total: 0,
            pendientes: 0,
            fabricando: 0,
            listos: 0,
            entregados: 0
        };
        
        data.each(function(pedido) {
            stats.total++;
            
            if (pedido.detalles) {
                const estados = pedido.detalles.map(d => parseInt(d.estadopedido));
                
                if (estados.every(e => e === 9)) {
                    stats.entregados++;
                } else if (estados.some(e => e === 5)) {
                    stats.fabricando++;
                } else if (estados.some(e => [6, 7, 8].includes(e))) {
                    stats.listos++;
                } else {
                    stats.pendientes++;
                }
            }
        });
        
        $('#stat-total').text(stats.total);
        $('#stat-pendientes').text(stats.pendientes);
        $('#stat-fabricando').text(stats.fabricando);
        $('#stat-listos').text(stats.listos);
        $('#stat-entregados').text(stats.entregados);
    }

    function loadInitialData() {
        // Cargar datos iniciales si es necesario
        console.log('⚡ Cargando datos iniciales del módulo...');
    }

    function initTooltips() {
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.map(function(tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    }

    // ========================================================================
    // API PÚBLICA - MANTENIENDO ORIGINAL
    // ========================================================================
    
    return {
        // Funciones principales
        init: init,
        refreshTable: refreshTable,
        toggleAllDetails: toggleAllDetails,
        getTableInstance: () => tablaPedidos,
        updateStats: updateStatsCards,
        
        // Funciones específicas para uso externo
        openModal: openOrderManagementModal,
        markOrderComplete: marcarOrdenCompleta,
        
        // ⭐ NUEVAS FUNCIONES PARA PRODUCTOS INDIVIDUALES ⭐
        editProduct: (productoId) => {
            if (typeof abrirModalEditarProducto === 'function') {
                abrirModalEditarProducto(productoId);
            } else {
                console.warn('Modal de editar producto no disponible');
            }
        },
        changeProductStatus: cambiarEstadoProducto,
        markProductDelivered: marcarProductoEntregado,
        
        // Funciones de utilidad
        formatNumber: formatNumber,
        getEstados: () => CONFIG.estados,
        getConfig: () => CONFIG
    };

})(jQuery);

// ========================================================================
// AUTO-INICIALIZACIÓN - MANTENIENDO ORIGINAL
// ========================================================================

$(document).ready(function() {
    // Verificar dependencias antes de inicializar
    if (typeof UIComponents !== 'undefined' && typeof APIClient !== 'undefined') {
        PedidosIngresados.init();
    } else {
        console.error('❌ Dependencias no encontradas. Verificar que estén cargados: UIComponents, APIClient');
        
        // Intentar inicializar después de un delay
        setTimeout(() => {
            if (typeof UIComponents !== 'undefined' && typeof APIClient !== 'undefined') {
                console.log('🔄 Reintentando inicialización del módulo...');
                PedidosIngresados.init();
            } else {
                console.error('❌ Error crítico: Dependencias aún no disponibles');
            }
        }, 2000);
    }
});

// Exportar al namespace global
window.PedidosIngresados = PedidosIngresados;