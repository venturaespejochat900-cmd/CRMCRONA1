const options2 = { style: 'currency', currency: 'EUR' };
const numberFormat2 = new Intl.NumberFormat('es-ES', options2);

function agrupacion2(tipoAgrupacion) {
    console.log(tipoAgrupacion);
    if (tipoAgrupacion == 0) {
        $('#prescriptor').css('display', 'none');
        $('#cliente').css('display', 'none');
    }
    if (tipoAgrupacion == 1) {
        $('#prescriptor').css('display', 'block');
        $('#cliente').css('display', 'none');
    }
    if (tipoAgrupacion == 2) {
        $('#prescriptor').css('display', 'none');
        $('#cliente').css('display', 'block');
    }
}
function vacio(){
    if($('#prescriptorinput').val() == '' && $('#clienteinput').val() == ''){
        $('#codigoOculto').val('');
    }
}

function enviarInforme() {
    $('#tarjeta').css('display', 'none');
    $('.preloader').css('display', 'block');
    var codigoComisionista = $('#codigoOculto').val();
    var dato = $('#dato').val();
    var ejercicio = $('#ejercicio').val();
    var agrupacion = $('#agrupacion').val();
    var total = "";
    if (dato == 1) {
        total = "bruto";
    }
    if (dato == 2) {
        total = "base_Imponible";
    }
    if (dato == 3) {
        total = "total";
    }
    var cod = "";
    if (agrupacion == 1) {
        cod = "prescriptor";
        nombre = "Comisionista";
    }
    if (agrupacion == 2) {
        cod = "cliente";
        nombre = "RazonSocial";
    }
    var datos = {
        "codigo": codigoComisionista,
        "dato": dato,
        "ejercicio": ejercicio,
        "agrupacion": agrupacion,
        "_token": $("meta[name='csrf-token']").attr("content")
    };
    console.log(datos);
    $.ajax({
        url: './informes/aniomes',
        data: datos,
        type: 'post',
        timeout: 2000,
        async: true,
        success: function(respuesta) {
            console.log(respuesta);
            $('#informe').empty();
            var html = '<table class="border-collapse table-auto w-full whitespace-no-wrap bg-white table-striped relative">' +
                '<thead>' +
                '<tr class="text-left">' +
                '<th class="bg-gray-100 sticky top-0 border-b border-gray-200 px-6 py-2 text-gray-600 font-bold  uppercase text-xs  fijo" style="z-index:2;">';
                if(agrupacion == 1){
                    html +='Comisionista' +
                    '</th>';
                }else if(agrupacion == 2){
                    html +='Clientes' +
                    '</th>';
                }
            for (m = 1; m <= 12; m++) {
                html += '<th class="bg-gray-100 sticky top-0 border-b border-gray-200 px-6 py-2 text-gray-600 font-bold tracking-wider uppercase text-xs">' +
                    ejercicio + "-" + m +
                    '</th>';
            }
            html += '</tr>' +
                '</thead>' +
                '<tbody>';
            var prescriptor = "";
            var mes = 0;
            for (i = 0; i < respuesta.length; i++) {

                if (respuesta[i][cod] == prescriptor) {
                    var caso = respuesta[i].mes - mes;
                    // console.log(caso);                                                    
                    switch (caso) {
                        case 1:
                            html += '<td class="border-dashed border-t border-gray-200">' +
                                '<span class="text-gray-700 px-6 py-3 flex items-center">' + numberFormat2.format(parseFloat(respuesta[i][total]).toFixed(2))+ '</span>' +
                                '</td>';
                            mes = mes + 1;
                            break;
                        case 2:
                            html += '<td class="border-dashed border-t border-gray-200">' +
                                '</td>' +
                                '<td class="border-dashed border-t border-gray-200">' +
                                '<span class="text-gray-700 px-6 py-3 flex items-center">' + numberFormat2.format(parseFloat(respuesta[i][total]).toFixed(2)) + '</span>' +
                                '</td>';
                            mes = mes + 2;
                            break;
                        case 3:
                            html += '<td class="border-dashed border-t border-gray-200">' +
                                '</td>' +
                                '<td class="border-dashed border-t border-gray-200">' +
                                '</td>' +
                                '<td class="border-dashed border-t border-gray-200">' +
                                '<span class="text-gray-700 px-6 py-3 flex items-center">' + numberFormat2.format(parseFloat(respuesta[i][total]).toFixed(2)) + '</span>' +
                                '</td>';
                            mes = mes + 3;
                            break;
                        case 4:
                            html += '<td class="border-dashed border-t border-gray-200">' +
                                '</td>' +
                                '<td class="border-dashed border-t border-gray-200">' +
                                '</td>' +
                                '<td class="border-dashed border-t border-gray-200">' +
                                '</td>' +
                                '<td class="border-dashed border-t border-gray-200">' +
                                '<span class="text-gray-700 px-6 py-3 flex items-center">' + numberFormat2.format(parseFloat(respuesta[i][total]).toFixed(2)) + '</span>' +
                                '</td>';
                            mes = mes + 4;
                            break;
                        case 5:
                            html += '<td class="border-dashed border-t border-gray-200">' +
                                '</td>' +
                                '<td class="border-dashed border-t border-gray-200">' +
                                '</td>' +
                                '<td class="border-dashed border-t border-gray-200">' +
                                '</td>' +
                                '<td class="border-dashed border-t border-gray-200">' +
                                '</td>' +
                                '<td class="border-dashed border-t border-gray-200">' +
                                '<span class="text-gray-700 px-6 py-3 flex items-center">' + numberFormat2.format(parseFloat(respuesta[i][total]).toFixed(2)) + '</span>' +
                                '</td>';
                            mes = mes + 5;
                            break;
                        case 6:
                            html += '<td class="border-dashed border-t border-gray-200">' +
                                '</td>' +
                                '<td class="border-dashed border-t border-gray-200">' +
                                '</td>' +
                                '<td class="border-dashed border-t border-gray-200">' +
                                '</td>' +
                                '<td class="border-dashed border-t border-gray-200">' +
                                '</td>' +
                                '<td class="border-dashed border-t border-gray-200">' +
                                '</td>' +
                                '<td class="border-dashed border-t border-gray-200">' +
                                '<span class="text-gray-700 px-6 py-3 flex items-center">' + numberFormat2.format(parseFloat(respuesta[i][total]).toFixed(2)) + '</span>' +
                                '</td>';
                            mes = mes + 6;
                            break;
                        case 7:
                            html += '<td class="border-dashed border-t border-gray-200">' +
                                '</td>' +
                                '<td class="border-dashed border-t border-gray-200">' +
                                '</td>' +
                                '<td class="border-dashed border-t border-gray-200">' +
                                '</td>' +
                                '<td class="border-dashed border-t border-gray-200">' +
                                '</td>' +
                                '<td class="border-dashed border-t border-gray-200">' +
                                '</td>' +
                                '<td class="border-dashed border-t border-gray-200">' +
                                '</td>' +
                                '<td class="border-dashed border-t border-gray-200">' +
                                '<span class="text-gray-700 px-6 py-3 flex items-center">' + numberFormat2.format(parseFloat(respuesta[i][total]).toFixed(2)) + '</span>' +
                                '</td>';
                            mes = mes + 7;
                            break;
                        case 8:
                            html += '<td class="border-dashed border-t border-gray-200">' +
                                '</td>' +
                                '<td class="border-dashed border-t border-gray-200">' +
                                '</td>' +
                                '<td class="border-dashed border-t border-gray-200">' +
                                '</td>' +
                                '<td class="border-dashed border-t border-gray-200">' +
                                '</td>' +
                                '<td class="border-dashed border-t border-gray-200">' +
                                '</td>' +
                                '<td class="border-dashed border-t border-gray-200">' +
                                '</td>' +
                                '<td class="border-dashed border-t border-gray-200">' +
                                '</td>' +
                                '<td class="border-dashed border-t border-gray-200">' +
                                '<span class="text-gray-700 px-6 py-3 flex items-center">' + numberFormat2.format(parseFloat(respuesta[i][total]).toFixed(2)) + '</span>' +
                                '</td>';
                            mes = mes + 8;
                            break;
                        case 9:
                            html += '<td class="border-dashed border-t border-gray-200">' +
                                '</td>' +
                                '<td class="border-dashed border-t border-gray-200">' +
                                '</td>' +
                                '<td class="border-dashed border-t border-gray-200">' +
                                '</td>' +
                                '<td class="border-dashed border-t border-gray-200">' +
                                '</td>' +
                                '<td class="border-dashed border-t border-gray-200">' +
                                '</td>' +
                                '<td class="border-dashed border-t border-gray-200">' +
                                '</td>' +
                                '<td class="border-dashed border-t border-gray-200">' +
                                '</td>' +
                                '<td class="border-dashed border-t border-gray-200">' +
                                '</td>' +
                                '<td class="border-dashed border-t border-gray-200">' +
                                '<span class="text-gray-700 px-6 py-3 flex items-center">' + numberFormat2.format(parseFloat(respuesta[i][total]).toFixed(2)) + '</span>' +
                                '</td>';
                            mes = mes + 9;
                            break;
                        case 10:
                            html += '<td class="border-dashed border-t border-gray-200">' +
                                '</td>' +
                                '<td class="border-dashed border-t border-gray-200">' +
                                '</td>' +
                                '<td class="border-dashed border-t border-gray-200">' +
                                '</td>' +
                                '<td class="border-dashed border-t border-gray-200">' +
                                '</td>' +
                                '<td class="border-dashed border-t border-gray-200">' +
                                '</td>' +
                                '<td class="border-dashed border-t border-gray-200">' +
                                '</td>' +
                                '<td class="border-dashed border-t border-gray-200">' +
                                '</td>' +
                                '<td class="border-dashed border-t border-gray-200">' +
                                '</td>' +
                                '<td class="border-dashed border-t border-gray-200">' +
                                '</td>' +
                                '<td class="border-dashed border-t border-gray-200">' +
                                '<span class="text-gray-700 px-6 py-3 flex items-center">' + numberFormat2.format(parseFloat(respuesta[i][total]).toFixed(2)) + '</span>' +
                                '</td>';
                            mes = mes + 10;
                            break;
                        case 11:
                            html += '<td class="border-dashed border-t border-gray-200">' +
                                '</td>' +
                                '<td class="border-dashed border-t border-gray-200">' +
                                '</td>' +
                                '<td class="border-dashed border-t border-gray-200">' +
                                '</td>' +
                                '<td class="border-dashed border-t border-gray-200">' +
                                '</td>' +
                                '<td class="border-dashed border-t border-gray-200">' +
                                '</td>' +
                                '<td class="border-dashed border-t border-gray-200">' +
                                '</td>' +
                                '<td class="border-dashed border-t border-gray-200">' +
                                '</td>' +
                                '<td class="border-dashed border-t border-gray-200">' +
                                '</td>' +
                                '<td class="border-dashed border-t border-gray-200">' +
                                '</td>' +
                                '<td class="border-dashed border-t border-gray-200">' +
                                '</td>' +
                                '<td class="border-dashed border-t border-gray-200">' +
                                '<span class="text-gray-700 px-6 py-3 flex items-center">' + numberFormat2.format(parseFloat(respuesta[i][total]).toFixed(2)) + '</span>' +
                                '</td>';
                            mes = mes + 11;
                            break;
                    }
                } else {
                    prescriptor = respuesta[i][cod];

                    html += '<tr>';
                    html += '<td class="border-dashed border-t border-gray-200 white-space fijo">' +
                        '<span class="text-gray-700 px-6 py-3 flex items-center">' + respuesta[i][nombre] + '</span>' +
                        '</td>';
                    if (respuesta[i].mes == 1) {
                        html += '<td class="border-dashed border-t border-gray-200">' +
                            '<span class="text-gray-700 px-6 py-3 flex items-center">' + numberFormat2.format(parseFloat(respuesta[i][total]).toFixed(2)) + '</span>' +
                            '</td>';
                        mes = 1;
                    }
                    if (respuesta[i].mes == 2) {
                        html += '<td class="border-dashed border-t border-gray-200">' +
                            '</td>' +
                            '<td class="border-dashed border-t border-gray-200">' +
                            '<span class="text-gray-700 px-6 py-3 flex items-center">' + numberFormat2.format(parseFloat(respuesta[i][total]).toFixed(2)) + '</span>' +
                            '</td>';
                        mes = 2;
                    }
                    if (respuesta[i].mes == 3) {
                        html += '<td class="border-dashed border-t border-gray-200">' +
                            '</td>' +
                            '<td class="border-dashed border-t border-gray-200">' +
                            '</td>' +
                            '<td class="border-dashed border-t border-gray-200">' +
                            '<span class="text-gray-700 px-6 py-3 flex items-center">' + numberFormat2.format(parseFloat(respuesta[i][total]).toFixed(2)) + '</span>' +
                            '</td>';
                        mes = 3;
                    }
                    if (respuesta[i].mes == 4) {
                        html += '<td class="border-dashed border-t border-gray-200">' +
                            '</td>' +
                            '<td class="border-dashed border-t border-gray-200">' +
                            '</td>' +
                            '<td class="border-dashed border-t border-gray-200">' +
                            '</td>' +
                            '<td class="border-dashed border-t border-gray-200">' +
                            '<span class="text-gray-700 px-6 py-3 flex items-center">' + numberFormat2.format(parseFloat(respuesta[i][total]).toFixed(2)) + '</span>' +
                            '</td>';
                        mes = 4;
                    }
                    if (respuesta[i].mes == 5) {
                        html += '<td class="border-dashed border-t border-gray-200">' +
                            '</td>' +
                            '<td class="border-dashed border-t border-gray-200">' +
                            '</td>' +
                            '<td class="border-dashed border-t border-gray-200">' +
                            '</td>' +
                            '<td class="border-dashed border-t border-gray-200">' +
                            '</td>' +
                            '<td class="border-dashed border-t border-gray-200">' +
                            '<span class="text-gray-700 px-6 py-3 flex items-center">' + numberFormat2.format(parseFloat(respuesta[i][total]).toFixed(2)) + '</span>' +
                            '</td>';
                        mes = 5;
                    }
                    if (respuesta[i].mes == 6) {
                        html += '<td class="border-dashed border-t border-gray-200">' +
                            '</td>' +
                            '<td class="border-dashed border-t border-gray-200">' +
                            '</td>' +
                            '<td class="border-dashed border-t border-gray-200">' +
                            '</td>' +
                            '<td class="border-dashed border-t border-gray-200">' +
                            '</td>' +
                            '<td class="border-dashed border-t border-gray-200">' +
                            '</td>' +
                            '<td class="border-dashed border-t border-gray-200">' +
                            '<span class="text-gray-700 px-6 py-3 flex items-center">' + numberFormat2.format(parseFloat(respuesta[i][total]).toFixed(2)) + '</span>' +
                            '</td>';
                        mes = 6;
                    }
                    if (respuesta[i].mes == 7) {
                        html += '<td class="border-dashed border-t border-gray-200">' +
                            '</td>' +
                            '<td class="border-dashed border-t border-gray-200">' +
                            '</td>' +
                            '<td class="border-dashed border-t border-gray-200">' +
                            '</td>' +
                            '<td class="border-dashed border-t border-gray-200">' +
                            '</td>' +
                            '<td class="border-dashed border-t border-gray-200">' +
                            '</td>' +
                            '<td class="border-dashed border-t border-gray-200">' +
                            '</td>' +
                            '<td class="border-dashed border-t border-gray-200">' +
                            '<span class="text-gray-700 px-6 py-3 flex items-center">' + numberFormat2.format(parseFloat(respuesta[i][total]).toFixed(2)) + '</span>' +
                            '</td>';
                        mes = 7;
                    }
                    if (respuesta[i].mes == 8) {
                        html += '<td class="border-dashed border-t border-gray-200">' +
                            '</td>' +
                            '<td class="border-dashed border-t border-gray-200">' +
                            '</td>' +
                            '<td class="border-dashed border-t border-gray-200">' +
                            '</td>' +
                            '<td class="border-dashed border-t border-gray-200">' +
                            '</td>' +
                            '<td class="border-dashed border-t border-gray-200">' +
                            '</td>' +
                            '<td class="border-dashed border-t border-gray-200">' +
                            '</td>' +
                            '<td class="border-dashed border-t border-gray-200">' +
                            '</td>' +
                            '<td class="border-dashed border-t border-gray-200">' +
                            '<span class="text-gray-700 px-6 py-3 flex items-center">' + numberFormat2.format(parseFloat(respuesta[i][total]).toFixed(2)) + '</span>' +
                            '</td>';
                        mes = 8;
                    }
                    if (respuesta[i].mes == 9) {
                        html += '<td class="border-dashed border-t border-gray-200">' +
                            '</td>' +
                            '<td class="border-dashed border-t border-gray-200">' +
                            '</td>' +
                            '<td class="border-dashed border-t border-gray-200">' +
                            '</td>' +
                            '<td class="border-dashed border-t border-gray-200">' +
                            '</td>' +
                            '<td class="border-dashed border-t border-gray-200">' +
                            '</td>' +
                            '<td class="border-dashed border-t border-gray-200">' +
                            '</td>' +
                            '<td class="border-dashed border-t border-gray-200">' +
                            '</td>' +
                            '<td class="border-dashed border-t border-gray-200">' +
                            '</td>' +
                            '<td class="border-dashed border-t border-gray-200">' +
                            '<span class="text-gray-700 px-6 py-3 flex items-center">' + numberFormat2.format(parseFloat(respuesta[i][total]).toFixed(2)) + '</span>' +
                            '</td>';
                        mes = 9;
                    }
                    if (respuesta[i].mes == 10) {
                        html += '<td class="border-dashed border-t border-gray-200">' +
                            '</td>' +
                            '<td class="border-dashed border-t border-gray-200">' +
                            '</td>' +
                            '<td class="border-dashed border-t border-gray-200">' +
                            '</td>' +
                            '<td class="border-dashed border-t border-gray-200">' +
                            '</td>' +
                            '<td class="border-dashed border-t border-gray-200">' +
                            '</td>' +
                            '<td class="border-dashed border-t border-gray-200">' +
                            '</td>' +
                            '<td class="border-dashed border-t border-gray-200">' +
                            '</td>' +
                            '<td class="border-dashed border-t border-gray-200">' +
                            '</td>' +
                            '<td class="border-dashed border-t border-gray-200">' +
                            '</td>' +
                            '<td class="border-dashed border-t border-gray-200">' +
                            '<span class="text-gray-700 px-6 py-3 flex items-center">' + numberFormat2.format(parseFloat(respuesta[i][total]).toFixed(2)) + '</span>' +
                            '</td>';
                        mes = 10;
                    }
                    if (respuesta[i].mes == 11) {
                        html += '<td class="border-dashed border-t border-gray-200">' +
                            '</td>' +
                            '<td class="border-dashed border-t border-gray-200">' +
                            '</td>' +
                            '<td class="border-dashed border-t border-gray-200">' +
                            '</td>' +
                            '<td class="border-dashed border-t border-gray-200">' +
                            '</td>' +
                            '<td class="border-dashed border-t border-gray-200">' +
                            '</td>' +
                            '<td class="border-dashed border-t border-gray-200">' +
                            '</td>' +
                            '<td class="border-dashed border-t border-gray-200">' +
                            '</td>' +
                            '<td class="border-dashed border-t border-gray-200">' +
                            '</td>' +
                            '<td class="border-dashed border-t border-gray-200">' +
                            '</td>' +
                            '<td class="border-dashed border-t border-gray-200">' +
                            '</td>' +
                            '<td class="border-dashed border-t border-gray-200">' +
                            '<span class="text-gray-700 px-6 py-3 flex items-center">' + numberFormat2.format(parseFloat(respuesta[i][total]).toFixed(2)) + '</span>' +
                            '</td>';
                        mes = 11;
                    }
                    if (respuesta[i].mes == 12) {
                        html += '<td class="border-dashed border-t border-gray-200">' +
                            '</td>' +
                            '<td class="border-dashed border-t border-gray-200">' +
                            '</td>' +
                            '<td class="border-dashed border-t border-gray-200">' +
                            '</td>' +
                            '<td class="border-dashed border-t border-gray-200">' +
                            '</td>' +
                            '<td class="border-dashed border-t border-gray-200">' +
                            '</td>' +
                            '<td class="border-dashed border-t border-gray-200">' +
                            '</td>' +
                            '<td class="border-dashed border-t border-gray-200">' +
                            '</td>' +
                            '<td class="border-dashed border-t border-gray-200">' +
                            '</td>' +
                            '<td class="border-dashed border-t border-gray-200">' +
                            '</td>' +
                            '<td class="border-dashed border-t border-gray-200">' +
                            '</td>' +
                            '<td class="border-dashed border-t border-gray-200">' +
                            '</td>' +
                            '<td class="border-dashed border-t border-gray-200">' +
                            '<span class="text-gray-700 px-6 py-3 flex items-center">' + numberFormat2.format(parseFloat(respuesta[i][total]).toFixed(2)) + '</span>' +
                            '</td>';
                        mes = 12;
                    }
                }

            }

            html += '</tbody>' +
                '</table>';
            $('#informe').append(html);
            $('#tarjeta').css('display', 'block');
            $('.preloader').css('display', 'none');

        }
    });
}