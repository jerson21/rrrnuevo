<!-- 
===============================================================================
MODAL DE GESTIÓN DE ORDEN - RESPALDOSCHILE (VERSIÓN COMPACTA)
===============================================================================
Modal completo para gestionar órdenes: datos, productos, pagos y acciones
@version 1.0 - Compacta
@author RespaldosChile Team
===============================================================================
-->

<!-- Modal Principal de Gestión de Orden -->
<div class="modal fade" id="modalGestionOrden" tabindex="-1" aria-labelledby="modalGestionOrdenLabel" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            
            <!-- Header del Modal -->
            <div class="modal-header bg-primary text-white py-2">
                <h6 class="modal-title mb-0" id="modalGestionOrdenLabel">
                    <i class="fas fa-edit me-2"></i>
                    Gestión de Orden <span id="ordenNumeroDisplay">#0000</span>
                </h6>
                <div class="d-flex align-items-center gap-2">
                    <span class="badge bg-light text-dark" id="estadoOrdenBadge">Cargando...</span>
                    <button type="button" class="btn-close btn-close-white btn-sm" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
            </div>
            
            <!-- Body del Modal -->
            <div class="modal-body p-0">
                
                <!-- Loading Overlay para el modal -->
                <div id="modalLoading" class="d-none">
                    <div class="text-center p-3">
                        <div class="spinner-border text-primary mb-2" role="status">
                            <span class="visually-hidden">Cargando...</span>
                        </div>
                        <div class="text-muted small">Cargando información de la orden...</div>
                    </div>
                </div>
                
                <!-- Contenido Principal -->
                <div id="modalContent" class="d-none">
                    
                    <!-- Navegación por Tabs -->
                    <ul class="nav nav-tabs nav-fill" id="ordenTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active py-2" id="info-tab" data-bs-toggle="tab" data-bs-target="#info-panel" 
                                    type="button" role="tab" aria-controls="info-panel" aria-selected="true">
                                <i class="fas fa-info-circle me-1"></i><span class="d-none d-md-inline">Información</span>
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link py-2" id="productos-tab" data-bs-toggle="tab" data-bs-target="#productos-panel" 
                                    type="button" role="tab" aria-controls="productos-panel" aria-selected="false">
                                <i class="fas fa-box me-1"></i><span class="d-none d-md-inline">Productos</span> <span class="badge bg-primary ms-1" id="productosCount">0</span>
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link py-2" id="pagos-tab" data-bs-toggle="tab" data-bs-target="#pagos-panel" 
                                    type="button" role="tab" aria-controls="pagos-panel" aria-selected="false">
                                <i class="fas fa-credit-card me-1"></i><span class="d-none d-md-inline">Pagos</span> <span class="badge bg-success ms-1" id="pagosCount">0</span>
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link py-2" id="historial-tab" data-bs-toggle="tab" data-bs-target="#historial-panel" 
                                    type="button" role="tab" aria-controls="historial-panel" aria-selected="false">
                                <i class="fas fa-history me-1"></i><span class="d-none d-md-inline">Historial</span>
                            </button>
                        </li>
                    </ul>
                    
                    <!-- Contenido de los Tabs -->
                    <div class="tab-content" id="ordenTabsContent">
                        
                        <!-- Panel de Información General -->
                        <div class="tab-pane fade show active" id="info-panel" role="tabpanel" aria-labelledby="info-tab">
                            <div class="p-2">
                                
                                <!-- Información básica de la orden -->
                                <div class="mb-2">
                                    <div class="card">
                                        <div class="card-header py-1">
                                            <h6 class="card-title mb-0 small">
                                                <i class="fas fa-clipboard-list me-1"></i>
                                                Datos de la Orden
                                            </h6>
                                        </div>
                                        <div class="card-body p-2">
                                            <form id="formInfoOrden">
                                                <div class="row g-1">
                                                    <div class="col-md-2">
                                                        <label for="numOrden" class="form-label small fw-semibold mb-1">Número Orden</label>
                                                        <input type="text" class="form-control form-control-sm" id="numOrden" name="numOrden" readonly>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <label for="fechaIngreso" class="form-label small fw-semibold mb-1">Fecha Ingreso</label>
                                                        <input type="text" class="form-control form-control-sm" id="fechaIngreso" name="fechaIngreso" readonly>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <label for="vendedor" class="form-label small fw-semibold mb-1">Vendedor</label>
                                                        <input type="text" class="form-control form-control-sm" id="vendedor" name="vendedor">
                                                    </div>
                                                    <div class="col-md-2">
                                                        <label for="estadoGeneral" class="form-label small fw-semibold mb-1">Estado General</label>
                                                        <input type="text" class="form-control form-control-sm" id="estadoGeneral" name="estadoGeneral" readonly>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <label for="canalVenta" class="form-label small fw-semibold mb-1">Canal</label>
                                                        <div class="input-group input-group-sm">
                                                            <input type="text" class="form-control" id="canalVenta" name="canalVenta" readonly>
                                                            <span class="input-group-text" id="canalIcon">
                                                                <i class="fas fa-store"></i>
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Información del cliente -->
                                <div class="mb-2">
                                    <div class="card">
                                        <div class="card-header py-1">
                                            <h6 class="card-title mb-0 small">
                                                <i class="fas fa-user me-1"></i>
                                                Información del Cliente
                                            </h6>
                                        </div>
                                        <div class="card-body p-2">
                                            <form id="formInfoCliente">
                                                <div class="row g-1">
                                                    <div class="col-md-3">
                                                        <label for="rutCliente" class="form-label small fw-semibold mb-1">RUT Cliente</label>
                                                        <input type="text" class="form-control form-control-sm" id="rutCliente" name="rutCliente" readonly>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label for="nombreCliente" class="form-label small fw-semibold mb-1">Nombre</label>
                                                        <input type="text" class="form-control form-control-sm" id="nombreCliente" name="nombreCliente">
                                                    </div>
                                                    <div class="col-md-3">
                                                        <label for="telefonoCliente" class="form-label small fw-semibold mb-1">Teléfono</label>
                                                        <div class="input-group input-group-sm">
                                                            <input type="text" class="form-control" id="telefonoCliente" name="telefonoCliente">
                                                            <button type="button" class="btn btn-success" id="btnWhatsApp">
                                                                <i class="fab fa-whatsapp"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <label for="instagramCliente" class="form-label small fw-semibold mb-1">Instagram</label>
                                                        <div class="input-group input-group-sm">
                                                            <span class="input-group-text">@</span>
                                                            <input type="text" class="form-control" id="instagramCliente" name="instagramCliente">
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Información de entrega -->
                                <div class="mb-2">
                                    <div class="card">
                                        <div class="card-header py-1">
                                            <h6 class="card-title mb-0 small">
                                                <i class="fas fa-shipping-fast me-1"></i>
                                                Información de Entrega
                                            </h6>
                                        </div>
                                        <div class="card-body p-2">
                                            <form id="formInfoEntrega">
                                                <div class="row g-1">
                                                    <div class="col-md-4">
                                                        <label for="direccionEntrega" class="form-label small fw-semibold mb-1">Dirección</label>
                                                        <input type="text" class="form-control form-control-sm" id="direccionEntrega" name="direccionEntrega">
                                                    </div>
                                                    <div class="col-md-2">
                                                        <label for="numeroEntrega" class="form-label small fw-semibold mb-1">Número</label>
                                                        <input type="text" class="form-control form-control-sm" id="numeroEntrega" name="numeroEntrega">
                                                    </div>
                                                    <div class="col-md-2">
                                                        <label for="dptoEntrega" class="form-label small fw-semibold mb-1">Depto/Of.</label>
                                                        <input type="text" class="form-control form-control-sm" id="dptoEntrega" name="dptoEntrega">
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label for="comunaEntrega" class="form-label small fw-semibold mb-1">Comuna</label>
                                                        <input type="text" class="form-control form-control-sm" id="comunaEntrega" name="comunaEntrega">
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Resumen financiero -->
                                <div class="mb-1">
                                    <div class="card">
                                        <div class="card-header py-1">
                                            <h6 class="card-title mb-0 small">
                                                <i class="fas fa-calculator me-1"></i>
                                                Resumen Financiero
                                            </h6>
                                        </div>
                                        <div class="card-body p-2">
                                            <div class="row g-1">
                                                <div class="col-md-3">
                                                    <label class="form-label small fw-semibold mb-1">Total Productos</label>
                                                    <div class="input-group input-group-sm">
                                                        <span class="input-group-text">$</span>
                                                        <input type="text" class="form-control" id="totalProductos" readonly>
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <label class="form-label small fw-semibold mb-1">Despacho</label>
                                                    <div class="input-group input-group-sm">
                                                        <span class="input-group-text">$</span>
                                                        <input type="text" class="form-control" id="costoDespacho">
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <label class="form-label small fw-semibold mb-1">Total General</label>
                                                    <div class="input-group input-group-sm">
                                                        <span class="input-group-text">$</span>
                                                        <input type="text" class="form-control fw-bold" id="totalGeneral" readonly>
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <label class="form-label small fw-semibold mb-1">Total Pagado</label>
                                                    <div class="input-group input-group-sm">
                                                        <span class="input-group-text text-success">$</span>
                                                        <input type="text" class="form-control text-success fw-bold" id="totalPagado" readonly>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <label class="form-label small fw-semibold mb-1">Saldo Pendiente</label>
                                                    <div class="input-group input-group-sm">
                                                        <span class="input-group-text" id="saldoIcon">$</span>
                                                        <input type="text" class="form-control fw-bold" id="saldoPendiente" readonly>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                            </div>
                        </div>
                        
                        <!-- Panel de Productos -->
                        <div class="tab-pane fade" id="productos-panel" role="tabpanel" aria-labelledby="productos-tab">
                            <div class="p-2">
                                
                                <!-- Controles de productos -->
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <h6 class="mb-0 small">
                                        <i class="fas fa-box me-1"></i>
                                        Productos de la Orden
                                    </h6>
                                    <div class="btn-group btn-group-sm">
                                        <button type="button" class="btn btn-outline-primary btn-sm" id="btnAgregarProducto">
                                            <i class="fas fa-plus"></i> <span class="d-none d-md-inline">Agregar</span>
                                        </button>
                                        <button type="button" class="btn btn-outline-secondary btn-sm" id="btnActualizarEstados">
                                            <i class="fas fa-sync-alt"></i> <span class="d-none d-md-inline">Actualizar</span>
                                        </button>
                                    </div>
                                </div>
                                
                                <!-- Tabla de productos -->
                                <div class="table-responsive">
                                    <table class="table table-hover table-sm" id="tablaProductosOrden">
                                        <thead class="table-light">
                                            <tr>
                                                <th width="50">ID</th>
                                                <th width="130">Producto</th>
                                                <th width="80">Tamaño</th>
                                                <th width="80">Material</th>
                                                <th width="70">Color</th>
                                                <th width="50">Cant.</th>
                                                <th width="80">Precio</th>
                                                <th width="100">Tapicero</th>
                                                <th width="120">Estado</th>
                                                <th width="150">Observaciones</th>
                                                <th width="100">Acciones</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <!-- Se llena dinámicamente -->
                                        </tbody>
                                    </table>
                                </div>
                                
                            </div>
                        </div>
                        
                        <!-- Panel de Pagos -->
                        <div class="tab-pane fade" id="pagos-panel" role="tabpanel" aria-labelledby="pagos-tab">
                            <div class="p-2">
                                
                                <!-- Controles de pagos -->
                                <div class="row mb-2">
                                    <div class="col-md-6">
                                        <div class="card h-100">
                                            <div class="card-header py-1">
                                                <h6 class="card-title mb-0 small">
                                                    <i class="fas fa-search me-1"></i>
                                                    Buscar Pagos
                                                </h6>
                                            </div>
                                            <div class="card-body p-2">
                                                <form id="formBuscarPagos">
                                                    <div class="row g-1">
                                                        <div class="col-md-4">
                                                            <select class="form-select form-select-sm" id="criterioBusqueda">
                                                                <option value="">Buscar por...</option>
                                                                <option value="rut">RUT Cliente</option>
                                                                <option value="rutTercero">RUT Tercero</option>
                                                                <option value="numeroTransaccion">Nº Transacción</option>
                                                                <option value="nombreTercero">Nombre</option>
                                                            </select>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <input type="text" class="form-control form-control-sm" id="valorBusqueda" placeholder="Ingrese valor a buscar">
                                                        </div>
                                                        <div class="col-md-2">
                                                            <button type="submit" class="btn btn-primary btn-sm w-100">
                                                                <i class="fas fa-search"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="card h-100">
                                            <div class="card-header py-1">
                                                <h6 class="card-title mb-0 small">
                                                    <i class="fas fa-plus me-1"></i>
                                                    Agregar Pago
                                                </h6>
                                            </div>
                                            <div class="card-body p-2">
                                                <form id="formAgregarPago">
                                                    <div class="row g-1">
                                                        <div class="col-md-4">
                                                            <select class="form-select form-select-sm" id="tipoPago">
                                                                <option value="">Tipo de pago...</option>
                                                                <option value="efectivo">Efectivo</option>
                                                                <option value="credito">Tarjeta Crédito</option>
                                                                <option value="debito">Tarjeta Débito</option>
                                                                <option value="transferencia">Transferencia</option>
                                                            </select>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <input type="number" class="form-control form-control-sm" id="montoPago" placeholder="Monto">
                                                        </div>
                                                        <div class="col-md-4">
                                                            <button type="submit" class="btn btn-success btn-sm w-100">
                                                                <i class="fas fa-plus"></i> Agregar
                                                            </button>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Resultados de búsqueda -->
                                <div class="row mb-2" id="resultadosBusqueda" style="display: none;">
                                    <div class="col-12">
                                        <div class="card">
                                            <div class="card-header py-1">
                                                <h6 class="card-title mb-0 small">
                                                    <i class="fas fa-list me-1"></i>
                                                    Resultados de Búsqueda
                                                </h6>
                                            </div>
                                            <div class="card-body p-2">
                                                <div class="table-responsive">
                                                    <table class="table table-sm" id="tablaResultadosPagos">
                                                        <thead>
                                                            <tr>
                                                                <th>ID</th>
                                                                <th>Fecha</th>
                                                                <th>RUT</th>
                                                                <th>Nombre</th>
                                                                <th>Banco</th>
                                                                <th>Monto</th>
                                                                <th>Detalle</th>
                                                                <th>Acción</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <!-- Se llena dinámicamente -->
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Pagos asociados -->
                                <div class="row">
                                    <div class="col-12">
                                        <div class="card">
                                            <div class="card-header py-1">
                                                <h6 class="card-title mb-0 small">
                                                    <i class="fas fa-check-circle me-1"></i>
                                                    Pagos Asociados a esta Orden
                                                </h6>
                                            </div>
                                            <div class="card-body p-2">
                                                <div class="table-responsive">
                                                    <table class="table table-hover table-sm" id="tablaPagosAsociados">
                                                        <thead class="table-light">
                                                            <tr>
                                                                <th>ID</th>
                                                                <th>Fecha</th>
                                                                <th>RUT</th>
                                                                <th>Nombre</th>
                                                                <th>Banco</th>
                                                                <th>Monto</th>
                                                                <th>Método</th>
                                                                <th>Acciones</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <!-- Se llena dinámicamente -->
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                            </div>
                        </div>
                        
                        <!-- Panel de Historial -->
                        <div class="tab-pane fade" id="historial-panel" role="tabpanel" aria-labelledby="historial-tab">
                            <div class="p-2">
                                
                                <h6 class="mb-2 small">
                                    <i class="fas fa-history me-1"></i>
                                    Historial de Cambios de la Orden
                                </h6>
                                
                                <!-- Timeline de cambios -->
                                <div id="timelineHistorial">
                                    <!-- Se llena dinámicamente -->
                                </div>
                                
                            </div>
                        </div>
                        
                    </div>
                </div>
                
            </div>
            
            <!-- Footer del Modal -->
            <div class="modal-footer bg-light py-2">
                <div class="d-flex justify-content-between align-items-center w-100">
                    <div class="text-muted small">
                        <i class="fas fa-info-circle me-1"></i>
                        Última actualización: <span id="ultimaActualizacion">-</span>
                    </div>
                    <div>
                        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">
                            <i class="fas fa-times me-1"></i>Cerrar
                        </button>
                        <button type="button" class="btn btn-primary btn-sm" id="btnGuardarCambios">
                            <i class="fas fa-save me-1"></i>Guardar
                        </button>
                        <button type="button" class="btn btn-success btn-sm" id="btnMarcarOrdenCompleta">
                            <i class="fas fa-check-circle me-1"></i>Completar
                        </button>
                    </div>
                </div>
            </div>
            
        </div>
    </div>
