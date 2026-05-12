@include('layouts.header')
@livewireStyles
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/1.9.2/tailwind.min.css" integrity="sha512-l7qZAq1JcXdHei6h2z8h8sMe3NbMrmowhOl+QkP3UhifPpCW2MC4M0i26Y8wYpbz1xD9t61MLT9L1N773dzlOA==" crossorigin="anonymous" />
@include('layouts.sidebar')
@include('layouts.navbar')

<?php
    if(session('codigoComisionista') == 0){
        header("Location: http://cronadis.abmscloud.com/");
        exit();
    }else{
?>


<div class="grid grid-cols-1 p-4 space-y-8 lg:gap-8 lg:space-y-0 lg:grid-cols-4 border-b w-full">
    <div class="col-span-4 bg-white rounded-md dark:bg-darker">
        <livewire:ofertascomi-datatable :post="session('codigoComisionista')"
        searchable=""
        exportable
        pedido
        />
    </div>
    <div class="hidden overflow-y-auto overflow-x-hidden mx-auto my-10 fixed z-50 items-center md:inset-0 h-modal sm:h-full" id="extralarge-modal">
        <div class="relative px-4 w-full mx-auto my-auto max-w-7xl h-full">
            <!-- Modal content -->
            <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                <!-- Modal header -->
                <div class="flex justify-between items-center p-5 rounded-t border-b dark:border-gray-600">
                    <h3 class="text-xl font-medium text-gray-900 dark:text-white">
                        Modificar Pedido
                    </h3>
                    <button type="button" onclick="cerrarModal()" id="xcerrarmodal" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-toggle="extralarge-modal">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                    </button>
                </div>
                <!-- Modal body -->
                <div class="p-6 space-y-6">
                    <div class="flex flex-wrap items-center">
                        <div class="relative w-full px-4 max-w-full flex-grow flex-1">                                
                            <span>
                                    <h3 id='pedidoSerieNumero' class="text-gray-900"></h3>
                                    <input hidden id="ejercicioMod">
                                    <input hidden id="serieMod">
                                    <input hidden id="numeroMod">
                                    <input hidden id="codigoClientePedido">
                                    <livewire:search-dropdownartimodificar :key="$randomKey" />                                        
                                </span>                                                                                               
                            </div>
                            <div class="relative w-full px-4 max-w-full flex-grow flex-1 text-right">
                                <h4 class="font-semibold text-base text-black">Base Imponible:
                                    <span>                                        
                                        <input type="number" class="font-semibold text-base text-black" id="baseImponibleModificar" value="0" readonly>
                                    </span>
                                </h4>
                                <h4 class="font-semibold text-base text-black">IVA:
                                    <span>
                                        <input type="number" class="font-semibold text-base text-black" id="ivaModificar" value="0" readonly>
                                    </span>
                                </h4>
                                <h4 id="recargoContenedorModificar" class="font-semibold text-base text-black" style="display: none;">Recargo Eq.:
                                    <span>
                                        <input type="number" class="font-semibold text-base text-black" id="recargoPedidoModificar" value="" readonly>
                                    </span>
                                </h4>
                            </div>
                            <div class="relative w-full px-4 max-w-full flex-grow flex-1 text-right">
                                <h4 class="font-semibold text-base text-black">Total:
                                    <span> 
                                        <input type="number" class="font-semibold text-base text-black" id="totalModificar" value="0" readonly>                                        
                                    </span>
                                </h4>                                                                                                                                                                                                                           
                            </div>
                        </div>
                        <div class="block w-full overflow-x-auto">
                            <table id="tablaModificarPedido" class="items-center bg-transparent w-full border-collapse ">
                                <thead>
                                <tr>
                                    <th class="px-6 bg-blueGray-50 text-black align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-left">
                                        Código Artículo
                                    </th>
                                    <th class="px-6 bg-blueGray-50 text-black align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-left">
                                        Descripción
                                    </th>                            
                                    <th class="px-6 bg-blueGray-50 text-black align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-left">
                                        Precio Unidad
                                    </th>
                                    <th class="px-6 bg-blueGray-50 text-black align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-left">
                                        Descuento
                                    </th>
                                    <th class="px-6 bg-blueGray-50 text-black align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-left">
                                        Precio Final
                                    </th>
                                    <th class="px-6 bg-blueGray-50 text-black align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-left">
                                        Cantidad
                                    </th>
                                    <th class="px-6 bg-blueGray-50 text-black align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-left">
                                        SubTotal
                                    </th>
                                </tr>
                                </thead>

                                <tbody id="lineasModificar">
                                
                                </tbody>

                            </table>
                        </div>
                    </div>
                    <!-- Modal footer -->
                    <div class="flex items-center p-6 space-x-2 rounded-b border-t border-gray-200 dark:border-gray-600">
                        <button onclick="pedidoMod()" class="flex items-center space-x-2 px-3 ml-3 mb-2  rounded-md bg-primary text-white text-xs leading-4 font-medium uppercase tracking-wider hover:bg-primary-dark focus:outline-none" style="height: 30px !important;">
                            <span>Modificar</span>
                        </button>
                        <button data-modal-toggle="extralarge-modal" onclick="cerrarModal()" type="button" id="cerrarmodal" class="flex items-center space-x-2 px-3 ml-3 mb-2  rounded-md bg-red-600 text-white text-xs leading-4 font-medium uppercase tracking-wider hover:bg-red-900 focus:outline-none"  style="height: 30px !important;"><span>Cerrar</span></button>
                    </div>
        </div>
    </div>        
</div>



<?php 
    }
