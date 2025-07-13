$(document).ready(function() {
    var table = $('#dataTableClientes').DataTable({
        processing: true,
        serverSide: false,
        ajax: {
            url: "api/get_clientes.php",
            type: "POST",
            dataSrc: ""
        },
        columns: [
            { "data": "rut" },
            { "data": "nombre" },
            { "data": "telefono" },
            { "data": "instagram" },
            { "data": "correo" },
            {
                "data": null,
                "orderable": false,
                "render": function(data, type, row) {
                    return `
                        <div class="btn-group btn-group-sm" role="group">
                            <button type="button" class="btn btn-outline-primary btn-edit" data-rut="${row.rut}" title="Editar Cliente">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button type="button" class="btn btn-outline-danger btn-delete" data-rut="${row.rut}" title="Eliminar Cliente">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>`;
                }
            }
        ],
        language: {
            lengthMenu: "Mostrar _MENU_ clientes",
            zeroRecords: "No se encontraron clientes",
            info: "Mostrando _START_ a _END_ de _TOTAL_ clientes",
            infoEmpty: "No hay clientes disponibles",
            infoFiltered: "(filtrado de _MAX_ clientes totales)",
            search: "Buscar:",
            paginate: {
                first: "Primero",
                last: "Último",
                next: "Siguiente",
                previous: "Anterior"
            },
            processing: "Cargando clientes..."
        },
        dom: '<"table-controls"<"left-controls"l><"center-controls"B><"right-controls"f>>rtip',
        buttons: [
            {
                text: '<i class="fas fa-plus-circle"></i> Nuevo Cliente',
                className: 'btn btn-primary btn-sm',
                action: function() {
                    newClienteModal.show();
                }
            },
            {
                text: '<i class="fas fa-sync-alt"></i> Actualizar',
                className: 'btn btn-outline-primary btn-sm',
                action: function() {
                    table.ajax.reload();
                }
            }
        ]
    });

    // Instancias de modales de Bootstrap 5
    const newClienteModal = new bootstrap.Modal(document.getElementById('modalNuevoCliente'));
    const editClienteModal = new bootstrap.Modal(document.getElementById('modalEditarCliente'));

    // Handle Edit Button
    $('#dataTableClientes tbody').on('click', 'button.btn-edit', function() {
        var data = table.row($(this).parents('tr')).data();
        // Populate edit modal with data
        $('#edit_rut').val(data.rut);
        $('#edit_nombre').val(data.nombre);
        $('#edit_telefono').val(data.telefono);
        $('#edit_instagram').val(data.instagram);
        $('#edit_correo').val(data.correo);
        editClienteModal.show();
    });

    // Handle Delete Button
    $('#dataTableClientes tbody').on('click', 'button.btn-delete', function() {
        var data = table.row($(this).parents('tr')).data();
        Swal.fire({
            title: '¿Estás seguro?',
            text: "¡No podrás revertir esto!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, eliminarlo!',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: 'api/delete_cliente.php',
                    type: 'POST',
                    data: { rut: data.rut },
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            Swal.fire(
                                '¡Eliminado!',
                                response.message,
                                'success'
                            );
                            table.ajax.reload();
                        } else {
                            Swal.fire(
                                '¡Error!',
                                response.message,
                                'error'
                            );
                        }
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        Swal.fire(
                            '¡Error!',
                            'Hubo un problema al conectar con el servidor: ' + textStatus,
                            'error'
                        );
                    }
                });
            }
        });
    });

    // Handle Save New Client Button
    $('#btnGuardarCliente').on('click', function() {
        var formData = $('#formNuevoCliente').serialize();
        $.ajax({
            url: 'api/add_cliente.php',
            type: 'POST',
            data: formData,
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    Swal.fire(
                        '¡Éxito!',
                        response.message,
                        'success'
                    );
                    newClienteModal.hide();
                    $('#formNuevoCliente')[0].reset(); // Limpiar el formulario
                    table.ajax.reload(); // Reload DataTable to show new client
                } else {
                    Swal.fire(
                        '¡Error!',
                        response.message,
                        'error'
                    );
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                Swal.fire(
                    '¡Error!',
                    'Hubo un problema al conectar con el servidor: ' + textStatus,
                    'error'
                );
            }
        });
    });

    // Handle Update Client Button
    $('#btnActualizarCliente').on('click', function() {
        var formData = $('#formEditarCliente').serialize();
        $.ajax({
            url: 'api/update_cliente.php',
            type: 'POST
            data: formData,
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    Swal.fire(
                        '¡Éxito!',
                        response.message,
                        'success'
                    );
                    editClienteModal.hide();
                    table.ajax.reload();
                } else {
                    Swal.fire(
                        '¡Error!',
                        response.message,
                        'error'
                    );
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                Swal.fire(
                    '¡Error!',
                    'Hubo un problema al conectar con el servidor: ' + textStatus,
                    'error'
                );
            }
        });
    });
});