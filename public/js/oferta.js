$(document).ready(main);
var total = total;
var totalDevolucion = 0;
var valorEnvio = 12;

var pagosRealizados = new Array();
var pendientePago;
var subTotal;
var observacionModal = observaciones;
//var serieBorrador = serieBorrador;
var comprobarEsDevolucion = false;
var descuentoEnvio = 0;
var ivaProducto;
var serieGlobal = "";
var numeroPedidoGlobal = "";
var codigoClienteGlobal = "";
var ejercicioAlbaranGlobal = "";
var arrayUnidadesDevueltasArticulo = {};
var partidas = {};
var tarifasEspeciales = {};


function main() {


    fechaActualDatePickerPedido();
    //datosClientePedido();
    procesarPedido();
    //procesarDevolución();
    //activarModalObservaciones();
    guardarPedidoBorrador();
    // accionesPedidos();
    borrarDatosPedido();
    //busquedaPedidos();
    //salirModoDevolucion();
    buscarArticulosPedido();
    $(".cerrarSalidaProductoPedido").click(function() {
        $("#busquedaProductoPedido").val("");
        $("#modalProductoPedido").css('display', 'none');
    });

    //guardarUltimoProceso();


}

function getParameterByName(name) {
    name = name.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
    var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"),
        results = regex.exec(location.search);
    return results === null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));
}

function datosClientePedido() {
    var idCliente = getParameterByName('cod');
    var parametros = {
        "cliente": idCliente,
        "_token": $("meta[name='csrf-token']").attr("content")
    };
    console.log(parametros);
    $.ajax({
        data: parametros,
        url: './datosClienteOferta',
        type: 'post',
        timeout: 2000,
        async: true,
        success: function(response) {            
            $("#codigoCliente").val(response[0].CodigoCliente);
            $("#razonSocialCliente").text(response[0].RazonSocial);
            $("#razonSocialCliente2").text(response[0].RazonSocial);
            direccion = response[0].Domicilio+', '+response[0].Municipio+', '+response[0].CodigoPostal;
            $("#direccionCliente").text(direccion);
            $("#direccionCliente2").text(direccion);
            email = 'mailto:'+response[0].EMail1+'?subject='+response[0].RazonSocial+' Oferta:';            
            $("#emailCliente").attr('href', email);           
            $("#emailCliente").text(response[0].EMail1);           
            $("#emailCliente2").text(response[0].EMail1); 
            $("#correoAEnviar").val(response[0].EMail1+', ');             
        }
    })

}

function buscarArticulosPedido() {
    $("#busquedaProductoMuestra").val("");
    $('#busquedaProductoPedido').keyup(function() {
        
        var productoAbuscar = $(this).val();
        var parametros = {
            "productoAbuscar": productoAbuscar,
            "_token": $("meta[name='csrf-token']").attr("content")
        };
        $.ajax({
            data: parametros,
            url: './busquedaArticuloOferta',
            type: 'post',
            timeout: 2000,
            async: true,
            success: function(response) {
                $("#modalProductoPedido").css('display', 'inline');
                $(".salidaProductoPedido").html(response);
            }
        })
    })
}

function fechaActualDatePickerPedido() {
    //PARA FECHA PEDIDO
    var fecha = new Date(); //Fecha actual
    var mes = fecha.getMonth() + 1; //obteniendo mes
    var dia = fecha.getDate(); //obteniendo dia
    var ano = fecha.getFullYear(); //obteniendo año
    if (dia < 10)
        dia = '0' + dia; //agrega cero si el menor de 10
    if (mes < 10)
        mes = '0' + mes //agrega cero si el menor de 10
    document.getElementById('fechaPedido').value = ano + "-" + mes + "-" + dia;
    datosClientePedido();
}



function obtenerDatosUltimoPedidoPendientePuntoVenta() {
    var parametros = {
        "puntoVenta": puntoVenta,
        "_token": $("meta[name='csrf-token']").attr("content")
    };
    //insercción pedido
    $.ajax({
        data: parametros,
        url: './obtenerUltimoOfertaPendiente',
        type: 'post',
        timeout: 2000,
        async: true,
        success: function(response) {
            $("#tabla-articulos").find(".listadoArticuloPedido").append(response);
        }
    })
}

function actualizarContadorPedido() {
    var parametros = {
        "seriePedido": $("#seriePedido").val(),
        "numeroDocumento": $("#codigoPedido").val(),
        "_token": $("meta[name='csrf-token']").attr("content")
    };
    $.ajax({
        data: parametros,
        url: './actualizarContadorOferta',
        type: 'post',
        timeout: 2000,
        async: true,
        success: function(response) {
            if (response = "OK") {
                alert('contador actualizado con éxito');
                $("#codigoPedido").val(response)
            } else {
                alert('ha ocurrido un error al actualizar el contador');
            }
        }
    })
}

/**
 *  métodos para ir guardando datos cada vez que se produzca un cambio en el body
 *  guardamos directamente en bbdd y recuperaremos el último
 */
/*function guardarUltimoProceso(){
    $(document.body).change(function (){
    insertarPedidoenBBDD();
    });
}*/
function guardarUltimoProceso2() {
    setTimeout(function() {
        insertarPedidoenBBDD();
    }, 2000);

}


function sleep(ms) {
    return new Promise(resolve => setTimeout(resolve, ms));
}

