<!-- Modal para Editar Producto Individual - Versión Compacta Sin Tabs -->
<div class="modal fade" id="modalEditarProducto" tabindex="-1" aria-labelledby="modalEditarProductoLabel" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            
            <!-- Header del Modal -->
            <div class="modal-header bg-warning text-dark py-2">
                <div class="d-flex justify-content-between w-100 align-items-center">
                    <div>
                        <h6 class="modal-title mb-0" id="modalEditarProductoLabel">
                            <i class="fas fa-edit me-2"></i>
                            Producto <span id="productoIdDisplay">#0000</span> - <span id="clienteNombreDisplay">Cliente</span>
                        </h6>
                        <small class="text-dark opacity-75">Orden: <span id="numOrdenDisplay">#0000</span> | Vendedor: <span id="vendedorDisplay">-</span></small>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <span class="badge bg-dark" id="estadoProductoBadge">Cargando...</span>
                        <span class="badge bg-secondary" id="estadoPagoBadge">Sin info</span>
                        <button type="button" class="btn-close btn-sm" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                    </div>
                </div>
            </div>
            
            <!-- Body del Modal -->
            <div class="modal-body p-2">
                
                <!-- Loading del modal -->
                <div id="modalProductoLoading" class="text-center py-3 d-none">
                    <div class="spinner-border text-warning mb-2" role="status">
                        <span class="visually-hidden">Cargando...</span>
                    </div>
                    <div class="text-muted small">Cargando información del producto...</div>
                </div>
                
                <!-- Formulario de edición -->
                <form id="formEditarProducto" class="d-none">
                    <input type="hidden" id="editProductoId" name="producto_id">
                    <input type="hidden" id="editNumOrden" name="num_orden">
                    
                    <!-- Información de Estado y Fechas -->
                    <div class="mb-2">
                        <div class="border rounded p-2" style="background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);">
                            <h6 class="mb-2 text-dark fw-bold">
                                <i class="fas fa-info-circle me-1"></i>
                                Estado del Pedido y Fechas
                            </h6>
                            <div class="row g-1">
                                <div class="col-md-3">
                                    <label for="editEstado" class="form-label small fw-semibold mb-1">Estado Actual</label>
                                    <select class="form-select form-select-sm" id="editEstado" name="estado">
                                        <option value="0">No Aceptado</option>
                                        <option value="1">Aceptado</option>
                                        <option value="2">Por Fabricar</option>
                                        <option value="3">Tela Cortada</option>
                                        <option value="4">Armando Esqueleto</option>
                                        <option value="5">Fabricando</option>
                                        <option value="6">Producto Listo</option>
                                        <option value="7">En Despacho</option>
                                        <option value="8">En Camión</option>
                                        <option value="9">Entregado</option>
                                        <option value="20">Reemitido</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label for="editFechaIngreso" class="form-label small fw-semibold mb-1">Fecha Ingreso</label>
                                    <input type="text" class="form-control form-control-sm" id="editFechaIngreso" name="fecha_ingreso" readonly>
                                </div>
                                <div class="col-md-3">
                                    <label for="editFechaEntrega" class="form-label small fw-semibold mb-1">Fecha Entrega</label>
                                    <input type="date" class="form-control form-control-sm" id="editFechaEntrega" name="fecha_entrega">
                                </div>
                                <div class="col-md-3">
                                    <label for="editEstadoPago" class="form-label small fw-semibold mb-1">Estado Pago</label>
                                    <select class="form-select form-select-sm" id="editEstadoPago" name="estado_pago">
                                        <option value="pendiente">Pendiente</option>
                                        <option value="parcial">Pago Parcial</option>
                                        <option value="pagado">Pagado</option>
                                        <option value="vencido">Vencido</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row g-1 mt-1">
                                <div class="col-md-4">
                                    <label for="editTapicero" class="form-label small fw-semibold mb-1">Tapicero Asignado</label>
                                    <select class="form-select form-select-sm" id="editTapicero" name="tapicero_id">
                                        <option value="">Sin asignar</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label for="editTelefonoCliente" class="form-label small fw-semibold mb-1">Teléfono Cliente</label>
                                    <input type="text" class="form-control form-control-sm" id="editTelefonoCliente" name="telefono_cliente" readonly>
                                </div>
                                <div class="col-md-4">
                                    <label for="editPrecio" class="form-label small fw-semibold mb-1">Precio Total ($) <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control form-control-sm" id="editPrecio" name="precio" placeholder="150.000" required>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Información básica del producto -->
                    <div class="mb-2">
                        <div class="border rounded p-2 bg-light">
                            <h6 class="mb-2 text-dark fw-bold">
                                <i class="fas fa-box me-1"></i>
                                Detalles del Producto
                            </h6>
                            <div class="row g-1">
                                <div class="col-md-6">
                                    <label for="editModelo" class="form-label small fw-semibold mb-1">Modelo/Producto <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control form-control-sm" id="editModelo" name="modelo" required>
                                </div>
                                <div class="col-md-3">
                                    <label for="editTamano" class="form-label small fw-semibold mb-1">Tamaño</label>
                                    <input type="text" class="form-control form-control-sm" id="editTamano" name="tamano">
                                </div>
                                <div class="col-md-3">
                                    <label for="editCantidad" class="form-label small fw-semibold mb-1">Cantidad</label>
                                    <input type="number" class="form-control form-control-sm" id="editCantidad" name="cantidad" min="1" value="1">
                                </div>
                            </div>
                            
                            <div class="row g-1 mt-1">
                                <div class="col-md-4">
                                    <label for="editMaterial" class="form-label small fw-semibold mb-1">Material/Tela</label>
                                    <input type="text" class="form-control form-control-sm" id="editMaterial" name="material" placeholder="Ej: Microfibra">
                                </div>
                                <div class="col-md-4">
                                    <label for="editColor" class="form-label small fw-semibold mb-1">Color</label>
                                    <input type="text" class="form-control form-control-sm" id="editColor" name="color" placeholder="Ej: Gris">
                                </div>
                                <div class="col-md-4">
                                    <label for="editTipoBoton" class="form-label small fw-semibold mb-1">Tipo de Botón</label>
                                    <select class="form-select form-select-sm" id="editTipoBoton" name="tipo_boton">
                                        <option value="">Sin botones</option>
                                        <option value="Normal">Botones Normales</option>
                                        <option value="B Color">Botones de Colores</option>
                                        <option value="B D">Botón Diamante</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Características adicionales -->
                    <div class="mb-2">
                        <div class="border rounded p-2 bg-light">
                            <h6 class="mb-2 text-dark fw-bold">
                                <i class="fas fa-cogs me-1"></i>
                                Características Técnicas
                            </h6>
                            <div class="row g-1">
                                <div class="col-md-6">
                                    <label for="editAnclaje" class="form-label small fw-semibold mb-1">Anclaje</label>
                                    <select class="form-select form-select-sm" id="editAnclaje" name="anclaje">
                                        <option value="no">Sin Anclaje</option>
                                        <option value="si">Con Anclaje</option>
                                        <option value="patas">Patas de Madera</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label for="editAlturaBase" class="form-label small fw-semibold mb-1">Altura Base (cm)</label>
                                    <input type="number" class="form-control form-control-sm" id="editAlturaBase" name="altura_base" min="0" max="100" placeholder="15">
                                </div>
                            </div>
                            
                            <div class="row g-1 mt-1">
                                <div class="col-md-6">
                                    <label for="editComentarios" class="form-label small fw-semibold mb-1">Comentarios del Cliente</label>
                                    <textarea class="form-control form-control-sm" id="editComentarios" name="comentarios" rows="2" placeholder="Observaciones del cliente..."></textarea>
                                </div>
                                <div class="col-md-6">
                                    <label for="editDetallesFabricacion" class="form-label small fw-semibold mb-1">Detalles de Fabricación</label>
                                    <textarea class="form-control form-control-sm" id="editDetallesFabricacion" name="detalles_fabricacion" rows="2" placeholder="Instrucciones específicas..."></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Información de entrega -->
                    <div class="mb-2">
                        <div class="border rounded p-2 bg-light">
                            <h6 class="mb-2 text-dark fw-bold">
                                <i class="fas fa-shipping-fast me-1"></i>
                                Información de Entrega
                            </h6>
                            <div class="row g-1">
                                <div class="col-md-5">
                                    <label for="editDireccion" class="form-label small fw-semibold mb-1">Dirección</label>
                                    <input type="text" class="form-control form-control-sm" id="editDireccion" name="direccion">
                                </div>
                                <div class="col-md-2">
                                    <label for="editNumero" class="form-label small fw-semibold mb-1">Número</label>
                                    <input type="text" class="form-control form-control-sm" id="editNumero" name="numero">
                                </div>
                                <div class="col-md-2">
                                    <label for="editDpto" class="form-label small fw-semibold mb-1">Depto</label>
                                    <input type="text" class="form-control form-control-sm" id="editDpto" name="dpto">
                                </div>
                                <div class="col-md-3">
                                    <label for="editComuna" class="form-label small fw-semibold mb-1">Comuna</label>
                                    <input type="text" class="form-control form-control-sm" id="editComuna" name="comuna">
                                </div>
                            </div>
                            
                            <div class="row g-1 mt-1">
                                <div class="col-md-6">
                                    <label for="editMetodoEntrega" class="form-label small fw-semibold mb-1">Método de Entrega</label>
                                    <select class="form-select form-select-sm" id="editMetodoEntrega" name="metodo_entrega">
                                        <option value="">Seleccionar...</option>
                                        <option value="Despacho">Despacho a Domicilio</option>
                                        <option value="Retiro en tienda">Retiro en Tienda</option>
                                        <option value="Starken">Envío por Starken</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label for="editDetalleEntrega" class="form-label small fw-semibold mb-1">Detalle de Entrega</label>
                                    <input type="text" class="form-control form-control-sm" id="editDetalleEntrega" name="detalle_entrega" placeholder="Información adicional">
                                </div>
                            </div>
                        </div>
                    </div>
                    
                </form>
                
            </div>
            
            <!-- Footer del Modal -->
            <div class="modal-footer bg-light py-2">
                <div class="d-flex justify-content-between align-items-center w-100">
                    <small class="text-muted">
                        <i class="fas fa-clock me-1"></i>
                        Última modificación: <span id="ultimaModificacionProducto">-</span>
                    </small>
                    <div>
                        <button type="button" class="btn btn-outline-secondary btn-sm me-1" data-bs-dismiss="modal">
                            <i class="fas fa-times me-1"></i>Cancelar
                        </button>
                        <button type="button" class="btn btn-warning btn-sm me-1" id="btnGuardarProducto">
                            <i class="fas fa-save me-1"></i>Guardar
                        </button>
                        <button type="button" class="btn btn-success btn-sm" id="btnMarcarProductoListo">
                            <i class="fas fa-check-circle me-1"></i>Marcar Listo
                        </button>
                    </div>
                </div>
            </div>
            
        </div>
    </div>
