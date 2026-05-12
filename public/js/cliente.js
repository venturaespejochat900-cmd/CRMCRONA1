$(document).ready(main);
var idClienteMuestra ;
function main() {
    buscarClientes();
    buscarPrescriptor();
    controlCerrarEmergente();
    limpiarFormulario();
    insertarNuevaDireccion();
    grabarDatos();
    buscarPrescriptorModal();
    buscarConsultaClientes();
    buscarPrescriptoresConsultaModal();
    buscarClientesMuestra();

}
//CLIENTES
/**
 * MÉTODO PARA BUSCAR CLIENTES
 */
function buscarClientes(){
    delay(function(){
        $('#busquedaCliente').keyup(function (){
            var clienteAbuscar = $(this).val();
            var parametros = {
                "clienteAbuscar" : clienteAbuscar,
                "_token": $("meta[name='csrf-token']").attr("content")
            };
            $.ajax({
                data: parametros,
                url: './busquedaClientes',
                type: 'post',
                timeout: 2000,
                async: true,
                success: function (response){
                    $("#modalCliente").css('display','inline');
                    $(".salida").html(response);
                }
            })
        })
    }, 100);
}
function buscarClientesMuestra(){
        $('#busquedaPrescriptorClienteModal').keyup(function (){
            var clienteAbuscar = $(this).val();
            var parametros = {
                "clienteAbuscar" : clienteAbuscar,
                "_token": $("meta[name='csrf-token']").attr("content")
            };
            $.ajax({
                data: parametros,
                url: './busquedaClientes',
                type: 'post',
                timeout: 2000,
                async: true,
                success: function (response){
                    $("#modalPrescriptorClienteModal").css('display','inline');
                    $(".salidaPrescriptorClienteModal").html(response);
                }
            })
        })
}
function seleccionarCliente(cliente){

    console.log(cliente);
    idClienteMuestra = cliente.CodigoCliente;
    $("#modalCliente").css('display','none');
    $("#modalPrescriptorClienteModal").css('display','none');
    $("#busquedaPrescriptorClienteModal").val(cliente.Nombre);
    $("#id").val(cliente.CodigoCliente);
    $("#nif").val(cliente.CifDni);
    $("#nombre").val(cliente.Nombre);
    $("#telefono").val(cliente.Telefono);
    $("#telefono2").val(cliente.Telefono2);
    $("#email").val(cliente.EMail1);
    $("#caducidadPrescripcion").val(cliente.VFechaPrescripcion.substring(0,11));
    $("#idPrescriptor").val(cliente.CodigoComisionista);
    $("#direccion").val(cliente.Domicilio);
    $("#poblacion").val(cliente.Municipio);
    $("#codigoPostal").val(cliente.CodigoPostal);
    $("#IBAN").val(cliente.IBAN);
    $("#descuentoCliente").val(cliente.Descuento);
    $("#filaOpcionDireccion").css('display','inline');
    $('#busquedaCliente').val(cliente.Nombre);
    if(cliente.VGamaVerde == 1){
        $("#gamaVerde").attr('checked');
    }
    if(cliente.VGamaAmarilla == 1){
        $("#gamaAmarilla").attr('checked');
    }
    if(cliente.VGamaRoja == 1){
        $("#gamaRoja").attr('checked');
    }
    if(cliente.VSuplementacion == 1){
        $("#suplementacion").attr('checked');
    }
    $("#notificacion option").each(function (){
        if ($(this).val() == cliente.VNotificacion){
            $(this).attr('selected','selected');
        }
    });
    if(cliente.CodigoComisionista != 0){
        var parametros = {
            "prescriptor" : cliente.CodigoComisionista,
            "_token": $("meta[name='csrf-token']").attr("content")
        };
        $.ajax({
            data: parametros,
            url: './obtenerNombrePrescriptor',
            type: 'post',
            timeout: 2000,
            async: true,
            success: function (response){
                $("#busquedaPrescriptor").val(response['0'].Comisionista);
            }
        })
    };
    if(cliente.ComercialAsignadoLc != 0){
        var parametros = {
            "prescriptor" : cliente.ComercialAsignadoLc,
            "_token": $("meta[name='csrf-token']").attr("content")
        };
        $.ajax({
            data: parametros,
            url: './obtenerNombrePrescriptor',
            type: 'post',
            timeout: 2000,
            async: true,
            success: function (response){
                $("#comercial").val(response['0'].Comisionista);
            }
        })
    };


    $("#provincia option").each(function (){
        if ($(this).val() == cliente.CodigoProvincia){
            $(this).attr('selected','selected');
        }
    });
    $("#pais option").each(function (){
        if ($(this).val() == cliente.CodigoNacion){
            $(this).attr('selected','selected');
        }
    });
    $("#tCliente option").each(function (){
        if ($(this).val()== cliente.CodigoTipoClienteLc){
            $(this).attr('selected','selected');
        }
    });
    $("#conocimientoDieta option").each(function (){
        if ($(this).val()== cliente.VMotivoDieta){
            $(this).attr('selected','selected');
        }
    });
    $("#alergico option").each(function (){
        if ($(this).index()== cliente.VAlergico){
            $(this).attr('selected','selected');
        }
    });
    $("#fase option").each(function (){
        if ($(this).val()== cliente.VFase){
            $(this).attr('selected','selected');
        }
    });
    $("#smdt option").each(function (){
        if ($(this).val()== cliente.VSmdt2){
            $(this).attr('selected','selected');
        }
    });
    $("#productosDia option").each(function (){
        if ($(this).val()== cliente.VProductosDia){
            $(this).attr('selected','selected');
        }
    });
    $("#descuentoCliente").val(parseFloat(cliente['%Descuento'],2));
    //PARA RELLENAR HISTÓRICO DEL CLIENTE
    mostrarHistoricoCliente(cliente.CodigoCliente);
}