/**
 * MÉTODO PARA AGREGAR LÍNEA CON LOS DATOS DEL PRODUCTO SELECCIONADO
 * @param articulo
 */
 async function rellenarListadoArticuloPedido(articulo) {

    var codigoCliente = ($('#codigoCliente').val());
    var consultaTarifa = true;
    var parametros = {        
        "CodigoCliente": codigoCliente,
        "CodigoArticulo": articulo.CodigoArticulo,
        "_token": $("meta[name='csrf-token']").attr("content")
    };
    //console.log(parametros);
    $.ajax({
        data: parametros,
        url: './comprobarTarifasOferta',
        type: 'post',
        timeout: 2000,
        async: true,
        success:  function(response){
            //console.log(response);
            if(response == "" || isNaN(resonse[0])){
                consultaTarifa = false;
                console.log('false');
            }else{
                tarifasEspeciales = response[0];
                console.log('true');
            }            
        }        
    });

    
    await sleep(300);

    var tratamientoPartida = true;
    //comprobamos si el artículo tiene tratamiento de partida, en caso afirmativo buscamos las partidas y las fechas de caducidad
    var parametros = {
        "codigoArticulo": articulo.CodigoArticulo,
        "_token": $("meta[name='csrf-token']").attr("content")
    };
    $.ajax({
        data: parametros,
        url: './comprobarTratamientoPartidasOferta',
        type: 'post',
        timeout: 2000,
        async: true,
        success: function(response) {
            if (response[0].TratamientoPartidas == -1) {
                var parametros = {
                    "codigoArticulo": articulo.CodigoArticulo,
                    "_token": $("meta[name='csrf-token']").attr("content")
                };
                $.ajax({
                    data: parametros,
                    url: './obtenerPartidasArticuloOferta',
                    type: 'post',
                    timeout: 2000,
                    async: true,
                    success: function(response) {
                        console.log(response);                        
                        if (response == "") {
                            tratamientoPartida = false;
                        } else {
                            partidas = response;
                        }
                    }
                })
            } else {
                tratamientoPartida = false;
            }
        }
    })
           
    setTimeout(function() {
        $("#modalProductoPedido").css('display', 'none');
        $("#busquedaProductoPedido").val("");
        //var precioUnidadFinal = articulo.PrecioVenta - (descuento *articulo.PrecioVenta /100) + ;

        var cantidadProducto = $("#cantidadProducto").val();
        if(consultaTarifa == true){
            if(tarifasEspeciales.tipo == 'precio'){
                console.log(tarifasEspeciales.Precio);
                articulo.PrecioVenta = tarifasEspeciales.Precio;        
            }else{
                console.log(tarifasEspeciales.Descuento);
                descuento = tarifasEspeciales.Descuento;
            }
        }
        if (iva == "si") {
            switch (articulo.GrupoIva) {
                case 1:
                    ivaProducto = 0.21;
                    break;
                case 2:
                    ivaProducto = 0.10;
                    break;
                case 3:
                    ivaProducto = 0.05;
                    break;
                default:
                    ivaProducto = 0.21;
            }
        } else {
            ivaProducto = 0;
        }

        var importeNeto = articulo.PrecioVenta - (descuento * articulo.PrecioVenta / 100);

        var importeLiquido = importeNeto + (importeNeto * ivaProducto);

        console.log(importeNeto);
        console.log(importeLiquido);

        subTotal = (importeLiquido * cantidadProducto);

        var codigoArticulo = articulo.CodigoArticulo;
        var html = "";
    

        setTimeout(function() {        

            var partida;
            var fechaCaducidad;
            html += "<tr class='linea-terminada " + codigoArticulo + "'>" +
                "<td><button type='button' class='btn mr-2 text-danger eliminarProducto' onclick='eliminarProductoTabla(this.id)' id='" + codigoArticulo + "'><i class='fas fa-trash-alt\ ml-3'></i></button>" +
                codigoArticulo + "</td>" +
                "<td>" + articulo.DescripcionArticulo + "</td>" +
                "<td>";
            if (tratamientoPartida == true) {
                html += "<select  id='partida' name='partida'> ";
                for (let i = 0; i < partidas.length; i++) {

                    partida = partidas[0].Partida;
                    fechaCaducidad = partidas[0].FechaCaducidad;
                    html += "<option value='" + partidas[i].FechaCaducidad + "-" + partidas[i].Partida + "-" + codigoArticulo + "'>" + partidas[i].Partida + "</option>";

                }
                html += "</select>";
                html += "<input type='hidden' class='partida' id='" + codigoArticulo + "partida'  value='" + partida + "'/>" +
                    "<input type='hidden' class='fechaCaducidad' id='" + codigoArticulo + "fechacaducidad' value='" + fechaCaducidad + "'/>";
            } else {
                html += "No tiene partida";
                html += "<input type='hidden' class='partida' id='" + codigoArticulo + "partida'  value='0'/>" +
                    "<input type='hidden' class='fechaCaducidad' id='" + codigoArticulo + "fechacaducidad' value='0'/>";
            }
            html += "</td>" +
                "<td class='text-right'><input type='number' class='"+codigoArticulo+"pvp_articulo' name='pvpArticulo' id='pvpArticulo-"+codigoArticulo+"' value='"  + parseFloat(articulo.PrecioVenta, 2) + "' min='0' onchange='actualizarPvpProducto(this.id)' style='width:50px; border:none; text-align: right!important;'> </td>";
            // if(tarifasEspeciales.tipo == 'precio' || tarifasEspeciales == '' ){
            //     html += "<td><input type='number' class='" + codigoArticulo + "dto_articulo' name='descuentoArticulo' id='descuentoArticulo-" + codigoArticulo + "' value='0' min='0' max='100' onchange='actualizarDatosDescuentoProducto(this.id)'style='width:50px; border:none; text-align: right!important;'>%</td>";        
            // }else{
            //     html += "<td><input type='number' class='" + codigoArticulo + "dto_articulo' name='descuentoArticulo' id='descuentoArticulo-" + codigoArticulo + "' value='"+parseFloat(descuento,2)+"' min='0' max='100' onchange='actualizarDatosDescuentoProducto(this.id)' disabled style='width:50px; border:none; text-align: right!important;'>%</td>";
            // }

            if(tarifasEspeciales.tipo == 'familia'){
                 html += "<td><input type='number' class='" + codigoArticulo + "dto_articulo' name='descuentoArticulo' id='descuentoArticulo-" + codigoArticulo + "' value='"+parseFloat(descuento,2)+"' min='0' max='100' onchange='actualizarDatosDescuentoProducto(this.id)' disabled style='width:50px; border:none; text-align: right!important;'>%</td>";

            }else{
                 html += "<td><input type='number' class='" + codigoArticulo + "dto_articulo' name='descuentoArticulo' id='descuentoArticulo-" + codigoArticulo + "' value='0' min='0' max='100' onchange='actualizarDatosDescuentoProducto(this.id)'style='width:50px; border:none; text-align: right!important;'>%</td>";        

            }

            html += "<td class='text-right'>" + parseFloat(importeLiquido.toFixed(2)) + "</td>" +
                "<td><input type='number' class='" + codigoArticulo + "cantidadProducto' name='cantidadProducto' id='cantidadProducto-" + codigoArticulo + "' value='" + cantidadProducto + "' onchange='actualizarDatos2(this.id)' style='width:50px; border:none;'></td>" +
                "<td class='text-right'>" + subTotal.toFixed(2) + "</td>" +
                "<input type='hidden' id='" + codigoArticulo + "1' class='subTotal' value='" + subTotal.toFixed(2) + "'/>" +
                "<input type='hidden' class='codigoArticulo' value='" + codigoArticulo + "' />" +
                "<input type='hidden' class='precio' value='" + importeLiquido + "' />" +
                "<input type='hidden' id='" + codigoArticulo + "cantidad'' class='cantidad' value='" + cantidadProducto + "' />" +
                "<input type='hidden' class='descripcion' value='" + articulo.DescripcionArticulo + "' />" +
                "<input type='hidden' class='precioUnidad' value='" + parseFloat(articulo.PrecioVenta, 2) + "' />" +
                "<input type='hidden' class='descuento_cliente' id='descuento_cliente_pedido' value='" + parseFloat(descuento, 2) + "' />" +
                "<input type='hidden' class='descuento_articulo' id='" + codigoArticulo + "2' value='0' />" +
                "</tr>";

            $("#tabla-articulos").find(".listadoArticuloPedido").append(html);
            calcularTotalPedido();
        }, 1000);
    }, 300);

    
}

/*function cambiarDatosPartida(informacion){
    var datos = informacion.split("-");
    $("#"+datos[2]+"partida").val(datos[1]);
    $("#"+datos[2]+"fechacaducidad").val(datos[0]);




}*/

function actualizarPrecioDescuento() {
    descuentoEnvio = $("#descuentoProducto").val();
    valorEnvio = valorEnvio - (valorEnvio * $("#descuentoProducto").val() / 100);
    //$("#linea-envio").children("td")[8].innerText = valorEnvio;
    //$("#inputDescuentoEnvio").val(descuentoEnvio);
    //$("#envio").val(valorEnvio);
    //$(".subEnvio").val(valorEnvio);
    //actualizarPrecioEnvioBBDD();
    calcularTotalPedido();

}