</div>

<!-- Scripts específicos del modal de producto -->
<script>
// Variables globales del modal de producto
let currentProductoData = null;
let tapicerosDisponibles = [];

/**
 * Función principal para abrir el modal de editar producto
 */
function abrirModalEditarProducto(productoId) {
    console.log('Abriendo modal editar producto:', productoId);
    
    // Mostrar loading y abrir modal
    $('#modalProductoLoading').removeClass('d-none');
    $('#formEditarProducto').addClass('d-none');
    $('#modalEditarProducto').modal('show');
    
    // Cargar datos del producto
    cargarDatosProducto(productoId);
}

/**
 * Cargar datos del producto desde la API
 */
function cargarDatosProducto(productoId) {
    // Cargar en paralelo: datos del producto y tapiceros
    Promise.all([
        cargarProductoIndividual(productoId),
        cargarTapicerosDisponibles()
    ])
    .then(([productoData, tapicerosData]) => {
        currentProductoData = productoData;
        tapicerosDisponibles = tapicerosData;
        
        llenarFormularioProducto(productoData);
        llenarSelectTapiceros(tapicerosData);
        
        // Mostrar formulario
        $('#modalProductoLoading').addClass('d-none');
        $('#formEditarProducto').removeClass('d-none');
    })
    .catch(error => {
        console.error('Error cargando datos del producto:', error);
        if (typeof UIComponents !== 'undefined') {
            UIComponents.showAlert('Error al cargar los datos del producto', 'error');
        }
        $('#modalEditarProducto').modal('hide');
    });
}

