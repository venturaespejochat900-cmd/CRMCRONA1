
    
    <?php

use App\Http\Controllers\ClienteController;
use App\Http\Controllers\PrescriptorController;

$cliente= ClienteController::clienteShow($codigoCliente);        

?>


        <div class="container bg-blue-100 p-2">
            <div class="p-2">
                <div class="md:flex mb-6">   
                    <div class="md:w-1/3">                
                        <legend class="text-gray-900 uppercase tracking-wide text-sm">Datos Cliente:</legend>
                    </div>                    
                    <div class="md:flex-1 mt-2 mb:mt-0 md:px-3">                                                                                                     
                        <div class="mb-4" id="datosCliente">

                            <div class="md:flex mb-4">
                                <div class="md:flex-1 md:pr-3">
                                    <label class="text-gray-700 block uppercase tracking-wide text-charcoal-darker text-xs font-bold">Código Cliente</label>
                                    <input type="text" class="form-control text-gray-900 w-full shadow-inner p-4 border-0" name="codigoCliente{{$cliente->CodigoCliente}}" id="codigoCliente{{$cliente->CodigoCliente}}" readonly value="{{$cliente->CodigoCliente}}">
                                </div>
                                <div class="md:flex-1 md:pr-3">
                                    <label class="text-gray-700 block uppercase tracking-wide text-charcoal-darker text-xs font-bold">Razón Social</label>
                                    <input type="text" class="form-control text-gray-900 w-full shadow-inner p-4 border-0" name="razonSocial{{$cliente->CodigoCliente}}" id="razonSocial{{$cliente->CodigoCliente}}" readonly value="{{$cliente->RazonSocial}}" >                        
                                </div>
                            </div>
                            <div class="md:flex mb-4">
                                <div class="md:flex-1 md:pr-3">
                                    <label class="text-gray-700 block uppercase tracking-wide text-charcoal-darker text-xs font-bold">C.I.F. / N.I.F.</label>
                                    <input type="text" class="form-control text-gray-900 w-full shadow-inner p-4 border-0" name="nif{{$cliente->CodigoCliente}}" id="nif{{$cliente->CodigoCliente}}"  value="{{$cliente->CifDni}}" onblur="ValidateSpanishID(this.value), comprobarExisteDNICIFenBBDD(this.value);">
                                    <div id="divErrorCIFDNI" class="alert alert-danger mt-2 " style="display: none"><small>DNI/CIF incorrecto</small></div>                                    
                                </div>
                                <div class="md:flex-1 md:pr-3">
                                    <label class="text-gray-700 block uppercase tracking-wide text-charcoal-darker text-xs font-bold">Código Comisionista</label>
                                    <input type="text" class="form-control text-gray-900 w-full shadow-inner p-4 border-0" name="codigoComisionista{{$cliente->CodigoCliente}}" id="codigoComisionista{{$cliente->CodigoCliente}}" readonly value="{{$cliente->CodigoComisionista}}">
                                </div>
                            </div>
                            
                            <div class="md:flex mb-4">
                                <div class="md:flex-1 md:pr-3">
                                    <label class="text-gray-700 block uppercase tracking-wide text-charcoal-darker text-xs font-bold">Dirección<spam class="text-red-700">*</spam></label>
                                    <input class="text-gray-900 w-full shadow-inner p-4 border-0" type="text" name="direccionA{{$cliente->CodigoCliente}}" id="direccionA{{$cliente->CodigoCliente}}" value="{{$cliente->Domicilio}}" placeholder="">
                                </div>
                                <div class="md:flex-1 md:pl-3">
                                    <label class="text-gray-700 block uppercase tracking-wide text-charcoal-darker text-xs font-bold">Población<spam class="text-red-700">*</spam></label>
                                    <input class="text-gray-900 w-full shadow-inner p-4 border-0" type="text" name="poblacionA{{$cliente->CodigoCliente}}" id="poblacionA{{$cliente->CodigoCliente}}" value="{{$cliente->Municipio}}" placeholder="">                            
                                </div>
                            </div>
                            <div class="md:flex mb-4">
                                <div class="md:flex-1 md:pr-3">
                                    <label class="text-gray-700 block uppercase tracking-wide text-charcoal-darker text-xs font-bold">Provincia<spam class="text-red-700">*</spam></label>                                
                                    <select class="text-gray-900 form-select w-full shadow-inner p-4 border-0 " name="provinciaA{{$cliente->CodigoCliente}}" id="provinciaA{{$cliente->CodigoCliente}}">
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
                                    <input class="text-gray-900 w-full shadow-inner p-4 border-0" type="text" name="codigoPostalA{{$cliente->CodigoCliente}}" id="codigoPostalA{{$cliente->CodigoCliente}}" value="{{$cliente->CodigoPostal}}" placeholder="">
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
                                <input class="text-gray-900 w-full shadow-inner p-4 border-0" type="tel" name="telefonoA{{$cliente->CodigoCliente}}" id="telefonoA{{$cliente->CodigoCliente}}" value="{{$cliente->Telefono}}" placeholder="">
                            </div>
                            <div class="md:flex-1 md:pr-3">
                                <label class="text-gray-700 block uppercase tracking-wide text-xs font-bold">Teléfono2</label>
                                <input class="text-gray-900 w-full shadow-inner p-4 border-0" type="tel" name="telefono2A{{$cliente->CodigoCliente}}" id="telefono2A{{$cliente->CodigoCliente}}" value="{{$cliente->Telefono2}}" placeholder="">
                            </div>                                                     
                        </div>
                        <div class="mb-4">
                            <label class="text-gray-700 block uppercase tracking-wide text-charcoal-darker text-xs font-bold">Email<spam class="text-red-700">*</spam></label>
                            <input class="text-gray-900 w-full shadow-inner p-4 border-0" type="email" name="eMail1A{{$cliente->CodigoCliente}}"  id="eMail1A{{$cliente->CodigoCliente}}" value="{{$cliente->EMail1}}"  placeholder="">
                        </div>
                        <div class="mb-4">
                            <label class="text-gray-700 block uppercase tracking-wide text-charcoal-darker text-xs font-bold">Comentarios<spam class="text-red-700">*</spam></label>
                            <input class="text-gray-900 w-full shadow-inner p-4 border-0" type="text" name="ObservacionesCliente{{$cliente->CodigoCliente}}"  id="ObservacionesCliente{{$cliente->CodigoCliente}}" value="{{$cliente->ObservacionesCliente}}"  placeholder="">
                        </div>          
                    </div>
                </div>  
            </div>
            <div class="p-2">
            <button class="bg-blue-700 hover:bg-blue-700 text-white font-bold py-2 px-4 border border-blue-700 rounded" name="anadirNuevoCliente" id="anadirNuevoCliente" onclick="editarCliente('{{$cliente->IdCliente}}','{{$cliente->CodigoCliente}}')">Guardar</button>
            <button class="bg-blue-700 hover:bg-blue-700 text-white font-bold py-2 px-4 border border-blue-700 rounded" name="convertirCliente" id="ConvertirCliente" onclick="convertirACliente('{{$cliente->IdCliente}}','{{$cliente->CodigoCliente}}')">Convertir a Cliente</button>
            <!-- <button class="bg-red-700 hover:bg-red-700 text-white font-bold py-2 px-4 border border-red-700 rounded" id="limpiarFormulario"><i class="fas fa-times-circle"></i>&nbsp Limpiar formulario</button> -->
            </div>                            
        </div>                