/**
 * PARA LA CONSULTA DEL CLIENTE
 */
function buscarConsultaClientes(){
    $("#consultaClientes").click( function (){
        $("#modalConsultaClientes").modal('show');
    });
            $('#busquedaConsultaCliente').keyup(function (){
                console.log($(this).val());
                var clienteAbuscar = $(this).val();
                var parametros = {
                    "clienteAbuscar" : clienteAbuscar,
                    "_token": $("meta[name='csrf-token']").attr("content")
                };
                $.ajax({
                    data: parametros,
                    url: './busquedaConsultaClientes',
                    type: 'post',
                    timeout: 2000,
                    async: true,
                    success: function (response){
                        $("#modalConsultaClienteCliente").css('display','inline');
                        $(".salidaConsultaCliente").html(response);
                    }
                })
            })
}

function mostrarDatosCliente(cliente){
    $("#modalConsultaClienteCliente").css('display','none');
    $("#busquedaConsultaCliente").val(cliente.Nombre);
    var html = "<tbody>" +
        "<tr><td><strong>Nombre</strong></td><td>"+cliente.Nombre+"</td></tr>" +
        "<tr><td><strong>DNI</strong></td><td>"+cliente.CifDni+"</td></tr>" +
        "<tr><td><strong>Teléfono</strong></td><td>"+cliente.Telefono+"</td></tr>" +
        "<tr><td><strong>Email</strong></td><td>"+cliente.EMail1+"</td></tr>" +
        "<tr><td><strong>Direccion</strong></td><td>"+cliente.Domicilio+"</td></tr>" +
        "<tr><td><strong>Municipio</strong></td><td>"+cliente.Municipio+"</td></tr>" +
        "<tr><td><strong>Código postal</strong></td><td>"+cliente.CodigoPostal+"</td></tr>" +
        "</tbody>";
    $("#datosConsultaCliente").append(html);

}


/**
 * MÉTODO PARA SELECCIONAR CLIENTE Y RELLENAR SUS DATOS EN EL FORMULARIO
 * @param cliente
 */


/**
 * MÉTODO PARA MOSTRAR EL HISTÓRICO DE COMPRAS DEL CLIENTE
 * @param codigoCliente
 */
function mostrarHistoricoCliente(codigoCliente){
    var parametros = {
        "idCliente" : codigoCliente,
        "_token": $("meta[name='csrf-token']").attr("content")
    };
    $.ajax({
        data: parametros,
        url: './cliente/historico',
        type: 'post',
        timeout: 2000,
        async: true,
        success: function (response){
            $(".historico").html(response);
        }
    })
}

/**
 * MÉTODO PARA OBTENER PRESCRIPTORES
 */
