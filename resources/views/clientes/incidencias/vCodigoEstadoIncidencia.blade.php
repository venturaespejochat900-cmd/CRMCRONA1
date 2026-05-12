

@if($vCodigoEstadoIncidencia == 0)
    <div class="flex space-x-1 justify-around"> 
        Abierta
    </div>
@endif
@if($vCodigoEstadoIncidencia == 1)
    <div class="flex space-x-1 justify-around"> 
        Cerrada
    </div>
@endif
@if($vCodigoEstadoIncidencia == 2)
    <div class="flex space-x-1 justify-around"> 
        Resolviendose
    </div>
@endif
@if($vCodigoEstadoIncidencia == 3)
    <div class="flex space-x-1 justify-around"> 
        Rechazada
    </div>
@endif