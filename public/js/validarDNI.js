/**
 * método para validar cif / dni
 *
 */
var dType = {
    'A':'Sociedad Anónima',
    'B':'Sociedad de Responsabilidad Limitada',
    'C':'Sociedad Colectiva',
    'D':'Sociedad Comanditaria',
    'E':'Comunidad de Bienes',
    'F':'Sociedad Cooperativa',
    'G':'Asociación o Fundación',
    'H':'Comunidad de Propietarios en Régimen de Propiedad Horizontal',
    'J':'Sociedad Civil, con o sin Personalidad Jurídica',
    'K':'Español menor de 14 años',
    'L':'Español residente en el extranjero sin DNI',
    'M':'NIF que otorga la Agencia Tributaria a extranjeros que no tienen NIE',
    'N':'Entidad Extranjera',
    'P':'Corporación Local',
    'Q':'Organismo Autónomo, Estatal o no, o Asimilado, o Congregación o Institución Religiosa',
    'R':'Congregación o Institución Religiosa (desde 2008, ORDEN EHA/451/2008)',
    'S':'Órgano de la Administración General del Estado o de las Comunidades Autónomas',
    'U':'Unión Temporal de Empresas',
    'V':'Sociedad Agraria de Transformación',
    'W':'Establecimiento permanente de entidad no residente en España',
    'X':'Extranjero identificado por la Policía con un número de identidad de extranjero, NIE, asignado hasta el 15 de julio de 2008',
    'Y':'Extranjero identificado por la Policía con un NIE, asignado desde el 16 de julio de 2008 (Orden INT/2058/2008, BOE del 15 de julio)',
    'Z':'Letra reservada para cuando se agoten los "Y" para Extranjeros identificados por la Policía con un NIE'
};

function comprobarExisteDNICIFenBBDD(cifdni){
    $("#divCIFDNIExistente").css('display','none');
    var parametros = {
        "cifdni" : cifdni,
        "_token": $("meta[name='csrf-token']").attr("content")
    };
    $.ajax({
        data: parametros,
        url: './comprobarExisteDniCifEnBBDD',
        type: 'post',
        timeout: 2000,
        async: true,
        success: function (response){
            if (response == 1){
                $("#divCIFDNIExistente").css('display','block');
                $("#dniIntroducido").text(cifdni)
            }
        }
    })
}


function ValidateSpanishID (str) {
        // Ensure upcase and remove whitespace
        str = str.toUpperCase().replace(/\s/, '');

        var valid = false;
        var type = spainIdType( str );

        switch (type) {
            case 'dni':
                valid = validDNI( str );
                break;
            case 'nie':
                valid = validNIE( str );
                break;
            case 'cif':
                valid = validCIF( str );
                break;
        }

        if(valid == false){
            $("#divErrorCIFDNI").css('display','block');
        }else{
            $("#divErrorCIFDNI").css('display','none');
        }

    };

    function spainIdType ( str ) {
        var DNI_REGEX = /^(\d{8})([A-Z])$/;
        var CIF_REGEX = /^([ABCDEFGHJKLMNPQRSUVW])(\d{7})([0-9A-J])$/;
        var NIE_REGEX = /^[XYZ]\d{7,8}[A-Z]$/;

        if ( str.match( DNI_REGEX ) ) {
            return 'dni';
        }
        if ( str.match( CIF_REGEX ) ) {
            return 'cif';
        }
        if ( str.match( NIE_REGEX ) ) {
            return 'nie';
        }
    };

    function validDNI (dni) {

        var dni_letters = "TRWAGMYFPDXBNJZSQVHLCKE";
        var letter = dni_letters.charAt( parseInt( dni, 10 ) % 23 );

        return letter ==  dni.charAt(8);
        /*console.log(dni.charAt(8));

        if (letter == dni.charAt(8)){
            console.log("bien");
            return true;
        }else{
            console.log("mal");
            return false;
        }*/

    };

    function validNIE (nie) {

        // Change the initial letter for the corresponding number and validate as DNI
        var nie_prefix = nie.charAt( 0 );

        switch (nie_prefix) {
            case 'X': nie_prefix = 0; break;
            case 'Y': nie_prefix = 1; break;
            case 'Z': nie_prefix = 2; break;
        }

        return validDNI( nie_prefix + nie.substr(1) );
    };

    function validCIF ( cif ) {

        var match = cif.match( CIF_REGEX );
        var letter  = match[1],
            number  = match[2],
            control = match[3];

        var even_sum = 0;
        var odd_sum = 0;
        var n;

        for ( var i = 0; i < number.length; i++) {
            n = parseInt( number[i], 10 );

            // Odd positions (Even index equals to odd position. i=0 equals first position)
            if ( i % 2 === 0 ) {
                // Odd positions are multiplied first.
                n *= 2;

                // If the multiplication is bigger than 10 we need to adjust
                odd_sum += n < 10 ? n : n - 9;

                // Even positions
                // Just sum them
            } else {
                even_sum += n;
            }

        }

       // var control_digit = (10 - (even_sum + odd_sum).toString().substr(-1)).toString().substr(-1) ;
        var control_digit = (10 - (even_sum + odd_sum).toString().substr(-1) ) % 10;
        var control_letter = 'JABCDEFGHI'.substr( control_digit, 1 );

        // Control must be a digit
        if ( letter.match( /[ABEH]/ ) ) {
            return control == control_digit;

            // Control must be a letter
        } else if ( letter.match( /[KPQS]/ ) ) {
            return control == control_letter;

            // Can be either
        } else {
            return control == control_digit || control == control_letter;
        }

    };	