function actualizarPrecioEnvioBBDD() {
    var parametros = {
        "seriePedido": $("#seriePedido").val(),
        "numeroDocumento": $("#codigoPedido").val(),
        "nuevaCantidad": $("#inputDescuentoEnvio").val(),
        "_token": $("meta[name='csrf-token']").attr("content")
    };
    //actualización en bbdd del porcentaje de envio
    $.ajax({
        data: parametros,
        url: './actualizarDescuentoOferta',
        type: 'post',
        timeout: 2000,
        async: true,
        success: function(response) {
            if (response = "OK") {
                //alert('descuento actualizado con éxito');
            } else {
                alert('ha ocurrido un error al actualizar la cantidad');
            }
        }
    })
}

/**
 * MÉTODO CON EL QUE ACTUALIZAMOS DATOS €€€ AL MODIFICAR LA CANTIDAD SOBRE LA FILA
 * @param codigoArticulo
 */
function actualizarDatos(codigoArticulo) {

    var codigoABuscar = codigoArticulo.split("-");
    var nuevaCantidad = $("." + codigoABuscar[1] + "cantidadProducto").val();
    var valorActual = $("." + codigoABuscar[1] + "pvp_articulo").val();
    var nuevoValor = valorActual * nuevaCantidad;
    subTotal = nuevoValor;

    $("#" + codigoABuscar[1] + "1").val(subTotal);
    $("." + codigoABuscar[1] + "").children("td")[7].innerText = subTotal.toFixed(2);
    $("#" + codigoABuscar[1] + "cantidad").val(nuevaCantidad);
    /**
     *
     */
    var nuevoDescuento = $("." + codigoABuscar[1] + "dto_articulo").val();
    var valorActual = $("." + codigoABuscar[1] + "pvp_articulo").val();


    calcularTotalPedido();

    if (nuevaCantidad > 0) {
        var parametros = {
            "seriePedido": $("#seriePedido").val(),
            "numeroDocumento": $("#codigoPedido").val(),
            "codigoProducto": codigoABuscar[1],
            "nuevaCantidad": nuevaCantidad,
            "precio": valorActual,
            "dtoCliente": descuento,
            "dtoArticulo": nuevoDescuento,
            "iva": ivaProducto,
            "_token": $("meta[name='csrf-token']").attr("content")
        };
        console.log(parametros);
        //actualización en bbdd de la cantidad de producto
        $.ajax({
            data: parametros,
            url: './actualizarCantidadOferta',
            type: 'post',
            timeout: 2000,
            async: true,
            success: function(response) {
                if (response = "OK") {
                    //alert('cantidad actualizada con éxito');
                } else {
                    alert('ha ocurrido un error al actualizar la cantidad');
                }
            }
        })
    }

}



function actualizarDatos2(codigoArticulo) {

    var descuento = 0;

    var codigoABuscar = codigoArticulo.split("-");
    var nuevaCantidad = $("." + codigoABuscar[1] + "cantidadProducto").val();
    descuento = $("." + codigoABuscar[1] + "dto_articulo").val();
    var valorActual = $("." + codigoABuscar[1] + "pvp_articulo").val();
    var nuevoValor = valorActual * nuevaCantidad;
    subTotal = nuevoValor;


    $("#" + codigoABuscar[1] + "1").val(subTotal);
    $("." + codigoABuscar[1] + "").children("td")[7].innerText = subTotal.toFixed(2);
    $("#" + codigoABuscar[1] + "cantidad").val(nuevaCantidad);
    /**
     *
     */
    var nuevoDescuento = $("." + codigoABuscar[1] + "dto_articulo").val();
    //var valorActual = $("." + codigoABuscar[1] + "").children("td")[3].innerText;


    calcularTotalPedido();

    if (nuevaCantidad > 0) {
        var parametros = {
            "seriePedido": $("#seriePedido").val(),
            "numeroDocumento": $("#codigoPedido").val(),
            "codigoProducto": codigoABuscar[1],
            "nuevaCantidad": nuevaCantidad,
            "precio": valorActual,
            "dtoCliente": descuento,
            "dtoArticulo": nuevoDescuento,
            "iva": ivaProducto,
            "_token": $("meta[name='csrf-token']").attr("content")
        };
        console.log(parametros);
        //actualización en bbdd de la cantidad de producto
        $.ajax({
            data: parametros,
            url: './actualizarCantidadOferta',
            type: 'post',
            timeout: 2000,
            async: true,
            success: function(response) {
                if (response = "OK") {
                    //alert('cantidad actualizada con éxito');
                } else {
                    alert('ha ocurrido un error al actualizar la cantidad');
                }
            }
        })
    }

}

/**
 * MÉTODO CON EL QUE ACTUALIZAMOS DATOS €€€ AL MODIFICAR EL DESCUENTO SOBRE EL ARTÍCULO
 * @param codigoArticulo
 */
function actualizarDatosDescuentoProducto(codigoArticulo) {
    
    var codigoABuscar = codigoArticulo.split("-");
    var cantidad = $("#" + codigoABuscar[1] + "cantidad").val();
    var nuevoDescuento = $("." + codigoABuscar[1] + "dto_articulo").val();
    var valorActual = $("." + codigoABuscar[1] + "pvp_articulo").val();
    console.log(valorActual);

    var valorDescuento = valorActual * (nuevoDescuento / 100);

    var valorConDescuentoIncluido = valorActual - valorDescuento;

    var descuentoCliente = valorConDescuentoIncluido - (valorConDescuentoIncluido * (descuento / 100));

    var valorConIva = descuentoCliente + (descuentoCliente * ivaProducto);

    $("." + codigoABuscar[1] + "").children("td")[5].innerText = valorConIva;
    $("." + codigoABuscar[1] + "").children("td")[7].innerText = valorConIva * cantidad;
    $("#" + codigoABuscar[1] + "1").val(valorConIva * cantidad);
    $("#" + codigoABuscar[1] + "2").val(nuevoDescuento);

    
    console.log(valorActual);
    console.log(valorDescuento);
    console.log(valorConDescuentoIncluido);
    console.log(valorConIva);
    console.log(valorConIva+'precio');
    console.log(descuentoCliente);

    calcularTotalPedido();

    if (nuevoDescuento > -1) {
        var parametros = {
            "seriePedido": $("#seriePedido").val(),
            "numeroDocumento": $("#codigoPedido").val(),
            "codigoProducto": codigoABuscar[1],
            "nuevaCantidad": cantidad,
            "precio": valorConIva,
            "nuevaCantidad": nuevoDescuento,
            "iva": ivaProducto,
            "_token": $("meta[name='csrf-token']").attr("content")

        };
        //actualización en bbdd de la cantidad de producto
        $.ajax({
            data: parametros,

            url: './actualizarDescuentoArticuloOferta',
            type: 'post',
            timeout: 2000,
            async: true,
            success: function(response) {
                if (response = "OK") {
                    //alert('cantidad actualizada con éxito');
                } else {
                    alert('ha ocurrido un error al actualizar la cantidad');
                }
            }
        })
    }

}