?>
<script>
var indicadorIva = "I";

function sleep(ms) {
    return new Promise(resolve => setTimeout(resolve, ms));
}

function modalPedido(id){
    //console.log(id);    
    $('#lineasModificar').empty();

    var parametros = {
        "idOferta": id,
        "_token": $("meta[name='csrf-token']").attr("content")
    };
    //console.log(parametros);
    $.ajax({
        data: parametros,
        url: '/recuperarOferta',
        type: 'post',
        timeout: 2000,
        async: true,
        success: function(response) {

            console.log("response")
            console.log(response)
            console.log("response.lineasOferta[0].NumeroOferta+'/'+response.lineasOferta[0].SerieOferta+'/'+response.lineasOferta[0].EjercicioOferta")
            console.log(response.lineasOferta[0].NumeroOferta+'/'+response.lineasOferta[0].SerieOferta+'/'+response.lineasOferta[0].EjercicioOferta)
            
                $('#pedidoSerieNumero').text(response.lineasOferta[0].NumeroOferta+'/'+response.lineasOferta[0].SerieOferta+'/'+response.lineasOferta[0].EjercicioOferta);
                $('#ejercicioMod').val(response.lineasOferta[0].EjercicioOferta);
                $('#serieMod').val(response.lineasOferta[0].SerieOferta);
                $('#numeroMod').val(response.lineasOferta[0].NumeroOferta);
                $('#codigoClientePedido').val(response.codigocliente);
                $('#observaciones').val(response.observaciones);
                indicadorIva = response.IndicadorIva;
                
                console.log("response.lineasOferta.length")
                console.log(response.lineasOferta.length)
                
                for(var i = 0; i < response.lineasOferta.length; i++){
                    let precioFinalUnidad = response.lineasOferta[i].Precio - (response.lineasOferta[i].ImporteDescuento/response.lineasOferta[i].UnidadesPedidas);
                    if(isNaN(precioFinalUnidad))precioFinalUnidad = 0;

                    var totalLinea = response.lineasOferta[i].ImporteNeto;
                    if (precioFinalUnidad >= 1) {
                        precioFinalUnidad = parseFloat(precioFinalUnidad).toFixed(2);
                    }else{
                        precioFinalUnidad = parseFloat(precioFinalUnidad).toFixed(4);
                    }
                    if (totalLinea >= 1) {
                        totalLinea = parseFloat(totalLinea).toFixed(2);
                    }else{
                        totalLinea = parseFloat(totalLinea).toFixed(4);
                    }

                    var html = '';

                    html += '<tr class="lineamod" id="rm' + response.lineasOferta[i].CodigoArticulo.replaceAll(/ /g,"ç") +'¬'+response.lineasOferta[i].Orden+ '">' +
                            '<th class="border-t-0 px-6 align-middle border-l-0 border-r-0 text-xs whitespace-nowrap p-4 text-left text-black ">' +
                            '<span class="text-red-900" id="removemod|' + response.lineasOferta[i].CodigoArticulo.replaceAll(/ /g,"ç") +'¬'+response.lineasOferta[i].Orden+  '" onclick="borrarLineamod(this.id)"><i class="fas fa-trash-alt"></i></span>&nbsp' +
                            response.lineasOferta[i].CodigoArticulo +
                            '<input class="lineaPosicionmod" type="hidden" value="' + response.lineasOferta[i].CodigoArticulo.replaceAll(/ /g,"ç") + '">' +
                            '<input id="origenmod' + response.lineasOferta[i].CodigoArticulo.replaceAll(/ /g,"ç") +'¬'+response.lineasOferta[i].Orden+'" type="hidden" value="' + 1 + '">' +
                            '</th>' +
                            '<th class="border-t-0 px-6 align-middle border-l-0 border-r-0 text-xs whitespace-nowrap p-4 text-left text-black ">' +
                            response.lineasOferta[i].DescripcionArticulo +
                            '</th>' +
                            '<th class="border-t-0 px-6 align-middle border-l-0 border-r-0 text-xs whitespace-nowrap p-4 text-centre text-black ">' +
                            '<input type="number" name="' + response.lineasOferta[i].CodigoArticulo.replaceAll(/ /g,"ç") +'¬'+response.lineasOferta[i].Orden+  '" id="preciomod' + response.lineasOferta[i].CodigoArticulo.replaceAll(/ /g,"ç") +'¬'+response.lineasOferta[i].Orden+'" value="' + parseFloat(response.lineasOferta[i].Precio).toFixed(2) + '" style="width: 50px;" onchange="actualizarPreciomod(this.name)">' +
                            '</th>' +
                            '<th class="border-t-0 px-6 align-middle border-l-0 border-r-0 text-xs whitespace-nowrap p-4 text-centre text-black ">' +
                            '<input type="number" name="' + response.lineasOferta[i].CodigoArticulo.replaceAll(/ /g,"ç") +'¬'+response.lineasOferta[i].Orden+  '" id="descuentomod' + response.lineasOferta[i].CodigoArticulo.replaceAll(/ /g,"ç") +'¬'+response.lineasOferta[i].Orden+  '" value="' + parseFloat(response.lineasOferta[i].Descuento).toFixed(2) + '" style="width: 50px;" onchange="actualizarPreciomod(this.name)">%' +
                            '</th>' +
                            '<th class="border-t-0 px-6 align-middle border-l-0 border-r-0 text-xs whitespace-nowrap p-4 text-centre text-black ">' +
                            '<div id="precioUnidadmod' + response.lineasOferta[i].CodigoArticulo.replaceAll(/ /g,"ç") +'¬'+response.lineasOferta[i].Orden+  '">' + precioFinalUnidad + '</div>' +
                            '</th>' +
                            '<th class="border-t-0 px-6 align-middle border-l-0 border-r-0 text-xs whitespace-nowrap p-4 text-centre text-black ">' +
                            '<input type="number" name="' + response.lineasOferta[i].CodigoArticulo.replaceAll(/ /g,"ç") +'¬'+response.lineasOferta[i].Orden+  '" id="unidadesmod' + response.lineasOferta[i].CodigoArticulo.replaceAll(/ /g,"ç") +'¬'+response.lineasOferta[i].Orden+  '" value="' + parseFloat(response.lineasOferta[i].UnidadesPedidas).toFixed(2) + '" style="width: 50px;" onchange="actualizarPreciomod(this.name)">' +
                            '</th>' +
                            '<th class="border-t-0 px-6 align-middle border-l-0 border-r-0 text-xs whitespace-nowrap p-4 text-centre text-black ">' +
                            // '<div id="subtotalmod' + response.lineasOferta[i].CodigoArticulo.replaceAll(/ /g,"ç") +'¬'+response.lineasOferta[i].Orden+  '">' + totalLinea + '€</div>' +
                            '<div id="subtotalmod' + response.lineasOferta[i].CodigoArticulo +'¬'+response.lineasOferta[i].Orden+  '">' +
                            "<p id='contenidoInicialArticulo" + response.lineasOferta[i].CodigoArticulo +'¬'+response.lineasOferta[i].Orden +"'>" + totalLinea + "</p>" +
                            '</div>' +
                            '<input class="subtotalmod" name="'+response.lineasOferta[i].GrupoIva+'" type="hidden"  id="subtotal2mod' + response.lineasOferta[i].CodigoArticulo.replaceAll(/ /g,"ç") +'¬'+response.lineasOferta[i].Orden+  '" value="' + totalLinea + '">' +
                            '<input class="recargomod" type="hidden"  id="recargomod' + response.lineasOferta[i].CodigoArticulo.replaceAll(/ /g,"ç") +'¬'+response.lineasOferta[i].Orden+  '" value="' + response.lineasOferta[i].Recargo + '">' +
                            '<input class="cantidadRecargomod" type="hidden"  id="cantidadRecargomod' + response.lineasOferta[i].CodigoArticulo.replaceAll(/ /g,"ç") +'¬'+response.lineasOferta[i].Orden+  '" value="' + response.lineasOferta[i].CuotaRecargo + '">' +
                            '<input class="precioCompraArticu" type="hidden"  id="precioCompraArticu' + response.lineasOferta[i].CodigoArticulo +'¬'+response.lineasOferta[i].Orden + '" value="' + response.lineasOferta[i].PrecioCompra + '">' +
                            '</th>' +
                            '</tr>';
                    $('#lineasModificar').append(html);
                    

                    //console.log(response.lineasOferta[i].CodigoArticulo)
                }         

                    calcularTotalMod();
        }
        
    });

    $('#extralarge-modal').show();
} 

