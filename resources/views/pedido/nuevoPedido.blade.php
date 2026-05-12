<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRM CRONADIS</title>
    <link rel="icon" href="{{asset('media/images/cronadis2.png')}}" type="image/gif">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <!-- CSRF Token AÑADIMOS TOKEN PARA PODER ENVIAR FORMULARIO DE LOGUEO POR POST Y EVITAR ERROR-->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    

    <!-- Estilos propios generales -->
    <link rel="stylesheet" href="{{asset('css/pedidos/propios.css')}}">

    <link
      href="https://fonts.googleapis.com/css2?family=Cairo:wght@200;300;400;600;700;900&display=swap"
      rel="stylesheet"
    />  
    <link href="https://unpkg.com/tailwindcss@^2/dist/tailwind.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="./css/tailwind.css" />
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/gh/alpine-collective/alpine-magic-helpers@0.5.x/dist/component.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/clipboard.js/2.0.8/clipboard.min.js"></script>
    <script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.7.3/dist/alpine.min.js" defer></script>
    <script src="https://kit.fontawesome.com/2a20cc777c.js" crossorigin="anonymous"></script>
    <!-- JQUERY -->
    <script src="{{asset('js/jquery-3.6.0.js')}}"></script> 
    
</head>

<style>
    .pop-up {
        margin-right: 5%;
        max-height: 320px;
        background-color: rgba(48, 48, 48, 0.1);
        overflow-y: scroll;
        cursor: pointer;
        position: fixed;
    }

    #close {
        color: red;
        cursor: pointer;
    }

    .info {
        color: #0a53be;
    }

    .invoice {
        position: relative;
        background-color: #FFF;
        min-height: 680px;
        padding: 15px
    }

    .invoice header {
        padding: 10px 0;
        margin-bottom: 20px;
        border-bottom: 1px solid #3989c6
    }

    .invoice .company-details {
        text-align: right
    }

    .invoice .company-details .name {
        margin-top: 0;
        margin-bottom: 0
    }

    .invoice .contacts {
        margin-bottom: 20px
    }

    .invoice .invoice-to {
        text-align: left
    }

    .invoice .invoice-to .to {
        margin-top: 0;
        margin-bottom: 0
    }

    .invoice .invoice-details {
        text-align: right
    }

    .invoice .invoice-details .invoice-id {
        margin-top: 0;
        color: #3989c6
    }

    .invoice main {
        padding-bottom: 50px
    }

    .invoice main .thanks {
        margin-top: -100px;
        font-size: 2em;
        margin-bottom: 50px
    }

    .invoice main .notices {
        padding-left: 6px;
        border-left: 6px solid #3989c6
    }

    .invoice main .notices .notice {
        font-size: 1.2em
    }

    .invoice table {
        width: 100%;
        border-collapse: collapse;
        border-spacing: 0;
        margin-bottom: 20px
    }

    .invoice table td,
    .invoice table th {
        padding: 15px;
        background: #eee;
        border-bottom: 1px solid #fff
    }

    .invoice table th {
        white-space: nowrap;
        font-weight: 400;
        font-size: 16px
    }

    .invoice table td h3 {
        margin: 0;
        font-weight: 400;
        color: #3989c6;
        font-size: 1.2em
    }

    .invoice table .qty,
    .invoice table .total,
    .invoice table .unit {
        text-align: right;
        font-size: 1.2em
    }

    .invoice table .no {
        color: #fff;
        font-size: 1.6em;
        background: #3989c6
    }

    .invoice table .unit {
        background: #ddd
    }

    .invoice table .total {
        background: #3989c6;
        color: #fff
    }

    .invoice table tbody tr:last-child td {
        border: none
    }

    .invoice table tfoot td {
        background: 0 0;
        border-bottom: none;
        white-space: nowrap;
        text-align: right;
        padding: 10px 20px;
        font-size: 1.2em;
        border-top: 1px solid #aaa
    }

    .invoice table tfoot tr:first-child td {
        border-top: none
    }

    .invoice table tfoot tr:last-child td {
        color: #3989c6;
        font-size: 1.4em;
        border-top: 1px solid #3989c6
    }

    .invoice table tfoot tr td:first-child {
        border: none
    }

    .invoice footer {
        width: 100%;
        text-align: center;
        color: #777;
        border-top: 1px solid #aaa;
        padding: 8px 0
    }

    .alfondo {
        position: relative;
        bottom: 0px;
    }

    @media (hover: none) and (pointer: coarse) {
        .estiloDivTotalBaseTPV .estiloDivTotalIvaTPV .estiloDivTotalPrecioTPV{
            font-size: 10px;
        }  
    }

    @media print {
    @page { margin: 0.3cm; size: landscape;}
    body { padding: 0.5cm; }
    
}
 
    
</style>