/**
 * MÉTODO CON EL QUE ACTUALIZAMOS DATOS €€€ AL MODIFICAR EL DESCUENTO SOBRE EL ARTÍCULO
 * @param codigoArticulo
 */
 function actualizarPvpProducto(codigoArticulo) {
    
    var codigoABuscar = codigoArticulo.split("-");
    var cantidad = $("#" + codigoABuscar[1] + "cantidad").val();
    var nuevoDescuento = $("." + codigoABuscar[1] + "dto_articulo").val();
    var valorActual = $("." + codigoABuscar[1] + "pvp_articulo").val();
    console.log(valorActual);

    var valorDescuento = valorActual * (nuevoDescuento / 100);

    var valorConDescuentoIncluido = valorActual - valorDescuento;

    var descuentoCliente = valorConDescuentoIncluido - (valorConDescuentoIncluido * (descuento / 100));

    var valorConIva = descuentoCliente + (descuentoCliente * ivaProducto);

    $("." + codigoABuscar[1] + "").children("td")[5].innerText = valorConIva;
    $("." + codigoABuscar[1] + "").children("td")[7].innerText = valorConIva * cantidad;
    $("#" + codigoABuscar[1] + "1").val(valorConIva * cantidad);
    $("#" + codigoABuscar[1] + "2").val(nuevoDescuento);

    
    console.log(valorActual);
    console.log(valorDescuento);
    console.log(valorConDescuentoIncluido);
    console.log(valorConIva);
    console.log(valorConIva+'precio');
    console.log(descuentoCliente);

    calcularTotalPedido();

    if (nuevoDescuento > -1) {
        var parametros = {
            "seriePedido": $("#seriePedido").val(),
            "numeroDocumento": $("#codigoPedido").val(),
            "codigoProducto": codigoABuscar[1],
            "nuevaCantidad": cantidad,
            "precio": valorConIva,
            "nuevaCantidad": nuevoDescuento,
            "iva": ivaProducto,
            "_token": $("meta[name='csrf-token']").attr("content")
        };
        //actualización en bbdd de la cantidad de producto
        $.ajax({
            data: parametros,
            
            url: './actualizarDescuentoArticuloOferta',
            type: 'post',
            timeout: 2000,
            async: true,
            success: function(response) {
                if (response = "OK") {
                    //alert('cantidad actualizada con éxito');
                } else {
                    alert('ha ocurrido un error al actualizar la cantidad');
                }
            }
        })
    }

}



/**
 * MÉTODO PARA ELIMINAR LÍNEA AL BORRAR PRODUCTO, ACTUALIZAMOS TODOS LOS DATOS
 * @param id
 */
function eliminarProductoTabla(id) {
    var dineroARestarProducto = $("." + id + "").children("td")[6].innerText;
    var parametros = {
        "codigoArticulo": id,
        "seriePedido": $("#seriePedido").val(),
        "numeroPedido": $("#codigoPedido").val(),
        "_token": $("meta[name='csrf-token']").attr("content")
    };
    //insercción pedido
    $.ajax({
        data: parametros,
        url: './eliminarLineaOferta',
        type: 'post',
        timeout: 2000,
        async: true,
        success: function(response) {
            if (response = "OK") {
                total = total - parseFloat(dineroARestarProducto);

                $("." + id + "").remove();

                if (!$(".linea-terminada")[0]) {
                    total = total - valorEnvio;
                    calcularTotalPedido();
                } else {
                    if (total > 60) {
                        //  $("#linea-envio").remove();
                        calcularTotalPedido();
                    }
                }
                $("#precioTotal").text(total.toFixed(2));
            } else {
                //alert(response);
            }
        }
    })


}

/**
 * MÉTODO PARA CALCULAR TOTAL €€ AL AÑADIR CADA ARTÍCULO
 */
function calcularTotalPedido() {
    total = 0;
    $(".linea-terminada").find(".subTotal").each(function() {
        console.log($(this).val());
        total += parseFloat($(this).val());
    });
    var html = "";

    $("#precioTotal").text(total.toFixed(2)+'€');
    

    if (observacionModal != "") {
        $("#imgAvisoObservacion").css('visibility', 'visible')
        $("#observacionesPedido").text(observacionModal);

    } else {
        $("#imgAvisoObservacion").css('visibility', 'hidden')
    }
}

/**
 * método con el que mostramos pagos realizados
 */
function procesarPedido() {
    $("#pagar").click(function() {
        $("#metodoPago").modal('show');
        if (pagosRealizados.length == 0) $("#alertaPagosRealizados").css('visibility', 'visible');
        if (comprobarEsDevolucion == true) {
            $("#divIndUnidadesDevolucion").css('display', 'block');
            pendientePago = totalDevolucion;
            console.log('devolucion');
        } else {
            console.log("no devolucion");
            console.log(total);
            pendientePago = total;
        }

        $("#pendientePago").text(parseFloat(pendientePago, 2).toFixed(2) + " €");
        $("#pagarRestante").click(function() {

                $("#cantidadPagada").val(parseFloat(pendientePago, 2).toFixed(2));
            })
            //llamada al método que nos va a permitir guardar los pagos y realizar el pedido
        procesarPagos();
    })
}

/**
 * Guardamos pagos que vamos realizando, cuando esté a 0  se realizará la insercción del pedido
 */
function procesarPagos() {
    $("#guardarPago").click(function() {
        var tipoPago = $("#tipoPago").find('option:selected').text().trim();
        pagosRealizados.push({ 'tipoPago': tipoPago, 'cantidad': $("#cantidadPagada").val() });
        $("#metodoPago").modal('hide');
        $("#metodoPago").modal('show');
        var html = "";
        if (pagosRealizados.length != 0) {
            $("#alertaPagosRealizados").css('visibility', 'hidden');
            $("#pagosRealizados").css('display', 'block');
            for (var i = 0; i < pagosRealizados.length; i++) {
                html = "<tr>" +
                    "<td>" + pagosRealizados[i]['tipoPago'] + "</td>" + "<td>" + pagosRealizados[i]['cantidad'] + " €</td>" + "</tr>"
            }
        }
        //actualizamos la cantidad que queda por pagar
        $("#pagosRealizados").append(html);
        pendientePago -= $("#cantidadPagada").val();

        $("#cantidadPagada").val("");
        $("#pendientePago").text(pendientePago.toFixed(2) + " €");
        //cuando no quede nada más por pagar se procederá a la insercción del pedido
        if (Math.round(pendientePago) == 0) {
            //si el pedido que vamos a realizar ha sido un borrador lo eliminaremos
            if (comprobarEsDevolucion == false) {
                insertarPagosenBBDD();
            } else {
                insertarDevolucionBBDD();
            }


        }
    })
}

function insertarDevolucionBBDD() {
    var datosDevolucion = obtenerDatosDevolucion();
    console.log(datosDevolucion);
    var parametros = {
            "pagos": pagosRealizados,
            "datos": datosDevolucion,
            /* "seriePedido": $("#seriePedido").val(),
             "numeroDocumento": $("#codigoPedido").val(),*/
            "_token": $("meta[name='csrf-token']").attr("content")
        }
        //insercción devolucion
    $.ajax({
        data: parametros,
        url: './realizarDevolucionOferta',
        type: 'post',
        timeout: 2000,
        async: true,
        success: function(response) {
            if (response = "OK") {
                $("#metodoPago").modal('hide');
                $("#pagar").attr('disabled', 'true');
                //$("#alertaEstadoPedido").css('visibility','visible');
            } else {
                alert('ha ocurrido un error al realizar el pedido');
            }
        }
    })

}