function cerrarModal(){
    $('#extralarge-modal').hide();    
}

async function selectCodigoArticuloModificable(producto,art,precio){

        
    let cliente = $('#codigoClientePedido').val();
    //console.log(cliente);
    var recargo = 0;
    var cantidadRecargo = 0;
    var descuento = 0;

    var parametros = {
        "cliente": cliente,
        "codigoProducto":producto,
        "_token": $("meta[name='csrf-token']").attr("content")
    };

    $.ajax({
        data: parametros,
        url: '/ivayrecargo',
        type: 'post',
        timeout: 2000,
        async: true,
        success: function(response) {
            console.log(response);
            recargo = response[0].Recargo;
        }
    });

    var parametros = {
        "CodigoCliente": cliente,
        "CodigoArticulo":producto,
        "_token": $("meta[name='csrf-token']").attr("content")
    };

    console.log(parametros);

    $.ajax({
        data: parametros,
        url: '/comprobarTarifas',
        type: 'post',
        timeout: 2000,
        async: true,
        success: function(respon) {
            console.log(respon)
            descuento = respon.Descuento1;
            precio = respon.precioVenta;
        }
    });

    await sleep(800);

    cantidadRecargo = precio * recargo/100;

    const options2 = { style: 'currency', currency: 'EUR' };
    const numberFormat2 = new Intl.NumberFormat('es-ES', options2);
    console.log(producto);        
    $('#articuloinputmodificar').val('')       
    $(".articuloResultadoModificable-box").hide();   

    let lineas =document.querySelectorAll('.subtotalmod');
    let orden = ((lineas.length)+1)*5;


    var html;

    html += '<tr class="lineamod" id="rm' + producto.replaceAll(" ", "ç") +'¬' +orden +'">' +
                '<th class="border-t-0 px-6 align-middle border-l-0 border-r-0 text-xs whitespace-nowrap p-4 text-left text-black ">' +
                '<span class="text-red-900" id="removemod/' + producto.replaceAll(" ", "ç") +'¬'+ orden +'" onclick="borrarLineamod(this.id)"><i class="fas fa-trash-alt"></i></span>&nbsp' +
                producto +
                '<input class="lineaPosicionmod" type="hidden" value="' + producto.replaceAll(" ", "ç") + '">' +
                '<input id="origenmod' + producto.replaceAll(" ", "ç") +'¬'+ orden +'" type="hidden" value="' + 0 + '">' +
                '</th>' +
                '<th class="border-t-0 px-6 align-middle border-l-0 border-r-0 text-xs whitespace-nowrap p-4 text-left text-black ">' +
                art +
                '</th>' +
                '<th class="border-t-0 px-6 align-middle border-l-0 border-r-0 text-xs whitespace-nowrap p-4 text-centre text-black ">' +
                '<input type="number" name="' + producto.replaceAll(" ", "ç") +'¬'+ orden +'" id="preciomod' + producto.replaceAll(" ", "ç") +'¬'+ orden +'" value="' + parseFloat(precio).toFixed(2) + '" style="width: 50px;" onchange="actualizarPreciomod(this.name)">' +
                '</th>' +
                '<th class="border-t-0 px-6 align-middle border-l-0 border-r-0 text-xs whitespace-nowrap p-4 text-centre text-black ">' +
                '<input type="number" name="' + producto.replaceAll(" ", "ç") +'¬'+ orden +'" id="descuentomod' + producto.replaceAll(" ", "ç") +'¬'+ orden +'" value="' + parseFloat(descuento).toFixed(2) + '" style="width: 50px;" onchange="actualizarPreciomod(this.name)">%' +
                '</th>' +
                '<th class="border-t-0 px-6 align-middle border-l-0 border-r-0 text-xs whitespace-nowrap p-4 text-centre text-black ">' +
                '<div id="precioUnidadmod' + producto.replaceAll(" ", "ç") +'¬'+ orden +'">' + parseFloat(precio).toFixed(2) + '</div>' +
                '</th>' +
                '<th class="border-t-0 px-6 align-middle border-l-0 border-r-0 text-xs whitespace-nowrap p-4 text-centre text-black ">' +
                '<input type="number" name="' + producto.replaceAll(" ", "ç") +'¬'+ orden +'" id="unidadesmod' + producto.replaceAll(" ", "ç") +'¬'+ orden +'" value="' + parseFloat(1.00).toFixed(2) + '" style="width: 50px;" onchange="actualizarPreciomod(this.name)">' +
                '</th>' +
                '<th class="border-t-0 px-6 align-middle border-l-0 border-r-0 text-xs whitespace-nowrap p-4 text-centre text-black ">' +
                '<div id="subtotalmod' + producto.replaceAll(" ", "ç") +'¬'+ orden +'">' + parseFloat(precio).toFixed(2) + '€</div>' +
                '<input class="subtotalmod" type="hidden"  id="subtotal2mod' + producto.replaceAll(" ", "ç") +'¬'+ orden +'" value="' + precio + '">' +
                '<input class="recargomod" type="hidden"  id="recargomod' + producto.replaceAll(" ", "ç") +'¬'+ orden +'" value="' + recargo + '">' +
                '<input class="cantidadRecargomod" type="hidden"  id="cantidadRecargomod' + producto.replaceAll(" ", "ç") +'¬'+ orden +'" value="' + cantidadRecargo + '">' +
                '</th>' +
                '</tr>';
    $('#lineasModificar').append(html);
    calcularTotalMod(); 

}
    
