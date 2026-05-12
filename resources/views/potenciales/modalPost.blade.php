<?php 
    use App\Http\Controllers\ClienteController; 
?>

<div class="px-4 py-5 bg-white sm:p-6 text-gray-700">
        <div class="grid grid-cols-6 gap-6">

            

            <div class="col-span-6 sm:col-span-6">
                <label class="text-gray-800 block mb-1 font-bold text-sm tracking-wide">Comisionista/Cliente/Potencial</label>                                                
                <input name="codigoModal" id="codigoModal" class="mt-1 w-full border-gray-500 shadow-md rounded-md" value="" readonly>                
            </div>

            <div class="col-span-6 sm:col-span-6" >                
                
            </div>

            <div class="col-span-6 sm:col-span-6">
                <label class="block text-sm font-medium text-gray-700">Fecha Inicio</label>
                <input type="date" name="fechaModal" id="fechaModal" value="<?php echo date('Y-m-d'); ?>" min="<?php echo date('Y-m-d'); ?>" class="mt-1 w-full border-gray-500 shadow-md rounded-md" readonly>				
            </div>

            <div class="col-span-6 sm:col-span-6">
                <label class="block text-sm font-medium text-gray-700">Hora Inicio</label>
                <input type="time" name="horaModal" id="horaModal" value="<?php echo date('H:m'); ?>" class="mt-1 w-full border-gray-500 shadow-md rounded-md">
            </div>            

            <div class="col-span-6 sm:col-span-3">
                <label for="last-name" class="block text-sm font-medium text-gray-700">Código Acción Comercial</label>
                <select id="accionComercialPost" name="accionComercialPost" class="mt-1 block w-full  border border-gray-300 bg-white rounded-md shadow-md " disabled>
                    <option value=""></option>
                    <?php  $accionComercial= ClienteController::accionComercial() ?>
                    @foreach($accionComercial as $accion)
                        <option value="{{$accion->CodigoAccionComercialLc}}">{{$accion->CodigoAccionComercialLc}}-{{$accion->AccionComercialLc}}</option>
                    @endforeach                    
                </select>
            </div>

            <div class="col-span-6 sm:col-span-3">
                <label for="last-name" class="block text-sm font-medium text-gray-700">Tema Comercial</label>
                <input name="temasComercialesPost" id="temasComercialesPost" class="mt-1 w-full border-gray-500 shadow-md rounded-md" value="" readonly>
            </div>

            <div class="col-span-6 sm:col-span-3">
                <label for="last-name" class="block text-sm font-medium text-gray-700">Estado</label>
                <select @change="event_theme = $event.target.value;" x-model="event_theme" onChange="estadoPost(this.value)" id="themePost" name="themePost" class="mt-1 block w-full  border border-gray-300 bg-white rounded-md shadow-md ">
                    <template x-for="(theme, index) in themes">
                        <option :value="theme.value" x-text="theme.label"></option>
                    </template>
                </select>
                <select id="estadoPost" name="estadoPost" class="mt-1 block w-full  border border-gray-300 bg-white rounded-md shadow-md " style="display: none;">                    
                    <option value="0">Pendiente</option>
                    <option value="1">Iniciada</option>
                    <!-- <option value="2">Detenida</option> -->
                    <option value="3">Cerrada</option>
                </select>
            </div>

            <div class="col-span-6 sm:col-span-3">
                <label for="last-name" class="block text-sm font-medium text-gray-700">Prioridad</label>
                <select id="prioridadPost" name="prioridadPost" class="mt-1 block w-full  border border-gray-300 bg-white rounded-md shadow-md " disabled>
                    <option value=""></option>
                    <?php  $prioridadAgenda= ClienteController::prioridad() ?>
                    @foreach($prioridadAgenda as $prioridad)
                        <option value="{{$prioridad->CodigoTipoPrioridadLc}}">{{$prioridad->CodigoTipoPrioridadLc}}</option>
                    @endforeach                     
                </select>
            </div>

            <div class="col-span-6 sm:col-span-12">
                <div class="box border rounded flex flex-col shadow bg-white">
                    <div class="box__title bg-grey-lighter px-3 py-2 border-b">
                        <h3 class="text-sm text-grey-darker font-medium">Objetivo</h3>
                    </div>
                    <textarea class="text-grey-darkest flex-1 p-2 m-1 bg-transparent" name="objetivoPost" id="objetivoPost" readonly></textarea>
                </div>
            </div>
            <div class="col-span-6 sm:col-span-12">
                <div class="box border rounded flex flex-col shadow bg-white">
                    <div class="box__title bg-grey-lighter px-3 py-2 border-b">
                        <h3 class="text-sm text-grey-darker font-medium">Resultado</h3>
                    </div>
                    <textarea class="text-grey-darkest flex-1 p-2 m-1 bg-transparent" name="resultadoPost" id="resultadoPost"></textarea>
                </div>
            </div>
            <div>
                <input type="hidden" value="" id="codigoClientePost">
                <input type="hidden" value="" id="codigoClienteCategoriaPost">
                <input type="hidden" value="" id="accionPosicionLcPost">
                <input type="hidden" value="" id="idDelegacionPost">
                <input type="hidden" value="" id="CodigoGrupoComercialPost">
                <input type="hidden" value="" id="estadoBandera">
                

            </div>
        </div> </br>

    </div>

    <script>
    
    </script>