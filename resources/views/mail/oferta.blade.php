<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>'.htmlspecialchars('Oferta Web').'</title>
    <script src="https://kit.fontawesome.com/2a20cc777c.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
    <!-- <link rel="stylesheet" href="{{asset('css/bootstrap.min.css')}}">
    <link rel="stylesheet" href="{{asset('css/pedidos/bootstrap/dist/css/bootstrap.min.css')}}">
    <link rel="stylesheet" href="{{asset('css/pedidos/propios.css')}}"> -->
    <script src="https://kit.fontawesome.com/2a20cc777c.js" crossorigin="anonymous"></script>
    <script src="{{asset('js/jquery-3.6.0.js')}}"></script>
    <script src="{{asset('js/bootstrap.min.js')}}"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/core-js/2.4.1/core.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/0.4.1/html2canvas.js"></script>

    <style type="text/css">
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
    </style>
</head>

<body>
    
    <?php

use App\Http\Controllers\OfertaController;

$contador = 0;
        $ejercicio = '';
        $serie = '';
        $numero = '';
    ?>
    @foreach($pedidoCorreo as $datos)
        @if($contador == 0)
        <?php
            $serie = $datos;
            $contador += 1;
        ?>
        @elseif($contador == 1)
        <?php
            $ejercicio = $datos;
            $contador += 1;
        ?>
        @elseif($contador == 2)
        <?php
            $numero = $datos;
            $contador += 1;
        ?>
        @endif
    @endforeach
        
    
    <?php 

        $cabecera = OfertaController::cabeceraOferta($ejercicio,$serie,$numero);
        $lineas = OfertaController::lineasOferta($ejercicio,$serie,$numero);

    ?>

    <div id="imprimir">
        <div id="cabeceraImprimir">
            <div class="invoice">
                <div style="min-width: 600px">

                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-6">
                                
                                <img src="{{asset('media/images/surembalaje.png')}}" style="width:40%;" data-holder-rendered="true" />
                                
                            </div>
                            <div class="col-6 company-details">
                            
                            </div>
                        </div>
                    </div>

                    <div class="form-group col-md-12">
                        <hr style="width:100%;margin-bottom: 1%; color:blue; border:4px;">
                    </div>

                    <div class="content-wrapper">
                        <section class="content">
                            
                                <div class="box-body">

                                    <div class="col-md-9 ">

                                        <div class="row">
                                            <div class="row contacts">
                                                <div class="col invoice-to" id="datosCliente">
                                                    <div class="text-gray-light">Cliente:</div>
                                                    <h3 class="to" id="razonSocialCliente2">{{$cabecera->RazonSocial}}</h3>
                                                    <div class="address" id="direccionCliente2">{{$cabecera->Domicilio}}, {{$cabecera->Municipio}} ({{$cabecera->CodigoPostal}})</div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group col-md-12">
                                            <hr style="width:100%;margin-bottom: 1%; border:4px;">
                                        </div>

                                        <div id="tablaImprimir">

                                            <table class="table table-bordered" border="0" cellspacing="0" cellpadding="0">
                                                <thead>
                                                    <th class="bg-primary text-light">
                                                        <div id="cabeceraDatoss">
                                                            {{$ejercicio}}/{{$serie}}/{{$numero}}
                                                        </div>
                                                    </th>
                                                    <th>Artículo</th>
                                                    
                                                    <th>Precio Und.</th>
                                                    <th>Dto.</th>

                                                    <th>Cantidad </th>
                                                    <th>Subtotal</th>
                                                </thead>
                                                <tbody class="listadoArticuloPedidos">

                                                    @foreach($lineas as $linea)
                                                    <tr>
                                                        <td>{{$linea->CodigoArticulo}}</td>
                                                        <td>{{$linea->DescripcionArticulo}}</td>
                                                        
                                                        <td class='text-right'>{{number_format((float)$linea->Precio, 2, ',', '')}}</td>
                                                        <td class='text-right'>{{number_format((float)$linea->descuento, 2, '.', '')}}%</td>
                                                        <td class='text-right'>{{number_format((float)$linea->UnidadesPedidas, 2, '.', '')}}</td>
                                                        <td class='text-right'>{{number_format((float)$linea->ImporteNeto, 2, ',', '')}}</td>
                                                    </tr>
                                                    @endforeach
                                                                                                        
                                                    <tr>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        
                                                        <td class='text-right'>
                                                            <div><strong>Iva</strong></div>
                                                            <div><strong>Base Imponible</strong></div>
                                                        </td>
                                                        <td class='text-right'>                                                            
                                                            <div><strong>{{number_format((float)$cabecera->TotalIva, 2, ',', '')}}</strong></div>
                                                            <div><strong>{{number_format((float)$cabecera->BaseImponible, 2, ',', '')}}</strong></div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        
                                                        <td class='text-right'><strong>Total</strong></td>
                                                        <td class='text-right'><strong>{{number_format((float)$cabecera->ImporteLiquido, 2, ',', '')}}€</strong></td>
                                                    </tr>

                                                </tbody>
                                            </table>                                                                                                                                    
                                            

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

</html>