function borrarLineamod(e){
    let id = e.split('|')
    console.log(id[1])
    let codigoArticulo = id[1].split('¬');

    if($('#origenmod'+id[1]).val() == 1){

        var parametros = {
            "numero":$('#numeroMod').val(),
            "serie":$('#serieMod').val(),
            "ejercicio":$('#ejercicioMod').val(),
            "codigoArticulo": codigoArticulo[0].replaceAll("ç",""),
            "orden": codigoArticulo[1],
            "_token": $("meta[name='csrf-token']").attr("content")
        };

        console.log(parametros);

        $.ajax({
            data: parametros,
            url: '/eliminarOfertamod',
            type: 'post',
            timeout: 2000,
            async: true,
            success: function(response) {
                if(response == 1){
                    $('#cerrarmodal').prop("disabled",true).removeClass('bg-red-600').removeClass('hover:bg-red-900');
                    $('#cerrarmodal').addClass('bg-gray-600').addClass('hover:bg-gray-900');
                    $('#xcerrarmodal').prop("disabled",true).css('background-color','gray');
                }else{
                    Swal.fire({
                        title: 'Error al eliminar línea',
                        text: 'Ha ocurrido un problema al borrar la línea',
                        icon: 'error',
                        confirmButtonText: 'Cerrar'
                    })
                }            
            }
        })

    }

    $('#rm'+id[1]+'').remove();   
    calcularTotalMod();        
    // if($('#lineasModificar').find('tr').length == 0){
    //     $('#lineasModificar').hide();
    // }   
    
}

