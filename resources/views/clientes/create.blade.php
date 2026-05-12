
    
    <?php

        use App\Http\Controllers\ClienteController;
        use App\Http\Controllers\PrescriptorController;

        $cliente = ClienteController::codigoClienteNuevo();
        //$cliente= ClienteController::clienteShow($codigoCliente);        
    
    ?>

    <div>
        <div class="card">
            <div class="card-body bg-blue-100">
                <div class="md:flex mb-6">   
                    <div class="md:w-1/3">                
                        <legend class="text-gray-900 uppercase tracking-wide text-sm">Datos Cliente:</legend>
                    </div>                    
                    <div class="md:flex-1 mt-2 mb:mt-0 md:px-3">                                                                                                     
                        <div class="mb-4" id="datosCliente">

                            <div class="md:flex mb-4">
                                <div class="md:flex-1 md:pr-3">
                                    <label class="text-gray-700 block uppercase tracking-wide text-charcoal-darker text-xs font-bold">Código Cliente</label>
                                    <input type="text" class="form-control text-gray-900 w-full shadow-inner p-4 border-0" name="codigoClienteA" id="codigoClienteA" readonly value="{{$cliente+1+43000000000}}">
                                </div>
                                <div class="md:flex-1 md:pr-3">
                                    <label class="text-gray-700 block uppercase tracking-wide text-charcoal-darker text-xs font-bold">Razón Social</label>
                                    <input type="text" class="form-control text-gray-900 w-full shadow-inner p-4 border-0" name="razonSocialA" id="razonSocialA" value="">                        
                                </div>
                            </div>
                            <div class="md:flex mb-4">
                                <div class="md:flex-1 md:pr-3">
                                    <label class="text-gray-700 block uppercase tracking-wide text-charcoal-darker text-xs font-bold">C.I.F. / N.I.F.</label>
                                    <input type="text" class="form-control text-gray-900 w-full shadow-inner p-4 border-0" name="nifA" id="nifA" value="">                                    
                                </div>
                                <div class="md:flex-1 md:pr-3">
                                    <?php 
                                        if(session('tipo') == 5){
                                    ?>

                                    <label class="text-gray-700 block uppercase tracking-wide text-charcoal-darker text-xs font-bold">Código Comisionista</label>
                                    <livewire:search-dropdowncomi/>
                                    <input type="hidden" id="codigoOculto">
                                    <input type="hidden" id="nombreOculto">

                                    <?php
                                        }else{
                                    ?>

                                    <label class="text-gray-700 block uppercase tracking-wide text-charcoal-darker text-xs font-bold">Código Comisionista</label>
                                    <input type="text" class="form-control text-gray-900 w-full shadow-inner p-4 border-0" name="codigoComisionista" id="codigoComisionista" value="{{session('codigoComisionista')}}-{{session('comisionista')}}">
                                    <input type="hidden" id="codigoOculto" value="{{session('codigoComisionista')}}">
                                    <input type="hidden" id="nombreOculto" value="{{session('comisionista')}}">

                                    <?php
                                        }
                                    ?>                                    
                                </div>
                            </div>
                            
                            <div class="md:flex mb-4">
                                <div class="md:flex-1 md:pr-3">
                                    <label class="text-gray-700 block uppercase tracking-wide text-charcoal-darker text-xs font-bold">Dirección<spam class="text-red-700">*</spam></label>
                                    <input class="text-gray-900 w-full shadow-inner p-4 border-0" type="text" name="direccion" id="direccion" value="" placeholder="">
                                </div>
                                <div class="md:flex-1 md:pl-3">
                                    <label class="text-gray-700 block uppercase tracking-wide text-charcoal-darker text-xs font-bold">Población<spam class="text-red-700">*</spam></label>
                                    <input class="text-gray-900 w-full shadow-inner p-4 border-0" type="text" name="poblacion" id="poblacion" value="" placeholder="">                            
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
                                    <input class="text-gray-900 w-full shadow-inner p-4 border-0" type="text" name="codigoPostal" id="codigoPostal" value="" placeholder="">
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
                                <input class="text-gray-900 w-full shadow-inner p-4 border-0" type="tel" name="telefono" id="telefono" value="" placeholder="">
                            </div>
                            <div class="md:flex-1 md:pr-3">
                                <label class="text-gray-700 block uppercase tracking-wide text-xs font-bold">Teléfono2</label>
                                <input class="text-gray-900 w-full shadow-inner p-4 border-0" type="tel" name="telefono2" id="telefono2" value="" placeholder="">
                            </div>                                                     
                        </div>
                        <div class="mb-4">
                            <label class="text-gray-700 block uppercase tracking-wide text-charcoal-darker text-xs font-bold">Email<spam class="text-red-700">*</spam></label>
                            <input class="text-gray-900 w-full shadow-inner p-4 border-0" type="email" name="eMail1"  id="eMail1" value=""  placeholder="">
                        </div>
                    </div>
                </div>  
                <div class="md:flex mb-6">
                    <div class="md:w-1/3">                
                        <legend class="text-gray-900 uppercase tracking-wide text-sm">Condiciones Pago:</legend>
                    </div> 
                
                        <div class="md:flex-1 mt-2 mb:mt-0 md:px-3">                                                                                                     
                            <div class="md:flex mb-4">
                                <div class="md:flex-1 md:pr-3">
                                    <label class="text-gray-700 block uppercase tracking-wide text-xs font-bold">Nº Plazos<spam class="text-red-700">*</spam></label>
                                    <input class="text-gray-900 w-full shadow-inner p-4 border-0" type="text" name="NumeroPlazosA" id="NumeroPlazosA" value="" placeholder="">                            
                                </div>
                                <div class="md:flex-1 md:pr-3">
                                    <label class="text-gray-700 block uppercase tracking-wide text-xs font-bold">Día 1º Plazo</label>
                                    <input class="text-gray-900 w-full shadow-inner p-4 border-0" type="text" name="DiasPrimerPlazoA" id="DiasPrimerPlazoA" value="" placeholder="">
                                </div>
                            </div>                            
                                
                            <div class="md:flex mb-4">
                                <div class="md:flex-1 md:pr-3">
                                    <label class="text-gray-700 block uppercase tracking-wide text-xs font-bold">Días entre Plazos</label>
                                    <input class="text-gray-900 w-full shadow-inner p-4 border-0" type="text" name="DiasEntrePlazosA" id="DiasEntrePlazosA" value="" placeholder="">
                                </div>                         
                                <div class="md:flex-1 md:pr-3">
                                    <label class="text-gray-700 block uppercase tracking-wide text-charcoal-darker text-xs font-bold">Días Retroceso<spam class="text-red-700">*</spam></label>
                                    <input class="text-gray-900 w-full shadow-inner p-4 border-0" type="text" name="DiasRetrocesoA"  id="DiasRetrocesoA" value=""  placeholder="">                            
                                </div>
                            </div>

                            <div class="md:flex mb-4">
                                <div class="md:flex-1 md:pr-3">
                                    <label class="text-gray-700 block uppercase tracking-wide text-charcoal-darker text-xs font-bold">Cód. T.Efecto<spam class="text-red-700">*</spam></label>
                                    <input class="text-gray-900 w-full shadow-inner p-4 border-0" type="text" name="CodigoTipoEfectoA"  id="CodigoTipoEfectoA" value=""  placeholder="">
                                </div>                         
                                <div class="md:flex-1 md:pr-3">
                                    <label class="text-gray-700 block uppercase tracking-wide text-charcoal-darker text-xs font-bold">Días Fijos 1<spam class="text-red-700">*</spam></label>
                                    <input class="text-gray-900 w-full shadow-inner p-4 border-0" type="text" name="DiasFijos1A"  id="DiasFijos1A" value=""  placeholder="">                            
                                </div>
                                <div class="md:flex-1 md:pr-3">
                                    <label class="text-gray-700 block uppercase tracking-wide text-charcoal-darker text-xs font-bold">Días Fijos 2<spam class="text-red-700">*</spam></label>
                                    <input class="text-gray-900 w-full shadow-inner p-4 border-0" type="text" name="DiasFijos2A"  id="DiasFijos2A" value=""  placeholder="">
                                </div>
                            </div>                         
                            
                            <div class="md:flex mb-4">
                                <div class="md:flex-1 md:pr-3">
                                    <label class="text-gray-700 block uppercase tracking-wide text-charcoal-darker text-xs font-bold">Cod.Banco<spam class="text-red-700">*</spam></label>
                                    <input class="text-gray-900 w-full shadow-inner p-4 border-0" type="text" name="CodigoBancoA"  id="CodigoBancoA" value=""  placeholder="">
                                </div>
                                <div class="md:flex-1 md:pr-3">
                                    <label class="text-gray-700 block uppercase tracking-wide text-charcoal-darker text-xs font-bold">Cod.Agencia<spam class="text-red-700">*</spam></label>
                                    <input class="text-gray-900 w-full shadow-inner p-4 border-0" type="text" name="CodigoAgenciaA"  id="CodigoAgenciaA" value=""  placeholder="">
                                </div>
                                <div class="md:flex-1 md:pr-3">                            
                                    <label class="text-gray-700 block uppercase tracking-wide text-charcoal-darker text-xs font-bold">CD<spam class="text-red-700">*</spam></label>
                                    <input class="text-gray-900 w-full shadow-inner p-4 border-0" type="text" name="DCA"  id="DCA" value=""  placeholder="">
                                </div>
                                <div class="md:flex-1 md:pr-3">
                                    <label class="text-gray-700 block uppercase tracking-wide text-charcoal-darker text-xs font-bold">CCC<spam class="text-red-700">*</spam></label>
                                    <input class="text-gray-900 w-full shadow-inner p-4 border-0" type="text" name="CCCA"  id="CCCA" value=""  placeholder="">
                                </div>
                            </div>
                            
                            <div class="mb-4">
                                <label class="text-gray-700 block uppercase tracking-wide text-charcoal-darker text-xs font-bold">IBAN<spam class="text-red-700">*</spam></label>
                                <input class="text-gray-900 w-full shadow-inner p-4 border-0" type="text" name="iban"  id="iban"  value="" placeholder="">
                            </div>
                            <div class="mb-4">
                                <label class="text-gray-700 block uppercase tracking-wide text-charcoal-darker text-xs font-bold">Comentarios<spam class="text-red-700">*</spam></label>
                                <input class="text-gray-900 w-full shadow-inner p-4 border-0" type="text" name="ObservacionesClienteA"  id="ObservacionesClienteA" value=""  placeholder="">
                            </div>

                            

                        </div>
                    
                </div>  
            </div>                
        </div>    
        
        <div class="card-footer">
                <button class="bg-blue-700 hover:bg-blue-700 text-white font-bold py-2 px-4 border border-blue-700 rounded" name="anadirNuevoCliente" id="anadirNuevoCliente" onclick="nuevoCliente()">Guardar</button>
                <!-- <button class="bg-red-700 hover:bg-red-700 text-white font-bold py-2 px-4 border border-red-700 rounded" id="limpiarFormulario"><i class="fas fa-times-circle"></i>&nbsp Limpiar formulario</button> -->
        </div>
    </div>
    