function buscarPrescriptor(){
    $('#busquedaPrescriptor').keyup(function (){
        var prescriptorAbuscar = $(this).val();
        console.log('prescriptor cliente pedido');
        var parametros = {
            "prescriptorAbuscar" : prescriptorAbuscar,
            "_token": $("meta[name='csrf-token']").attr("content")
        };
        $.ajax({
            data: parametros,
            url: './busquedaPrescriptor',
            type: 'post',
            timeout: 3000,
            async: true,
            success: function (response){
                $("#modalPrescriptor").css('display','inline');
                $(".salidaPrescriptor").html(response);
            }
        })
    })
}
function buscarPrescriptorModal(){
    $('#busquedaPrescriptorModal').keyup(function (){
        var prescriptorAbuscar = $(this).val();
        var parametros = {
            "prescriptorAbuscar" : prescriptorAbuscar,
            "_token": $("meta[name='csrf-token']").attr("content")
        };
        $.ajax({
            data: parametros,
            url: './busquedaPrescriptor',
            type: 'post',
            timeout: 3000,
            async: true,
            success: function (response){
                $("#modalPrescriptorModal").css('display','inline');
                $(".salidaPrescriptorModal").html(response);
            }
        })
    })
}

function buscarPrescriptoresConsultaModal(){
    $("#consultaPrescriptor").click( function (){
        $("#modalConsultaPrescriptores").modal('show');
    });
    $('#busquedaConsultaPrescriptor').keyup(function (){
        console.log($(this).val());
        var prescriptorAbuscar = $(this).val();
        var parametros = {
            "prescriptorAbuscar" : prescriptorAbuscar,
            "_token": $("meta[name='csrf-token']").attr("content")
        };
        $.ajax({
            data: parametros,
            url: './busquedaConsultaPrescriptor',
            type: 'post',
            timeout: 3000,
            async: true,
            success: function (response){
                $("#modalConsultaPrescriptoresVisible").css('display','inline');
                $(".salidaConsultaPrescriptor").html(response);
            }
        })
    })
}

function seleccionarPrescriptorConsultaModal(prescriptor){
    $("#modalConsultaPrescriptoresVisible").css('display','none');
    $("#busquedaConsultaCliente").val(prescriptor.Nombre);
    var idComisionista = prescriptor.CodigoComisionista;
    var tablaClientes = new Array();
    var html = "<tbody>" +
        "<tr><td><strong>Nombre</strong></td><td>"+prescriptor.Comisionista+"</td></tr>" +
        "<tr><td><strong>DNI</strong></td><td>"+prescriptor.CifDni+"</td></tr>" +
        "<tr><td><strong>Teléfono</strong></td><td>"+prescriptor.Telefono+"</td></tr>" +
        "<tr><td><strong>Email</strong></td><td>"+prescriptor.EMail1+"</td></tr>" +
        "<tr><td><strong>Direccion</strong></td><td>"+prescriptor.Domicilio+"</td></tr>" +
        "<tr><td><strong>Municipio</strong></td><td>"+prescriptor.Municipio+"</td></tr>" +
        "<tr><td><strong>Código postal</strong></td><td>"+prescriptor.CodigoPostal+"</td></tr>" +
        "<tr><td><strong>Nación</strong></td><td>"+prescriptor.Nacion+"</td></tr>" +
        "<tr><td><strong>IBAN</strong></td><td>"+prescriptor.IBAN+"</td></tr>" +
        "<tr><td><strong>Pacientes</strong></td><td class='htmlCliente'></td></tr>" +
        "</tbody>";
    var parametros = {
        "codigoComisionista": idComisionista,
        "_token": $("meta[name='csrf-token']").attr("content")
    };
    $.ajax({
        data: parametros,
        url: './obtenerClientesComisionista',
        type: 'post',
        timeout: 2000,
        async: true,
        success: function (response){
            $(".htmlCliente").html(response);
        }
    })
    $("#datosConsultaPrescriptor").append(html);

}

function seleccionarPrescriptor(prescriptor){
    $("#modalPrescriptor").css('display','none');
    $("#busquedaPrescriptor").val(prescriptor.Comisionista);
    $("#idPrescriptor").val(prescriptor.CodigoComisionista);
    $("#comercial").val(prescriptor.Comisionista);
    $("#busquedaPrescriptorModal").val(prescriptor.Comisionista);
    $("#modalPrescriptorModal").css('display','none');
}