async function actualizarPreciomod(e) {
    console.log(e)

    let precio = $('#preciomod' + e + '').val();
    let descuento = $('#descuentomod' + e + '').val();
    let unidades = $('#unidadesmod' + e + '').val();
    let recargo = $('#recargomod' + e + '').val();

    let precioUnidad = precio * (1 - (descuento / 100));
    let subtotal = precioUnidad * unidades;
    let subRecargo = subtotal * recargo/100;

    console.log(subRecargo);

    $('#precioUnidadmod' + e + '').empty();
    $('#precioUnidadmod' + e + '').append(precioUnidad.toFixed(2));
    $('#subtotalmod' + e + '').empty();
    $('#subtotalmod' + e + '').append(subtotal.toFixed(2) + '€');
    $('#subtotal2mod' + e + '').val(subtotal.toFixed(2));
    $('#cantidadRecargomod'+e+'').val(subRecargo.toFixed(2))

    //await sleep(300);

    let pretotal = document.querySelectorAll('.subtotalmod');
    let cantidadRecargo = document.querySelectorAll('.cantidadRecargomod');
    console.log(pretotal);

    var total = 0;
    var total_recargo = 0;
    for (i = 0; i < pretotal.length; i++) {
        if (pretotal[i].value != 0) {
            total += parseFloat(pretotal[i].value);
        }
    } 

    for (i = 0; i < cantidadRecargo.length; i++) {
        if (cantidadRecargo[i].value != 0) {
            total_recargo += parseFloat(cantidadRecargo[i].value);
        }
    } 
    var iva = total * 0.21;
    var totalIva = total * 1.21 + total_recargo;            

    $('#totalModificar').val(totalIva.toFixed(2));
    $('#baseImponibleModificar').val(total.toFixed(2));
    $('#ivaModificar').val(iva.toFixed(2));
    $('#recargoPedidoModificar').val(total_recargo.toFixed(2));
    
}

