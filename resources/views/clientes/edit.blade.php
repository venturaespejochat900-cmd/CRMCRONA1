
    
    <?php

use App\Http\Controllers\ClienteController;
use App\Http\Controllers\PrescriptorController;

$cliente= ClienteController::clienteShow($CodigoCliente);        

?>


<div class="bg-blue-100 container">
    <div class="p-2">
        <div class="md:flex mb-6">   
            <div class="md:w-1/3">                
                <legend class="text-gray-900 uppercase tracking-wide text-sm">Datos Cliente:</legend>
                    <input type="hidden" name="latitud" id="latitud" value="{{$cliente->VLatitud}}">
                    <input type="hidden" name="longitud" id="longitud" value="{{$cliente->VLongitud}}">
            </div>                    
            <div class="md:flex-1 mt-2 mb:mt-0 md:px-3">                                                                                                     
                <div class="mb-4" id="datosCliente">

                    <div class="md:flex mb-4">
                        <div class="md:flex-1 md:pr-3">
                            <label class="text-gray-700 block uppercase tracking-wide text-charcoal-darker text-xs font-bold">Código Cliente</label>
                            <input type="text" class="form-control text-gray-900 w-full shadow-inner p-4 border-0" name="codigoCliente" id="codigoCliente" readonly value="{{$cliente->CodigoCliente}}">
                        </div>
                        <div class="md:flex-1 md:pr-3">
                            <label class="text-gray-700 block uppercase tracking-wide text-charcoal-darker text-xs font-bold">Razón Social</label>
                            <input type="text" class="form-control text-gray-900 w-full shadow-inner p-4 border-0" name="razonSocial" id="razonSocial" readonly value="{{$cliente->RazonSocial}}">                        
                        </div>
                    </div>
                    <div class="md:flex mb-4">
                        <div class="md:flex-1 md:pr-3">
                            <label class="text-gray-700 block uppercase tracking-wide text-charcoal-darker text-xs font-bold">C.I.F. / N.I.F.</label>
                            <input type="text" class="form-control text-gray-900 w-full shadow-inner p-4 border-0" name="nif" id="nif" readonly value="{{$cliente->CifDni}}">                                    
                        </div>
                        <div class="md:flex-1 md:pr-3">
                            <label class="text-gray-700 block uppercase tracking-wide text-charcoal-darker text-xs font-bold">Código Comisionista</label>
                            <input type="text" class="form-control text-gray-900 w-full shadow-inner p-4 border-0" name="codigoComisionista" id="codigoComisionista" readonly value="{{$cliente->CodigoComisionista}}">
                        </div>
                    </div>
                    
                    <div class="md:flex mb-4">
                        <div class="md:flex-1 md:pr-3">
                            <label class="text-gray-700 block uppercase tracking-wide text-charcoal-darker text-xs font-bold">Dirección<spam class="text-red-700">*</spam></label>
                            <input class="text-gray-900 w-full shadow-inner p-4 border-0" type="text" name="direccionA" id="direccionA" value="{{$cliente->Domicilio}}" readonly placeholder="">
                        </div>
                        <div class="md:flex-1 md:pl-3">
                            <label class="text-gray-700 block uppercase tracking-wide text-charcoal-darker text-xs font-bold">Población<spam class="text-red-700">*</spam></label>
                            <input class="text-gray-900 w-full shadow-inner p-4 border-0" type="text" name="poblacionA" id="poblacionA" value="{{$cliente->Municipio}}" readonly placeholder="">                            
                        </div>
                    </div>
                    <div class="md:flex mb-4">
                        <div class="md:flex-1 md:pr-3">
                            <label class="text-gray-700 block uppercase tracking-wide text-charcoal-darker text-xs font-bold">Provincia<spam class="text-red-700">*</spam></label>                                
                            <select class="text-gray-900 form-select w-full shadow-inner p-4 border-0 " name="provinciaA" id="provinciaA" disabled>
                                    <?php                                        
                                    $provincias = PrescriptorController::provincias();
                                    ?>
                                        <option value="0">
                                            Selecciona
                                        </option>
                                    @foreach($provincias as $provincia)
                                        <option value="{{$provincia->CodigoProvincia}}" <?php if($cliente->CodigoProvincia == $provincia->CodigoProvincia) echo 'selected="selected"'?>>
                                        {{$provincia->Provincia}}
                                        </option>
                                    @endforeach
                                </select>
                        </div>
                        <div class="md:flex-1 md:pl-3">
                            <label class="text-gray-700 block uppercase tracking-wide text-charcoal-darker text-xs font-bold">CP<spam class="text-red-700">*</spam></label>
                            <input class="text-gray-900 w-full shadow-inner p-4 border-0" type="text" name="codigoPostalA" id="codigoPostalA" value="{{$cliente->CodigoPostal}}"  readonly placeholder="">
                        </div>
                    </div>
                    <div class="md:flex mb-4">
                        <div class="md:flex-1 md:pr-3">
                            <label class="text-gray-700 block uppercase tracking-wide text-charcoal-darker text-xs font-bold">Sector Cliente<spam class="text-red-700">*</spam></label>                                
                            <select class="text-gray-900 form-select w-full shadow-inner p-4 border-0 " name="sectorA" id="sectorA" readonly>
                                    <?php                                        
                                    $sectores = ClienteController::sectorCliente();
                                    ?>
                                        <option value="0">
                                            Selecciona
                                        </option>
                                    @foreach($sectores as $sector)
                                        <option value="{{$sector->CodigoSector_}}" <?php if($cliente->CodigoSector_ == $sector->CodigoSector_) echo 'selected="selected"'?>>
                                        {{$sector->DescripcionSector_}}
                                        </option>
                                    @endforeach
                                </select>
                        </div>                                
                    </div>
                    <div class="md:flex mb-4">
                        <div class="md:flex-1 md:pr-3">
                            <label class="text-gray-700 block uppercase tracking-wide text-charcoal-darker text-xs font-bold">Forma de pago<spam class="text-red-700">*</spam></label>                                
                            <select class="text-gray-900 form-select w-full shadow-inner p-4 border-0 " name="fPagoA" id="fPagoA" readonly>
                                    <?php                                        
                                    $sectores = ClienteController::formasPago();
                                    ?>
                                        <option value="0">
                                            Selecciona
                                        </option>
                                    @foreach($sectores as $sector)
                                        <option value="{{$sector->CodigoCondiciones}}" <?php if($cliente->CodigoCondiciones == $sector->CodigoCondiciones) echo 'selected="selected"'?>>
                                        {{$sector->Condiciones}}
                                        </option>
                                    @endforeach
                                </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="md:flex mb-6">
            <div class="md:w-1/3">                
                <legend class="text-gray-900 uppercase tracking-wide text-sm">Contacto:</legend>
            </div>                    
            <div class="md:flex-1 mt-2 mb:mt-0 md:px-3">                                                                                                     
                <div class="md:flex mb-4">
                    <div class="md:flex-1 md:pr-3">
                        <label class="text-gray-700 block uppercase tracking-wide text-xs font-bold">Teléfono<spam class="text-red-700">*</spam></label>
                        <input class="text-gray-900 w-full shadow-inner p-4 border-0" type="tel" name="telefonoA" id="telefonoA" value="{{$cliente->Telefono}}" placeholder="">
                    </div>
                    <div class="md:flex-1 md:pr-3">
                        <label class="text-gray-700 block uppercase tracking-wide text-xs font-bold">Teléfono2</label>
                        <input class="text-gray-900 w-full shadow-inner p-4 border-0" type="tel" name="telefono2A" id="telefono2A" value="{{$cliente->Telefono2}}" placeholder="">
                    </div>                                                     
                </div>
                <div class="mb-4">
                    <label class="text-gray-700 block uppercase tracking-wide text-charcoal-darker text-xs font-bold">Email<spam class="text-red-700">*</spam></label>
                    <input class="text-gray-900 w-full shadow-inner p-4 border-0" type="email" name="eMail1A"  id="eMail1A" value="{{$cliente->EMail1}}"  placeholder="">
                </div>
            </div>
        </div>  
        <div class="md:flex mb-6">
            <?php 
                $conta = ClienteController::clienteConta($cliente->CodigoCliente); 
            ?> 
            
            @if(count($conta) > 0)
                <div class="md:w-1/3">                
                    <legend class="text-gray-900 uppercase tracking-wide text-sm">Condiciones Pago:</legend>
                </div> 

                @foreach($conta as $contas) 
                    <div class="md:flex-1 mt-2 mb:mt-0 md:px-3">                                                                                                     
                        <div class="md:flex mb-4">
                            <div class="md:flex-1 md:pr-3">
                                <label class="text-gray-700 block uppercase tracking-wide text-xs font-bold">Nº Plazos<spam class="text-red-700">*</spam></label>
                                <input class="text-gray-900 w-full shadow-inner p-4 border-0" type="text" name="NumeroPlazos" id="NumeroPlazos" value="{{$contas->NumeroPlazos}}" readonly placeholder="">                            
                            </div>
                            <div class="md:flex-1 md:pr-3">
                                <label class="text-gray-700 block uppercase tracking-wide text-xs font-bold">Día 1º Plazo</label>
                                <input class="text-gray-900 w-full shadow-inner p-4 border-0" type="text" name="DiasPrimerPlazo" id="DiasPrimerPlazo" value="{{$contas->DiasPrimerPlazo}}" readonly placeholder="">
                            </div>
                        </div>                            
                            
                        <div class="md:flex mb-4">
                            <div class="md:flex-1 md:pr-3">
                                <label class="text-gray-700 block uppercase tracking-wide text-xs font-bold">Días entre Plazos</label>
                                <input class="text-gray-900 w-full shadow-inner p-4 border-0" type="text" name="DiasEntrePlazos" id="DiasEntrePlazos" value="{{$contas->DiasEntrePlazos}}" readonly placeholder="">
                            </div>                         
                            <div class="md:flex-1 md:pr-3">
                                <label class="text-gray-700 block uppercase tracking-wide text-charcoal-darker text-xs font-bold">Días Retroceso<spam class="text-red-700">*</spam></label>
                                <input class="text-gray-900 w-full shadow-inner p-4 border-0" type="text" name="DiasRetroceso"  id="DiasRetroceso" value="{{$contas->DiasRetroceso}}" readonly placeholder="">                            
                            </div>
                        </div>

                        <div class="md:flex mb-4">
                            <div class="md:flex-1 md:pr-3">
                                <label class="text-gray-700 block uppercase tracking-wide text-charcoal-darker text-xs font-bold">Cód. T.Efecto<spam class="text-red-700">*</spam></label>
                                <input class="text-gray-900 w-full shadow-inner p-4 border-0" type="text" name="CodigoTipoEfecto"  id="CodigoTipoEfecto" value="{{$contas->CodigoTipoEfecto}}" readonly  placeholder="">
                            </div>                         
                            <div class="md:flex-1 md:pr-3">
                                <label class="text-gray-700 block uppercase tracking-wide text-charcoal-darker text-xs font-bold">Días Fijos 1<spam class="text-red-700">*</spam></label>
                                <input class="text-gray-900 w-full shadow-inner p-4 border-0" type="text" name="DiasFijos1"  id="DiasFijos1" value="{{$contas->DiasFijos1}}" readonly  placeholder="">                            
                            </div>
                            <div class="md:flex-1 md:pr-3">
                                <label class="text-gray-700 block uppercase tracking-wide text-charcoal-darker text-xs font-bold">Días Fijos 2<spam class="text-red-700">*</spam></label>
                                <input class="text-gray-900 w-full shadow-inner p-4 border-0" type="text" name="DiasFijos2"  id="DiasFijos2" value="{{$contas->DiasFijos2}}" readonly  placeholder="">
                            </div>
                        </div>                         
                        
                        <div class="md:flex mb-4">
                            <div class="md:flex-1 md:pr-3">
                                <label class="text-gray-700 block uppercase tracking-wide text-charcoal-darker text-xs font-bold">Cod.Banco<spam class="text-red-700">*</spam></label>
                                <input class="text-gray-900 w-full shadow-inner p-4 border-0" type="text" name="CodigoBanco"  id="CodigoBanco" value="{{$cliente->CodigoBanco}}" readonly  placeholder="">
                            </div>
                            <div class="md:flex-1 md:pr-3">
                                <label class="text-gray-700 block uppercase tracking-wide text-charcoal-darker text-xs font-bold">Cod.Agencia<spam class="text-red-700">*</spam></label>
                                <input class="text-gray-900 w-full shadow-inner p-4 border-0" type="text" name="CodigoAgencia"  id="CodigoAgencia" value="{{$cliente->CodigoAgencia}}"readonly  placeholder="">
                            </div>
                            <div class="md:flex-1 md:pr-3">                            
                                <label class="text-gray-700 block uppercase tracking-wide text-charcoal-darker text-xs font-bold">CD<spam class="text-red-700">*</spam></label>
                                <input class="text-gray-900 w-full shadow-inner p-4 border-0" type="text" name="DC"  id="DC" value="{{$cliente->DC}}" readonly placeholder="">
                            </div>
                            <div class="md:flex-1 md:pr-3">
                                <label class="text-gray-700 block uppercase tracking-wide text-charcoal-darker text-xs font-bold">CCC<spam class="text-red-700">*</spam></label>
                                <input class="text-gray-900 w-full shadow-inner p-4 border-0" type="text" name="CCC"  id="CCC" value="{{$cliente->CCC}}" readonly placeholder="">
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <label class="text-gray-700 block uppercase tracking-wide text-charcoal-darker text-xs font-bold">IBAN<spam class="text-red-700">*</spam></label>
                            <input class="text-gray-900 w-full shadow-inner p-4 border-0" type="text" name="ibanA"  id="ibanA"  value="{{$cliente->IBAN}}"readonly placeholder="">
                        </div>
                        {{-- <div class="mb-4">
                            <label class="text-gray-700 block uppercase tracking-wide text-charcoal-darker text-xs font-bold">Comentarios<spam class="text-red-700">*</spam></label>
                            <input class="text-gray-900 w-full shadow-inner p-4 border-0" type="text" name="ObservacionesCliente"  id="ObservacionesCliente" value="{{$cliente->ObservacionesCliente}}"  placeholder="">
                        </div> --}}

                        

                    </div>
                @endforeach
            @endif

        </div>
        <div class="md:flex mb-6">
            <div class="md:w-1/3">                
                <legend class="text-gray-900 uppercase tracking-wide text-sm">Observaciones:</legend>
            </div> 

            <div class="md:flex-1 mt-2 mb:mt-0 md:px-3">                                                                                                     
                <div class="mb-4">
                    <label class="text-gray-700 block uppercase tracking-wide text-charcoal-darker text-xs font-bold">Observaciones Clientes</label>
                    <input class="text-gray-900 w-full shadow-inner p-4 border-0" type="text" name="ObservacionesCliente"  id="ObservacionesCliente" value="{{$cliente->ObservacionesCliente}}"  placeholder="">
                </div>
            </div>
        </div> 
    </div>
    <div class="p-2">
    <button class="bg-blue-700 hover:bg-blue-700 text-white font-bold py-2 px-4 border border-blue-700 rounded" name="anadirNuevoCliente" id="anadirNuevoCliente" onclick="editarCliente('{{$cliente->IdCliente}}','{{$cliente->CodigoCliente}}')">Guardar</button>
        <!-- <button class="bg-red-700 hover:bg-red-700 text-white font-bold py-2 px-4 border border-red-700 rounded" id="limpiarFormulario"><i class="fas fa-times-circle"></i>&nbsp Limpiar formulario</button> -->
    </div>
