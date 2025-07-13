/**
 * Funciones helper para manejar estados y formas de pago
 * Para integrar en tu archivo JavaScript principal
 */

// Función para formatear método de pago con iconos
function formatPaymentMethod(method) {
    if (!method) return '<span class="payment-method">No especificado</span>';
    
    const methodLower = method.toLowerCase();
    let className = 'payment-method';
    let text = method;
    
    if (methodLower.includes('transferencia') || methodLower.includes('transfer')) {
        className += ' transferencia';
        text = 'Transferencia';
    } else if (methodLower.includes('efectivo') || methodLower.includes('cash')) {
        className += ' efectivo';
        text = 'Efectivo';
    } else if (methodLower.includes('tarjeta') || methodLower.includes('card')) {
        className += ' tarjeta';
        text = 'Tarjeta';
    }
    
    return `<span class="${className}">${text}</span>`;
}

// Función para formatear estado del pedido
function formatOrderStatus(status) {
    if (!status) return '<span class="status-badge">Sin estado</span>';
    
    const statusLower = status.toLowerCase();
    let className = 'status-badge';
    let text = status;
    
    if (statusLower.includes('no aceptado') || statusLower.includes('rechazado')) {
        className += ' no-aceptado';
        text = 'No Aceptado';
    } else if (statusLower.includes('por fabricar') || statusLower.includes('fabricar')) {
        className += ' por-fabricar';
        text = 'Por Fabricar';
    } else if (statusLower.includes('fabricando') || statusLower.includes('proceso')) {
        className += ' fabricando';
        text = 'Fabricando';
    } else if (statusLower.includes('entregado') || statusLower.includes('completado')) {
        className += ' entregado';
        text = 'Entregado';
    } else if (statusLower.includes('cancelado')) {
        className += ' cancelado';
        text = 'Cancelado';
    } else if (statusLower.includes('pendiente')) {
        className += ' pendiente';
        text = 'Pendiente';
    }
    
    return `<span class="${className}">${text}</span>`;
}

// Función para formatear botones de acción
function formatActionButtons(rowData, userPrivileges = 0) {
    let buttons = '';
    
    // Botón Ver/Editar (siempre disponible)
    buttons += `
        <button type="button" class="btn btn-view" onclick="verDetalleOrden('${rowData.id || rowData.orden}')" title="Ver Detalle">
            <i class="fas fa-eye"></i>
        </button>
    `;
    
    // Botón Editar (solo para usuarios con permisos)
    if (userPrivileges >= 10) {
        buttons += `
            <button type="button" class="btn btn-edit" onclick="editarOrden('${rowData.id || rowData.orden}')" title="Editar Pedido">
                <i class="fas fa-edit"></i>
            </button>
        `;
    }
    
    // Botón Historial
    buttons += `
        <button type="button" class="btn btn-history" onclick="verHistorialOrden('${rowData.id || rowData.orden}')" title="Ver Historial">
            <i class="fas fa-history"></i>
        </button>
    `;
    
    // Botón Eliminar (solo para administradores)
    if (userPrivileges >= 20) {
        buttons += `
            <button type="button" class="btn btn-delete" onclick="eliminarOrden('${rowData.id || rowData.orden}')" title="Eliminar Pedido">
                <i class="fas fa-trash"></i>
            </button>
        `;
    }
    
    return `<div class="table-actions">${buttons}</div>`;
}

// Función para formatear información del cliente
function formatClientInfo(rowData) {
    return `
        <div class="client-info">
            <div class="client-name">${rowData.cliente || 'Sin nombre'}</div>
            <div class="client-rut">${rowData.rut || 'Sin RUT'}</div>
        </div>
    `;
}

// Función para formatear información de contacto
function formatContactInfo(rowData) {
    return `
        <div class="contact-info">
            <div class="contact-phone">${rowData.telefono || 'Sin teléfono'}</div>
            <div class="contact-address">${rowData.direccion || 'Sin dirección'}</div>
        </div>
    `;
}

// Función para formatear el número de orden
function formatOrderNumber(orderNumber) {
    if (!orderNumber) return 'N/A';
    return `<span class="order-id">#${orderNumber}</span>`;
}

// Función para formatear el total del pedido
function formatOrderTotal(total) {
    if (!total) return '<span class="order-total">$0</span>';
    
    // Convertir a número si es string
    const numericTotal = typeof total === 'string' ? parseFloat(total.replace(/[^\d.-]/g, '')) : total;
    
    // Formatear con separadores de miles
    const formattedTotal = numericTotal.toLocaleString('es-CL', {
        style: 'currency',
        currency: 'CLP',
        minimumFractionDigits: 0
    });
    
    return `<span class="order-total">${formattedTotal}</span>`;
}

// Ejemplo de uso en DataTables
function initializePedidosTable() {
    $('#pedidosTable').DataTable({
        ajax: {
            url: 'api/pedidos.php',
            type: 'GET'
        },
        columns: [
            {
                data: 'orden',
                render: function(data) {
                    return formatOrderNumber(data);
                }
            },
            {
                data: 'estado',
                render: function(data) {
                    return formatOrderStatus(data);
                }
            },
            {
                data: null,
                render: function(data) {
                    return data.rut || 'Sin RUT';
                }
            },
            {
                data: null,
                render: function(data) {
                    return formatClientInfo(data);
                }
            },
            {
                data: 'direccion',
                render: function(data) {
                    return data || 'Sin dirección';
                }
            },
            {
                data: null,
                render: function(data) {
                    return formatContactInfo(data);
                }
            },
            {
                data: 'total',
                render: function(data) {
                    return formatOrderTotal(data);
                }
            },
            {
                data: 'forma_pago',
                render: function(data) {
                    return formatPaymentMethod(data);
                }
            },
            {
                data: null,
                render: function(data) {
                    return formatActionButtons(data, window.PAGE_CONFIG?.privilegios || 0);
                },
                orderable: false,
                searchable: false
            }
        ],
        language: {
            url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json'
        },
        responsive: true,
        order: [[0, 'desc']], // Ordenar por número de orden descendente
        pageLength: 25,
        dom: 'Bfrtip',
        buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print'
        ]
    });
}

// Funciones de callback para los botones (debes implementarlas según tu lógica)
function verDetalleOrden(ordenId) {
    console.log('Ver detalle de orden:', ordenId);
    // Implementar lógica para ver detalle
}

function editarOrden(ordenId) {
    console.log('Editar orden:', ordenId);
    // Implementar lógica para editar
}

function verHistorialOrden(ordenId) {
    console.log('Ver historial de orden:', ordenId);
    // Implementar lógica para ver historial
}

function eliminarOrden(ordenId) {
    console.log('Eliminar orden:', ordenId);
    // Implementar lógica para eliminar con confirmación
    if (confirm('¿Estás seguro de que deseas eliminar esta orden?')) {
        // Lógica de eliminación
    }
}