function calcularTotalMod(){

    let pretotal = document.querySelectorAll('.subtotalmod');   
    let cantidadRecargo = document.querySelectorAll('.cantidadRecargomod');


    var total = 0;
    var total_recargo = 0;
    for (i = 0; i < pretotal.length; i++) {
        if (pretotal[i].value != 0) {
            total += parseFloat(pretotal[i].value);
        }
    } 

    for (i = 0; i < cantidadRecargo.length; i++) {
        if (cantidadRecargo[i].value != 0) {
            total_recargo += parseFloat(cantidadRecargo[i].value);
        }
    }

    var iva = total * 0.21;
    var totalIva = total * 1.21 + total_recargo;   

    $('#totalModificar').val(totalIva.toFixed(2));
    $('#baseImponibleModificar').val(total.toFixed(2));
    $('#ivaModificar').val(iva.toFixed(2));
    $('#recargoPedidoModificar').val(total_recargo.toFixed(2));
    if(total_recargo > 0){
        $('#recargoContenedorModificar').show();
    }
}

function pedidoMod (){

    let lineas = document.querySelectorAll('.lineamod');
    var correoCli = '';
    var guids = new Array();
    for (let i = 0; i < lineas.length; i++) {

        var articulo = lineas[i].id;
        console.log(articulo.charAt(0));
        console.log(articulo.charAt(1));

        if(articulo.charAt(0) == 'r'){
            if(articulo.charAt(1)== 'm'){
                var narticulo = articulo.slice(2);
                //narticulo = articulo.slice(-1);
                console.log('sin r '+narticulo);
                articulo = narticulo;
            }
        }                

        var datos = {};
        var a = articulo;
        var b = $('#preciomod' + a + '').val();
        var c = $('#descuentomod' + a + '').val();
        var d = $('#unidadesmod' + a + '').val();
        var e = $('#origenmod' + a + '').val();
        var f = $('#recargomod'+a+'').val();

        datos.guid = a.replaceAll("ç", " ");
        datos.precio = b;
        datos.descuento = c;
        datos.unidades = d;
        datos.origen = e;
        datos.recargo = f;

        guids.push(datos);
    }

    //console.log(guids);

    var parametros = {
        "numero":$('#numeroMod').val(),
        "serie":$('#serieMod').val(),
        "ejercicio":$('#ejercicioMod').val(),
        "indicadorIva" : indicadorIva,
        "lineasPosicion": guids,
        "_token": $("meta[name='csrf-token']").attr("content")
    };

    console.log(parametros);

    $.ajax({
        data: parametros,
        url: '/ofertamod',
        type: 'post',
        timeout: 3000,
        async: true,
        success: function(response) {
            console.log(response);   
            cerrarModal();
            $('#cerrarmodal').prop("disabled",false).addClass('bg-red-600').addClass('hover:bg-red-900');
            $('#xcerrarmodal').prop("disabled",false).css('background-color','white');    
            
            correoCli = response['correo'];

            const inputOptions = {
                '0': 'Propuesta Pedido',
                '1': 'Cerrar Pedido'                
            };
            
            Swal.fire({
                title: 'Estado del pedido',
                input: 'radio',
                inputOptions: inputOptions,
                confirmButtonColor: '#3085d6',
                allowOutsideClick: false,
                inputValidator: (value) => {
                    if (!value) {
                    return 'Necesita elegir un estado '
                    }else{
                        
                        console.log(value);
                        $("#refrescarTablaPedido").trigger('click');                          

                        var estado = {
                            "numero":$('#numeroMod').val(),
                            "serie":$('#serieMod').val(),
                            "ejercicio":$('#ejercicioMod').val(),
                            "estado":value,
                            "_token": $("meta[name='csrf-token']").attr("content")
                        };

                        $.ajax({
                            data: estado,
                            url: '/estadoPedido',
                            type: 'post',
                            timeout: 7000,
                            async: true,
                            success: function(response) {
                                if(response == 0){
                                    Swal.fire({
                                        title: 'Pedido Exitoso!',
                                        icon: 'success',
                                        text: '¿Desea enviar el pedido por correo?',
                                        input: 'email',
                                        inputValue: correoCli,
                                        inputPlaceholder: 'Insertar Correo Electrónico',
                                        //showCancelButton: true,
                                        confirmButtonText: 'Enviar Correo',
                                        confirmButtonColor: '#3085d6'
                                    }).then(function(value) {
                                        console.log(value);
                                        if (value['isConfirmed'] == true) {
                                            
                                            let correo = value['value'];

                                            var parametros = {
                                            "numero":$('#numeroMod').val(),
                                                "serie":$('#serieMod').val(),
                                                "ejercicio":$('#ejercicioMod').val(),
                                                "correos": correo,
                                                "_token": $("meta[name='csrf-token']").attr("content")
                                            };
                                            console.log(parametros)

                                            $.ajax({
                                                data: parametros,
                                                url: '/correoArticulosPedidos',
                                                type: 'post',
                                                timeout: 7000,
                                                async: true,
                                                success: function(response) {

                                                    Swal.fire({
                                                        title: 'Enviado!',
                                                        text: 'Correo enviado a ' + value['value'],
                                                        icon: 'success',
                                                        confirmButtonText: 'Cerrar'
                                                    })                                                   

                                                },
                                                error: function(XMLHttpRequest, textStatus, errorThrown) {

                                                    console.log(XMLHttpRequest, textStatus, errorThrown)
                                                    Swal.fire({
                                                        title: 'Error Al Enviar pedido por correo!',
                                                        text: 'Recarga la Página',
                                                        icon: 'error',
                                                        confirmButtonText: 'Cerrar'
                                                    })
                                                }
                                            });
                                        }
                                    })
                                }
                            }
                        })
                    }
                }
            })

        }
        
    })
}