</div>
                    



<script>
function editarCliente(id, cod){

var actualizarCliente = actualizarClienteFormulario(id, cod);
console.log(actualizarCliente);
var parametros = {
    "datos" : actualizarCliente,
    "_token": $("meta[name='csrf-token']").attr("content")
};
console.log(parametros);
$.ajax({
    data: parametros,
    url: 'actualizar',
    type: 'post',
    timeout: 2000,
    async: true,
    success: function (response){
            console.log(response);
            if(response == 'ok'){
                //$("#refrescarTabla").trigger('click');   
                Swal.fire({
                    title: 'Correcto!',
                    text: response,
                    icon: 'success',
                    confirmButtonText: 'Cerrar'
                })
            }else{
                Swal.fire({
                    title: 'Error!',
                    text: response,
                    icon: 'error',
                    confirmButtonText: 'Cerrar'
                })
            }
            //$("#refrescarTabla").trigger('click');
            //window.location.reload();                
    }
})
}


function actualizarClienteFormulario(id, cod){

//console.log(cod);       
var datosActualizar = {};

datosActualizar.IdCliente = id;
datosActualizar.CodigoCliente = cod;
datosActualizar.direccion = $("#direccionA").val();
datosActualizar.poblacion = $("#poblacionA").val();
datosActualizar.provincia = $("#provinciaA").val();
datosActualizar.sector = $("#sectorA").val();
datosActualizar.fPago = $("#fPagoA").val();
datosActualizar.nombreProvincia = $("#provinciaA").find('option:selected').text().trim();
datosActualizar.codigoPostal = $("#codigoPostalA").val();
datosActualizar.telefono = $("#telefonoA").val();
datosActualizar.telefono2 = $("#telefono2A").val();
datosActualizar.eMail1 = $("#eMail1A").val();
datosActualizar.VLatitud = $("#latitud").val();
datosActualizar.VLongitud = $("#longitud").val();

datosActualizar.iban = $("#ibanA").val();
datosActualizar.NumeroPlazos = $("#NumeroPlazos").val();
datosActualizar.DiasPrimerPlazo = $("#DiasPrimerPlazo").val();
datosActualizar.DiasEntrePlazos = $("#DiasEntrePlazos").val();
datosActualizar.DiasRetroceso = $("#DiasRetroceso").val();
datosActualizar.CodigoTipoEfecto = $("#CodigoTipoEfecto").val();
datosActualizar.DiasFijos1 = $("#DiasFijos1").val();
datosActualizar.DiasFijos2 = $("#DiasFijos2").val();
datosActualizar.CodigoBanco = $("#CodigoBanco").val();
datosActualizar.CodigoAgencia = $("#CodigoAgencia").val();
datosActualizar.DC = $("#DC").val();
datosActualizar.CCC = $("#CCC").val();
datosActualizar.ObservacionesCliente = $("#ObservacionesCliente").val();
datosActualizar.VObservacionesCliente = $("#VObservacionesCliente").val();

console.log(datosActualizar);
return datosActualizar ;
}

</script>