</div>

<!-- Modal para Cambiar Estado de Producto -->
<div class="modal fade" id="modalCambiarEstado" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header py-2">
                <h6 class="modal-title">
                    <i class="fas fa-exchange-alt me-2"></i>
                    Cambiar Estado del Producto
                </h6>
                <button type="button" class="btn-close btn-sm" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-2">
                <form id="formCambiarEstado">
                    <input type="hidden" id="productoIdEstado">
                    
                    <div class="mb-2">
                        <label for="nuevoEstado" class="form-label small fw-semibold mb-1">Nuevo Estado</label>
                        <select class="form-select form-select-sm" id="nuevoEstado" required>
                            <option value="">Seleccionar estado...</option>
                            <option value="1">Aceptado</option>
                            <option value="2">Por Fabricar</option>
                            <option value="3">Tela Cortada</option>
                            <option value="4">Armando Esqueleto</option>
                            <option value="5">Fabricando</option>
                            <option value="6">Producto Listo</option>
                            <option value="7">En Despacho</option>
                            <option value="8">En Camión</option>
                            <option value="9">Entregado</option>
                        </select>
                    </div>
                    
                    <div class="mb-2">
                        <label for="observacionEstado" class="form-label small fw-semibold mb-1">Observación (opcional)</label>
                        <textarea class="form-control form-control-sm" id="observacionEstado" rows="2" 
                                  placeholder="Agregar comentario sobre el cambio de estado..."></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer py-2">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary btn-sm" id="btnConfirmarCambioEstado">
                    <i class="fas fa-check me-1"></i>Cambiar Estado
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Scripts específicos del modal -->
<script>
// Función para abrir el modal de gestión de orden
function abrirModalGestionOrden(numOrden) {
    // Mostrar loading
    $('#modalLoading').removeClass('d-none');
    $('#modalContent').addClass('d-none');
    $('#modalGestionOrden').modal('show');
    
    // Cargar datos de la orden
    cargarDatosOrden(numOrden);
}