function insertarPagosenBBDD() {
    var datosPedido = obtenerDatosPedido();
    var serie;
    if ($("#seriePedido").val() == undefined) {
        serie = serieGlobal;
    } else {
        serie = $("#seriePedido").val();
    }
    var parametros = {
        "pagos": pagosRealizados,
        "seriePedido": serie,
        "numeroDocumento": $("#codigoPedido").val(),
        "datos": datosPedido,
        "_token": $("meta[name='csrf-token']").attr("content")
    };
    //insercción pedido
    $.ajax({
        data: parametros,
        url: './insercionPagosOferta',
        type: 'post',
        timeout: 2000,
        async: true,
        success: function(response) {
            if (response = "OK") {
                $("#metodoPago").modal('hide');
                $("#pagar").attr('disabled', 'true');
                $("#alertaEstadoPedido").css('visibility', 'visible');
            } else {
                alert('ha ocurrido un error al realizar el pedido');
            }
        }
    })
}

function insertarPedidoenBBDD() {
    //obtenemos todos los datos para realizar el pedido
    datosPedido = obtenerDatosPedido();
    console.log(datosPedido);
    var parametros = {
        "datos": datosPedido,
        "_token": $("meta[name='csrf-token']").attr("content")
    };

    //insercción pedido
    $.ajax({
        data: parametros,
        url: './insercionOferta',
        type: 'post',
        timeout: 2000,
        async: true,
        success: function(response) {
            if (response = "OK") {
                //alert('linea insertada correctamente');
            } else {
                alert('ha ocurrido un error al realizar el pedido');
            }
        }
    })
}

/**
 * método para obtener todos los datos que tenemos en nuestra vista que nos harán falta para
 * poder realizar insercción del pedido
 * @returns {{}}
 */
function obtenerDatosPedido() {
    //comprobamos si tenemos algún descuento del cliente
    //si viene vacío lo ponemos a 0 para evitar problemas en insercciones
    /*console.log(descuento);
    if(descuento == "" ) descuento = 0;*/
    //document.getElementById('inputDescuentoEnvio').value = descuentoEnvio;
    var datosPedido = {};
    datosPedido.lineas = [];
    if ($("#codigoPedido").val() == undefined) {
        datosPedido.idPedido = numeroPedidoGlobal;
    } else {
        datosPedido.idPedido = $("#codigoPedido").val();
    }
    if ($("#seriePedido").val() == undefined) {
        datosPedido.seriePedido = serieGlobal;
    } else {
        datosPedido.seriePedido = $("#seriePedido").val();
    }
    if ($("#codigoCliente").val() == undefined) {
        datosPedido.codigoCliente = codigoClienteGlobal;
    } else {
        datosPedido.codigoCliente = $("#codigoCliente").val();
    }

    datosPedido.observacionesPedido = $("#observacionesPedido").val();
    datosPedido.observacionesReparto = $("#observacionesReparto").val();
    datosPedido.total = total;
    datosPedido.iva = 21;
    datosPedido.importeBruto = 0;
    datosPedido.descuentoCliente = $("#descuento_cliente_pedido").val();
    datosPedido.descuentoLineas = 0;
    $(".linea-terminada").each(function() {
        var precio = parseFloat($(this).find($(".precioUnidad")).val());
        var linea = {};
        linea.codigo = $(this).find($(".codigoArticulo")).val();
        linea.descripcion = $(this).find($(".descripcion")).val();
        linea.cantidad = $(this).find($(".cantidad")).val();
        linea.precio = $(this).find($(".precioUnidad")).val();
        linea.descuento = $(this).find($(".descuento_articulo")).val();
        datosPedido.descuentoLineas += $(this).find($(".descuento_articulo")).val();
        linea.partida = "";
        linea.fechaCaducidad = 0;
        if ($(this).find($(".partida")).val() != 0) {
            linea.partida = $(this).find($(".partida")).val();
            linea.fechaCaducidad = $(this).find($(".fechaCaducidad")).val();
        }
        //linea.descuento =  $("#inputDescuentoEnvio").val();
        linea.total = $(this).find($(".precio")).val();
        datosPedido.importeBruto += precio;
        datosPedido.lineas.push(linea);
    });
    console.log(datosPedido);
    return datosPedido;
}

/**
 * método para mostrar modal devolución
 */
function procesarDevolución() {
    $("#devolver").click(function() {
        $("#devoluciones").modal("show");
        obtenerLineasDevolucionAlbaran();

    });
    $("#procederAdevolucion").click(function() {
        $("#devoluciones").modal("hide");
        comprobarEsDevolucion = true;
        $("#modalPinOperario").modal("show");
    })
}

/**
 * MÉTODO POR EL QUE OBTENEMOS LÍNEAS DE AQUELLOS PEDIDOS QUE TENGAN REALIZADA ALGUNA DEVOLUCIÓN
 */
function obtenerLineasDevolucionAlbaran() {
    $("#alertaDevoluciones").css('display', 'none');
    $("#lineasDevolucion").text("");
    $("#modal-dialog").removeClass('modal-xl');
    var parametros = {
        "serieAlbaran": serieGlobal,
        "numeroAlbaran": numeroPedidoGlobal,
        "ejercicioAlbaran": ejercicioAlbaranGlobal,
        "_token": $("meta[name='csrf-token']").attr("content")
    };
    $.ajax({
        data: parametros,
        url: './obtenerLineasDevolucionOferta',
        type: 'post',
        timeout: 3000,
        async: true,
        success: function(response) {
            if (response != "ERROR") {
                var html = "";
                var ejercicioAlbaran;
                var serieAlbaran;
                var numeroAlbaran;
                html = "<table class='table table-bordered'>" +
                    "<thead>" +
                    "<th>Artículo</th>" +
                    "<th>Unidades</th>" +
                    "<th>Fecha</th>" +
                    "</thead><tbody>";
                for (let i = 0; i < response.length; i++) {
                    html += "<td>" + response[i].CodigoArticulo + " - " + response[i].DescripcionArticulo + "</td>" +
                        "<td>" + -(response[i].Unidades) + "</td>" +
                        "<td>" + response[i].FechaRegistro + "</td>";
                    ejercicioAlbaran = response[i].EjercicioAlbaran;
                    serieAlbaran = response[i].SerieAlbaran;
                    numeroAlbaran = response[i].NumeroAlbaran
                }
                html += "</tbody></table>";
                $("#lineasDevolucion").css('display', 'block');
                $("#modal-dialog").addClass('modal-xl');
                $("#lineasDevolucion").append(html);
                comprobarUnidadesDevolucion(ejercicioAlbaran, serieAlbaran, numeroAlbaran);


            } else {
                $("#lineasDevolucion").css('display', 'none');
                $("#alertaDevoluciones").css('display', 'block');

            }
        }
    })
}

/**
 * método para comprobar si el albaran ha sido abonado por completo
 */
function comprobarUnidadesDevolucion(ejercicioAlbaran, serieAlbaran, numeroAlbaran) {
    var parametros = {
        "serieAlbaran": serieAlbaran,
        "numeroAlbaran": numeroAlbaran,
        "ejercicioAlbaran": ejercicioAlbaran,
        "_token": $("meta[name='csrf-token']").attr("content")
    };
    $.ajax({
        data: parametros,
        url: './comprobarUnidadesDevolucionOferta',
        type: 'post',
        timeout: 3000,
        async: true,
        success: function(response) {
            if (response == "ABONADOCOMPLETO") {
                $("#alertaDevolucionCompletada").css('display', 'block');
                $("#procederAdevolucion").addClass('disabled');
            } else {
                arrayUnidadesDevueltasArticulo = response;
                comprobarUnidadesQuedanPorDevolver(ejercicioAlbaranGlobal, serieGlobal, numeroPedidoGlobal);
            }

        }
    })
}

