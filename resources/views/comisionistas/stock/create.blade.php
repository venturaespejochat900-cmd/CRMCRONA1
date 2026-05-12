<?php use App\Http\Controllers\ArticuloController; ?>
@php
    $randomKey = time();
@endphp 
<div class="grid grid-cols-4 gap-4 bg-white rounded-md dark:bg-darker py-3 px-3 ">


    <div>
        <label for="articulo" class="block text-sm font-medium text-gray-700 dark:text-light ">Articulo</label>
        <livewire:search-dropdown :key="$randomKey"/>              
    </div>

    <div>
        <label for="unidades1" class="block text-sm font-medium text-gray-700 dark:text-light">Unidasdes 1</label>
        <input type="number" id="unidades1" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm text-gray-900 " value="" disabled>
    </div>
   
    <div>
        <label for="precio1" class="block text-sm font-medium text-gray-700 dark:text-light">Precio 1</label>
        <input type="number" id="precio1" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm text-gray-900 " value="" disabled>
    </div>
    
    <div>
        <label for="unidades2" class="block text-sm font-medium text-gray-700 dark:text-light">Unidasdes 2</label>
        <input type="number" id="unidades2" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm text-gray-900 " value="" disabled>
    </div>
   
    <div>
        <label for="precio2" class="block text-sm font-medium text-gray-700 dark:text-light">Precio 2</label>
        <input type="number" id="precio2" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm text-gray-900 " value="" disabled>
    </div>

    <div>
        <label for="unidades3" class="block text-sm font-medium text-gray-700 dark:text-light">Unidasdes 3</label>
        <input type="number" id="unidades3" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm text-gray-900 " value="" disabled>
    </div>
   
    <div>
        <label for="precio3" class="block text-sm font-medium text-gray-700 dark:text-light">Precio 3</label>
        <input type="number" id="precio3" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm text-gray-900 " value="" disabled>
    </div>

    <div>
        <label for="unidades4" class="block text-sm font-medium text-gray-700 dark:text-light">Unidasdes 4</label>
        <input type="number" id="unidades4" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm text-gray-900 " value="" disabled>
    </div>
   
    <div>
        <label for="precio4" class="block text-sm font-medium text-gray-700 dark:text-light">Precio 4</label>
        <input type="number" id="precio4" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm text-gray-900 " value="" disabled>
    </div>
    
</div>
<div class="grid grid-cols-2 gap-6">
    <div id="insertarTarifa" class="mt-3">
        <button onclick="refrescar()" class="flex items-center space-x-2 px-3 ml-3 mb-2  rounded-md bg-primary text-white text-xs leading-4 font-medium uppercase tracking-wider hover:bg-primary-dark focus:outline-none" style="height: 30px !important;"><span>Registrar Tarifa</span>
        </button>
    </div>
    <div id="insertarTarifaProducto" class="mt-3" style="display: none;" >
        <button onclick="insertarTarifaProducto()" class="flex items-center space-x-2 px-3 ml-3 mb-2  rounded-md bg-primary text-white text-xs leading-4 font-medium uppercase tracking-wider hover:bg-primary-dark focus:outline-none" style="height: 30px !important;"><span>Registrar Tarifa</span>
        </button>
    </div>
    <div id="limpiarTarifa" class="ml-auto mt-3 justify-items: end;">
        <button onclick="limpiarTarifa()" class="flex items-center space-x-2 px-3 ml-3 mb-2  rounded-md bg-red-600 text-white text-xs leading-4 font-medium uppercase tracking-wider hover:bg-red-400 focus:outline-none" style="height: 30px !important;"><span>Limpiar</span>
        </button>
    </div>
</div>

<script>
    function limpiarTarifa(cod){        
        $('#articuloTarifa').val('');
        $('#unidades1').val('');                
        $('#unidades2').val('');                
        $('#unidades3').val('');                
        $('#unidades4').val('');                
        $('#precio1').val('');                
        $('#precio2').val('');                
        $('#precio3').val('');                
        $('#precio4').val('');                        
    }    

    function selectCodigoProducto(producto,precio){
        //console.log(producto);
        //console.log(precio);
        $('#articuloTarifa').val(producto);
        $('#precio1').val(precio);
        $('#unidades1').val(1);
        $(".productoResultado-box").hide();
        $('#precio1').prop('disabled', false);       
        $('#precio2').prop('disabled', false);       
        $('#precio3').prop('disabled', false);       
        $('#precio4').prop('disabled', false);       
        $('#unidades1').prop('disabled', false);       
        $('#unidades2').prop('disabled', false);       
        $('#unidades3').prop('disabled', false);       
        $('#unidades4').prop('disabled', false);       
        $('#insertarTarifa').css("display", "none");        
        $('#insertarTarifaProducto').css("display", "block");        
        
    }

    function insertarTarifaProducto(cod){
          console.log('producto');
          var cliente = cod;
          var codigoArticulo = $('#articuloTarifa').val();
          var precio = $('#precioTarifa').val();          
          $('#articuloTarifa'+cod+'').val('');
          $('#precioTarifa'+cod+'').val('');
          $('#insertarTarifa'+cod+'').css("display", "block");
          $('#insertarTarifaProducto'+cod+'').css("display", "none");
          $('#insertarFechaTarifa'+cod+'').css("display", "none");
          $('#insertarFechaTarifaI'+cod+'').css("display", "none");
          $('#familiaTarifa'+cod+'').prop('disabled', false).css("background-color", "white");
          $('#descuentoTarifa'+cod+'').prop('disabled', false).css("background-color", "white");            
            var parametros = {
                "cliente": cliente,
                "codigo": codigoArticulo,
                "precio": precio,
                "fechaInicnio": fechaIni,
                "fechaFin": fechaFin,
                "_token": $("meta[name='csrf-token']").attr("content")
            };
            console.log(parametros);
            $.ajax({
                url: './tarifa/producto',
                data: parametros,
                type: 'post',
                timeout: 2000,
                async: true,
                success: function(respuesta) {
                    console.log(respuesta);
                }
            });            
          //$("#refrescarTablaTarifa").trigger('click');
          refrescar();
          


    }    

    function refrescar(){
        $("#refrescarTablaTarifa").trigger('click');
    }

</script>