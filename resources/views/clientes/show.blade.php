<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8"/>
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <!-- <link href="{{ asset('css/app.css') }}" rel="stylesheet"> -->
        <title>CRM CRONADIS</title>
        <link rel="icon" href="{{asset('media/images/cronadis2.png')}}" type="image/gif">
        <link
        href="https://fonts.googleapis.com/css2?family=Cairo:wght@200;300;400;600;700;900&display=swap"
        rel="stylesheet"
        />  
        <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.4/dist/tailwind.css" rel="stylesheet" />
        
        <link rel="stylesheet" href="{{asset('css/tailwind.css')}}" />
        <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script src="https://cdn.jsdelivr.net/gh/alpine-collective/alpine-magic-helpers@0.5.x/dist/component.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/clipboard.js/2.0.8/clipboard.min.js"></script>
        <script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.7.3/dist/alpine.min.js" defer></script>
        <!-- JQUERY -->
        <script src="{{asset('js/jquery-3.6.0.js')}}"></script>
        @livewireStyles
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/1.9.2/tailwind.min.css" integrity="sha512-l7qZAq1JcXdHei6h2z8h8sMe3NbMrmowhOl+QkP3UhifPpCW2MC4M0i26Y8wYpbz1xD9t61MLT9L1N773dzlOA==" crossorigin="anonymous" />
    </head>
    <?php

    ?>
    <body class="flex" style="background: #edf2f7;">
        <div class="fixed inset-y-0 left-0 w-1/6">
            <nav class="flex flex-col bg-blue-900 h-screen px-4 tex-gray-900 border border-purple-900">
                <div class="flex flex-wrap mt-8">
                <div class="w-1/2">
                    <img src="{{asset('media/images/ligna.png')}}" class="mx-auto w-20 h-20 rounded-full">
                </div>
                <div class="w-1/2">
                    <span class="font-semibold text-white">{{$IdCliente}}</span>
                    <button class="bg-green-500 text-white px-4 py-2 rounded-md border border-blue-500 hover:bg-white hover:text-green-500">
                    Pedido
                    </button>
                </div>
                </div>
                <div class="mt-10 mb-4">
                    <ul class="ml-4">
            
                        <li data-select="info" id="info" href="javascript:void(0)" class="mb-2 px-4 py-4 text-gray-100 flex flex-row  border-gray-300 hover:text-black   hover:bg-gray-300  hover:font-bold rounded rounded-lg">
                            <span>
                                <svg class="w-5 h-5 " fill="currentColor" viewBox="0 0 30 30"  xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M 15 3 C 8.373 3 3 8.373 3 16 c 0 6.627 5.373 12 12 12 s 12 -5.373 12 -12 C 27 8.373 21.627 3 15 3 z h 2 z z M 4 17 l 3.5 -4.5 l 3.5 7.5 L 16 5 l 3 10 L 21 9 L 23 9 L 19 21 L 16 10 L 11 24 L 7.5 16 L 6 18 z" clip-rule="evenodd"></path></svg>                
                            </span>            
                            <a>
                                <span class="ml-2">Info</span>
                            </a>
                        </li>
                        <li data-select="editar" id="editar" href="javascript:void(0)" class="mb-2 px-4 py-4 text-gray-100 flex flex-row  border-gray-300 hover:text-black   hover:bg-gray-300  hover:font-bold rounded rounded-lg">
                            <span>              
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"></path></svg>
                            </span>
                            <a>
                                <span class="ml-2">Editar</span>
                            </a>
                        </li>
                        <li data-select="oferta" id="oferta" href="javascript:void(0)" class="mb-2 px-4 py-4 text-gray-100 flex flex-row  border-gray-300 hover:text-black   hover:bg-gray-300  hover:font-bold rounded rounded-lg">
                            <span>
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M 4.4160156 1.9960938 L 1.0039062 2.0136719 L 1.0136719 4.0136719 L 3.0839844 4.0039062 L 6.3789062 11.908203 L 5.1816406 13.822266 C 4.3432852 15.161017 5.3626785 17 6.9414062 17 L 19 17 L 19 15 L 6.9414062 15 C 6.8301342 15 6.8173041 14.978071 6.8769531 14.882812 L 8.0527344 13 L 15.521484 13 C 16.247484 13 16.917531 12.605703 17.269531 11.970703 L 20.871094 5.484375 C 21.242094 4.818375 20.760047 4 19.998047 4 L 13 4 L 13 7 L 16 7 L 12 11 L 8 7 L 11 7 L 11 4 L 5.25 4 L 4.4160156 1.9960938 z M 11 4 L 13 4 L 13 2 L 11 2 L 11 4 z M 7 18 A 2 2 0 0 0 5 20 A 2 2 0 0 0 7 22 A 2 2 0 0 0 9 20 A 2 2 0 0 0 7 18 z M 17 18 A 2 2 0 0 0 15 20 A 2 2 0 0 0 17 22 A 2 2 0 0 0 19 20 A 2 2 0 0 0 17 18 z"></path></svg>
                            </span>
                            <a>               
                                <span class="ml-2">Ofertas</span>
                            </a>
                        </li>
                        <li  data-select="pedido" id="pedido" href="javascript:void(0)" class="mb-2 px-4 py-4 text-gray-100 flex flex-row  border-gray-300 hover:text-black   hover:bg-gray-300  hover:font-bold rounded rounded-lg">
                            <span>
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M10 2a4 4 0 00-4 4v1H5a1 1 0 00-.994.89l-1 9A1 1 0 004 18h12a1 1 0 00.994-1.11l-1-9A1 1 0 0015 7h-1V6a4 4 0 00-4-4zm2 5V6a2 2 0 10-4 0v1h4zm-6 3a1 1 0 112 0 1 1 0 01-2 0zm7-1a1 1 0 100 2 1 1 0 000-2z" clip-rule="evenodd"></path></svg>
                            </span>
                            <a>
                                <span class="ml-2">Pedidos</span>
                            </a>
                        </li>
                        <li  data-select="cobro" id="cobro" href="javascript:void(0)" class="mb-2 px-4 py-4 text-gray-100 flex flex-row  border-gray-300 hover:text-black   hover:bg-gray-300  hover:font-bold rounded rounded-lg">
                            <span>
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 30 30" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M 4 2 C 3.448 2 3 2.448 3 3 L 3 24 C 3 26.209 4.791 28 7 28 L 22 28 L 23 28 L 24 28 C 26.209 28 28 26.209 28 24 L 28 10 C 28 9.448 27.552 9 27 9 L 25 9 L 25 24 C 25 24.553 24.552 25 24 25 C 23.448 25 23 24.553 23 24 L 23 3 C 23 2.448 22.552 2 22 2 L 4 2 z M 6 6 L 20 6 L 20 9 L 6 9 L 6 6 z M 6 13 L 12 13 L 12 15 L 6 15 L 6 13 z M 14 13 L 20 13 L 20 15 L 14 15 L 14 13 z M 6 17 L 12 17 L 12 19 L 6 19 L 6 17 z M 14 17 L 20 17 L 20 19 L 14 19 L 14 17 z M 6 21 L 12 21 L 12 23 L 6 23 L 6 21 z M 14 21 L 20 21 L 20 23 L 14 23 L 14 21 z" clip-rule="evenodd"></path></svg>
                            </span>
                            <a>
                                <span class="ml-2">Cobros</span>
                            </a>
                        </li>
                        <li data-select="tarifa" id="tarifa" href="javascript:void(0)" class="mb-2 px-4 py-4 text-gray-100 flex flex-row  border-gray-300 hover:text-black   hover:bg-gray-300  hover:font-bold rounded rounded-lg">
                            <span>
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 30 30"  xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M24.707,8.793l-6.5-6.5C18.019,2.105,17.765,2,17.5,2H7C5.895,2,5,2.895,5,4v22c0,1.105,0.895,2,2,2h16c1.105,0,2-0.895,2-2 V9.5C25,9.235,24.895,8.981,24.707,8.793z M18,21h-8c-0.552,0-1-0.448-1-1c0-0.552,0.448-1,1-1h8c0.552,0,1,0.448,1,1 C19,20.552,18.552,21,18,21z M20,17H10c-0.552,0-1-0.448-1-1c0-0.552,0.448-1,1-1h10c0.552,0,1,0.448,1,1C21,16.552,20.552,17,20,17 z M18,10c-0.552,0-1-0.448-1-1V3.904L23.096,10H18z" clip-rule="evenodd"></path></svg>
                            </span>
                            <a>
                                <span class="ml-2">Tarifas</span>
                            </a>
                        </li>
                        <li data-select="incidencia" id="incidencia" href="javascript:void(0)" class="mb-2 px-4 py-4 text-gray-100 flex flex-row  border-gray-300 hover:text-black   hover:bg-gray-300  hover:font-bold rounded rounded-lg">
                            <span>
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 30 30"  xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M22,4H8C5.791,4,4,5.791,4,8v14c0,2.209,1.791,4,4,4h14c2.209,0,4-1.791,4-4V8C26,5.791,24.209,4,22,4z M6.293,10.293l4-4	c0.391-0.391,1.023-0.391,1.414,0s0.391,1.023,0,1.414l-4,4C7.512,11.902,7.256,12,7,12s-0.512-0.098-0.707-0.293	C5.902,11.316,5.902,10.684,6.293,10.293z M6.293,16.293l10-10c0.391-0.391,1.023-0.391,1.414,0s0.391,1.023,0,1.414l-10,10	C7.512,17.902,7.256,18,7,18s-0.512-0.098-0.707-0.293C5.902,17.316,5.902,16.684,6.293,16.293z M22,24c-1.105,0-2-0.895-2-2	c0-1.105,0.895-2,2-2s2,0.895,2,2C24,23.105,23.105,24,22,24z M23.707,13.707l-10,10C13.512,23.902,13.256,24,13,24	s-0.512-0.098-0.707-0.293c-0.391-0.391-0.391-1.023,0-1.414l10-10c0.391-0.391,1.023-0.391,1.414,0S24.098,13.316,23.707,13.707z M23.707,7.707l-16,16C7.512,23.902,7.256,24,7,24s-0.512-0.098-0.707-0.293c-0.391-0.391-0.391-1.023,0-1.414l16-16	c0.391-0.391,1.023-0.391,1.414,0S24.098,7.316,23.707,7.707z" clip-rule="evenodd"></path></svg>
                            </span>
                            <a>
                                <span class="ml-2">Incidencias</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </nav>
        </div>


        <div class="absolute inset-y-0 right-0 w-5/6">            
            <div id="infos">
                <div class="px-6 py-3 text-xl border-b font-bold">Info</div>
                <div class="flex items-center justify-center flex-1 h-full p-4">
                    @include('clientes.dashboard',['IdCliente'=>$IdCliente])                        
                </div>
            </div>
            <div id="editars" hidden>
                <div class="px-6 py-3 text-xl border-b font-bold">Editar Cliente</div>
                    <div class="flex items-center justify-center flex-1 h-full p-4">
                        @include('clientes.edit', ['idCliente'=>$IdCliente])
                    </div>
                </div>
            <div id="ofertas" hidden>
                <div class="px-6 py-3 text-xl border-b font-bold">Ofertas: </div>
                <div class="flex items-center justify-center flex-1 h-full p-4">
                    @php
                        $randomKey = time();
                    @endphp 
                    <livewire:ofertas-datatable :post='$IdCliente' :key='$randomKey'                                
                        exportable    
                    />
                </div>
            </div>
            <div id="pedidos" hidden>
                <div class="px-6 py-3 text-xl border-b font-bold">Pedidos:</div>
                <div class="flex items-center justify-center flex-1 h-full p-4">
                    <livewire:pedidos-datatable :post='$IdCliente' :key='$randomKey'                              
                        exportable                                                          
                    />
                </div>
            </div>
            <div id="cobros" hidden>
                <div class="px-6 py-3 text-xl border-b font-bold">Cobros:</div>
                <div class="flex items-center justify-center flex-1 h-full p-4">
                    <livewire:cobros-datatable :post='$IdCliente'  :key='$randomKey'                             
                        exportable                                                             
                    />
                </div>
            </div>
            <div id="tarifas" hidden>
                <div class="px-6 py-3 text-xl border-b font-bold">Tarifas Especiales</div>
                <div class=" items-center justify-center p-4">
                    @include('clientes.tarifas.tarifas',['IdCliente'=>$IdCliente])    
                </div>
            </div>
            <div id="incidencias" hidden>
                <div class="px-6 py-3 text-xl border-b font-bold">Incidencias</div>
                <div class="flex items-center justify-center flex-1 p-4">
                    <livewire:incidencias-datatable :post="$IdCliente" :key="$randomKey"                                                                               
                    exportable                                             
                    />
                </div>
            </div>           
        </div>
    </body>
    @livewireScripts
    <script>
        
      document.getElementById("info").onclick = function() {showTab(this)};
      document.getElementById("editar").onclick = function() {showTab(this)};
      document.getElementById("oferta").onclick = function() {showTab(this)};
      document.getElementById("pedido").onclick = function() {showTab(this)};
      document.getElementById("cobro").onclick = function() {showTab(this)};
      document.getElementById("tarifa").onclick = function() {showTab(this)};
      document.getElementById("incidencia").onclick = function() {showTab(this)};
      
      
      function showTab(e) {
        let selectType = $(e).attr("data-select");
      	if (selectType == 'info') {
      	    $("#editars,#ofertas,#pedidos,#cobros,#tarifas,#incidencias").hide();
      	    $("#infos").show();
      	    $("#info").addClass('text-white-800');
      	    $("#editar,#oferta,#pedido,#cobro,#tarifa,#incidencia").removeClass('text-white-800');
      
      	} else if (selectType == 'editar') {
      
      		$("#infos,#ofertas,#pedidos,#cobros,#tarifas,#incidencias").hide();
      	    $("#editars").show();
      		$("#editar").addClass('tex-twhite-800');
      		$("#info,#oferta,#pedido,#cobro,#tarifa,#incidencia").removeClass('text-white-800');
      
      	} else if (selectType == 'oferta') {
      
            $("#infos,#editars,#pedidos,#cobros,#tarifas,#incidencias").hide();
            $("#ofertas").show();
            $("#oferta").addClass('tex-twhite-800');
            $("#info,#editar,#pedido,#cobro,#tarifa,#incidencia").removeClass('text-white-800');

        } else if (selectType == 'pedido') {
            
            $("#infos,#ofertas,#editars,#cobros,#tarifas,#incidencias").hide();
            $("#pedidos").show();
            $("#pedido").addClass('tex-twhite-800');
            $("#info,#oferta,#editar,#cobro,#tarifa,#incidencia").removeClass('text-white-800');

        } else if (selectType == 'cobro') {
            
            $("#infos,#ofertas,#pedidos,#editars,#tarifas,#incidencias").hide();
            $("#cobros").show();
            $("#cobro").addClass('tex-twhite-800');
            $("#info,#oferta,#pedido,#editar,#tarifa,#incidencia").removeClass('text-white-800');

        }  else if (selectType == 'tarifa') {
            
            $("#infos,#ofertas,#pedidos,#editars,#cobros,#incidencias").hide();
            $("#tarifas").show();
            $("#tarifa").addClass('tex-twhite-800');
            $("#info,#oferta,#pedido,#editar,#cobro,#incidencia").removeClass('text-white-800');

        }  else if (selectType == 'incidencia') {
            
            $("#infos,#ofertas,#pedidos,#editars,#tarifas,#cobros").hide();
            $("#incidencias").show();
            $("#incidencia").addClass('tex-twhite-800');
            $("#info,#oferta,#pedido,#editar,#tarifa,#cobro").removeClass('text-white-800');

        }
      }
    
    </script>
    <script>
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
    </script>


</html>