/**
 * Cargar datos específicos del producto
 */
function cargarProductoIndividual(productoId) {
    console.log('🔍 Cargando producto ID:', productoId);
    
    if (typeof APIClient !== 'undefined' && APIClient.getProductDetails) {
        console.log('✅ APIClient disponible, llamando getProductDetails...');
        return APIClient.getProductDetails(productoId)
            .then(response => {
                console.log('📊 Respuesta de API:', response);
                if (response.success) {
                    return response.data;
                } else {
                    throw new Error(response.message || 'Error al obtener datos del producto');
                }
            })
            .catch(error => {
                console.error('❌ Error en getProductDetails:', error);
                throw error;
            });
    } else {
        console.warn('⚠️ APIClient no disponible o sin método getProductDetails');
        
        // Intentar llamada directa a la API
        return fetch(`api/get_producto_detalle.php?id=${productoId}`)
            .then(response => {
                console.log('📡 Respuesta fetch:', response);
                if (!response.ok) {
                    throw new Error(`HTTP ${response.status}: ${response.statusText}`);
                }
                return response.json();
            })
            .then(data => {
                console.log('📊 Datos del fetch:', data);
                if (data.success) {
                    return data.data;
                } else {
                    throw new Error(data.message || 'Error en la respuesta');
                }
            })
            .catch(error => {
                console.error('❌ Error en fetch:', error);
                // Solo como último recurso usar datos simulados
                if (typeof UIComponents !== 'undefined') {
                    UIComponents.showAlert('Error cargando datos del producto. Usando datos simulados.', 'warning');
                }
                return {
                    id: productoId,
                    modelo: 'Producto (Error de carga)',
                    tamano: 'N/A',
                    cantidad: 1,
                    material: 'N/A',
                    color: 'N/A',
                    precio: '0',
                    tipo_boton: '',
                    anclaje: 'no',
                    altura_base: '',
                    estado: '1',
                    tapicero_id: '',
                    direccion: '',
                    numero: '',
                    dpto: '',
                    comuna: '',
                    metodo_entrega: '',
                    detalle_entrega: '',
                    comentarios: 'Error al cargar datos reales',
                    detalles_fabricacion: ''
                };
            });
    }
}

/**
 * Cargar lista de tapiceros disponibles
 */
function cargarTapicerosDisponibles() {
    return new Promise((resolve, reject) => {
        if (typeof APIClient !== 'undefined' && APIClient.getTapicerosWithCache) {
            APIClient.getTapicerosWithCache()
                .then(response => {
                    if (response.success) {
                        resolve(response.data);
                    } else {
                        // Datos de fallback
                        resolve([
                            { id: 1, nombres: 'Juan', apaterno: 'Pérez' },
                            { id: 2, nombres: 'María', apaterno: 'González' },
                            { id: 3, nombres: 'Carlos', apaterno: 'López' }
                        ]);
                    }
                })
                .catch(() => {
                    resolve([]); // Array vacío en caso de error
                });
        } else {
            resolve([]); // Array vacío si no hay APIClient
        }
    });
}

/**
 * Llenar el formulario con los datos del producto
 */
function llenarFormularioProducto(data) {
    $('#productoIdDisplay').text('#' + data.id);
    $('#editProductoId').val(data.id);
    
    // Información del header
    $('#numOrdenDisplay').text('#' + (data.num_orden || 'N/A'));
    $('#clienteNombreDisplay').text(data.pedido?.cliente_nombre || 'Cliente');
    $('#vendedorDisplay').text(data.pedido?.vendedor || 'N/A');
    
    // Información de estado y fechas
    $('#editEstado').val(data.estado || '1');
    $('#editFechaIngreso').val(data.pedido?.fecha_ingreso || '');
    $('#editFechaEntrega').val(''); // Se puede agregar al backend
    $('#editEstadoPago').val(data.estado_pago || 'pendiente');
    $('#editTelefonoCliente').val(data.pedido?.cliente_telefono || '');
    $('#editPrecio').val(data.precio || '');
    $('#editTapicero').val(data.tapicero_id || '');
    
    // Información básica del producto
    $('#editModelo').val(data.modelo || '');
    $('#editTamano').val(data.tamano || '');
    $('#editCantidad').val(data.cantidad || 1);
    $('#editMaterial').val(data.material || '');
    $('#editColor').val(data.color || '');
    $('#editTipoBoton').val(data.tipo_boton || '');
    
    // Características técnicas
    $('#editAnclaje').val(data.anclaje || 'no');
    $('#editAlturaBase').val(data.altura_base || '');
    $('#editComentarios').val(data.comentarios || '');
    $('#editDetallesFabricacion').val(data.detalles_fabricacion || '');
    
    // Información de entrega
    $('#editDireccion').val(data.direccion || '');
    $('#editNumero').val(data.numero || '');
    $('#editDpto').val(data.dpto || '');
    $('#editComuna').val(data.comuna || '');
    $('#editMetodoEntrega').val(data.metodo_entrega || '');
    $('#editDetalleEntrega').val(data.detalle_entrega || '');
    
    // Actualizar badges
    actualizarBadgeEstado(data.estado);
    actualizarBadgePago(data.estado_pago || 'pendiente');
    
    // Actualizar timestamp
    $('#ultimaModificacionProducto').text(new Date().toLocaleString());
}

