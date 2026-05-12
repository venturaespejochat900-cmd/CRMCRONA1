<?php use App\Http\Controllers\ArticuloController; ?>
<div class="grid grid-cols-4 gap-4 bg-white rounded-md dark:bg-darker py-3 px-3 ">


    <div>
        <label for="articulo" class="block text-sm font-medium text-gray-700 dark:text-light ">Articulo</label>
        <livewire:search-dropdown  :post="$IdCliente" :key="$randomKey"/>              
    </div>
   
   
    <div>
        <label for="precio" class="block text-sm font-medium text-gray-700 dark:text-light">Precio</label>
        <input type="number" id="precioTarifa{{$IdCliente}}" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm text-gray-900 " value="" disabled>
    </div>
    

    <div>
        <label for="familia" class="block text-sm font-medium text-gray-700 dark:text-light ">Familia</label>
        <livewire:familia-dropdown  :post="$IdCliente" :key="$randomKey"/>              
    </div>
    <div>
        <label for="Descuento" class="block text-sm font-medium text-gray-700 dark:text-light">Descuento</label>
        <input type="number" id="descuentoTarifa{{$IdCliente}}" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm text-gray-900 " value="" disabled>
    </div>
    <div id="insertarFechaTarifaI{{$IdCliente}}" style="display: none;">
        <?php $date = date('Y-m-d');?>
        <label for="fecha2" class="block text-sm font-medium text-gray-700 dark:text-light">Fecha inicio tarifa</label>
        <input type="date" id="fechaTarifaI{{$IdCliente}}" min="<?= $date ?>" max="2059-12-31" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm text-gray-900 " value="<?= $date ?>">
    </div>
    <div id="insertarFechaTarifa{{$IdCliente}}" style="display: none;">
        <?php $date = date('Y-m-d');?>
        <label for="fecha" class="block text-sm font-medium text-gray-700 dark:text-light">Fecha fin tarifa</label>
        <input type="date" id="fechaTarifa{{$IdCliente}}" min="<?= $date ?>" max="2059-12-31" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm text-gray-900 " value="<?php echo '2059-12-31'; ?>">
    </div>
    
</div>
<div class="grid grid-cols-2 gap-6">
    <div id="insertarTarifa{{$IdCliente}}" class="mt-3">
        <button onclick="refrescar()" class="flex items-center space-x-2 px-3 ml-3 mb-2  rounded-md bg-primary text-white text-xs leading-4 font-medium uppercase tracking-wider hover:bg-primary-dark focus:outline-none" style="height: 30px !important;"><span>Registrar Tarifa</span>
        </button>
    </div>
    <div id="insertarTarifaProducto{{$IdCliente}}" class="mt-3" style="display: none;" >
        <button onclick="insertarTarifaProducto(<?=$IdCliente?>)" class="flex items-center space-x-2 px-3 ml-3 mb-2  rounded-md bg-primary text-white text-xs leading-4 font-medium uppercase tracking-wider hover:bg-primary-dark focus:outline-none" style="height: 30px !important;"><span>Registrar Tarifa</span>
        </button>
    </div>
    <div id="insertarTarifaFamilia{{$IdCliente}}" class="mt-3" style="display: none;" >
        <button onclick="insertarTarifaFamilia(<?=$IdCliente?>)"  class="flex items-center space-x-2 px-3 ml-3 mb-2  rounded-md bg-primary text-white text-xs leading-4 font-medium uppercase tracking-wider hover:bg-primary-dark focus:outline-none" style="height: 30px !important;"><span>Registrar Tarifa</span>
        </button>
    </div>
    <div id="limpiarTarifa{{$IdCliente}}" class="ml-auto mt-3 justify-items: end;">
        <button onclick="limpiarTarifa(<?=$IdCliente ?>)" class="flex items-center space-x-2 px-3 ml-3 mb-2  rounded-md bg-red-600 text-white text-xs leading-4 font-medium uppercase tracking-wider hover:bg-red-400 focus:outline-none" style="height: 30px !important;"><span>Limpiar</span>
        </button>
    </div>
</div>

<div class="col-span-4 bg-white rounded-md dark:bg-darker m-3">
    <livewire:descuentos-datatable :post="$IdCliente" :key="$randomKey"
    searchable="descuentoPrecioFamilia.CodigoArticulo, descuentoPrecioFamilia.CodigoFamilia, Articulos.DescripcionArticulo, VFamilias.Descripcion"
    modal   
    tarifa                                           
    />
</div>