/**
 * Método con el que vamos a obtener las unidades totales del albarán original para compararlo con las del albarán de abono
 * @param ejercicioAlbaran
 * @param serieAlbaran
 * @param numeroAlbaran
 */
function comprobarUnidadesQuedanPorDevolver(ejercicioAlbaran, serieAlbaran, numeroAlbaran) {
    var parametros = {
        "serieAlbaran": serieAlbaran,
        "numeroAlbaran": numeroAlbaran,
        "ejercicioAlbaran": ejercicioAlbaran,
        "_token": $("meta[name='csrf-token']").attr("content")
    };
    $.ajax({
        data: parametros,
        url: './comprobarUnidadesQuedanPorDevolverOferta',
        type: 'post',
        timeout: 3000,
        async: true,
        success: function(response) {

            for (let i = 0; i <= response.length - 1; i++) {
                let j = 0;
                for (j = 0; j < arrayUnidadesDevueltasArticulo.length; j++) {
                    if (response[i].CodigoArticulo == arrayUnidadesDevueltasArticulo[j].CodigoArticulo) {
                        var unidadesRestantes = response[i].Unidades - arrayUnidadesDevueltasArticulo[j].UnidadesAbonadasLc;
                        console.log(unidadesRestantes);
                        if (unidadesRestantes == 0) {
                            $("#devolucionArticulo-" + response[i].CodigoArticulo + "").prop('readonly', true);
                        } else {

                            $("#devolucionArticulo-" + response[i].CodigoArticulo + "").attr('max', unidadesRestantes);
                            //$("#devolucionArticulo"+response[i].codigoArticulo+"").m
                        }
                    }
                }

            }


        }
    })
}

function salirModoDevolucion() {
    $("#btnCerrarDevolucion").click(function() {
        comprobarEsDevolucion = false;
        window.location.reload();
    });

}

/**
 * método para guardar pedido como borrador
 */
function guardarPedidoBorrador() {
    $("#btnBorrador").click(function() {
        $("#observaciones").modal('show');
        $("#observacionPedido").text("Especifique razones por las que el pedido no ha sido completado");
        $("#iconoInformacion").removeClass('info');
        $("#iconoInformacion").css('color', 'red');
        $("#realizarObservacion").click(function() {
            $("#observaciones").modal('hide');
            var datosPedido = obtenerDatosPedido();
            var parametros = {
                "datos": datosPedido,
                "_token": $("meta[name='csrf-token']").attr("content")
            };
            $.ajax({
                data: parametros,
                url: './guardarBorradorOferta',
                type: 'post',
                timeout: 3000,
                async: true,
                success: function(response) {}
            })
        });
    });
}

/**
 * Activamos modal observaciones
 */
function activarModalObservaciones() {
    $("#btnObservaciones").click(function() {
        $("#observaciones").modal('show');
        $("#observacionPedido").text("Esta observación será adjuntada a su pedido");
        $("#btnCerrarModalObservaciones").css('visibility', 'visible');
    });

}

/**
 * Mostramos datos del pedido seleccionado en la pantalla inicial del pedido
 * @param pedido
 */
function mostrarDatosPedidoEnVentana(pedido, ejercicio) {
    var pedido = pedido.split("/");
    var serie = pedido[0];
    var numPedido = pedido[1];

    var parametros = {
        "seriePedido": serie,
        "numeroPedido": numPedido,
        "ejercicioPedido": ejercicio,
        "_token": $("meta[name='csrf-token']").attr("content")
    };
    console.log(parametros);

    $.ajax({
        data: parametros,
        url: './obtenerDatosOferta',
        type: 'post',
        timeout: 2000,
        async: true,
        success: function(response) {
            console.log(response);
            $("#pedidoPendientesVTelefonica").modal('hide');
            $("#pedidosPendientes").modal('hide');
            $("#tabla-articulos").find(".listadoArticuloPedido").text("");
            var array = response;
            var html = "";
            for (let i = 0; i < array.length; i++) {
                if (array[i].CodigoArticulo == "PORTES") {
                    html += "<tr class='linea-terminada' id='linea-envio'>" +
                        "<td>ENVIO</td>" +
                        "<td>Agencia</td>" +
                        "<td></td>" +
                        "<td>" + parseFloat(array[i].Precio, 2).toFixed(2) + "</td>" +
                        "<td><input type='text' class='descuento' name='descuentoProducto' id='descuentoProducto' value='" + parseFloat(array[i].Descuento, 2).toFixed(0) + "' readonly min='0' max='100''></td>" +
                        "<td>" + parseFloat(array[i].Precio, 2).toFixed(2) + "</td>" +
                        "<td><input type='text' readonly class='cantidadDescuento' name='cantidadDescuento' id='cantidadDescuento' value='" + parseFloat(array[i].Unidades2_, 2).toFixed(0) + "' readonly></td>" +
                        "<td id='unidadesDevolucionEnvio'  style='display: none'><input type='number' id='devolucionArticulo-" + array[i].CodigoArticulo + "'  value='0' min='0' max='" + parseFloat(array[i].Unidades2_, 2).toFixed(0) + "'></td>" +
                        //subtotal
                        "<td id='inputSubTotalEnvio'><input type='hidden' id='envio' class='envio' name='envio' value='" + parseFloat(array[i].ImporteLiquido, 2).toFixed(2) + "'>" + parseFloat(array[i].ImporteLiquido, 2).toFixed(2) + "</td>" +
                        //subtotaldevolucion
                        "<input type='hidden' id='subTotal' class='subTotal subEnvio'  value='12'/>" +
                        "<input type='hidden' class='codigoArticulo' value='PORTES' />" +
                        "<input type='hidden' class='precio' value='" + parseFloat(array[i].ImporteLiquido, 2).toFixed(2) + "' />" +
                        "<input type='hidden' class='cantidad' value='1' />" +
                        "<input type='hidden' class='descripcion' value='Portes envio' />" +
                        "<input type='hidden' class='precioUnidad' value='" + parseFloat(array[i].ImporteLiquido, 2).toFixed(2) + "' />" +
                        "<input type='hidden' class='descuento_articulo' id='inputDescuentoEnvio' value='" + parseFloat(array[i].Descuento, 2).toFixed(0) + "' />" +
                        "</tr>";
                } else {
                    html += "<tr class='linea-terminada " + array[i].CodigoArticulo + "'>" +
                        "<td>" + array[i].CodigoArticulo + "</td>" +
                        "<td>" + array[i].DescripcionArticulo + "</td>" +
                        "<td>" + array[i].Partida + "</td>" +
                        "<td>" + parseFloat(array[i].Precio, 2).toFixed(2) + "</td>" +
                        "<td><input type='text' readonly class='" + array[i].CodigoArticulo + "dto_articulo' name='descuentoArticulo' id='descuentoArticulo-" + array[i].CodigoArticulo + "' value='" + parseFloat(array[i].Descuento, 2).toFixed(0) + "' min='0' max='100' onchange='actualizarDatosDescuentoProducto(this.id)'></td>" +
                        "<td>" + parseFloat(array[i].ImporteLiquido / array[i].Unidades2_, 2).toFixed(2) + "</td>" +
                        "<td><input type='text' readonly class='" + array[i].CodigoArticulo + "cantidadProducto' name='cantidadProducto' id='cantidadProducto-" + array[i].CodigoArticulo + "' value='" + parseFloat(array[i].Unidades2_, 2).toFixed(0) + "' onchange='actualizarDatos2(this.id)'></td>" +
                        "<td>" + parseFloat(array[i].ImporteLiquido, 2).toFixed(2) + "</td>" +
                        "<input type='hidden' id='" + array[i].CodigoArticulo + "subtotal' class='subTotal' value='" + array[i].ImporteLiquido + "'/>" +
                        "<input type='hidden' class='codigoArticulo' value='" + array[i].CodigoArticulo + "' />" +
                        "<input type='hidden' id='" + array[i].CodigoArticulo + "precio'' class='precio' value='" + parseFloat(array[i].Precio, 2).toFixed(2) + "' />" +
                        "<input type='hidden' id='" + array[i].CodigoArticulo + "cantidad'' class='cantidad' value='" + parseFloat(array[i].Unidades2_, 2).toFixed(0) + "' />" +
                        "<input type='hidden' class='descripcion' id='descripcion" + array[i].CodigoArticulo + "' value='" + array[i].DescripcionArticulo + "' />" +
                        "<input type='hidden' id='" + array[i].CodigoArticulo + "precioUnidad'' class='precioUnidad' value='" + parseFloat(array[i].ImporteLiquido / array[i].Unidades2_, 2).toFixed(2) + "' />" +
                        "<input type='hidden' class='descuento_cliente' id='descuento_cliente_pedido' value='" + parseFloat(descuento, 2) + "' />" +
                        "<input type='hidden' class='descuento_articulo' id='" + array[i].CodigoArticulo + "2' value='" + parseFloat(array[i].Descuento, 2).toFixed(0) + "' />" +
                        "</tr>";
                }
            }
            $("#tabla-articulos").find(".listadoArticuloPedido").append(html);
            // $("#devolver").removeClass("disabled");
            //$("#pagar").addClass("disabled");
            //$("#eliminar").addClass("disabled");
            // $("#precioTotal").text(parseFloat(response[0].importeLiquido).toFixed(2));
            $("#observacionesPedido").text(response[0].ObservacionesAlbaran);
            $("#observacionesReparto").text(response[0].ObservacionesFactura);
            $("#columnaCabeceraCodigo").text("");
            $("#columnaCabeceraCodigo").text(serie + "-" + numPedido);
            $("#codigoCliente").val(response[0].codigoCliente);            
            document.getElementById('datosCliente').innerHTML = response[0].RazonSocial + "<br>" + response[0].Telefono;
            $("#busquedaPedidos").val("");
            $("#rgpd").css('display', 'none');
            codigoClienteGlobal = response[0].codigoCliente;
            numeroPedidoGlobal = numPedido;
            serieGlobal = serie;
            ejercicioAlbaranGlobal = ejercicio;
            calcularTotalPedido();
        }
    })
}