/**
 * Llenar select de tapiceros
 */
function llenarSelectTapiceros(tapiceros) {
    const select = $('#editTapicero');
    select.empty().append('<option value="">Sin asignar</option>');
    
    tapiceros.forEach(tapicero => {
        const option = $('<option></option>')
            .attr('value', tapicero.id)
            .text(`${tapicero.nombres} ${tapicero.apaterno}`);
        select.append(option);
    });
}

/**
 * Actualizar badge de estado
 */
function actualizarBadgeEstado(estadoId) {
    const estados = {
        '0': { nombre: 'No Aceptado', class: 'bg-warning' },
        '1': { nombre: 'Aceptado', class: 'bg-info' },
        '2': { nombre: 'Por Fabricar', class: 'bg-warning' },
        '3': { nombre: 'Tela Cortada', class: 'bg-primary' },
        '4': { nombre: 'Armando Esqueleto', class: 'bg-primary' },
        '5': { nombre: 'Fabricando', class: 'bg-info' },
        '6': { nombre: 'Producto Listo', class: 'bg-success' },
        '7': { nombre: 'En Despacho', class: 'bg-success' },
        '8': { nombre: 'En Camión', class: 'bg-success' },
        '9': { nombre: 'Entregado', class: 'bg-success' },
        '20': { nombre: 'Reemitido', class: 'bg-danger' }
    };
    
    const estado = estados[estadoId] || { nombre: 'Desconocido', class: 'bg-secondary' };
    $('#estadoProductoBadge').removeClass().addClass(`badge ${estado.class}`).text(estado.nombre);
}

/**
 * Actualizar badge de estado de pago
 */
function actualizarBadgePago(estadoPago) {
    const estadosPago = {
        'pendiente': { nombre: 'Pendiente', class: 'bg-warning' },
        'parcial': { nombre: 'Pago Parcial', class: 'bg-info' },
        'pagado': { nombre: 'Pagado', class: 'bg-success' },
        'vencido': { nombre: 'Vencido', class: 'bg-danger' }
    };
    
    const estado = estadosPago[estadoPago] || { nombre: 'Sin info', class: 'bg-secondary' };
    $('#estadoPagoBadge').removeClass().addClass(`badge ${estado.class}`).text(estado.nombre);
}

// Event Listeners del modal de producto
$(document).ready(function() {
    
    // Guardar cambios del producto
    $('#btnGuardarProducto').on('click', function() {
        guardarCambiosProducto();
    });
    
    // Marcar producto como listo
    $('#btnMarcarProductoListo').on('click', function() {
        marcarProductoListo();
    });
    
    // Actualizar badge cuando cambia el estado
    $('#editEstado').on('change', function() {
        actualizarBadgeEstado($(this).val());
    });
    
    // Actualizar badge cuando cambia el estado de pago
    $('#editEstadoPago').on('change', function() {
        actualizarBadgePago($(this).val());
    });
    
    // Formatear precio mientras se escribe
    $('#editPrecio').on('input', function() {
        let value = $(this).val().replace(/[^0-9]/g, '');
        if (value) {
            value = parseInt(value).toLocaleString('es-CL');
            $(this).val(value);
        }
    });
    
    // Validaciones en tiempo real
    $('#editModelo').on('blur', function() {
        if (!$(this).val().trim()) {
            $(this).addClass('is-invalid');
            if (!$(this).next('.invalid-feedback').length) {
                $(this).after('<div class="invalid-feedback">El modelo es requerido</div>');
            }
        } else {
            $(this).removeClass('is-invalid').next('.invalid-feedback').remove();
        }
    });
    
});

/**
 * Guardar cambios del producto - IMPLEMENTACIÓN REAL
 */