<body>
    <div x-data="setup()" x-init="$refs.loading.classList.add('hidden');">
        <div
            x-ref="loading"
            class="fixed inset-0 z-50 flex items-center justify-center text-2xl font-semibold text-white bg-primary-darker"
        >
            Cargando DRD.....
        </div>

        <input type="hidden" id="codigoCliente" name="codigoCliente">
        <div hidden id="imprimir">
            <div id="cabeceraImprimir">
                <div class="invoice">
                    <div style="min-width: 600px">
                        
                        <div class="flex mb-4">
                            <div class="w-1/2 p-2 text-left">
                                <a target="_blank" href="">
                                    <img src="{{asset('media/images/surembalaje.png')}}" style="width:17%;" data-holder-rendered="true" />
                                </a>
                            </div>
                            <div class="w-1/2 p-2 text-right">
                                <div class="company-details">
                                    <h2 class="name">
                                        <a target="_blank">
                                            DRD SSL.
                                        </a>
                                    </h2>
                                    <div>Ctra. Sevilla-Málaga, km5 Pol. Ind. la Red</div>
                                    <div>C/ La Red Uno, 17 41500 Alcalá de Guadaira (SEVILLA)</div>
                                    <div>954 475 942</div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group col-md-12">
                            <hr style="width:100%;margin-bottom: 1%; color:blue; border:4px;">
                        </div>

                        <div class="content-wrapper">
                            <section class="content">
                                <!-- TPV NO TACTIL -->
                                <div class="box" id="tpvNoTactil">
                                    <div class="box-body">
                                        <!-- Primera columna general -->
                                        <div class="col-md-12 ">

                                            <div class="row">
                                                <div class="row contacts">
                                                    <div class="col invoice-to" id="datosCliente">
                                                        <div class="text-gray-light">Cliente:</div>
                                                        <h3 class="to" id="razonSocialCliente2"> </h3>
                                                        <div class="address" id="direccionCliente2"></div>
                                                        <div class="email" id="emailCliente2"></div>
                                                    </div>                                                
                                                </div>
                                            </div>

                                            <div class="form-group col-md-12">
                                                <hr style="width:100%;margin-bottom: 1%; border:4px;">
                                            </div>

                                            <div style="width:100%" id="tablaImprimir">


                                            
                                            </div>
                                            <div class="row" id="totalImprimir">

                                            </div>
                                                                                    
                                        </div>                                    
                                    </div>
                                </div>
                            </section>
                        </div>
                    </div>                     
                </div>    
            </div>    
            
        </div>
        <div id="invoice">
            <div class="invoice">
                <div style="min-width: 600px">
                    <header id=cabecera>                    
                        <div class="flex mb-4">
                            <div class="w-1/2 p-2 text-left">
                                <a target="_blank" href="">
                                    <img src="{{asset('media/images/surembalaje.png')}}" style="width:17%;" data-holder-rendered="true" />
                                </a>
                            </div>
                            <div class="w-1/2 p-2 text-right">
                                <div class="company-details">
                                    <h2 class="name">
                                        <a target="_blank">
                                            DRD SSL.
                                        </a>
                                    </h2>
                                    <div>Ctra. Sevilla-Málaga, km5 Pol. Ind. la Red</div>
                                    <div>C/ La Red Uno, 17 41500 Alcalá de Guadaira (SEVILLA)</div>
                                    <div>954 475 942</div>
                                </div>
                            </div>
                        </div>
                    </header>            
                        
                    <div class="flex-wrap">
                        <section class="content">
                            <!-- TPV NO TACTIL -->
                            <div class="box" id="tpvNoTactil">
                                <div class="flex mb-4">

                                    <!-- Primera columna general -->
                                    <div class="w-3/4 sm:5/8 p-2 border-right">

                                        <div class="flex-wrap">
                                            <div class="flex flex-row contacts">
                                                <div class=" invoice-to" id="datosCliente">
                                                    <div class="text-gray-light">Cliente:</div>
                                                    <h2 class="to" id="razonSocialCliente"> </h2>
                                                    <div class="address" id="direccionCliente"></div>
                                                    <div class="email"><a href="mailto:" id="emailCliente"></a></div>
                                                </div>
                                                <div class=" invoice-details">
                                                    <h1 id="columnaCabeceraCodigo" class="invoice-id" hidden>


                                                        <?php                                                    
                                                        use App\Http\Controllers\PedidoController;
                                                        use Illuminate\Support\Facades\Mail;

                                                        //$numPedido = PedidoController::codigoPedidoNuevo('PW');
                                                        //echo date('Y') . "/" . "PW" . '/' . $numPedido;
                                                        //echo "<input type='hidden' id='codigoPedido' value='" . $numPedido . "'>";
                                                        //echo "<input type='hidden' id='seriePedido' value='" . "PW" . "'>";
                                                        echo "<input type='hidden' id='codigoCliente' value=''>";
                                                        $total = 0;
                                                        $observaciones = "";
                                                        $serieBorrador = "";
                                                        $numeroPedido = "";
                                                        $numeroPedidoBorrador = "";
                                                        ?>
                                                    </h1>
                                                    
                                                </div>
                                                
                                                <div class="w-full"></div>
                                                <div class="w-full grid grid-row-3">
                                                    <div class="flex justify-end">
                                                        <button id="printInvoice" class="btn"><i class="fa fa-print" style="size:30%;"></i></button>
                                                    </div>                                                
                                                    <div class="grid grid-cols-1 mt-5" style="display: none" id="conSerie"">                                                                                
                                                        
                                                        <div class="mt-1 flex flex-end rounded-md shadow-sm mt-5 mb-2">
                                                                                                                                                    
                                                            <input id="busquedaProductoPedido" name="busquedaProductoPedido" type="text" class="focus:ring-indigo-500 focus:border-indigo-500 flex-2 block w-full rounded-l-md rounded-none sm:text-sm border-gray-300" placeholder="Codigo Articulo" autofocus autocomplete="off">                                                                                                                                                                   
                                                            <span class="inline-flex items-center px-3 border border-gray-300 bg-gray-50 text-gray-500 text-sm" >-</span>
                                                        
                                                            <input id="busquedaProductoPedidoDescripicion" name="busquedaProductoPedidoDescripicion" type="text" class="focus:ring-indigo-500 focus:border-indigo-500 flex-2 block w-full sm:text-sm border-gray-300" placeholder="Buscar producto" autofocus autocomplete="off">
                                                            
                                                            <div x-data="{ show: false }">
                                                                <div class="flex justify-between mt-2">
                                                                    <span @click={show=true} class="inline-flex items-center px-3 border border-gray-300 rounded-none rounded-r-md bg-gray-50 text-gray-500 text-sm"><i class="fa fa-search"></i></span>                                                                 
                                                                </div>
                                                                <div x-show="show" tabindex="0" class="z-10 overflow-auto left-0 top-0 bottom-0 right-0 h-full fixed">
                                                                    <div  @click.away="show = false" class="z-20 relative p-3 mx-auto my-0 max-w-full" style="width: 1000px;">
                                                                        <div class="bg-white rounded shadow-lg border flex flex-col overflow-hidden">
                                                                            <button @click={show=false} id="cerrarArticulosBuscador" class="fill-current h-6 w-6 absolute right-0 top-0 m-6 font-3xl font-bold">&times;</button>                    
                                                                            <div class="px-6 py-3 text-xl border-b font-bold">
                                                                                Articulos                                                               
                                                                                <div class="p-6 flex-grow">
                                                                                    @include('pedido.articulosBuscador')  
                                                                                </div>                  
                                                                            </div>
                                                                            <div class="px-6 py-3 border-t">
                                                                                <div class="flex justify-end">                                                                                
                                                                                    <button @click={show=false} type="button" class=" w-25 p-4 bg-red-500 hover:bg-red-700 text-center text-white font-bold font-heading uppercase rounded-md transition duration-200">Cerrar</Button>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>                                                
                                                                </div>                                                            
                                                            </div>
                                                        </div>
                                                                                                            
                                                        <div class="pop-up rounded-bottom z-10"  id="modalProductoPedido" style="display: none"><i class="far fa-times-circle cerrarSalidaProductoPedido" id="close"></i>
                                                            <div class="pop-up-wrap">
                                                                <div class="salidaProductoPedido">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>                                                    
                                                </div>
                                            </div>
                                        </div>

                                                                            

                                        <div class="form-group w-full">
                                            <hr style="width:100%;margin-bottom: 1%;">
                                        </div>
                                        
                                        <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                                            <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400" id="tabla-articulos" border="0" cellspacing="0" cellpadding="0">
                                                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                                    <tr>
                                                        <th scope="col" class="px-6 py-3" id="columnaCabeceraCodigo">
                                                            <div id="cabeceraDatos"> </div>
                                                            <?php    
                                                            $total = 0;
                                                            $observaciones = "";
                                                            $serieBorrador = "";
                                                            $numeroPedido = "";
                                                            $numeroPedidoBorrador = "";
                                                            ?>
                                                        </th>
                                                        <th scope="col" class="px-6 py-3 text-center">Artículo</th>
                                                        <th hidden scope="col" class="px-6 py-3">Partida</th>
                                                        <th scope="col" class="px-6 py-3 text-right">P. Unidad</th>
                                                        <th scope="col" class="px-6 py-3 text-right">Descuento</th>
                                                        <th scope="col" class="px-6 py-3 text-right">P.F unidad</th>
                                                        <th scope="col" class="px-6 py-3 text-right">Cantidad </th>
                                                        <th scope="col" class="px-6 py-3" id="udsDevolucion" style="display: none">Uds. devolución</th>
                                                        <th scope="col" class="px-6 py-3 text-right">Subtotal</th>
                                                        <th scope="col" class="px-6 py-3" id="totalDevolucion" style="display: none">Total devolución</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="listadoArticuloPedido">
                                                </tbody>                                            
                                            </table>
                                        </div>
                                    </div>

                                    <!-- Segunda columna general -->
                                    <div class="w-1/4 sm:3/8 p-1">
                                        <div class="p-3 md:p-5 bg-blue-300">                                        
                                            <h2 class="mb-6 text-4xl font-bold font-heading text-white">Pedido</h2>
                                            <div id="sinSerie"><h3 class="text-red-500">Elige una serie</h3></div>
                                            <div class="flex mb-8 items-center justify-between pb-2 border-b border-blue-100">
                                                <div class="grid grid-cols-1 gap-2">
                                                    <div class="col-span-8 sm:col-span-4">
                                                        <label for="serie" class="block text-sm font-medium text-gray-700"> Serie </label>
                                                        <div class="mt-1 flex rounded-md shadow-sm mb-2">
                                                            <input type="text" class="focus:ring-indigo-500 focus:border-indigo-500 flex-2 block w-full rounded-l-md rounded-none sm:text-sm border-gray-300" id="tpvCodigoTicketEjercicio" name="tpvCodigoTicketEjercicio" size="1" value="<?php echo date("Y"); ?>" readonly>
                                                            <span class="inline-flex items-center px-3 border border-gray-300 bg-gray-50 text-gray-500 text-sm">/</span>
                                                            <!-- <input type="text" class="form-control" id="tpvCodigoTicketSerie" name="tpvCodigoTicketSerie" size="1"> -->
                                                            <select class="focus:ring-indigo-500 focus:border-indigo-500 flex-2 block w-full sm:text-sm border-gray-300" id="seriePedido" name="seriePedido" onchange="contador(this.value)" >
                                                                <option value="">Selecciona Serie...</option>
                                                                <?php 
                                                                    $serie = PedidoController::seriePedido();
                                                                ?>                                                    
                                                                @foreach($serie as $series)
                                                                    <option value="{{$series->sysNumeroSerie}}">{{$series->sysNumeroSerie}}</option>
                                                                @endforeach
                                                            </select>
                                                            <span class="inline-flex items-center px-3 border border-gray-300 bg-gray-50 text-gray-500 text-sm">/</span>                                            
                                                            <input type="text" class="focus:ring-indigo-500 focus:border-indigo-500 flex-2 block w-full rounded-none rounded-r-md sm:text-sm border-gray-300" id="codigoPedido" name="codigoPedido" size="1" readonly>
                                                            
                                                        </div>
                                                        <div class="grid grid-cols-2">
                                                            <div class="col-6">                                                   
                                                                <label for="fecha pedido" class="block text-sm font-medium text-gray-700"> Fecha P. </label>                                                                
                                                                <div class="mt-1 flex rounded-md shadow-sm">
                                                                    <input type="date" class="focus:ring-indigo-500 focus:border-indigo-500 flex-2 rounded block sm:text-sm" id="fechaPedido" name="fechaPedido" style="width:100px;" value="<?php echo date('Y-m-d');?>" onchange="modificarFecha();" readonly>                                                        
                                                                </div>
                                                            </div>
                                                            <div class="col-6"> 
                                                                <label for="pronto pago" class="block text-sm font-medium text-gray-700"> % P. Pago </label>                                                                
                                                                <div class="mt-1 flex rounded-md shadow-sm">
                                                                    <input id="descuentoProntoPago" name="descuentoProntoPago" class="focus:ring-indigo-500 focus:border-indigo-500 flex-2 rounded-none rounded-l-md block sm:text-sm " style="width :65px;" value="0" onblur="guardarProntoPago()">
                                                                    <button class="inline-flex items-center px-3 border rounded-none rounded-r-md  bg-gray-50 text-gray-500 text-sm"  id="botondescuentoProntoPago" style="width:35px;" onclick="guardarProntoPago()"><span><i class="fas fa-calculator"></i></span></button>
                                                                    <input hidden type="number" id='lineasPedido' value="0">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    
                                                </div>                                                                                                                                                                                
                                            </div>
                                            <div class="col-span-12 sm:col-span-7">
                                                <label for="su pedido" class="block text-sm font-medium text-gray-700"> Su Pedido </label>                                                                                                            
                                                <input type="text" id="suPedido" name="suPedido" class="focus:ring-indigo-500 focus:border-indigo-500 flex-2 rounded block sm:text-sm w-full" onblur="guardarSuPedido(this.value)">                                            
                                            </div>
                                            <div class="col-span-12 sm:col-span-7 mb-3">
                                                <label for="su pedido" class="block text-sm font-medium text-gray-700"> 0bservaciones </label>                                                                                                            
                                                <textarea type="text" id="comentario" name="comentario" class="focus:ring-indigo-500 focus:border-indigo-500 flex-2 rounded block sm:text-sm w-full" rows="5" onblur="guardarComentario(this.value)"></textarea>                                                                                    
                                            </div>
                                            

                                            <div class="grid grid-cols-1 gap-2 mt-1">
                                                <div class="col-12 mb-1" id="cuadroRecargo" style="display: none;">
                                                    <div class="flex justify-between items-center estiloDivRecargoTPV ">
                                                        
                                                            Recargo Eq.
                                                            <b class="estiloDivTotalRecargoTPV" id="totalRecargo">0.00</b>
                                                        
                                                    </div>                                        
                                                </div> 
                                                <div class="grid grid-cols-2">
                                                    <div class="col-6 mb-1 ">
                                                        <div class="flex justify-between items-center estiloDivBaseTPV w-full">
                                                            
                                                                Base Imp.
                                                                <b class="estiloDivTotalBaseTPV" id="baseImponible">0.00</b>
                                                            
                                                        </div>                                        
                                                    </div>
                                                    <div class="col-6 mb-1">
                                                        <div class="flex justify-between items-center estiloDivIvaTPV w-full">                                            
                                                                Total IVA                                                                                        
                                                                <b  class="estiloDivTotalIvaTPV" id="totaliva">0.00</b>                                            
                                                        </div>                                        
                                                    </div>
                                                </div>                                   
                                                <div class="col-12">
                                                    <div class="flex justify-between items-center estiloDivTotalTPV">
                                                        <div class="col-md-4">
                                                            TOTAL
                                                        </div>
                                                        <div class="col-md-8 estiloDivTotalPrecioTPV">
                                                            <b id="precioTotal">0.00</b>
                                                        </div>
                                                    </div>                                        
                                                </div>
                                            </div>                                                                                
                                            
                                            
                                            <div x-data="{ show: false }">
                                                <div class="flex justify-between mt-2">
                                                    <button @click={show=true} class="block w-full py-4 bg-blue-500 hover:bg-blue-700 text-center text-white font-bold font-heading uppercase rounded-md transition duration-200 mr-1" onclick="comprobaciónPedido()">
                                                        FINALIZAR PEDIDO
                                                    </button>
                                                    <button type="button" id="eliminarPedido" name="eliminarPedido" class="block w-full py-4 bg-red-500 hover:bg-red-700 text-center text-white font-bold font-heading uppercase rounded-md transition duration-200 ml-1"><i class="fas fa-trash-alt"></i>&nbsp; ELIMINAR PEDIDO</button>                                                        
                                                </div>
                                                <div x-show="show" tabindex="0" class="z-10 overflow-auto left-0 top-0 bottom-0 right-0 h-full fixed">
                                                    <div  @click.away="show = false" class="z-20 relative p-3 mx-auto my-0 max-w-full" style="width: 1000px;">
                                                        <div class="bg-white rounded shadow-lg border flex flex-col overflow-hidden">
                                                            <button @click={show=false} class="fill-current h-6 w-6 absolute right-0 top-0 m-6 font-3xl font-bold">&times;</button>                    
                                                            <div class="px-6 py-3 text-xl border-b font-bold">
                                                                Finalizar Pedido                                                                
                                                                <div class="p-6 flex-grow">
                                                                    <div class="grid grid-cols-2 gap-8 p-4">

                                                                        <div class="col-12 sm:col-7">

                                                                            <label for="correo" class="block text-sm font-medium text-gray-700"> Correo </label>      
                                                                            <input type="text" id="correoAEnviar" name="correoAEnviar" class="focus:ring-indigo-500 focus:border-indigo-500 flex-1 block rounded text-xl sm:text-sm w-full border" autofocus required>                                                                                                                                                                                                                  
                                                                        </div>
                                                                        
                                                                        <div class="col-12 sm:col-7 justify-content-center">
                                                                            <button type="button" class=" p-3 bg-green-300 hover:bg-green-400 text-center text-white font-bold rounded-md transition duration-200 mr-2" name="enviarMail" id="enviarMail">Enviar</button> 
                                                                            <a  class=" p-2 bg-blue-300 hover:bg-blue-400 text-center text-white font-bold rounded-md transition duration-200 mr-2" id="imprimir2" STYLE="cursor: pointer"><i class="fa fa-print"></i>Imprimir</a>
                                                                        </div> 
                                                                    </div>                                                                
                                                                </div>                  
                                                            </div>
                                                            <div class="px-6 py-3 border-t">
                                                                <div class="flex justify-end">
                                                                    <button @click={show=false} type="button" class=" w-25 p-4 bg-blue-500 hover:bg-blue-700 text-center text-white font-bold font-heading uppercase rounded-md transition duration-200 mr-2" name="terminar" id="terminar">Finalizar</button>
                                                                    <button @click={show=false} type="button" class=" w-25 p-4 bg-red-500 hover:bg-red-700 text-center text-white font-bold font-heading uppercase rounded-md transition duration-200">Cerrar</Button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>                                                
                                                </div>
                                            </div>                                                                                        
                                                                                
                                        </div>

                                    </div>
                                </div>

                            </div>
                                                                                                    
                            <div x-data="{ show: false }">
                                <div class="flex justify-between mt-2">
                                    <span hidden @click={show=true} id="botonUltimosArticulos" class="input-group-addon">articulos anteriores</i></span>                                                                 
                                </div>
                                <div x-show="show" tabindex="0" class="z-10 overflow-auto left-0 top-0 bottom-0 right-0 h-full fixed">
                                    <div  @click.away="show = false" class="z-20 relative p-3 mx-auto my-0 max-w-full" style="width: 1000px;">
                                        <div class="bg-white rounded shadow-lg border flex flex-col overflow-hidden">
                                            <button @click={show=false} class="fill-current h-6 w-6 absolute right-0 top-0 m-6 font-3xl font-bold">&times;</button>                    
                                            <div class="px-6 py-3 text-xl border-b font-bold">
                                                Compras Anteriores del Artículo                                                               
                                                <div class="p-6 flex-grow">
                                                    <table class="w-full">
                                                        <thead>
                                                        <tr class="text-md font-semibold tracking-wide text-left text-gray-900 bg-gray-100 uppercase border-b border-gray-600">
                                                            <th class="px-4 py-3">Codigo Artículo</th>
                                                            <th class="px-4 py-3">Descipcion</th>
                                                            <th class="px-4 py-3">Fecha</th>
                                                            <th class="px-4 py-3">U.Pedidas</th>
                                                            <th class="px-4 py-3">Precio</th>
                                                            <th class="px-4 py-3">Descuento</th>
                                                            <th class="px-4 py-3">Importe</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody class="bg-white" id="ArticulosPedidosAnteriormente">
                                                        
                                                        </tbody>
                                                    </table>
                                                </div>                  
                                            </div>
                                            <div class="px-6 py-3 border-t">
                                                <div class="flex justify-end">                                                                                
                                                    <button @click={show=false} type="button" class=" w-25 p-4 bg-red-500 hover:bg-red-700 text-center text-white font-bold font-heading uppercase rounded-md transition duration-200">Cerrar</Button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>                                                
                                </div>
                            </div>
                            
                            

                        </section>

                    </div>
                </div>
            </div>
        </div>
    </div>    