// Función para cargar datos de la orden
function cargarDatosOrden(numOrden) {
    if (typeof APIClient === 'undefined') {
        console.error('APIClient no está disponible');
        return;
    }
    
    APIClient.getOrderDetails(numOrden)
        .then(function(response) {
            if (response.success) {
                llenarModalConDatos(response.data);
                $('#modalLoading').addClass('d-none');
                $('#modalContent').removeClass('d-none');
            } else {
                if (typeof UIComponents !== 'undefined') {
                    UIComponents.showAlert('Error al cargar los datos de la orden', 'error');
                }
            }
        })
        .catch(function(error) {
            console.error('Error cargando orden:', error);
            if (typeof UIComponents !== 'undefined') {
                UIComponents.showAlert('Error de conexión', 'error');
            }
        });
}

// Función para llenar el modal con datos
function llenarModalConDatos(datos) {
    // Información básica
    $('#ordenNumeroDisplay').text('#' + datos.num_orden);
    $('#numOrden').val(datos.num_orden);
    $('#fechaIngreso').val(datos.fecha_ingreso);
    $('#vendedor').val(datos.vendedor);
    
    // Canal de venta
    if (datos.orden_ext) {
        $('#canalVenta').val('Tienda Online');
        $('#canalIcon i').removeClass('fa-store').addClass('fa-shopping-cart');
    } else {
        $('#canalVenta').val('Tienda Física');
        $('#canalIcon i').removeClass('fa-shopping-cart').addClass('fa-store');
    }
    
    // Información del cliente
    $('#rutCliente').val(datos.rut_cliente);
    $('#nombreCliente').val(datos.nombre);
    $('#telefonoCliente').val(datos.telefono);
    $('#instagramCliente').val(datos.instagram || '');
    
    // Información de entrega
    if (datos.detalles && datos.detalles.length > 0) {
        const primerDetalle = datos.detalles[0];
        $('#direccionEntrega').val(primerDetalle.direccion);
        $('#numeroEntrega').val(primerDetalle.numero);
        $('#dptoEntrega').val(primerDetalle.dpto);
        $('#comunaEntrega').val(primerDetalle.comuna);
    }
    
    // Llenar tabla de productos
    llenarTablaProductos(datos.detalles || []);
    
    // Llenar tabla de pagos
    llenarTablaPagos(datos.pagos || []);
    
    // Calcular totales
    calcularTotales(datos);
    
    // Configurar botón de WhatsApp
    configurarWhatsApp(datos.telefono);
    
    // Actualizar timestamp
    $('#ultimaActualizacion').text(new Date().toLocaleString());
}