function guardarCambiosProducto() {
    // Validar formulario
    if (!validarFormularioProducto()) {
        return;
    }
    
    // Recopilar datos del formulario
    const datosProducto = {
        id: $('#editProductoId').val(),
        num_orden: $('#editNumOrden').val(),
        modelo: $('#editModelo').val().trim(),
        tamano: $('#editTamano').val().trim(),
        cantidad: parseInt($('#editCantidad').val()) || 1,
        material: $('#editMaterial').val().trim(),
        color: $('#editColor').val().trim(),
        precio: $('#editPrecio').val().replace(/[.,\s]/g, ''), // Limpiar formato
        tipo_boton: $('#editTipoBoton').val(),
        anclaje: $('#editAnclaje').val(),
        altura_base: $('#editAlturaBase').val(),
        estado: $('#editEstado').val(),
        estado_pago: $('#editEstadoPago').val(),
        fecha_entrega: $('#editFechaEntrega').val(),
        tapicero_id: $('#editTapicero').val() || null,
        direccion: $('#editDireccion').val().trim(),
        numero: $('#editNumero').val().trim(),
        dpto: $('#editDpto').val().trim(),
        comuna: $('#editComuna').val().trim(),
        metodo_entrega: $('#editMetodoEntrega').val(),
        detalle_entrega: $('#editDetalleEntrega').val().trim(),
        comentarios: $('#editComentarios').val().trim(),
        detalles_fabricacion: $('#editDetallesFabricacion').val().trim()
    };
    
    // Mostrar loading
    if (typeof UIComponents !== 'undefined') {
        UIComponents.showLoading(true, 'Guardando cambios del producto...');
    }
    
    // Deshabilitar botón para evitar doble clic
    $('#btnGuardarProducto').prop('disabled', true);
    
    // Llamar a la API
    if (typeof APIClient !== 'undefined') {
        APIClient.updateProduct(datosProducto)
            .then(response => {
                if (response.success || response.status === 'success') {
                    // Éxito - Cerrar modal y actualizar tabla
                    $('#modalEditarProducto').modal('hide');
                    
                    if (typeof UIComponents !== 'undefined') {
                        UIComponents.showToast('Producto actualizado correctamente', 'success');
                    }
                    
                    // Actualizar tabla principal si está disponible
                    if (typeof PedidosIngresados !== 'undefined') {
                        PedidosIngresados.refreshTable();
                    }
                    
                    // Disparar evento personalizado
                    $(document).trigger('productoActualizado', [datosProducto.id]);
                    
                } else {
                    // Error en la respuesta
                    const mensaje = response.message || 'Error al actualizar el producto';
                    if (typeof UIComponents !== 'undefined') {
                        UIComponents.showAlert(mensaje, 'error');
                    } else {
                        alert('Error: ' + mensaje);
                    }
                }
            })
            .catch(error => {
                console.error('Error al guardar producto:', error);
                const mensaje = error.message || 'Error de conexión al guardar el producto';
                
                if (typeof UIComponents !== 'undefined') {
                    UIComponents.showAlert(mensaje, 'error');
                } else {
                    alert('Error: ' + mensaje);
                }
            })
            .finally(() => {
                // Ocultar loading y rehabilitar botón
                if (typeof UIComponents !== 'undefined') {
                    UIComponents.showLoading(false);
                }
                $('#btnGuardarProducto').prop('disabled', false);
            });
    } else {
        // Fallback si no hay APIClient
        console.error('APIClient no disponible');
        if (typeof UIComponents !== 'undefined') {
            UIComponents.showLoading(false);
            UIComponents.showAlert('Error: Sistema de API no disponible', 'error');
        }
        $('#btnGuardarProducto').prop('disabled', false);
    }
    
    console.log('Datos enviados:', datosProducto);
}
/**
 * Marcar producto como listo
 */
function marcarProductoListo() {
    const productoId = $('#editProductoId').val();
    
    if (typeof UIComponents !== 'undefined') {
        UIComponents.showConfirm('¿Marcar este producto como listo?', 'Confirmar Estado')
            .then(result => {
                if (result.isConfirmed) {
                    $('#editEstado').val('6').trigger('change');
                    guardarCambiosProducto();
                }
            });
        } else {
        if (confirm('¿Marcar este producto como listo?')) {
            $('#editEstado').val('6').trigger('change');
            guardarCambiosProducto();
        }
    }
}

/**
 * Validar formulario antes de guardar
 */
function validarFormularioProducto() {
    let esValido = true;
    
    // Limpiar validaciones previas
    $('.is-invalid').removeClass('is-invalid');
    $('.invalid-feedback').remove();
    
    // Validar campos requeridos
    const camposRequeridos = [
        { selector: '#editModelo', mensaje: 'El modelo es requerido' },
        { selector: '#editPrecio', mensaje: 'El precio es requerido' }
    ];
    
    camposRequeridos.forEach(campo => {
        const input = $(campo.selector);
        if (!input.val().trim()) {
            input.addClass('is-invalid');
            input.after(`<div class="invalid-feedback">${campo.mensaje}</div>`);
            esValido = false;
        }
    });
    
    if (!esValido && typeof UIComponents !== 'undefined') {
        UIComponents.showAlert('Por favor complete todos los campos requeridos', 'warning');
    }
    
    return esValido;
}

// Exportar función para uso global
window.abrirModalEditarProducto = abrirModalEditarProducto;



/**
 * JavaScript para Modal Editar Orden - IMPLEMENTACIÓN REAL
 */

$(document).ready(function() {
    
    // Event listener para el botón Guardar
    $('#btnGuardar').on('click', function(e) {
        e.preventDefault();
        guardarCambiosOrden();
    });
    
    // También manejar submit del formulario
    $('#editarpedido').on('submit', function(e) {
        e.preventDefault();
        guardarCambiosOrden();
    });
    
    // Calcular totales automáticamente
    $('#total_productos, #despacho_valor').on('input', function() {
        calcularTotales();
    });
    
});

/**
 * Función principal para guardar cambios de la orden
 */
