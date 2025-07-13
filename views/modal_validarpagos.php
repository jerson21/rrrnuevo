<style type="text/css">
    #customTableList {

        border-collapse: collapse;
        font-family: "Segoe UI", Arial, sans-serif;
        font-size: 0.75rem;
    }

    #customTableList th,
    #customTableList td {
        border: 1px solid #dee2e6;
        /* bordes para cada celda */
        padding: .5rem;
        /* espaciado dentro de cada celda */
        text-align: left;
        /* Alineación del texto a la izquierda */
    }

    #customTableList thead th {
        background-color: #f2f2f2;
        /* Color de fondo para los encabezados */
        position: sticky;
        /* Para que el encabezado sea fijo en el scroll */
        top: 0;
        /* Posición fija en la parte superior */
    }

    #customTableList tbody tr:hover {
        background-color: #e9ecef;
        /* Color de fondo al pasar el mouse */
    }

    /* Estilos para zebra striping */
    #customTableList tbody tr:nth-of-type(odd) {
        background-color: #f8f9fa;
        /* Filas de color claro */
    }

    #customTableList tbody tr:nth-of-type(even) {
        background-color: #e9ecef;
        /* Filas de color oscuro */
    }
</style>


<!--Modal para CRUD EDITAR ORDEN-->


<div class="modal fade" id="modalEditarOrden" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
        <div class="modal-content microsoft-style">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Validacion de Pago</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form id="editarpedido">
                <div class="modal-body">
                    <div id="header_modal" style="background-color: #F2F2F2; padding: 15px; border-radius: 5px; font-size: 14px;">
                        <div class="row gy-2">

                            <div class="col-lg-1" name="ide_webs" id="ide_webs" style="display:none;">
                                <label for="ide_web" class="col-form-label small-text">CodWeb:</label>
                                <input type="text" class="form-control form-control-sm small-input small-text" style="background-color:#F1FFD5;" id="ide_web" name="ide_web" disabled>
                            </div>
                            <div class="col-lg-1">
                                <label for="ide" class="col-form-label small-text">Orden:</label>
                                <input type="text" class="form-control form-control-sm small-input small-text" id="n_orden" name="n_orden" disabled>
                            </div>
                            <div class="col-lg-2">
                                <label for="ide" class="col-form-label small-text">Fecha Ingreso:</label>
                                <input type="text" class="form-control form-control-sm small-input small-text" id="fecha_ingresob" name="fecha_ingresob" disabled>
                            </div>
                            <div class="col-lg-2">
                                <label for="ide" class="col-form-label small-text">Vendedor:</label>
                                <input type="text" class="form-control form-control-sm small-input small-text" id="vendedorb" name="vendedorb" disabled>
                            </div>
                            <div class="col-lg-3">
                                <label for="ide" class="col-form-label small-text">Estado de la orden:</label>
                                <input type="text" class="form-control form-control-sm small-input small-text" id="estadopedido" name="estadopedido" disabled>
                            </div>

                            <div class="col-lg-2" style="background-color: #FFE4E4; border-radius: 5px;padding: 5px; text-align: center;">
                                <label for="ide" class="col-form-label small-text">Pedido Reclamado</label>

                                <button type="button" class="btn btn-primary btn-sm" id="reclamo" onclick="cerrarcaso()">Agregar Solucion</button>


                            </div>


                        </div>



                        <div class="row gy-4">
                            <div class="col-lg-3">
                                <label for="rut" class="col-form-label">Rut:</label>
                                <input type="text" class="form-control form-control-sm" id="rutb" name="rutb" disabled>
                            </div>
                            <div class="col-lg-3">
                                <label for="nombre" class="col-form-label">Nombre:</label>
                                <input type="text" class="form-control form-control-sm" id="nombreb" name="nombreb">
                            </div>
                            <div class="col-lg-2">
                                <label for="lugar_venta" class="col-form-label">Lugar Venta:</label>
                                <input type="text" class="form-control form-control-sm" id="lugar_venta" name="lugar_venta">
                            </div>
                            <style type="text/css">
                                .telefono-container {
                                    position: relative;
                                }

                                .telefono-container img {
                                    position: absolute;
                                    top: 50%;
                                    transform: translateY(-50%);
                                    left: calc(100% + 2px);
                                }
                            </style>
                            <div class="col-lg-2">
                                <label for="telefono" class="col-form-label">Telefono:</label>
                                <div class="telefono-container">
                                    <input type="text" class="form-control form-control-sm" id="telefono" name="telefono">
                                    <a href="#" id="whatsapp-link" target="_blank">
                                        <img src="img/whatsapp.png" alt="WhatsApp" width="40" id="whatsapp" data-toggle="tooltip" data-placement="right" title="Puedes hablarle al whatsapp">
                                    </a>
                                </div>
                            </div>

                        </div>







                        <div class="row gy-4">
                            <div class="col-lg-2">
                                <label for="precio" class="col-form-label">Total Productos:</label>
                                <input type="text" class="form-control form-control-sm" id="total_productos" name="total_productos">
                            </div>

                            <div class="col-lg-2">
                                <label for="precio" class="col-form-label">Despacho:</label>
                                <input type="text" class="form-control form-control-sm" id="despacho_valor" name="despacho_valor">
                            </div>
                            <div class="col-lg-2">
                                <label for="precio" class="col-form-label">Total A pagar:</label>
                                <input type="text" class="form-control form-control-sm" id="total_precio" name="total_precio">
                            </div>


                            <div class="col-lg-2" id="totalpagadodiv">

                                <label for="totalpagado" class="col-form-label">Total Pagado:</label>
                                <input type="text" class="form-control form-control-sm" id="totalpagado" name="totalpagado" disabled>
                            </div>
                            <div id="loadingIndicator" class="col-lg-4" style="display: none; padding: 20px;text-align: center;">
                                <img src="img/loading.gif" width="20" alt="Cargando...">
                            </div>

                            <div class="col-lg-3" id="calculoporpagar" style="display: none;">

                                <label for="porpagar" class="col-form-label">Por Pagar:</label>
                                <input type="text" class="form-control form-control-sm" id="porpagar" name="porpagar" disabled>
                            </div>
                            <div class="col-lg-3" id="">

                                <label for="mododepago" class="col-form-label">Forma de Pago:</label>
                                <input type="text" class="form-control form-control-sm" id="mododepago" name="mododepago" disabled>
                            </div>




                        </div>

                        <div style="margin-top: 15px; ">
                            <div class="" style="background-color:white;  padding: 10px; border-radius:5px; overflow-x: auto;">
                                <h5>Detalle de la Orden</h5>
                                <table class="table table-bordered table-hover table-striped table-sm" id="customTableList">

                                    <thead>
                                        <tr>
                                            <th scope="col">ID Producto</th>
                                            <th scope="col">Producto</th>
                                            <th scope="col">Cant</th>
                                            <th scope="col">Direccion de entrega</th>
                                            <th scope="col">Precio</th>
                                            <th scope="col">Referencia Pago</th>
                                            <th scope="col">Fecha entrega</th>
                                            <th scope="col">Estado</th>

                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!-- Filas de Ejemplo -->

                                    </tbody>
                                </table>
                            </div>
                        </div>


                        <div style="margin-top: 15px; ">
                            <!-- Detalle de pagos TableList -->
                            <div style="background-color:white; padding: 10px; border-radius:5px; ">
                                <div class="container">

                                    <!-- Botones para elegir la acción -->
                                    <div style=" display: flex; justify-content: flex-start">
                                        <button type="button" id="btnBuscarPago" class="btn btn-primary btn-sm" style="margin-right:2px;" onclick="mostrarFormulario('buscar')">Buscar Pago</button>
                                        <button type="button" id="btnAnadirPago" class="btn btn-success btn-sm" style="margin-right:2px;" onclick="mostrarFormulario('anadir')">Añadir Pago</button>
                                       <?php if (isset($_SESSION["privilegios"]) && $_SESSION["privilegios"] >= 21) { ?> <button type="button" id="btnCartola" class="btn btn-info btn-sm" alt="Robot para buscar Cartola" style="margin-left: auto;" onclick="solicitarCartola()">Solicitar Cartola</button>
                                       <?php } ?> <button type="button" id="btnCartola" class="btn btn-info btn-sm" alt="Robot para buscar Cartola" style="margin-left: auto;" onclick="solicitarPorMes()">Solicitar Por Mes</button>
                                    </div>
                                    <!-- Contenedor para los formularios -->
                                    <div id="contenedorFormularios">
                                        <!-- Aquí se insertarán los formularios dinámicamente -->
                                    </div>

                                    <script>
                                        function mostrarFormulario(tipo) {
                                            var contenedor = document.getElementById('contenedorFormularios');
                                            contenedor.innerHTML = ''; // Limpiar el contenedor

                                            if (tipo === 'buscar') {
                                                // Insertar formulario para buscar pago
                                                contenedor.innerHTML = `<div id="divBuscarPagos" style="background-color:white; padding: 10px; border-radius:5px;">
                    <h5>Buscar Pagos</h5>
<form id="searchForm">
    <div class="col-lg-6" style="display: flex; align-items: end; gap: 10px;">
        <div class="form-group" style="flex-grow: 1;">
            <label for="selectorCriterio">Buscar por:</label>
            <select class="form-control" id="selectorCriterio">
                <option>Seleccionar</option>
                <option value="rut">RUT</option>
                <option value="rutTercero">RUT de Tercero</option>
                <option value="numeroTransaccion">Número de Transacción</option>
                <option value="nombreTercero">Nombre Tercero</r
            </select>
        </div>

        <div class="form-group" style="flex-grow: 1;">
            <label for="inputValor">Búsqueda:</label>
            <input type="text" class="form-control" name="inputValor" id="inputValor">
        </div>
        <div class="form-group" style="flex-grow: 1;">
        <button type="button" class="btn btn-primary" id="buscarBtn" onclick="buscarPagos()">Buscar</button>
        </div>
        <div class="form-group" style="flex-grow: 1;">
        <img width="90" src="../assets/images/robot.gif">
            </div>
       
       
    </div>
</form>
                </div>

                </div>
                <div style="background-color:white; padding: 10px; border-radius:5px; margin-top: 20px; overflow-x: auto;">
                    <h5>Resultados Cartola Bancaria</h5>
                    <table class="table table-bordered table-hover table-striped table-sm" id="bankStatementTable" >
                        <thead>
                            <tr>
                                <th scope="col">Id</th>
                                <th scope="col">Fecha</th>
                                <th scope="col">Rut</th>
                                <th scope="col">Nombre</th>
                                <th scope="col">Banco</th>
                                <th scope="col">Cuenta Origen</th>
                                <th scope="col">Monto</th>
                                <th scope="col">Detalle</th>
                                <th scope="col">Accion</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Se llenará dinámicamente -->
                        </tbody>
                    </table>
                </div>`;
                                            } else if (tipo === 'anadir') {
                                                // Insertar formulario para añadir pago con íconos para seleccionar el tipo de pago
                                                contenedor.innerHTML = `
                                                    <div style="background-color:white; padding: 10px; border-radius:5px;">
                                                        <h5>Añadir Pago</h5>
                                                        <div id="paymentOptions" class="form-group">
                                                            <label>Seleccionar Tipo de Pago:</label><br>
                                                            <img src="../assets/images/transbank.png" id="transbankOption" style="cursor:pointer;" width="80" title="Pago Transbank">
                                                            <img src="https://cdn-icons-png.flaticon.com/512/2916/2916115.png" id="cashOption" style="cursor:pointer;" width="50" title="Efectivo">
                                                        </div>
                                                        <div id="paymentFields"></div>
                                                        <button type="button" id="addPaymentButton" class="btn btn-primary">Ingresar</button>
                                                    </div>
                                                `;
                                                setupPaymentTypeSelection();
                                            }
                                        }

                                        function setupPaymentTypeSelection() {
                                            document.getElementById('transbankOption').addEventListener('click', function() {
                                                document.getElementById('paymentFields').innerHTML = `
        <div class="row">
        <div class="col-md-4 form-group">
                <label for="tipoPago">Tipo de Pago:</label>
                <select class="form-control" id="tipoPago">
                <option>Seleccionar</option>
                    <option value="credito">Crédito</option>
                    <option value="debito">Débito</option>
                </select>
            </div>
    <div class="col-md-4 form-group">
                <label for="identificacion">Identificación:</label>
                <input type="text" class="form-control" id="identificacion">
            </div>
            <div class="col-md-4 form-group">
                <label for="monto">Monto:</label>
                <input type="number" class="form-control" id="monto">
            </div>`;
                                            });

                                            document.getElementById('cashOption').addEventListener('click', function() {
                                                document.getElementById('paymentFields').innerHTML = `
            <div class="form-group">
                <label for="montoEfectivo">Monto:</label>
                <input type="number" class="form-control" id="montoEfectivo">
            </div>
        `;
                                            });

                                            // Aquí puedes agregar el código para manejar el clic en el botón "Añadir Pago", similar al ejemplo anterior.
                                            document.getElementById('addPaymentButton').addEventListener('click', function() {
                                                // Recoger los datos del formulario
                                                var tipoPago = document.getElementById('tipoPago') ? document.getElementById('tipoPago').value : 'efectivo';
                                                var identificacion = document.getElementById('identificacion') ? document.getElementById('identificacion').value : '';
                                                var monto;

                                                // Determinar si el monto viene de un pago Transbank o en efectivo
                                                if (tipoPago === 'efectivo') {
                                                    monto = document.getElementById('montoEfectivo') ? document.getElementById('montoEfectivo').value : 0;
                                                } else {
                                                    monto = document.getElementById('monto') ? document.getElementById('monto').value : 0;
                                                }

                                                var idOrden = $('#n_orden').val();



                                                // Crear el objeto de datos para enviar
                                                const params = new URLSearchParams({
                                                    tipoPago: tipoPago, // Agregado para enviar el tipo de pago
                                                    identificacion: identificacion, // El numero identificador de transbank o getnet.
                                                    monto: monto,
                                                    num_orden: idOrden
                                                });

                                                // Configurar la solicitud Fetch API para enviar los datos mediante GET en la URL
                                                fetch(`../api/anadirpago.php?${params.toString()}`, {
                                                        method: 'GET',
                                                    })
                                                    .then(response => {
                                                        if (response.ok) {

                                                            elemento = document.getElementById('montoEfectivo');
                                                            if (elemento) {
                                                                elemento.value = '';
                                                            }

                                                            actualizarTotal(idOrden);
                                                            return response.json(); // o manejar la respuesta como sea apropiado
                                                        }
                                                        throw new Error('Algo salió mal en la petición AJAX.');
                                                    })
                                                    .then(data => {
                                                        console.log('Success:', data);
                                                        cargarPagosAsociados(idOrden);
                                                        elemento = document.getElementById('tipoPago');
                                                        if (elemento) {
                                                            elemento.value = '';
                                                        }
                                                        elemento = document.getElementById('identificacion');
                                                        if (elemento) {
                                                            elemento.value = '';
                                                        }
                                                        elemento = document.getElementById('monto');
                                                        if (elemento) {
                                                            elemento.value = '';
                                                        }
                                                        


                                                        // Aquí puedes manejar la respuesta del servidor, por ejemplo, mostrar un mensaje de éxito
                                                    })
                                                    .catch((error) => {
                                                        console.error('Error:', error);
                                                        // Manejar el error, por ejemplo, mostrar un mensaje de error
                                                    });
                                            });




                                        }

                                        // Obtener la URL actual
                                        const url = new URL(window.location.href);

                                        // Obtener los parámetros de la URL
                                        const paramse = new URLSearchParams(url.search);

                                        // Obtener el valor de la variable "ruta"
                                        const ruta_cargada = paramse.get('ruta');

                                        function actualizarTotal(id) {
                                            console.log("Actualizando el total");
                                            // Realizar una consulta a la base de datos utilizando el ID
                                            $.ajax({
                                                url: "../api/getpedido.php?method=num_orden", // Asegúrate de que la URL es correcta
                                                type: "POST",
                                                data: {
                                                    method: "num_orden",
                                                    id: id,
                                                    ruta: ruta_cargada
                                                },
                                                dataType: "json",
                                                success: function(resultado) {
                                                    var pedidos = resultado.pedidos;
                                                    var firstKey = Object.keys(pedidos)[0];
                                                    var response = pedidos[firstKey];


                                                    $("#totalpagado").val(response.total_pagado);

                                                    // Compara el total pagado con el total precio
                                                    var totalPagado = parseFloat(response.total_pagado);
                                                    var totalPrecio = parseFloat(resultado.totalPrecioTodosLosPedidos);
                                                    console.log(response.total_pagado);
                                                    console.log(resultado.totalPrecioTodosLosPedidos);

                                                    if (totalPagado === totalPrecio) {
                                                        // Utiliza SweetAlert para mostrar el mensaje de éxito
                                                        Swal.fire({
                                                            icon: 'success',
                                                            title: 'Total pagado',
                                                            text: 'El total del pedido ya está pagado.',
                                                            confirmButtonColor: '#3085d6',
                                                            confirmButtonText: 'Ok'
                                                        });
                                                    } else {
                                                        Swal.fire({
                                                            icon: 'warning',
                                                            title: 'Pago no corresponde',
                                                            text: 'No coincide el precio con lo pagado.',
                                                            confirmButtonColor: '#3085d6',
                                                            confirmButtonText: 'Entendido'
                                                        });
                                                    }
                                                },
                                                error: function(error) {
                                                    console.error("Error al obtener los datos del pedido:", error);
                                                }
                                            });
                                        }
                                    </script>


                                    <div style="background-color: white; padding: 10px; border-radius:5px; margin-top: 20px; overflow-x: auto;">
                                        <h5>Pagos Asociados</h5>
                                        <table class="table table-bordered table-hover table-striped table-sm" id="paymentTable">
                                            <thead>
                                                <tr>
                                                    <th scope="col">Id</th>
                                                    <th scope="col">Fecha</th>
                                                    <th scope="col">Rut</th>
                                                    <th scope="col">Nombre</th>
                                                    <th scope="col">Banco</th>
                                                    <th scope="col">Cuenta Origen</th>
                                                    <th scope="col">Monto</th>
                                                    <th scope="col">Detalle</th>
                                                    <th scope="col"></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <!-- Se llenará dinámicamente -->
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
                                <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></r
                                <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
                                <script>
                                    $(document).ready(function() {
                                        $('#contenedorFormularios').on('change', '#selectorCriterio', function() {
                                            var criterio = $(this).val();
                                            if (criterio === 'rut' || criterio === 'rutTercero') {
                                              //  $('#buscarBtn').prop('disabled', true); // Deshabilitar el botón por defecto
                                                $('#inputValor').val(''); // Limpiar el campo

                                                // Si el criterio es 'rut', formatear automáticamente el RUT basado en el valor del campo 'rutb'
                                                if (criterio === 'rut') {
                                                    var rutValor = $('#rutb').val(); // Obtiene el valor del campo 'rutb'
                                                    // Formatear y asignar el valor formateado al campo 'inputValor'
                                                    var rutFormateado = formatearRut(rutValor);
                                                    $('#inputValor').val(rutFormateado);

                                                    // Verificar si el RUT formateado es válido
                                                    validarRut($('#inputValor').val()) ? $('#buscarBtn').prop('disabled', false) : $('#buscarBtn').prop('disabled', true);
                                                }

                                                // Adjuntar manejador de eventos para la validación de RUT en el input
                                                $('#inputValor').on('input', function() {
                                                    // Permite números y 'k' o 'K', formatea el RUT.
                                                    var rutSinFormato = this.value.toLowerCase() // Convierte a minúsculas para estandarizar la 'K' a 'k'
                                                        .replace(/[^0-9k]/g, '') // Elimina caracteres no deseados, excepto números y 'k'
                                                        .replace(/^(\d{1,2})(\d{3})(\d{0,3})([k\d]{0,1})$/, '$1.$2.$3-$4') // Aplica formateo
                                                        .replace(/\.$/, ''); // Elimina punto final si lo hay
                                                    this.value = rutSinFormato;

                                                    validarRut(rutSinFormato) ? $('#buscarBtn').prop('disabled', false) : $('#buscarBtn').prop('disabled', true);
                                                });
                                            } else {
                                                $('#buscarBtn').prop('disabled', false);
                                                $('#inputValor').off('input');
                                            }
                                        });
                                    });

                                    function formatearRut(rut) {
                                        // Elimina caracteres no deseados, excepto 'k' o 'K'
                                        var resultado = rut.replace(/[^0-9kK]/g, '')
                                            .replace(/^(\d{1,3})(\d{3})(\d{3})([kK\d]{1})$/, '$1.$2.$3-$4');
                                        return resultado;
                                    }

                                    function validarRut(rutCompleto) {
                                        if (!/^\d{1,3}\.\d{3}\.\d{3}-[\dkK]$/i.test(rutCompleto)) return false;
                                        var tmp = rutCompleto.split('-');
                                        var digv = tmp[1];
                                        var rut = tmp[0].replace(/\./g, '');
                                        if (digv == 'K') digv = 'k';
                                        return (dv(rut) == digv);
                                    }

                                    function dv(T) {
                                        var M = 0,
                                            S = 1;
                                        for (; T; T = Math.floor(T / 10))
                                            S = (S + T % 10 * (9 - M++ % 6)) % 11;
                                        return S ? S - 1 : 'k';
                                    }






                                    function buscarPagos() {
                                        var criterio = $('#selectorCriterio').val();
                                        var valor = $('#inputValor').val();
                                        console.time('Tiempo de busqueda');
                                        $.ajax({

                                            url: '../api/buscarPagos.php',
                                            type: 'GET',
                                            data: {
                                                criterio: criterio,
                                                valor: valor
                                            },
                                            dataType: 'json',
                                            success: function(respuestaDeLaAPI) {
                                                actualizarTablaBusqueda(respuestaDeLaAPI);
                                                console.timeEnd('Tiempo de busqueda');
                                            },
                                            error: function() {
                                                console.error('Error al obtener los datos');
                                            }
                                        });
                                    }


                                    function actualizarTablaBusqueda(datos) {
                                        var tabla = $('#bankStatementTable tbody');
                                        tabla.empty(); // Limpiar la tabla antes de agregar nuevos datos

                                        datos.forEach(function(pago) {
                                            var fila = `<tr>
                        <td>${pago.id}</td>
                        <td>${pago.fecha}</td>
                        <td>${pago.rut}</td>
                        <td>${pago.nombre}</td>
                        <td>${pago.banco}</td>
                        <td>${pago.numero}</td>
                        <td>${pago.monto}</td>
                        <td>${pago.detalle}</td>
                        <td><button type="button" onclick="asociarPago(${pago.id}, event, '${pago.monto.replace(/\'/g, "\\'")}', '${pago.banco}', '${pago.rut}', '${pago.nombre}')" class="btn btn-primary btn-sm">Asociar</button>
</td>

                    </tr>`;
                                            console.log("exportando datos");
                                            tabla.append(fila);
                                        });
                                    }

                                    function asociarPago(idPago, event, monto, banco, rut, nombre) {
                                        event.stopPropagation();
                                        var num_ord = $('#n_orden').val();

                                        $.ajax({
                                            url: '../api/buscarPagos.php', // Asegúrate de que este endpoint está correctamente implementado para manejar esta solicitud
                                            type: 'GET',
                                            data: {
                                                criterio: "pagados",
                                                id_cartola: idPago,
                                                funcion: "asociarPago",
                                                metodo_pago: "Transferencia",
                                                monto: monto,
                                                valor: idPago,
                                                banco: banco,
                                                rut: rut,
                                                num_orden: num_ord,
                                                nombre: nombre // Asegúrate de que este nombre de parámetro coincide con lo que tu servidor espera
                                            },
                                            dataType: 'json',
                                            success: function(respuestaDeLaAPI) {
                                                // Aquí asumimos que respuestaDeLaAPI devuelve directamente el objeto del pago deseado
                                                // Verifica la estructura de respuesta de tu API y ajusta según sea necesario
                                                if (Array.isArray(respuestaDeLaAPI) && respuestaDeLaAPI.length > 0) {
                                                    // Si la API devuelve una lista, tomamos el primer elemento
                                                    actualizarTablaPagosOrden(respuestaDeLaAPI[0]);
                                                    cargarPagosAsociados(num_ord);
                                                    actualizarTotal(num_ord);
                                                    //  cargarDatosPedido(num_ord);
                                                } else {
                                                    // Si la API devuelve un único objeto directamente
                                                    actualizarTablaPagosOrden(respuestaDeLaAPI);
                                                    cargarPagosAsociados(num_ord);
                                                    actualizarTotal(num_ord);
                                                    //  cargarDatosPedido(num_ord);
                                                }
                                                $(event.target).closest('tr').remove();

                                            },
                                            error: function() {
                                                console.error('Error al obtener los datos del pago');
                                            }
                                        });
                                    }


                                    function actualizarTablaPagosOrden(pago) {
                                        // Asumiendo que pago tiene todos los campos necesarios
                                        var tabla = $('#paymentTable tbody');

                                        var fila = `<tr>
                        <td>${pago.id_mostrar}</td>
                        <td>${pago.fecha}</td>
                        <td>${pago.rut}</td>
                        <td>${pago.nombre}</td>
                        <td>${pago.banco}</td>
                        <td>${pago.numero}</td>
                        <td>${pago.monto}</td>
                        <td>${pago.detalle}</td>
                        <td><button type="button" onclick="desasociarPago(this, ${pago.id}, ${pago.id_cartola})" class="btn btn-danger btn-sm">Desasociar</button></td>

                     

                    </tr>`;
                                        console.log("exportando datos");
                                        tabla.append(fila);

                                    }

                                    function desasociarPago(button, idPago, idcartola) {

                                        var num_ord = $('#n_orden').val();
                                        $.ajax({
                                            url: '../api/buscarPagos.php', // Asegúrate de que este endpoint está correctamente implementado para manejar esta solicitud
                                            type: 'GET',
                                            data: {
                                                criterio: "pagados",
                                                valor: idPago,
                                                id_cartola: idcartola, // Asumimos que idPago es el ID del pago a eliminar
                                                funcion: "eliminarPago" // Cambiamos la función a eliminarPago
                                                // No necesitamos enviar num_orden para eliminar, a menos que tu backend lo requiera por alguna razón
                                            },
                                            dataType: 'json',
                                            success: function(respuestaDeLaAPI) {
                                                // Manejar la respuesta de la API aquí
                                                // Si la eliminación fue exitosa, puedes actualizar la UI acordemente
                                                actualizarTotal(num_ord);
                                                console.log('Pago eliminado con éxito');
                                                // Por ejemplo, si estás eliminando una fila de una tabla en la UI:
                                                $(button).closest('tr').remove(); // Asegúrate de tener una forma de identificar la fila a eliminar, como un ID único

                                            },
                                            error: function(xhr, status, error) {
                                                console.error('Error al eliminar el pago', error);
                                            }
                                        });
                                        // Eliminar la fila

                                    }


                                    function cargarPagosAsociados(numOrden) {
                                        $.ajax({
                                            url: '../api/buscarPagos.php', // La URL de tu servidor que devuelve los pagos asociados
                                            type: 'GET',
                                            data: {
                                                criterio: "por_n_orden",
                                                valor: numOrden
                                            },
                                            dataType: 'json',
                                            success: function(pagos) {
                                                var tabla = $('#paymentTable tbody');
                                                tabla.empty(); // Limpiar la tabla antes de agregar nuevos datos
                                                pagos.forEach(function(pago) {
                                                    var fila = `<tr>
                                <td>${pago.id_mostrar}</td>
                                <td>${pago.fecha}</td>
                                <td>${pago.rut}</td>
                                <td>${pago.nombre}</td>
                                <td>${pago.banco}</td>
                                <td>${pago.numero}</td>
                                <td>${pago.monto}</td>
                                <td>${pago.detalle}</td>
                                <td><button type="button" onclick="desasociarPago(this, ${pago.id}, ${pago.id_cartola})" class="btn btn-danger btn-sm">Desasociar</button></td>
                            </tr>`;
                                                    tabla.append(fila);
                                                });
                                            },
                                            error: function() {
                                                console.error('Error al obtener los pagos asociados');
                                            }
                                        });
                                    }
                                </script>
                            </div>
                        </div>






                    </div>


                </div>

                <div class="modal-footer">

                    <button type="button" class="btn btn-light" data-bs-dismiss="modal" aria-label="Close">Cancelar</button>
                    <button type="button" id="btnGuardar" class="btn btn-dark">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script type="text/javascript">
    function calcularPorPagarTermino() {

        // Obtener los valores de los campos "precio" y "abono"
        var precio = parseFloat($("#precio").val());
        var abono = parseFloat($("#abono").val());

        if (isNaN(abono)) {
            // Si el valor de "abono" no es numérico, mostrar el precio en el campo "porpagar"
            $("#porpagar").val(precio);
        } else {
            // Calcular el resultado de la resta
            var porPagar = precio - abono;

            // Actualizar el valor del campo "porpagar"

            $("#porpagar").val(porPagar);

        }

    }


    $(document).ready(function() {
        function mostrarCargando() {
            $("#calculoporpagar").hide(); // Mostrar el GIF de carga
            $("#loadingIndicator").show(); // Mostrar el GIF de carga



        }

        // Función para ocultar el GIF de carga
        function ocultarCargando() {
            $("#loadingIndicator").hide();
            $("#calculoporpagar").show(); // Ocultar el GIF de carga
        }
        // Función para calcular y actualizar el campo "porpagar"
        function calcularPorPagar() {

            mostrarCargando();
            // Obtener los valores de los campos "precio" y "abono"
            var precio = parseFloat($("#precio").val());
            var abono = parseFloat($("#abono").val());
            var costo_envio = parseFloat($("#costo_envio").val());
            if (isNaN(abono)) {
                // Si el valor de "abono" no es numérico, mostrar el precio en el campo "porpagar"
                $("#porpagar").val(precio);
            } else {
                // Calcular el resultado de la resta
                var porPagar = precio + costo_envio - abono;

                // Actualizar el valor del campo "porpagar"

                $("#porpagar").val(porPagar);

            }
            setTimeout(ocultarCargando, 800);
        }
        $('[data-toggle="tooltip"]').tooltip();





        // Vincular eventos al abrir el modal
        function abrirModal() {

            $('#whatsapp').tooltip('show');
            setTimeout(function() {
                $('#whatsapp').tooltip('hide');
            }, 3000);
            // Llamar a la función para calcular y actualizar el campo "porpagar"
            calcularPorPagar();

        }

        // Vincular eventos al cerrar el modal
        function cerrarModal() {


        }

        // Desvincular eventos antes de volver a vincularlos
        $('#modalEditarPedido').off('shown.bs.modal', abrirModal);
        $('#modalEditarPedido').off('hide.bs.modal', cerrarModal);

        // Vincular eventos al abrir y cerrar el modal
        $('#modalEditarPedido').on('shown.bs.modal', abrirModal);
        $('#modalEditarPedido').on('hide.bs.modal', cerrarModal);




    });