// Función para llenar tabla de productos
function llenarTablaProductos(productos) {
    const tbody = $('#tablaProductosOrden tbody');
    tbody.empty();
    
    $('#productosCount').text(productos.length);
    
    productos.forEach(function(producto) {
        const estadoNombre = obtenerNombreEstado(producto.estadopedido);
        const estadoColor = obtenerColorEstado(producto.estadopedido);
        
        const fila = `
            <tr data-producto-id="${producto.id}">
                <td><span class="badge bg-secondary">${producto.id}</span></td>
                <td><strong class="small">${producto.modelo || 'N/A'}</strong></td>
                <td><small>${producto.tamano || 'N/A'}</small></td>
                <td><small>${producto.material || producto.tipotela || 'N/A'}</small></td>
                <td><span class="badge bg-light text-dark small">${producto.color || 'N/A'}</span></td>
                <td class="text-center">${producto.cantidad || 1}</td>
                <td class="text-end small">$${formatearNumero(producto.precio)}</td>
                <td><small>${producto.tapicero_nombre || 'Sin asignar'}</small></td>
                <td><span class="badge bg-${estadoColor} small">${estadoNombre}</span></td>
                <td><small class="text-muted">${producto.comentarios || 'Sin observaciones'}</small></td>
                <td>
                    <div class="btn-group btn-group-sm">
                        <button type="button" class="btn btn-outline-warning btn-sm btn-cambiar-estado-modal" 
                                data-id="${producto.id}" data-estado="${producto.estadopedido}">
                            <i class="fas fa-exchange-alt"></i>
                        </button>
                        <button type="button" class="btn btn-outline-success btn-sm btn-marcar-entregado-modal" 
                                data-id="${producto.id}" ${producto.estadopedido === '9' ? 'disabled' : ''}>
                            <i class="fas fa-check-circle"></i>
                        </button>
                    </div>
                </td>
            </tr>
        `;
        tbody.append(fila);
    });
}

// Función para llenar tabla de pagos
function llenarTablaPagos(pagos) {
    const tbody = $('#tablaPagosAsociados tbody');
    tbody.empty();
    
    $('#pagosCount').text(pagos.length);
    
    pagos.forEach(function(pago) {
        const fila = `
            <tr data-pago-id="${pago.id}">
                <td>${pago.id}</td>
                <td><small>${pago.fecha}</small></td>
                <td><small>${pago.rut || 'N/A'}</small></td>
                <td><small>${pago.nombre || 'N/A'}</small></td>
                <td><small>${pago.banco || 'N/A'}</small></td>
                <td class="text-end fw-bold small">$${formatearNumero(pago.monto)}</td>
                <td><small>${pago.metodo_pago || 'N/A'}</small></td>
                <td>
                    <button type="button" class="btn btn-outline-danger btn-sm btn-desasociar-pago" 
                            data-id="${pago.id}">
                        <i class="fas fa-unlink"></i> <span class="d-none d-md-inline">Desasociar</span>
                    </button>
                </td>
            </tr>
        `;
        tbody.append(fila);
    });
}

// Función para calcular totales
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
// Función para configurar WhatsApp
function configurarWhatsApp(telefono) {
    $('#btnWhatsApp').off('click').on('click', function() {
        if (telefono) {
            const mensaje = encodeURIComponent('Hola! Te escribimos de RespaldosChile');
            const url = `https://api.whatsapp.com/send/?phone=+56${telefono}&text=${mensaje}`;
            window.open(url, '_blank');
        }
    });
}

// Funciones auxiliares
function obtenerNombreEstado(estadoId) {
    const estados = {
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
    return estados[estadoId] || 'Desconocido';
}

function obtenerColorEstado(estadoId) {
    const colores = {
        '0': 'warning',
        '1': 'info',
        '2': 'warning',
        '3': 'primary',
        '4': 'primary',
        '5': 'info',
        '6': 'success',
        '7': 'success',
        '8': 'success',
        '9': 'success',
        '20': 'danger'
    };
    return colores[estadoId] || 'secondary';
}

function formatearNumero(numero) {
    if (!numero) return '0';
    const num = parseFloat(numero.toString().replace(/[$,.]/g, ''));
    return num.toLocaleString('es-CL');
}

// Event listeners del modal
$(document).ready(function() {
    
    // Cambiar estado de producto
    $(document).on('click', '.btn-cambiar-estado-modal', function() {
        const productoId = $(this).data('id');
        const estadoActual = $(this).data('estado');
        
        $('#productoIdEstado').val(productoId);
        $('#nuevoEstado').val('');
        $('#observacionEstado').val('');
        $('#modalCambiarEstado').modal('show');
    });
    
    // Confirmar cambio de estado
    $('#btnConfirmarCambioEstado').on('click', function() {
        const productoId = $('#productoIdEstado').val();
        const nuevoEstado = $('#nuevoEstado').val();
        const observacion = $('#observacionEstado').val();
        
        if (!nuevoEstado) {
            if (typeof UIComponents !== 'undefined') {
                UIComponents.showAlert('Debe seleccionar un estado', 'warning');
            }
            return;
        }
        
        if (typeof APIClient !== 'undefined') {
            APIClient.updateProductStatus(productoId, nuevoEstado, observacion)
                .then(function(response) {
                    if (response.status === 'success') {
                        $('#modalCambiarEstado').modal('hide');
                        if (typeof UIComponents !== 'undefined') {
                            UIComponents.showToast('Estado actualizado correctamente', 'success');
                        }
                        // Recargar datos del modal
                        const numOrden = $('#numOrden').val();
                        cargarDatosOrden(numOrden);
                    } else {
                        if (typeof UIComponents !== 'undefined') {
                            UIComponents.showAlert('Error al actualizar el estado', 'error');
                        }
                    }
                })
                .catch(function(error) {
                    console.error('Error:', error);
                    if (typeof UIComponents !== 'undefined') {
                        UIComponents.showAlert('Error de conexión', 'error');
                    }
                });
        }
    });
    
    // Marcar producto como entregado
    $(document).on('click', '.btn-marcar-entregado-modal', function() {
        const productoId = $(this).data('id');
        
        if (typeof UIComponents !== 'undefined') {
            UIComponents.showConfirm('¿Marcar este producto como entregado?')
                .then(function(result) {
                    if (result.isConfirmed && typeof APIClient !== 'undefined') {
                        APIClient.updateProductStatus(productoId, 9)
                            .then(function(response) {
                                if (response.status === 'success') {
                                    UIComponents.showToast('Producto marcado como entregado', 'success');
                                    // Recargar datos del modal
                                    const numOrden = $('#numOrden').val();
                                    cargarDatosOrden(numOrden);
                                } else {
                                    UIComponents.showAlert('Error al marcar como entregado', 'error');
                                }
                            })
                            .catch(function(error) {
                                console.error('Error:', error);
                                UIComponents.showAlert('Error de conexión', 'error');
                            });
                    }
                });
        }
    });
    
});
</script>

<!-- Estilos específicos del modal -->
<style>
/* Modal de tamaño optimizado */
#modalGestionOrden .modal-lg {
    max-width: 900px;
}