function enviarEmailPedido(id){
    
    
    
    Swal.fire({
        title: 'Enviar Copia de Pedido!',            
        text: '¿facilite un correo para enviar el pedido?',
        input: 'email',
        inputPlaceholder: 'Insertar Correo Electrónico',
        //showCancelButton: true,
        confirmButtonText: 'Enviar Correo',
        confirmButtonColor: '#3085d6'
    }).then(function(value) {
        console.log(value);
        if (value['isConfirmed'] == true) {
            
            let correo = value['value'];                

            var param = {
                "idPedido":id,                    
                "_token": $("meta[name='csrf-token']").attr("content")
            };

            $.ajax({
                data: param,
                url: './datosPedido',
                type: 'post',
                timeout: 2000,
                async: true,
                success: function(response){
                    
                    var parametros = {
                    "ejercicio": response[0].EjercicioPedido,
                    "serie": response[0].SeriePedido,
                    "numero": response[0].NumeroPedido,
                    "correos": correo,
                    "_token": $("meta[name='csrf-token']").attr("content")
                    };
                    console.log(parametros)

                    $.ajax({
                        data: parametros,
                        url: './correoPedido',
                        type: 'post',
                        timeout: 4000,
                        async: true,
                        success: function(response) {

                            Swal.fire({
                                title: 'Enviado!',
                                text: 'Correo enviado a ' + value['value'],
                                icon: 'success',
                                confirmButtonText: 'Cerrar'
                            })

                        },
                        error: function(XMLHttpRequest, textStatus, errorThrown) {

                            console.log(XMLHttpRequest, textStatus, errorThrown)
                            Swal.fire({
                                title: 'Correo enviado puede ser que tarde un poco!',
                                text: 'Correo enviado a ' + value['value'],
                                icon: 'success',
                                confirmButtonText: 'Cerrar'
                            })
                        }
                    });

                }
            })
            
        }
    })
                                
}