function guardarCambiosOrden() {
    // Validar formulario
    if (!validarFormularioOrden()) {
        return;
    }
    
    // Recopilar datos del formulario
    const datosOrden = {
        num_orden: $('#n_orden').val(),
        rut_cliente: $('#rutb').val(),
        nombre_cliente: $('#nombreb').val(),
        telefono: $('#telefono').val(),
        lugar_venta: $('#lugar_venta').val(),
        total_productos: $('#total_productos').val().replace(/[.,\s]/g, ''),
        despacho: $('#despacho_valor').val().replace(/[.,\s]/g, ''),
        total_precio: $('#total_precio').val().replace(/[.,\s]/g, ''),
        modo_pago: $('#mododepago').val(),
        vendedor: $('#vendedorb').val(),
        fecha_ingreso: $('#fecha_ingresob').val()
    };
    
    // Mostrar loading
    if (typeof UIComponents !== 'undefined') {
        UIComponents.showLoading(true, 'Guardando cambios de la orden...');
    }
    
    // Deshabilitar botón
    $('#btnGuardar').prop('disabled', true);
    
    // Llamar a la API
    if (typeof APIClient !== 'undefined') {
        APIClient.updateOrder(datosOrden)
            .then(response => {
                if (response.success || response.status === 'success') {
                    // Éxito
                    $('#modalEditarOrden').modal('hide');
                    
                    if (typeof UIComponents !== 'undefined') {
                        UIComponents.showToast('Orden actualizada correctamente', 'success');
                    }
                    
                    // Actualizar tabla principal
                    if (typeof PedidosIngresados !== 'undefined') {
                        PedidosIngresados.refreshTable();
                    }
                    
                    // Disparar evento personalizado
                    $(document).trigger('ordenActualizada', [datosOrden.num_orden]);
                    
                } else {
                    // Error en la respuesta
                    const mensaje = response.message || 'Error al actualizar la orden';
                    if (typeof UIComponents !== 'undefined') {
                        UIComponents.showAlert(mensaje, 'error');
                    } else {
                        alert('Error: ' + mensaje);
                    }
                }
            })
            .catch(error => {
                console.error('Error al guardar orden:', error);
                const mensaje = error.message || 'Error de conexión al guardar la orden';
                
                if (typeof UIComponents !== 'undefined') {
                    UIComponents.showAlert(mensaje, 'error');
                } else {
                    alert('Error: ' + mensaje);
                }
            })
            .finally(() => {
                // Limpiar loading y rehabilitar botón
                if (typeof UIComponents !== 'undefined') {
                    UIComponents.showLoading(false);
                }
                $('#btnGuardar').prop('disabled', false);
            });
    } else {
        console.error('APIClient no disponible');
        if (typeof UIComponents !== 'undefined') {
            UIComponents.showLoading(false);
            UIComponents.showAlert('Error: Sistema de API no disponible', 'error');
        }
        $('#btnGuardar').prop('disabled', false);
    }
    
    console.log('Datos de orden enviados:', datosOrden);
}

/**
 * Validar formulario de orden
 */
function validarFormularioOrden() {
    let esValido = true;
    const errores = [];
    
    // Validar campos requeridos
    if (!$('#n_orden').val().trim()) {
        errores.push('Número de orden es requerido');
        $('#n_orden').addClass('is-invalid');
        esValido = false;
    }
    
    if (!$('#rutb').val().trim()) {
        errores.push('RUT del cliente es requerido');
        $('#rutb').addClass('is-invalid');
        esValido = false;
    }
    
    if (!$('#nombreb').val().trim()) {
        errores.push('Nombre del cliente es requerido');
        $('#nombreb').addClass('is-invalid');
        esValido = false;
    }
    
    // Validar formato de precios
    const totalProductos = $('#total_productos').val();
    if (totalProductos && isNaN(totalProductos.replace(/[.,\s]/g, ''))) {
        errores.push('Total de productos debe ser un número válido');
        $('#total_productos').addClass('is-invalid');
        esValido = false;
    }
    
    if (!esValido) {
        const mensaje = 'Por favor corrige los siguientes errores:\n' + errores.join('\n');
        if (typeof UIComponents !== 'undefined') {
            UIComponents.showAlert(mensaje, 'warning');
        } else {
            alert(mensaje);
        }
    }
    
    return esValido;
}

/**
 * FUNCIÓN CORREGIDA: calcularTotales
 * Maneja correctamente valores undefined y null
 */

function calcularTotales(datos) {
    // Función auxiliar para limpiar y convertir valores monetarios
    function limpiarValorMonetario(valor) {
        if (valor === null || valor === undefined || valor === '') {
            return 0;
        }
        
        // Convertir a string de forma segura
        const valorStr = String(valor);
        
        // Limpiar el formato y convertir a número
        const valorLimpio = valorStr.replace(/[$,.]/g, '');
        const numero = parseFloat(valorLimpio);
        
        return isNaN(numero) ? 0 : numero;
    }
    
    // Calcular total de productos
    let totalProductos = 0;
    if (datos && datos.detalles && Array.isArray(datos.detalles)) {
        totalProductos = datos.detalles.reduce(function(sum, detalle) {
            if (!detalle) return sum;
            
            const precio = limpiarValorMonetario(detalle.precio);
            return sum + precio;
        }, 0);
    }
    
    // Obtener otros valores de forma segura
    const costoDespacho = limpiarValorMonetario(datos?.despacho);
    const totalPagado = limpiarValorMonetario(datos?.total_pagado);
    
    // Calcular totales
    const totalGeneral = totalProductos + costoDespacho;
    const saldoPendiente = totalGeneral - totalPagado;
    
    // Actualizar campos en el DOM de forma segura
    try {
        const $totalProductos = $('#totalProductos');
        const $costoDespacho = $('#costoDespacho');
        const $totalGeneral = $('#totalGeneral');
        const $totalPagado = $('#totalPagado');
        const $saldoPendiente = $('#saldoPendiente');
        
        if ($totalProductos.length) $totalProductos.val(formatearNumero(totalProductos));
        if ($costoDespacho.length) $costoDespacho.val(formatearNumero(costoDespacho));
        if ($totalGeneral.length) $totalGeneral.val(formatearNumero(totalGeneral));
        if ($totalPagado.length) $totalPagado.val(formatearNumero(totalPagado));
        if ($saldoPendiente.length) $saldoPendiente.val(formatearNumero(saldoPendiente));
        
        // Cambiar color del saldo según si está pendiente o pagado
        const saldoInput = $('#saldoPendiente');
        const saldoIcon = $('#saldoIcon');
        
        if (saldoInput.length && saldoIcon.length) {
            if (saldoPendiente > 0) {
                saldoInput.removeClass('text-success').addClass('text-danger');
                saldoIcon.removeClass('text-success').addClass('text-danger');
            } else {
                saldoInput.removeClass('text-danger').addClass('text-success');
                saldoIcon.removeClass('text-danger').addClass('text-success');
            }
        }
        
    } catch (error) {
        console.error('Error actualizando campos del DOM:', error);
    }
    
    // Log para debugging
    console.log('Totales calculados:', {
        totalProductos,
        costoDespacho,
        totalGeneral,
        totalPagado,
        saldoPendiente
    });
}