/* Elementos compactos */
#modalGestionOrden .table {
    font-size: 0.8rem;
}

#modalGestionOrden .table th {
    font-weight: 600;
    font-size: 0.75rem;
    text-transform: uppercase;
    letter-spacing: 0.025em;
    padding: 0.375rem 0.25rem;
}

#modalGestionOrden .table td {
    padding: 0.375rem 0.25rem;
    vertical-align: middle;
}

#modalGestionOrden .nav-tabs .nav-link {
    font-weight: 500;
    font-size: 0.8rem;
    padding: 0.5rem 0.75rem;
}

#modalGestionOrden .nav-tabs .nav-link.active {
    font-weight: 600;
}

/* Cards compactas */
#modalGestionOrden .card-header {
    padding: 0.5rem 0.75rem;
    background-color: #f8f9fa;
}

#modalGestionOrden .card-body {
    padding: 0.75rem;
}

/* Formularios compactos */
#modalGestionOrden .form-label {
    font-size: 0.75rem;
    font-weight: 600;
    color: #374151;
    margin-bottom: 0.125rem;
}

#modalGestionOrden .form-control-sm,
#modalGestionOrden .form-select-sm {
    font-size: 0.8rem;
    padding: 0.25rem 0.4rem;
}

#modalGestionOrden .input-group-sm > .input-group-text {
    padding: 0.25rem 0.4rem;
    font-size: 0.8rem;
}

/* Botones compactos */
#modalGestionOrden .btn-sm {
    padding: 0.2rem 0.5rem;
    font-size: 0.75rem;
}

#modalGestionOrden .btn-group-sm > .btn {
    padding: 0.15rem 0.4rem;
    font-size: 0.7rem;
}

/* Badges compactos */
#modalGestionOrden .badge {
    font-size: 0.7rem;
    padding: 0.25rem 0.4rem;
}

/* Espaciado reducido */
#modalGestionOrden .row.g-1 > * {
    padding: 0.125rem;
}

#modalGestionOrden .mb-2 {
    margin-bottom: 0.5rem !important;
}

#modalGestionOrden .mb-1 {
    margin-bottom: 0.25rem !important;
}

/* Headers y footers compactos */
#modalGestionOrden .modal-header {
    padding: 0.5rem 1rem;
}

#modalGestionOrden .modal-footer {
    padding: 0.5rem 1rem;
}

/* Responsive para móviles */
@media (max-width: 768px) {
    #modalGestionOrden .modal-dialog {
        margin: 0.5rem;
        max-width: none;
    }
    
    #modalGestionOrden .card-body {
        padding: 0.5rem;
    }
    
    #modalGestionOrden .table {
        font-size: 0.7rem;
    }
    
    #modalGestionOrden .nav-tabs .nav-link {
        padding: 0.375rem 0.5rem;
        font-size: 0.75rem;
    }
    
    #modalGestionOrden .btn-group-sm > .btn {
        padding: 0.125rem 0.25rem;
        font-size: 0.65rem;
    }
}

/* Scroll optimizado */
#modalGestionOrden .table-responsive {
    border-radius: 0.375rem;
}

#modalGestionOrden .table-responsive::-webkit-scrollbar {
    height: 6px;
}

#modalGestionOrden .table-responsive::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 3px;
}

#modalGestionOrden .table-responsive::-webkit-scrollbar-thumb {
    background: #c1c1c1;
    border-radius: 3px;
}

#modalGestionOrden .table-responsive::-webkit-scrollbar-thumb:hover {
    background: #a8a8a8;
}
</style>

<script>/**
 * ============================================================================
 * MODAL GESTIÓN ORDEN - JAVASCRIPT LIMPIO Y FUNCIONAL
 * ============================================================================
 * Versión depurada sin duplicaciones que FUNCIONA
 * @version 2.0 - Clean & Working
 * @author RespaldosChile Team
 * ============================================================================
 */

// Variables globales
let hasUnsavedChanges = false;
let originalOrderData = {};
let isFormInitialized = false;

/**
 * ============================================================================
 * FUNCIONES AUXILIARES
 * ============================================================================
 */

function limpiarValorMonetario(valor) {
    if (valor === null || valor === undefined || valor === '') return 0;
    const valorStr = String(valor);
    const valorLimpio = valorStr.replace(/[$.,\s]/g, '');
    const numero = parseFloat(valorLimpio);
    return isNaN(numero) ? 0 : numero;
}

function obtenerValorSeguro(selector, valorPorDefecto = '') {
    const $elemento = $(selector);
    if ($elemento.length === 0) return valorPorDefecto;
    const valor = $elemento.val();
    return (valor !== null && valor !== undefined) ? String(valor).trim() : valorPorDefecto;
}

function formatearNumero(numero) {
    if (numero === null || numero === undefined || numero === '' || isNaN(numero)) return '0';
    const num = typeof numero === 'number' ? numero : parseFloat(String(numero).replace(/[$,.]/g, ''));
    return isNaN(num) ? '0' : num.toLocaleString('es-CL');
}

/**
 * ============================================================================
 * FUNCIONES PRINCIPALES DEL MODAL
 * ============================================================================
 */

function abrirModalGestionOrden(numOrden) {
    console.log('🔄 Abriendo modal para orden:', numOrden);
    $('#modalLoading').removeClass('d-none');
    $('#modalContent').addClass('d-none');
    $('#modalGestionOrden').modal('show');
    cargarDatosOrden(numOrden);
}

function cargarDatosOrden(numOrden) {
    if (typeof APIClient === 'undefined') {
        console.error('❌ APIClient no está disponible');
        mostrarMensaje('Error: APIClient no disponible', 'error');
        return;
    }
    
    console.log('📡 Cargando datos para orden:', numOrden);
    
    APIClient.getOrderDetails(numOrden)
        .then(function(response) {
            console.log('📨 Respuesta recibida:', response);
            
            if (response && response.success && response.data) {
                llenarModalConDatos(response.data);
                $('#modalLoading').addClass('d-none');
                $('#modalContent').removeClass('d-none');
            } else {
                throw new Error(response?.message || 'Respuesta inválida del servidor');
            }
        })
        .catch(function(error) {
            console.error('❌ Error cargando orden:', error);
            mostrarMensaje('Error al cargar los datos: ' + error.message, 'error');
            $('#modalGestionOrden').modal('hide');
        });
}