/**
 * método para desplegar los distintos modales que tenemos de los pedidos
 */
function accionesPedidos() {
    $("#recuperarPedido").click(function() {
        $("#modalConsultaPedidos").modal('show');
    })
    $("#pedidosDia").click(function() {
        $("#pedidoDia").modal('show');
    })
    $("#pedidosAbiertos").click(function() {
        $("#pedidosPendientes").modal('show');
    })
    $("#pedidosAbiertosTlfn").click(function() {
        $("#pedidoPendientesVTelefonica").modal('show');
    })
    $("#pedidosMuestra").click(function() {
        $("#muestras").modal('show');
    })
}

function busquedaPedidos() {
    $('#busquedaPedidos').keyup(function() {
        console.log("hola");
        var datosPedido = $(this).val();
        var parametros = {
            "pedido": datosPedido,
            "_token": $("meta[name='csrf-token']").attr("content")
        };
        $.ajax({
            data: parametros,
            url: './buscadorOferta',
            type: 'post',
            timeout: 3000,
            async: true,
            success: function(response) {
                $("#modalBusquedaPedidos").css('display', 'inline');
                $(".salidaBuscadorPedidos").html(response);
            }
        })
    })

}

/**
 *método por el cual eliminamos pedido
 */
function borrarDatosPedido() {
    $("#eliminarPedido").click(function() {
        var datos = obtenerDatosPedido();
        var parametros = {
            "datos": datos,
            "numeroPedido": $("#codigoPedido").val(),
            "seriePedido": $("#seriePedido").val(),
            "_token": $("meta[name='csrf-token']").attr("content")
        };
        $.ajax({
            data: parametros,
            url: './borrarOferta',
            type: 'post',
            timeout: 2000,
            async: true,
            success: function(response) {

                if (response == "OK") {
                    //alert(response);
                    window.location.reload();
                } else {
                    alert(response);
                }

            }
        })
    })
}


/**
 * MÉTODO POR EL QUE OBTENEMOS UN ALBARAN Y LO MOSTRAMOS EN PANTALLA PARA REALIZAR DEVOLUCIONES
 * @param albaran
 */