<script>
function editarCliente(id, cod){

    var actualizarCliente = actualizarClienteFormulario(id, cod);
    //console.log(actualizarAutorizacion);
    var parametros = {
        "datos" : actualizarCliente,
        "_token": $("meta[name='csrf-token']").attr("content")
    };
    console.log(parametros);
    $.ajax({
        data: parametros,
        url: './potencial/actualizar',
        type: 'post',
        timeout: 2000,
        async: true,
        success: function (response){
                console.log(response);
                if(response == 'ok'){
                    $("#refrescarTabla").trigger('click');   
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
    datosActualizar.direccion = $("#direccionA"+cod+"").val();
    datosActualizar.poblacion = $("#poblacionA"+cod+"").val();
    datosActualizar.provincia = $("#provinciaA"+cod+"").val();
    datosActualizar.nombreProvincia = $("#provinciaA"+cod+"").find('option:selected').text().trim();
    datosActualizar.codigoPostal = $("#codigoPostalA"+cod+"").val();
    datosActualizar.telefono = $("#telefonoA"+cod+"").val();
    datosActualizar.telefono2 = $("#telefono2A"+cod+"").val();
    datosActualizar.eMail1 = $("#eMail1A"+cod+"").val();
    datosActualizar.ObservacionesCliente = $("#ObservacionesCliente"+cod+"").val();

    //console.log(datosNuevoPrescriptor);
    return datosActualizar ;
}

function convertirACliente(id, cod){

    console.log(id);
    console.log(cod);
    editarCliente(id,cod);

    var parametros = {
        "id" : id,
        "cod": cod,
        "_token": $("meta[name='csrf-token']").attr("content")
    };

    $.ajax({
        data: parametros,
        url: './potencial/convertirCliente',
        type: 'post',
        timeout: 2000,
        async: true,
        success: function (response){
                console.log(response);
                if(response == 'ok'){
                    $("#refrescarTabla").trigger('click');   
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

</script>
<script src="{{asset('js/comprobarDni.js')}}"></script>