function llenarModalConDatos(datos) {
    try {
        console.log('📋 Llenando modal con datos:', datos);
        
        if (!datos) {
            console.warn('⚠️ No se recibieron datos');
            return;
        }

        // Información básica
        actualizarCampo('#ordenNumeroDisplay', `#${datos.num_orden || 'N/A'}`, 'text');
        actualizarCampo('#numOrden', datos.num_orden);
        actualizarCampo('#fechaIngreso', datos.fecha_ingreso);
        actualizarCampo('#vendedor', datos.vendedor);

        // Información del cliente
        actualizarCampo('#rutCliente', datos.rut_cliente);
        actualizarCampo('#nombreCliente', datos.nombre);
        actualizarCampo('#telefonoCliente', datos.telefono);
        actualizarCampo('#instagramCliente', datos.instagram);

        // Canal de venta
        if (datos.orden_ext) {
            actualizarCampo('#canalVenta', 'Tienda Online');
            $('#canalIcon i').removeClass('fa-store').addClass('fa-shopping-cart');
        } else {
            actualizarCampo('#canalVenta', 'Tienda Física');
            $('#canalIcon i').removeClass('fa-shopping-cart').addClass('fa-store');
        }

        // Información de entrega
        if (datos.detalles && Array.isArray(datos.detalles) && datos.detalles.length > 0) {
            const primerDetalle = datos.detalles[0];
            actualizarCampo('#direccionEntrega', primerDetalle.direccion);
            actualizarCampo('#numeroEntrega', primerDetalle.numero);
            actualizarCampo('#dptoEntrega', primerDetalle.dpto);
            actualizarCampo('#comunaEntrega', primerDetalle.comuna);
        }

        // Llenar tablas
        llenarTablaProductos(datos.detalles || []);
        llenarTablaPagos(datos.pagos || []);

        // Calcular totales
        calcularTotales(datos);

        // Configurar WhatsApp
        configurarWhatsApp(datos.telefono);

        // Guardar datos originales
        guardarDatosOriginales(datos);

        // Actualizar timestamp
        $('#ultimaActualizacion').text(new Date().toLocaleString());

        console.log('✅ Modal llenado correctamente');

    } catch (error) {
        console.error('❌ Error llenando modal:', error);
        mostrarMensaje('Error al cargar los datos en el modal', 'error');
    }
}

function actualizarCampo(selector, valor, tipo = 'val') {
    const $elemento = $(selector);
    if ($elemento.length) {
        if (tipo === 'text') {
            $elemento.text(valor || '');
        } else {
            $elemento.val(valor || '');
        }
    }
}

function llenarTablaProductos(productos) {
    const tbody = $('#tablaProductosOrden tbody');
    tbody.empty();
    $('#productosCount').text(productos.length);
    
    productos.forEach(function(producto) {
        const estadoNombre = obtenerNombreEstado(producto.estadopedido);
        const estadoColor = obtenerColorEstado(producto.estadopedido);
        
        const fila = `
            <tr data-producto-id="${producto.id}">
                <td><span class="badge bg-secondary">${producto.id}</span></td>
                <td><strong class="small">${producto.modelo || 'N/A'}</strong></td>
                <td><small>${producto.tamano || 'N/A'}</small></td>
                <td><small>${producto.material || producto.tipotela || 'N/A'}</small></td>
                <td><span class="badge bg-light text-dark small">${producto.color || 'N/A'}</span></td>
                <td class="text-center">${producto.cantidad || 1}</td>
                <td class="text-end small">$${formatearNumero(producto.precio)}</td>
                <td><small>${producto.tapicero_nombre || 'Sin asignar'}</small></td>
                <td><span class="badge bg-${estadoColor} small">${estadoNombre}</span></td>
                <td><small class="text-muted">${producto.comentarios || 'Sin observaciones'}</small></td>
                <td>
                    <div class="btn-group btn-group-sm">
                        <button type="button" class="btn btn-outline-warning btn-sm btn-cambiar-estado-modal" 
                                data-id="${producto.id}" data-estado="${producto.estadopedido}">
                            <i class="fas fa-exchange-alt"></i>
                        </button>
                        <button type="button" class="btn btn-outline-success btn-sm btn-marcar-entregado-modal" 
                                data-id="${producto.id}" ${producto.estadopedido === '9' ? 'disabled' : ''}>
                            <i class="fas fa-check-circle"></i>
                        </button>
                    </div>
                </td>
            </tr>
        `;
        tbody.append(fila);
    });
}

function llenarTablaPagos(pagos) {
    const tbody = $('#tablaPagosAsociados tbody');
    tbody.empty();
    $('#pagosCount').text(pagos.length);
    
    pagos.forEach(function(pago) {
        const fila = `
            <tr data-pago-id="${pago.id}">
                <td>${pago.id}</td>
                <td><small>${pago.fecha}</small></td>
                <td><small>${pago.rut || 'N/A'}</small></td>
                <td><small>${pago.nombre || 'N/A'}</small></td>
                <td><small>${pago.banco || 'N/A'}</small></td>
                <td class="text-end fw-bold small">$${formatearNumero(pago.monto)}</td>
                <td><small>${pago.metodo_pago || 'N/A'}</small></td>
                <td>
                    <button type="button" class="btn btn-outline-danger btn-sm btn-desasociar-pago" 
                            data-id="${pago.id}">
                        <i class="fas fa-unlink"></i> <span class="d-none d-md-inline">Desasociar</span>
                    </button>
                </td>
            </tr>
        `;
        tbody.append(fila);
    });
}

function calcularTotales(datos) {
    let totalProductos = 0;
    
    if (datos && datos.detalles && Array.isArray(datos.detalles)) {
        totalProductos = datos.detalles.reduce(function(sum, detalle) {
            if (!detalle) return sum;
            const precio = limpiarValorMonetario(detalle.precio);
            return sum + precio;
        }, 0);
    }

    const costoDespacho = limpiarValorMonetario(datos?.despacho);
    const totalPagado = limpiarValorMonetario(datos?.total_pagado);
    const totalGeneral = totalProductos + costoDespacho;
    const saldoPendiente = totalGeneral - totalPagado;

    // Actualizar campos
    actualizarCampo('#totalProductos', formatearNumero(totalProductos));
    actualizarCampo('#costoDespacho', formatearNumero(costoDespacho));
    actualizarCampo('#totalGeneral', formatearNumero(totalGeneral));
    actualizarCampo('#totalPagado', formatearNumero(totalPagado));
    actualizarCampo('#saldoPendiente', formatearNumero(saldoPendiente));

    // Cambiar color del saldo
    const $saldoInput = $('#saldoPendiente');
    const $saldoIcon = $('#saldoIcon');
    
    if (saldoPendiente > 0) {
        $saldoInput.removeClass('text-success').addClass('text-danger');
        $saldoIcon.removeClass('text-success').addClass('text-danger');
    } else {
        $saldoInput.removeClass('text-danger').addClass('text-success');
        $saldoIcon.removeClass('text-danger').addClass('text-success');
    }
}

/**
 * ============================================================================
 * FUNCIONES DE GUARDADO
 * ============================================================================
 */