/**
 * FUNCIÓN AUXILIAR MEJORADA: formatearNumero
 * Maneja valores undefined/null de forma segura
 */
function formatearNumero(numero) {
    if (numero === null || numero === undefined || numero === '' || isNaN(numero)) {
        return '0';
    }
    
    // Asegurar que es un número
    const num = typeof numero === 'number' ? numero : parseFloat(String(numero).replace(/[$,.]/g, ''));
    
    if (isNaN(num)) {
        return '0';
    }
    
    return num.toLocaleString('es-CL');
}

/**
 * FUNCIÓN CORREGIDA: llenarModalConDatos
 * Versión con manejo seguro de datos
 */
function llenarModalConDatos(datos) {
    try {
        // Verificar que datos existe
        if (!datos) {
            console.warn('No se recibieron datos para llenar el modal');
            return;
        }
        
        // Función auxiliar para obtener valor seguro
        function obtenerValorSeguro(valor, valorPorDefecto = '') {
            return (valor !== null && valor !== undefined) ? valor : valorPorDefecto;
        }
        
        // Información básica - Con verificación de existencia de elementos
        const campos = {
            '#ordenNumeroDisplay': `#${obtenerValorSeguro(datos.num_orden)}`,
            '#numOrden': obtenerValorSeguro(datos.num_orden),
            '#fechaIngreso': obtenerValorSeguro(datos.fecha_ingreso),
            '#vendedor': obtenerValorSeguro(datos.vendedor),
            '#rutCliente': obtenerValorSeguro(datos.rut_cliente),
            '#nombreCliente': obtenerValorSeguro(datos.nombre),
            '#telefonoCliente': obtenerValorSeguro(datos.telefono),
            '#instagramCliente': obtenerValorSeguro(datos.instagram)
        };
        
        // Actualizar campos de forma segura
        Object.entries(campos).forEach(([selector, valor]) => {
            const $elemento = $(selector);
            if ($elemento.length) {
                if ($elemento.is('input, select, textarea')) {
                    $elemento.val(valor);
                } else {
                    $elemento.text(valor);
                }
            }
        });
        
        // Canal de venta
        const canalIcon = $('#canalIcon i');
        const canalVenta = $('#canalVenta');
        
        if (datos.orden_ext) {
            if (canalVenta.length) canalVenta.val('Tienda Online');
            if (canalIcon.length) canalIcon.removeClass('fa-store').addClass('fa-shopping-cart');
        } else {
            if (canalVenta.length) canalVenta.val('Tienda Física');
            if (canalIcon.length) canalIcon.removeClass('fa-shopping-cart').addClass('fa-store');
        }
        
        // Información de entrega - Verificar que detalles existe y tiene elementos
        if (datos.detalles && Array.isArray(datos.detalles) && datos.detalles.length > 0) {
            const primerDetalle = datos.detalles[0] || {};
            
            const camposEntrega = {
                '#direccionEntrega': obtenerValorSeguro(primerDetalle.direccion),
                '#numeroEntrega': obtenerValorSeguro(primerDetalle.numero),
                '#dptoEntrega': obtenerValorSeguro(primerDetalle.dpto),
                '#comunaEntrega': obtenerValorSeguro(primerDetalle.comuna)
            };
            
            Object.entries(camposEntrega).forEach(([selector, valor]) => {
                const $elemento = $(selector);
                if ($elemento.length) {
                    $elemento.val(valor);
                }
            });
        }
        
        // Llenar tabla de productos
        if (typeof llenarTablaProductos === 'function') {
            llenarTablaProductos(datos.detalles || []);
        }
        
        // Llenar tabla de pagos
        if (typeof llenarTablaPagos === 'function') {
            llenarTablaPagos(datos.pagos || []);
        }
        
        // Calcular totales de forma segura
        calcularTotales(datos);
        
        // Configurar botón de WhatsApp
        if (typeof configurarWhatsApp === 'function') {
            configurarWhatsApp(datos.telefono);
        }
        
        // Actualizar timestamp
        const $ultimaActualizacion = $('#ultimaActualizacion');
        if ($ultimaActualizacion.length) {
            $ultimaActualizacion.text(new Date().toLocaleString());
        }
        
        console.log('✅ Modal llenado correctamente con datos:', datos);
        
    } catch (error) {
        console.error('❌ Error llenando modal con datos:', error);
        console.error('Datos recibidos:', datos);
        
        if (typeof UIComponents !== 'undefined') {
            UIComponents.showAlert('Error al cargar los datos en el modal', 'error');
        }
    }
}

/**
 * FUNCIÓN AUXILIAR: Validar estructura de datos
 */