</script>

<script type="text/javascript">
    function toggleDetails() {
        var detailsContainer = document.getElementById("detailsContainer");
        var toggleBtn = document.getElementById("toggleDetailsBtn");

        if (detailsContainer.style.display === "none") {
            detailsContainer.style.display = "block";
            toggleBtn.innerText = "Ocultar Detalles";
        } else {
            detailsContainer.style.display = "none";
            toggleBtn.innerText = "Mostrar Detalles";
        }
    }
</script>

<script>
    // Obtener el elemento del campo de teléfono
    var telefonoInput = document.getElementById("telefonob");

    // Obtener el elemento del enlace de WhatsApp
    var whatsappLink = document.getElementById("whatsapp-link");

    // Agregar un evento de clic al enlace de WhatsApp
    whatsappLink.addEventListener("click", function(event) {
        // Obtener el número de teléfono del campo de entrada
        var telefono = telefonoInput.value;

        // Construir la URL del enlace de WhatsApp con el número de teléfono
        var url = "https://api.whatsapp.com/send/?phone=+56" + telefono + "&text=Hola! te escribimos de respaldos chile ";

        // Establecer la URL del enlace de WhatsApp
        whatsappLink.href = url;
    });
</script>

<script>function solicitarCartola() {
  var boton = document.getElementById("btnCartola");
  // Cambiar el texto del botón y deshabilitarlo
  boton.innerHTML = 'Cargando...';
  boton.disabled = true;

  var now = new Date().getTime();
  localStorage.setItem("disabledUntil", now + (5 * 60 * 1000)); // Añade 5 minutos al tiempo actual


  // Simulación de una petición AJAX/Fetch (reemplaza esto con tu código real)
  fetch('../api/obtener_curl.php')
  .then((response) => {
    if (!response.ok) {
        throw new Error('La solicitud no se completó con éxito');
    }
    return response.text(); // o .text() si la respuesta es texto y no JSON
  })
  .then((data) => {
    // Procesa la respuesta obtenida si es necesario
    Swal.fire({
  title: 'Solicitud Exitosa',
  html: '<strong>Cartola solicitada.</strong><br>Por favor, espera al menos 5 minutos antes de volver a solicitar.<br>',
  icon: 'success',
  confirmButtonText: 'Entendido'
});
  })
  .catch((error) => {
    Swal.fire({
      title: 'Error',
      text: 'Ocurrió un error al solicitar la cartola: ' + error.message,
      icon: 'error',
      confirmButtonText: 'Cerrar'
    });
  }).finally(() => {
      // Restablecer el texto del botón y habilitarlo nuevamente
      boton.innerHTML = 'Solicitar Cartola';
      boton.disabled = true;
    });
}