function enviarEmailOferta(id){
    
    
    
    Swal.fire({
        title: 'Enviar Copia de Oferta!',            
        text: '¿facilite un correo para enviar la oferta?',
        input: 'email',
        inputPlaceholder: 'Insertar Correo Electrónico',
        //showCancelButton: true,
        confirmButtonText: 'Enviar Correo',
        confirmButtonColor: '#3085d6'
    }).then(function(value) {
        console.log(value);
        if (value['isConfirmed'] == true) {
            
            let correo = value['value'];                

            var param = {
                "idOferta":id,                    
                "_token": $("meta[name='csrf-token']").attr("content")
            };

            $.ajax({
                data: param,
                url: '/datosOferta',
                type: 'post',
                timeout: 2000,
                async: true,
                success: function(response){
                    let ejercicio = response[0].EjercicioOferta;
                    let serie = response[0].SerieOferta;
                    let numero = response[0].NumeroOferta;

                    var parametros = {
                    "ejercicio": response[0].EjercicioOferta,
                    "serie": response[0].SerieOferta,
                    "numero": response[0].NumeroOferta,
                    "correos": correo,
                    "_token": $("meta[name='csrf-token']").attr("content")
                    };
                    console.log(parametros)

                    $.ajax({
                        data: parametros,
                        url: '/correoOferta',
                        type: 'post',
                        timeout: 4000,
                        async: true,
                        success: function(response) {
                            Swal.fire({
                                title: 'Enviado!',
                                text: 'Correo enviado a ' + value['value'],
                                icon: 'success',
                                confirmButtonText: 'Cerrar'
                            })
                        },
                        error: function(XMLHttpRequest, textStatus, errorThrown) {
                            console.log(XMLHttpRequest, textStatus, errorThrown)
                            Swal.fire({
                                title: 'Correo enviado puede ser que tarde un poco!',
                                text: 'Correo enviado a ' + value['value'],
                                icon: 'success',
                                confirmButtonText: 'Cerrar'
                            })
                        }
                    });
                }
            })
            
        }
    })
                                
}

function convertirEnPedido(id){
    Swal.fire({
            title: 'Quieres aprobar la oferta?',
            text: "Una vez aceptado este proceso no es reversible! ",
            icon: 'info',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Si'
        }).then((result) => {
        if (result.isConfirmed) {

            var param = {
                "idOferta":id,                    
                "_token": $("meta[name='csrf-token']").attr("content")
            };

            $.ajax({
                data: param,
                url: '/aprobarOferta',
                type: 'post',
                //timeout: 2000,
                async: true,
                beforeSend: function () {
                    Swal.fire({
                            title: 'Ejecutando proceso',
                            showCancelButton: false,
                            showConfirmButton: false,
                            icon: 'info',
                            //confirmButtonText: 'Cerrar'
                        })
                },
                success: function(response){
                    console.log(response);
                    if(response == 'La oferta no se puede aprobar porque es procedente de un potencial'){
                        Swal.fire({
                            title: 'Error al Aprobar',
                            text: response,
                            icon: 'warning',
                            confirmButtonText: 'Cerrar'
                        })
                    }else{

                        $("#refrescarTablaPedido").trigger('click');
                        $("modificar"+id).css('display', 'none');

                        const inputOptions = {
                            '0': 'Modificar Lineas',
                            '1': 'Cerrar Pedido'                
                        };

                        Swal.fire({
                            title: '¿Desea pasar todas las lineas de la oferta a pedido?',
                            text: 'En caso de que sí, pasaran todas a pedido modificable y podrás editar las lineas desde pedidos',
                            input: 'radio',
                            inputOptions: inputOptions,
                            confirmButtonColor: '#3085d6',
                            allowOutsideClick: false,
                            inputValidator: (value) => {
                            if (!value) {
                            return 'Necesita elegir un estado '
                            }else{                                                                                

                                var estado = {
                                    "numero":response.numero,
                                    "serie":response.serie,
                                    "ejercicio":response.ejercicio,
                                    "estado":value,
                                    "_token": $("meta[name='csrf-token']").attr("content")
                                };

                                $.ajax({
                                    data: estado,
                                    url: '/estadoPedido',
                                    type: 'post',
                                    timeout: 7000,
                                    async: true,
                                    success: function(response) {
                                        if(response == 0){
                                            Swal.fire({
                                                title: 'Oferta Aprobada!',
                                                text: 'La oferta se encuentra como pedido',
                                                icon: 'success',
                                                confirmButtonText: 'Cerrar'
                                            })
                                        }
                                    }
                                })
                            }
                        }
                        });                        
                    }

                },
                error: function(XMLHttpRequest, textStatus, errorThrown) {

                    //console.log(XMLHttpRequest, textStatus, errorThrown)                    
                    Swal.fire({
                        title: 'Error al Aprobar',
                        text: 'Ha surgido un problema al crear el pedido',
                        icon: 'warning',
                        confirmButtonText: 'Cerrar'
                    })
                }
            });
                        
        }
    })
}
</script>

@livewireScripts
@include('layouts.footer')
@include('layouts.panels')