function guardarCambiosOrden() {
    console.log('💾 Iniciando guardado de cambios...');
    
    try {
        const numOrden = obtenerValorSeguro('#numOrden');
        if (!numOrden) {
            throw new Error('No se puede identificar la orden');
        }

        if (!validarFormulariosOrden()) {
            console.log('❌ Validación fallida');
            return;
        }

        const datosOrden = recopilarDatosOrden();
        console.log('📊 Datos a guardar:', datosOrden);

        if (Object.keys(datosOrden).length === 0) {
            throw new Error('No hay datos para guardar');
        }

        // Mostrar loading
        mostrarLoading(true, 'Guardando cambios...');
        $('#btnGuardarCambios').prop('disabled', true);

        if (typeof APIClient === 'undefined') {
            throw new Error('APIClient no disponible');
        }

        APIClient.updateOrder(datosOrden)
            .then(response => {
                console.log('✅ Respuesta:', response);
                
                if (response && (response.success || response.status === 'success')) {
                    mostrarMensaje('Orden actualizada correctamente', 'success');
                    restablecerEstadoGuardar();
                    
                    // Actualizar datos originales
                    originalOrderData = {...originalOrderData, ...datosOrden};
                    
                    // Actualizar tabla principal si existe
                    if (typeof PedidosIngresados !== 'undefined' && PedidosIngresados.refreshTable) {
                        PedidosIngresados.refreshTable();
                    }
                    
                    // Actualizar timestamp
                    $('#ultimaActualizacion').text(new Date().toLocaleString());
                    
                } else {
                    throw new Error(response?.message || 'Error al actualizar la orden');
                }
            })
            .catch(error => {
                console.error('❌ Error:', error);
                mostrarMensaje('Error: ' + error.message, 'error');
            })
            .finally(() => {
                mostrarLoading(false);
                $('#btnGuardarCambios').prop('disabled', false);
            });

    } catch (error) {
        console.error('❌ Error en guardarCambiosOrden:', error);
        mostrarMensaje('Error: ' + error.message, 'error');
        mostrarLoading(false);
        $('#btnGuardarCambios').prop('disabled', false);
    }
}

function recopilarDatosOrden() {
    const datosOrden = {
        num_orden: obtenerValorSeguro('#numOrden'),
        vendedor: obtenerValorSeguro('#vendedor'),
        rut_cliente: obtenerValorSeguro('#rutCliente'),
        nombre_cliente: obtenerValorSeguro('#nombreCliente'),
        telefono: obtenerValorSeguro('#telefonoCliente'),
        instagram: obtenerValorSeguro('#instagramCliente'),
        direccion: obtenerValorSeguro('#direccionEntrega'),
        numero: obtenerValorSeguro('#numeroEntrega'),
        dpto: obtenerValorSeguro('#dptoEntrega'),
        comuna: obtenerValorSeguro('#comunaEntrega'),
        despacho: limpiarValorMonetario(obtenerValorSeguro('#costoDespacho'))
    };
    
    // Limpiar campos vacíos
    Object.keys(datosOrden).forEach(key => {
        if (datosOrden[key] === '' || datosOrden[key] === null || datosOrden[key] === undefined) {
            delete datosOrden[key];
        }
    });
    
    return datosOrden;
}

function validarFormulariosOrden() {
    const numOrden = obtenerValorSeguro('#numOrden');
    const nombreCliente = obtenerValorSeguro('#nombreCliente');
    
    if (!numOrden) {
        mostrarMensaje('Número de orden es requerido', 'warning');
        return false;
    }
    
    if (!nombreCliente || nombreCliente.length < 2) {
        mostrarMensaje('Nombre del cliente es requerido (mínimo 2 caracteres)', 'warning');
        $('#nombreCliente').focus();
        return false;
    }
    
    return true;
}

function detectarCambios() {
    if (!isFormInitialized) return;
    
    hasUnsavedChanges = true;
    const $btnGuardar = $('#btnGuardarCambios');
    
    if ($btnGuardar.length && !$btnGuardar.hasClass('btn-warning')) {
        $btnGuardar.removeClass('btn-primary').addClass('btn-warning');
        $btnGuardar.find('i').removeClass('fa-save').addClass('fa-exclamation-triangle');
        $btnGuardar.html('<i class="fas fa-exclamation-triangle me-1"></i>Guardar Cambios*');
    }
}

function restablecerEstadoGuardar() {
    hasUnsavedChanges = false;
    const $btnGuardar = $('#btnGuardarCambios');
    
    if ($btnGuardar.length) {
        $btnGuardar.removeClass('btn-warning').addClass('btn-primary');
        $btnGuardar.find('i').removeClass('fa-exclamation-triangle').addClass('fa-save');
        $btnGuardar.html('<i class="fas fa-save me-1"></i>Guardar');
    }
}

function guardarDatosOriginales(datos) {
    originalOrderData = {
        num_orden: datos.num_orden,
        vendedor: datos.vendedor,
        rut_cliente: datos.rut_cliente,
        nombre_cliente: datos.nombre,
        telefono: datos.telefono,
        instagram: datos.instagram,
        despacho: datos.despacho,
        detalles: datos.detalles,
        pagos: datos.pagos
    };
    
    if (datos.detalles && datos.detalles.length > 0) {
        const primerDetalle = datos.detalles[0];
        originalOrderData.direccion = primerDetalle.direccion;
        originalOrderData.numero = primerDetalle.numero;
        originalOrderData.dpto = primerDetalle.dpto;
        originalOrderData.comuna = primerDetalle.comuna;
    }
    
    // Marcar como inicializado después de un delay
    setTimeout(() => {
        isFormInitialized = true;
        console.log('✅ Formulario inicializado');
    }, 500);
}

/**
 * ============================================================================
 * FUNCIONES AUXILIARES DEL MODAL
 * ============================================================================
 */

function configurarWhatsApp(telefono) {
    $('#btnWhatsApp').off('click').on('click', function() {
        if (telefono) {
            const mensaje = encodeURIComponent('Hola! Te escribimos de RespaldosChile');
            const url = `https://api.whatsapp.com/send/?phone=+56${telefono}&text=${mensaje}`;
            window.open(url, '_blank');
        }
    });
}

function obtenerNombreEstado(estadoId) {
    const estados = {
        '0': 'No Aceptado', '1': 'Aceptado', '2': 'Por Fabricar',
        '3': 'Tela Cortada', '4': 'Armando Esqueleto', '5': 'Fabricando',
        '6': 'Producto Listo', '7': 'En Despacho', '8': 'En Camión',
        '9': 'Entregado', '20': 'Reemitido'
    };
    return estados[estadoId] || 'Desconocido';
}

function obtenerColorEstado(estadoId) {
    const colores = {
        '0': 'warning', '1': 'info', '2': 'warning', '3': 'primary',
        '4': 'primary', '5': 'info', '6': 'success', '7': 'success',
        '8': 'success', '9': 'success', '20': 'danger'
    };
    return colores[estadoId] || 'secondary';
}

function mostrarLoading(mostrar, mensaje = 'Cargando...') {
    if (typeof UIComponents !== 'undefined' && UIComponents.showLoading) {
        UIComponents.showLoading(mostrar, mensaje);
    } else if (mostrar) {
        console.log(`⏳ ${mensaje}`);
    }
}

