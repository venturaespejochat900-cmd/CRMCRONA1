<?php

use App\Http\Controllers\ClienteController;

if (session('codigoComisionista') == 0) {
    header("Location: https://cronadis.abmscloud.com/");
    exit();
} else {
    $randomKey = time();
?>
    @include('layouts.header')
    @livewireStyles
    <script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fullcalendar@5.10.2/main.min.css">

    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.10.2/main.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.10.2/locales/es.js"></script>    
    <script src="{{ asset('js/app.js') }}" defer></script>
    <script src="{{asset('js/agenda.js')}}"></script>

    @include('layouts.sidebar')
    @include('layouts.navbarCalendar')

    <style>
        
    </style>

    <div class="container mx-auto flex flex-grap justify-center mx-4">
        <div class="w-4/6 p-4 ">
            <div id="agenda" name="agenda" class="agenda bg-white text-black p-3">
            </div>
        </div>
    </div>
    

    <!-- Modal -->

    <div id="evento" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 w-full md:inset-0 h-modal md:h-full flex justify-center">
        <div class="relative p-4 w-full max-w-2xl h-full md:h-auto">
            <!-- Modal content -->
            <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                <!-- Modal header -->
                <div class="flex justify-between items-start p-5 rounded-t border-b dark:border-gray-600">
                    <h3 class="text-xl font-semibold text-gray-900 lg:text-2xl dark:text-white">
                        Agregar evento en Calendario
                    </h3>
                    <button type="button" id="cerrar" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-toggle="defaultModal">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                        </svg>
                    </button>
                </div>
                <!-- Modal body -->
                <div class="p-6 space-y-6 flex justify-center">
                    <form action="" id="formularioEventos" class="w-full max-w-lg" name="formularioEventos">
                        {{ csrf_field() }}
                        <div class="flex flex-wrap -mx-3 mb-6">

                            <!-- Cliente/comisionista -->
                            <div class="w-full px-3 mb-6 md:mb-0">
                                <label for="Cliente/Comisionista" class="block text-gray-700 text-sm font-bold mb-2">Cliente/Comisionista</label>                                
                                <input type='hidden' id="accionPosicionId" name="accionPosicionId" class="mt-1 w-full border-gray-500 shadow-md rounded-md" >                                
                                <input type='hidden' id="tituloAgenda" class="mt-1 w-full border-gray-500 shadow-md rounded-md" type="text" >                                
                                <input type='hidden' name="comisionistaOculto" id="comisionistaOculto" class="mt-1 w-full border-gray-500 shadow-md rounded-md" value="">
                                <input type='hidden' name="codigoCategoriaCliente" id="codigoCategoriaCliente" class="mt-1 w-full border-gray-500 shadow-md rounded-md" value="">
                                <livewire:search-dropdownpoten :key="$randomKey"/>
                                <!-- <input type="text" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" name="id" id="id" aria-describedby="helpId" placeholder=""> -->
                                <small id="helpId" class="form-text text-muted">&nbsp;</small>
                            </div>

                            <!-- Fecha y hora inicio -->
                            <div class="w-1/2 px-3 mb-6 md:mb-0">
                                <label for="id" class="block text-gray-700 text-sm font-bold mb-2">Fecha Inicio</label>
                                <input type="date" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" name="start" id="start" aria-describedby="helpId" placeholder="">
                                <small id="helpId" class="form-text text-muted">&nbsp;</small>
                            </div>
                            <div class="w-1/2 px-3">
                                <label for="id" class="block text-gray-700 text-sm font-bold mb-2">Hora Fin</label>
                                <input type="time" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" name="startH" id="startH" aria-describedby="helpId" placeholder="">
                                <small id="helpId" class="form-text text-muted">&nbsp;</small>
                            </div>

                            <!-- Fecha y hora fin -->
                            <div class="w-1/2 px-3 mb-6 md:mb-0">
                                <label for="id" class="block text-gray-700 text-sm font-bold mb-2">Fecha Fin</label>
                                <input type="date" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" name="end" id="end" aria-describedby="helpId" placeholder="">
                                <small id="helpId" class="form-text text-muted">&nbsp;</small>
                            </div>
                            <div class="w-1/2 px-3 mb-6 md:mb-0">
                                <label for="id" class="block text-gray-700 text-sm font-bold mb-2">Hora Fin</label>
                                <input type="time" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" name="endH" id="endH" aria-describedby="helpId" placeholder="">
                                <small id="helpId" class="form-text text-muted">&nbsp;</small>
                            </div>

                            <div class="w-1/2 px-3 mb-6 md:mb-0">
                                <label for="id" class="block text-gray-700 text-sm font-bold mb-2">Color</label>
                                <input type="color" class="form-control" name="color" id="color" value="#0891B2">
                                <small id="helpId" class="form-text text-muted">&nbsp;</small>                    
                            </div>
                            <div class="w-1/2 px-3 mb-6 md:mb-0">
                                <label for="id" class="block text-gray-700 text-sm font-bold mb-2">Color Texto</label>
                                <input type="color" class="form-control" name="textColor" id="textColor" value="#ffffff">
                                <small id="helpId" class="form-text text-muted">&nbsp;</small>
                            </div>

                            <!-- Select -->
                            <div id="accionComercial2" class="inline-block relative w-1/2 px-3 mb-6 md:mb-0">
                                <label for="descripcion" class="block text-gray-700 text-sm font-bold mb-2">Código Acción Comercial</label>
                                <select id="accionComercial" name="accionComercial" class="block text-gray-700 text-sm font-bold  appearance-none w-full bg-white border border-gray-400 hover:border-gray-500 px-4 py-2 pr-8 rounded shadow leading-tight focus:outline-none focus:shadow-outline" onChange="temaComercial(this.value)">
                                    <option value=""></option>
                                    <?php  $accionComercial= ClienteController::accionComercial() ?>
                                    @foreach($accionComercial as $accion)
                                        <option value="{{$accion->CodigoAccionComercialLc}}">{{$accion->CodigoAccionComercialLc}}-{{$accion->AccionComercialLc}}</option>
                                    @endforeach 
                                </select>                                
                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                        <path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z" />
                                    </svg>
                                </div>
                            </div>
                            <div class="inline-block relative w-1/2 px-3 mb-6 md:mb-0" id="temasComerciales"></div>
                            <div class="inline-block relative w-1/2 px-3 mb-6 md:mb-0">
                                <label for="descripcion" class="block text-gray-700 text-sm font-bold mb-2">Estado</label>
                                <select id="estado" name="estado" class="block text-gray-700 text-sm font-bold appearance-none w-full bg-white border border-gray-400 hover:border-gray-500 px-4 py-2 pr-8 rounded shadow leading-tight focus:outline-none focus:shadow-outline">
                                    <option value=""></option>
                                    <option value="0">Pendiente</option>
                                    <option value="1">Iniciada</option>
                                    <!-- <option value="2">Detenida</option> -->
                                    <option value="3">Cerrada</option>
                                </select>
                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                        <path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z" />
                                    </svg>
                                </div>
                            </div>
                            <div class="inline-block relative w-1/2 px-3 mb-6 md:mb-0">
                                <label for="descripcion" class="block text-gray-700 text-sm font-bold mb-2">Prioridad</label>
                                <select id="prioridad" name="prioridad" class="block text-gray-700 text-sm font-bold appearance-none w-full bg-white border border-gray-400 hover:border-gray-500 px-4 py-2 pr-8 rounded shadow leading-tight focus:outline-none focus:shadow-outline">
                                    <option value=""></option>
                                    <?php  $prioridadAgenda= ClienteController::prioridad() ?>
                                    @foreach($prioridadAgenda as $prioridad)
                                        <option value="{{$prioridad->CodigoTipoPrioridadLc}}">{{$prioridad->CodigoTipoPrioridadLc}}</option>
                                    @endforeach 
                                </select>
                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                        <path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z" />
                                    </svg>
                                </div>
                            </div>
                        
                            <!-- Objetivo -->
                            <div class="w-full px-3 mb-6 md:mb-0">
                                <label for="descripcion" class="block text-gray-700 text-sm font-bold mb-2">Objetivo</label>
                                <textarea name="objetivo" id="objetivo" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" rows="3"></textarea>
                                <small id="helpId" class="form-text text-muted">&nbsp;</small>
                            </div>

                            <div id="resultados" class="w-full px-3 mb-6 md:mb-0">
                                <label for="descripcion" class="block text-gray-700 text-sm font-bold mb-2">Resultado</label>
                                <textarea name="resultado" id="resultado" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" rows="3"></textarea>
                                <small id="helpId" class="form-text text-muted">&nbsp;</small>
                            </div>
                        </div>
                    </form>
                </div>
                <!-- Modal footer -->
                <div class="flex items-center justify-end p-6 space-x-2 rounded-b border-t border-gray-200 dark:border-gray-600">
                    <button type="button" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" id="btnGuardar" onclick="cerrarModal()">Guardar</button>
                    <button type="button" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" id="btnModificar" onclick="cerrarModal()">Modificar</button>
                    <button hidden type="button" class="btn btn-primary" id="btnMoveDate">Mover Fecha</button>
                    <button type="button" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" id="btnEliminar" onclick="cerrarModal()">Eliminar</button>
                    <button type="button" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" id="btnCerrar">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@ryangjchandler/spruce@1.1.0/dist/spruce.umd.js"></script>
    <script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.8.0/dist/alpine.min.js"></script>
    <script>

        function temaComercial(valor){            
            var temaComercial;
            datos={
                'accion':valor,
                "_token": $("meta[name='csrf-token']").attr("content")
            };
            $.ajax({
            url: './temaComercial',
            data: datos,
            type: 'post',
            timeout: 2000,
            async: true,
            success: function(result) {
            //console.log(result);
                temaComercial = result;
                $('#temasComerciales').empty();
                html2 = '<label for="temaComercial" class="block text-gray-700 text-sm font-bold mb-2">Código Acción Comercial</label>'+
                '<select id="temaComercial" name="temaComercial" class="block text-gray-700 text-sm font-bold  appearance-none w-full bg-white border border-gray-400 hover:border-gray-500 px-4 py-2 pr-8 rounded shadow leading-tight focus:outline-none focus:shadow-outline">'+
                    '<option value=""></option>';                
                    for(let l = 0; l<temaComercial.length; l++){ html2 +='<option value="' +temaComercial[l].CodigoTemaComercialLc+'">'+temaComercial[l].TemaComercialLc+'</option>';
                        }                    
                    html2 +='</select>'+
                    '<div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">'+
                        '<svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">'+
                            '<path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z" />'+
                        '</svg>'+
                    '</div>';            
                    $('#temasComerciales').append(html2);
                }
            });
        }

        function selectCodigoAgenda(id,codigo,tipo,nombre){
            //console.log(id);        
            //document.getElementById('titulo').text= tipo+'-'+nombre;
            //$('#tituloAgenda').writevalue(tipo+'-'+nombre);
            $('#tituloAgenda').val(tipo+'-'+nombre+'-'+codigo);        
            $('#comisionistaOculto').val(codigo);
            $('#codigoCategoriaCliente').val(tipo);
            $('#agendaInput').val(tipo+"-"+nombre)       
            $(".angendaResultado-box").hide();        
        }

        function cerrarModal(){
            $('#evento').addClass('hidden')
        }

    </script>
<?php
}
?>
@livewireScripts
@include('layouts.footerCalendar')
@include('layouts.panels')