<script>
    function limpiarTarifa(cod){
        $('#familiaTarifa'+cod+'').val('');
        $('#descuentoTarifa'+cod+'').val('');
        $('#articuloTarifa'+cod+'').val('');
        $('#precioTarifa'+cod+'').val('');
        $('#insertarFechaTarifa'+cod+'').css("display", "none");
        $('#insertarFechaTarifaI'+cod+'').css("display", "none");        
        $('#insertarTarifaFamilia'+cod+'').css("display", "none");
        $('#insertarTarifaProducto'+cod+'').css("display", "none");
        $('#insertarTarifa'+cod+'').css("display", "block");
        $('#familiaTarifa'+cod+'').prop('disabled', false).css("background-color", "white");
        $('#precioTarifa'+cod+'').prop('disabled', false).css("background-color", "white");
        $('#articuloTarifa'+cod+'').prop('disabled', false).css("background-color", "white");
        $('#descuentoTarifa'+cod+'').prop('disabled', false).css("background-color", "white");

    }

    function blockFamilia(e, cod){
        console.log(e);
          if (e == ""){
            $('#familiaTarifa'+cod+'').prop('disabled', false).css("background-color", "white");
            $('#precioTarifa'+cod+'').prop('disabled', true).css("background-color", "gray");
            $('#descuentoTarifa'+cod+'').prop('disabled', true).css("background-color", "gray");        
          }else{            
            $('#precioTarifa'+cod+'').prop('disabled', false).css("background-color", "white");
            $('#familiaTarifa'+cod+'').prop('disabled', true).css("background-color", "gray");
            $('#descuentoTarifa'+cod+'').prop('disabled', true).css("background-color", "gray");            
          }
      }

    function selectCodigoProducto(producto,cod,precio){
        //console.log(producto);
        //console.log(precio);
        $('#articuloTarifa'+cod+'').val(producto);
        $('#precioTarifa'+cod+'').val(precio);
        $(".productoResultado-box").hide();
        $('#precioTarifa'+cod+'').prop('disabled', false);
        $('#familiaTarifa'+cod+'').prop('disabled', true).css("background-color", "gray");
        $('#descuentoTarifa'+cod+'').prop('disabled', true).css("background-color", "gray");
        $('#insertarTarifa'+cod+'').css("display", "none");
        $('#insertarTarifaProducto'+cod+'').css("display", "block");
        $('#insertarFechaTarifa'+cod+'').css("display", "block");
        $('#insertarFechaTarifaI'+cod+'').css("display", "block");
        //console.log ($('#prescriptores'));
    }

    function blockArticulo(e, cod){
        console.log(e);
          if (e == ""){
            $('#articuloTarifa'+cod+'').prop('disabled', false).css("background-color", "white");
            $('#descuentoTarifa'+cod+'').prop('disabled', true).css("background-color", "gray");
            $('#precioTarifa'+cod+'').prop('disabled', true).css("background-color", "gray");             
          }else{            
            $('#descuentoTarifa'+cod+'').prop('disabled', false).css("background-color", "white");
            $('#articuloTarifa'+cod+'').prop('disabled', true).css("background-color", "gray");
            $('#precioTarifa'+cod+'').prop('disabled', true).css("background-color", "gray");
            ///console.log('hola');            
          }
    }

    function selectFamilia(familia,cod){
        //console.log(familia);
        //console.log(descuento);
        $('#familiaTarifa'+cod+'').val(familia);
        $('#descuentoTarifa'+cod+'').val(0);
        $(".familiaResultado-box").hide();
        $('#descuentoTarifa'+cod+'').prop('disabled', false);
        $('#articuloTarifa'+cod+'').prop('disabled', true).css("background-color", "gray");
        $('#precioTarifa'+cod+'').prop('disabled', true).css("background-color", "gray");
        $('#insertarTarifa'+cod+'').css("display", "none");
        $('#insertarTarifaFamilia'+cod+'').css("display", "block");
        $('#insertarFechaTarifa'+cod+'').css("display", "block");
        $('#insertarFechaTarifaI'+cod+'').css("display", "block");
        //console.log ($('#prescriptores'));
    }


    function insertarTarifaProducto(cod){
          console.log('producto');
          var cliente = cod;
          var codigoArticulo = $('#articuloTarifa'+cod+'').val();
          var precio = $('#precioTarifa'+cod+'').val();
          var fechaFin = $('#fechaTarifa'+cod+'').val();
          var fechaIni = $('#fechaTarifaI'+cod+'').val();
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

    function insertarTarifaFamilia(cod){
          console.log('familia');
          var cliente = cod;
          var codigoFamilia = $('#familiaTarifa'+cod+'').val();
          var descuento = $('#descuentoTarifa'+cod+'').val();
          var fechaFin = $('#fechaTarifa'+cod+'').val();
          var fechaIni = $('#fechaTarifaI'+cod+'').val();
          $('#familiaTarifa'+cod+'').val('');
          $('#descuentoTarifa'+cod+'').val('');
          $('#insertarTarifa'+cod+'').css("display", "block");
          $('#insertarTarifaFamilia'+cod+'').css("display", "none");
          $('#insertarFechaTarifa'+cod+'').css("display", "none");
          $('#insertarFechaTarifaI'+cod+'').css("display", "none");
          $('#articuloTarifa'+cod+'').prop('disabled', false).css("background-color", "white");
          $('#precioTarifa'+cod+'').prop('disabled', false).css("background-color", "white");
          var parametros = {
                "cliente": cliente,
                "familia": codigoFamilia,
                "descuento": descuento,
                "fechaInicnio": fechaIni,
                "fechaFin": fechaFin,
                "_token": $("meta[name='csrf-token']").attr("content")
            };
            console.log(parametros);
            $.ajax({
                url: './tarifa/familia',
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