<script>

    function selectCodigoComisionista(id,comi){
        //console.log(producto);        
        $('#codigoOculto').val(id);
        $('#nombreOculto').val(comi);
        $('#comisionistainput').val(id+"-"+comi)       
        $(".comisionistaResultado-box").hide();        
    }

    function nuevoCliente(){
        var datosActualizar = {};

        datosActualizar.nombre = $('#razonSocialA').val();
        datosActualizar.comisionista = $('#codigoOculto').val();
        datosActualizar.direccion = $("#direccion").val();
        datosActualizar.poblacion = $("#poblacion").val();
        datosActualizar.provincia = $("#provincia").val();
        datosActualizar.nombreProvincia = $("#provincia").find('option:selected').text().trim();
        datosActualizar.codigoPostal = $("#codigoPostal").val();
        datosActualizar.telefono = $("#telefono").val();
        datosActualizar.telefono2 = $("#telefono2").val();
        datosActualizar.eMail1 = $("#eMail1").val();
        datosActualizar.iban = $("#iban").val();
        datosActualizar.NumeroPlazos = $("#NumeroPlazosA").val();
        datosActualizar.DiasPrimerPlazo = $("#DiasPrimerPlazoA").val();
        datosActualizar.DiasEntrePlazos = $("#DiasEntrePlazosA").val();
        datosActualizar.DiasRetroceso = $("#DiasRetrocesoA").val();
        datosActualizar.CodigoTipoEfecto = $("#CodigoTipoEfectoA").val();
        datosActualizar.DiasFijos1 = $("#DiasFijos1A").val();
        datosActualizar.DiasFijos2 = $("#DiasFijos2A").val();
        datosActualizar.CodigoBanco = $("#CodigoBancoA").val();
        datosActualizar.CodigoAgencia = $("#CodigoAgenciaA").val();
        datosActualizar.DC = $("#DCA").val();
        datosActualizar.CCC = $("#CCCA").val();
        datosActualizar.nif = $("#nifA").val();
        datosActualizar.ObservacionesCliente = $("#ObservacionesClienteA").val();

        //var actualizarCliente = actualizarClienteFormulario();
        //console.log(actualizarAutorizacion);

        var parametros = {
            "datos" : datosActualizar,
            "_token": $("meta[name='csrf-token']").attr("content")
        };
        //console.log(datosActualizar);
        $.ajax({
            data: parametros,
            url: './cliente/nuevo',
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


    function actualizarClienteFormulario(){
        //console.log(cod);       
        var datosActualizar = {};

        datosActualizar.comisionista = $('#codigoClienteA').val();

        datosActualizar.comisionista = $('#codigoOculto').val();
        datosActualizar.direccion = $("#direccion").val();
        datosActualizar.poblacion = $("#poblacion").val();
        datosActualizar.provincia = $("#provincia").val();
        datosActualizar.nombreProvincia = $("#provincia").find('option:selected').text().trim();
        datosActualizar.codigoPostal = $("#codigoPostal").val();
        datosActualizar.telefono = $("#telefono").val();
        datosActualizar.telefono2 = $("#telefono2").val();
        datosActualizar.eMail1 = $("#eMail1").val();
        datosActualizar.iban = $("#iban").val();
        datosActualizar.NumeroPlazos = $("#NumeroPlazosA").val();
        datosActualizar.DiasPrimerPlazo = $("#DiasPrimerPlazoA").val();
        datosActualizar.DiasEntrePlazos = $("#DiasEntrePlazosA").val();
        datosActualizar.DiasRetroceso = $("#DiasRetrocesoA").val();
        datosActualizar.CodigoTipoEfecto = $("#CodigoTipoEfectoA").val();
        datosActualizar.DiasFijos1 = $("#DiasFijos1A").val();
        datosActualizar.DiasFijos2 = $("#DiasFijos2A").val();
        datosActualizar.CodigoBanco = $("#CodigoBancoA").val();
        datosActualizar.CodigoAgencia = $("#CodigoAgenciaA").val();
        datosActualizar.DC = $("#DCA").val();
        datosActualizar.CCC = $("#CCCA").val();
        datosActualizar.ObservacionesCliente = $("#ObservacionesClienteA").val();
        
        //console.log(datosActualizar);
        return datosActualizar ;
    }

</script>