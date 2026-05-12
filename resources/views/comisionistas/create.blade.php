
<div class="card">
    <div class="card-header ">
        <h4 class="text-dark">Datos del prescriptor</h4>
    </div>

    <div class="card-body bg-blue-100">
        <?php
            use App\Http\Controllers\PrescriptorController;

            //$codigoComisionista = PrescriptorController::obtenerCodigoComisionita();
            $accesoUsuario = PrescriptorController::obtenerCodigoAccesoUsuario();
            $guid= PrescriptorController::obtenerGuid();
        ?>

        <input type="hidden" name="codigoComisionista" id="codigoComisionista" value="{{$accesoUsuario[0]->cod + 1}}">
        <input type="hidden" name="accesoUsuario" id="accesoUsuario" value="{{$accesoUsuario[0]->cod + 1}}">
        <input type="hidden" name="guid" id="guid" value="{{$guid}}">


        <div class="container-fluid" style="height: 700px; overflow-y: scroll">

            
                <div class="md:flex mb-8">
                    <div class="md:w-1/3">
                        <legend class="text-gray-900 uppercase tracking-wide text-sm">Tipo</legend>
                    </div>
                    <div class="md:flex-1 mt-2 mb:mt-0 md:px-3">                        
                        <div class="mb-4">
                            <label class="text-gray-700 block uppercase tracking-wide text-charcoal-darker text-xs font-bold">Pais<spam class="text-red-700">*</spam></label>                            
                            <select class="form-select w-full shadow-inner p-4 border-0 text-gray-900 " name="paisPrescriptor" id="paisPrescriptor">
                                <?php
                                $naciones = PrescriptorController::naciones();
                                ?>
                                    <option value="0">
                                        Selecciona
                                    </option>
                                @foreach($naciones as $pais)
                                    <option value="{{$pais->CodigoNacion}}">
                                        {{$pais->Nacion}}
                                    </option>
                                @endforeach
                            </select>
                        </div>                        
                    </div>
                </div>
                <div class="md:flex mb-8">
                    <div class="md:w-1/3">
                        <legend class="text-gray-900 uppercase tracking-wide text-sm">General</legend>
                        <p class="text-xs font-light text-red-400">Campos equeridos.</p>
                    </div>
                    <div class="md:flex-1 mt-2 mb:mt-0 md:px-3">
                        <div class="md:flex mb-4">                            
                            <div class="md:flex-1 md:pl-3">
                                <label class="text-gray-700 block uppercase tracking-wide text-charcoal-darker text-xs font-bold">Nif/Cif<spam class="text-red-700">*</spam></label>
                                <input class="text-gray-900 w-full shadow-inner p-4 border-0" type="text" name="nifCif" id="nifCif" placeholder="" >                               
                            </div>                                                      
                        </div>                        
                        <div class="mb-4">
                            <label class="text-gray-700 block uppercase tracking-wide text-xs font-bold">
                                Nombre Completo<spam class="text-red-700">*</spam></label>
                            <input class="text-gray-900 w-full shadow-inner p-4 border-0" type="text" name="comisionista" id="comisionista" placeholder="">
                        </div>
                        <div class="md:flex mb-4">
                            <div class="md:flex-1 md:pr-3">
                                <label class="text-gray-700 block uppercase tracking-wide text-charcoal-darker text-xs font-bold">Dirección<spam class="text-red-700">*</spam></label>
                                <input class="text-gray-900 w-full shadow-inner p-4 border-0" type="text" name="direccion" id="direccion" placeholder="">
                            </div>
                            <div class="md:flex-1 md:pl-3">
                                <label class="text-gray-700 block uppercase tracking-wide text-charcoal-darker text-xs font-bold">Población<spam class="text-red-700">*</spam></label>
                                <input class="text-gray-900 w-full shadow-inner p-4 border-0" type="text" name="poblacion" id="poblacion" placeholder="">                            
                            </div>
                        </div>
                        <div class="md:flex mb-4">
                            <div class="md:flex-1 md:pr-3">
                                <label class="text-gray-700 block uppercase tracking-wide text-charcoal-darker text-xs font-bold">Provincia<spam class="text-red-700">*</spam></label>                                
                                <select class="text-gray-900 form-select w-full shadow-inner p-4 border-0 " name="provincia" id="provincia">
                                        <?php                                        
                                        $provincias = PrescriptorController::provincias();
                                        ?>
                                            <option value="0">
                                                Selecciona
                                            </option>
                                        @foreach($provincias as $provincia)
                                            <option value="{{$provincia->CodigoProvincia}}">
                                               {{$provincia->Provincia}}
                                            </option>
                                        @endforeach
                                    </select>
                            </div>
                            <div class="md:flex-1 md:pl-3">
                                <label class="text-gray-700 block uppercase tracking-wide text-charcoal-darker text-xs font-bold">CP<spam class="text-red-700">*</spam></label>
                                <input class="text-gray-900 w-full shadow-inner p-4 border-0" type="text" name="codigoPostal" id="codigoPostal" placeholder="">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="md:flex mb-8">
                    <div class="md:w-1/3">
                        <legend class="text-gray-900 uppercase tracking-wide text-sm">Contacto</legend>
                    </div>
                    <div class="md:flex-1 mt-2 mb:mt-0 md:px-3">
                        <div class="mb-4">
                            <label class="text-gray-700 block uppercase tracking-wide text-xs font-bold">Teléfono<spam class="text-red-700">*</spam></label>
                            <input class="text-gray-900 w-full shadow-inner p-4 border-0" type="tel" name="telefono" id="telefono" placeholder="">
                        </div>
                        <div class="mb-4">
                            <label class="text-gray-700 block uppercase tracking-wide text-xs font-bold">Teléfono2</label>
                            <input class="text-gray-900 w-full shadow-inner p-4 border-0" type="tel" name="telefono2" id="telefono2" placeholder="">
                        </div>                         
                        <div class="mb-4">
                            <label class="text-gray-700 block uppercase tracking-wide text-charcoal-darker text-xs font-bold">Email<spam class="text-red-700">*</spam></label>
                            <input class="text-gray-900 w-full shadow-inner p-4 border-0" type="email" name="eMail1"  id="eMail1" placeholder="">
                        </div>
                    </div>
                </div>
                <div class="md:flex mb-8">
                    <div class="md:w-1/3">
                        <legend class="text-gray-900 uppercase tracking-wide text-sm">Banca</legend>
                    </div>
                    <div class="md:flex-1 mt-2 mb:mt-0 md:px-3">
                        <div class="md:flex mb-4">
                            <div class="md:flex-1 md:pr-3">
                                <label class="text-gray-700 block uppercase tracking-wide text-charcoal-darker text-xs font-bold">Comisión<spam class="text-red-700">*</spam></label>                                
                                <input class="text-gray-900 w-full shadow-inner p-4 border-0" type="text" name="comision" id="comision" placeholder="">
                            </div>                        
                        </div>                        
                    </div>
                </div>
                <div class="md:flex mb-6">
                    <div class="md:w-1/3">
                        <legend class="text-gray-900 uppercase tracking-wide text-sm">Observaciones</legend>
                    </div>
                    <div class="md:flex-1 mt-2 mb:mt-0 md:px-3">
                        <textarea class="text-gray-900 w-full shadow-inner p-4 border-0" placeholder="Observaciones" rows="3" name="observaciones" id="observaciones"></textarea>
                    </div>
                </div>
                <div class="md:flex">
                    <div class="md:w-1/3">
                        <legend class="text-gray-900 uppercase tracking-wide text-sm">Comercial</legend>
                    </div>
                    <div class="md:flex-1 mt-2 mb:mt-0 md:px-3">
                        <div class="md:flex mb-4">
                            <div class="md:flex-1 md:pr-3">
                                <label class="text-gray-700 block uppercase tracking-wide text-charcoal-darker text-xs font-bold">Comercial</label>
                                <div class="w-full flex">                                    
                                    <input class="text-gray-900 flex-1 shadow-inner p-4 border-0" type="text" name="comercialPrescriptor" id="comercialPrescriptor" value="<?php echo session('codigoComisionista'); ?>" readonly>
                                </div>
                            </div>
                            <div class="md:flex-1 md:pl-3 mt-2 md:mt-0">
                                <label class="text-gray-700 block uppercase tracking-wide text-charcoal-darker text-xs font-bold">Creado</label>
                                <div class="w-full flex">                                    
                                    <input class="text-red-700 flex-1 shadow-inner p-4 border-0" type="text" name="creacion"  id="creacion" value="<?php echo date("d-m-Y H:i:s");?>" readonly>
                                </div>
                            </div>
                            <div class="md:flex-1 md:pl-3 mt-2 md:mt-0">
                                <label class="text-gray-700 block uppercase tracking-wide text-charcoal-darker text-xs font-bold">Fecha Baja</label>
                                <div class="w-full flex">                                    
                                    <input class="text-gray-900  flex-1 shadow-inner p-4 border-0" type="date" name="fechaBaja"  id="fechaBaja" placeholder="">
                                </div>
                            </div>
                        </div>
                        
                    </div>
                </div>                
        </div>    
    </div>
    <div class="card-footer">
            <button class="bg-blue-700 hover:bg-blue-700 text-white font-bold py-2 px-4 border border-blue-700 rounded" name="anadirNuevoPrescriptor" id="anadirNuevoPrescriptor" onclick="nuevoPrescriptor()">Grabar datos</button>
            <!-- <button class="bg-red-700 hover:bg-red-700 text-white font-bold py-2 px-4 border border-red-700 rounded" id="limpiarFormulario"><i class="fas fa-times-circle"></i>&nbsp Limpiar formulario</button> -->
    </div>
