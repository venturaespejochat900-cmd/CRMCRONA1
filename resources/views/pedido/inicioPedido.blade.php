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
    <!-- Bootstrap -->
    <!--<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">-->
    <link rel="stylesheet" href="{{asset('css/bootstrap.min.css')}}">
    <link rel="stylesheet" href="{{asset('css/pedidos/bootstrap/dist/css/bootstrap.min.css')}}">


    <!-- Estilos propios generales -->
    <link rel="stylesheet" href="{{asset('css/pedidos/propios.css')}}">

    <!-- Font Awesome -->
    <!-- <link rel="stylesheet" href="bower_components/font-awesome/css/font-awesome.min.css"> -->
    <script src="https://kit.fontawesome.com/2a20cc777c.js" crossorigin="anonymous"></script>



    <!-- Google Font -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">

    <!-- DataTables -->
    <!--<link rel="stylesheet" href="bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css">-->
    <!--<link rel="stylesheet" href="bower_components/datatables.net-bs/css/responsive.bootstrap.min.css">-->

    <!--=====================================
    PLUGINS DE JAVASCRIPT
    ======================================-->

    <!-- jQuery 3 
    <script src="bower_components/jquery/dist/jquery.min.js"></script>-->
    <script src="{{asset('js/jquery-3.6.0.js')}}"></script>

    <!-- Bootstrap 3.3.7 -->
    <script src="{{asset('js/bootstrap.min.js')}}"></script>

    <!-- FastClick 
    <script src="bower_components/fastclick/lib/fastclick.js"></script>-->

    <!-- AdminLTE App -->
    <!--<script src="{{asset('js/pedidos/adminlte.min.js')}}"></script>-->

    <!-- DataTables 
    <script src="bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
    <script src="bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
    <script src="bower_components/datatables.net-bs/js/dataTables.responsive.min.js"></script>
    <script src="bower_components/datatables.net-bs/js/responsive.bootstrap.min.js"></script> -->

    <!-- SweetAlert 2 
    <script src="plugins/sweetalert2/sweetalert2.all.js"></script> -->
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- By default SweetAlert2 doesn't support IE. To enable IE 11 support, include Promise polyfill:-->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/core-js/2.4.1/core.js"></script>

    <!--<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>-->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/0.4.1/html2canvas.js"></script>    