function grabarDatos(){
    $('#anadirNuevoCliente').click(function (){
        var id = $("#id").val();
        if(id != ""){
            var datosPedido = obtenerDatosClienteFormulario();
            var parametros = {
                "datos": datosPedido,
                "_token": $("meta[name='csrf-token']").attr("content")
            };
            $.ajax({
                data: parametros,
                url: './obtenerCliente',
                type: 'post',
                timeout: 2000,
                async: true,
                success: function (response){
                    if(response == "OK"){
                        window.location.href = './inicioNuevoCliente';
                    }
                }
            })
        }else{
            insertarNuevoCliente();
        }
    });
}
/**
 * MÉTODO PARA REALIZAR INSERCIÓN EN BBDD DE NUEVO CLIENTE
 */
function insertarNuevoCliente(){

        var datosCliente = obtenerDatosClienteFormulario();
        console.log(datosCliente);
        var parametros = {
            "datos" : datosCliente,
            "_token": $("meta[name='csrf-token']").attr("content")
        };
        console.log(parametros);
        $.ajax({
            data: parametros,
            url: './cliente/insertarNuevoCliente',
            type: 'post',
            timeout: 2000,
            async: true,
            success: function (response){
                if(response == "OK"){
                    console.log('realizado correctamente');
                }
            }
        })

}

/**
 * MÉTODO PARA OBTENER TODOS LOS DATOS DEL FORMULARIO Y MANDARLOS AL SERVIDOR
 */
function obtenerDatosClienteFormulario(){
    var datosNuevoCliente = {};
    var id = $("#id").val();
    if(id != ""){
        datosNuevoCliente.idCliente = id;
    }
    datosNuevoCliente.tipoCliente = $("#tCliente").val();
    datosNuevoCliente.nif = $("#nif").val();
    datosNuevoCliente.nombreEmpresa = $("#apellidos").val();
    datosNuevoCliente.nombre = $("#nombre").val();
    datosNuevoCliente.email = $("#email").val();
    datosNuevoCliente.telefono = $("#telefono").val();
    datosNuevoCliente.telefono2 = $("#telefono2").val();
    datosNuevoCliente.conocimientoDieta = $("#conocimientoDieta").val();
    datosNuevoCliente.prescriptor = $("#busquedaPrescriptor").val();
    datosNuevoCliente.idPrescriptor = $("#idPrescriptor").val();
    datosNuevoCliente.fase = $("#fase").val();
    datosNuevoCliente.productosDia = $("#productosDia").val();
    datosNuevoCliente.pais = $("#pais").val();
    datosNuevoCliente.iva = $("#IVA").val();
    datosNuevoCliente.nombrePais = $("#pais").find('option:selected').text().trim();
    datosNuevoCliente.provincia = $("#provincia").val();
    datosNuevoCliente.nombreProvincia =$("#provincia").find('option:selected').text().trim();
    datosNuevoCliente.poblacion = $("#poblacion").val();
    datosNuevoCliente.direccion = $("#direccion").val();
    datosNuevoCliente.codigoPostal = $("#codigoPostal").val();
    datosNuevoCliente.tipoEnvio = $("#tipoEnvio").val();
    datosNuevoCliente.fEntrega = $("#fEntrega").val();
    datosNuevoCliente.iban = $("#IBAN").val();
    datosNuevoCliente.vGamaAmarilla = $("#gamaAmarilla").val();
    datosNuevoCliente.vGamaRoja = $("#gamaRoja").val();
    datosNuevoCliente.vGamaVerde = $("#gamaVerde").val();
    datosNuevoCliente.vSuplementacion = $("#suplementacion").val();
    datosNuevoCliente.vNotificacion = $("#notificacion").val();
    datosNuevoCliente.smdt2 = $("#smdt").val();
    datosNuevoCliente.alergico = $("#alergico").val();
    datosNuevoCliente.descuento = $("#descuentoCliente").val();
    datosNuevoCliente.fechaPrescripcion = $("#caducidadPrescripcion").val();

    return datosNuevoCliente;
}