function seleccionarAlbaran(albaran) {
    var parametros = {
        "serieAlbaran": albaran.serie,
        "numeroAlbaran": albaran.pedido,
        "ejercicioAlbaran": albaran.EjercicioAlbaran,
        "_token": $("meta[name='csrf-token']").attr("content")
    };

    $.ajax({
        data: parametros,
        url: './obtenerLineasOfertaSeleccionado',
        type: 'post',
        timeout: 2000,
        async: true,
        success: function(response) {
            $("#modalBusquedaPedidos").css('display', 'none');
            $("#modalConsultaPedidos").modal('hide');
            $("#tabla-articulos").find(".listadoArticuloPedido").text("");
            var array = response;
            var html = "";
            for (let i = 0; i < array.length; i++) {
                if (array[i].CodigoArticulo == "PORTES") {
                    html += "<tr class='linea-terminadaDevolucion' id='linea-envio'>" +
                        "<td>ENVIO</td>" +
                        "<td>Agencia</td>" +
                        "<td></td>" +
                        "<td>" + parseFloat(array[i].Precio, 2).toFixed(2) + "</td>" +
                        "<td><input type='text' class='descuento' name='descuentoProducto' id='descuentoProducto' value='" + parseFloat(array[i].Descuento, 2).toFixed(0) + "' readonly min='0' max='100''></td>" +
                        "<td>" + parseFloat(array[i].Precio, 2).toFixed(2) + "</td>" +
                        "<td><input type='text' readonly class='cantidadDescuento' name='cantidadDescuento' id='cantidadDescuento' value='" + parseFloat(array[i].Unidades, 2).toFixed(0) + "' readonly></td>" +
                        "<td id='unidadesDevolucionEnvio'  style='display: none'><input type='number' id='devolucionArticulo-" + array[i].CodigoArticulo + "'  value='0' min='0' max='" + parseFloat(array[i].Unidades, 2).toFixed(0) + "'></td>" +
                        //subtotal
                        "<td id='inputSubTotalEnvio'><input type='hidden' id='envio' class='envio' name='envio' value='" + parseFloat(array[i].ImporteLiquido, 2).toFixed(2) + "'>" + parseFloat(array[i].ImporteLiquido, 2).toFixed(2) + "</td>" +
                        //subtotaldevolucion
                        "<td id='totalDevolucionPedido' style='display: none'>0</td>" +
                        "<input type='hidden' id='subTotal' class='subTotalDevolucion subEnvio'  value='0'/>" +
                        "<input type='hidden' class='codigoArticulo' value='PORTES' />" +
                        "<input type='hidden' class='precio' value='" + parseFloat(array[i].ImporteLiquido, 2).toFixed(2) + "' />" +
                        "<input type='hidden' class='cantidad' value='1' />" +
                        "<input type='hidden' class='descripcion' value='Portes envio' />" +
                        "<input type='hidden' class='precioUnidad' value='" + parseFloat(array[i].ImporteLiquido, 2).toFixed(2) + "' />" +
                        "<input type='hidden' class='descuento_articulo' id='inputDescuentoEnvio' value='" + parseFloat(array[i].Descuento, 2).toFixed(0) + "' />" +
                        "</tr>";
                } else {
                    html += "<tr class='linea-terminadaDevolucion " + array[i].CodigoArticulo + "'>" +
                        "<td>" + array[i].CodigoArticulo + "</td>" +
                        "<td>" + array[i].DescripcionArticulo + "</td>" +
                        "<td>" + array[i].Partida + "</td>" +
                        "<td>" + parseFloat(array[i].Precio, 2).toFixed(2) + "</td>" +
                        "<td><input type='text' readonly class='" + array[i].CodigoArticulo + "dto_articulo' name='descuentoArticulo' id='descuentoArticulo-" + array[i].CodigoArticulo + "' value='" + parseFloat(array[i].Descuento, 2).toFixed(0) + "' min='0' max='100' onchange='actualizarDatosDescuentoProducto(this.id)'></td>" +
                        "<td>" + parseFloat(array[i].ImporteLiquido / array[i].Unidades, 2).toFixed(2) + "</td>" +
                        "<td><input type='text' readonly class='" + array[i].CodigoArticulo + "cantidadProducto' name='cantidadProducto' id='cantidadProducto-" + array[i].CodigoArticulo + "' value='" + parseFloat(array[i].Unidades, 2).toFixed(0) + "' onchange='actualizarDatos2(this.id)'></td>" +
                        "<td id='unidadesDevolucionArticulos-" + array[i].CodigoArticulo + "' class='udsDevolucionArticulo' style='display: none'><input type='number' id='devolucionArticulo-" + array[i].CodigoArticulo + "' onchange='actualizarPrecioDevolucion(this.id, this.value)' value='0' min='0' max='" + parseFloat(array[i].Unidades, 2).toFixed(0) + "'></td>" +
                        "<td>" + parseFloat(array[i].ImporteLiquido, 2).toFixed(2) + "</td>" +
                        "<td id='totalDevolucionArticulos-" + array[i].CodigoArticulo + "' class='ttlDevolucionArticulos' style='display: none'>0</td>" +

                        "<input type='hidden' id='" + array[i].CodigoArticulo + "subtotal' class='subTotalDevolucion' value='0'/>" +
                        "<input type='hidden' class='codigoArticulo' value='" + array[i].CodigoArticulo + "' />" +
                        "<input type='hidden' id='" + array[i].CodigoArticulo + "precio'' class='precio' value='" + parseFloat(array[i].Precio, 2).toFixed(2) + "' />" +
                        "<input type='hidden' id='" + array[i].CodigoArticulo + "cantidad'' class='cantidad' value='" + parseFloat(array[i].Unidades, 2).toFixed(0) + "' />" +
                        "<input type='hidden' class='descripcion' id='descripcion" + array[i].CodigoArticulo + "' value='" + array[i].DescripcionArticulo + "' />" +
                        "<input type='hidden' id='" + array[i].CodigoArticulo + "precioUnidad'' class='precioUnidad' value='" + parseFloat(array[i].ImporteLiquido / array[i].Unidades, 2).toFixed(2) + "' />" +
                        "<input type='hidden' class='descuento_cliente' id='descuento_cliente_pedido' value='" + parseFloat(descuento, 2) + "' />" +
                        "<input type='hidden' class='descuento_articulo' id='" + array[i].CodigoArticulo + "2' value='" + parseFloat(array[i].Descuento, 2).toFixed(0) + "' />" +
                        "</tr>";
                }
            }
            $("#tabla-articulos").find(".listadoArticuloPedido").append(html);
            $("#devolver").removeClass("disabled");
            $("#pagar").addClass("disabled");
            $("#eliminar").addClass("disabled");
            $("#precioTotal").text(parseFloat(albaran.importe).toFixed(2));
            $("#observacionesPedido").text(albaran.ObservacionesAlbaran);
            $("#observacionesReparto").text(albaran.ObservacionesFactura);
            $("#columnaCabeceraCodigo").text("");
            $("#columnaCabeceraCodigo").text(albaran.serie + "-" + albaran.pedido);
            document.getElementById('datosCliente').innerHTML = albaran.nombreCliente + "<br>" + albaran.telefono;
            $("#busquedaPedidos").val("");
            $("#rgpd").css('display', 'none');
            codigoClienteGlobal = albaran.CodigoCliente;
            numeroPedidoGlobal = albaran.pedido;
            serieGlobal = albaran.serie;
            ejercicioAlbaranGlobal = albaran.EjercicioAlbaran;
        }
    })
}

function actualizarPrecioDevolucion(idArticulo, unidades) {
    var codigoABuscar = idArticulo.split("-");
    var precioUnidad = $("#" + codigoABuscar[1] + "precioUnidad").val();
    var precioTotalDevolucion = precioUnidad * unidades;
    var descripcionArticulo = $("#descripcion" + codigoABuscar[1] + "").val();

    $('#totalDevolucionArticulos-' + codigoABuscar[1] + '').text(parseFloat(precioTotalDevolucion, 2).toFixed(2));
    $("#" + codigoABuscar[1] + "subtotal").val(precioTotalDevolucion);
    $("#" + codigoABuscar[1] + "cantidad").val(unidades);


    calcularTotalDevolucion();
    datosArticuloADevolver(codigoABuscar[1], unidades, descripcionArticulo);
    obtenerDatosDevolucion();
}

/**
 * MÉTODO PARA CALCULAR TOTAL DEVOLUCIÓN
 */
function calcularTotalDevolucion() {
    totalDevolucion = 0;
    $(".linea-terminadaDevolucion").find(".subTotalDevolucion").each(function() {

        totalDevolucion += parseFloat($(this).val());
    });

    $("#precioTotal").text(parseFloat(totalDevolucion, 2).toFixed(2));
}

/**
 * MÉTODO PARA MOSTRAR INFORMACIÓN DE UNIDADES A DEVOLVER
 * @param idArticulo
 */
function datosArticuloADevolver(idArticulo, unidades, descripcion) {
    //obtenemos los motivos de abono
    var parametros = {
        "_token": $("meta[name='csrf-token']").attr("content")
    };
    $.ajax({
        data: parametros,
        url: './obtenerMotivosAbonoOferta',
        type: 'post',
        timeout: 2000,
        async: true,
        success: function(response) {
            var html = "<div class='row'><strong>" + idArticulo + " " + descripcion + "</strong> " +
                "<select class='form-select' id='selectUnidadesDevolucion" + idArticulo + "' name='selectUnidadesDevolucion" + idArticulo + "'>";
            for (let i = 0; i <= unidades; i++) {
                html += "<option value='" + i + "'>" + i + "</option>";
            }
            html += "</select> <div class='row'>Motivo devolución <select id='selectMotivoDevolucion" + idArticulo + "' class='form-select' name='selectMotivoDevolucion" + idArticulo + "'>";
            for (let i = 0; i < response.length; i++) {
                html += "<option value='" + response[i].codigoMotivo + "'>" + response[i].motivo + "</option>";
            }
            html += "</div> " +
                "</div><br>";
            $("#ProductoUnidadDevuelta").append(html);
        }
    })

}