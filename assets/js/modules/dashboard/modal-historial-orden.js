/**
 * ============================================================================
 * MODAL DE HISTORIAL DE ORDEN - ORGANIZADO POR PRODUCTOS
 * ============================================================================
 * Modal que separa claramente eventos de orden vs eventos por producto
 * @version 3.0 - Organizado y legible
 * @author RespaldosChile Team
 * ============================================================================
 */

const ModalHistorialOrden = (function($) {
    'use strict';

    // ========================================================================
    // CONFIGURACIÓN
    // ========================================================================
    
    const CONFIG = {
        modalId: 'modalHistorialOrden',
        endpoint: 'api/get_historial_orden.php'
    };

    // ========================================================================
    // FUNCIÓN PRINCIPAL PARA MOSTRAR MODAL
    // ========================================================================
    
    function mostrarModalHistorial(numOrden, responseData = [], tipoError = null) {
        const modalId = CONFIG.modalId;
        
        // Extraer datos de la respuesta del servidor
        let historialData = responseData;
        let ordenInfo = null;
        
        if (responseData && typeof responseData === 'object' && responseData.data) {
            historialData = responseData.data;
            ordenInfo = responseData.orden_info;
        }
        
        // Crear modal dinámico si no existe
        if ($(`#${modalId}`).length === 0) {
            crearModal(modalId, numOrden, ordenInfo);
        } else {
            // Actualizar el título
            let titulo = `<i class="fas fa-history me-2"></i>Historial #${numOrden}`;
            if (ordenInfo && ordenInfo.cliente) {
                titulo += ` - ${ordenInfo.cliente}`;
            }
            $(`#${modalId} .modal-title`).html(titulo);
        }
        
        // Mostrar información de la orden si está disponible
        if (ordenInfo) {
            mostrarInfoOrden(ordenInfo);
        }
        
        // Manejar casos especiales de error
        if (tipoError === 'orden_no_encontrada') {
            mostrarErrorOrdenNoEncontrada(numOrden);
        } else {
            // Calcular estadísticas y llenar contenido normal
            calcularEstadisticasHistorial(responseData);
            llenarContenidoHistorialOrganizado(historialData);
        }
        
        // Mostrar modal
        const modal = new bootstrap.Modal(document.getElementById(modalId));
        modal.show();
    }

    // ========================================================================
    // CREAR MODAL DINÁMICAMENTE
    // ========================================================================
    
    function crearModal(modalId, numOrden, ordenInfo = null) {
        let titulo = `<i class="fas fa-history me-2"></i>Historial #${numOrden}`;
        if (ordenInfo && ordenInfo.cliente) {
            titulo += ` - ${ordenInfo.cliente}`;
        }
        
        const modalHTML = `
            <div class="modal fade" id="${modalId}" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-xl">
                    <div class="modal-content">
                        <div class="modal-header bg-info text-white py-2">
                            <h6 class="modal-title">${titulo}</h6>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body p-2">
                            <!-- Info de la orden (compacta) -->
                            <div id="infoOrden" class="bg-light p-2 mb-2 rounded" style="display: none;">
                                <div class="row">
                                    <div class="col-8">
                                        <small class="text-muted">CLIENTE:</small>
                                        <span id="clienteInfo" class="ms-1 fw-bold"></span>
                                    </div>
                                    <div class="col-4">
                                        <small class="text-muted">VENDEDOR:</small>
                                        <span id="vendedorInfo" class="ms-1"></span>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Estadísticas compactas -->
                            <div class="bg-light p-2 mb-2 rounded">
                                <div class="row text-center">
                                    <div class="col-3">
                                        <div class="h6 mb-0 text-info" id="totalEventos">0</div>
                                        <small class="text-muted">Eventos</small>
                                    </div>
                                    <div class="col-3">
                                        <div class="h6 mb-0 text-success" id="totalPagos">0</div>
                                        <small class="text-muted">Pagos</small>
                                    </div>
                                    <div class="col-3">
                                        <div class="h6 mb-0 text-warning" id="totalEstados">0</div>
                                        <small class="text-muted">Estados</small>
                                    </div>
                                    <div class="col-3">
                                        <div class="h6 mb-0 text-secondary" id="duracionOrden">0</div>
                                        <small class="text-muted">Productos</small>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Contenido organizado -->
                            <div id="historialContent" style="max-height: 500px; overflow-y: auto;">
                                <!-- Se llena dinámicamente -->
                            </div>
                        </div>
                        <div class="modal-footer py-2">
                            <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">
                                <i class="fas fa-times me-1"></i>Cerrar
                            </button>
                            <button type="button" class="btn btn-sm btn-info" onclick="ModalHistorialOrden.exportarHistorial('${numOrden}')">
                                <i class="fas fa-download me-1"></i>Exportar
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            
            ${getModalStyles()}
        `;
        $('body').append(modalHTML);
    }

    // ========================================================================
    // LLENAR CONTENIDO ORGANIZADO POR PRODUCTOS
    // ========================================================================
    
    function llenarContenidoHistorialOrganizado(historialData) {
        if (!historialData || historialData.length === 0) {
            $('#historialContent').html(`
                <div class="text-center text-muted py-4">
                    <i class="fas fa-history fa-2x mb-2 opacity-50"></i>
                    <h6>Sin historial</h6>
                    <small>Esta orden no tiene eventos registrados.</small>
                </div>
            `);
            return;
        }
        
        // Separar eventos de orden vs eventos de productos
        const eventosOrden = historialData.filter(item => item.es_evento_orden);
        const eventosProducto = historialData.filter(item => !item.es_evento_orden);
        
        // Agrupar eventos de producto por producto_id
        const eventosPorProducto = {};
        eventosProducto.forEach(evento => {
            if (evento.producto_info && evento.producto_info.id) {
                const productoId = evento.producto_info.id;
                if (!eventosPorProducto[productoId]) {
                    eventosPorProducto[productoId] = {
                        info: evento.producto_info,
                        eventos: []
                    };
                }
                eventosPorProducto[productoId].eventos.push(evento);
            }
        });
        
        let contenidoHTML = '';
        
        // ===== SECCIÓN 1: EVENTOS DE LA ORDEN =====
        if (eventosOrden.length > 0) {
            contenidoHTML += `
                <div class="seccion-orden mb-3">
                    <div class="seccion-header bg-success text-white p-2 rounded">
                        <h6 class="mb-0">
                            <i class="fas fa-receipt me-2"></i>
                            📋 Eventos de la Orden (${eventosOrden.length})
                        </h6>
                    </div>
                    <div class="seccion-content p-2 bg-light rounded-bottom">
                        ${eventosOrden.map(evento => crearEventoSimple(evento)).join('')}
                    </div>
                </div>
            `;
        }
        
        // ===== SECCIÓN 2: PRODUCTOS Y SUS ESTADOS =====
        if (Object.keys(eventosPorProducto).length > 0) {
            contenidoHTML += `
                <div class="seccion-productos mb-3">
                    <div class="seccion-header bg-primary text-white p-2 rounded">
                        <h6 class="mb-0">
                            <i class="fas fa-boxes me-2"></i>
                            📦 Productos y Sus Estados (${Object.keys(eventosPorProducto).length})
                        </h6>
                    </div>
                    <div class="seccion-content">
            `;
            
            // Crear una tarjeta por cada producto
            Object.entries(eventosPorProducto).forEach(([productoId, grupo], index) => {
                contenidoHTML += crearTarjetaProducto(productoId, grupo, index);
            });
            
            contenidoHTML += `
                    </div>
                </div>
            `;
        }
        
        // Si no hay productos
        if (Object.keys(eventosPorProducto).length === 0 && eventosOrden.length > 0) {
            contenidoHTML += `
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>
                    <strong>Información:</strong> Esta orden no tiene productos registrados o no tienen historial de estados.
                </div>
            `;
        }
        
        $('#historialContent').html(contenidoHTML);
    }
    
    // ========================================================================
    // CREAR EVENTO SIMPLE (PARA EVENTOS DE ORDEN)
    // ========================================================================
    
    function crearEventoSimple(evento) {
        const icono = evento.icono || 'fas fa-circle';
        const color = evento.color || 'secondary';
        const fecha = evento.fecha_formateada || evento.fecha;
        
        return `
            <div class="evento-simple d-flex align-items-center mb-2 p-2 bg-white rounded border">
                <div class="evento-icon me-3">
                    <i class="${icono} text-${color}" style="font-size: 18px;"></i>
                </div>
                <div class="evento-info flex-grow-1">
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="fw-bold">${evento.titulo || evento.accion || 'Evento'}</span>
                        <small class="text-muted">${fecha}</small>
                    </div>
                    <small class="text-muted">${evento.descripcion || 'Sin descripción'}</small>
                    ${evento.monto ? `<div><small class="text-success"><i class="fas fa-dollar-sign me-1"></i>$${formatearMonto(evento.monto)}</small></div>` : ''}
                </div>
            </div>
        `;
    }
    
    // ========================================================================
    // CREAR TARJETA DE PRODUCTO
    // ========================================================================
    
    function crearTarjetaProducto(productoId, grupo, index) {
        // Ordenar eventos por fecha
        grupo.eventos.sort((a, b) => new Date(a.fecha) - new Date(b.fecha));
        
        // Determinar estado actual
        const ultimoEvento = grupo.eventos[grupo.eventos.length - 1];
        const estadoActual = ultimoEvento ? ultimoEvento.titulo : 'Sin estado';
        
        // Color del estado
        let colorEstado = 'secondary';
        if (ultimoEvento) {
            if (ultimoEvento.color === 'success') colorEstado = 'success';
            else if (ultimoEvento.color === 'danger') colorEstado = 'danger';
            else if (ultimoEvento.color === 'warning') colorEstado = 'warning';
            else if (ultimoEvento.color === 'info') colorEstado = 'info';
            else if (ultimoEvento.color === 'primary') colorEstado = 'primary';
        }
        
        const collapseId = `producto${productoId}`;
        
        return `
            <div class="producto-card border rounded mb-2">
                <!-- Header del producto (siempre visible) -->
                <div class="producto-header bg-light p-2 border-bottom" 
                     data-bs-toggle="collapse" 
                     data-bs-target="#${collapseId}" 
                     aria-expanded="false"
                     style="cursor: pointer;">
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <h6 class="mb-1">
                                <i class="fas fa-box text-primary me-2"></i>
                                ${grupo.info.identificador || `${grupo.info.modelo} ${grupo.info.tamano}`}
                            </h6>
                            <small class="text-muted">
                                <strong>Tela:</strong> ${grupo.info.tipotela} ${grupo.info.color}
                            </small>
                        </div>
                        <div class="col-md-4">
                            <div class="text-center">
                                <span class="badge bg-${colorEstado} mb-1">
                                    ${estadoActual}
                                </span>
                                <br>
                                <small class="text-muted">${grupo.eventos.length} cambios de estado</small>
                            </div>
                        </div>
                        <div class="col-md-2 text-center">
                            <i class="fas fa-chevron-down text-muted"></i>
                            <br>
                            <small class="text-muted">Ver detalles</small>
                        </div>
                    </div>
                </div>
                
                <!-- Contenido expandible (historial de estados) -->
                <div id="${collapseId}" class="collapse">
                    <div class="producto-body p-2">
                        <!-- Información detallada del producto -->
                        <div class="info-producto bg-white p-2 mb-2 rounded border">
                            <div class="row text-center">
                                <div class="col-3">
                                    <small class="text-muted d-block">MODELO</small>
                                    <strong class="small">${grupo.info.modelo}</strong>
                                </div>
                                <div class="col-3">
                                    <small class="text-muted d-block">TAMAÑO</small>
                                    <strong class="small">${grupo.info.tamano}</strong>
                                </div>
                                <div class="col-3">
                                    <small class="text-muted d-block">TELA</small>
                                    <strong class="small">${grupo.info.tipotela}</strong>
                                </div>
                                <div class="col-3">
                                    <small class="text-muted d-block">COLOR</small>
                                    <strong class="small">${grupo.info.color}</strong>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Timeline de estados del producto -->
                        <div class="estados-timeline">
                            <h6 class="mb-2">
                                <i class="fas fa-timeline me-2"></i>
                                Historial de Estados:
                            </h6>
                            ${grupo.eventos.map((evento, idx) => crearEstadoProducto(evento, idx, grupo.eventos.length)).join('')}
                        </div>
                    </div>
                </div>
            </div>
        `;
    }
    
    // ========================================================================
    // CREAR ESTADO DE PRODUCTO (EN TIMELINE)
    // ========================================================================
    
    function crearEstadoProducto(evento, indice, total) {
        const icono = evento.icono || 'fas fa-circle';
        const color = evento.color || 'secondary';
        const fecha = evento.fecha_formateada || evento.fecha;
        const esUltimo = indice === total - 1;
        
        // Determinar si es evento importante/negativo
        const eventosImportantes = ['pedido_aceptado', 'fabricado', 'producto_entregado'];
        const eventosNegativos = ['devuelto_error_fabricacion', 'devuelto_disconformidad', 'reemitido'];
        
        const esImportante = eventosImportantes.includes(evento.tipo);
        const esNegativo = eventosNegativos.includes(evento.tipo);
        
        let claseEspecial = '';
        let badgeEspecial = '';
        
        if (esImportante && !esNegativo) {
            claseEspecial = 'estado-importante';
            badgeEspecial = '<i class="fas fa-star text-warning ms-1"></i>';
        } else if (esNegativo) {
            claseEspecial = 'estado-negativo';
            badgeEspecial = '<i class="fas fa-exclamation-triangle text-danger ms-1"></i>';
        }
        
        return `
            <div class="estado-item d-flex align-items-start mb-2 ${claseEspecial}">
                <!-- Línea conectora -->
                <div class="estado-connector me-3 d-flex flex-column align-items-center">
                    <div class="estado-dot bg-${color} text-white d-flex align-items-center justify-content-center">
                        <i class="${icono}" style="font-size: 10px;"></i>
                    </div>
                    ${!esUltimo ? '<div class="estado-line"></div>' : ''}
                </div>
                
                <!-- Contenido del estado -->
                <div class="estado-content flex-grow-1 bg-white p-2 rounded border">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <span class="fw-bold small">
                                ${evento.titulo || 'Estado'}
                                ${badgeEspecial}
                            </span>
                            <br>
                            <small class="text-muted">${evento.descripcion || 'Sin descripción'}</small>
                            ${evento.observacion ? `<br><small class="text-warning"><i class="fas fa-comment me-1"></i>${evento.observacion}</small>` : ''}
                        </div>
                        <div class="text-end">
                            <small class="text-muted">${fecha}</small>
                            ${evento.fecha_relativa ? `<br><span class="badge bg-secondary small">${evento.fecha_relativa}</span>` : ''}
                        </div>
                    </div>
                    <div class="mt-1">
                        <small class="text-muted">
                            <i class="fas fa-user me-1"></i>${evento.usuario || 'Sistema'}
                            ${evento.proceso_id ? ` • ID: ${evento.proceso_id}` : ''}
                        </small>
                    </div>
                </div>
            </div>
        `;
    }

    // ========================================================================
    // ESTILOS DEL MODAL
    // ========================================================================
    
    function getModalStyles() {
        return `
            <style>
                /* Secciones principales */
                .seccion-header {
                    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
                }
                
                .seccion-content {
                    border: 1px solid #dee2e6;
                    border-top: none;
                }
                
                /* Eventos simples */
                .evento-simple {
                    transition: all 0.2s ease;
                }
                
                .evento-simple:hover {
                    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
                    transform: translateX(2px);
                }
                
                /* Tarjetas de productos */
                .producto-card {
                    transition: all 0.3s ease;
                }
                
                .producto-card:hover {
                    box-shadow: 0 4px 8px rgba(0,0,0,0.15);
                }
                
                .producto-header:hover {
                    background-color: #f8f9fa !important;
                }
                
                /* Timeline de estados */
                .estado-dot {
                    width: 24px;
                    height: 24px;
                    border-radius: 50%;
                    box-shadow: 0 2px 4px rgba(0,0,0,0.2);
                }
                
                .estado-line {
                    width: 2px;
                    height: 30px;
                    background: #dee2e6;
                    margin-top: 4px;
                }
                
                .estado-content {
                    border-left: 3px solid transparent;
                }
                
                /* Estados especiales */
                .estado-importante .estado-content {
                    border-left-color: #ffc107;
                    box-shadow: 0 2px 4px rgba(255, 193, 7, 0.2);
                }
                
                .estado-negativo .estado-content {
                    border-left-color: #dc3545;
                    box-shadow: 0 2px 4px rgba(220, 53, 69, 0.2);
                }
                
                /* Animación de chevron */
                .fa-chevron-down {
                    transition: transform 0.3s ease;
                }
                
                [aria-expanded="true"] .fa-chevron-down {
                    transform: rotate(180deg);
                }
                
                /* Colores de estado */
                .bg-success { background-color: #28a745 !important; }
                .bg-danger { background-color: #dc3545 !important; }
                .bg-warning { background-color: #ffc107 !important; }
                .bg-info { background-color: #17a2b8 !important; }
                .bg-primary { background-color: #007bff !important; }
                .bg-secondary { background-color: #6c757d !important; }
                
                /* Scrollbar personalizado */
                #historialContent::-webkit-scrollbar {
                    width: 6px;
                }
                
                #historialContent::-webkit-scrollbar-track {
                    background: #f1f1f1;
                    border-radius: 3px;
                }
                
                #historialContent::-webkit-scrollbar-thumb {
                    background: #c1c1c1;
                    border-radius: 3px;
                }
                
                #historialContent::-webkit-scrollbar-thumb:hover {
                    background: #a8a8a8;
                }
                
                /* Responsive */
                @media (max-width: 768px) {
                    .producto-header .col-md-6,
                    .producto-header .col-md-4,
                    .producto-header .col-md-2 {
                        margin-bottom: 0.5rem;
                    }
                    
                    .info-producto .col-3 {
                        margin-bottom: 0.5rem;
                    }
                }
            </style>
        `;
    }

    // ========================================================================
    // RESTO DE FUNCIONES (IGUALES QUE ANTES)
    // ========================================================================
    
    function mostrarInfoOrden(ordenInfo) {
        if (!ordenInfo) return;
        
        $('#clienteInfo').text(`${ordenInfo.cliente || 'Sin nombre'} (${ordenInfo.rut_cliente || 'Sin RUT'})`);
        $('#vendedorInfo').text(ordenInfo.vendedor || 'Sin asignar');
        $('#infoOrden').show();
    }
    
    function mostrarErrorOrdenNoEncontrada(numOrden) {
        $('#historialContent').html(`
            <div class="text-center text-muted py-4">
                <i class="fas fa-search fa-2x mb-2 text-danger opacity-50"></i>
                <h6 class="text-danger">Orden no encontrada</h6>
                <p class="mb-0">La orden #${numOrden} no existe en la base de datos.</p>
            </div>
        `);
        
        // Limpiar estadísticas
        $('#totalEventos').text('0');
        $('#totalPagos').text('0');
        $('#totalEstados').text('0');
        $('#duracionOrden').text('0');
    }
    
    function calcularEstadisticasHistorial(data) {
        // Usar estadísticas del servidor si están disponibles
        if (data && data.estadisticas) {
            $('#totalEventos').text(data.estadisticas.total_eventos || '0');
            $('#totalPagos').text(data.estadisticas.total_pagos || '0');
            $('#totalEstados').text(data.estadisticas.total_estados_producto || '0');
            $('#duracionOrden').text(data.estadisticas.productos_en_orden || '0');
            return;
        }
        
        // Fallback al método anterior
        const historial = data && data.data ? data.data : data;
        
        if (!historial || historial.length === 0) {
            $('#totalEventos').text('0');
            $('#totalPagos').text('0');
            $('#totalEstados').text('0');
            $('#duracionOrden').text('0');
            return;
        }
        
        const totalEventos = historial.length;
        const totalPagos = historial.filter(item => item.tipo === 'pago_agregado').length;
        const totalEstados = historial.filter(item => !item.es_evento_orden).length;
        
        // Contar productos únicos
        const productosUnicos = new Set();
        historial.forEach(item => {
            if (item.producto_info && item.producto_info.id) {
                productosUnicos.add(item.producto_info.id);
            }
        });
        
        $('#totalEventos').text(totalEventos);
        $('#totalPagos').text(totalPagos);
        $('#totalEstados').text(totalEstados);
        $('#duracionOrden').text(productosUnicos.size);
    }
    
    function formatearMonto(monto) {
    if (!monto || monto === 0 || monto === '0' || monto === '') return '0';
    
    let numero = monto;
    if (typeof monto === 'string') {
        numero = monto.replace(/[^0-9.-]/g, '');
        numero = parseFloat(numero);
    }
    
    if (isNaN(numero)) return '0';
    
    // CORRECCIÓN: Multiplicar por 1000 si los montos vienen en miles
    // Si 56 debe mostrarse como 56.000, entonces multiplicamos por 1000
    numero = numero * 1000;
    
    return new Intl.NumberFormat('es-CL').format(numero);
}

    // ========================================================================
    // FUNCIÓN PARA CARGAR HISTORIAL DESDE API - IGUAL QUE ANTES
    // ========================================================================
    
    function cargarHistorial(numOrden) {
        if (typeof UIComponents !== 'undefined') {
            UIComponents.showLoading(true, 'Cargando historial de la orden...');
        }
        
        console.log('🔄 Cargando historial para orden:', numOrden);
        
        $.ajax({
            url: CONFIG.endpoint,
            method: 'GET',
            data: { num_orden: numOrden },
            dataType: 'text',
            timeout: 30000,
            cache: false
        })
        .done(function(responseText, textStatus, xhr) {
            let response;
            
            try {
                response = JSON.parse(responseText);
            } catch (parseError) {
                console.error('❌ Error parseando JSON:', parseError.message);
                mostrarError('Respuesta del servidor no válida');
                mostrarModalHistorial(numOrden, []);
                return;
            }
            
            if (response.success === true) {
                console.log('✅ Carga exitosa, mostrando modal');
                mostrarModalHistorial(numOrden, response);
            } else {
                console.warn('⚠️ Error en respuesta del servidor:', response.message);
                
                if (response.error_type === 'orden_no_encontrada') {
                    mostrarModalHistorial(numOrden, [], 'orden_no_encontrada');
                } else {
                    mostrarModalHistorial(numOrden, []);
                    mostrarError('Error del servidor: ' + (response.message || 'Error desconocido'));
                }
            }
        })
        .fail(function(xhr, textStatus, errorThrown) {
            console.error('❌ Error en la petición AJAX:', errorThrown);
            mostrarModalHistorial(numOrden, []);
            mostrarError('Error de conexión con el servidor');
        })
        .always(function() {
            if (typeof UIComponents !== 'undefined') {
                UIComponents.showLoading(false);
            }
        });
    }
    
    function mostrarError(mensaje) {
        if (typeof UIComponents !== 'undefined') {
            UIComponents.showAlert('Error al cargar historial: ' + mensaje, 'danger');
        } else if (typeof Swal !== 'undefined') {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Error al cargar historial: ' + mensaje,
                confirmButtonText: 'Entendido'
            });
        } else {
            alert('Error al cargar historial: ' + mensaje);
        }
    }

    function exportarHistorial(numOrden) {
        if (typeof UIComponents !== 'undefined') {
            UIComponents.showToast('Función de exportar próximamente disponible', 'info');
        } else {
            alert('Función de exportar próximamente disponible');
        }
    }

    // ========================================================================
    // API PÚBLICA
    // ========================================================================
    
    return {
        mostrar: mostrarModalHistorial,
        cargar: cargarHistorial,
        exportarHistorial: exportarHistorial
    };

})(jQuery);

// Exportar al namespace global
window.ModalHistorialOrden = ModalHistorialOrden;