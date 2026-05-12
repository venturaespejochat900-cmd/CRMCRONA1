function sleep(ms) {
    return new Promise(resolve => setTimeout(resolve, ms));
}

async function enviarInforme() {
    var fecha = new Date(); //Fecha actual
    var mes = fecha.getMonth()+1; //obteniendo mes
    var dia = fecha.getDate(); //obteniendo dia
    var ano = fecha.getFullYear(); //obteniendo año
    var hora = fecha.getHours();
    var minutos = fecha.getMinutes();    
    if(dia<10)
        dia='0'+dia; //agrega cero si el menor de 10
    if(mes<10)
        mes='0'+mes; //agrega cero si el menor de 10
    if(hora<10)
        hora='0'+hora;
    if(minutos<10)
        minutos='0'+minutos;

    var accionComercial;
    var data = {               
        "_token": $("meta[name='csrf-token']").attr("content")
    };
    $.ajax({
        url: './accionComercial',
        data: data,
        type: 'post',
        timeout: 2000,
        async: true,
        success: function(result) {
           //console.log(result);
           accionComercial = result;
        }
    });

    await sleep(100);

    var prioridad;
    var data = {               
        "_token": $("meta[name='csrf-token']").attr("content")
    };
    $.ajax({
        url: './prioridad',
        data: data,
        type: 'post',
        timeout: 2000,
        async: true,
        success: function(result) {
           //console.log(result);
           prioridad = result;
        }
    });
   
    

    $('#tarjeta').css('display', 'none');
    $('.preloader').css('display', 'block');
    var codigoComisionista = $('#codigoOculto').val();
    var nombreComisionista = $('#nombreOculto').val();
    var dato = $('#dato').val();
    var ejercicio = $('#plazo').val();
    
    var datos = {
        "codigo": codigoComisionista,
        "nombre": nombreComisionista,
        "dato": dato,
        "ejercicio": ejercicio,       
        "_token": $("meta[name='csrf-token']").attr("content")
    };
    console.log(datos);
    $.ajax({
        url: './informes/inforprescriptores',
        data: datos,
        type: 'post',
        timeout: 2000,
        async: true,
        success: function(respuesta) {
            //console.log(respuesta);
            //console.log(accionComercial);
            $('#informe').empty();
            var html = '<table class="border-collapse table-auto w-full whitespace-no-wrap bg-white table-striped relative">' +
                '<thead>' +
                '<tr class="text-center">' +
                '<th class="z-10 bg-gray-100 sticky top-0 border-b border-gray-200 px-6 py-2 text-gray-600 font-bold  uppercase text-xs">' +
                // '#' +
                // '</th>'+
                // '<th class="bg-gray-100 sticky top-0 border-b border-gray-200 px-6 py-2 text-gray-600 font-bold tracking-wider uppercase text-xs">' +
                // 'id' +
                // '</th>'+
                // '<th class="bg-gray-100 sticky top-0 border-b border-gray-200 px-6 py-2 text-gray-600 font-bold tracking-wider uppercase text-xs">' +
                'Comisionista' +
                '</th>'+
                '<th class="bg-gray-100 sticky top-0 border-b border-gray-200 px-6 py-2 text-gray-600 font-bold tracking-wider uppercase text-xs">' +
                'Clientes total' +
                '</th>';
                html +='<th class="bg-gray-100 sticky top-0 border-b border-gray-200 px-6 py-2 text-gray-600 font-bold tracking-wider uppercase text-xs">' +
                    'clientes activos' +
                    '</th>';
                if(dato == 1){
                    html +='<th class="bg-gray-100 sticky top-0 border-b border-gray-200 px-6 py-2 text-gray-600 font-bold tracking-wider uppercase text-xs">' +
                    'clientes activos(periodo)' +
                    '</th>';
                }else if(dato == 2){
                    html +='<th class="bg-gray-100 sticky top-0 border-b border-gray-200 px-6 py-2 text-gray-600 font-bold tracking-wider uppercase text-xs">' +
                    'clientes no activos(periodo)' +
                    '</th>';
                }
                html +='<th class="bg-gray-100 sticky top-0 border-b border-gray-200 px-6 py-2 text-gray-600 font-bold tracking-wider uppercase text-xs">' +
                    'primera compra' +
                    '</th>';
                html +='<th class="bg-gray-100 sticky top-0 border-b border-gray-200 px-6 py-2 text-gray-600 font-bold tracking-wider uppercase text-xs">' +
                    'ultima compra' +
                    '</th>';
                html +='<th class="bg-gray-100 sticky top-0 border-b border-gray-200 px-6 py-2 text-gray-600 font-bold tracking-wider uppercase text-xs">' +
                    'nº pedidos' +
                    '</th>';
                html +='<th class="bg-gray-100 sticky top-0 border-b border-gray-200 px-6 py-2 text-gray-600 font-bold tracking-wider uppercase text-xs">' +
                'suma ventas' +
                '</th>';


            html += '</tr>' +
                '</thead>' +
                '<tbody>';
                        
            for (let i = 0; i < respuesta.length; i++) {                

                    html += '<tr>';
                    html += '<td class="sticky border-dashed border-t border-gray-200" style="z-index:0 !important;">' +                                
                                '<div x-data="{ show: false }">'+
                                    '<div class="flex border-dashed border-t border-gray-200 white-space ml-2">'+
                                        '<button @click={show=true} type="button" class="p-1 text-black-600  hover:bg-black-600 hover:text-white rounded has-tooltip">'+
                                            '<span class="tooltip rounded shadow-lg p-1 bg-black text-white-500 -mt-8">Crear Seguimiento</span>'+
                                            '<svg fill="currentColor" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 30 30" width="25px" height="25px">'+
                                                '<path d="M 5.9667969 3 C 4.8922226 3 4 3.8922226 4 4.9667969 L 4 7.3867188 C 3.6700827 7.5693007 3.3668503 7.8022905 3.1230469 8.1015625 C 2.5561678 8.7967647 2.3299502 9.7121994 2.5097656 10.591797 L 2.5097656 10.59375 C 3.0409792 13.178038 3.7794422 16.772026 3.8261719 17 C 3.7795089 17.227574 3.0439586 20.806083 2.5136719 23.390625 C 2.33239 24.274562 2.5603689 25.193876 3.1289062 25.892578 L 3.1289062 25.894531 C 3.6989449 26.593193 4.5544182 27 5.4570312 27 L 24.501953 27 C 25.414029 27 26.27875 26.588847 26.855469 25.882812 L 26.855469 25.880859 C 27.431397 25.174017 27.661627 24.244791 27.478516 23.351562 C 26.948342 20.768542 26.220293 17.226607 26.173828 17 C 26.220578 16.771923 26.959908 13.172625 27.490234 10.587891 C 27.670205 9.7105358 27.445474 8.7948488 26.878906 8.0996094 L 26.876953 8.0976562 C 26.633016 7.7986233 26.330145 7.566746 26 7.3847656 L 26 4.9667969 C 26 3.8922226 25.107777 3 24.033203 3 L 5.9667969 3 z M 6 5 L 24 5 L 24 7 L 6 7 L 6 5 z M 12.105469 13.080078 C 12.565469 13.080078 12.985281 13.135094 13.363281 13.246094 C 13.742281 13.357094 14.067844 13.522188 14.339844 13.742188 C 14.611844 13.962188 14.823609 14.234594 14.974609 14.558594 C 15.125609 14.882594 15.199219 15.258547 15.199219 15.685547 C 15.199219 15.882547 15.170375 16.076531 15.109375 16.269531 C 15.048375 16.461531 14.958844 16.6425 14.839844 16.8125 C 14.791844 16.8805 14.720063 16.937953 14.664062 17.001953 L 11.066406 17.001953 L 11.066406 18.390625 L 12.03125 18.390625 C 12.26125 18.390625 12.46925 18.41675 12.65625 18.46875 C 12.84225 18.52075 13.000859 18.601937 13.130859 18.710938 C 13.260859 18.819938 13.358734 18.960766 13.427734 19.134766 C 13.496734 19.308766 13.53125 19.515812 13.53125 19.757812 C 13.53125 19.945812 13.5005 20.116531 13.4375 20.269531 C 13.3745 20.422531 13.283062 20.553063 13.164062 20.664062 C 13.045063 20.775063 12.899516 20.861922 12.728516 20.919922 C 12.556516 20.978922 12.365344 21.007813 12.152344 21.007812 C 11.959344 21.007812 11.781187 20.978922 11.617188 20.919922 C 11.454187 20.860922 11.313359 20.780734 11.193359 20.677734 C 11.074359 20.574734 10.981062 20.452547 10.914062 20.310547 C 10.847062 20.168547 10.814453 20.011844 10.814453 19.839844 L 9 19.839844 C 9 20.291844 9.0895781 20.680766 9.2675781 21.009766 C 9.4455781 21.337766 9.6797031 21.610125 9.9707031 21.828125 C 10.261703 22.046125 10.591844 22.2085 10.964844 22.3125 C 11.336844 22.4175 11.718422 22.46875 12.107422 22.46875 C 12.567422 22.46875 12.994625 22.408062 13.390625 22.289062 C 13.785625 22.170063 14.128969 21.997531 14.417969 21.769531 C 14.706969 21.541531 14.932656 21.260781 15.097656 20.925781 C 15.262656 20.590781 15.345703 20.210203 15.345703 19.783203 C 15.345703 19.281203 15.219797 18.846516 14.966797 18.478516 C 14.713797 18.110516 14.329453 17.834391 13.814453 17.650391 C 14.036453 17.550391 14.232297 17.427203 14.404297 17.283203 C 14.507297 17.196203 14.581016 17.096953 14.666016 17.001953 L 19.185547 17.001953 L 19.185547 22.34375 L 21 22.34375 L 21 17 L 19.185547 17 L 19.185547 15.353516 L 17.019531 16.025391 L 17.019531 14.550781 L 20.804688 13.193359 L 21 13.193359 L 21 17 L 24.132812 17 L 24.173828 17.201172 C 24.173828 17.201172 24.970539 21.088714 25.517578 23.753906 C 25.579608 24.059411 25.503358 24.374804 25.306641 24.617188 C 25.107355 24.861152 24.815878 25 24.501953 25 L 5.4570312 25 C 5.1536443 25 4.871649 24.864198 4.6796875 24.628906 C 4.4891549 24.393814 4.4120716 24.088382 4.4726562 23.792969 C 5.0196814 21.126846 5.8261719 17.201172 5.8261719 17.201172 L 5.8671875 17 L 11.064453 17 L 12 17 C 12.353 17 12.833687 16.856953 13.054688 16.626953 C 13.275688 16.396953 13.386719 16.090938 13.386719 15.710938 C 13.386719 15.543938 13.3625 15.387188 13.3125 15.242188 C 13.2625 15.098187 13.184031 14.973094 13.082031 14.871094 C 12.979031 14.769094 12.852266 14.689859 12.697266 14.630859 C 12.542266 14.571859 12.359391 14.542969 12.150391 14.542969 C 11.983391 14.542969 11.826734 14.565281 11.677734 14.613281 C 11.529734 14.661281 11.398109 14.730359 11.287109 14.818359 C 11.176109 14.906359 11.088437 15.013625 11.023438 15.140625 C 10.958438 15.268625 10.927734 15.411313 10.927734 15.570312 L 9.1132812 15.570312 C 9.1132812 15.193312 9.1906562 14.851922 9.3476562 14.544922 C 9.5046563 14.237922 9.7182812 13.976719 9.9882812 13.761719 C 10.258281 13.545719 10.575453 13.378766 10.939453 13.259766 C 11.303453 13.140766 11.691469 13.080078 12.105469 13.080078 z" />'+
                                            '</svg>'+
                                        '</button>' +
                                        '<span class="text-gray-700 px-6 py-3 flex items-center">' + respuesta[i].nombre + '</span>'+
                                    '</div>'+
                                    '<div x-show="show" class="overflow-auto left-0 top-0 bottom-0 right-0 w-full h-full fixed" style="z-index:-1 !important">'+
                                        '<div @click.away="show = false" class="relative p-3 mx-auto my-0 max-w-full" style="width: 1000px;">'+
                                            '<div class="bg-white rounded shadow-lg border flex flex-col overflow-hidden">'+
                                                '<button @click={show=false} class="fill-current h-6 w-6 absolute right-0 top-0 m-6 font-3xl font-bold text-gray-700">&times;</button>'+
                                                '<div class="px-6 py-3 text-xl border-b font-bold text-gray-700">Crear Seguimiento</div>'+
                                                '<div class="p-6 flex-grow">'+
                                                    //'<form action="#" method="POST">'+
                                                        //'<div class="shadow overflow-hidden sm:rounded-md text-gray-700">'+
                                                            '<div class="px-4 py-5 bg-white sm:p-6 text-gray-700">'+
                                                                '<div class="grid grid-cols-6 gap-6">'+
                                                                    '<div class="col-span-6 sm:col-span-6">'+
                                                                        '<label class="block text-sm font-medium text-gray-700">Fecha Inicio</label>'+
                                                                        '<input type="date" name="fechaInicio' + respuesta[i].id  + '" id="fechaInicio' + respuesta[i].id  + '" value="'+ano+'-'+mes+'-'+dia+'" min="'+ano+'-'+mes+'-'+dia+'" class="mt-1 w-full border-gray-500 shadow-md rounded-md">'+
                                                                    '</div>'+
                                
                                                                    '<div class="col-span-6 sm:col-span-6">'+
                                                                        '<label class="block text-sm font-medium text-gray-700">Hora Inicio</label>'+
                                                                        '<input type="time" name="horaInicio' + respuesta[i].id  + '" id="horaInicio' + respuesta[i].id  + '" value="'+hora+':'+minutos+'"  class="mt-1 w-full border-gray-500 shadow-md rounded-md">'+
                                                                    '</div>'+
                                
                                                                    '<div class="col-span-6 sm:col-span-6">'+
                                                                        '<label class="block text-sm font-medium text-gray-700">Fecha Fin</label>'+
                                                                        '<input type="date" name="fechaFin' + respuesta[i].id  + '" id="fechaFin' + respuesta[i].id  + '" value="'+ano+'-'+mes+'-'+dia+'" min="'+ano+'-'+mes+'-'+dia+'" class="mt-1 w-full border-gray-500 shadow-md rounded-md">'+
                                                                    '</div>'+
                                
                                                                    '<div class="col-span-6 sm:col-span-6">'+
                                                                        '<label class="block text-sm font-medium text-gray-700">Hora Fin</label>'+
                                                                        '<input type="time" name="horaFin' + respuesta[i].id  + '" id="horaFin' + respuesta[i].id  + '" value="'+hora+':'+minutos+'"  class="mt-1 w-full border-gray-500 shadow-md rounded-md">'+
                                                                    '</div>'+
                                
                                                                    '<div class="col-span-6 sm:col-span-3">'+
                                                                        '<label class="block text-sm font-medium text-gray-700">Código Comisionista/Cliente</label>'+
                                                                        '<input type="text" name="comisionista' + respuesta[i].id  + '" id="comisionista' + respuesta[i].id  + '" class="mt-1 w-full border-gray-500 shadow-md rounded-md" value="'+respuesta[i].id+'-'+respuesta[i].nombre +'">'+
                                                                        '<input type="hidden" name="comisionistaOculto' + respuesta[i].id  + '" id="comisionistaOculto' + respuesta[i].id  + '" class="mt-1 w-full border-gray-500 shadow-md rounded-md" value="'+respuesta[i].id+'">'+
                                                                    '</div>'+
                                
                                                                    '<div class="col-span-6 sm:col-span-3">'+
                                                                        '<label for="last-name" class="block text-sm font-medium text-gray-700">Código Acción Comercial</label>'+
                                                                        '<select id="accionComercial' + respuesta[i].id  + '" name="accionComercial' + respuesta[i].id  + '" class="mt-1 block w-full  border border-gray-300 bg-white rounded-md shadow-md " onChange="temaComercial(this.value, '+respuesta[i].id+')">'+
                                                                            '<option value=""></option>';
                                                                            for(let j = 0; j<accionComercial.length; j++){ html +='<option value="' +accionComercial[j].CodigoAccionComercialLc+'">'+accionComercial[j].CodigoAccionComercialLc+'-'+accionComercial[j].AccionComercialLc+'</option>';
                                                                                }
                                                                            html +='</select>'+
                                                                    '</div>'+

                                                                    '<div class="col-span-6 sm:col-span-3" id="temasComerciales' + respuesta[i].id  + '">'+
                                                                        
                                                                    '</div>'+
                                
                                                                    '<div class="col-span-6 sm:col-span-3">'+
                                                                        '<label for="last-name" class="block text-sm font-medium text-gray-700">Estado</label>'+
                                                                        '<select id="estado' + respuesta[i].id  + '" name="estado' + respuesta[i].id  + '" class="mt-1 block w-full  border border-gray-300 bg-white rounded-md shadow-md ">'+
                                                                            '<option value=""></option>'+
                                                                            '<option value="0">Pendiente</option>'+
                                                                            '<option value="1">Iniciada</option>'+
                                                                            '<option value="2">Detenida</option>'+
                                                                            '<option value="3">Cerrada</option>'+
                                                                        '</select>'+
                                                                    '</div>'+

                                                                    '<div class="col-span-6 sm:col-span-3">'+
                                                                        '<label for="last-name" class="block text-sm font-medium text-gray-700">Prioridad</label>'+
                                                                        '<select id="prioridad' + respuesta[i].id  + '" name="prioridad' + respuesta[i].id  + '" class="mt-1 block w-full  border border-gray-300 bg-white rounded-md shadow-md ">'+
                                                                            '<option value=""></option>';
                                                                            for(let k = 0; k<prioridad.length; k++){ html +='<option value="' +prioridad[k].CodigoTipoPrioridadLc+'">'+prioridad[k].CodigoTipoPrioridadLc+'</option>';
                                                                                }
                                                                            html +='</select>'+
                                                                    '</div>'+

                                                                    '<div class="col-span-6 sm:col-span-12">'+                                                                                                                                                                                                                       
                                                                        '<div class="box border rounded flex flex-col shadow bg-white">'+
                                                                            '<div class="box__title bg-grey-lighter px-3 py-2 border-b"><h3 class="text-sm text-grey-darker font-medium">Objetivo</h3></div>'+
                                                                                '<textarea class="text-grey-darkest flex-1 p-2 m-1 bg-transparent" name="objetivo' + respuesta[i].id  + '" id="objetivo' + respuesta[i].id  + '"></textarea>'+
                                                                            '</div>'+
                                                                        '</div>'+
                                                                    '</div> </br>'+
                                                                    
                                                                '</div>'+
                                                                '<div class="px-4 py-3 bg-gray-50 text-right sm:px-6">'+
                                                                    '<button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500" onclick="seguimiento(' + respuesta[i].id  + ')">'+
                                                                        'Guardar'+
                                                                    '</button>'+
                                                                    '<button @click={show=false} type="button" class="bg-primary text-gray-100 rounded px-4 py-2 mr-1 ml-2" id="cerrarModal">Cerrar</Button>'+
                                                                '</div>'+
                                                            '</div>'+
                                                        //'</div>'+                                                    
                                                    //'</form>'+                                                    
                                                '</div>'+
                                            '</div>'+                                            
                                        '</div>'+
                                    '</div>'+                                    
                                '</div>'+                                                                                 
                            '</td>';
                    
                    // html += '<td class="border-dashed border-t border-gray-200 white-space fijo">' +
                    //     '<span class="text-gray-700 px-6 py-3 flex items-center">' + respuesta[i].id  + '</span>' +
                    //     '</td>';
                    // html += '<td class="border-dashed border-t border-gray-200 white-space fijo">' +
                    // '<span class="text-gray-700 px-6 py-3 flex items-center">' + respuesta[i].nombre + '</span>' +
                    // '</td>';
                    html += '<td class="border-dashed border-t border-gray-200">' +
                    '<span class="text-gray-700 px-6 py-3 flex items-center">' + respuesta[i].clientesTotal  + '</span>' +
                    '</td>';
                    html += '<td class="border-dashed border-t border-gray-200">' +
                    '<span class="text-gray-700 px-6 py-3 flex items-center">' + respuesta[i].clientesActivos  + '</span>' +
                    '</td>';
                    html += '<td class="border-dashed border-t border-gray-200">' +
                    '<span class="text-gray-700 px-6 py-3 flex items-center">' + respuesta[i].clientesActivosPeriodo  + '</span>' +
                    '</td>';
                    html += '<td class="border-dashed border-t border-gray-200">' +
                    '<span class="text-gray-700 px-6 py-3 flex items-center">' + respuesta[i].primeraCompra + '</span>' +
                    '</td>';
                    html += '<td class="border-dashed border-t border-gray-200">' +
                    '<span class="text-gray-700 px-6 py-3 flex items-center">' + respuesta[i].ultimaCompra  + '</span>' +
                    '</td>';
                    html += '<td class="border-dashed border-t border-gray-200">' +
                    '<span class="text-gray-700 px-6 py-3 flex items-center">' + respuesta[i].numeroDepedidos  + '</span>' +
                    '</td>';                    
                    html += '<td class="border-dashed border-t border-gray-200">' +
                    '<span class="text-gray-700 px-6 py-3 flex items-center">' + respuesta[i].sumaVentas + '</span>' +
                    '</td>'+
                    '</tr>';

            }

            html += '</tbody>' +
                '</table>'+                
            $('#informe').append(html);
            $('#tarjeta').css('display', 'block');
            $('.preloader').css('display', 'none');
            limpiar();

        }
        
    });

    
}