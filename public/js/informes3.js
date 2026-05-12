
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

function notArticulo() {
    if ($('#familia').val() != 0) {
        $('#articulos').css('display', 'none');
        $('#articulo').val("");
    } else {
        $('#articulos').css('display', 'block');
        $('#articulo').val("");
    }
}

function notFamilia() {
    if ($('#articuloinput').val() != '') {
        $('#familias').css('display', 'none');
        $('#familia').val(0);
    } else {
        $('#familias').css('display', 'block');
        $('#familia').val(0);
        $("#productoResultado-box").hide();
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
    //var dato = $('#dato').val();
    var fechaInicio = $('#fechaInicio').val();
    var fechaFin = $('#fechaFin').val();
    var agrupacion = $('#agrupacion').val();
    var forma = $('#forma').val();
    var familia = $('#familia').val();
    var articulo = $('#articuloOculto').val();
    var total = "";
    var cod = "";
    if (agrupacion == 1) {
        cod = "CodigoComisionista";
    }
    if (agrupacion == 2) {
        cod = "CodigoCliente"
    }
    var datos = {
        "codigo": codigoComisionista,
        //"dato": dato,
        "fechaInicio": fechaInicio,
        "fechaFin": fechaFin,
        "agrupacion": agrupacion,
        "forma": forma,
        "familia": familia,
        "articulos": articulo,
        "_token": $("meta[name='csrf-token']").attr("content")
    };
    console.log(datos);
    $.ajax({
        url: './informes/ventaFechaFamilia',
        data: datos,
        type: 'post',
        timeout: 2000,
        async: true,
        success: function (respuesta) {
            console.log(respuesta);
            $('#informe').empty();
            var prescriptor = "";
            var anio = 0;
            var fecha = new Date();            
            if (forma == 0) {
                var html = '<table class="border-collapse table-auto w-full whitespace-no-wrap bg-white table-striped relative">' +
                    '<thead>' +
                    '<tr class="text-left">' +
                    '<th class="bg-gray-100 sticky top-0 border-b border-gray-200 px-6 py-2 text-gray-600 font-bold  uppercase text-xs  fijo" style="z-index:2;">';
                    if(agrupacion == 1){
                        html += 'Comisionista' +
                        '</th>'; 
                    }else if(agrupacion == 2){
                        html += 'Cliente' +
                        '</th>'; 
                    }
                                      
                for (m = 2019; m <= fecha.getFullYear() ; m++) {
                    html += '<th class="bg-gray-100 sticky top-0 border-b border-gray-200 px-6 py-2 text-gray-600 font-bold tracking-wider uppercase text-xs">' +
                        m +
                        '</th>';
                }
                html += '</tr>' +
                    '</thead>' +
                    '<tbody>';
                for (i = 0; i < respuesta.length; i++) {
                    if (respuesta[i].cod == prescriptor) {
                        var caso = respuesta[i].anio - anio;
                        switch (caso) {
                            case 1:
                                html += '<td class="border-dashed border-t border-gray-200 ">' +
                                    '<span class="text-gray-700 px-6 py-3 flex items-right">' + parseFloat(respuesta[i].total).toFixed(0) + '</span>' +
                                    '</td>';
                                anio = anio + 1;
                                break;
                            case 2:
                                html += '<td class="border-dashed border-t border-gray-200">' +
                                    '</td>' +
                                    '<td class="border-dashed border-t border-gray-200">' +
                                    '<span class="text-gray-700 px-6 py-3 flex items-right">' + parseFloat(respuesta[i].total).toFixed(0) + '</span>' +
                                    '</td>';
                                anio = anio + 2;
                                break;
                            case 3:
                                html += '<td class="border-dashed border-t border-gray-200">' +
                                    '</td>' +
                                    '<td class="border-dashed border-t border-gray-200">' +
                                    '</td>' +
                                    '<td class="border-dashed border-t border-gray-200">' +
                                    '<span class="text-gray-700 px-6 py-3 flex items-right">' + parseFloat(respuesta[i].total).toFixed(0) + '</span>' +
                                    '</td>';
                                anio = anio + 3;
                                break;
                            case 4:
                                html += '<td class="border-dashed border-t border-gray-200">' +
                                    '</td>' +
                                    '<td class="border-dashed border-t border-gray-200">' +
                                    '</td>' +
                                    '<td class="border-dashed border-t border-gray-200">' +
                                    '</td>' +
                                    '<td class="border-dashed border-t border-gray-200">' +
                                    '<span class="text-gray-700 px-6 py-3 flex items-right">' + parseFloat(respuesta[i].total).toFixed(0) + '</span>' +
                                    '</td>';
                                anio = anio + 4;
                                break;
                        }
                    } else {
                        prescriptor = respuesta[i].cod;
                        //console.log(prescriptor);                        
                        html += '<tr>';
                        html += '<td class="border-dashed border-t border-gray-200 white-space fijo">' +
                            '<span class="text-gray-700 px-6 py-3 flex items-right">' + respuesta[i].cod + '</span>' +
                            '</td>';
                        if (respuesta[i].anio == 2019) {
                            html += '<td class="border-dashed border-t border-gray-200">' +
                                '<span class="text-gray-700 px-6 py-3 flex items-right">' + parseFloat(respuesta[i].total).toFixed(0) + '</span>' +
                                '</td>';
                            anio = 2019;
                        }
                        if (respuesta[i].anio == 2020) {
                            html += '<td class="border-dashed border-t border-gray-200">' +
                                '</td>' +
                                '<td class="border-dashed border-t border-gray-200">' +
                                '<span class="text-gray-700 px-6 py-3 flex items-right">' + parseFloat(respuesta[i].total).toFixed(0) + '</span>' +
                                '</td>';
                            anio = 2020;
                        }
                        if (respuesta[i].anio == 2021) {
                            html += '<td class="border-dashed border-t border-gray-200">' +
                                '</td>' +
                                '<td class="border-dashed border-t border-gray-200">' +
                                '</td>' +
                                '<td class="border-dashed border-t border-gray-200">' +
                                '<span class="text-gray-700 px-6 py-3 flex items-right">' + parseFloat(respuesta[i].total).toFixed(0) + '</span>' +
                                '</td>';
                            anio = 2021;
                        }
                        if (respuesta[i].anio == 2022) {
                            html += '<td class="border-dashed border-t border-gray-200">' +
                                '</td>' +
                                '<td class="border-dashed border-t border-gray-200">' +
                                '</td>' +
                                '<td class="border-dashed border-t border-gray-200">' +
                                '</td>' +
                                '<td class="border-dashed border-t border-gray-200">' +
                                '<span class="text-gray-700 px-6 py-3 flex items-right">' + parseFloat(respuesta[i].total).toFixed(0) + '</span>' +
                                '</td>';
                            anio = 2022;
                        }
                        if (respuesta[i].anio == 2023) {
                            html += '<td class="border-dashed border-t border-gray-200">' +
                                '</td>' +
                                '<td class="border-dashed border-t border-gray-200">' +
                                '</td>' +
                                '<td class="border-dashed border-t border-gray-200">' +
                                '</td>' +
                                '<td class="border-dashed border-t border-gray-200">' +
                                '</td>' +
                                '<td class="border-dashed border-t border-gray-200">' +
                                '<span class="text-gray-700 px-6 py-3 flex items-right">' + parseFloat(respuesta[i].total).toFixed(0) + '</span>' +
                                '</td>';
                            anio = 2023;
                        }

                    }
                }


                var prescriptor = "";
                var mes = 0;
                var semana = 1;
                html += '</tbody>' +
                    '</table>';
                $('#informe').append(html);
                $('#tarjeta').css('display', 'block');
                $('.preloader').css('display', 'none');
            }

            fechaInicio = new Date(fechaInicio);
            fechaFin = new Date(fechaFin);
            var fechaIA = fechaInicio.getFullYear();
            var fechaFA = fechaFin.getFullYear();
            var fechaIM = fechaInicio.getMonth();
            var fechaFM = fechaFin.getMonth();
            var fechaIS = fechaInicio.format("W");
            var fechaFS = fechaFin.format("W");
            const dayOfYear = date => Math.floor((date - new Date(date.getFullYear(), 0, 0)) / 1000 / 60 / 60 / 24);
            var fechaID = dayOfYear(fechaInicio);
            var fechaFD = dayOfYear(fechaFin);
            if (fechaIS == 53 && fechaIM == 0) {
                fechaIS = 01;
            }
            if (fechaIS == 01 && fechaIM == 11) {
                fechaIS = 53;
            }
            if (fechaFS == 53 && fechaFM == 0) {
                fechaFS = 01;
            }
            if (fechaFS == 01 && fechaFM == 11) {
                fechaFS = 53;
            }
            if (forma == 1) {
                var html = '<table class="border-collapse table-auto w-full whitespace-no-wrap bg-white table-striped relative">' +
                    '<thead>' +
                    '<tr class="text-left">' +
                    '<th class="bg-gray-100 sticky top-0 border-b border-gray-200 px-6 py-2 text-gray-600 font-bold  uppercase text-xs  fijo" style="z-index:2;">';
                    if(agrupacion == 1){
                        html += 'Comisionista' +
                        '</th>'; 
                    }else if(agrupacion == 2){
                        html += 'Cliente' +
                        '</th>'; 
                    }
                for (a = fechaIA; a <= fechaFA; a++) {
                    //mes empieza en enero y termina en diciembre
                    if (fechaIM == 0 && a != fechaFA) {
                        console.log('MES EN ENERO FIN DICIEMBRE');
                        for (m = fechaIM + 1; m <= 12; m++) {
                            html += '<th class="bg-gray-100 sticky top-0 border-b border-gray-200 px-6 py-2 text-gray-600 font-bold tracking-wider uppercase text-xs">' +
                                m + "-" + a +
                                '</th>';
                        }
                        fechaIM == 0
                    }
                    // ultimo año con inicio en enero y fin da igual

                    if (fechaFA == a && fechaIM == 0) {
                        console.log('MES ENERO FIN DISTINTO');
                        for (m = 1; m <= fechaFM + 1; m++) {
                            html += '<th class="bg-gray-100 sticky top-0 border-b border-gray-200 px-6 py-2 text-gray-600 font-bold tracking-wider uppercase text-xs">' +
                                m + "-" + a +
                                '</th>';
                        }
                    }
                    //empiece en mes distinto de enero y fin distinto diciembre  
                    if (fechaIM > 0 && fechaFM == 11) {
                        console.log('MES DISTINTO ENERO FIN DICIEMBRE');
                        for (m = fechaIM + 1; m <= 12; m++) {
                            html += '<th class="bg-gray-100 sticky top-0 border-b border-gray-200 px-6 py-2 text-gray-600 font-bold tracking-wider uppercase text-xs">' +
                                m + "-" + a +
                                '</th>';
                        }
                        fechaIM = 0;
                    }

                    if (fechaIM > 0 && fechaFM <= 11 && fechaIA == a && fechaFA != a) {
                        console.log('MES DISTINTO ENERO FIN DICIEMBRE');
                        for (m = fechaIM + 1; m <= 12; m++) {
                            html += '<th class="bg-gray-100 sticky top-0 border-b border-gray-200 px-6 py-2 text-gray-600 font-bold tracking-wider uppercase text-xs">' +
                                m + "-" + a +
                                '</th>';
                        }
                        fechaIM = 0;
                    }

                    if (fechaIM > 0 && fechaFM < 11 && fechaIA == a && fechaFA == a) {
                        console.log('MES DISTINTO ENERO FIN DISTINTO mismo año');
                        for (m = fechaIM + 1; m <= fechaFM + 1; m++) {
                            html += '<th class="bg-gray-100 sticky top-0 border-b border-gray-200 px-6 py-2 text-gray-600 font-bold tracking-wider uppercase text-xs">' +
                                m + "-" + a +
                                '</th>';
                        }
                        fechaIM = 0;
                    }



                }
                html += '</tr>' +
                    '</thead>' +
                    '<tbody>';
                for (i = 0; i < respuesta.length; i++) {
                    console.log()
                    if (respuesta[i].cod == prescriptor) {

                        var saltoDatosA = respuesta[i].anio - anio;
                        var saltoDatosM = respuesta[i].mes - mes;
                        var incrementacion = (saltoDatosA * 12) + saltoDatosM - 1;

                        for (var q = 1; q <= incrementacion - 1; q++) {
                            html += '<td class="border-dashed border-t border-gray-200">' +
                                '</td>';
                        }

                        html += '<td class="border-dashed border-t border-gray-200">' +
                            '<span class="text-gray-700 px-6 py-3 flex items-right">' + parseFloat(respuesta[i].total).toFixed(0) + '</span>' +
                            '</td>';
                        mes = respuesta[i].mes - 1;
                        anio = respuesta[i].anio;
                    } else {
                        prescriptor = respuesta[i].cod;

                        html += '<tr>';
                        html += '<td class="border-dashed border-t border-gray-200 white-space fijo">' +
                            '<span class="text-gray-700 px-6 py-3 flex items-right">' + respuesta[i].cod + '</span>' +
                            '</td>';

                        if (respuesta[i].mes == fechaInicio.getMonth() + 1 && respuesta[i].anio == fechaInicio.getFullYear()) {
                            html += '<td class="border-dashed border-t border-gray-200">' +
                                '<span class="text-gray-700 px-6 py-3 flex items-right">' + parseFloat(respuesta[i].total).toFixed(0) + '</span>' +
                                '</td>';
                            mes = respuesta[i].mes;
                            anio = respuesta[i].anio;
                        } else {
                            var diferenciaA = respuesta[i].anio - fechaInicio.getFullYear();
                            var diferenciaM = respuesta[i].mes - 1 - fechaInicio.getMonth();
                            var incremento = (diferenciaA * 12) + diferenciaM;
                            for (var r = 1; r <= incremento; r++) {
                                html += '<td class="border-dashed border-t border-gray-200">' +
                                    '</td>';
                            }

                            html += '<td class="border-dashed border-t border-gray-200">' +
                                '<span class="text-gray-700 px-6 py-3 flex items-right">' + parseFloat(respuesta[i].total).toFixed(0) + '</span>' +
                                '</td>';
                            mes = respuesta[i].mes;
                            anio = respuesta[i].anio;
                        }


                    }
                }


                var prescriptor = "";
                var mes = 0;
                html += '</tbody>' +
                    '</table>';
                $('#informe').append(html);
                $('#tarjeta').css('display', 'block');
                $('.preloader').css('display', 'none');
            }
            if (forma == 2) {
                console.log(fechaIS);
                console.log(fechaFS);
                var html = '<table class="border-collapse table-auto w-full whitespace-no-wrap bg-white table-striped relative">' +
                    '<thead>' +
                    '<tr class="text-left">' +
                    '<th class="bg-gray-100 sticky top-0 border-b border-gray-200 px-6 py-2 text-gray-600 font-bold  uppercase text-xs  fijo" style="z-index:2;">';
                    if(agrupacion == 1){
                        html += 'Comisionista' +
                        '</th>'; 
                    }else if(agrupacion == 2){
                        html += 'Cliente' +
                        '</th>'; 
                    }
                for (a = fechaIA; a <= fechaFA; a++) {

                    //inicio semana 1 ultimo año
                    if (fechaFA != a && fechaIS == 01) {
                        console.log('SEMANAS inicio 01 y fin 53 ');
                        for (m = 01; m <= 53; m++) {
                            html += '<th class="bg-gray-100 sticky top-0 border-b border-gray-200 px-6 py-2 text-gray-600 font-bold tracking-wider uppercase text-xs">' +
                                m + "-" + a +
                                '</th>';

                        }
                        fechaIS = 01;
                    }

                    if (fechaFA != a && fechaIS != 01) {
                        console.log('SEMANAS inicio distinto y fin 53 ');
                        for (m = fechaIS; m <= 53; m++) {
                            html += '<th class="bg-gray-100 sticky top-0 border-b border-gray-200 px-6 py-2 text-gray-600 font-bold tracking-wider uppercase text-xs">' +
                                m + "-" + a +
                                '</th>';

                        }
                        fechaIS = 01;
                    }

                    if (fechaFA == a && fechaIS == 01) {
                        console.log('SEMANAS inicio 01 y fin fecha elegida año actual ');
                        for (m = 01; m <= fechaFS; m++) {
                            html += '<th class="bg-gray-100 sticky top-0 border-b border-gray-200 px-6 py-2 text-gray-600 font-bold tracking-wider uppercase text-xs">' +
                                m + "-" + a +
                                '</th>';

                        }
                        fechaIS = 01;
                    }

                    //inicio semana 1 cualquier año
                    if (fechaFA == a && fechaIS != 01 && fechaFS != 53) {
                        console.log('Semanas dstinta a 01 final cualquiera');
                        for (s = fechaIS; s <= fechaFS; s++) {
                            html += '<th class="bg-gray-100 sticky top-0 border-b border-gray-200 px-6 py-2 text-gray-600 font-bold tracking-wider uppercase text-xs">' +
                                s + "-" + a +
                                '</th>';
                        }
                        fechaIS = 01;
                    }

                }
                html += '</tr>' +
                    '</thead>' +
                    '<tbody>';
                for (i = 0; i < respuesta.length; i++) {
                    console.log()
                    if (respuesta[i].cod == prescriptor) {

                        var saltoDatosA = respuesta[i].anio - anio;
                        var saltoDatosM = respuesta[i].NumSemana - semana;
                        var incrementacion = (saltoDatosA * 53) + saltoDatosM;

                        for (var q = 1; q <= incrementacion - 1; q++) {
                            html += '<td class="border-dashed border-t border-gray-200">' +
                                '</td>';
                        }

                        html += '<td class="border-dashed border-t border-gray-200">' +
                            '<span class="text-gray-700 px-6 py-3 flex items-right">' + parseFloat(respuesta[i].total).toFixed(0) + '</span>' +
                            '</td>';
                        semana = respuesta[i].NumSemana;
                        anio = respuesta[i].anio;
                    } else {
                        prescriptor = respuesta[i].cod;
                        console.log(prescriptor);
                        html += '<tr>';
                        html += '<td class="border-dashed border-t border-gray-200 white-space fijo">' +
                            '<span class="text-gray-700 px-6 py-3 flex items-right">' + respuesta[i].cod + '</span>' +
                            '</td>';

                        if (respuesta[i].NumSemana == fechaFin.format("W") && respuesta[i].anio == fechaInicio.getFullYear()) {
                            html += '<td class="border-dashed border-t border-gray-200">' +
                                '<span class="text-gray-700 px-6 py-3 flex items-right">' + parseFloat(respuesta[i].total).toFixed(0) + '</span>' +
                                '</td>';
                            semana = respuesta[i].NumSemana;
                            anio = respuesta[i].anio;
                        } else {
                            var diferenciaA = respuesta[i].anio - fechaInicio.getFullYear();
                            var diferenciaM = respuesta[i].NumSemana - fechaInicio.format("W");
                            var incremento = (diferenciaA * 53) + diferenciaM;
                            for (var r = 1; r <= incremento; r++) {
                                html += '<td class="border-dashed border-t border-gray-200">' +
                                    '</td>';
                            }

                            html += '<td class="border-dashed border-t border-gray-200">' +
                                '<span class="text-gray-700 px-6 py-3 flex items-right">' + parseFloat(respuesta[i].total).toFixed(0) + '</span>' +
                                '</td>';
                            semana = respuesta[i].NumSemana;
                            anio = respuesta[i].anio;
                        }


                    }
                }



                var prescriptor = "";
                var mes = 0;
                html += '</tbody>' +
                    '</table>';
                $('#informe').append(html);
                $('#tarjeta').css('display', 'block');
                $('.preloader').css('display', 'none');

            }

            if (forma == 3) {
                console.log(fechaID);
                console.log(fechaFD);
                var html = '<table class="border-collapse table-auto w-full whitespace-no-wrap bg-white table-striped relative">' +
                    '<thead>' +
                    '<tr class="text-left">' +
                    '<th class="bg-gray-100 sticky top-0 border-b border-gray-200 px-6 py-2 text-gray-600 font-bold  uppercase text-xs  fijo" style="z-index:2;">';
                    if(agrupacion == 1){
                        html += 'Comisionista' +
                        '</th>'; 
                    }else if(agrupacion == 2){
                        html += 'Cliente' +
                        '</th>'; 
                    }
                for (a = fechaIA; a <= fechaFA; a++) {

                    //inicio semana 1 ultimo año
                    if (fechaFA != a && fechaID == 1) {
                        console.log('SEMANAS inicio 01 y fin 53 ');
                        for (m = 1; m <= 365; m++) {
                            html += '<th class="bg-gray-100 sticky top-0 border-b border-gray-200 px-6 py-2 text-gray-600 font-bold tracking-wider uppercase text-xs">' +
                                m + "-" + a +
                                '</th>';
                        }
                        fechaID = 01;
                    }

                    if (fechaFA != a && fechaID != 01) {
                        console.log('SEMANAS inicio distinto y fin 53 ');
                        for (m = fechaID; m <= 365; m++) {
                            html += '<th class="bg-gray-100 sticky top-0 border-b border-gray-200 px-6 py-2 text-gray-600 font-bold tracking-wider uppercase text-xs">' +
                                m + "-" + a +
                                '</th>';

                        }
                        fechaID = 01;
                    }

                    if (fechaFA == a && fechaID == 01) {
                        console.log('SEMANAS inicio 01 y fin fecha elegida año actual ');
                        for (m = 01; m <= fechaFD; m++) {
                            html += '<th class="bg-gray-100 sticky top-0 border-b border-gray-200 px-6 py-2 text-gray-600 font-bold tracking-wider uppercase text-xs">' +
                                m + "-" + a +
                                '</th>';

                        }
                        fechaID = 01;
                    }

                    //inicio semana 1 cualquier año
                    if (fechaFA == a && fechaID != 01 && fechaFD != 365) {
                        console.log('Semanas dstinta a 01 final cualquiera');
                        for (s = fechaID; s <= fechaFD; s++) {
                            html += '<th class="bg-gray-100 sticky top-0 border-b border-gray-200 px-6 py-2 text-gray-600 font-bold tracking-wider uppercase text-xs">' +
                                s + "-" + a +
                                '</th>';
                        }
                        fechaID = 01;
                    }

                }
                html += '</tr>' +
                    '</thead>' +
                    '<tbody>';
                fechadia = 0;
                for (i = 0; i < respuesta.length; i++) {
                    var fechadia0 = new Date(respuesta[i].fechaAlbaran);
                    fechadia = dayOfYear(fechadia0);
                    // var hola = dayOfYear(fechadia);
                    //console.log(hola)
                    if (respuesta[i].cod == prescriptor) {

                        var saltoDatosA = respuesta[i].anio - anio;                        
                        var saltoDatosM = fechadia - dia;                                                                               
                        var incrementacion = (saltoDatosA * 365) + saltoDatosM;                        
                        for (var q = 1; q <= incrementacion - 1; q++) {
                            html += '<td class="border-dashed border-t border-gray-200">' +
                                '</td>';
                        }

                        html += '<td class="border-dashed border-t border-gray-200">' +
                            '<span class="text-gray-700 px-6 py-3 flex items-right">' + parseFloat(respuesta[i].total).toFixed(0) + '</span>' +
                            '</td>';
                        dia = fechadia;
                        anio = respuesta[i].anio;
                    } else {
                        prescriptor = respuesta[i].cod;

                        html += '<tr>';
                        html += '<td class="border-dashed border-t border-gray-200 white-space fijo">' +
                            '<span class="text-gray-700 px-6 py-3 flex items-right">' + respuesta[i].cod + '</span>' +
                            '</td>';

                        if (fechadia == dayOfYear(fechaFin) && respuesta[i].anio == fechaInicio.getFullYear()) {
                            html += '<td class="border-dashed border-t border-gray-200">' +
                                '<span class="text-gray-700 px-6 py-3 flex items-right">' + parseFloat(respuesta[i].total).toFixed(0) + '</span>' +
                                '</td>';                            
                            dia = dayOfYear(respuesta[i].fechaAlbaran);
                            anio = respuesta[i].anio;
                        } else {
                            var diferenciaA = respuesta[i].anio - fechaInicio.getFullYear();                        
                            var diferenciaM = fechadia - dayOfYear(fechaInicio);                        
                            var incremento = (diferenciaA * 365) + diferenciaM;                        
                            for (var r = 1; r <= incremento; r++) {
                                html += '<td class="border-dashed border-t border-gray-200">' +
                                    '</td>';
                            }

                            html += '<td class="border-dashed border-t border-gray-200">' +
                                '<span class="text-gray-700 px-6 py-3 flex items-right">' + parseFloat(respuesta[i].total).toFixed(0) + '</span>' +
                                '</td>';
                            dia = fechadia;
                            anio = respuesta[i].anio;
                        }


                    }
                }
                // var prescriptor = "";
                // var mes = 0;
                html += '</tbody>' +
                    '</table>';
                $('#informe').append(html);
                $('#tarjeta').css('display', 'block');
                $('.preloader').css('display', 'none');
            }
        }
    });
}