//DIRECCIONES
function verMasDirecciones(){
    var idCliente = $("#id").val();
    var parametros = {
        "idCliente" : idCliente,
        "_token": $("meta[name='csrf-token']").attr("content")
    };
    $.ajax({
        data: parametros,
        url: './cliente/verDirecciones',
        type: 'post',
        timeout: 2000,
        async: true,
        success: function (response){
            $("#direcciones").modal('show');
            $(".modalDirecciones").html(response);
        }
    })
}
/**
 * MÉTODO PARA SELECCIONAR UNA DIRECCIÓN DE ENVÍO DIFERENTE A LA PRINCIPAL
 */
function seleccionarDireccionEnvio(direccion){
    console.log(direccion);
    $("#direccion").val(direccion.Domicilio);
    $("#poblacion").val(direccion.Municipio);
    $("#codigoPostal").val(direccion.CodigoPostal);
    $("#provincia option").each(function (){
        if ($(this).val() == 14){
            $(this).attr('selected','selected');
        }
    });
    $("#pais option").each(function (){
        if ($(this).val() == direccion.CodigoNacion){
            $(this).attr('selected','true');
        }
    });
    $("#direcciones").modal('hide');
}

/**
 * MÉTODO PARA AÑADIR UNA NUEVA DIRECCIÓN
 */
function insertarNuevaDireccion(){
    $("#anadirNuevaDireccion").click(function (){
        datosDireccion = recogerDatosNuevaDireccion();
        var parametros = {
            "datos" : datosDireccion,
            "_token": $("meta[name='csrf-token']").attr("content")
        };
        console.log(parametros);
        $.ajax({
            data: parametros,
            url: './clientes/nuevoDomicilio',
            type: 'post',
            timeout: 2000,
            async: true,
            success: function (response){
                if(response == "OK"){
                    verMasDirecciones();
                }
            }
        })
    });
}

function recogerDatosNuevaDireccion(){
    var datosDireccion = {};
    datosDireccion.direccion = $("#nuevaDireccion").val();
    datosDireccion.poblacion = $("#nuevaPoblacion").val();
    datosDireccion.codigoPostal = $("#nuevoCodigoPostal").val();
    datosDireccion.razonSocial = $("#nombre").val();
    datosDireccion.idCliente = $("#id").val();
    datosDireccion.codigoPais = $("#nuevoPais").val();
    datosDireccion.pais = $("#nuevoPais").find('option:selected').text();
    datosDireccion.codigoProvincia = $("#nuevaProvincia").val();
    var provincia = $("#nuevaProvincia").find('option:selected').text();
    datosDireccion.provincia = provincia.trim();
    datosDireccion.observaciones = $("#observacionesNuevaDireccion").val();
    datosDireccion.numeroDomicilio = $('#numeroDireccion').val();

    return datosDireccion;
}

function limpiarFormulario(){
    $('#limpiarFormulario').click(function() {
        $('input[type="text"]').val('');
        $(".historico").html('');
    });
}
function controlCerrarEmergente () {
    $(".cerrarSalidaCliente").click(function(){
        console.log('cerrar cliente');
        $('.pop-up').css('display','none');
        $('.pop-up-wrap').css('display','none');

    });
    $(".cerrarSalidaPrescriptor").click(function(){
        console.log("cerrarmodalprescriptor");
        $('.prescriptor').css('display','none');
        //$('.modalPrescriptor').css('display','none');
        $("#busquedaPrescriptor").val("");

    });
    $(".busquedaPrescriptorModal").click(function (){
        $("#modalPrescriptorModal").css('display','none');
        $("#busquedaPrescriptorModal").val("");

    })
    $(".cerrarSalidaProductoModal").click(function(){
        console.log("cerrarmodalproducto ");
        $("#busquedaProducto").val("");
        $('#modalProducto').css('display','none');
    });
    $(".cerrarSalidaProductoPedido").click(function (){
        console.log("cerrar producto salida");
        $("#busquedaProductoPedido").val("");
        $("#modalProductoPedido").css('display','none');
    });
    $(".cerrarSalidaProductoModal").click(function (){
        console.log("cerrar producto salida");
        $("#busquedaProductoMuestra").val("");
        $("#modalProductoMuestra").css('display','none');
    });
}
var delay = (function(){
    var timer = 0;
    return function(callback, ms){
        clearTimeout (timer);
        timer = setTimeout(callback, ms);
    };
})();