</div>


<script type="text/javascript">


function nuevoPrescriptor(){

    var datosPrescriptor = obtenerDatosPrescriptorFormularioC();
        console.log(datosPrescriptor);
        var parametros = {
            "datos" : datosPrescriptor,
            "_token": $("meta[name='csrf-token']").attr("content")
        };
        console.log(parametros);
        $.ajax({
            data: parametros,
            url: './comisionistas/insertarNuevoPrescriptores',
            type: 'post',
            timeout: 2000,
            async: true,
            success: function (response){
                if(response == "OK"){
                    console.log('realizado correctamente');
                    var parametros2 = {
                        "_token": $("meta[name='csrf-token']").attr("content")
                    };
                    window.location.reload();                               
                }else{
                    Swal.fire({
                        title: 'Error!',
                        text: response,
                        icon: 'error',
                        confirmButtonText: 'Cerrar'
                    })
                    limpiarFormulario();
                }
            }
        })
}

function limpiarFormulario(){
    $('#limpiarFormulario').click(function() {
        $('input[type="text"]').val('');
        $(".historico").html('');
    });
}


function obtenerDatosPrescriptorFormularioC(){

    var accesoUsuario = "D"+ 00000 + $('#accesoUsuario').val();
    var dni;
    console.log($('#nifCif').val())
    if($('#nifCif').val()==''){dni = 0}else{dni = $('#nifCif').val()};



    var datosNuevoPrescriptor = {};

    datosNuevoPrescriptor.CodigoComisionista = $('#codigoComisionista').val();
    datosNuevoPrescriptor.AccesoUsuario = accesoUsuario;
    datosNuevoPrescriptor.Nacion = $("#paisPrescriptor").val();
    datosNuevoPrescriptor.nombrePais = $("#paisPrescriptor").find('option:selected').text().trim();

    datosNuevoPrescriptor.CifDni = dni;
    datosNuevoPrescriptor.Comisionista = $("#comisionista").val();
    datosNuevoPrescriptor.Direccion = $("#direccion").val();
    datosNuevoPrescriptor.Municipio = $("#poblacion").val();
    datosNuevoPrescriptor.Provincia = $("#provincia").val();
    datosNuevoPrescriptor.nombreProvincia =$("#provincia").find('option:selected').text().trim();
    datosNuevoPrescriptor.codigoPostal = $("#codigoPostal").val();
    
    datosNuevoPrescriptor.Telefono = $("#telefono").val();
    datosNuevoPrescriptor.Telefono2 = $("#telefono2").val();
    datosNuevoPrescriptor.EMail1 = $("#eMail1").val();
    
    datosNuevoPrescriptor.VComisionista = $('#comercialPrescriptor').val();    

    datosNuevoPrescriptor.Comision= $("#comision").val();  
    datosNuevoPrescriptor.IBAN= $("#IBAN").val();
    datosNuevoPrescriptor.Observaciones = $("#observaciones").val(); 
    datosNuevoPrescriptor.FechaBajaLc = $('#fechaBaja').val();    
    datosNuevoPrescriptor.IdComisionista = $('#guid').val();

    console.log(datosNuevoPrescriptor);
    
    return datosNuevoPrescriptor;

}


</script>