function validarEstructuraDatos(datos) {
    const errores = [];
    
    if (!datos) {
        errores.push('Datos es null o undefined');
        return errores;
    }
    
    // Verificar campos esenciales
    const camposEsenciales = ['num_orden'];
    camposEsenciales.forEach(campo => {
        if (!datos[campo]) {
            errores.push(`Campo requerido '${campo}' no encontrado`);
        }
    });
    
    // Verificar estructura de detalles
    if (datos.detalles && !Array.isArray(datos.detalles)) {
        errores.push('Campo detalles debe ser un array');
    }
    
    // Verificar estructura de pagos
    if (datos.pagos && !Array.isArray(datos.pagos)) {
        errores.push('Campo pagos debe ser un array');
    }
    
    return errores;
}

/**
 * WRAPPER SEGURO para cargar datos de orden
 */
function cargarDatosOrden(numOrden) {
    if (!numOrden) {
        console.error('Número de orden no proporcionado');
        if (typeof UIComponents !== 'undefined') {
            UIComponents.showAlert('Número de orden requerido', 'error');
        }
        return;
    }
    
    if (typeof APIClient === 'undefined') {
        console.error('APIClient no está disponible');
        if (typeof UIComponents !== 'undefined') {
            UIComponents.showAlert('Sistema de API no disponible', 'error');
        }
        return;
    }
    
    console.log('🔍 Cargando datos para orden:', numOrden);
    
    APIClient.getOrderDetails(numOrden)
        .then(function(response) {
            console.log('📨 Respuesta recibida:', response);
            
            if (response && response.success && response.data) {
                // Validar estructura de datos
                const errores = validarEstructuraDatos(response.data);
                if (errores.length > 0) {
                    console.warn('⚠️ Advertencias en estructura de datos:', errores);
                }
                
                // Llenar modal con datos
                llenarModalConDatos(response.data);
                
                // Mostrar contenido y ocultar loading
                const $modalLoading = $('#modalLoading');
                const $modalContent = $('#modalContent');
                
                if ($modalLoading.length) $modalLoading.addClass('d-none');
                if ($modalContent.length) $modalContent.removeClass('d-none');
                
            } else {
                throw new Error(response?.message || 'Respuesta inválida del servidor');
            }
        })
        .catch(function(error) {
            console.error('❌ Error cargando orden:', error);
            
            if (typeof UIComponents !== 'undefined') {
                UIComponents.showAlert('Error al cargar los datos de la orden: ' + error.message, 'error');
            } else {
                alert('Error: ' + error.message);
            }
            
            // Cerrar modal en caso de error
            const $modal = $('#modalGestionOrden');
            if ($modal.length) {
                $modal.modal('hide');
            }
        });
}

console.log('✅ Funciones de cálculo corregidas cargadas');

/**
 * Formatear número para mostrar
 */
function formatearNumero(numero) {
    if (!numero) return '0';
    return numero.toLocaleString('es-CL');
}

/**
 * Limpiar validaciones al hacer cambios
 */
$(document).on('input', '.form-control', function() {
    $(this).removeClass('is-invalid');
});

console.log('✅ JavaScript de Modal Editar Orden cargado');

</script>

<!-- Estilos específicos del modal de producto -->
<style>
/* Espaciado compacto general */
#modalEditarProducto .form-label {
    font-size: 0.75rem;
    font-weight: 600;
    color: #374151;
    margin-bottom: 2px;
}

#modalEditarProducto .form-control-sm,
#modalEditarProducto .form-select-sm {
    font-size: 0.8rem;
    padding: 0.25rem 0.4rem;
    height: auto;
}

#modalEditarProducto .row.g-1 > * {
    padding: 0.125rem;
}

#modalEditarProducto .border.rounded {
    border: 1px solid #e5e7eb !important;
    background-color: #f9fafb !important;
}

#modalEditarProducto h6 {
    font-size: 0.8rem;
    margin-bottom: 0.5rem;
}

#modalEditarProducto .modal-header {
    padding: 0.5rem 1rem;
}

#modalEditarProducto .modal-body {
    padding: 0.75rem;
}

#modalEditarProducto .modal-footer {
    padding: 0.5rem 1rem;
}

#modalEditarProducto .btn-sm {
    padding: 0.2rem 0.6rem;
    font-size: 0.75rem;
}

#modalEditarProducto .badge {
    font-size: 0.7rem;
}

#modalEditarProducto .form-control:focus,
#modalEditarProducto .form-select:focus {
    border-color: #f59e0b;
    box-shadow: 0 0 0 0.15rem rgba(245, 158, 11, 0.25);
}

#modalEditarProducto .is-invalid {
    border-color: #ef4444;
}

#modalEditarProducto .invalid-feedback {
    display: block;
    font-size: 0.7rem;
}

/* Reducir espacio entre secciones */
#modalEditarProducto .mb-2 {
    margin-bottom: 0.5rem !important;
}

#modalEditarProducto .mt-1 {
    margin-top: 0.25rem !important;
}

/* Textarea más compacta */
#modalEditarProducto textarea.form-control-sm {
    min-height: 50px;
}

/* Sección destacada de estado */
#modalEditarProducto .border.rounded[style*="gradient"] {
    border: 2px solid #f59e0b !important;
    box-shadow: 0 2px 8px rgba(245, 158, 11, 0.15);
}

/* Header información adicional */
#modalEditarProducto .modal-header small {
    font-size: 0.7rem;
    line-height: 1.2;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    #modalEditarProducto .modal-dialog {
        margin: 0.25rem;
        max-width: none;
    }
    
    #modalEditarProducto .modal-body {
        padding: 0.5rem;
    }
    
    #modalEditarProducto .border.rounded {
        padding: 0.5rem !important;
    }
    
    #modalEditarProducto .modal-header .d-flex {
        flex-direction: column;
        align-items: flex-start !important;
    }
    
    #modalEditarProducto .modal-header .gap-2 {
        margin-top: 0.25rem;
        align-self: flex-end;
    }
}
</style>


