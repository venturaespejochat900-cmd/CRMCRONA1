<?php

use Illuminate\Support\Facades\DB;

    $fecha = date("Y-m-d");
    $hora = date("h:i");
    $accionComercial = DB::table('LcAccionesComerciales')->get();
    $prioridad = DB::table('LcTiposPrioridadTareas')->get();
    $CodigoComisionista = session('codigoComisionista');


?>
<div class="p-6 flex-grow">
    <div class="px-4 py-5 bg-white sm:p-6 text-gray-700">
        <div class="grid grid-cols-6 gap-6">
            <div class="col-span-6 sm:col-span-6">
                <label class="block text-sm font-medium text-gray-700">Fecha Inicio</label>
                <input type="date" name="fechaInicio{{$IdCliente}}" id="fechaInicio{{$IdCliente}}" value="{{$fecha}}" min="{{$fecha}}" class="mt-1 w-full border-gray-500 shadow-md rounded-md">
            </div>

            <div class="col-span-6 sm:col-span-6">
                <label class="block text-sm font-medium text-gray-700">Hora Inicio</label>
                <input type="time" name="horaInicio{{$IdCliente}}" id="horaInicio{{$IdCliente}}" value="{{$hora}}"  class="mt-1 w-full border-gray-500 shadow-md rounded-md">
            </div>

            <div class="col-span-6 sm:col-span-6">
                <label class="block text-sm font-medium text-gray-700">Fecha Fin</label>
                <input type="date" name="fechaFin{{$IdCliente}}" id="fechaFin{{$IdCliente}}" value="{{$fecha}}" min="{{$fecha}}" class="mt-1 w-full border-gray-500 shadow-md rounded-md">
            </div>

            <div class="col-span-6 sm:col-span-6">
                <label class="block text-sm font-medium text-gray-700">Hora Fin</label>
                <input type="time" name="horaFin{{$IdCliente}}" id="horaFin{{$IdCliente}}" value="{{$hora}}"  class="mt-1 w-full border-gray-500 shadow-md rounded-md">
            </div>

            <div class="col-span-6 sm:col-span-3">
                <label class="block text-sm font-medium text-gray-700">Código Comisionista/Cliente</label>
                <input type="text" name="comisionista{{$IdCliente}}" id="comisionista{{$IdCliente}}" class="mt-1 w-full border-gray-500 shadow-md rounded-md" value="{{$CodigoCliente}}">
                <input type="hidden" name="comisionistaOculto{{$IdCliente}}" id="comisionistaOculto{{$IdCliente}}" class="mt-1 w-full border-gray-500 shadow-md rounded-md" value="{{$CodigoComisionista}}">
            </div>

            <div class="col-span-6 sm:col-span-3">
                <label for="last-name" class="block text-sm font-medium text-gray-700">Código Acción Comercial</label>
                <select id="accionComercial{{$IdCliente}}" name="accionComercial{{$IdCliente}}" class="mt-1 block w-full  border border-gray-300 bg-white rounded-md shadow-md " onChange="temaComercial(this.value, this.id)">
                    <option value=""></option>
                    @foreach($accionComercial as $a )
                        <option value="{{$a->CodigoAccionComercialLc}}">{{$a->CodigoAccionComercialLc}}-{{$a->AccionComercialLc}}</option>
                    @endforeach    
                    </select>
            </div>

            <div class="col-span-6 sm:col-span-3" id="temasComercialesaccionComercial{{$IdCliente}}">
                
            </div>

            <div class="col-span-6 sm:col-span-3">
                <label for="last-name" class="block text-sm font-medium text-gray-700">Estado</label>
                <select id="estado{{$IdCliente}}" name="estado{{$IdCliente}}" class="mt-1 block w-full  border border-gray-300 bg-white rounded-md shadow-md ">
                    <option value=""></option>
                    <option value="0">Pendiente</option>
                    <option value="1">Iniciada</option>
                    <option value="2">Detenida</option>
                    <option value="3">Cerrada</option>
                </select>
            </div>

            <div class="col-span-6 sm:col-span-3">
                <label for="last-name" class="block text-sm font-medium text-gray-700">Prioridad</label>
                <select id="prioridad{{$IdCliente}}" name="prioridad{{$IdCliente}}" class="mt-1 block w-full  border border-gray-300 bg-white rounded-md shadow-md ">
                    <option value=""></option>
                    @foreach($prioridad as $b)
                        <option value="{{$b->CodigoTipoPrioridadLc}}">{{$b->TipoPrioridadLc}}</option>
                    @endforeach
                    </select>
            </div>

            <div class="col-span-6 sm:col-span-12">                                                                                                                                                                                                                       
                <div class="box border rounded flex flex-col shadow bg-white">
                    <div class="box__title bg-grey-lighter px-3 py-2 border-b"><h3 class="text-sm text-grey-darker font-medium">Objetivo</h3></div>
                    <textarea class="text-grey-darkest flex-1 p-2 m-1 bg-transparent" name="objetivo{{$IdCliente}}" id="objetivo{{$IdCliente}}"></textarea>                
                </div>
            </div> </br>
            
        </div>
        <div class="px-4 py-3 bg-gray-50 text-right sm:px-6">
            <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500" onclick="seguimiento('{{$IdCliente}}')">
                Guardar
            </button>
            <button @click={show=false} type="button" class="bg-primary text-gray-100 rounded px-4 py-2 mr-1 ml-2" id="cerrarModal">Cerrar</Button>
        </div>
    </div>
</div>

<script>
    function temaComercial(valor, id){
        console.log(id);
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
           $('#temasComerciales'+id+'').empty();
           var html2 = '<label for="last-name" class="block text-sm font-medium text-gray-700">Tema Comercial</label>'+
            '<select id="temaComercial'+id+'" name="temaComercial'+id+'" class="mt-1 block w-full  border border-gray-300 bg-white rounded-md shadow-md ">'+
                '<option value=""></option>';
                for(let l = 0; l<temaComercial.length; l++){ html2 +='<option value="' +temaComercial[l].CodigoTemaComercialLc+'">'+temaComercial[l].TemaComercialLc+'</option>';
                    }
            html2 +='</select>';
            $('#temasComerciales'+id+'').append(html2);
        }
    });
        
    }
    

    function seguimiento(id){
        datos={
            'fechaInicio': $('#fechaInicio'+id+'').val(),
            'horaInicio': $('#horaInicio'+id+'').val(),
            'fechaFin': $('#fechaFin'+id+'').val(),
            'horaFin': $('#horaFin'+id+'').val(),
            'comisionistaOculto': $('#comisionista'+id+'').val(),
            'accionComercial': $('#accionComercial'+id+'').val(),
            'temaComercial':$('#temaComercialaccionComercial'+id+'').val(),
            'estado': $('#estado'+id+'').val(),
            'prioridad': $('#prioridad'+id+'').val(),
            'objetivo': $('#objetivo'+id+'').val(),
            'codigoCategoriaCliente':'POT',
            //'resultado': $('#resultado'+id+'').val(),
            "_token": $("meta[name='csrf-token']").attr("content")
        };
        console.log(datos);
        $.ajax({
        url: './seguimiento',
        data: datos,
        type: 'post',
        timeout: 2000,
        async: true,
        success: function(result) {
           //console.log(result);
           $('#cerrarModal').trigger('click');
        }
    });
        console.log(datos);
    }
</script>