</head>
<style>
    .pop-up {
        margin-left: 1%;
        max-height: 350px;
        background-color: rgba(48, 48, 48, 0.1);
        overflow-y: scroll;
        cursor: pointer;
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

    <input type="hidden" id="codigoCliente" name="codigoCliente">
    <div id="imprimir" style="display: none;">
        <div id="cabeceraImprimir">
            <div class="invoice">
                <div style="min-width: 600px">
                    
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-6">
                                <a target="_blank" href="{{session('codigoComisionista')}}">
                                    <img src="{{asset('media/images/surembalaje.png')}}" style="width:17%;" data-holder-rendered="true" />
                                </a>
                            </div>
                            <div class="col-6 company-details">
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
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-6">
                                <a target="_blank" href="">
                                    <img src="{{asset('media/images/surembalaje.png')}}" style="width:17%;" data-holder-rendered="true" />
                                </a>
                            </div>
                            <div class="col-6 company-details">
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

                <div class="content-wrapper">
                    <section class="content">
                        <!-- TPV NO TACTIL -->
                        <div class="box" id="tpvNoTactil">
                            <div class="box-body">

                                <!-- Primera columna general -->
                                <div class="col-md-9 border-right">

                                    <div class="row">
                                        <div class="row contacts">
                                            <div class="col invoice-to" id="datosCliente">
                                                <div class="text-gray-light">Cliente:</div>
                                                <h2 class="to" id="razonSocialCliente"> </h2>
                                                <div class="address" id="direccionCliente"></div>
                                                <div class="email"><a href="mailto:" id="emailCliente"></a></div>
                                            </div>
                                            <div class="col invoice-details">
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
                                            <div class="col-md-3 col-lg-3 col-sm-3"></div>
                                            <div class="col-md-3 col-lg-3 col-sm-3 ">
                                                <div class="d-flex flex-row-reverse bd-highlight ">
                                                    <button id="printInvoice" class="btn"><i class="fa fa-print" style="size:30%;"></i></button>                                                    
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-6"></div>                                        
                                        <div class="col-md-6 col-lg-6 col-sm-6" style="display: none" id="conSerie">
                                            <div class="row">
                                                <div class="col-3">
                                                    <div class="input-group">                                                                                           
                                                        <input id="busquedaProductoPedido" name="busquedaProductoPedido" type="text" class="form-control" placeholder="Codigo Articulo" autofocus autocomplete="off">                                                                                                                                                                   
                                                    </div>
                                                </div>
                                                <div class="col-9">
                                                    <div class="input-group">
                                                        <input id="busquedaProductoPedidoDescripicion" name="busquedaProductoPedidoDescripicion" type="text" class="form-control" placeholder="Buscar producto" autofocus autocomplete="off">
                                                        <span id="tpvAnyadirArticuloCodigoBtn" class="input-group-addon" onclick="buscarTodosLosArticulos()"><i class="fa fa-search"></i></span>        
                                                    </div>
                                                </div>
                                            </div>
                                        </div>                                                        
                                        <div class="pop-up rounded-bottom"  id="modalProductoPedido" style="display: none"><i class="far fa-times-circle cerrarSalidaProductoPedido" id="close"></i>
                                            <div class="pop-up-wrap">
                                                <div class="salidaProductoPedido">
                                                </div>
                                            </div>
                                        </div>
                                    </div>                                    

                                    <div class="form-group col-md-12">
                                        <hr style="width:100%;margin-bottom: 1%;">
                                    </div>



                                    <div class="col-md-12 mt-2">
                                        <table class="table table-responsive-lg table-bordered" id="tabla-articulos" border="0" cellspacing="0" cellpadding="0">
                                            <thead>
                                                <th id="columnaCabeceraCodigo" class="bg-primary text-light">
                                                    <div id="cabeceraDatos"> </div>
                                                    <?php    
                                                    $total = 0;
                                                    $observaciones = "";
                                                    $serieBorrador = "";
                                                    $numeroPedido = "";
                                                    $numeroPedidoBorrador = "";
                                                    ?>
                                                </th>
                                                <th>Artículo</th>
                                                <th hidden>Partida</th>
                                                <th>P. Unidad</th>
                                                <th>Descuento</th>
                                                <th>P.F unidad</th>
                                                <th>Cantidad </th>
                                                <th id="udsDevolucion" style="display: none">Uds. devolución</th>
                                                <th>Subtotal</th>
                                                <th id="totalDevolucion" style="display: none">Total devolución</th>
                                            </thead>
                                            <tbody class="listadoArticuloPedido">
                                            </tbody>                                            
                                        </table>
                                    </div>
                                </div>

                                <!-- Segunda columna general -->
                                <div class="col-md-3">
                                    <!-- Código Ticket -->
                                    <div class="form-group col-md-12">
                                        <div id="sinSerie"><h3 style="color: red">Elige una serie</h3></div>
                                        <h5>Pedido</h5>
                                        <div class="input-group">
                                            <input type="text" class="form-control" id="tpvCodigoTicketEjercicio" name="tpvCodigoTicketEjercicio" size="1" value="<?php echo date("Y"); ?>" readonly>
                                            <span class="input-group-addon">/</span>
                                            <!-- <input type="text" class="form-control" id="tpvCodigoTicketSerie" name="tpvCodigoTicketSerie" size="1"> -->
                                            <select class="form-control" id="seriePedido" name="seriePedido" onchange="contador(this.value)" >
                                                <option value="">Selecciona Serie...</option>
                                                <?php 
                                                    $serie = PedidoController::seriePedido();
                                                ?>                                                    
                                                @foreach($serie as $series)
                                                    <option value="{{$series->sysNumeroSerie}}">{{$series->sysNumeroSerie}}</option>
                                                @endforeach
                                            </select>
                                            <span class="input-group-addon">/</span>                                            
                                            <input type="text" class="form-control" id="codigoPedido" name="codigoPedido" size="1" readonly>
                                        </div>
                                    </div>
                                    <!-- Fecha del pedido -->
                                    <div class="form-group col-md-12" id="tpvAnyadirFechaPedidoDiv">

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="input-group">
                                                    <h5>Fecha pedido</h5>
                                                    <input type="date" class="form-control" id="fechaPedido" name="fechaPedido" style="width:100px;" value="<?php echo date('Y-m-d');?>" onchange="modificarFecha();" readonly>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="input-group" >
                                                    <h5>% P. Pago</h5>
                                                    <input id="descuentoProntoPago" name="descuentoProntoPago" class="form-control" style="width :65px;" value="0" onblur="guardarProntoPago()">
                                                    <button class="form-control"  id="botondescuentoProntoPago" style="width:35px;" onclick="guardarProntoPago()"><span><i class="fas fa-calculator"></i></span></button>
                                                    <input hidden type="number" id='lineasPedido' value="0">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    
                                    <div class="form-group col-md-12 mt-5">
                                        <label>Su Pedido:</label><br>
                                        <input type="text" id="suPedido" name="suPedido" class="form-control" onblur="guardarSuPedido(this.value)">                                        
                                    </div>

                                    <div class="form-group col-md-12 mt-5">
                                        <label>Observaciones Pedido:</label><br>
                                        <textarea type="text" id="comentario" name="comentario" class="form-control" rows="5" onblur="guardarComentario(this.value)"></textarea>                                        
                                    </div>

                                    <div class="form-group col-md-12">
                                        <hr style="width:100%;">                                                                                        
                                    </div>

                                    <!-- Cuadro del Total -->
                                    <div class="col-md-12 mb-1" id="cuadroRecargo" style="display: none;">
                                        <div class="row estiloDivRecargoTPV">
                                            
                                                Recargo Eq.
                                                <b class="estiloDivTotalRecargoTPV" id="totalRecargo">0.00</b>
                                            
                                        </div>                                        
                                    </div>                                    
                                    <div class="col-md-6 mb-1">
                                        <div class="row estiloDivBaseTPV">
                                            
                                                Base Imponible
                                                <b class="estiloDivTotalBaseTPV" id="baseImponible">0.00</b>
                                            
                                        </div>                                        
                                    </div>
                                    <div class="col-md-6 mb-1">
                                        <div class="row estiloDivIvaTPV">                                            
                                                Total IVA                                                                                        
                                                <b  class="estiloDivTotalIvaTPV" id="totaliva">0.00</b>                                            
                                        </div>                                        
                                    </div>
                                    <div class="col-md-12">
                                        <div class="row estiloDivTotalTPV">
                                            <div class="col-md-4">
                                                TOTAL
                                            </div>
                                            <div class="col-md-8 estiloDivTotalPrecioTPV">
                                                <b id="precioTotal">0.00</b>
                                            </div>
                                        </div>                                        
                                    </div>
                                    <!-- <div class="col-md-12">
                                        <div id="filaEntregaCuenta" class="row" style="display: none;">
                                        <div class="col-md-6">
                                            <h5>Entrega cuenta</h5>
                                        </div>
                                        <div class="col-md-6" >
                                            <b id="desgloseEntregaCuentaTPV">0.00</b><b>€</b>
                                        </div>
                                        </div>
                                    </div> -->


                                    <div class="form-group col-md-12 ">
                                        <hr style="width:100%;margin-bottom: 1%;">
                                    </div>



                                    <div class="col-md-12 ajusteTecladoNumericoColTPV alfondo">
                                        <div class='row m-3'>
                                            
                                            
                                                <button type="button" id="finalizarPedido" class="btn btn-lg btn-primary mb-1">FINALIZAR PEDIDO &nbsp; &nbsp;</button>
                                                


                                                <button type="button" id="eliminarPedido" name="eliminarPedido" class="btn btn-lg btn-danger"><i class="fas fa-trash-alt"></i>&nbsp; ELIMINAR PEDIDO</button>
                                              
                                            
                                        </div>
                                    </div>



                                </div>
                            </div>

                        </div>

                        <div class="modal" id="metodoPago">
                            <div class="modal-dialog ">
                                <div class="modal-content">
                                    <div class="modal-header justify-content-around">
                                        <h4 class="modal-title w-100">Finalizar pedido</h4>
                                    </div>
                                    <div class="modal-body">                                                                                                                                                                    
                                        <div class="col-md-12 col-sm-12">
                                            <div class="form-group">
                                                <label>Correo:</label><br>
                                                <input type="text" id="correoAEnviar" name="correoAEnviar" class="form-control" autofocus required>
                                                                                               
                                            </div>
                                        </div>
                                        <div class="col-md-12 col-sm-12 m-3 justify-content-center">
                                            <button type="button" class="btn btn-primary " name="enviarMail" id="enviarMail">Enviar</button> 
                                            <a  class="" id="imprimir2" STYLE="cursor: pointer"><i class="fa fa-print"></i>Imprimir</a>
                                        </div>                                                                           
                                    </div>
                                    <div class="modal-footer float-end">
                                        <button type="button" class="btn btn-primary" name="terminar" id="terminar">Finalizar</button>
                                        <button type="button" class="btn btn-danger" name="cancelar" id="cancelar">Cancelar</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="modal" id="ultimosPedidos">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header justify-content-around">
                                        <h4 class="modal-title w-100">Anteriores Veces Pedido</h4>
                                    </div>
                                    <div class="modal-body">                                                                                                                                                                    
                                        <div class="col-md-12 col-sm-12">
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
                                    <div class="modal-footer float-end">                                        
                                        <button type="button" class="btn btn-danger" name="cancelarVerArticulos" id="cancelarVerArticulos">Cancelar</button>
                                    </div>
                                </div>
                            </div>
                        </div>                        

                    </section>

                </div>
            </div>
        </div>
    </div>
</body>

<script type="text/javascript">

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


    $('#finalizarPedido').click(function(){

        $("#metodoPago").show();
        $('#correoAEnviar').focus();

    });

    $('#terminar').click(function(){

        $("#metodoPago").hide();

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

    $('#cancelar').click(function(){

        $("#metodoPago").hide();        
        
    });

    $('#cancelarVerArticulos').click(function(){

        $("#ultimosPedidos").hide();
        $(".articuloYaComprado").remove();        

    });

    $('#cancelarArticulos').click(function(){
        $("#todosArticulos").hide();
        //$(".articuloYaComprado").remove();        
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
                var html = '<table style="width:100%;" class="table table-bordered" border="0" cellspacing="0" cellpadding="0">'+
                                            '<thead>'+
                                                '<th class="bg-primary text-light">'+
                                                    '<div id="cabeceraDatoss">'+ 
                                                        ejercicio+'/'+serie+'/'+numero+
                                                    '</div>'+                                                    
                                                '</th>'+
                                                '<th>Artículo</th>'+
                                                '<th hidden>Partida</th>'+
                                                '<th>P. Unidad:</th>'+
                                                '<th>Dto.</th>'+
                                                //'<th>Precio final unidad</th>'+
                                                '<th>Cantidad </th>'+                                                
                                                '<th>Subtotal</th>'+                                                
                                            '</thead>'+
                                            '<tbody class="listadoArticuloPedidos">';
                                            //console.log(response.lineas);                                            
                                            for(let i = 0; i < response.lineas.length; i++){
                                                html += "<tr>"+
                                                    "<td>"+response.lineas[i].CodigoArticulo+"</td>" +
                                                    "<td style='font-size:10px;'>"+response.lineas[i].DescripcionArticulo +"</td>" +
                                                    "<td hidden>"+response.lineas[i].Partida+"</td>"+
                                                    "<td class='text-right'>"+parseFloat(response.lineas[i].Precio).toFixed(2)+"</td>"+
                                                    "<td class='text-right'>"+parseFloat(response.lineas[i].descuento).toFixed(2)+"%</td>"+
                                                    "<td class='text-right'>"+parseFloat(response.lineas[i].UnidadesPedidas).toFixed(2)+"</td>"+
                                                    "<td class='text-right'>"+parseFloat(response.lineas[i].Precio * response.lineas[i].UnidadesPedidas).toFixed(2)+"</td>"+
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


    function buscarTodosLosArticulos(){
        $("#todosArticulos").show();
    }


</script>

<script src="{{asset('js/pedido.js')}}"></script>