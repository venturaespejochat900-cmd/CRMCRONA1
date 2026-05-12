@include('layouts.header')
@livewireStyles    
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/1.9.2/tailwind.min.css" integrity="sha512-l7qZAq1JcXdHei6h2z8h8sMe3NbMrmowhOl+QkP3UhifPpCW2MC4M0i26Y8wYpbz1xD9t61MLT9L1N773dzlOA==" crossorigin="anonymous" />
@include('layouts.sidebar')
@include('layouts.navbar')
<style>
    
    .preloader {
    width: 70px;
    height: 70px;
    border: 10px solid #eee;
    border-top: 10px solid #666;
    border-radius: 50%;
    animation-name: girar;
    animation-duration: 2s;
    animation-iteration-count: infinite;
    animation-timing-function: linear;
    margin-left: 350%;    
    }
@keyframes girar {
  from {
    transform: rotate(0deg);
  }
  to {
    transform: rotate(360deg);
  }
}

.tooltip{
  visibility: hidden;
  position: absolute;
  
}

.has-tooltip:hover .tooltip {
  visibility: visible;  
  z-index: 100;  
}

/* smartphones, touchscreens */
@media (hover: none) and (pointer: coarse) {
    .has-tooltip:hover .tooltip{
    visibility: hidden;    
    }
}
th:first-child, td:first-child
{
  /* position:sticky; */
  /* position:sticky; */
  /* left:0px; */
  background-color:wheat;
  color:black;
  font-weight: bold;
  /* z-index: 1; */
}
</style>
<?php 
    use App\Http\Controllers\InformesController;
?>
<?php
    if(session('codigoComisionista') == 0){  
        header("Location: http://cronadis.abmscloud.com/");
        exit();
    }else{
?>
<div class="grid grid-cols-1 p-4 space-y-12 lg:gap-12 lg:space-y-0 lg:grid-cols-4 border-b">
    <!-- Bar chart card -->
    <div class="col-span-12 bg-white rounded-md dark:bg-darker">
        <!-- Card header -->
        <div class="flex items-center justify-between p-4 border-b dark:border-primary">
            <h4 class="text-lg font-semibold text-gray-500 dark:text-light">Informes: Venta por Comisionista</h4>
            <div class="flex items-center space-x-2">
                <!-- <span class="text-sm text-gray-500 dark:text-light">Solicitar</span> -->
                <button onclick="enviarInforme()" class="bg-primary-darker hover:bg-primary-100 text-white hover:text-black font-bold py-2 px-4 rounded-full">
                    Enviar
                </button>
            </div>
        </div>
        <!-- Chart -->
        <div class="relative p-5">
            <div class="mt-5 md:mt-0 md:col-span-1">
                <form action="#" method="POST">
                    
                    <div class="grid grid-cols-6 gap-6">                                                    
                        
                        <div>                            
                            <label for="plazo" class="block text-sm font-medium text-gray-500 dark:text-light">Plazo:</label>
                            <select id="plazo" name="plazo" autocomplete="plazo" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm text-gray-900 ">
                                <option value="0"></option>
                                <option value="1">2 Semanas</option>
                                <option value="2">1 Mes</option>
                                <option value="3">3 Meses</option>
                                <option value="4">6 Meses</option> 
                                <option value="5">1 Año</option>
                                <!-- <option value="6">Completo</option>                                       -->
                            </select>
                        </div>
                        
                        <div>
                            <label for="dato" class="block text-sm font-medium text-gray-500 dark:text-light">Dato:</label>
                            <select id="dato" name="dato" autocomplete="dato" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm text-gray-900 ">
                                <option value="0"></option>
                                <option value="1">Con ventas</option>
                                <option value="2">Sin ventas</option>                               
                            </select>
                        </div>
                                
                        <div id="prescriptor">                            
                            <label for="prescriptores" class="block text-sm font-medium text-gray-500 dark:text-light">Comisionista:</label>                    
                            <livewire:search-dropdownpre :key="$randomKey"/>
                        </div>       
                                                
                        <input type="hidden" id="codigoOculto">
                        <input type="hidden" id="nombreOculto">

                    </div>                    
                </form>
            </div>
        </div>
    </div>
    <div class="preloader text-center" style="display: none;" ></div>
    <div style="display: none;" id="tarjeta" class="col-span-12 bg-white rounded-md dark:bg-darker">
        <div class="overflow-x-auto bg-white rounded-lg shadow overflow-y-auto relative"
            style="height: 405px;">
            <div id="informe">
                
            </div>
        </div>        
    </div>
    
</div>
<script>
   function selectCodigoPrescriptor(producto,comi){
        console.log(producto);        
        $('#codigoOculto').val(producto);
        $('#nombreOculto').val(comi);
        $('#prescriptorinput').val(producto+"-"+comi)       
        $(".prescriptorResultado-box").hide();        
    }

    function limpiar(valor){        
            $('#codigoOculto').val('')        
    }

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
           $('#temasComerciales'+id).empty();
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
            'comisionistaOculto': $('#comisionistaOculto'+id+'').val(),
            'accionComercial': $('#accionComercial'+id+'').val(),
            'temaComercial':$('#temaComercial'+id+'').val(),
            'estado': $('#estado'+id+'').val(),
            'prioridad': $('#prioridad'+id+'').val(),
            'objetivo': $('#objetivo'+id+'').val(),
            'codigoCategoriaCliente':'COMI',
            //'resultado': $('#resultado'+id+'').val(),
            "_token": $("meta[name='csrf-token']").attr("content")
        };
        $.ajax({
        url: './seguimiento',
        data: datos,
        type: 'post',
        timeout: 2000,
        async: true,
        success: function(result) {
           //console.log(result);
           
        }
    });
        console.log(datos);
    }
</script>
<script src="{{asset('js/informes4.js')}}"></script>
<?php
    }
?>
@livewireScripts
@include('layouts.footer')
@include('layouts.panels')