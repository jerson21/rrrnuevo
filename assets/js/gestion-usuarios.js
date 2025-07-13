$(document).ready(function() {
    var table = $('#dataTableUsers').DataTable({
        processing: true,
        serverSide: false,
        ajax: {
            url: "api/get_usuarios.php",
            type: "POST",
            dataSrc: ""
        },
        columns: [
            { "data": "id" },
            { "data": "usuario" },
            { "data": "nombres" },
            { "data": "apaterno" },
            { "data": "rut" },
            { "data": "correo" },
            {
                "data": "privilegios",
                "render": function(data, type, row) {
                    const roles = {
                        0: { nombre: 'Tapicero', color: 'secondary' },
                        4: { nombre: 'Vendedor', color: 'info' },
                        5: { nombre: 'Supervisor', color: 'warning' },
                        20: { nombre: 'Administrador', color: 'primary' },
                        21: { nombre: 'Super Admin', color: 'danger' }
                    };
                    const roleInfo = roles[data] || { nombre: 'Desconocido', color: 'light' };
                    return `<span class="badge bg-${roleInfo.color}">${roleInfo.nombre}</span>`;
                }
            },
            {
                "data": null,
                "orderable": false,
                "render": function(data, type, row) {
                    return `
                        <div class="btn-group btn-group-sm" role="group">
                            <button type="button" class="btn btn-outline-primary btn-edit" data-id="${row.id}" title="Editar Usuario">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button type="button" class="btn btn-outline-danger btn-delete" data-id="${row.id}" title="Eliminar Usuario">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>`;
                }
            }
        ],
        language: {
            lengthMenu: "Mostrar _MENU_ usuarios",
            zeroRecords: "No se encontraron usuarios",
            info: "Mostrando _START_ a _END_ de _TOTAL_ usuarios",
            infoEmpty: "No hay usuarios disponibles",
            infoFiltered: "(filtrado de _MAX_ usuarios totales)",
            search: "Buscar:",
            paginate: {
                first: "Primero",
                last: "Último",
                next: "Siguiente",
                previous: "Anterior"
            },
            processing: "Cargando usuarios..."
        },
        dom: '<"table-controls"<"left-controls"l><"center-controls"B><"right-controls"f>>rtip',
        buttons: [
            {
                text: '<i class="fas fa-plus-circle"></i> Nuevo Usuario',
                className: 'btn btn-primary btn-sm',
                action: function() {
                    newUsuarioModal.show();
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
    const newUsuarioModal = new bootstrap.Modal(document.getElementById('modalNuevoUsuario'));
    const editUsuarioModal = new bootstrap.Modal(document.getElementById('modalEditarUsuario'));

    // Handle Edit Button
    $('#dataTableUsers tbody').on('click', 'button.btn-edit', function() {
        var data = table.row($(this).parents('tr')).data();
        $('#edit_id').val(data.id);
        $('#edit_usuario').val(data.usuario);
        $('#edit_nombres').val(data.nombres);
        $('#edit_apaterno').val(data.apaterno);
        $('#edit_amaterno').val(data.amaterno);
        $('#edit_rut').val(data.rut);
        $('#edit_correo').val(data.correo);
        $('#edit_privilegios').val(data.privilegios);
        editUsuarioModal.show();
    });

    // Handle Update User Button
    $('#btnActualizarUsuario').on('click', function() {
        var formData = $('#formEditarUsuario').serialize();
        $.ajax({
            url: 'api/update_usuario.php',
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
                    editUsuarioModal.hide();
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

    // Handle Delete Button
    $('#dataTableUsers tbody').on('click', 'button.btn-delete', function() {
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
                    url: 'api/delete_usuario.php',
                    type: 'POST',
                    data: { id: data.id },
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

    // Handle Save New User Button
    $('#btnGuardarUsuario').on('click', function() {
        var formData = $('#formNuevoUsuario').serialize();
        $.ajax({
            url: 'api/add_usuario.php',
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
                    newUsuarioModal.hide();
                    $('#formNuevoUsuario')[0].reset(); // Limpiar el formulario
                    table.ajax.reload(); // Reload DataTable to show new user
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
