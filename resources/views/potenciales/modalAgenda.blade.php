<?php 
    use App\Http\Controllers\ClienteController; 
?>

<div class="px-4 py-5 bg-white sm:p-6 text-gray-700">
        <div class="grid grid-cols-6 gap-6">

            

        <div class="col-span-6 sm:col-span-6">
                <label class="block text-sm font-medium text-gray-700">Fecha Inicio</label>
                <input type="date" name="fechaInicio" id="fechaInicio" value="<?php echo date('Y-m-d'); ?>" min="<?php echo date('Y-m-d'); ?>" class="mt-1 w-full border-gray-500 shadow-md rounded-md">
				<input type='hidden' class="bg-gray-200 appearance-none border-2 border-gray-200 rounded-lg w-full py-2 px-4 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-blue-500" type="text" x-model="event_date" readonly>
            </div>

            <div class="col-span-6 sm:col-span-6">
                <label class="block text-sm font-medium text-gray-700">Hora Inicio</label>
            <input type="time" name="horaInicio" id="horaInicio" min="<?php echo date('H:m'); ?>"  class="mt-1 w-full border-gray-500 shadow-md rounded-md" x-model="event_hour">
            </div>

            <div class="col-span-6 sm:col-span-6">
                <label class="block text-sm font-medium text-gray-700">Fecha Fin</label>
                <input type="date" name="fechaFin" id="fechaFin" value="<?php echo date('Y-m-d'); ?>" min="<?php echo date('Y-m-d'); ?>" class="mt-1 w-full border-gray-500 shadow-md rounded-md">
            </div>

            <div class="col-span-6 sm:col-span-6">
                <label class="block text-sm font-medium text-gray-700">Hora Fin</label>
                <input type="time" name="horaFin" id="horaFin" min="<?php echo date('H:m'); ?>" class="mt-1 w-full border-gray-500 shadow-md rounded-md">
            </div>

            <div class="col-span-6 sm:col-span-3">
                <label for="last-name" class="block text-sm font-medium text-gray-700">Código Acción Comercial</label>
                <select id="accionComercial" name="accionComercial" class="mt-1 block w-full  border border-gray-300 bg-white rounded-md shadow-md " onChange="temaComercialA(this.value)">
                    <option value=""></option>
                    <?php  $accionComercial= ClienteController::accionComercial() ?>
                    @foreach($accionComercial as $accion)
                        <option value="{{$accion->CodigoAccionComercialLc}}">{{$accion->CodigoAccionComercialLc}}-{{$accion->AccionComercialLc}}</option>
                    @endforeach                    
                </select>
            </div>

            <div class="col-span-6 sm:col-span-3" id="temasComerciales"></div>

            <div class="col-span-6 sm:col-span-3">
                <label for="last-name" class="block text-sm font-medium text-gray-700">Estado</label>
                <select @change="event_theme = $event.target.value;" x-model="event_theme" onChange="estado(this.value)" class="mt-1 block w-full  border border-gray-300 bg-white rounded-md shadow-md ">
                    <template x-for="(theme, index) in themes">
                        <option :value="theme.value" x-text="theme.label"></option>
                    </template>
                </select>
                <select id="estado" name="estado" class="mt-1 block w-full  border border-gray-300 bg-white rounded-md shadow-md " style="display: none;">                    
                    <option value="0">Pendiente</option>
                    <option value="1">Iniciada</option>
                    <!-- <option value="2">Detenida</option> -->
                    <option value="3">Cerrada</option>
                </select>
            </div>

            <div class="col-span-6 sm:col-span-3">
                <label for="last-name" class="block text-sm font-medium text-gray-700">Prioridad</label>
                <select id="prioridad" name="prioridad" class="mt-1 block w-full  border border-gray-300 bg-white rounded-md shadow-md ">
                    <option value=""></option>
                    <?php  $prioridadAgenda= ClienteController::prioridad() ?>
                    @foreach($prioridadAgenda as $prioridad)
                        <option value="{{$prioridad->CodigoTipoPrioridadLc}}">{{$prioridad->CodigoTipoPrioridadLc}}</option>
                    @endforeach                     
                </select>
            </div>

            <div class="col-span-6 sm:col-span-6">
                <label class="text-gray-800 block mb-1 font-bold text-sm tracking-wide">Cliente/Prospección</label>
                <input type='hidden' id="tituloAgenda" class="mt-1 w-full border-gray-500 shadow-md rounded-md" type="text" x-model="event_title" >                                
                <input type='hidden' name="comisionistaOculto" id="comisionistaOculto" class="mt-1 w-full border-gray-500 shadow-md rounded-md" value="">
                <input type='hidden' name="codigoCategoriaCliente" id="codigoCategoriaCliente" class="mt-1 w-full border-gray-500 shadow-md rounded-md" value="">
                <livewire:search-dropdownpoten :key="$randomKey"/>
            </div>  

            <div class="col-span-6 sm:col-span-6" >                
                
            </div>         
            

            <div class="col-span-6 sm:col-span-12">
                <div class="box border rounded flex flex-col shadow bg-white">
                    <div class="box__title bg-grey-lighter px-3 py-2 border-b">
                        <h3 class="text-sm text-grey-darker font-medium">Objetivo</h3>
                    </div>
                    <textarea class="text-grey-darkest flex-1 p-2 m-1 bg-transparent" name="objetivo" id="objetivo"></textarea>
                </div>
            </div>
        </div> </br>

    </div>

    <script>
    
    </script>