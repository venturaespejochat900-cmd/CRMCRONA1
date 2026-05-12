(function ($) {

    $(document).ready(function(){
        //PARA QUE UNA VEZ ENTREMOS A CUALQUIER PANTALLA SE COLLAPSE LA SIDEBAR
        $("body").addClass('sidebar-collapse');
       
        
        var configTratamientoPartidas = false;
        var configTratamientoTallaColores = false;
        var configPermitirStockNegativo = false;
        var configFormatoCodigoBarras = "";
        var configSeparador = "";
        // Variable global para guardar el foco actual. De incio se establece uno por defecto
        var tpvIdFocoActual = "tpvVistaArticulosBuscador";
        // Variable que se utiliza para guardar la orden a insertar las líneas
        var numeroOrden = 0;
        // Variable que informa si hay líneas en el desglose
        var hayLineasDesglose = false;
        // Variable global para guardar los artículos del ticket
        // let contenidoTicket = new Map();
        // https://www.digitalocean.com/community/tutorials/how-to-work-with-json-in-javascript       https://stackoverflow.com/questions/18884840/adding-a-new-array-element-to-a-json-object/18884871
        var tipoPantalla = "" // Si es 0 es NO TACTIL si es 1, es TACTIL
        var checkContadorCodigoTicketEstaVacio = false;
        var codigoCliente;


        // // Función para mostrar un alert que confirme la salida de la página
        // window.addEventListener('beforeunload', function (e) {
        //     // Cancel the event and show alert that
        //     // the unsaved changes would be lost
        //     e.preventDefault();
        //     e.returnValue = '';
        // });

        
        // Carga de la configuración
        var datos = new FormData();
        datos.append("cargarConfiguracion", "true");
        $.ajax({
            url:"controladores/configuracion.controlador.php",
            method: "POST",
            data: datos,
            cache: false,
            contentType: false,
            processData: false,
            dataType:"json",
            success:function(respuesta){
                // Si tiene tratamiento de talla y colores o no, se muestra o oculta selects
                if(respuesta["TratamientoTallasColores"] == 0){
                    // $("#tpvAnyadirArticuloTallaDiv").attr("hidden", true);
                    // $("#tpvAnyadirArticuloColorDiv").attr("hidden", true);
                    configTratamientoTallaColores = false;

                    $(".tablaPuntoVentaDesgloseTicketTratamientoTallaColores").attr("hidden", true);
                } else {
                    $("#tpvAnyadirArticuloTallaDiv").removeAttr("hidden");
                    $("#tpvAnyadirArticuloColorDiv").removeAttr("hidden");
                    configTratamientoTallaColores = true;

                    $(".tablaPuntoVentaDesgloseTicketTratamientoTallaColores").removeAttr("hidden");
                }

                // Si tiene tratamiento de partidas o no, se muestra o oculta inputs
                if(respuesta["TratamientoPartidas"] == 0){
                    configTratamientoPartidas = false;

                    $(".tablaPuntoVentaDesgloseTicketTratamientoPartidas").attr("hidden", true);
                } else {
                    // $("#tpvAnyadirArticuloPartidaDiv").removeAttr("hidden");
                    // $("#tpvAnyadirArticuloFechaCaducidadDiv").removeAttr("hidden");
                    configTratamientoPartidas = true;

                    $(".tablaPuntoVentaDesgloseTicketTratamientoPartidas").removeAttr("hidden");
                }

                // Si se permite el stock negativo o no
                if(respuesta["PermiteStockNegativo"] == 0){
                    configPermitirStockNegativo = false;
                } else {
                    configPermitirStockNegativo = true;
                }

                configFormatoCodigoBarras = respuesta["FormatoCodigoBarras"];
                configSeparador = respuesta["SeparadorCodigoBarras"];
                codigoCliente = respuesta["CodigoCliente"];
            }
        });

        // Carga del tipo de pantalla
        var datos = new FormData();
        datos.append("cargaTipoPantalla", "true");
        $.ajax({
            url:"controladores/puntoventa.controlador.php",
            method: "POST",
            data: datos,
            cache: false,
            contentType: false,
            processData: false,
            dataType:"json",
            success:function(respuesta){
                
                if(respuesta["TPVTactil"] == "0"){ // NO tactil
                    $("#tpvTactil").attr("hidden", "hidden");
                    $("#tpvNoTactil").removeAttr("hidden");
                    $("#tpvCodigoTicketSerie").focus();
                    tipoPantalla = respuesta["TPVTactil"];
                } else { // Tactil
                    $("#tpvTactil").removeAttr("hidden");
                    $("#tpvNoTactil").attr("hidden", "hidden");
                    $("#tpvTactilCodigoTicketSerie").focus();
                    tipoPantalla = respuesta["TPVTactil"];
                }

            }
        });

        // Carga inicial del cliente contado
        function sleep(ms) {
            return new Promise(resolve => setTimeout(resolve, ms));
          }
        sleep(1000).then(() => { 
            $("#tpvCodigoCliente,#tpvTactilCodigoCliente").val("");
            var datos = new FormData();

            datos.append("cargaInicialCliente", codigoCliente);
            $.ajax({
                url:"controladores/puntoventa.controlador.php",
                method: "POST",
                data: datos,
                cache: false,
                contentType: false,
                processData: false,
                dataType:"json",
                success:function(respuesta){
                    var nombre = " ";
                    var cif = " ";
                    var domicilio = " ";
                    var email = " ";
                    var telefono = " ";
                    if(respuesta.length != 0){
                        nombre = respuesta['Nombre'];
                        cif = respuesta['CifDni'];
                        domicilio = respuesta['Domicilio'];
                        telefono = respuesta['Telefono'];
                        email = respuesta['Email1'];
                    }
                    $("#tpvCodigoCliente,#tpvTactilCodigoCliente").val(codigoCliente);
                    $("#tpvNombreCliente,#tpvTactilNombreCliente").text("Nombre: "+nombre);
                    $("#tpvCifDniCliente,#tpvTactilCifDniCliente").text("CIF/DNI: "+cif);
                    $("#tpvDomicilioCliente,#tpvTactilDomicilioCliente").text("Domicilio: "+ domicilio);
                    $("#tpvEmailCliente,#tpvTactilEmailCliente").text("Email: "+ email);
                    $("#tpvTelefonoCliente,#tpvTactilTelefonoCliente").text("Telefono: "+ telefono);
    
                }
            });
        });
         

        // Carga de las series del ticket
        var datos = new FormData();
        datos.append("cargaSeriesTicket", "true");
        $.ajax({
            url:"controladores/pedidos.controlador.php",
            method: "POST",
            data: datos,
            cache: false,
            contentType: false,
            processData: false,
            dataType:"json",
            success:function(respuesta){
                let tpvCodigoTicketSerie = $("#tpvCodigoTicketSerie,#tpvTactilCodigoTicketSerie");
                tpvCodigoTicketSerie.empty();
                for (i = 0; i < respuesta.data.length; i++) { // Lo rellenamos con tantos options como colores tenga el artículo
                    var respuestaSerieTicket = respuesta.data[i][0];
                    var respuestaCodigoCanal = respuesta.data[i][1];
                    tpvCodigoTicketSerie.append("<option value='"+respuestaSerieTicket+"' CodigoCanal='"+respuestaCodigoCanal+"'>"+respuestaSerieTicket+"</option>");
                }
                cargarContadorTicketSegunSerie($("#tpvTactilCodigoTicketSerie").find(":selected").text(), true);
                // $("#tpvCodigoTicketSerie,#tpvTactilCodigoTicketSerie").val(respuesta["SerieTicket"]);
                // $("#tpvCodigoTicketNumero,#tpvTactilCodigoTicketNumero").val(respuesta["sysContadorValor"]);
            }
        });


        // Caarga del contador según la serie seleccionada
        // $("#tpvCodigoTicketSerie").on('blur', function(){ // No tactil
        //     cargarContadorTicketSegunSerie($("#tpvCodigoTicketSerie").find(":selected").text(), false);
        // });
        $("#tpvCodigoTicketSerie").on('change', function(){ // No tactil
            cargarContadorTicketSegunSerie($("#tpvCodigoTicketSerie").find(":selected").text(), true);
        });
        // $("#tpvTactilCodigoTicketSerie").on('blur', function(){ // Tactil
        //     cargarContadorTicketSegunSerie($("#tpvTactilCodigoTicketSerie").find(":selected").text(), false);
        // });
        $("#tpvTactilCodigoTicketSerie").on('change', function(){ // Tactil
            cargarContadorTicketSegunSerie($("#tpvTactilCodigoTicketSerie").find(":selected").text(), true);
        });
        function cargarContadorTicketSegunSerie(SerieTicket,CambioSelectSerie){
            if ($('#tpvCodigoTicketNumero,#tpvTactilCodigoTicketNumero').val() === "" || CambioSelectSerie) {
                var datos = new FormData();
                datos.append("cargarContadorTicketSegunSerie", "true");
                datos.append("SerieTicket", SerieTicket);
                $.ajax({
                    url:"controladores/pedidos.controlador.php",
                    method: "POST",
                    data: datos,
                    cache: false,
                    contentType: false,
                    processData: false,
                    dataType:"json",
                    success:function(respuesta){
                        $("#tpvCodigoTicketNumero,#tpvTactilCodigoTicketNumero").val(respuesta["sysContadorValor"]);
                        insercciónCabeceraDocumento();
                    }
                });
            }
        }



        /*=============================================
        APERTURA DE CAJA
        =============================================*/
        // Comprobamos primero si está abierta o no
        // var datos = new FormData();
        // datos.append("checkAperturaCaja", "true");
        // datos.append("FechaActual", $("#modalpedidosAperturaCajaReposicionFecha").val());
        // $.ajax({
        //     url:"controladores/pedidos.controlador.php",
        //     method: "POST",
        //     data: datos,
        //     cache: false,
        //     contentType: false,
        //     processData: false,
        //     dataType:"json",
        //     success:function(respuesta){

        //         // Si la caja está cerrada
        //         if(respuesta["CheckAperturaCaja"] == false) {

        //             // Mostramos el modal
        //             $('#modalpedidosAperturaCajaReposicion').modal('show');
        //             $("#modalpedidosAperturaCajaReposicionImporte").val(respuesta["Importe"]);
        //             tpvIdFocoActual = "modalpedidosAperturaCajaReposicionImporte";

        //             // Las siguientes líneas es para la comprobación dinámica del Importe, si hay o no,
        //             // se activará o no el btn de Aceptar
        //             $("#modalpedidosAperturaCajaReposicionAceptar").attr("disabled", "disabled");
        //             if($("#modalpedidosAperturaCajaReposicionImporte").val() == ""){
        //                 $("#modalpedidosAperturaCajaReposicionAceptar").attr("disabled", "disabled");
        //             } else {
        //                 $("#modalpedidosAperturaCajaReposicionAceptar").removeAttr("disabled");
        //             }
        //             $("#modalpedidosAperturaCajaReposicionImporte").on('input', function(){
        //                 if($("#modalpedidosAperturaCajaReposicionImporte").val() == ""){
        //                     $("#modalpedidosAperturaCajaReposicionAceptar").attr("disabled", "disabled");
        //                 } else {
        //                     $("#modalpedidosAperturaCajaReposicionAceptar").removeAttr("disabled");
        //                 }
        //             });
        //         }



        //     }
        // });

        // // Abrimos la caja
        // // Nos conectamos con la BBDD para registrar la apertura
        // $("#modalpedidosAperturaCajaReposicionAceptar").on('click', function(){

        //     var datos = new FormData();
        //     datos.append("aperturaCajaTPV", "true");
        //     datos.append("FechaActual", $("#modalpedidosAperturaCajaReposicionFecha").val());
        //     datos.append("Importe", $("#modalpedidosAperturaCajaReposicionImporte").val());
        //     $.ajax({
        //         url:"controladores/pedidos.controlador.php",
        //         method: "POST",
        //         data: datos,
        //         cache: false,
        //         contentType: false,
        //         processData: false,
        //         dataType:"json",
        //         success:function(respuesta){

        //             if(respuesta["CheckApertura"] == true){
        //                 $("#modalpedidosAperturaCajaReposicion").modal('hide');
        //                 var today = new Date().toLocaleString();
        //                 swal({
        //                     type: "success",
        //                     title: "La caja se ha abierto a las " + today + " con un saldo inicial de " + $("#modalpedidosAperturaCajaReposicionImporte").val() + "€",
        //                     showConfirmButton: true,
        //                     confirmButtonText: "Cerrar",
        //                     allowOutsideClick: false
        //                 })
        //             } else {
        //                 $("#modalpedidosAperturaCajaReposicionMensajeInformativo").text("Error en la apertura de caja. Posible fallo con la conexión de la base de datos.");
        //             }

        //         }
        //     });

        // });

        // Cuando se introduce un contador manualmente
        $("#tpvCodigoTicketNumero").on("change", function(){

            console.log($("#tpvCodigoTicketNumero").val());

            var datos = new FormData();
            datos.append("comprobarContadorIntroducidoManualmente", "true");
            datos.append("EjercicioDocumento", $("#tpvCodigoTicketEjercicio").val());
            datos.append("SerieDocumento", $("#tpvCodigoTicketSerie").val());
            datos.append("NumeroDocumento", $("#tpvCodigoTicketNumero").val());
            $.ajax({
                url:"controladores/pedidos.controlador.php",
                method: "POST",
                data: datos,
                cache: false,
                contentType: false,
                processData: false,
                dataType:"json",
                success:function(respuesta){

                    if(respuesta["ExisteCabecera"]){
                        swal({
                            type: "error",
                            title: "Error - El número introducido ya está asignado",
                            showConfirmButton: true,
                            confirmButtonText: "Cerrar",
                            allowOutsideClick: false
                        })
                    } else {
                        insercciónCabeceraDocumento();
                    }

                }
            });


        });


        /*=============================================
        INSERCCIÓN DE LA CABECERA DOCUMENTO
        =============================================*/
        async function  insercciónCabeceraDocumento() {
            await sleep(1000); // Dormimos la función 1 seg para que pueda coger el número del ticket a tiempo
            var datos = new FormData();
            if(tipoPantalla == 0){ // No tactil
                datos.append("SerieDocumento", $("#tpvCodigoTicketSerie").find(":selected").val());
                datos.append("CanalSerieDocumento", $("#tpvCodigoTicketSerie").find(":selected").attr("codigocanal"));
                datos.append("NumeroDocumento", $("#tpvCodigoTicketNumero").val());
                datos.append("FechaPedido", $("#fechaPedido").val());
                //datos.append()
                // console.log($("#tpvCodigoTicketSerie").find(":selected").attr("codigocanal"));
                // console.log($("#tpvCodigoTicketSerie").find(":selected").val());
                // console.log(datos.get("SerieDocumento"));
            } else { // Tactil
                datos.append("SerieDocumento", $("#tpvTactilCodigoTicketSerie").find(":selected").val());
                datos.append("CanalSerieDocumento", $("#tpvTactilCodigoTicketSerie").find(":selected").attr("codigocanal"));
                datos.append("NumeroDocumento", $("#tpvTactilCodigoTicketNumero").val());
                datos.append("FechaPedido", $("#fechaPedido").val());
            }

            
            datos.append("inserccionCabeceraDocumento", $("#tpvCodigoCliente,#tpvTactilCodigoCliente").val());
            $.ajax({
                url:"controladores/pedidos.controlador.php",
                method: "POST",
                data: datos,
                cache: false,
                contentType: false,
                processData: false,
                dataType:"json",
                success:function(respuesta){
                    if(respuesta["CheckInsertado"] == true){
                        $("#tpvTactilRecuperarTicketBtn,#tpvRecuperarTicketBtn, #btnRecuperarPedidoFinalizado").removeAttr("disabled");
                    } else {
                        swal({
                            type: "error",
                            title: "Error - No se ha podido insertar la cabecera. Posible error en la conexión con el servidor",
                            showConfirmButton: true,
                            confirmButtonText: "Cerrar",
                            allowOutsideClick: false
                        })
                    }

                }
            });
        }
        /*=============================================
        FIN || INSERCCIÓN DE LA CABECERA DOCUMENTO
        =============================================*/




        /*=============================================
        LISTADO DE MODAL LISTA CLIENTES
        =============================================*/
        $('.tablaPuntoVentaListaClientes').DataTable( {
            "ajax": "controladores/datatable.puntoventa-listaclientes.php",
            "deferRender": true,
            "retrieve": true,
            "processing": true,
            "language": {

                "sProcessing":     "Procesando...",
                "sLengthMenu":     "Mostrar _MENU_ registros",
                "sZeroRecords":    "No se encontraron resultados",
                "sEmptyTable":     "Ningún dato disponible en esta tabla",
                "sInfo":           "Mostrando registros del _START_ al _END_ de un total de _TOTAL_",
                "sInfoEmpty":      "Mostrando registros del 0 al 0 de un total de 0",
                "sInfoFiltered":   "(filtrado de un total de _MAX_ registros)",
                "sInfoPostFix":    "",
                "sSearch":         "Buscar:",
                "sUrl":            "",
                "sInfoThousands":  ",",
                "sLoadingRecords": "Cargando...",
                "oPaginate": {
                    "sFirst":    "Primero",
                    "sLast":     "Último",
                    "sNext":     "Siguiente",
                    "sPrevious": "Anterior"
                },
                "oAria": {
                    "sSortAscending":  ": Activar para ordenar la columna de manera ascendente",
                    "sSortDescending": ": Activar para ordenar la columna de manera descendente"
                }

            }

        } );


        /*=============================================
        LISTADO CLIENTES
        =============================================*/
        // Al hacer click en la lupa del Código Cliente muestro el modal con el listado de las naciones
        $("#tpvCodigoClienteBtn,#tpvTactilCodigoClienteBtn").on("click", function(){
            $('#modalPuntoVentaListaClientes').modal('show');
        });

        // Al hacer click en una fila y en Selecionar, cargamos el código en el input, ocultamos el modal y hacemos focus en el input
        // También se hace algo parecido al hacer doble click
        $('.tablaPuntoVentaListaClientes tbody').on('click', 'tr', function () {
            var data = $('.tablaPuntoVentaListaClientes').DataTable().row( this ).data();

            $("#modalPuntoVentaListaClientesSeleccionar").on('click', function(){
                $("#tpvCodigoCliente,#tpvTactilCodigoCliente").val(data[0]);
                $("#tpvNombreCliente,#tpvTactilNombreCliente").text("Nombre: "+data[2]);
                $("#tpvCifDniCliente,#tpvTactilCifDniCliente").text("CIF/DNI: "+data[1]);
                $("#tpvDomicilioCliente,#tpvTactilDomicilioCliente").text("Domicilio: "+data[3]);
                $("#tpvEmailCliente,#tpvTactilEmailCliente").text("Email: "+data[4]);
                $("#tpvTelefonoCliente,#tpvTactilTelefonoCliente").text("Telefono: "+data[5]);
                $("#modalPuntoVentaListaClientes").modal('hide');
                $("#tpvCodigoCliente,#tpvTactilCodigoCliente").focus();

                // Cargamos el contador
                if(tipoPantalla == "0"){ // Vista no táctil
                    if($("#tpvCodigoTicketNumero").val() == ""){ // Comprobar si está vacío
                        cargarContadorTicketSegunSerie($("#tpvCodigoTicketSerie").find(":selected").text(), true);
                    }
                } else {
                    if($("#tpvTactilCodigoTicketNumero").val() == ""){
                        cargarContadorTicketSegunSerie($("#tpvTactilCodigoTicketSerie").find(":selected").text(), true);
                    }
                }

                // Actualizamos el CodigoCliente del ticket en la BBDD (CabeceraDocumento)
                actualizarCodigoClienteEnTicket(data[0]);
            })
        });
        $('.tablaPuntoVentaListaClientes tbody').on('dblclick', 'tr', function () {
            var data = $('.tablaPuntoVentaListaClientes').DataTable().row( this ).data();
            $("#tpvCodigoCliente,#tpvTactilCodigoCliente").val(data[0]);
            $("#tpvNombreCliente,#tpvTactilNombreCliente").text("Nombre: "+data[2]);
            $("#tpvCifDniCliente,#tpvTactilCifDniCliente").text("CIF/DNI: "+data[1]);
            $("#tpvDomicilioCliente,#tpvTactilDomicilioCliente").text("Domicilio: "+data[3]);
            $("#tpvEmailCliente,#tpvTactilEmailCliente").text("Email: "+data[4]);
            $("#tpvTelefonoCliente,#tpvTactilTelefonoCliente").text("Telefono: "+data[5]);
            $("#modalPuntoVentaListaClientes").modal('hide');
            $("#tpvCodigoCliente,#tpvTactilCodigoCliente").focus();

            // Cargamos el contador
            if(tipoPantalla == "0"){ // Vista no táctil
                if($("#tpvCodigoTicketNumero").val() == ""){ // Comprobar si está vacío
                    cargarContadorTicketSegunSerie($("#tpvCodigoTicketSerie").find(":selected").text(), true);
                }
            } else {
                if($("#tpvTactilCodigoTicketNumero").val() == ""){
                    cargarContadorTicketSegunSerie($("#tpvTactilCodigoTicketSerie").find(":selected").text(), true);
                }
            }

            // Actualizamos el CodigoCliente del ticket en la BBDD (CabeceraDocumento)
            actualizarCodigoClienteEnTicket(data[0]);
        });
        /*=============================================
        FIN || LISTADO CLIENTES
        =============================================*/




        /*=============================================
        Función para actualizar el código cliente en el ticket (CabeceraDocumento)
        =============================================*/
        function actualizarCodigoClienteEnTicket(CodigoCliente){

            var datos = new FormData();
            datos.append("actualizarCodigoClienteEnTicketTPV", "true");
            datos.append("EjercicioDocumento", $("#tpvCodigoTicketEjercicio").val());
            datos.append("SerieDocumento", $("#tpvCodigoTicketSerie").val());
            datos.append("NumeroDocumento", $("#tpvCodigoTicketNumero").val());
            datos.append("CodigoCliente", CodigoCliente);
            $.ajax({
                url:"controladores/pedidos.controlador.php",
                method: "POST",
                data: datos,
                cache: false,
                contentType: false,
                processData: false,
                dataType:"json",
                success:function(respuesta){
                    if(respuesta["CheckUpdate"] == true){
                        console.log("Cliente actualizado");
                    }
                }
            });
        }




        /*=============================================
        CAJA BUSCADOR DESCRIPCION || NO TÁCTIL
        =============================================*/
        $("#tpvAnyadirArticuloDescripcion").keyup(function(){

            if($(this).val() != ""){
                var datos = new FormData();
                datos.append("buscadorDescripcion", $(this).val());
                datos.append("DescripcionArticulo", $(this).val());
                $.ajax({
                    url:"controladores/articulos.controlador.php",
                    method: "POST",
                    data: datos,
                    cache: false,
                    contentType: false,
                    processData: false,
                    dataType:"json",
                    success: function(respuesta){
                        var resCodigoArticulo;
                        var resDescripcionArticulo;
                        $("#descripResultado-box").show();

                        $("#descripcionArticulo-list").empty();

                        for (i = 0; i < respuesta.data.length; i++) {
                            resCodigoArticulo = respuesta.data[i][0];
                            resDescripcionArticulo = respuesta.data[i][1];
                            //$("#descripcionArticulo-list").append("<li onClick='selectDescripcionArticulo("+resCodigoArticulo+","+resDescripcionArticulo+")'>"+resDescripcionArticulo+"</li>");
                            $("#descripcionArticulo-list").append("<li id='"+resCodigoArticulo+"'>"+resDescripcionArticulo+"</li>");

                            $("#"+resCodigoArticulo+"").click( function(){
                                selectDescripcionArticulo($(this).attr("id"),$(this).text());
                            });
                            //console.log(selectDescripcionArticulo(resCodigoArticulo,resDescripcionArticulo));
                        }
                    }
                });
            } else {
                $("#tpvAnyadirArticuloCodigo").val("");
                $("#tpvAnyadirArticuloDescripcion").val("");
                $("#tpvAnyadirArticuloImagen").removeAttr("src");
                $("#tpvAnyadirArticuloUnidades").val("");
                $("#tpvAnyadirArticuloPartida").attr("readonly", "readonly");
                $("#tpvAnyadirArticuloPrecio").val("");
                $("#tpvAnyadirArticuloDescuento").val("");
                $("#tpvAnyadirArticuloFechaCaducidad").attr("readonly", "readonly");
                $("#tpvAnyadirArticuloBtnAnyadir").attr("disabled", "disabled");
                $("#descripcionArticulo-list").empty();
                //$("#descripResultado-box").hide();
                $("#descripResultado-box").hidden = true;
            }

        });

        function selectDescripcionArticulo(resCodigoArticulo, resDescripcionArticulo) {
            //va a metodo ctrMostrarArticuloPorIDSQLS
            $("#tpvAnyadirArticuloCodigo").val(resCodigoArticulo);
            var datos = new FormData();
            datos.append("idArticulo", resCodigoArticulo);
            $.ajax({
                url:"controladores/articulos.controlador.php",
                method: "POST",
                data: datos,
                cache: false,
                contentType: false,
                processData: false,
                dataType:"json",
                success:function(respuesta){
                    console.log("1");

                    if(typeof respuesta["DescripcionArticulo"] != "undefined"){
                        $("#tpvAnyadirArticuloDescripcion").val(respuesta["DescripcionArticulo"]);
                        $("#tpvAnyadirArticuloImagen").attr("src","controladores/imagen_mostrar.php?ImagenExt="+respuesta["ImagenExt"]);
                        $("#tpvAnyadirArticuloUnidades").val("1");
                        if(typeof respuesta["TratamientoPartidas"] !== 'undefined' || respuesta["TratamientoPartidas"] == "0"){
                            $("#tpvAnyadirArticuloPartida").attr("readonly", "readonly");
                            $("#tpvAnyadirArticuloFechaCaducidad").attr("readonly", "readonly");
                        } else {
                            $("#tpvAnyadirArticuloPartida").removeAttr("readonly");
                            $("#tpvAnyadirArticuloFechaCaducidad").removeAttr("readonly");
                        }
                        obtenerColoresSegunArticulo(respuesta["CodigoArticulo"]);
                        var respuestaPrecioVenta = 0; // Esta variable y el if es para controlar el cómo se muestra el precio de venta
                        if(respuesta["PrecioVenta"] == .0000000000){
                            respuestaPrecioVenta = parseFloat(0.0000000000);
                        } else {
                            respuestaPrecioVenta = parseFloat(respuesta["PrecioVenta"]);
                        }
                        // obtenerTarifa(respuesta["CodigoArticulo"],$("#tpvTactilCodigoCliente").val());
                        $("#tpvAnyadirArticuloPrecio").val(respuestaPrecioVenta.toFixed(2));
                        if(respuesta["Descuento"] == .0000000000){
                            $("#tpvAnyadirArticuloDescuento").val("0");
                        }else{
                            $("#tpvAnyadirArticuloDescuento").val(respuesta["Descuento"]);
                        }

                        $("#tpvAnyadirArticuloBtnAnyadir").removeAttr("disabled");

                        if(typeof respuesta["CodigoColor_"] != "undefined"){ // Aquí entrará cuando se introduzca un CódigoAlternativo (para la pistola, con talla y colores)
                            $("#tpvAnyadirArticuloColor").val(respuesta["CodigoColor_"]);
                            $("#tpvAnyadirArticuloTalla").val(respuesta["CodigoTalla01_"]);
                            console.log($("#tpvAnyadirArticuloColor").val());
                            console.log(respuesta["CodigoTalla01_"]);
                        }
                        $("#descripResultado-box").hide();
                    } else { // Si está vacío, limpiará el formulario
                        $("#tpvAnyadirArticuloDescripcion").val("");
                        $("#tpvAnyadirArticuloImagen").removeAttr("src");
                        $("#tpvAnyadirArticuloUnidades").val("");
                        $("#tpvAnyadirArticuloPartida").attr("readonly", "readonly");
                        $("#tpvAnyadirArticuloPrecio").val("");
                        $("#tpvAnyadirArticuloDescuento").val("");
                        $("#tpvAnyadirArticuloFechaCaducidad").attr("readonly", "readonly");
                        $("#tpvAnyadirArticuloBtnAnyadir").attr("disabled", "disabled");
                        $("#descripResultado-box").hide();
                    }


                }
            });
            $("#descripResultado-box").hide();
        }





        /*=============================================
        FIN || CAJA BUSCADOR REF/EAN || NO TÁCTIL
        =============================================*/





        /*=============================================
        CARGA Y CONTROL DE LAS FAMILIAS || FILTRO DE ARTÍCULOS SEGÚN FAMILIA
        =============================================*/
        var datos = new FormData();
        datos.append("cargaFamiliasTPV", "true");
        $.ajax({
            url:"controladores/pedidos.controlador.php",
            method: "POST",
            data: datos,
            cache: false,
            contentType: false,
            processData: false,
            dataType:"json",
            success:function(respuesta){

                for (i = 0; i < respuesta.data.length; i++) {

                    // Muestro las familias en el panel del puesto de venta
                    var idFamiliaTPV = "familiaTPV"+respuesta.data[i][0];

                    // Construimos el 'cuadrado' para mostar la familia
                    let tpvPanelVistaFamilias = $("#tpvPanelVistaFamilias");
                    tpvPanelVistaFamilias.append("<div class='btn btn-app' id='"+idFamiliaTPV+"'>"+
                        "<img src='' class='pruebaImagenPuntoVenta'></img>"+
                        "<p id='familiatpvcodigo'>"+respuesta.data[i][0]+"</p>"+
                        "<p id='familiatpvdescripcion'>"+respuesta.data[i][1]+"</p>"+
                        "</div>");

                    $("#"+idFamiliaTPV).on("click",function(){

                        // Limpiamos la vista de los artículos
                        let tpvPanelVistaArticulos = $("#tpvPanelVistaArticulos");
                        tpvPanelVistaArticulos.empty();

                        var datos = new FormData();
                        datos.append("cargaArticulosSegunFamiliaTPV", $(this).children('#familiatpvcodigo').text());
                        $.ajax({
                            url:"controladores/pedidos.controlador.php",
                            method: "POST",
                            data: datos,
                            cache: false,
                            contentType: false,
                            processData: false,
                            dataType:"json",
                            success:function(respuesta){


                                for (i = 0; i < respuesta.data.length; i++) {

                                    // Muestro los artículos en el panel del puesto de venta
                                    //console.log(respuesta.data[i][0]); // CodigoArticulo
                                    var idArticuloTPV = "articuloTPV"+respuesta.data[i][0]; // Variable para poder identificar cada registro. Se usa más adelante
                                    var respuestaPrecioVenta = 0; // Esta variable y el if es para controlar el cómo se muestra el precio de venta
                                    if(respuesta.data[i][2] == .0000000000){
                                        respuestaPrecioVenta = parseFloat(0.0000000000);
                                    } else {
                                        respuestaPrecioVenta = parseFloat(respuesta.data[i][2]);
                                    }
                                    var respuestaDescuento = 0; // Esta variable y el if es para controlar el cómo se muestra el descuento
                                    if(respuesta.data[i][3] == .0000000000){
                                        respuestaDescuento = parseFloat(0.0000000000);
                                    } else {
                                        respuestaDescuento = parseFloat(respuesta.data[i][3]);
                                    }
                                    var respuestaImagenExt = respuesta.data[i][4];

                                    // Construimos el 'cuadrado' para mostar el artículo
                                    let tpvPanelVistaArticulos = $("#tpvPanelVistaArticulos");
                                    tpvPanelVistaArticulos.append("<div class='btn btn-app' id='"+idArticuloTPV+"'>"+
                                        "<img src='controladores/imagen_mostrar.php?ImagenExt="+respuestaImagenExt+" class='pruebaImagenpedidos' style='position: absolute; top: 2px; left: 10px; width: 100px; opacity: 0.25;'></img>"+
                                        "<p id='articulotpvcodigo'>"+respuesta.data[i][0]+"</p>"+
                                        "<p id='articulotpvdescripcion'>"+respuesta.data[i][1]+"</p>"+
                                        "<p id='articulotpvprecioventa' class='ocultar'>"+respuestaPrecioVenta+"</p>"+
                                        "<p id='articulotpvdescuento' class='ocultar'>"+respuestaDescuento+"</p>"+
                                        "</div>");

                                    $("#"+idArticuloTPV).on("click",function(){
                                        // console.log($(this).attr('id'));
                                        // console.log($(this).children('#articulotpvdescripcion').text());

                                        // Se calcula el descuento si lo tuviera
                                        var precioVenta = $(this).children('#articulotpvprecioventa').text();
                                        var descuento = $(this).children('#articulotpvdescuento').text() / 100;
                                        var precioTotal = precioVenta - (precioVenta * descuento);

                                        // Contruimos el "formulario" de la confirmación de la selección del artículo
                                        let tpvPanelPreConfirmacionArticulo = $("#tpvPanelPreConfirmacionArticulo");
                                        tpvPanelPreConfirmacionArticulo.empty(); // Borramos el contenido del div por si hubiera un artículo seleccionado. Esto es por si se selecciona otro
                                        tpvPanelPreConfirmacionArticulo.append("<p id='tpvPanelPreConfirmacionArticuloCodigo'>"+$(this).attr('id').replace('articuloTPV','')+"</p>"+
                                            "<p><b id='tpvPanelPreConfirmacionArticuloDescripcion'>"+$(this).children('#articulotpvdescripcion').text()+"</b></p>");

                                        if(configTratamientoPartidas){
                                            tpvPanelPreConfirmacionArticulo.append("<div class='form-group col-md-4'>"+
                                                "<p>Partida</p>"+
                                                "<input type='number' class='form-control' style='text-align:center;' id='tpvPanelPreConfirmacionArticuloPartida' size='1' min='1'>"+
                                                "</div>"+
                                                "<div class='form-group col-md-4'>"+
                                                "<p>Fecha Caducidad</p>"+
                                                "<input type='date' class='form-control' style='text-align:center;' id='tpvPanelPreConfirmacionArticuloFechaCaducidad' size='1' min='1'>"+
                                                "</div>");
                                        }

                                        if(configTratamientoTallaColores){
                                            tpvPanelPreConfirmacionArticulo.append("<div class='form-group col-md-4'>"+
                                                "<p>Color</p>"+
                                                "<select class='form-control' id='tpvPanelPreConfirmacionArticuloColor' name='tpvPanelPreConfirmacionArticuloColor'></select>"+
                                                "</div>"+
                                                "<div class='form-group col-md-4'>"+
                                                "<p>Talla</p>"+
                                                "<select class='form-control' id='tpvPanelPreConfirmacionArticuloTalla' name='tpvPanelPreConfirmacionArticuloTalla'></select>"+
                                                "</div>");
                                        }

                                        tpvPanelPreConfirmacionArticulo.append("<div class='form-group col-md-4'>"+
                                            "<p>Cantidad</p>"+
                                            "<input type='number' class='form-control' style='text-align:center;' id='tpvPanelPreConfirmacionArticuloCantidad' size='1' min='1'>"+
                                            "</div>"+
                                            "<div class='form-group col-md-4'>"+
                                            "<p>Descuento</p>"+
                                            "<div class='input-group'>"+
                                            "<input type='number' class='form-control' style='text-align:center;' id='tpvPanelPreConfirmacionArticuloDescuento' value='"+$(this).children('#articulotpvdescuento').text()+"' size='1' min='1' max='100'>"+
                                            "<span class='input-group-addon'>%</span>"+
                                            "</div>"+
                                            "</div>"+
                                            "<div class='form-group col-md-4'>"+
                                            "<p>Precio</p>"+
                                            "<div class='input-group'>"+
                                            "<span class='input-group-addon'>1u:</span>"+
                                            "<input type='number' class='form-control' style='text-align:center;' id='tpvPanelPreConfirmacionArticuloPrecioVenta' value='"+$(this).children('#articulotpvprecioventa').text()+"' size='1'>"+
                                            "<span class='input-group-addon'>€</span>"+
                                            "</div>"+
                                            "</div>"+
                                            "<br>"+
                                            "<div class='form-group col-md-12'>"+
                                            "<h4>Precio total: <b id='tpvPanelPreConfirmacionArticuloPrecioTotal'>"+precioTotal+"€</b></h4>"+
                                            "</div>"+
                                            "<br><button type='button' class='btn btn-block btn-primary' id='tpvPanelPreConfirmacionArticuloBtn'><i class='fa fa-check'></i> Confirmar</button>");

                                        var codigoArticuloSeleccionado = $(this).attr('id').replace('articuloTPV','');

                                        // Obtenemos los colores
                                        obtenerColoresSegunArticulo(codigoArticuloSeleccionado);

                                        // Obtenemos las tallas
                                        $("#tpvPanelPreConfirmacionArticuloColor").on("change", function() {
                                            obtenerTallasSegunArticuloYColor(codigoArticuloSeleccionado, this.value);
                                        });

                                        // Establecemos la funcionalidad para añadir o disminuir la cantidad del artículo seleccionado
                                        // Y a la vez, el precio total del artículo seleccionado se modifica
                                        $("#tpvPanelPreConfirmacionArticuloCantidad").val(1); // Por defecto tendrá 1 cantidad
                                        $(this).click(function(){ // Por cada nuevo click se se haga, se sumará +1 en la cantidad
                                            $("#tpvPanelPreConfirmacionArticuloCantidad").val(Number.parseInt($("#tpvPanelPreConfirmacionArticuloCantidad").val())+1);
                                        });
                                        $("#tpvPanelPreConfirmacionArticuloCantidad").focus(); // Establecemos el foco.
                                        tpvIdFocoActual = "tpvPanelPreConfirmacionArticuloCantidad"; // Sobreescribimos la variable que guarda el foco actual
                                        $("#tpvPanelPreConfirmacionArticuloCantidad").on("click", function() { // También la sobreescribimos cuando se haga click en el input
                                            tpvIdFocoActual = "tpvPanelPreConfirmacionArticuloCantidad";
                                        });

                                        // Cuando se haga click en Descuento, se cambia el foco
                                        $("#tpvPanelPreConfirmacionArticuloDescuento").on("click", function() {
                                            tpvIdFocoActual = "tpvPanelPreConfirmacionArticuloDescuento";
                                        });

                                        // Función que controla el cambio del imput de la cantidad y del descuento, y calcula el precio total
                                        setInterval(function() {
                                            var data = $("#tpvPanelPreConfirmacionArticuloCantidad").data("value"),
                                                val = $("#tpvPanelPreConfirmacionArticuloCantidad").val(),
                                                data2 = $('#tpvPanelPreConfirmacionArticuloDescuento').data("value"),
                                                val2 = $('#tpvPanelPreConfirmacionArticuloDescuento').val();

                                            if (data !== val || data2 !== val2) {
                                                nuevoPrecioTotalArticulo = $('#tpvPanelPreConfirmacionArticuloPrecioVenta').val() * $("#tpvPanelPreConfirmacionArticuloCantidad").val();
                                                nuevoPrecioTotalArticulo = nuevoPrecioTotalArticulo.toFixed(2); // Mostramos solo 2 decimales
                                                descuento = $('#tpvPanelPreConfirmacionArticuloDescuento').val() / 100;
                                                nuevoPrecioTotalArticulo = nuevoPrecioTotalArticulo - (nuevoPrecioTotalArticulo * descuento); // Se calcula el descuento
                                                $('#tpvPanelPreConfirmacionArticuloPrecioTotal').text(nuevoPrecioTotalArticulo.toFixed(2)+"€");
                                            }
                                        }, 100);


                                        // Al hacer click en Confirmar se realiza lo siguiente
                                        $("#tpvPanelPreConfirmacionArticuloBtn").on("click",function(){

                                            // Se inserta la línea
                                            inserccionLineasDocumento();

                                            // Se actualiza la información que se muestra en pantalla
                                            actualizarDesgloseTicket();

                                            // Se vacía el panel en donde se confirma el artículo una vez seleccionado
                                            $("#tpvPanelPreConfirmacionArticulo").empty();
                                        });


                                    });




                                }






                            }
                        });




                    });

                }


                // Código para controlar el scroll horizontal
                // http://jsfiddle.net/Lpjj3n1e/
                var print = function(msg) {
                    alert(msg);
                };

                var setInvisible = function(elem) {
                    elem.css('visibility', 'hidden');
                };
                var setVisible = function(elem) {
                    elem.css('visibility', 'visible');
                };

                var elem = $("#tpvPanelVistaFamilias");
                var items = elem.children();

                // Inserting Buttons
                elem.prepend('<a href="#"><div id="right-button" style="visibility: hidden;"><</div></a>');
                elem.append('<a href="#"><div id="left-button">></div></a>');

                // Inserting Inner
                items.wrapAll('<div id="inner" />');

                // Inserting Outer
                elem.find('#inner').wrap('<div id="outer"/>');

                var outer = $('#outer');

                var updateUI = function() {
                    var maxWidth = outer.outerWidth(true);
                    var actualWidth = 0;
                    $.each($('#inner >'), function(i, item) {
                        actualWidth += $(item).outerWidth(true);
                    });

                    if (actualWidth <= maxWidth) {
                        setVisible($('#left-button'));
                    }
                };
                updateUI();

                $('#right-button').click(function() {
                    var leftPos = outer.scrollLeft();
                    outer.animate({
                        scrollLeft: leftPos - 400
                    }, 800, function() {
                        if ($('#outer').scrollLeft() <= 0) {
                            setInvisible($('#right-button'));
                        }
                    });
                });

                $('#left-button').click(function() {
                    setVisible($('#right-button'));
                    var leftPos = outer.scrollLeft();
                    outer.animate({
                        scrollLeft: leftPos + 400
                    }, 800);
                });

                $(window).resize(function() {
                    updateUI();
                });
                // Fin código scroll horizontal


            }
        });
        /*=============================================
        FIN || CARGA Y CONTROL DE LAS FAMILIAS || FILTRO DE ARTÍCULOS SEGÚN FAMILIA
        =============================================*/



        /*=============================================
        CARGA DE LOS ARTÍCULOS
        =============================================*/
        var datos = new FormData();
        datos.append("cargaArticulosTPV", "true");
        $.ajax({
            url:"controladores/pedidos.controlador.php",
            method: "POST",
            data: datos,
            cache: false,
            contentType: false,
            processData: false,
            dataType:"json",
            success:function(respuesta){

                for (i = 0; i < respuesta.data.length; i++) {

                    // Muestro los artículos en el panel del puesto de venta
                    //console.log(respuesta.data[i][0]); // CodigoArticulo
                    var idArticuloTPV = "articuloTPV"+respuesta.data[i][0]; // Variable para poder identificar cada registro. Se usa más adelante
                    var respuestaPrecioVenta = 0; // Esta variable y el if es para controlar el cómo se muestra el precio de venta
                    if(respuesta.data[i][2] == .0000000000){
                        respuestaPrecioVenta = parseFloat(0.0000000000);
                    } else {
                        respuestaPrecioVenta = parseFloat(respuesta.data[i][2]);
                    }
                    var respuestaDescuento = 0; // Esta variable y el if es para controlar el cómo se muestra el descuento
                    if(respuesta.data[i][3] == .0000000000){
                        respuestaDescuento = parseFloat(0.0000000000);
                    } else {
                        respuestaDescuento = parseFloat(respuesta.data[i][3]);
                    }
                    var respuestaImagenExt = respuesta.data[i][4];

                    // Construimos el 'cuadrado' para mostar el artículo
                    let tpvPanelVistaArticulos = $("#tpvPanelVistaArticulos");
                    tpvPanelVistaArticulos.append("<div class='btn btn-app' id='"+idArticuloTPV+"'>"+
                        "<img src='controladores/imagen_mostrar.php?ImagenExt="+respuestaImagenExt+" class='pruebaImagenpedidos' style='position: absolute; top: 2px; left: 10px; width: 100px; opacity: 0.25;'></img>"+
                        "<p id='articulotpvcodigo'>"+respuesta.data[i][0]+"</p>"+
                        "<p id='articulotpvdescripcion'>"+respuesta.data[i][1]+"</p>"+
                        "<p id='articulotpvprecioventa' class='ocultar'>"+respuestaPrecioVenta+"</p>"+
                        "<p id='articulotpvdescuento' class='ocultar'>"+respuestaDescuento+"</p>"+

                        "</div>");

                    $("#"+idArticuloTPV).on("click",function(){
                        // console.log($(this).attr('id'));
                        // console.log($(this).children('#articulotpvdescripcion').text());

                        // Se calcula el descuento si lo tuviera
                        var precioVenta = $(this).children('#articulotpvprecioventa').text();
                        var descuento = $(this).children('#articulotpvdescuento').text() / 100;
                        var precioTotal = precioVenta - (precioVenta * descuento);

                        // Contruimos el "formulario" de la confirmación de la selección del artículo
                        let tpvPanelPreConfirmacionArticulo = $("#tpvPanelPreConfirmacionArticulo");
                        tpvPanelPreConfirmacionArticulo.empty(); // Borramos el contenido del div por si hubiera un artículo seleccionado. Esto es por si se selecciona otro
                        tpvPanelPreConfirmacionArticulo.append("<p id='tpvPanelPreConfirmacionArticuloCodigo'>"+$(this).attr('id').replace('articuloTPV','')+"</p>"+
                            "<p><b id='tpvPanelPreConfirmacionArticuloDescripcion'>"+$(this).children('#articulotpvdescripcion').text()+"</b></p>");

                        if(configTratamientoPartidas){
                            tpvPanelPreConfirmacionArticulo.append("<div class='form-group col-md-4'>"+
                                "<p>Partida</p>"+
                                "<input type='number' class='form-control' style='text-align:center;' id='tpvPanelPreConfirmacionArticuloPartida' size='1' min='1'>"+
                                "</div>"+
                                "<div class='form-group col-md-4'>"+
                                "<p>Fecha Caducidad</p>"+
                                "<input type='date' class='form-control' style='text-align:center;' id='tpvPanelPreConfirmacionArticuloFechaCaducidad' size='1' min='1'>"+
                                "</div>");
                        }

                        if(configTratamientoTallaColores){
                            tpvPanelPreConfirmacionArticulo.append("<div class='form-group col-md-4'>"+
                                "<p>Color</p>"+
                                "<select class='form-control' id='tpvPanelPreConfirmacionArticuloColor' name='tpvPanelPreConfirmacionArticuloColor'></select>"+
                                "</div>"+
                                "<div class='form-group col-md-4'>"+
                                "<p>Talla</p>"+
                                "<select class='form-control' id='tpvPanelPreConfirmacionArticuloTalla' name='tpvPanelPreConfirmacionArticuloTalla'></select>"+
                                "</div>");
                        }

                        tpvPanelPreConfirmacionArticulo.append("<div class='form-group col-md-4'>"+
                            "<p>Cantidad</p>"+
                            "<input type='number' class='form-control' style='text-align:center;' id='tpvPanelPreConfirmacionArticuloCantidad' size='1' min='1'>"+
                            "</div>"+
                            "<div class='form-group col-md-4'>"+
                            "<p>Descuento</p>"+
                            "<div class='input-group'>"+
                            "<input type='number' class='form-control' style='text-align:center;' id='tpvPanelPreConfirmacionArticuloDescuento' value='"+$(this).children('#articulotpvdescuento').text()+"' size='1' min='1' maxlength='3'>"+
                            "<span class='input-group-addon'>%</span>"+
                            "</div>"+
                            "</div>"+
                            "<div class='form-group col-md-4'>"+
                            "<p>Precio</p>"+
                            "<div class='input-group'>"+
                            "<span class='input-group-addon'>1u:</span>"+
                            "<input type='number' class='form-control' style='text-align:center;' id='tpvPanelPreConfirmacionArticuloPrecioVenta' value='"+$(this).children('#articulotpvprecioventa').text()+"' size='1'>"+
                            "<span class='input-group-addon'>€</span>"+
                            "</div>"+
                            "</div>"+
                            "<br>"+
                            "<div class='form-group col-md-12'>"+
                            "<h4>Precio total: <b id='tpvPanelPreConfirmacionArticuloPrecioTotal'>"+precioTotal+"€</b></h4>"+
                            "</div>"+
                            "<br><button type='button' class='btn btn-block btn-primary' id='tpvPanelPreConfirmacionArticuloBtn'><i class='fa fa-check'></i> Confirmar</button>");



                        // Codigo para la funcionalidad de un MAS y MENOS para modificar la cantidad
                        // $("#tpvPanelPreConfirmacionArticulosCantidad").val(1);
                        // $('#tpvPanelPreConfirmacionArticulosCantidadBtnMas').click(function add() {
                        //     var $rooms = $("#tpvPanelPreConfirmacionArticulosCantidad");
                        //     var a = $rooms.val();
                        //     a++;
                        //     $("#tpvPanelPreConfirmacionArticulosCantidadBtnMenos").prop("disabled", !a);
                        //     $rooms.val(a);
                        // });
                        // $("#tpvPanelPreConfirmacionArticulosCantidadBtnMenos").prop("disabled", !$("#noOfRoom").val());
                        // $('#tpvPanelPreConfirmacionArticulosCantidadBtnMenos').click(function subst() {
                        //     var $rooms = $("#tpvPanelPreConfirmacionArticulosCantidad");
                        //     var b = $rooms.val();
                        //     if (b >= 2) {
                        //         b--;
                        //         $rooms.val(b);
                        //     }
                        //     else {
                        //         $("#tpvPanelPreConfirmacionArticulosCantidadBtnMenos").prop("disabled", true);
                        //     }
                        // });


                        var codigoArticuloSeleccionado = $(this).attr('id').replace('articuloTPV','');

                        // Obtenemos los colores
                        obtenerColoresSegunArticulo(codigoArticuloSeleccionado);

                        // Obtenemos las tallas
                        $("#tpvPanelPreConfirmacionArticuloColor").on("change", function() {
                            obtenerTallasSegunArticuloYColor(codigoArticuloSeleccionado, this.value);
                        });

                        // Establecemos la funcionalidad para añadir o disminuir la cantidad del artículo seleccionado
                        // Y a la vez, el precio total del artículo seleccionado se modifica
                        $("#tpvPanelPreConfirmacionArticuloCantidad").val(1); // Por defecto tendrá 1 cantidad
                        $(this).click(function(){ // Por cada nuevo click se se haga, se sumará +1 en la cantidad
                            $("#tpvPanelPreConfirmacionArticuloCantidad").val(Number.parseInt($("#tpvPanelPreConfirmacionArticuloCantidad").val())+1);
                        });
                        $("#tpvPanelPreConfirmacionArticuloCantidad").focus(); // Establecemos el foco.
                        tpvIdFocoActual = "tpvPanelPreConfirmacionArticuloCantidad"; // Sobreescribimos la variable que guarda el foco actual
                        $("#tpvPanelPreConfirmacionArticuloCantidad").on("click", function() { // También la sobreescribimos cuando se haga click en el input
                            tpvIdFocoActual = "tpvPanelPreConfirmacionArticuloCantidad";
                        });

                        // Cuando se haga click en Descuento, se cambia el foco
                        $("#tpvPanelPreConfirmacionArticuloDescuento").on("click", function() {
                            tpvIdFocoActual = "tpvPanelPreConfirmacionArticuloDescuento";
                        });

                        // Función que controla el cambio del imput de la cantidad y el descuento, y calcula el precio total
                        setInterval(function() {
                            var data = $("#tpvPanelPreConfirmacionArticuloCantidad").data("value"),
                                val = $("#tpvPanelPreConfirmacionArticuloCantidad").val(),
                                data2 = $('#tpvPanelPreConfirmacionArticuloDescuento').data("value"),
                                val2 = $('#tpvPanelPreConfirmacionArticuloDescuento').val();

                            if (data !== val || data2 !== val2) {
                                nuevoPrecioTotalArticulo = $('#tpvPanelPreConfirmacionArticuloPrecioVenta').val() * $("#tpvPanelPreConfirmacionArticuloCantidad").val();
                                nuevoPrecioTotalArticulo = nuevoPrecioTotalArticulo.toFixed(2); // Mostramos solo 2 decimales
                                descuento = $('#tpvPanelPreConfirmacionArticuloDescuento').val() / 100;
                                nuevoPrecioTotalArticulo = nuevoPrecioTotalArticulo - (nuevoPrecioTotalArticulo * descuento); // Se calcula el descuento
                                $('#tpvPanelPreConfirmacionArticuloPrecioTotal').text(nuevoPrecioTotalArticulo.toFixed(2)+"€");
                            }
                        }, 250);


                        // Al hacer click en Confirmar se realiza lo siguiente
                        $("#tpvPanelPreConfirmacionArticuloBtn").on("click",function(){
                            // console.log($("#tpvPanelPreConfirmacionArticuloCodigo").text());
                            // console.log($("#tpvPanelPreConfirmacionArticuloDescripcion").text());
                            // console.log($("#tpvPanelPreConfirmacionArticuloCantidad").val());
                            // console.log($("#tpvPanelPreConfirmacionArticuloDescuento").val());
                            // console.log($("#tpvPanelPreConfirmacionArticuloPrecioVenta").val());
                            // console.log($("#tpvPanelPreConfirmacionArticuloPrecioTotal").text().replace('€',''));

                            // Se inserta la línea
                            inserccionLineasDocumento();

                            // Se actualiza la información que se muestra en pantalla
                            actualizarDesgloseTicket();

                            // Se vacía el panel en donde se confirma el artículo una vez seleccionado
                            $("#tpvPanelPreConfirmacionArticulo").empty();
                        });


                    });



                }


            }
        });
        /*=============================================
        FIN || CARGA DE LOS ARTÍCULOS
        =============================================*/


        /*=============================================
        BUSCADOR DE LOS ARTÍCULOS || VERSIÓN TÁCTIL
        =============================================*/
        setInterval(function() {
            var data = $("#tpvVistaArticulosBuscador").data("value"),
                val = $("#tpvVistaArticulosBuscador").val();

            if (data !== val) {
                var value = $("#tpvVistaArticulosBuscador").val().toLowerCase();
                $("#tpvPanelVistaArticulos *").filter(function() {
                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
                });
            }
        }, 100);
        $("#tpvVistaArticulosBuscador").on("click", function(){ // Establecemos el nuevo foco para el teclado
            tpvIdFocoActual = "tpvVistaArticulosBuscador";
        });


        /*=============================================
        BUSCADOR DE LOS ARTÍCULOS || VERSIÓN NO TÁCTIL
        =============================================*/
        // Al introducir el código buscará el artículo
        $("#tpvAnyadirArticuloCodigo").on("input", function(){

            if($("#tpvAnyadirArticuloCodigo").val() != ""){ // Si no está vacío buscará el artículo

                // Carga del formato del código y separador | Para saber cómo cargamos el artículo
                var datos = new FormData();
                datos.append("cargarConfiguracion", "true");
                $.ajax({
                    url:"controladores/configuracion.controlador.php",
                    method: "POST",
                    data: datos,
                    cache: false,
                    contentType: false,
                    processData: false,
                    dataType:"json",
                    success:function(respuesta){

                        // Recogemos los datos de configuración
                        var configFormatoCodigoBarras = respuesta["FormatoCodigoBarras"];
                        var configSeparador = respuesta["SeparadorCodigoBarras"];

                        // Comprobamos si el código que se ha introducido tiene el separador
                        // Si no tiene el separador, se busca el código sin más
                        if($("#tpvAnyadirArticuloCodigo").val().includes(configSeparador) == false) {

                            var datos = new FormData();
                            datos.append("idArticulo", $("#tpvAnyadirArticuloCodigo").val());
                            $.ajax({
                                url:"controladores/articulos.controlador.php",
                                method: "POST",
                                data: datos,
                                cache: false,
                                contentType: false,
                                processData: false,
                                dataType:"json",
                                success:function(respuesta){
                                    if(typeof respuesta["DescripcionArticulo"] != "undefined"){
                                        $("#tpvAnyadirArticuloDescripcion").val(respuesta["DescripcionArticulo"]);
                                        $("#tpvAnyadirArticuloImagen").attr("src","controladores/imagen_mostrar.php?ImagenExt="+respuesta["ImagenExt"]);
                                        $("#tpvAnyadirArticuloUnidades").val("1");
                                        if(typeof respuesta["TratamientoPartidas"] !== 'undefined' || respuesta["TratamientoPartidas"] == "0"){
                                            $("#tpvAnyadirArticuloPartida").attr("readonly", "readonly");
                                            $("#tpvAnyadirArticuloFechaCaducidad").attr("readonly", "readonly");
                                        } else {
                                            $("#tpvAnyadirArticuloPartida").removeAttr("readonly");
                                            $("#tpvAnyadirArticuloFechaCaducidad").removeAttr("readonly");
                                        }
                                        obtenerColoresSegunArticulo(respuesta["CodigoArticulo"]);
                                        var respuestaPrecioVenta = 0; // Esta variable y el if es para controlar el cómo se muestra el precio de venta
                                        if(respuesta["PrecioVenta"] == .0000000000){
                                            respuestaPrecioVenta = parseFloat(0.0000000000);
                                        } else {
                                            respuestaPrecioVenta = parseFloat(respuesta["PrecioVenta"]);
                                        }
                                        // obtenerTarifa(respuesta["CodigoArticulo"],$("#tpvTactilCodigoCliente").val());
                                        $("#tpvAnyadirArticuloPrecio").val(respuestaPrecioVenta.toFixed(2));
                                        $("#tpvAnyadirArticuloDescuento").val("0");
                                        $("#tpvAnyadirArticuloBtnAnyadir").removeAttr("disabled");

                                        if(typeof respuesta["CodigoColor_"] != "undefined"){ // Aquí entrará cuando se introduzca un CódigoAlternativo (para la pistola, con talla y colores)
                                            $("#tpvAnyadirArticuloColor").val(respuesta["CodigoColor_"]);
                                            $("#tpvAnyadirArticuloTalla").val(respuesta["CodigoTalla01_"]);
                                            console.log($("#tpvAnyadirArticuloColor").val());
                                            console.log(respuesta["CodigoTalla01_"]);
                                        }
                                        inserccionLineasDocumento();

                                        $("#tpvAnyadirArticuloCodigo").val("");
                                        $("#tpvAnyadirArticuloDescripcion").val("");
                                        $("#tpvAnyadirArticuloUnidades").val("");
                                        $("#tpvAnyadirArticuloPartida").val("");
                                        $("#tpvAnyadirArticuloFechaCaducidad").val("");
                                        $("#tpvAnyadirArticuloPrecio").val("");
                                        $("#tpvAnyadirArticuloUnidades").val("");

                                        $("#tpvAnyadirArticuloCodigo").focus();
                                    } else { // Si está vacío, limpiará el formulario
                                        $("#tpvAnyadirArticuloDescripcion").val("");
                                        $("#tpvAnyadirArticuloImagen").removeAttr("src");
                                        $("#tpvAnyadirArticuloUnidades").val("");
                                        $("#tpvAnyadirArticuloPartida").attr("readonly", "readonly");
                                        $("#tpvAnyadirArticuloPrecio").val("");
                                        $("#tpvAnyadirArticuloDescuento").val("");
                                        $("#tpvAnyadirArticuloFechaCaducidad").attr("readonly", "readonly");
                                        $("#tpvAnyadirArticuloBtnAnyadir").attr("disabled", "disabled");
                                    }


                                }
                            });


                        } else { // Si hay un separador se hace lo siguiente

                            var formatoSeparado = configFormatoCodigoBarras.split(configSeparador);
                            var codigoIntroducidoSeparado = $("#tpvAnyadirArticuloCodigo").val().split(configSeparador);

                            var condicion1 = formatoSeparado[0];
                            var condicion2 = formatoSeparado[1];
                            var condicion3 = formatoSeparado[2];
                            var valorCondicion1 = codigoIntroducidoSeparado[0];
                            var valorCondicion2 = codigoIntroducidoSeparado[1];
                            var valorCondicion3 = codigoIntroducidoSeparado[2];

                            $("#tpvAnyadirArticuloPartida").removeAttr("readonly");
                            $("#tpvAnyadirArticuloFechaCaducidad").removeAttr("readonly");


                            var datos = new FormData();
                            datos.append("BuscarArticuloPorCondiciones", condicion1);
                            datos.append("EjercicioDocumento", $("#tpvCodigoTicketEjercicio").val());
                            datos.append("Condicion1", condicion1);
                            datos.append("ValorCondicion1", valorCondicion1);
                            datos.append("Condicion2", condicion2);
                            datos.append("ValorCondicion2", valorCondicion2);
                            datos.append("Condicion3", condicion3);
                            datos.append("ValorCondicion3", valorCondicion3);
                            $.ajax({
                                url:"controladores/articulos.controlador.php",
                                method: "POST",
                                data: datos,
                                cache: false,
                                contentType: false,
                                processData: false,
                                dataType:"json",
                                success:function(respuesta){
                                    console.log("3");
                                    if(typeof respuesta["DescripcionArticulo"] != "undefined"){
                                        $("#tpvAnyadirArticuloDescripcion").val(respuesta["DescripcionArticulo"]);
                                        $("#tpvAnyadirArticuloImagen").attr("src",respuesta["ImagenExt"]);
                                        $("#tpvAnyadirArticuloUnidades").val("1");
                                        $("#tpvAnyadirArticuloPartida").val(respuesta["Partida"]);
                                        $("#tpvAnyadirArticuloFechaCaducidad").val(respuesta["FechaCaduca"]);
                                        var respuestaPrecioVenta = 0; // Esta variable y el if es para controlar el cómo se muestra el precio de venta
                                        if(respuesta["PrecioVenta"] == .0000000000){
                                            respuestaPrecioVenta = parseFloat(0.0000000000);
                                        } else {
                                            respuestaPrecioVenta = parseFloat(respuesta["PrecioVenta"]);
                                        }
                                        //var respuestaPrecioVenta = obtenerTarifa(respuesta["CodigoArticulo"], $("#tpvTactilCodigoCliente").val());
                                        $("#tpvAnyadirArticuloPrecio").val(respuestaPrecioVenta.toFixed(2));
                                        $("#tpvAnyadirArticuloDescuento").val("0");

                                        inserccionLineasDocumento();

                                        $("#tpvAnyadirArticuloCodigo").val("");
                                        $("#tpvAnyadirArticuloDescripcion").val("");
                                        $("#tpvAnyadirArticuloUnidades").val("");
                                        $("#tpvAnyadirArticuloPartida").val("");
                                        $("#tpvAnyadirArticuloFechaCaducidad").val("");
                                        $("#tpvAnyadirArticuloPrecio").val("");
                                        $("#tpvAnyadirArticuloUnidades").val("");

                                        $("#tpvAnyadirArticuloCodigo").focus();
                                    } else { // Si está vacío, limpiará el formulario
                                        $("#tpvAnyadirArticuloDescripcion").val("");
                                        $("#tpvAnyadirArticuloImagen").removeAttr("src");
                                        $("#tpvAnyadirArticuloUnidades").val("");
                                        $("#tpvAnyadirArticuloPrecio").val("");
                                        $("#tpvAnyadirArticuloDescuento").val("");
                                        $("#tpvAnyadirArticuloBtnAnyadir").attr("disabled", "disabled");
                                    }


                                }
                            });





                        }


                    }
                });


            } else { // Cuando no se haya introducido un código
                $("#tpvAnyadirArticuloDescripcion").val("");
                $("#tpvAnyadirArticuloImagen").removeAttr("src");
                $("#tpvAnyadirArticuloUnidades").val("");
                $("#tpvAnyadirArticuloPartida").attr("readonly", "readonly");
                $("#tpvAnyadirArticuloPartida").val("");
                $("#tpvAnyadirArticuloColor").empty();
                $("#tpvAnyadirArticuloTalla").empty();
                $("#tpvAnyadirArticuloPrecio").val("");
                $("#tpvAnyadirArticuloDescuento").val("");
                $("#tpvAnyadirArticuloFechaCaducidad").attr("readonly", "readonly");
                $("#tpvAnyadirArticuloFechaCaducidad").val("");
                $("#tpvAnyadirArticuloBtnAnyadir").attr("disabled", "disabled");
            }



        });



        /*=============================================
        FUNCIÓN PARA RECOGER LAS TARIFAS
        =============================================*/
        function obtenerTarifa(CodigoArticulo, CodigoCliente){
            var returnPrecioVenta = 0;

            var datos = new FormData();
            datos.append("obtenerTarifaPorArticulo", "true");
            datos.append("CodigoArticulo", CodigoArticulo);
            datos.append("CodigoCliente", CodigoCliente);
            $.ajax({
                url:"controladores/articulos.controlador.php",
                method: "POST",
                data: datos,
                cache: false,
                contentType: false,
                processData: false,
                dataType:"json",
                success:function(respuesta){


                    if(tipoPantalla == "0"){ // Vista no táctil



                    } else {

                    }

                }
            });

            return returnPrecioVenta;
        }


        /*=============================================
        FUNCIÓN PARA RECOGER EL PRECIO SEGÚN EL ARTÍCULO || NO TÁCTIL
        =============================================*/
        function obtenerColoresSegunArticulo(CodigoArticulo){

            var datos = new FormData();
            datos.append("obtenerColoresPorArticulo", "true");
            datos.append("CodigoArticulo", CodigoArticulo); // Enviamos el código del artículo
            $.ajax({
                url:"controladores/articulos.controlador.php",
                method: "POST",
                data: datos,
                cache: false,
                contentType: false,
                processData: false,
                dataType:"json",
                success:function(respuesta){

                    if(tipoPantalla == "0"){ // Vista no táctil
                        // Llamamos al select y lo vaciamos
                        let tpvAnyadirArticuloColor = $("#tpvAnyadirArticuloColor");
                        tpvAnyadirArticuloColor.empty();

                        for (i = 0; i < respuesta.data.length; i++) { // Lo rellenamos con tantos options como colores tenga el artículo
                            var respuestaCodigoColor = respuesta.data[i][0];
                            var respuestaColor = respuesta.data[i][1];
                            tpvAnyadirArticuloColor.append("<option value='"+respuestaCodigoColor+"'>"+respuestaColor+"</option>");
                        }

                        // Llamamos inicialmente a la función que obtiene las tallas según el primer color existente y el código del artículo (parar tener algo inicial)
                        obtenerTallasSegunArticuloYColor(CodigoArticulo,$("#tpvAnyadirArticuloColor").val());

                    } else { // Vista táctil
                        // Llamamos al select y lo vaciamos
                        let tpvPanelPreConfirmacionArticuloColor = $("#tpvPanelPreConfirmacionArticuloColor");
                        tpvPanelPreConfirmacionArticuloColor.empty();

                        for (i = 0; i < respuesta.data.length; i++) { // Lo rellenamos con tantos options como colores tenga el artículo
                            var respuestaCodigoColor = respuesta.data[i][0];
                            var respuestaColor = respuesta.data[i][1];
                            tpvPanelPreConfirmacionArticuloColor.append("<option value='"+respuestaCodigoColor+"'>"+respuestaColor+"</option>");
                        }

                        // Llamamos inicialmente a la función que obtiene las tallas según el primer color existente y el código del artículo (parar tener algo inicial)
                        obtenerTallasSegunArticuloYColor(CodigoArticulo,$("#tpvPanelPreConfirmacionArticuloColor").val());
                    }




                }
            });
        }





        /*=============================================
        CONTROL DE LAS TALLAS SEGÚN COLOR || NO TÁCTIL
        =============================================*/
        // Cada vez que se cambia el color se llama a la función que busca las tallas existentes del artículo
        $("#tpvAnyadirArticuloColor").on("change", function() {
            obtenerTallasSegunArticuloYColor($("#tpvAnyadirArticuloCodigo").val(), this.value);
        });
        function obtenerTallasSegunArticuloYColor(CodigoArticulo,CodigoColor){
            // console.log(CodigoArticulo);
            // console.log(CodigoColor);

            var datos = new FormData();
            datos.append("obtenerTallasSegunArticuloYColor", "true");
            datos.append("CodigoArticulo", CodigoArticulo); // Enviamos el código del artículo
            datos.append("CodigoColor", CodigoColor); // Enviamos el código del artículo
            $.ajax({
                url:"controladores/articulos.controlador.php",
                method: "POST",
                data: datos,
                cache: false,
                contentType: false,
                processData: false,
                dataType:"json",
                success:function(respuesta){

                    if(tipoPantalla == "0"){ // Vista no táctil
                        // Llamamos al select y lo vaciamos
                        let tpvAnyadirArticuloTalla = $("#tpvAnyadirArticuloTalla");
                        tpvAnyadirArticuloTalla.empty();

                        for (i = 0; i < respuesta.data.length; i++) { // Lo rellenamos con tantos options como colores tenga el artículo

                            var respuestaCodigoTalla = respuesta.data[i][0];
                            var respuestaTalla = respuesta.data[i][1];
                            var respuestaUnidadesStock = respuesta.data[i][2];

                            // Si el TPV tiene configurado que se permita el stock negativo, se podrá seleccionar cualquier talla aunque no las tenga
                            if(configPermitirStockNegativo){
                                tpvAnyadirArticuloTalla.append("<option value='"+respuestaCodigoTalla+"'>"+respuestaTalla+"</option>");
                            } else { // Si no, dependiendo del stock, se podrá seleccionar la talla o no
                                if(respuestaUnidadesStock == .0000000000){
                                    tpvAnyadirArticuloTalla.append("<option value='"+respuestaCodigoTalla+"' disabled='true' style='color:#adadad'>"+respuestaTalla+"</option>");
                                } else {
                                    tpvAnyadirArticuloTalla.append("<option value='"+respuestaCodigoTalla+"'>"+respuestaTalla+"</option>");
                                }
                            }
                        }
                    } else { // Vista táctil
                        // Llamamos al select y lo vaciamos
                        let tpvPanelPreConfirmacionArticuloTalla = $("#tpvPanelPreConfirmacionArticuloTalla");
                        tpvPanelPreConfirmacionArticuloTalla.empty();

                        for (i = 0; i < respuesta.data.length; i++) { // Lo rellenamos con tantos options como colores tenga el artículo

                            var respuestaCodigoTalla = respuesta.data[i][0];
                            var respuestaTalla = respuesta.data[i][1];
                            var respuestaUnidadesStock = respuesta.data[i][2];

                            // Si el TPV tiene configurado que se permita el stock negativo, se podrá seleccionar cualquier talla aunque no las tenga
                            if(configPermitirStockNegativo){
                                tpvPanelPreConfirmacionArticuloTalla.append("<option value='"+respuestaCodigoTalla+"'>"+respuestaTalla+"</option>");
                            } else { // Si no, dependiendo del stock, se podrá seleccionar la talla o no
                                if(respuestaUnidadesStock == .0000000000){
                                    tpvPanelPreConfirmacionArticuloTalla.append("<option value='"+respuestaCodigoTalla+"' disabled='true' style='color:#adadad'>"+respuestaTalla+"</option>");
                                } else {
                                    tpvPanelPreConfirmacionArticuloTalla.append("<option value='"+respuestaCodigoTalla+"'>"+respuestaTalla+"</option>");
                                }
                            }
                        }
                    }

                }
            });
        }



        /*=============================================
        TECLADO NUMÉRICO
        =============================================*/
        // Teclado vista táctil
        $("#tpvTeclado0").on("click", function(){
            $("#"+tpvIdFocoActual).val( $("#"+tpvIdFocoActual).val() + "0");
        });
        $("#tpvTeclado1").on("click", function(){
            $("#"+tpvIdFocoActual).val( $("#"+tpvIdFocoActual).val() + "1");
        });
        $("#tpvTeclado2").on("click", function(){
            $("#"+tpvIdFocoActual).val( $("#"+tpvIdFocoActual).val() + "2");
        });
        $("#tpvTeclado3").on("click", function(){
            $("#"+tpvIdFocoActual).val( $("#"+tpvIdFocoActual).val() + "3");
        });
        $("#tpvTeclado4").on("click", function(){
            $("#"+tpvIdFocoActual).val( $("#"+tpvIdFocoActual).val() + "4");
        });
        $("#tpvTeclado5").on("click", function(){
            $("#"+tpvIdFocoActual).val( $("#"+tpvIdFocoActual).val() + "5");
        });
        $("#tpvTeclado6").on("click", function(){
            $("#"+tpvIdFocoActual).val( $("#"+tpvIdFocoActual).val() + "6");
        });
        $("#tpvTeclado7").on("click", function(){
            $("#"+tpvIdFocoActual).val( $("#"+tpvIdFocoActual).val() + "7");
        });
        $("#tpvTeclado8").on("click", function(){
            $("#"+tpvIdFocoActual).val( $("#"+tpvIdFocoActual).val() + "8");
        });
        $("#tpvTeclado9").on("click", function(){
            $("#"+tpvIdFocoActual).val( $("#"+tpvIdFocoActual).val() + "9");
        });
        $("#tpvTecladoC").on("click", function(){
            $("#"+tpvIdFocoActual).val("");
        });
        $("#tpvTecladoBack").on("click", function(){
            var textoFocoActual = $("#"+tpvIdFocoActual).val();
            textoFocoActual = textoFocoActual.substring(0, textoFocoActual.length-1);
            $("#"+tpvIdFocoActual).val(textoFocoActual);
        });

        // Teclado modal apertura
        $("#tpvTecladoApertura0").on("click", function(){
            $("#"+tpvIdFocoActual).val( $("#"+tpvIdFocoActual).val() + "0");
            console.log("Has hecho click");
            $("#modalPuntoVentaAperturaCajaReposicionAceptar").removeAttr("disabled");
        });
        $("#tpvTecladoApertura1").on("click", function(){
            $("#"+tpvIdFocoActual).val( $("#"+tpvIdFocoActual).val() + "1");
            $("#modalPuntoVentaAperturaCajaReposicionAceptar").removeAttr("disabled");
        });
        $("#tpvTecladoApertura2").on("click", function(){
            $("#"+tpvIdFocoActual).val( $("#"+tpvIdFocoActual).val() + "2");
            $("#modalPuntoVentaAperturaCajaReposicionAceptar").removeAttr("disabled");
        });
        $("#tpvTecladoApertura3").on("click", function(){
            $("#"+tpvIdFocoActual).val( $("#"+tpvIdFocoActual).val() + "3");
            $("#modalPuntoVentaAperturaCajaReposicionAceptar").removeAttr("disabled");
        });
        $("#tpvTecladoApertura4").on("click", function(){
            $("#"+tpvIdFocoActual).val( $("#"+tpvIdFocoActual).val() + "4");
            $("#modalPuntoVentaAperturaCajaReposicionAceptar").removeAttr("disabled");
        });
        $("#tpvTecladoApertura5").on("click", function(){
            $("#"+tpvIdFocoActual).val( $("#"+tpvIdFocoActual).val() + "5");
            $("#modalPuntoVentaAperturaCajaReposicionAceptar").removeAttr("disabled");
        });
        $("#tpvTecladoApertura6").on("click", function(){
            $("#"+tpvIdFocoActual).val( $("#"+tpvIdFocoActual).val() + "6");
            $("#modalPuntoVentaAperturaCajaReposicionAceptar").removeAttr("disabled");
        });
        $("#tpvTecladoApertura7").on("click", function(){
            $("#"+tpvIdFocoActual).val( $("#"+tpvIdFocoActual).val() + "7");
            $("#modalPuntoVentaAperturaCajaReposicionAceptar").removeAttr("disabled");
        });
        $("#tpvTecladoApertura8").on("click", function(){
            $("#"+tpvIdFocoActual).val( $("#"+tpvIdFocoActual).val() + "8");
            $("#modalPuntoVentaAperturaCajaReposicionAceptar").removeAttr("disabled");
        });
        $("#tpvTecladoApertura9").on("click", function(){
            $("#"+tpvIdFocoActual).val( $("#"+tpvIdFocoActual).val() + "9");
            $("#modalPuntoVentaAperturaCajaReposicionAceptar").removeAttr("disabled");
        });
        $("#tpvTecladoAperturaC").on("click", function(){
            $("#"+tpvIdFocoActual).val("");
            $("#modalPuntoVentaAperturaCajaReposicionAceptar").attr("disabled","disabled");
        });
        $("#tpvTecladoAperturaBack").on("click", function(){
            var textoFocoActual = $("#"+tpvIdFocoActual).val();
            textoFocoActual = textoFocoActual.substring(0, textoFocoActual.length-1);
            $("#"+tpvIdFocoActual).val(textoFocoActual);
            if($("#"+tpvIdFocoActual).val() == ""){
                $("#modalPuntoVentaAperturaCajaReposicionAceptar").attr("disabled","disabled");
            }
        });


        /*=============================================
        Función para actualizar la vista del desglose del ticket || VERSIÓN TÁCTIL
        =============================================*/
        // function actualizarTicketTactil(){

        //     var valueTotal = 0;

        //     // Se limpia el panel del edsglose del ticket
        //     let tpvTactiltablaPuntoVentaDesgloseTicketTbody = $("#tpvTactiltablaPuntoVentaDesgloseTicketTbody");
        //     tpvTactiltablaPuntoVentaDesgloseTicketTbody.empty();

        //     // Con un bucle se recorre la variable que contiene los artículos seleccionados y se muestra en el desglose
        //     contenidoTicket.forEach(function(value, key) {
        //         tpvTactiltablaPuntoVentaDesgloseTicketTbody.append("<tr>"+
        //         "<td>"+value.descripcion+"</td>"+
        //         "<td>"+value.cantidad+"</td>"+
        //         "<td>"+value.precio+"</td>"+
        //         "<td>"+value.descuento+"</td>"+
        //         "<td>"+value.importe+"</td>"+
        //         "</tr>");

        //         // Y a su vez se va calculando el total y la guardamos en una variable
        //         valueTotal = valueTotal + Number.parseFloat(value.importe);
        //     });

        //     // Se actualiza la visualización de la variable
        //     $("#tpvTactilDesgloseTicketTotal").text(valueTotal.toFixed(2));
        // }




        /*=============================================
        BOTÓN AÑADIR ARTÍCULO A LA LÍNEA || VERSIÓN NO TÁCTIL
        =============================================*/
        $("#tpvAnyadirArticuloBtnAnyadir").on("click", function(){


            console.log($("#tpvAnyadirArticuloColor").val());

            if(configPermitirStockNegativo){
                inserccionLineasDocumento();
            } else {

                if(tipoPantalla == "0"){ // Vista no táctil

                    // Enviamos los datos necesarios y comprobamos el stock
                    var datos = new FormData();
                    datos.append("comprobarStockPorArticulo", "true");
                    if($("#tpvAnyadirArticuloCodigo").val().includes(configSeparador) == false) {
                        datos.append("CodigoArticulo", $("#tpvAnyadirArticuloCodigo").val());
                    } else { // Modificar si el tipo de formato del código cambia || Condicion 1 es CodigoArticulo
                        var codigoIntroducidoSeparado = $("#tpvAnyadirArticuloCodigo").val().split(configSeparador);
                        var valorCondicion1 = codigoIntroducidoSeparado[0];
                        datos.append("CodigoArticulo", valorCondicion1);
                    }

                    datos.append("Partida", $("#tpvAnyadirArticuloPartida").val());
                    datos.append("FechaCaduca", $("#tpvAnyadirArticuloFechaCaducidad").val());
                    datos.append("CodigoColor", $("#tpvAnyadirArticuloColor").val());
                    console.log($("#tpvAnyadirArticuloColor").val());
                    datos.append("CodigoTalla", $("#tpvAnyadirArticuloTalla").val());
                    $.ajax({
                        url:"controladores/articulos.controlador.php",
                        method: "POST",
                        data: datos,
                        cache: false,
                        contentType: false,
                        processData: false,
                        dataType:"json",
                        success:function(respuesta){
                            if(respuesta["CheckStock"] == true){
                                inserccionLineasDocumento();
                            } else {
                                swal({
                                    type: "error",
                                    title: "Error - No hay stock del artículo",
                                    showConfirmButton: true,
                                    confirmButtonText: "Cerrar",
                                    allowOutsideClick: false
                                })
                            }

                        }
                    });

                } else {

                    // Enviamos los datos necesarios y comprobamos el stock
                    var datos = new FormData();
                    datos.append("AAAAAAAAAAAA", "true");
                    if($("#tpvPanelPreConfirmacionArticuloCodigo").text().includes(configSeparador) == false) {
                        datos.append("CodigoArticulo", $("#tpvPanelPreConfirmacionArticuloCodigo").text());
                    } else { // Modificar si el tipo de formato del código cambia || Condicion 1 es CodigoArticulo
                        var codigoIntroducidoSeparado = $("#tpvPanelPreConfirmacionArticuloCodigo").text().split(configSeparador);
                        var valorCondicion1 = codigoIntroducidoSeparado[0];
                        datos.append("CodigoArticulo", valorCondicion1);
                    }
                    datos.append("Partida", $("#tpvAnyadirArticuloPartida").val());
                    datos.append("FechaCaduca", $("#tpvAnyadirArticuloFechaCaducidad").val());
                    datos.append("CodigoColor", $("#tpvPanelPreConfirmacionArticuloColor").val());
                    datos.append("CodigoTalla", $("#tpvPanelPreConfirmacionArticuloTalla").val());
                    $.ajax({
                        url:"controladores/articulos.controlador.php",
                        method: "POST",
                        data: datos,
                        cache: false,
                        contentType: false,
                        processData: false,
                        dataType:"json",
                        success:function(respuesta){
                            if(respuesta["CheckStock"] == true){
                                inserccionLineasDocumento();
                            } else {
                                swal({
                                    type: "error",
                                    title: "Error - No hay stock del artículo",
                                    showConfirmButton: true,
                                    confirmButtonText: "Cerrar",
                                    allowOutsideClick: false
                                })
                                //console.log(respuesta["Sql"]);
                            }

                        }
                    });
                }


            }



        });

        function inserccionLineasDocumento(){
            if(tipoPantalla == "0"){ // Vista no táctil
                // Incrementamos el número de la orden
                numeroOrden = numeroOrden + 5;

                // Enviamos los datos necesarios e insertamos la línea
                var datos = new FormData();
                datos.append("inserccionLineasDocumento", "true");
                datos.append("CodigoCliente", $("#tpvCodigoCliente").val());
                datos.append("SerieDocumento", $("#tpvCodigoTicketSerie").val());
                datos.append("NumeroDocumento", $("#tpvCodigoTicketNumero").val());
                datos.append("NumeroOrden", numeroOrden);

                if($("#tpvAnyadirArticuloCodigo").val().includes(configSeparador) == false) {
                    datos.append("CodigoArticulo", $("#tpvAnyadirArticuloCodigo").val());
                } else { // Modificar si el tipo de formato del código cambia || Condicion 1 es CodigoArticulo
                    var codigoIntroducidoSeparado = $("#tpvAnyadirArticuloCodigo").val().split(configSeparador);
                    var valorCondicion1 = codigoIntroducidoSeparado[0];
                    datos.append("CodigoArticulo", valorCondicion1);
                }

                datos.append("Partida", $("#tpvAnyadirArticuloPartida").val());
                datos.append("DescripcionArticulo", $("#tpvAnyadirArticuloDescripcion").val());
                datos.append("FechaCaduca", $("#tpvAnyadirArticuloFechaCaducidad").val());
                datos.append("Unidades", $("#tpvAnyadirArticuloUnidades").val());
                datos.append("CodigoColor", $("#tpvAnyadirArticuloColor").val());
                datos.append("CodigoTalla", $("#tpvAnyadirArticuloTalla").val());
                datos.append("Precio", $("#tpvAnyadirArticuloPrecio").val());
                datos.append("Descuento", $("#tpvAnyadirArticuloDescuento").val());
                $.ajax({
                    url:"controladores/pedidos.controlador.php",
                    method: "POST",
                    data: datos,
                    cache: false,
                    contentType: false,
                    processData: false,
                    dataType:"json",
                    success:function(respuesta){
                        if(respuesta["CheckInsertado"] == true){
                            actualizarDesgloseTicket();
                            $("#tpvAnyadirArticuloCodigo").val("");
                            $("#tpvAnyadirArticuloDescripcion").val("");
                            $("#tpvAnyadirArticuloImagen").removeAttr("src");
                            $("#tpvAnyadirArticuloUnidades").val("");
                            $("#tpvAnyadirArticuloPartida").attr("readonly", "readonly");
                            $("#tpvAnyadirArticuloPrecio").val("");
                            $("#tpvAnyadirArticuloDescuento").val("");
                            $("#tpvAnyadirArticuloFechaCaducidad").attr("readonly", "readonly");
                            $("#tpvAnyadirArticuloBtnAnyadir").attr("disabled", "disabled");
                            $("#descripcionArticulo-list").empty();
                            //$("#descripResultado-box").hide();
                            $("#descripResultado-box").hidden = true;
                        } else {
                            console.log("error");
                            // swal({ Salta error porque si, REVISAR
                            //     type: "error",
                            //     title: "Error - No se ha podido insertar la linea. Posible error en la conexión con el servidor",
                            //     showConfirmButton: true,
                            //     confirmButtonText: "Cerrar",
                            //     allowOutsideClick: false
                            // })
                            //console.log(respuesta["Sql"]);
                        }

                    }
                });

            } else {

                // Incrementamos el número de la orden
                numeroOrden = numeroOrden + 5;

                // Enviamos los datos necesarios e insertamos la línea
                var datos = new FormData();
                datos.append("inserccionLineasDocumento", "true");
                datos.append("CodigoCliente", $("#tpvTactilCodigoCliente").val());
                datos.append("SerieDocumento", $("#tpvTactilCodigoTicketSerie").val());
                datos.append("NumeroDocumento", $("#tpvTactilCodigoTicketNumero").val());
                datos.append("NumeroOrden", numeroOrden);

                if($("#tpvPanelPreConfirmacionArticuloCodigo").text().includes(configSeparador) == false) {
                    datos.append("CodigoArticulo", $("#tpvPanelPreConfirmacionArticuloCodigo").text());
                } else { // Modificar si el tipo de formato del código cambia || Condicion 1 es CodigoArticulo
                    var codigoIntroducidoSeparado = $("#tpvPanelPreConfirmacionArticuloCodigo").text().split(configSeparador);
                    var valorCondicion1 = codigoIntroducidoSeparado[0];
                    datos.append("CodigoArticulo", valorCondicion1);
                }

                datos.append("Partida", $("#tpvAnyadirArticuloPartida").val());
                datos.append("DescripcionArticulo", $("#tpvPanelPreConfirmacionArticuloDescripcion").text());
                datos.append("FechaCaduca", $("#tpvAnyadirArticuloFechaCaducidad").val());
                datos.append("Unidades", $("#tpvPanelPreConfirmacionArticuloCantidad").val());
                datos.append("CodigoColor", $("#tpvPanelPreConfirmacionArticuloColor").val());
                datos.append("CodigoTalla", $("#tpvPanelPreConfirmacionArticuloTalla").val());
                datos.append("Precio", $("#tpvPanelPreConfirmacionArticuloPrecioVenta").val());
                datos.append("Descuento", $("#tpvPanelPreConfirmacionArticuloDescuento").val());
                $.ajax({
                    url:"controladores/pedidos.controlador.php",
                    method: "POST",
                    data: datos,
                    cache: false,
                    contentType: false,
                    processData: false,
                    dataType:"json",
                    success:function(respuesta){
                        if(respuesta["CheckInsertado"] == true){
                            console.log("Insertado Linea");
                            actualizarDesgloseTicket();
                        } else {
                            swal({
                                type: "error",
                                title: "Error - No se ha podido insertar la linea. Posible error en la conexión con el servidor",
                                showConfirmButton: true,
                                confirmButtonText: "Cerrar",
                                allowOutsideClick: false
                            })
                            //console.log(respuesta["Sql"]);
                        }

                    }
                });
            }


        }



        /*=============================================
        Función para actualizar el desglose del ticket al insertar un artículo || VERSIÓN NO TÁCTIL
        =============================================*/
        function actualizarDesgloseTicket(){

            if(tipoPantalla == "0"){ // Vista no táctil

                // Actualizamos las líneas documento en el TPV
                var ticketTotal = 0;

                var datos = new FormData();
                datos.append("actualizarDesgloseTicketTPV", "true");
                datos.append("EjercicioDocumento", $("#tpvCodigoTicketEjercicio").val());
                datos.append("SerieDocumento", $("#tpvCodigoTicketSerie").val());
                datos.append("NumeroDocumento", $("#tpvCodigoTicketNumero").val());
                $.ajax({
                    url:"controladores/pedidos.controlador.php",
                    method: "POST",
                    data: datos,
                    cache: false,
                    contentType: false,
                    processData: false,
                    dataType:"json",
                    success:function(respuesta){
                        console.log(respuesta);
                         if(respuesta.data[0][12] == 1){
                            $("#btnRealizarAlbaran").css('display','block');
                            $("#tpvTecladoPagar").css('display','none');
                            $("#btnRealizarFactura").css('display','none');
                            $("#tpvTactilEliminarTicketActualBtn").css('display','none');
                            $("#tpvTactilRecuperarTicketBtn").css('display','block');
                            $("#btnRecuperarPedidoFinalizado").css('display','block');
                            $("#btnRealizarNuevoPedido").css('display','block');
                            
                            var datos = new FormData();
                            datos.append("comprobarEntregaACuenta", "true");
                            datos.append("EjercicioDocumento", $("#tpvCodigoTicketEjercicio").val());
                            datos.append("SerieDocumento", $("#tpvCodigoTicketSerie").val());
                            datos.append("NumeroDocumento", $("#tpvCodigoTicketNumero").val());
                            $.ajax({
                                url:"controladores/pedidos.controlador.php",
                                method: "POST",
                                data: datos,
                                cache: false,
                                contentType: false,
                                processData: false,
                                dataType:"json",
                                success:function(respuesta){
                                    if(respuesta['CheckEntregaAcuenta'] == true){
                                        
                                        $("#filaEntregaCuenta").css('display','block');
                                        $("#desgloseEntregaCuentaTPV").text(parseFloat(respuesta['importeEntregaCuenta'],2).toFixed(2));
                                    }else{
                                        $("#filaEntregaCuenta").css('display','none');
                                    }  
                                }
                            });

                            if(respuesta.data[0][11] == -1){
                                $("#btnRealizarAlbaran").css('display','none');
                                $("#tpvTecladoPagar").css('display','none');
                                $("#btnRealizarFactura").css('display','block');
                                $("#tpvTactilEliminarTicketActualBtn").css('display','none');
                                $("#tpvTactilRecuperarTicketBtn").css('display','block');
                                $("#btnRecuperarPedidoFinalizado").css('display','block');
    
                            }
                        }else{
                            $("#btnRealizarNuevoPedido").css('display','block');
                            $("#btnRealizarAlbaran").css('display','none');
                            $("#tpvTecladoPagar").css('display','block');
                            $("#btnRealizarFactura").css('display','none');
                            $("#tpvTactilEliminarTicketActualBtn").css('display','block');
                            $("#tpvTactilRecuperarTicketBtn").css('display','block');
                            $("#btnRecuperarPedidoFinalizado").css('display','block');
                            $("#filaEntregaCuenta").css('display','none');
                        }

                        let tablaPuntoVentaDesgloseTicketTbody = $("#tablaPuntoVentaDesgloseTicketTbody");
                        tablaPuntoVentaDesgloseTicketTbody.empty();

                        for (i = 0; i < respuesta.data.length; i++) {
                            // Recogemos la respuesta y la guardamos en variables
                            var desgloseCodigoArticulo = respuesta.data[i][0];
                            var desgloseDescripcionArticulo = respuesta.data[i][1];
                            var desgloseUnidades = respuesta.data[i][2];
                            var desglosePartida = respuesta.data[i][3];
                            var desgloseFechaCaduca = respuesta.data[i][4];
                            var desgloseColor = respuesta.data[i][5];
                            var desgloseTalla = respuesta.data[i][6];
                            if(respuesta.data[i][7] == .00){
                                var desglosePrecio = parseFloat(0.00);
                            } else {
                                var desglosePrecio = parseFloat(respuesta.data[i][7]);
                            }
                            if(respuesta.data[i][8] == .00){
                                var desgloseDescuento = parseFloat(0.00);
                            } else {
                                var desgloseDescuento = parseFloat(respuesta.data[i][8]);
                            }
                            var desgloseOrden = respuesta.data[i][9];
                            var desgloseIva = respuesta.data[i][10];

                            // Se actualiza el numero de orden actual, para que no haya problemas al insertar nuevos artículos y eliminarlos
                            numeroOrden = parseInt(desgloseOrden);

                            // Se calcula el descuento si lo tuviera
                            var desgloseImporte = 0;
                            var desgloseDescuentoEntreCien = desgloseDescuento / 100;
                            desgloseImporte = desglosePrecio - (desglosePrecio * desgloseDescuentoEntreCien);
                            desgloseImporte = desgloseImporte * desgloseUnidades;

                            var idArticuloBtnEliminar = "eliminarArticuloDesglose"+desgloseOrden;

                            // Construimos la tabla para mostrar el desglose
                            if(configTratamientoPartidas && configTratamientoTallaColores){
                                tablaPuntoVentaDesgloseTicketTbody.append("<tr>"+
                                    "<td>"+desgloseCodigoArticulo+"</td>"+
                                    "<td>"+desgloseDescripcionArticulo+"</td>"+
                                    "<td>"+desgloseUnidades+"</td>"+
                                    "<td>"+desglosePartida+"</td>"+
                                    "<td>"+desgloseFechaCaduca+"</td>"+
                                    "<td>"+desgloseColor+"</td>"+
                                    "<td>"+desgloseTalla+"</td>"+
                                    "<td>"+desglosePrecio.toFixed(2)+"</td>"+
                                    "<td>"+desgloseDescuento.toFixed(0)+"</td>"+
                                    "<td>"+desgloseImporte.toFixed(2)+"</td>"+
                                    "<td><button class='btn btn-danger' id='"+idArticuloBtnEliminar+"'><i class='fas fa-trash-alt'></i></button></td>"+
                                    "</tr>");
                            } else {
                                if(configTratamientoPartidas && !configTratamientoTallaColores){
                                    tablaPuntoVentaDesgloseTicketTbody.append("<tr>"+
                                        "<td>"+desgloseCodigoArticulo+"</td>"+
                                        "<td>"+desgloseDescripcionArticulo+"</td>"+
                                        "<td>"+desgloseUnidades+"</td>"+
                                        "<td>"+desglosePartida+"</td>"+
                                        "<td>"+desgloseFechaCaduca+"</td>"+
                                        "<td>"+desglosePrecio.toFixed(2)+"</td>"+
                                        "<td>"+desgloseDescuento.toFixed(0)+"</td>"+
                                        "<td>"+desgloseImporte.toFixed(2)+"</td>"+
                                        "<td><button class='btn btn-danger' id='"+idArticuloBtnEliminar+"'><i class='fas fa-trash-alt'></i></button></td>"+
                                        "</tr>");
                                } else {
                                    tablaPuntoVentaDesgloseTicketTbody.append("<tr>"+
                                        "<td>"+desgloseCodigoArticulo+"</td>"+
                                        "<td>"+desgloseDescripcionArticulo+"</td>"+
                                        "<td>"+desgloseUnidades+"</td>"+
                                        "<td>"+desgloseColor+"</td>"+
                                        "<td>"+desgloseTalla+"</td>"+
                                        "<td>"+desglosePrecio.toFixed(2)+"</td>"+
                                        "<td>"+desgloseDescuento.toFixed(0)+"</td>"+
                                        "<td>"+desgloseImporte.toFixed(2)+"</td>"+
                                        "<td><button class='btn btn-danger' id='"+idArticuloBtnEliminar+"'><i class='fas fa-trash-alt'></i></button></td>"+
                                        "</tr>");
                                }
                            }

                            // Actualizamos el valor que comprueba si hay líneas en el desglose
                            hayLineasDesglose = true;

                            // Sumamos el valor del total
                            ticketTotal = ticketTotal + Number.parseFloat(desgloseImporte.toFixed(2));

                            // En el botón de borrar buscamos el artículo pasándole la orden, ejercicio, serie y número
                            $("#"+idArticuloBtnEliminar).on("click",function(){

                                //console.log($(this).attr('id').replace('eliminarArticuloDesglose',''));

                                var datos = new FormData();
                                datos.append("eliminarArticuloDesglose", "true");
                                datos.append("EjercicioDocumento", $("#tpvCodigoTicketEjercicio").val());
                                datos.append("SerieDocumento", $("#tpvCodigoTicketSerie").val());
                                datos.append("NumeroDocumento", $("#tpvCodigoTicketNumero").val());
                                datos.append("Orden", $(this).attr('id').replace('eliminarArticuloDesglose',''));
                                datos.append("CodigoCliente", $("#tpvCodigoCliente").val());
                                datos.append("IdArticulo", desgloseCodigoArticulo);
                                $.ajax({
                                    url:"controladores/pedidos.controlador.php",
                                    method: "POST",
                                    data: datos,
                                    cache: false,
                                    contentType: false,
                                    processData: false,
                                    dataType:"json",
                                    success:function(respuesta){

                                        if(respuesta["CheckEliminado"] == true){ // Si se ha borrado, volvemos a llamar a esta función para recargar el desglose
                                            actualizarDesgloseTicket();
                                            hayLineasDesglose = false;
                                        } else { // Si no, muestra un error
                                            swal({
                                                type: "error",
                                                title: "Error - No se ha podido eliminar la línea. Posible error en la conexión con el servidor",
                                                showConfirmButton: true,
                                                confirmButtonText: "Cerrar",
                                                allowOutsideClick: false
                                            })
                                        }
                                    }
                                });
                            });
                        }
                        // Actualizamos el valor total con IVA
                        // ticketTotal = ticketTotal*(1+desgloseIva/100);
                        // if(isNaN(ticketTotal)){
                        //     ticketTotal = 0.00;
                        // }
                        $("#desgloseTicketTotalTPV").text(ticketTotal.toFixed(2));

                    }
                });

                // Actualizamos la cabecera en el TPV
                var datos = new FormData();
                datos.append("actualizarCabeceraDocumentoEnTPV", "true");
                datos.append("EjercicioDocumento", $("#tpvCodigoTicketEjercicio").val());
                datos.append("SerieDocumento", $("#tpvCodigoTicketSerie").val());
                datos.append("NumeroDocumento", $("#tpvCodigoTicketNumero").val());
                $.ajax({
                    url:"controladores/pedidos.controlador.php",
                    method: "POST",
                    data: datos,
                    cache: false,
                    contentType: false,
                    processData: false,
                    dataType:"json",
                    success:function(respuesta){

                        $("#tpvCodigoCliente,#tpvTactilCodigoCliente").val(respuesta["CodigoCliente"]);
                        $("#tpvNombreCliente,#tpvTactilNombreCliente").text("Nombre: "+respuesta["Nombre"]);
                        $("#tpvCifDniCliente,#tpvTactilCifDniCliente").text("CIF/DNI: "+respuesta["CifDni"]);
                        $("#tpvDomicilioCliente,#tpvTactilDomicilioCliente").text("Domicilio: "+respuesta["Domicilio"]);
                        $("#tpvEmailCliente,#tpvTactilEmailCliente").text("Email: "+respuesta["Email1"]);
                        $("#tpvTelefonoCliente,#tpvTactilTelefonoCliente").text("Telefono: "+respuesta["Telefono"]);
                        $("#fechaPedido").val(respuesta["FechaAlbaran"]);

                    }
                });

            } else { // Vista táctil

                // Actualizamos las líneas documento en el TPV
                var ticketTotal = 0;

                var datos = new FormData();
                datos.append("actualizarDesgloseTicketTPV", "true");
                datos.append("EjercicioDocumento", $("#tpvTactilCodigoTicketEjercicio").val());
                datos.append("SerieDocumento", $("#tpvTactilCodigoTicketSerie").val());
                datos.append("NumeroDocumento", $("#tpvTactilCodigoTicketNumero").val());
                $.ajax({
                    url:"controladores/pedidos.controlador.php",
                    method: "POST",
                    data: datos,
                    cache: false,
                    contentType: false,
                    processData: false,
                    dataType:"json",
                    success:function(respuesta){


                        let tpvTactiltablaPuntoVentaDesgloseTicketTbody = $("#tpvTactiltablaPuntoVentaDesgloseTicketTbody");
                        tpvTactiltablaPuntoVentaDesgloseTicketTbody.empty();

                        for (i = 0; i < respuesta.data.length; i++) {
                            // Recogemos la respuesta y la guardamos en variables
                            var desgloseCodigoArticulo = respuesta.data[i][0];
                            var desgloseDescripcionArticulo = respuesta.data[i][1];
                            var desgloseUnidades = respuesta.data[i][2];
                            var desglosePartida = respuesta.data[i][3];
                            var desgloseFechaCaduca = respuesta.data[i][4];
                            var desgloseColor = respuesta.data[i][5];
                            var desgloseTalla = respuesta.data[i][6];
                            if(respuesta.data[i][7] == .00){
                                var desglosePrecio = parseFloat(0.00);
                            } else {
                                var desglosePrecio = parseFloat(respuesta.data[i][7]);
                            }
                            if(respuesta.data[i][8] == .00){
                                var desgloseDescuento = parseFloat(0.00);
                            } else {
                                var desgloseDescuento = parseFloat(respuesta.data[i][8]);
                            }
                            var desgloseOrden = respuesta.data[i][9];
                            var desgloseIva = respuesta.data[i][10];

                            // Se actualiza el numero de orden actual, para que no haya problemas al insertar nuevos artículos y eliminarlos
                            numeroOrden = parseInt(desgloseOrden);

                            // Se calcula el descuento si lo tuviera
                            var desgloseImporte = 0;
                            var desgloseDescuentoEntreCien = desgloseDescuento / 100;
                            desgloseImporte = desglosePrecio - (desglosePrecio * desgloseDescuentoEntreCien);
                            desgloseImporte = desgloseImporte * desgloseUnidades;

                            var idArticuloBtnEliminar = "eliminarArticuloDesglose"+desgloseOrden;

                            // Construimos la tabla para mostrar el desglose
                            if(configTratamientoPartidas && configTratamientoTallaColores){
                                tpvTactiltablaPuntoVentaDesgloseTicketTbody.append("<tr>"+
                                    "<td>"+desgloseCodigoArticulo+"</td>"+
                                    "<td>"+desgloseDescripcionArticulo+"</td>"+
                                    "<td>"+desgloseUnidades+"</td>"+
                                    "<td>"+desglosePartida+"</td>"+
                                    "<td>"+desgloseFechaCaduca+"</td>"+
                                    "<td>"+desgloseColor+"</td>"+
                                    "<td>"+desgloseTalla+"</td>"+
                                    "<td>"+desglosePrecio.toFixed(2)+"</td>"+
                                    "<td>"+desgloseDescuento.toFixed(0)+"</td>"+
                                    "<td>"+desgloseImporte.toFixed(2)+"</td>"+
                                    "<td><button class='btn btn-danger' id='"+idArticuloBtnEliminar+"'><i class='fas fa-trash-alt'></i></button></td>"+
                                    "</tr>");
                            } else {
                                if(configTratamientoPartidas && !configTratamientoTallaColores){
                                    tpvTactiltablaPuntoVentaDesgloseTicketTbody.append("<tr>"+
                                        "<td>"+desgloseCodigoArticulo+"</td>"+
                                        "<td>"+desgloseDescripcionArticulo+"</td>"+
                                        "<td>"+desgloseUnidades+"</td>"+
                                        "<td>"+desglosePartida+"</td>"+
                                        "<td>"+desgloseFechaCaduca+"</td>"+
                                        "<td>"+desglosePrecio.toFixed(2)+"</td>"+
                                        "<td>"+desgloseDescuento.toFixed(0)+"</td>"+
                                        "<td>"+desgloseImporte.toFixed(2)+"</td>"+
                                        "<td><button class='btn btn-danger' id='"+idArticuloBtnEliminar+"'><i class='fas fa-trash-alt'></i></button></td>"+
                                        "</tr>");
                                } else {
                                    tpvTactiltablaPuntoVentaDesgloseTicketTbody.append("<tr>"+
                                        "<td>"+desgloseCodigoArticulo+"</td>"+
                                        "<td>"+desgloseDescripcionArticulo+"</td>"+
                                        "<td>"+desgloseUnidades+"</td>"+
                                        "<td>"+desgloseColor+"</td>"+
                                        "<td>"+desgloseTalla+"</td>"+
                                        "<td>"+desglosePrecio.toFixed(2)+"</td>"+
                                        "<td>"+desgloseDescuento.toFixed(0)+"</td>"+
                                        "<td>"+desgloseImporte.toFixed(2)+"</td>"+
                                        "<td><button class='btn btn-danger' id='"+idArticuloBtnEliminar+"'><i class='fas fa-trash-alt'></i></button></td>"+
                                        "</tr>");
                                }
                            }

                            // Actualizamos el valor que comprueba si hay líneas en el desglose
                            hayLineasDesglose = true;

                            // Sumamos el valor del total 
                            ticketTotal = ticketTotal + Number.parseFloat(desgloseImporte.toFixed(2));

                            // En el botón de borrar buscamos el artículo pasándole la orden, ejercicio, serie y número
                            $("#"+idArticuloBtnEliminar).on("click",function(){
                                console.log($(this).attr('id').replace('eliminarArticuloDesglose',''));

                                var datos = new FormData();
                                datos.append("eliminarArticuloDesglose", "true");
                                datos.append("EjercicioDocumento", $("#tpvCodigoTicketEjercicio").val());
                                datos.append("SerieDocumento", $("#tpvCodigoTicketSerie").val());
                                datos.append("NumeroDocumento", $("#tpvCodigoTicketNumero").val());
                                datos.append("Orden", $(this).attr('id').replace('eliminarArticuloDesglose',''));
                                $.ajax({
                                    url:"controladores/pedidos.controlador.php",
                                    method: "POST",
                                    data: datos,
                                    cache: false,
                                    contentType: false,
                                    processData: false,
                                    dataType:"json",
                                    success:function(respuesta){

                                        if(respuesta["CheckEliminado"] == true){ // Si se ha borrado, volvemos a llamar a esta función para recargar el desglose
                                            actualizarDesgloseTicket();
                                            hayLineasDesglose = false;
                                        } else { // Si no, muestra un error
                                            swal({
                                                type: "error",
                                                title: "Error - No se ha podido eliminar la línea. Posible error en la conexión con el servidor",
                                                showConfirmButton: true,
                                                confirmButtonText: "Cerrar",
                                                allowOutsideClick: false
                                            })
                                        }
                                    }
                                });
                            });
                        }
                        // Actualizamos el valor total con el cálculo del IVA
                        // ticketTotal = ticketTotal*(1+desgloseIva/100);
                        // if(isNaN(ticketTotal)){
                        //     ticketTotal = 0.00;
                        // }
                        $("#tpvTactilDesgloseTicketTotal").text(ticketTotal.toFixed(2));

                    }
                });

                // Actualizamos la cabecera en el TPV
                var datos = new FormData();
                datos.append("actualizarCabeceraDocumentoEnTPV", "true");
                datos.append("EjercicioDocumento", $("#tpvTactilCodigoTicketEjercicio").val());
                datos.append("SerieDocumento", $("#tpvTactilCodigoTicketSerie").val());
                datos.append("NumeroDocumento", $("#tpvTactilCodigoTicketNumero").val());
                $.ajax({
                    url:"controladores/pedidos.controlador.php",
                    method: "POST",
                    data: datos,
                    cache: false,
                    contentType: false,
                    processData: false,
                    dataType:"json",
                    success:function(respuesta){

                        $("#tpvCodigoCliente,#tpvTactilCodigoCliente").val(respuesta["CodigoCliente"]);
                        $("#tpvNombreCliente,#tpvTactilNombreCliente").text("Nombre: "+respuesta["Nombre"]);
                        $("#tpvCifDniCliente,#tpvTactilCifDniCliente").text("CIF/DNI: "+respuesta["CifDni"]);
                        $("#tpvDomicilioCliente,#tpvTactilDomicilioCliente").text("Domicilio: "+respuesta["Domicilio"]);
                        $("#tpvEmailCliente,#tpvTactilEmailCliente").text("Email: "+respuesta["Email1"]);
                        $("#tpvTelefonoCliente,#tpvTactilTelefonoCliente").text("Telefono: "+respuesta["Telefono"]);

                    }
                });

            }






        }





        /*=============================================
        BOTÓN PAGAR | MÉTODOS DE PAGO || VERSIÓN TÁCTIL
        =============================================*/
        $("#tpvTactilTecladoPagar").on("click", function(){

            // Si el ticket esá vacío, mostrará un mensaje de error
            if(hayLineasDesglose == false){
                swal({
                    type: "error",
                    title: "El ticket está vacío",
                    showConfirmButton: true,
                    confirmButtonText: "Cerrar",
                    allowOutsideClick: false
                });
            } else { // Si no, se procede a seleccionar los métodos de pago y se realiza el ticket
                // Mostramos el modal
                $('#modalEntregaAcuenta').modal('show');
                // Rellenamos el total que aparece en el modal
                $("#desgloseTicketTotalPagoTPV").text($("#tpvTactilDesgloseTicketTotal").text().replace('€',''));
                //$("#desgloseTicketTotalPagoTPV").text(ticketTotal.toFixed(2));


                /*=============================================
                CONTROL DE LOS MÉTODOS DE PAGO
                =============================================*/
                $("#pagoEfectivoTPV").on("click", function(){

                    // Ocultamos los demás métodos de pago y mostramos el seleccionado
                    $("#panelPagoTarjetaTPV").attr("hidden", "hidden");
                    $("#panelPagoEfectivoTPV").removeAttr("hidden");

                    // Función que controla el cambio del imput de la entrega y calcula el cambio
                    $("#panelPagoEfectivoEntregaTPV").on("input", function(){
                        var cambio = $("#panelPagoEfectivoEntregaTPV").val() - $("#desgloseTicketTotalPagoTPV").text();
                        $('#panelPagoEfectivoCambioTPV').text(cambio.toFixed(2));
                        if(cambio >= 0){ // Si el cambio 
                            $("#panelPagoEfectivoBtnTPV").removeAttr("disabled");
                        } else {
                            $("#panelPagoEfectivoBtnTPV").attr("disabled", "disabled");
                        }
                    });

                    $("#panelPagoEfectivoBtnTPV").on("click", function(){

                        var configCodigoEmpresa = 0;

                        var datos = new FormData();
                        datos.append("pagarYFinalizarTicketTPV", "true");
                        datos.append("EjercicioDocumento", $("#tpvTactilCodigoTicketEjercicio").val());
                        datos.append("SerieDocumento", $("#tpvTactilCodigoTicketSerie").val());
                        datos.append("NumeroDocumento", $("#tpvTactilCodigoTicketNumero").val());
                        datos.append("ImporteTotal", $("#desgloseTicketTotalPagoTPV").text());
                        datos.append("PagoEfectivo", true);
                        datos.append("PagoTarjeta", false);
                        $.ajax({
                            url:"controladores/pedidos.controlador.php",
                            method: "POST",
                            data: datos,
                            cache: false,
                            contentType: false,
                            processData: false,
                            dataType:"json",
                            success:function(respuesta){
                                if(respuesta["CheckCabecera"] == true ){

                                    $("#modalEntregaAcuenta").modal('toggle');

                                    configCodigoEmpresa = respuesta["CodigoEmpresa"];

                                    var parametros = "?NombreEmpleado="+$("#cabeceraNombreUsuario").text()+"&CodigoEmpresa="+configCodigoEmpresa+"&EjercicioDocumento="+$("#tpvCodigoTicketEjercicio").val()+"&SerieDocumento="+$("#tpvCodigoTicketSerie").val()+"&NumeroDocumento="+$("#tpvCodigoTicketNumero").val()+"&TotalAPagar="+$("#desgloseTicketTotalPagoTPV").text()+"&Recibido="+$("#panelPagoEfectivoEntregaTPV").val()+"&Cambio="+$("#panelPagoEfectivoCambioTPV").text()+"&PagadoCon=Efectivo";
                                    var impresionTicket = window.open("pdf/ticket.php"+parametros, "Ticket", "width=1000,height=800,screenX=10,fullscreen=yes");
                                    impresionTicket.focus();
                                    impresionTicket.print();
                                    //impresionTicket.close();


                                    swal({
                                        type: "success",
                                        title: "Venta realizada",
                                        showConfirmButton: true,
                                        confirmButtonText: "Cerrar",
                                        allowOutsideClick: false
                                    }).then(function(result){
                                        if (result.value) {

                                            window.location = "pedidos";

                                        }
                                    })
                                } else {
                                    $("#modalEntregaAcuenta").modal('toggle');
                                    swal({
                                        type: "error",
                                        title: "Error con la base de datos",
                                        showConfirmButton: true,
                                        confirmButtonText: "Cerrar",
                                        allowOutsideClick: false
                                    })
                                }
                            }
                        });



                    });


                });

                $("#pagoTarjetaTPV").on("click", function(){

                    // Ocultamos los demás métodos de pago y mostramos el seleccionado
                    $("#panelPagoEfectivoTPV").attr("hidden", "hidden");
                    $("#panelPagoTarjetaTPV").removeAttr("hidden");

                    $("#panelPagoTarjetaBtnTPV").on("click", function(){

                        var configCodigoEmpresa = 0;

                        var datos = new FormData();
                        datos.append("pagarYFinalizarTicketTPV", "true");
                        datos.append("EjercicioDocumento", $("#tpvCodigoTicketEjercicio").val());
                        datos.append("SerieDocumento", $("#tpvCodigoTicketSerie").val());
                        datos.append("NumeroDocumento", $("#tpvCodigoTicketNumero").val());
                        datos.append("ImporteTotal", $("#desgloseTicketTotalPagoTPV").text());
                        datos.append("PagoEfectivo", false);
                        datos.append("PagoTarjeta", true);
                        $.ajax({
                            url:"controladores/pedidos.controlador.php",
                            method: "POST",
                            data: datos,
                            cache: false,
                            contentType: false,
                            processData: false,
                            dataType:"json",
                            success:function(respuesta){
                                if(respuesta["CheckCabecera"] == true ){

                                    $("#modalEntregaAcuenta").modal('toggle');

                                    configCodigoEmpresa = respuesta["CodigoEmpresa"];

                                    var parametros = "?NombreEmpleado="+$("#cabeceraNombreUsuario").text()+"&CodigoEmpresa="+configCodigoEmpresa+"&EjercicioDocumento="+$("#tpvCodigoTicketEjercicio").val()+"&SerieDocumento="+$("#tpvCodigoTicketSerie").val()+"&NumeroDocumento="+$("#tpvCodigoTicketNumero").val()+"&TotalAPagar="+$("#desgloseTicketTotalPagoTPV").text()+"&Recibido="+$("#panelPagoEfectivoEntregaTPV").val()+"&Cambio="+$("#panelPagoEfectivoCambioTPV").text()+"&PagadoCon=Tarjeta";
                                    var impresionTicket = window.open("pdf/ticket.php"+parametros, "Ticket", "width=1000,height=800,screenX=10,fullscreen=yes");
                                    impresionTicket.focus();
                                    impresionTicket.print();
                                    //impresionTicket.close();


                                    swal({
                                        type: "success",
                                        title: "Venta realizada",
                                        showConfirmButton: true,
                                        confirmButtonText: "Cerrar",
                                        allowOutsideClick: false
                                    }).then(function(result){
                                        if (result.value) {

                                            window.location = "pedidos";

                                        }
                                    })
                                } else {
                                    $("#modalEntregaAcuenta").modal('toggle');
                                    swal({
                                        type: "error",
                                        title: "Error con la base de datos",
                                        showConfirmButton: true,
                                        confirmButtonText: "Cerrar",
                                        allowOutsideClick: false
                                    })
                                }
                            }
                        });



                    });

                });


            }


        });




        /*=============================================
        BOTÓN PAGAR | MÉTODOS DE PAGO || VERSIÓN NO TÁCTIL
        =============================================*/
        $("#tpvTecladoPagar").on("click", function(){

            // Si el ticket esá vacío, mostrará un mensaje de error
            if(hayLineasDesglose == false){
                swal({
                    type: "error",
                    title: "El ticket está vacío",
                    showConfirmButton: true,
                    confirmButtonText: "Cerrar",
                    allowOutsideClick: false
                });
            } else { // Si no, se procede a seleccionar los métodos de pago y se realiza el ticket
                // Mostramos el modal
                $('#modalEntregaAcuenta').modal('show');
                // Rellenamos el total que aparece en el modal
                $("#desgloseTotalPedido").text($("#desgloseTicketTotalTPV").text());


                /*=============================================
                CONTROL DE LOS MÉTODOS DE PAGO
                =============================================*/
                 
                $("#btnPagoEntregaPedido").on("click", function(){
                    var configCodigoEmpresa = 0;

                    var datos = new FormData();
                    datos.append("pagarYFinalizarTicketTPV", "true");
                    datos.append("EjercicioDocumento", $("#tpvCodigoTicketEjercicio").val());
                    datos.append("SerieDocumento", $("#tpvCodigoTicketSerie").val());
                    datos.append("NumeroDocumento", $("#tpvCodigoTicketNumero").val());
                    datos.append("ImporteTotal", $("#panelPagoEntregaPedido").val());
                    $.ajax({
                        url:"controladores/pedidos.controlador.php",
                        method: "POST",
                        data: datos,
                        cache: false,
                        contentType: false,
                        processData: false,
                        dataType:"json",
                        success:function(respuesta){
                            console.log(respuesta);
                            if(respuesta["CheckCabecera"] == true){

                                $("#modalEntregaAcuenta").modal('toggle');

                                configCodigoEmpresa = respuesta["CodigoEmpresa"];

                                // var parametros = "?NombreEmpleado="+$("#cabeceraNombreUsuario").text()+"&CodigoEmpresa="+configCodigoEmpresa+"&EjercicioDocumento="+$("#tpvCodigoTicketEjercicio").val()+"&SerieDocumento="+$("#tpvCodigoTicketSerie").val()+"&NumeroDocumento="+$("#tpvCodigoTicketNumero").val()+"&TotalAPagar="+$("#desgloseTicketTotalTPV").text()+"&Recibido="+$("#panelPagoEfectivoEntregaTPV").val()+"&Cambio="+$("#panelPagoEfectivoCambioTPV").text()+"&PagadoCon=Efectivo";
                                // var impresionTicket = window.open("pdf/ticket.php"+parametros, "Ticket", "width=1000,height=800,screenX=10,fullscreen=yes");
                                // impresionTicket.focus();
                                // impresionTicket.print();
                                //impresionTicket.close();
                                

                                swal({
                                    type: "success",
                                    title: "Pedido realizado ",
                                    showConfirmButton: true,
                                    confirmButtonText: "Cerrar",
                                    allowOutsideClick: false
                                }).then(function(result){
                                    if (result.value) {
                                        $("#panelPagoEntregaPedido").val(" ");
                                        $("#tpvTecladoPagar").css('display','none');
                                        $("#tpvTactilEliminarTicketActualBtn").css('display','none');
                                        $("#btnRecuperarPedidoFinalizado").css('display','block');
                                        $("#tpvTactilRecuperarTicketBtn").css('display','block');
                                        $("#btnRealizarNuevoPedido").css('display','block');
                                        $("#btnRealizarAlbaran").css('display','block');
                                        $("#btnRealizarFactura").css('display','none');
                                        //window.location = "pedidos";
                                       
                                    }
                                })
                            } else {
                                $("#modalEntregaAcuenta").modal('toggle');
                                swal({
                                    type: "error",
                                    title: "Error con la base de datos",
                                    showConfirmButton: true,
                                    confirmButtonText: "Cerrar",
                                    allowOutsideClick: false
                                })
                            }
                        }
                    });
                });

                $("#btnFinalizarSinEntrega").on("click", function(){
                    var datos = new FormData();
                    datos.append("finalizarPedidoSinEntrega", "true");
                    datos.append("EjercicioDocumento", $("#tpvCodigoTicketEjercicio").val());
                    datos.append("SerieDocumento", $("#tpvCodigoTicketSerie").val());
                    datos.append("NumeroDocumento", $("#tpvCodigoTicketNumero").val());
                    $.ajax({
                        url:"controladores/pedidos.controlador.php",
                        method: "POST",
                        data: datos,
                        cache: false,
                        contentType: false,
                        processData: false,
                        dataType:"json",
                        success:function(respuesta){
                            if(respuesta["CheckCabecera"] == true){

                                $("#modalEntregaAcuenta").modal('toggle');

                                configCodigoEmpresa = respuesta["CodigoEmpresa"];
                          
                                swal({
                                    type: "success",
                                    title: "Pedido realizado",
                                    showConfirmButton: true,
                                    confirmButtonText: "Cerrar",
                                    allowOutsideClick: false
                                }).then(function(result){
                                    if (result.value) {
                                        $("#panelPagoEntregaPedido").val(" ");
                                        $("#tpvTecladoPagar").css('display','none');
                                        $("#tpvTactilEliminarTicketActualBtn").css('display','none');
                                        $("#tpvTactilRecuperarTicketBtn").css('display','none');
                                        $("#btnRealizarNuevoPedido").css('display','block');
                                        $("#btnRealizarAlbaran").css('display','block');
                                       
                                    }
                                })
                            } else {
                                $("#modalEntregaAcuenta").modal('toggle');
                                swal({
                                    type: "error",
                                    title: "Error con la base de datos",
                                    showConfirmButton: true,
                                    confirmButtonText: "Cerrar",
                                    allowOutsideClick: false
                                })
                            }
                        }
                    });
                });

                
            }


        });

        /*=============================================
                    PASAR PEDIDO A ALBARÁN 
        =============================================*/
        $("#btnRealizarAlbaran").on("click", function(){
            $('#modalPuntoVentaMetodosDePago').modal('show');
                // Rellenamos el total que aparece en el modal
                $("#desgloseTicketTotalPagoTPV").text($("#desgloseTicketTotalTPV").text());
                //$("#desgloseTicketTotalPagoTPV").text(ticketTotal.toFixed(2));


                /*=============================================
                CONTROL DE LOS MÉTODOS DE PAGO
                =============================================*/
                $("#pagoEfectivoTPV").on("click", function(){

                    // Ocultamos los demás métodos de pago y mostramos el seleccionado
                    $("#panelPagoTarjetaTPV").attr("hidden", "hidden");
                    $("#panelPagoEfectivoTPV").removeAttr("hidden");

                    // Función que controla el cambio del imput de la entrega y calcula el cambio
                    $("#panelPagoEfectivoEntregaTPV").on("input", function(){
                        var cambio = $("#panelPagoEfectivoEntregaTPV").val() - $("#desgloseTicketTotalPagoTPV").text();
                        $('#panelPagoEfectivoCambioTPV').text(cambio.toFixed(2));
                        if(cambio >= 0){ // Si el cambio 
                            $("#panelPagoEfectivoBtnTPV").removeAttr("disabled");
                        } else {
                            $("#panelPagoEfectivoBtnTPV").attr("disabled", "disabled");
                        }
                    });

                    $("#panelPagoEfectivoBtnTPV").on("click", function(){

                        var configCodigoEmpresa = 0;

                
                        var datos = new FormData();
                        datos.append("albaranarPedido", "true");
                        datos.append("EjercicioDocumento", $("#tpvCodigoTicketEjercicio").val());
                        datos.append("SerieDocumento", $("#tpvCodigoTicketSerie").val());
                        datos.append("NumeroDocumento", $("#tpvCodigoTicketNumero").val());
                        datos.append("ImporteTotal", $("#desgloseTicketTotalPagoTPV").text());
                        datos.append("PagoEfectivo", true);
                        datos.append("PagoTarjeta", false);
                        $.ajax({
                            url:"controladores/pedidos.controlador.php",
                            method: "POST",
                            data: datos,
                            cache: false,
                            contentType: false,
                            processData: false,
                            dataType:"json",
                            success:function(respuesta){
                                if(respuesta["CheckCabecera"] == true ){
                                    $("#modalPuntoVentaMetodosDePago").modal('toggle');
                                    swal({
                                        type: "success",
                                        title: "Pedido albaranado",
                                        showConfirmButton: true,
                                        confirmButtonText: "Cerrar",
                                        allowOutsideClick: false
                                    })
                                    $("#btnRealizarAlbaran").css('display','none');
                                    $("#btnRealizarFactura").css('display','block');
                                    $("#tpvTactilRecuperarTicketBtn").css('display','block');
                                }else{
                                    $("#modalPuntoVentaMetodosDePago").modal('toggle');
                                    swal({
                                        type: "error",
                                        title: "Error con la base de datos",
                                        showConfirmButton: true,
                                        confirmButtonText: "Cerrar",
                                        allowOutsideClick: false
                                    })
                                }
                                
                            }
                        });
                    });
                });

                $("#pagoTarjetaTPV").on("click", function(){

                    var configCodigoEmpresa = 0;
                    // Ocultamos los demás métodos de pago y mostramos el seleccionado
                    $("#panelPagoEfectivoTPV").attr("hidden", "hidden");
                    $("#panelPagoTarjetaTPV").removeAttr("hidden");

            
                    var datos = new FormData();
                    datos.append("albaranarPedido", "true");
                    datos.append("EjercicioDocumento", $("#tpvCodigoTicketEjercicio").val());
                    datos.append("SerieDocumento", $("#tpvCodigoTicketSerie").val());
                    datos.append("NumeroDocumento", $("#tpvCodigoTicketNumero").val());
                    datos.append("ImporteTotal", $("#desgloseTicketTotalPagoTPV").text());
                    datos.append("PagoEfectivo", false);
                    datos.append("PagoTarjeta", true);
                    $.ajax({
                        url:"controladores/pedidos.controlador.php",
                        method: "POST",
                        data: datos,
                        cache: false,
                        contentType: false,
                        processData: false,
                        dataType:"json",
                        success:function(respuesta){
                            if(respuesta["CheckCabecera"] == true ){
                                $("#modalPuntoVentaMetodosDePago").modal('toggle');
                                swal({
                                    type: "success",
                                    title: "Pedido albaranado",
                                    showConfirmButton: true,
                                    confirmButtonText: "Cerrar",
                                    allowOutsideClick: false
                                })
                                $("#btnRealizarAlbaran").css('display','none');
                                $("#btnRealizarFactura").css('display','block');
                            }else{
                                $("#modalPuntoVentaMetodosDePago").modal('toggle');
                                swal({
                                    type: "error",
                                    title: "Error con la base de datos",
                                    showConfirmButton: true,
                                    confirmButtonText: "Cerrar",
                                    allowOutsideClick: false
                                })
                            }
                        }
                    });
                });
        });
                

        /*=============================================
                REALIZAR FACTURA DE UN ALBARÁN
        =============================================*/
        $("#btnRealizarFactura").on("click", function(){
            swal({
                type: "question",
                title: "¿Desea facturar el albaran?",
                showCancelButton: true,
                cancelButtonText: "Cancelar",
                showConfirmButton: true,
                confirmButtonText: "Aceptar",
                confirmButtonColor: '#0586f7',
                allowOutsideClick: false
            }).then(function(result){
                if (result.value) {
                    var datos = new FormData();
                    datos.append("facturarAlbaran", "true");
                    datos.append("EjercicioDocumento", $("#tpvCodigoTicketEjercicio").val());
                    datos.append("SerieDocumento", $("#tpvCodigoTicketSerie").val());
                    datos.append("NumeroDocumento", $("#tpvCodigoTicketNumero").val());
                    $.ajax({
                        url:"controladores/pedidos.controlador.php",
                        method: "POST",
                        data: datos,
                        cache: false,
                        contentType: false,
                        processData: false,
                        dataType:"json",
                        success:function(respuesta){
                            if(respuesta["CheckFase1"] == true){
                                configCodigoEmpresa = respuesta["CodigoEmpresa"];
                        
                                swal({
                                    type: "success",
                                    title: "Factura realizada",
                                    showConfirmButton: true,
                                    confirmButtonText: "Cerrar",
                                    allowOutsideClick: false
                                }).then(function(result){
                                    if (result.value) {
                                        $("#btnRealizarFactura").css('display','none');
                                        $("#btnRealizarNuevoPedido").css('display','block');                                      
                                        
                                    }
                                })
                            } else {
                                swal({
                                    type: "error",
                                    title: "Error con la base de datos",
                                    showConfirmButton: true,
                                    confirmButtonText: "Cerrar",
                                    allowOutsideClick: false
                                })
                            }
                        }
                    });
                }
            });

        });



        /*=============================================
        ELIMINAR TICKET ACTUAL || VERSIÓN NO TÁCTIL
        =============================================*/
        $("#tpvEliminarTicketActualBtn,#tpvTactilEliminarTicketActualBtn").on("click", function(){

            swal({
                type: "question",
                title: "¿Desea eliminar el pedido?",
                showCancelButton: true,
                cancelButtonText: "Cancelar",
                showConfirmButton: true,
                confirmButtonText: "Eliminar",
                confirmButtonColor: '#c41b08',
                allowOutsideClick: false
            }).then(function(result){
                if (result.value) {

                    var datos = new FormData();
                    datos.append("eliminarTicketActual", "true");
                    datos.append("EjercicioDocumento", $("#tpvCodigoTicketEjercicio").val());
                    datos.append("SerieDocumento", $("#tpvCodigoTicketSerie").val());
                    datos.append("NumeroDocumento", $("#tpvCodigoTicketNumero").val());
                    $.ajax({
                        url:"controladores/pedidos.controlador.php",
                        method: "POST",
                        data: datos,
                        cache: false,
                        contentType: false,
                        processData: false,
                        dataType:"json",
                        success:function(respuesta){

                            if(respuesta["CheckDelete"] == true){
                                swal({
                                    type: "success",
                                    title: "Se ha eliminado el ticket correctamente",
                                    showConfirmButton: true,
                                    confirmButtonText: "Cerrar",
                                    allowOutsideClick: false
                                }).then(function(result){
                                    if (result.value) {

                                        window.location = "pedidos";

                                    }
                                });
                            } else {
                                swal({
                                    type: "error",
                                    title: "No se ha podido eliminar el ticket",
                                    showConfirmButton: true,
                                    confirmButtonText: "Cerrar",
                                    allowOutsideClick: false
                                });
                            }

                        }
                    });
                }
            })

        });


        /*=============================================
        TICKET EN ESPERA || VERSIÓN NO TÁCTIL Y TÁCTIL
        =============================================*/
        $("#tpvTicketEnEsperaBtn,#tpvTactilTicketEnEsperaBtn").on("click", function(){
            swal({
                type: "success",
                title: "Se ha aparcado el ticket",
                showConfirmButton: true,
                confirmButtonText: "Cerrar",
                allowOutsideClick: false
            }).then(function(result){
                if (result.value) {
                    window.location = "pedidos";
                }
            })

        });


        /*=============================================
        RECUPERAR TICKETS || VERSIÓN NO TÁCTIL Y TÁCTIL
        =============================================*/
        $("#tpvRecuperarTicketBtn,#tpvTactilRecuperarTicketBtn").on("click", function(){

            // Carga de la lista de tickets en espera
            cargaListaTicketsEnEspera();

            // Mostramos el modal
            $('#modalPuntoVentaTicketsEnEspera').modal('show');

            // Al seleccionar una línea, recogemos y mostramos los datos identificativos del ticket y actualizamos el desglose
            $('.tablaPuntoVentaTicketsEnEspera tbody').on('click', 'tr', function () {
                var data = $('.tablaPuntoVentaTicketsEnEspera').DataTable().row( this ).data();

                $("#modalPuntoVentaTicketsEnEsperaSeleccionar").on('click', function(){
                    if(tipoPantalla == "0"){ // Vista no táctil
                        $("#tpvCodigoTicketEjercicio").val(data[0]);
                        $("#tpvCodigoTicketSerie").val(data[1]);
                        $("#tpvCodigoTicketNumero").val(data[2]);
                        $("#modalPuntoVentaTicketsEnEspera").modal('hide');
                        $("#tpvAnyadirArticuloCodigo").focus();
                    } else { // Vista táctil
                        $("#tpvTactilCodigoTicketEjercicio").val(data[0]);
                        $("#tpvTactilCodigoTicketSerie").val(data[1]);
                        $("#tpvTactilCodigoTicketNumero").val(data[2]);
                        $("#modalPuntoVentaTicketsEnEspera").modal('hide');
                        $("#tpvVistaArticulosBuscador").focus();
                        tpvIdFocoActual = "tpvVistaArticulosBuscador";
                    }

                    actualizarDesgloseTicket();
                })
            });
            $('.tablaPuntoVentaTicketsEnEspera tbody').on('dblclick', 'tr', function () {
                var data = $('.tablaPuntoVentaTicketsEnEspera').DataTable().row( this ).data();
                if(tipoPantalla == "0"){ // Vista no táctil
                    $("#tpvCodigoTicketEjercicio").val(data[0]);
                    $("#tpvCodigoTicketSerie").val(data[1]);
                    $("#tpvCodigoTicketNumero").val(data[2]);
                    $("#modalPuntoVentaTicketsEnEspera").modal('hide');
                    $("#tpvAnyadirArticuloCodigo").focus();
                } else { // Vista táctil
                    $("#tpvTactilCodigoTicketEjercicio").val(data[0]);
                    $("#tpvTactilCodigoTicketSerie").val(data[1]);
                    $("#tpvTactilCodigoTicketNumero").val(data[2]);
                    $("#modalPuntoVentaTicketsEnEspera").modal('hide');
                    $("#tpvVistaArticulosBuscador").focus();
                    tpvIdFocoActual = "tpvVistaArticulosBuscador";
                }


                actualizarDesgloseTicket();
            });

        });

        function cargaListaTicketsEnEspera(){

            $('.tablaPuntoVentaTicketsEnEspera').DataTable( {
                "ajax": "controladores/datatable.pedidos-pedidosPendientes.php",
                "deferRender": true,
                "retrieve": true,
                "processing": true,
                "language": {

                    "sProcessing":     "Procesando...",
                    "sLengthMenu":     "Mostrar _MENU_ registros",
                    "sZeroRecords":    "No se encontraron resultados",
                    "sEmptyTable":     "Ningún dato disponible en esta tabla",
                    "sInfo":           "Mostrando registros del _START_ al _END_ de un total de _TOTAL_",
                    "sInfoEmpty":      "Mostrando registros del 0 al 0 de un total de 0",
                    "sInfoFiltered":   "(filtrado de un total de _MAX_ registros)",
                    "sInfoPostFix":    "",
                    "sSearch":         "Buscar:",
                    "sUrl":            "",
                    "sInfoThousands":  ",",
                    "sLoadingRecords": "Cargando...",
                    "oPaginate": {
                        "sFirst":    "Primero",
                        "sLast":     "Último",
                        "sNext":     "Siguiente",
                        "sPrevious": "Anterior"
                    },
                    "oAria": {
                        "sSortAscending":  ": Activar para ordenar la columna de manera ascendente",
                        "sSortDescending": ": Activar para ordenar la columna de manera descendente"
                    }

                }

            } );

        }

        /*=============================================
        REALIZAR NUEVO PEDIDO 
        =============================================*/
        $("#btnRealizarNuevoPedido").on("click", function(){
            swal({
                type: "question",
                title: "¿Desea realizar un nuevo pedido?",
                showCancelButton: true,
                cancelButtonText: "Cancelar",
                showConfirmButton: true,
                confirmButtonText: "Aceptar",
                confirmButtonColor: '#3C8DBC',
                allowOutsideClick: false
            }).then(function(result){
                if (result.value) {
                    window.location = "pedidos";
                }
            })
        });        

        /*=============================================
        RECUPERAR PEDIDOS FINALIZADOS || VERSIÓN NO TÁCTIL Y TÁCTIL
        =============================================*/
        $("#btnRecuperarPedidoFinalizado").on("click", function(){

            // Carga de la lista de tickets en espera
            cargaListaPedidosFinalizados();

            // Mostramos el modal
            $('#modalPedidosFinalizados').modal('show');

            // Al seleccionar una línea, recogemos y mostramos los datos identificativos del ticket y actualizamos el desglose
            $('.tablaPedidosFinalizados tbody').on('click', 'tr', function () {
                var data = $('.tablaPedidosFinalizados').DataTable().row( this ).data();
                $("#btnSeleccionarPedidoFinalizado").on('click', function(){
                    if(tipoPantalla == "0"){ // Vista no táctil
                        $("#tpvCodigoTicketEjercicio").val(data[0]);
                        $("#tpvCodigoTicketSerie").val(data[1]);
                        $("#tpvCodigoTicketNumero").val(data[2]);
                        $("#modalPedidosFinalizados").modal('hide');
                        $("#tpvAnyadirArticuloCodigo").focus();
                    } else { // Vista táctil
                        $("#tpvTactilCodigoTicketEjercicio").val(data[0]);
                        $("#tpvTactilCodigoTicketSerie").val(data[1]);
                        $("#tpvTactilCodigoTicketNumero").val(data[2]);
                        $("#modalPedidosFinalizados").modal('hide');
                        $("#tpvVistaArticulosBuscador").focus();
                        tpvIdFocoActual = "tpvVistaArticulosBuscador";
                    }

                    $("#btnRealizarAlbaran").css('display','block');
                    $("#btnRealizarNuevoPedido").css('display','block');
                    $("#tpvTecladoPagar").css('display','none');
                    $("#tpvTactilEliminarTicketActualBtn").css('display','none');
                    $("#tpvTactilRecuperarTicketBtn").css('display','none');
                    $("#btnRecuperarPedidoFinalizado").css('display','none');
                    actualizarDesgloseTicket();
                })
            });
            $('.tablaPedidosFinalizados tbody').on('dblclick', 'tr', function () {
                var data = $('.tablaPedidosFinalizados').DataTable().row( this ).data();
                if(tipoPantalla == "0"){ // Vista no táctil
                    $("#tpvCodigoTicketEjercicio").val(data[0]);
                    $("#tpvCodigoTicketSerie").val(data[1]);
                    $("#tpvCodigoTicketNumero").val(data[2]);
                    $("#modalPedidosFinalizados").modal('hide');
                    $("#tpvAnyadirArticuloCodigo").focus();
                } else { // Vista táctil
                    $("#tpvTactilCodigoTicketEjercicio").val(data[0]);
                    $("#tpvTactilCodigoTicketSerie").val(data[1]);
                    $("#tpvTactilCodigoTicketNumero").val(data[2]);
                    $("#modalPedidosFinalizados").modal('hide');
                    $("#tpvVistaArticulosBuscador").focus();
                    tpvIdFocoActual = "tpvVistaArticulosBuscador";
                }
                $("#btnRealizarAlbaran").css('display','block');
                $("#btnRealizarNuevoPedido").css('display','block');
                $("#tpvTecladoPagar").css('display','none');
                $("#tpvTactilEliminarTicketActualBtn").css('display','none');
                $("#tpvTactilRecuperarTicketBtn").css('display','none');
                $("#btnRecuperarPedidoFinalizado").css('display','none');
                actualizarDesgloseTicket();
               
            });

        });

        function cargaListaPedidosFinalizados(){

            $('.tablaPedidosFinalizados').DataTable( {
                "ajax": "controladores/datatable.pedidos-pedidosFinalizados.php",
                "deferRender": true,
                "retrieve": true,
                "processing": true,
                "language": {

                    "sProcessing":     "Procesando...",
                    "sLengthMenu":     "Mostrar _MENU_ registros",
                    "sZeroRecords":    "No se encontraron resultados",
                    "sEmptyTable":     "Ningún dato disponible en esta tabla",
                    "sInfo":           "Mostrando registros del _START_ al _END_ de un total de _TOTAL_",
                    "sInfoEmpty":      "Mostrando registros del 0 al 0 de un total de 0",
                    "sInfoFiltered":   "(filtrado de un total de _MAX_ registros)",
                    "sInfoPostFix":    "",
                    "sSearch":         "Buscar:",
                    "sUrl":            "",
                    "sInfoThousands":  ",",
                    "sLoadingRecords": "Cargando...",
                    "oPaginate": {
                        "sFirst":    "Primero",
                        "sLast":     "Último",
                        "sNext":     "Siguiente",
                        "sPrevious": "Anterior"
                    },
                    "oAria": {
                        "sSortAscending":  ": Activar para ordenar la columna de manera ascendente",
                        "sSortDescending": ": Activar para ordenar la columna de manera descendente"
                    }

                }

            } );

        }


        /*=============================================
        RECUPERAR TICKETS / ELIMINARLOS || VERSIÓN NO TÁCTIL
        =============================================*/
        $(".tablaPuntoVentaTicketsEnEspera tbody").on("click", "div.btnEliminarTicket", function(){

            var EjercicioDocumento = $(this).attr("EjercicioDocumento");
            var SerieDocumento = $(this).attr("SerieDocumento");
            var NumeroDocumento = $(this).attr("NumeroDocumento");

            swal({
                type: "question",
                title: "¿Desea eliminar el pedido?",
                showCancelButton: true,
                cancelButtonText: "Cancelar",
                showConfirmButton: true,
                confirmButtonText: "Eliminar",
                confirmButtonColor: '#c41b08',
                allowOutsideClick: false
            }).then(function(result){
                if (result.value) {

                    var datos = new FormData();
                    datos.append("eliminarTicketActual", "true");
                    datos.append("EjercicioDocumento", EjercicioDocumento);
                    datos.append("SerieDocumento", SerieDocumento);
                    datos.append("NumeroDocumento", NumeroDocumento);
                    $.ajax({
                        url:"controladores/pedidos.controlador.php",
                        method: "POST",
                        data: datos,
                        cache: false,
                        contentType: false,
                        processData: false,
                        dataType:"json",
                        success:function(respuesta){

                            if(respuesta["CheckDelete"] == true){
                                swal({
                                    type: "success",
                                    title: "Se ha eliminado el ticket correctamente",
                                    showConfirmButton: true,
                                    confirmButtonText: "Cerrar",
                                    allowOutsideClick: false
                                }).then(function(result){

                                    cargaListaTicketsEnEspera();

                                });
                            } else {
                                swal({
                                    type: "error",
                                    title: "No se ha podido eliminar el ticket",
                                    showConfirmButton: true,
                                    confirmButtonText: "Cerrar",
                                    allowOutsideClick: false
                                });
                            }

                        }
                    });
                }
            });
        });



        /*=============================================
        LISTADO DE MODAL LISTA ARTICULOS
        =============================================*/
        $('.tablaPuntoVentaListaArticulos').DataTable( {
            "ajax": "controladores/datatable.puntoventa-listaarticulos.php",
            "deferRender": true,
            "retrieve": true,
            "processing": true,
            "language": {

                "sProcessing":     "Procesando...",
                "sLengthMenu":     "Mostrar _MENU_ registros",
                "sZeroRecords":    "No se encontraron resultados",
                "sEmptyTable":     "Ningún dato disponible en esta tabla",
                "sInfo":           "Mostrando registros del _START_ al _END_ de un total de _TOTAL_",
                "sInfoEmpty":      "Mostrando registros del 0 al 0 de un total de 0",
                "sInfoFiltered":   "(filtrado de un total de _MAX_ registros)",
                "sInfoPostFix":    "",
                "sSearch":         "Buscar:",
                "sUrl":            "",
                "sInfoThousands":  ",",
                "sLoadingRecords": "Cargando...",
                "oPaginate": {
                    "sFirst":    "Primero",
                    "sLast":     "Último",
                    "sNext":     "Siguiente",
                    "sPrevious": "Anterior"
                },
                "oAria": {
                    "sSortAscending":  ": Activar para ordenar la columna de manera ascendente",
                    "sSortDescending": ": Activar para ordenar la columna de manera descendente"
                }

            }

        } );


        /*=============================================
        LISTADO ARTICULOS
        =============================================*/
        // Al hacer click en la lupa del Código Articulo muestro el modal con el listado de las naciones
        $("#tpvAnyadirArticuloCodigoBtn").on("click", function(){
            $('#modalPuntoVentaListaArticulos').modal('show');
        });

        // Al hacer click en una fila y en Selecionar, cargamos el código en el input, ocultamos el modal y hacemos focus en el input
        // También se hace algo parecido al hacer doble click
        $('.tablaPuntoVentaListaArticulos tbody').on('click', 'tr', function () {
            var data = $('.tablaPuntoVentaListaArticulos').DataTable().row( this ).data();

            $("#modalPuntoVentaListaArticulosSeleccionar").on('click', function(){

                var datos = new FormData();
                datos.append("idArticulo", data[0]);
                $.ajax({
                    url:"controladores/articulos.controlador.php",
                    method: "POST",
                    data: datos,
                    cache: false,
                    contentType: false,
                    processData: false,
                    dataType:"json",
                    success:function(respuesta){
                        
                        $("#tpvAnyadirArticuloDescripcion").val(respuesta["DescripcionArticulo"]);
                        $("#tpvAnyadirArticuloImagen").attr("src","controladores/imagen_mostrar.php?ImagenExt="+respuesta["ImagenExt"]);
                        $("#tpvAnyadirArticuloUnidades").val("1");
                        if(typeof respuesta["TratamientoPartidas"] !== 'undefined' || respuesta["TratamientoPartidas"] == "0"){
                            $("#tpvAnyadirArticuloPartida").attr("readonly", "readonly");
                            $("#tpvAnyadirArticuloFechaCaducidad").attr("readonly", "readonly");
                        } else {
                            $("#tpvAnyadirArticuloPartida").removeAttr("readonly");
                            $("#tpvAnyadirArticuloFechaCaducidad").removeAttr("readonly");
                        }
                        obtenerColoresSegunArticulo(respuesta["CodigoArticulo"]);
                        var respuestaPrecioVenta = 0; // Esta variable y el if es para controlar el cómo se muestra el precio de compra
                        if(respuesta["PrecioVenta"] == .0000000000){
                            respuestaPrecioVenta = parseFloat(0.0000000000);
                        } else {
                            respuestaPrecioVenta = parseFloat(respuesta["PrecioVenta"]);
                        }
                        // obtenerTarifa(respuesta["CodigoArticulo"],$("#tpvTactilCodigoCliente").val());
                        $("#tpvAnyadirArticuloPrecio").val(respuestaPrecioVenta.toFixed(2));
                        if(respuesta["Descuento"] == .0000000000){
                            $("#tpvAnyadirArticuloDescuento").val("0");
                        }else{
                            $("#tpvAnyadirArticuloDescuento").val(respuesta["Descuento"]);
                        }
                        $("#tpvAnyadirArticuloBtnAnyadir").removeAttr("disabled");

                        if(typeof respuesta["CodigoColor_"] != "undefined"){ // Aquí entrará cuando se introduzca un CódigoAlternativo (para la pistola, con talla y colores)
                            $("#tpvAnyadirArticuloColor").val(respuesta["CodigoColor_"]);
                            $("#tpvAnyadirArticuloTalla").val(respuesta["CodigoTalla01_"]);
                            
                        }


                    }
                });

                $("#modalPuntoVentaListaArticulos").modal('hide');
                $("#tpvAnyadirArticuloUnidades").focus();
            })
        });
        $('.tablaPuntoVentaListaArticulos tbody').on('dblclick', 'tr', function () {
            var data = $('.tablaPuntoVentaListaArticulos').DataTable().row( this ).data();

            var datos = new FormData();
            datos.append("idArticulo", data[0]);
            $.ajax({
                url:"controladores/articulos.controlador.php",
                method: "POST",
                data: datos,
                cache: false,
                contentType: false,
                processData: false,
                dataType:"json",
                success:function(respuesta){
                    $("#tpvAnyadirArticuloCodigo").val(data[0]);
                    $("#tpvAnyadirArticuloDescripcion").val(respuesta["DescripcionArticulo"]);
                    $("#tpvAnyadirArticuloImagen").attr("src","controladores/imagen_mostrar.php?ImagenExt="+respuesta["ImagenExt"]);
                    $("#tpvAnyadirArticuloUnidades").val("1");
                    if(typeof respuesta["TratamientoPartidas"] !== 'undefined' || respuesta["TratamientoPartidas"] == "0"){
                        $("#tpvAnyadirArticuloPartida").attr("readonly", "readonly");
                        $("#tpvAnyadirArticuloFechaCaducidad").attr("readonly", "readonly");
                    } else {
                        $("#tpvAnyadirArticuloPartida").removeAttr("readonly");
                        $("#tpvAnyadirArticuloFechaCaducidad").removeAttr("readonly");
                    }
                    obtenerColoresSegunArticulo(respuesta["CodigoArticulo"]);
                    var respuestaPrecioVenta = 0; // Esta variable y el if es para controlar el cómo se muestra el precio de compra
                    if(respuesta["PrecioVenta"] == .0000000000){
                        respuestaPrecioVenta = parseFloat(0.0000000000);
                    } else {
                        respuestaPrecioVenta = parseFloat(respuesta["PrecioVenta"]);
                    }
                    // obtenerTarifa(respuesta["CodigoArticulo"],$("#tpvTactilCodigoCliente").val());
                    $("#tpvAnyadirArticuloPrecio").val(respuestaPrecioVenta.toFixed(2));
                    if(respuesta["Descuento"] == .0000000000){
                        $("#tpvAnyadirArticuloDescuento").val("0");
                    }else{
                        $("#tpvAnyadirArticuloDescuento").val(respuesta["Descuento"]);
                    }
                    $("#tpvAnyadirArticuloBtnAnyadir").removeAttr("disabled");

                    if(typeof respuesta["CodigoColor_"] != "undefined"){ // Aquí entrará cuando se introduzca un CódigoAlternativo (para la pistola, con talla y colores)
                        $("#tpvAnyadirArticuloColor").val(respuesta["CodigoColor_"]);
                        $("#tpvAnyadirArticuloTalla").val(respuesta["CodigoTalla01_"]);
                        console.log($("#tpvAnyadirArticuloColor").val());
                        console.log(respuesta["CodigoTalla01_"]);
                    }
                }
            });

            $("#modalPuntoVentaListaArticulos").modal('hide');
            $("#tpvAnyadirArticuloUnidades").focus();

        });
        /*=============================================
        FIN || LISTADO ARTICULOS
        =============================================*/

        /*=============================================
            CONVERTIR PEDIDO A ALBARÁN
        =============================================*/











        // Función sleep
        function sleep(ms) {
            return new Promise(resolve => setTimeout(resolve, ms));
        }






    });

})(jQuery);