function solicitarPorMes() {
  Swal.fire({
    title: 'Seleccione el mes',
    input: 'select',
    inputOptions: getMesOptions(),
    inputPlaceholder: 'Seleccione un mes',
    showCancelButton: true,
    confirmButtonText: 'Consultar'
  }).then((result) => {
    if (result.isConfirmed && result.value) {
      var selectedMes = result.value;
      
      // Mostrar modal de carga para indicar que se está trabajando
      Swal.fire({
          title: 'Cargando...',
          html: 'Por favor, espere un momento.',
          allowOutsideClick: false,
          didOpen: () => {
              Swal.showLoading();
          }
      });

      // Realizar la solicitud a obtener_curl.php enviando el mes seleccionado
      fetch('../api/obtener_cartola_mes_curl.php', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json'
        },
        body: JSON.stringify({ searchType: "dateRange", mes: selectedMes })
      })
      .then((response) => {
        if (!response.ok) {
          throw new Error('La solicitud no se completó con éxito');
        }
        return response.text(); // O .json() si la respuesta es JSON
      })
      .then((data) => {
        Swal.fire({
          title: 'Solicitud Exitosa',
          html: '<strong>Cartola solicitada.</strong><br>Por favor, espera al menos 5 minutos antes de volver a solicitar.',
          icon: 'success',
          confirmButtonText: 'Entendido'
        });
      })
      .catch((error) => {
        Swal.fire({
          title: 'Error',
          text: 'Ocurrió un error al solicitar la cartola: ' + error.message,
          icon: 'error',
          confirmButtonText: 'Cerrar'
        });
      });
    }
  });
}

// Función para generar las opciones de mes (solo del año actual, hasta el mes actual)
function getMesOptions() {
  var options = {};
  var now = new Date();
  var currentMonth = now.getMonth() + 1; // getMonth() devuelve 0-11
  var monthNames = {
    1: "Enero",
    2: "Febrero",
    3: "Marzo",
    4: "Abril",
    5: "Mayo",
    6: "Junio",
    7: "Julio",
    8: "Agosto",
    9: "Septiembre",
    10: "Octubre",
    11: "Noviembre",
    12: "Diciembre"
  };
  // Se generan las opciones desde el mes actual hasta enero
  for (var m = currentMonth; m >= 1; m--) {
    var key = m < 10 ? "0" + m : "" + m;
    options[key] = monthNames[m];
  }
  return options;
}


</script>