function mostrarMensaje(mensaje, tipo = 'info') {
    if (typeof UIComponents !== 'undefined') {
        if (tipo === 'success' && UIComponents.showToast) {
            UIComponents.showToast(mensaje, 'success');
        } else if (UIComponents.showAlert) {
            UIComponents.showAlert(mensaje, tipo);
        } else {
            alert(mensaje);
        }
    } else {
        console.log(`${tipo.toUpperCase()}: ${mensaje}`);
        alert(mensaje);
    }
}

/**
 * ============================================================================
 * EVENT LISTENERS
 * ============================================================================
 */

$(document).ready(function() {
    console.log('🚀 Inicializando modal gestión orden...');
    
    // Event listener principal para guardar cambios
    $(document).off('click', '#btnGuardarCambios').on('click', '#btnGuardarCambios', function(e) {
        e.preventDefault();
        console.log('🔄 Botón Guardar Cambios presionado');
        guardarCambiosOrden();
    });
    
    // Event listener para marcar orden completa
    $(document).off('click', '#btnMarcarOrdenCompleta').on('click', '#btnMarcarOrdenCompleta', function(e) {
        e.preventDefault();
        console.log('✅ Botón Marcar Orden Completa presionado');
        // Implementar si es necesario
    });
    
    // Detectar cambios en formularios
    const formSelectors = '#formInfoOrden, #formInfoCliente, #formInfoEntrega';
    $(document).off('input change', `${formSelectors} input, ${formSelectors} select, ${formSelectors} textarea`)
              .on('input change', `${formSelectors} input, ${formSelectors} select, ${formSelectors} textarea`, function() {
        if (isFormInitialized) {
            console.log('📝 Cambio detectado');
            detectarCambios();
        }
    });
    
    // Detectar cambios en costo de despacho
    $(document).off('input', '#costoDespacho').on('input', '#costoDespacho', function() {
        if (isFormInitialized) {
            detectarCambios();
            // Recalcular totales si hay datos originales
            if (originalOrderData && originalOrderData.detalles) {
                const datosActualizados = {...originalOrderData};
                datosActualizados.despacho = $(this).val();
                calcularTotales(datosActualizados);
            }
        }
    });
    
    // Advertir antes de cerrar con cambios
    $('#modalGestionOrden').off('hide.bs.modal').on('hide.bs.modal', function(e) {
        if (hasUnsavedChanges) {
            if (!confirm('⚠️ Tienes cambios sin guardar.\n\n¿Estás seguro de cerrar?')) {
                e.preventDefault();
                return false;
            }
        }
        // Limpiar estado
        hasUnsavedChanges = false;
        originalOrderData = {};
        isFormInitialized = false;
        restablecerEstadoGuardar();
    });
    
    // Cambiar estado de producto
    $(document).off('click', '.btn-cambiar-estado-modal').on('click', '.btn-cambiar-estado-modal', function() {
        const productoId = $(this).data('id');
        $('#productoIdEstado').val(productoId);
        $('#nuevoEstado').val('');
        $('#observacionEstado').val('');
        $('#modalCambiarEstado').modal('show');
    });
    
    // Confirmar cambio de estado
    $(document).off('click', '#btnConfirmarCambioEstado').on('click', '#btnConfirmarCambioEstado', function() {
        const productoId = $('#productoIdEstado').val();
        const nuevoEstado = $('#nuevoEstado').val();
        const observacion = $('#observacionEstado').val();
        
        if (!nuevoEstado) {
            mostrarMensaje('Debe seleccionar un estado', 'warning');
            return;
        }
        
        if (typeof APIClient !== 'undefined') {
            APIClient.updateProductStatus(productoId, nuevoEstado, observacion)
                .then(function(response) {
                    if (response.status === 'success') {
                        $('#modalCambiarEstado').modal('hide');
                        mostrarMensaje('Estado actualizado correctamente', 'success');
                        // Recargar datos del modal
                        const numOrden = $('#numOrden').val();
                        if (numOrden) cargarDatosOrden(numOrden);
                    } else {
                        mostrarMensaje('Error al actualizar el estado', 'error');
                    }
                })
                .catch(function(error) {
                    console.error('Error:', error);
                    mostrarMensaje('Error de conexión', 'error');
                });
        }
    });
    
    // Marcar producto como entregado
    $(document).off('click', '.btn-marcar-entregado-modal').on('click', '.btn-marcar-entregado-modal', function() {
        const productoId = $(this).data('id');
        
        if (confirm('¿Marcar este producto como entregado?')) {
            if (typeof APIClient !== 'undefined') {
                APIClient.updateProductStatus(productoId, 9)
                    .then(function(response) {
                        if (response.status === 'success') {
                            mostrarMensaje('Producto marcado como entregado', 'success');
                            const numOrden = $('#numOrden').val();
                            if (numOrden) cargarDatosOrden(numOrden);
                        } else {
                            mostrarMensaje('Error al marcar como entregado', 'error');
                        }
                    })
                    .catch(function(error) {
                        console.error('Error:', error);
                        mostrarMensaje('Error de conexión', 'error');
                    });
            }
        }
    });
    
    // Formatear campos en tiempo real
    $(document).off('input', '#rutCliente').on('input', '#rutCliente', function() {
        let valor = $(this).val().replace(/[^0-9kK]/g, '');
        if (valor.length > 1) {
            valor = valor.replace(/^(\d{1,2})(\d{3})(\d{3})([kK\d]{1})$/, '$1.$2.$3-$4');
        }
        $(this).val(valor);
    });
    
    $(document).off('input', '#telefonoCliente').on('input', '#telefonoCliente', function() {
        let valor = $(this).val().replace(/[^0-9]/g, '');
        $(this).val(valor);
    });
    
    $(document).off('input', '#costoDespacho').on('input', '#costoDespacho', function() {
        let valor = $(this).val().replace(/[^0-9]/g, '');
        if (valor) {
            const numero = parseInt(valor);
            if (!isNaN(numero)) {
                $(this).val(numero.toLocaleString('es-CL'));
            }
        }
    });
    
    console.log('✅ Modal gestión orden inicializado correctamente');
});

/**
 * ============================================================================
 * FUNCIONES GLOBALES PARA USO EXTERNO
 * ============================================================================
 */

// Hacer funciones disponibles globalmente
window.abrirModalGestionOrden = abrirModalGestionOrden;
window.cargarDatosOrden = cargarDatosOrden;
window.guardarCambiosOrden = guardarCambiosOrden;

// Función de test para debugging
window.testModalGestion = function() {
    console.group('🧪 TEST - Modal Gestión');
    console.log('hasUnsavedChanges:', hasUnsavedChanges);
    console.log('isFormInitialized:', isFormInitialized);
    console.log('originalOrderData:', originalOrderData);
    console.log('APIClient disponible:', typeof APIClient !== 'undefined');
    console.log('Botón guardar existe:', $('#btnGuardarCambios').length > 0);
    console.groupEnd();
};

console.log('✅ Sistema de modal gestión orden cargado y listo');
console.log('🔧 Para testing ejecuta: testModalGestion()')



</script>
