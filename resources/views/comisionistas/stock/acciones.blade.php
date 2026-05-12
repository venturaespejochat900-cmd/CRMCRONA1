<style>    
.pop-up {
    margin-left: 1%;
    max-height: 350px;
    background-color: rgba(48, 48, 48, 0.1);
    overflow-y: scroll;
    cursor: pointer;    
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
</style>
 
<div class="flex space-x-1 justify-around">


    <div x-data="{ show: false }">
        <div class="flex justify-center">
            <button @click={show=true} type="button" class="p-1 text-yellow-600 hover:bg-yellow-600 hover:text-white rounded has-tooltip" id="{{$IdArticulo}}" onclick="fichaTecnica(this.id)">
                <span class='tooltip rounded shadow-lg p-1 bg-black text-white-500 -mt-8'>Ficha Técnica</span>
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M10 12a2 2 0 100-4 2 2 0 000 4z"></path><path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"></path></svg>                
            </button>
        </div>
        <div x-show="show" tabindex="0" class="z-40 overflow-auto left-0 top-0 bottom-0 right-0 h-full fixed">
            <div  @click.away="show = false" class="z-50 relative p-3 mx-auto my-0 max-w-full" style="width: 1000px;">
                <div class="bg-white rounded shadow-lg border flex flex-col overflow-hidden">
                    <button @click={show=false} class="fill-current h-6 w-6 absolute right-0 top-0 m-6 font-3xl font-bold">&times;</button>                    
                    <div class="px-6 py-3 text-xl border-b font-bold">
                        Detalles del producto 
                        <button class="items-center space-x-2 px-3 ml-5 border border-blue-400 rounded-md bg-white text-blue-500 text leading-4 font-medium uppercase tracking-wider hover:bg-blue-200 focus:outline-none" id="{{$IdArticulo}}/enviar" onclick="enviarEmailProducto(this.id)"><i class="space-x-2 py-3 fas fa-paper-plane"></i></button></div>
                    <div class="p-6 flex-grow">
                        <div id="fichaTecnica{{$IdArticulo}}" class="grid grid-cols-1 gap-8 p-4">
                            
                            

                        </div>                  
                    </div>
                    <div class="px-6 py-3 border-t">
                        <div class="flex justify-end">
                            <button @click={show=false} type="button" class="bg-primary text-gray-100 rounded px-4 py-2 mr-1">Cerrar</Button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="z-40 overflow-auto left-0 top-0 bottom-0 right-0 w-full h-full absolute bg-black opacity-50"></div>
        </div>
    </div>
</div>

<script>     

    function fichaTecnica(id){        

        var parametros = {
            "IdArticulo": id,
            "_token": $("meta[name='csrf-token']").attr("content")
        }

        $.ajax({
            data: parametros,
            url: '/fichaTecnica',
            type: 'post',
            timoout: 2000,
            async: true,
            success: function(response){
                console.log(response[0].IdArticulo);
                var precio = parseFloat(response[0].PrecioVenta);

                var html ='<div class="flex inline-block justify-between p-4" id="'+response[0].IdArticulo+'ficha" >'+
                                '<div>'+
                                    '<div class="h-60 w-60 rounded-lg bg-gray-100 mb-4">'+
                                        '<div class="h-60 rounded-lg bg-gray-100 mb-4 flex items-center justify-center">'+
                                            //'<span class="text-5xl">1</span>'+
                                            "<img alt='105x105' class='img-responsive' src='http://cronadis.abmscloud.com/imagen_mostrar.php?ImagenExt="+response[0].ImagenExt+"'/>"+
                                        '</div>'+
                                    '</div>'+                        
                                '</div>'+
                                '<div>'+
                                    '<div class="p-3 max-w-2xl">'+
                                        '<h2 class="mb-2 leading-tight tracking-tight font-bold text-gray-800 text-xl">'+response[0].DescripcionArticulo+'</h2>'+
                                        '<p class="text-gray-500 text-sm">'+response[0].MarcaProducto+'</p>'+

                                        '<div class="space-x-2 my-4">'+
                                            '<div>'+
                                                '<div class="rounded-lg bg-gray-100 py-2 px-3">'+                                                    
                                                    '<span class="font-bold text-indigo-600 text-3xl">'+precio.toFixed(2)+'</span>'+
                                                    '<span class="text-indigo-400 text-2xl mr-1 mt-1">€</span>'+
                                                '</div>'+
                                            '</div>'+                                
                                        '</div>'+

                                        '<label class="block">'+
                                            '<span class="text-gray-600">Garantía</span>'+
                                            '<textarea class="form-textarea mt-1 block w-full" readonly rows="auto">'+                                            
                                            response[0].MesesGarantiaVenta+
                                            '</textarea>'+
                                        '</label>'+   
                                        '<label class="block">'+
                                            '<span class="text-gray-600">Descripión</span>'+
                                            '<textarea class="form-textarea mt-1 block w-full" readonly rows="auto">'+                                            
                                            response[0].DescripcionLinea+
                                            '</textarea>'+
                                        '</label>'+                                                                                                    
                                                                            
                                    '</div>'+                                      
                                '</div>'+ 
                                '<div></div>'+
                                '<input hidden id="'+response[0].IdArticulo+'descripcion" value ="'+response[0].DescripcionArticulo+'">'+
                                '<input hidden id="'+response[0].IdArticulo+'imagen" value ="'+response[0].ImagenExt+'">'+
                                '<input hidden id="'+response[0].IdArticulo+'marca" value ="'+response[0].MarcaProducto+'">'+
                                '<input hidden id="'+response[0].IdArticulo+'precio" value ="'+precio.toFixed(2)+'">'+
                                '<input hidden id="'+response[0].IdArticulo+'garantia" value ="'+response[0].MesesGarantiaVenta+'">'+
                                '<input hidden id="'+response[0].IdArticulo+'descripcionlinea" value ="'+response[0].DescripcionLinea+'">'+
                            '</div>';

                $('#fichaTecnica'+id+'').html(html);

            }
           
        })        
        //var html;

        //$('#fichaTecnica'+id+'').html('hola '+ id);
    }

    function enviarEmailProducto(id){
        
        var divcontenido = id.split('/');
        //console.log($('#'+divcontenido[0]+'ficha').html());
        Swal.fire({
            title: 'Enviar Ficha Técnica!',            
            text: '¿facilite un correo para enviar la ficha?',
            input: 'email',
            inputPlaceholder: 'Insertar Correo Electrónico',
            //showCancelButton: true,
            confirmButtonText: 'Enviar Correo',
            confirmButtonColor: '#3085d6'
        }).then(function(value) {
            console.log(value);
            if (value['isConfirmed'] == true) {
                
                let correo = value['value'];
                var descripcion = $('#'+divcontenido[0]+'descripcion').val();
                var imagen = $('#'+divcontenido[0]+'imagen').val();
                var marca = $('#'+divcontenido[0]+'marca').val();
                var precio = $('#'+divcontenido[0]+'precio').val();
                var garantia = $('#'+divcontenido[0]+'garantia').val();
                var descripcionlinea = $('#'+divcontenido[0]+'descripcionlinea').val();

                var parametros = {
                    "descripcion": descripcion,
                    "imagen": imagen,
                    "marca": marca,
                    "precio": precio,
                    "garantia": garantia,
                    "descripcionlinea": descripcionlinea,
                    "correos": correo,
                    "_token": $("meta[name='csrf-token']").attr("content")
                };
                console.log(parametros)

                $.ajax({
                    data: parametros,
                    url: '/correoArticulosFicha',
                    type: 'post',
                    timeout: 4000,
                    async: true,
                    success: function(response) {

                        Swal.fire({
                            title: 'Enviado!',
                            text: 'Correo enviado a ' + value['value'],
                            icon: 'success',
                            confirmButtonText: 'Cerrar'
                        })

                    },
                    error: function(XMLHttpRequest, textStatus, errorThrown) {

                        console.log(XMLHttpRequest, textStatus, errorThrown)
                        Swal.fire({
                            title: 'Correo enviado puede ser que tarde un poco!',
                            text: 'Correo enviado a ' + value['value'],
                            icon: 'success',
                            confirmButtonText: 'Cerrar'
                        })
                    }
                });
            }
        })
                                   
    }

</script>