</body>

<script type="text/javascript">

    const setup = () => {                

        const updateBarChart = (on) => {
          const data = {
            label:menosUno,
            data: grafica2,
            backgroundColor: 'rgb(34, 220, 19 )',
          }
          const grafic = {
            label: menosDos,
            data: grafica3,
            backgroundColor: 'rgb(178, 13, 13)',
          }
          if (on) {
            barChart.data.datasets.unshift(data)
            barChart.data.datasets.unshift(grafic)
            barChart.update()
          } else {
            barChart.data.datasets.shift()
            barChart.data.datasets.shift()
            barChart.update()
          }
        }
        

        return {
          loading: true,                                    
          updateBarChart,      
        }
    }

    $('#myTab a').on('click', function(e) {
        e.preventDefault()
        $(this).tab('show')
    })
    var descuento = '<?php echo session('descuento'); ?>';
    var iva = '<?php echo session('iva'); ?>';
    var total = '<?php echo $total; ?>';
    var observaciones = '<?php echo $observaciones; ?>';
    var puntoVenta = '<?php echo session('puntoVenta'); ?>';
    var tipoPedido = '<?php echo session('tipoPedido'); ?>';


    $('#terminar').click(function(){       

        let ejercicio = $('#tpvCodigoTicketEjercicio').val();
        let serie = $('#seriePedido').val() ;
        let numero = $('#codigoPedido').val();
        let cliente = $("#codigoCliente").val();

        var datos = {        
            "cliente": cliente,
            "_token": $("meta[name='csrf-token']").attr("content")
        };
        console.log(datos);

        $.ajax({
            data: datos,
            url: '/direcciones',
            type: 'post',
            timeout: 2000,
            async: true,
            success: function(response) {
                console.log(response);

                if (response['envio'].length > 0) {
                    var envios = '<select class="border" style="width: 275px;" id="envioPedido">';
                    for (i = 0; i < response['envio'].length; i++) {
                        if(response['envio'][i]['Nombre'] == ""){
                            envios += '<option value="' + response['envio'][i]['NumeroDomicilio'] + '">' +response['envio'][i]['Direccion'] + '</option>';
                        }else{    
                            envios += '<option value="' + response['envio'][i]['NumeroDomicilio'] + '">' +response['envio'][i]['Nombre']+'-'+ response['envio'][i]['Direccion'] + '</option>';
                        }
                    }
                    envios += '</select>';

                    Swal.fire({
                        title: 'Selecciona direción de envio',
                        input: 'checkbox',
                        //inputValue: 1,
                        inputPlaceholder: 'Acepta cambiar dirección de envio',
                        //inputPlaceholder: 'Direcciones',
                        confirmButtonText: 'Terminar',
                        confirmButtonColor: '#3085d6',
                        html: envios,
                        //showCancelButton: true,
                        allowOutsideClick: false,
                        inputValidator: (value) => {
                            console.log(value);
                            //let selected = $('#envioPedido').val();
                            if (!value) {
                                return 'Acepta el cambio de dirección';
                            } else {
                                                                
                                var parametrosE = {
                                    "ejercicio": ejercicio,
                                    "serie": serie,
                                    "numero": numero,                                            
                                    "direccion": $('#envioPedido').val(),
                                    "_token": $("meta[name='csrf-token']").attr("content")
                                };
                                console.log(parametrosE);

                                $.ajax({
                                    data: parametrosE,
                                    url: '/direccionPedido',
                                    type: 'post',
                                    timeout: 2000,
                                    async: true,
                                    success: function(response) {
                                        console.log(response);


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
                                            //$("#refrescarTablaPedido").trigger('click');                          

                                            var estado = {
                                                "ejercicio": ejercicio,
                                                "serie": serie,
                                                "numero": numero,  
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
                                        Swal.fire({
                                            title: 'Pedido Finalizado',                                            
                                            icon: 'success',
                                            confirmButtonText: 'Cerrar'
                                        }).then((result) => {
                                            if (result.isConfirmed) {
                                                window.location.reload();
                                            }
                                        })                                        
                                    }
                                })  
                                        }                    
                                    }
                                })                                        
                            }
                                });
                            }
                        }
                    })
                }
            }
        });
        //

    });



    $('#cancelarVerArticulos').click(function(){

        $("#ultimosPedidos").hide();
        $(".articuloYaComprado").remove();        

    });

    

    $('#imprimir2').click(function(){
        
        $("#printInvoice").trigger('click');

    });

    $('#enviarMail').click(function(){
        
        let ejercicio = $('#tpvCodigoTicketEjercicio').val();
        let serie = $('#seriePedido').val() ;
        let numero = $('#codigoPedido').val();
        let correo = $('#correoAEnviar').val();

        var parametros = {
            "ejercicio": ejercicio,
            "serie": serie,
            "numero": numero,
            "correos": correo,
            "_token": $("meta[name='csrf-token']").attr("content")
        };
        console.log(parametros)

        $.ajax({
            data: parametros,
            url: './correoPedido',
            type: 'post',
            timeout: 7000,
            async: true,
            success: function(response) {
                
                Swal.fire({
                    title: 'Enviado!',
                    text: 'Correo/s enviado',
                    icon: 'success',
                    confirmButtonText: 'Cerrar'
                })
                
            },
            error: function(XMLHttpRequest, textStatus, errorThrown){
                
                console.log(XMLHttpRequest, textStatus, errorThrown)
                Swal.fire({
                        title: 'Error Al Enviar pedido por correo!',
                        text: 'Recarga la Página',
                        icon: 'error',
                        confirmButtonText: 'Cerrar'
                    })
            }
        });    

        

    });

    $('#printInvoice').click(async function() {



        $('#imprimir').css('display', 'block');
        $('#invoice').css('display', 'none');

        var ejercicio = $('#tpvCodigoTicketEjercicio').val();        
        var serie = $('#seriePedido').val();        
        var numero = $('#codigoPedido').val();        
        
        var parametros = {
            "ejercicio":ejercicio,
            "serie": serie,
            "numero": numero,
            "_token": $("meta[name='csrf-token']").attr("content")
        } 
        $.ajax({
            data: parametros,
            url: './tabla',
            type: 'post',
            timeout: 2000,
            async: true,
            success: function(response) {
                //console.log(response);
                var html ='<table class="w-full text-sm text-left text-gray-500 dark:text-gray-400" id="tabla-articulos" border="0" cellspacing="0" cellpadding="0">'+
                                            '<thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">'+
                                                '<tr>'+
                                                   '<th scope="col" class="px-6 py-3" id="columnaCabeceraCodigo">'+
                                                        '<div id="cabeceraDatos">'+
                                                        ejercicio+'/'+serie+'/'+numero+
                                                        '</div>'+                                                        
                                                    '</th>'+
                                                    '<th scope="col" class="px-6 py-3 text-center">Artículo</th>'+
                                                    '<th hidden scope="col" class="px-6 py-3">Partida</th>'+
                                                    '<th scope="col" class="px-6 py-3 text-right">P. Unidad</th>'+
                                                    '<th scope="col" class="px-6 py-3 text-right">Descuento</th>'+
                                                    //'<th scope="col" class="px-6 py-3 text-right">P.F unidad</th>'+
                                                    '<th scope="col" class="px-6 py-3 text-right">Cantidad </th>'+
                                                    '<th scope="col" class="px-6 py-3" id="udsDevolucion" style="display: none">Uds. devolución</th>'+
                                                    '<th scope="col" class="px-6 py-3 text-right">Subtotal</th>'+
                                                    '<th scope="col" class="px-6 py-3" id="totalDevolucion" style="display: none">Total devolución</th>'+
                                                '</tr>'+
                                            '</thead>'+
                                            '<tbody class="listadoArticuloPedido">';
                                            for(let i = 0; i < response.lineas.length; i++){
                                                html += "<tr class'border-b dark:bg-gray-800 dark:border-gray-700 odd:bg-white even:bg-gray-50 odd:dark:bg-gray-800 even:dark:bg-gray-700'>"+
                                                    "<td>"+response.lineas[i].CodigoArticulo+"</td>" +
                                                    "<td style='font-size:10px;'>"+response.lineas[i].DescripcionArticulo +"</td>" +
                                                    "<td class='px-6 py-4 text-right' hidden>"+response.lineas[i].Partida+"</td>"+
                                                    "<td class='px-6 py-4 text-right'>"+parseFloat(response.lineas[i].Precio).toFixed(2)+"</td>"+
                                                    "<td class='px-6 py-4 text-right'>"+parseFloat(response.lineas[i].descuento).toFixed(2)+"%</td>"+
                                                    "<td class='px-6 py-4 text-right'>"+parseFloat(response.lineas[i].UnidadesPedidas).toFixed(2)+"</td>"+
                                                    "<td class='px-6 py-4 text-right'>"+parseFloat(response.lineas[i].Precio * response.lineas[i].UnidadesPedidas).toFixed(2)+"</td>"+
                                                "</tr>";
                                            }
                                     html +='</tbody>'+                                            
                                        '</table>';
                //console.log(html);
                $('#tablaImprimir').empty();
                $('#tablaImprimir').append(html);
                
                var caja =  "<div class='col-8'></div>"+
                            "<div class='col-4 mb-1 mr-1'>"+
                                "<div class='row' >"+
                                    "<div class='col-6'>"+
                                        "Base Imponible"+
                                    "</div>"+
                                    "<div class='col-6 text-right'>"+
                                        parseFloat(response.cabecera[0].BaseImponible).toFixed(2)+
                                    "</div>"+
                                "</div>"+                      
                            "</div>"+ 
                            "<div class='col-8'></div>"+                           
                            "<div class='col-4 mb-1 mr-1'>"+
                                "<div class='row'>"+
                                    "<div class='col-6'>"+
                                        "Total IVA"+
                                    "</div>"+
                                    "<div class='col-6 text-right'>"+
                                        parseFloat(response.cabecera[0].TotalIva).toFixed(2)+
                                    "</div>"+
                                "</div>"+                      
                            "</div>"+

                            "<div class='col-8'></div>"+                           
                            "<div class='col-4 mr-1'>"+ 
                                "<div class='row' style='border: 2px solid #3c8dbc; font-size: 20px;'>"+
                                    "<div class='col-6'>"+                                
                                       "Total"+
                                    "</div>"+
                                    "<div class='col-6 text-right'>"+                                
                                        parseFloat(response.cabecera[0].ImporteLiquido).toFixed(2)+"€"+
                                    "</div>"+
                                "</div>"+
                            "</div>";


                $('#totalImprimir').empty();
                $('#totalImprimir').append(caja);
                
            }
        })
        
        await sleep(500);
        
        

        Popup($('#imprimir')[0].outerHTML);

        async function Popup(data) {

            var ua = navigator.userAgent.toLowerCase(); 
            if (ua.indexOf('safari') != -1) { 
                if (ua.indexOf('chrome') > -1) { 
                    window.print();
                    $('#imprimir').css('display', 'none');
                    $('#invoice').css('display', 'block');
                } else { 
                    window.print();
                    window.onafterprint = function(){
                        console.log('garfield come lasaña')
                        $('#imprimir').css('display', 'none');
                        $('#invoice').css('display', 'block');
                    } 
                } 
            }

                
        } 
        
    });

    function contador(serie){
        var parametros = {
            "serie": serie,            
            "_token": $("meta[name='csrf-token']").attr("content")
        };
        //insercción pedido
        $.ajax({
            data: parametros,
            url: './contador',
            type: 'post',
            timeout: 2000,
            async: true,
            success: function(response) {
                //console.log(response);
                var fecha = new Date()
                $('#codigoPedido').val(response);
                var relleno = fecha.getFullYear()+'/'+serie+'/'+response;
                //console.log(relleno);
                $('#cabeceraDatos').text(relleno);
                $('#sinSerie').css('display', 'none');
                $('#conSerie').css('display', 'block');
                                        
                document.getElementById('emailCliente').href += ' '+relleno+' &body=';                

            }
        })
    }

    function guardarComentario(comentario){
        var parametros = {
            "serie":$('#seriePedido').val(),
            "numero":$('#codigoPedido').val(),
            "comentario":comentario,
            "_token": $("meta[name='csrf-token']").attr("content")
        }

        console.log(parametros);

        $.ajax({
            data: parametros,
            url: './observacionPedido',
            type: 'post',
            timeout: 2000,
            async: true,
            success: function(response) {
                console.log(response);                     
            }
        })

    }

    function guardarSuPedido(pedido){
        var parametros = {
            "serie":$('#seriePedido').val(),
            "numero":$('#codigoPedido').val(),
            "pedido":pedido,
            "_token": $("meta[name='csrf-token']").attr("content")
        }

        console.log(parametros);

        $.ajax({
            data: parametros,
            url: './guardarSuPedido',
            type: 'post',
            timeout: 2000,
            async: true,
            success: function(response) {
                console.log(response);                     
            }
        })

    }

    function guardarProntoPago(){
        var parametros = {
            "serie":$('#seriePedido').val(),
            "numero":$('#codigoPedido').val(),
            "prontoPago":$('#descuentoProntoPago').val(),
            "_token": $("meta[name='csrf-token']").attr("content")
        }

        console.log(parametros);

        $.ajax({
            data: parametros,
            url: './guardarProntoPago',
            type: 'post',
            timeout: 2000,
            async: true,
            success: function(response) {
                console.log(response);             
                
                var nLineas = $(".subTotal").length;
                console.log(nLineas);

                
                $('#lineasPedido').val(nLineas);
                
                recalcularLineasPedido(response);

            }
        })
    
        //calcularTotalPedido(); 

    }
    
    function datosArticulo(id){            
        var datosp = {
            "id": id,            
            "_token": $("meta[name='csrf-token']").attr("content")
        };
        $.ajax({
            data: datosp,
            url: './datosArticulo',
            type: 'post',
            timeout: 1000,
            async: true,
            success:  function(response){                
                rellenarListadoArticuloPedido(response);
                guardarUltimoProceso2();
                $("#cerrarArticulosBuscador").trigger('click');
            }        
        });
    }

    function showObservacionArticulo(div){                
        div = div.slice(2)
        //console.log(div);
        $('#'+div).css('display', 'block');
    }

    function observacionArticulo(value, id){
        console.log(value);
        datos = id.split('-');
        parametros = {
            "serie":$('#seriePedido').val(),
            "numero":$('#codigoPedido').val(),
            "articulo":datos[1],
            "orden":datos[2],
            "observacion":value,
            "_token": $("meta[name='csrf-token']").attr("content")
        }
        $.ajax({
            data: parametros,
            url: './observacionArticulo',
            type: 'post',
            timeout: 1000,
            async: true,
            success:  function(response){                                
            }        
        });
    }

    function comprobacionPedido(){
        let ejercicio = $('#tpvCodigoTicketEjercicio').val();
        let serie = $('#seriePedido').val() ;
        let numero = $('#codigoPedido').val();
        let cliente = $("#codigoCliente").val();

        var datos = {        
            "cliente": cliente,
            "ejercicio": ejercicio,
            "_token": $("meta[name='csrf-token']").attr("content")
        };
        console.log(datos);
    }


</script>

<script src="{{asset('js/pedido2.js')}}"></script>