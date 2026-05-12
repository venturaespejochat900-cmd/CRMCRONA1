var Base64={
    _keyStr:"ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=",
    encode:function(e){
        var t="";
        var n,r,i,s,o,u,a;
        var f=0;e=Base64._utf8_encode(e);
        while(f<e.length){
            n=e.charCodeAt(f++);
            r=e.charCodeAt(f++);
            i=e.charCodeAt(f++);s=n>>2;
            o=(n&3)<<4|r>>4;u=(r&15)<<2|i>>6;
            a=i&63;
            if(isNaN(r)){
                u=a=64
            }else if(isNaN(i)){
                a=64
            }
            t=t+this._keyStr.charAt(s)+this._keyStr.charAt(o)+this._keyStr.charAt(u)+this._keyStr.charAt(a)
        }
        return t
    },
    decode:function(e){
        var t="";
        var n,r,i;
        var s,o,u,a;
        var f=0;
        e=e.replace(/[^A-Za-z0-9+/=]/g,"");
        while(f<e.length){
            s=this._keyStr.indexOf(e.charAt(f++));
            o=this._keyStr.indexOf(e.charAt(f++));
            u=this._keyStr.indexOf(e.charAt(f++));
            a=this._keyStr.indexOf(e.charAt(f++));
            n=s<<2|o>>4;
            r=(o&15)<<4|u>>2;i=(u&3)<<6|a;
            t=t+String.fromCharCode(n);
            if(u!=64){
                t=t+String.fromCharCode(r)
            }
            if(a!=64){
                t=t+String.fromCharCode(i)
            }
        }
        t=Base64._utf8_decode(t);
        return t
    },
    _utf8_encode:function(e){
        e=e.replace(/rn/g,"n");
        var t="";
        for(var n=0;n<e.length;n++){
            var r=e.charCodeAt(n);
            if(r<128){
                t+=String.fromCharCode(r)
            }else if(r>127&&r<2048){
                t+=String.fromCharCode(r>>6|192);
                t+=String.fromCharCode(r&63|128)
            }else{
                t+=String.fromCharCode(r>>12|224);
                t+=String.fromCharCode(r>>6&63|128);
                t+=String.fromCharCode(r&63|128)
            }
        }
        return t
    },
    _utf8_decode:function(e){
        var t="";
        var n=0;
        var r=c1=c2=0;
        while(n<e.length){
            r=e.charCodeAt(n);
            if(r<128){
                t+=String.fromCharCode(r);
                n++
            }else if(r>191&&r<224){
                c2=e.charCodeAt(n+1);
                t+=String.fromCharCode((r&31)<<6|c2&63);
                n+=2
            }else{
                c2=e.charCodeAt(n+1);
                c3=e.charCodeAt(n+2);
                t+=String.fromCharCode((r&15)<<12|(c2&63)<<6|c3&63);
                n+=3
            }
        }
        return t
    }
}

function b64EncodeUnicode(str) {
    return btoa(encodeURIComponent(str).replace(/%([0-9A-F]{2})/g, function(match, p1) {
        return String.fromCharCode('0x' + p1);
    }));
}

var canvas;
var signaturePad;

function send_form(){
    load_script ("control",$("form.form-signin").serialize());
}

function load_script (data1, firma){
    var id = $('#id').val();    
    var tipo = $('#tipo').val();
    var currentTime = new Date();
    var datos = {
        "pdf" : data1,
        "firma": firma,
        "id": id,
        "fecha": currentTime,
        "tipo": tipo,
        "_token": $("meta[name='csrf-token']").attr("content") 
    }
    console.log(tipo);

    if(tipo == 'sepa'){

        Pace.track(function(){
            $.ajax({
                data: datos,
                url: '../../firma/sepa',
                type: 'post',
                timeout: 2000,
                async: true,
                error: function (XMLHttpRequest, textStatus, errorThrown) { 
                    $("body").pgNotification( { 
                        style: 'simple', 
                        type: 'danger', 
                        // timeout: 10000, 
                        message: "<div class='col-md-2'><i class=\"fa fa-code\"></i></div><div class='col-md-10'><strong>Page:</strong> " + 'Firmar' + "<br/><strong>Status:</strong> " + XMLHttpRequest.status + "<br/><strong>Error:</strong> " + errorThrown + "</div>" 
                    }).show(); 
                },            
                success: function(response){                
                    //$('div.aviso2').append('Descarga tu contrato firmado <a href="'+response+'" target="_blank">'+response+'</a>');
                    $('div.aviso2').append('Descarga la rgpd firmada <a href="'+response+'" target="_blank">'+response+'</a>');
                    $('div.aviso2').fadeIn();                 
                    
                }
            });
        });

    }else if(tipo == "rgpd"){

        Pace.track(function(){
            $.ajax({
                data: datos,
                url: '../../firma/rgpd',
                type: 'post',
                timeout: 2000,
                async: true,
                error: function (XMLHttpRequest, textStatus, errorThrown) { 
                    $("body").pgNotification( { 
                        style: 'simple', 
                        type: 'danger', 
                       // timeout: 10000, 
                        message: "<div class='col-md-2'><i class=\"fa fa-code\"></i></div><div class='col-md-10'><strong>Page:</strong> " + 'Firmar' + "<br/><strong>Status:</strong> " + XMLHttpRequest.status + "<br/><strong>Error:</strong> " + errorThrown + "</div>" 
                    }).show(); 
                },            
                success: function(response){                
                    //$('div.aviso2').append('Descarga tu contrato firmado <a href="'+response+'" target="_blank">'+response+'</a>');
                    $('div.aviso2').append('Descarga la rgpd firmada <a href="'+response+'" target="_blank">'+response+'</a>');
                    $('div.aviso2').fadeIn();                 
                    
                }
            });
        });

    }else if(tipo == "rgpdempresa"){

        Pace.track(function(){
            $.ajax({
                data: datos,
                url: '../../firma/rgpd',
                type: 'post',
                timeout: 2000,
                async: true,
                error: function (XMLHttpRequest, textStatus, errorThrown) { 
                    $("body").pgNotification( { 
                        style: 'simple', 
                        type: 'danger', 
                       // timeout: 10000, 
                        message: "<div class='col-md-2'><i class=\"fa fa-code\"></i></div><div class='col-md-10'><strong>Page:</strong> " + 'Firmar' + "<br/><strong>Status:</strong> " + XMLHttpRequest.status + "<br/><strong>Error:</strong> " + errorThrown + "</div>" 
                    }).show(); 
                },            
                success: function(response){                
                    //$('div.aviso2').append('Descarga tu contrato firmado <a href="'+response+'" target="_blank">'+response+'</a>');
                    $('div.aviso2').append('Descarga tu contrato firmado <a href="'+response+'" target="_blank">'+response+'</a>');
                    $('div.aviso2').fadeIn();                 
                    
                }
            });
        });
    }else{

        Pace.track(function(){
            $.ajax({
                data: datos,
                url: '../../firma/completa',
                type: 'post',
                timeout: 2000,
                async: true,
                error: function (XMLHttpRequest, textStatus, errorThrown) { 
                    $("body").pgNotification( { 
                        style: 'simple', 
                        type: 'danger', 
                       // timeout: 10000, 
                        message: "<div class='col-md-2'><i class=\"fa fa-code\"></i></div><div class='col-md-10'><strong>Page:</strong> " + 'Firmar' + "<br/><strong>Status:</strong> " + XMLHttpRequest.status + "<br/><strong>Error:</strong> " + errorThrown + "</div>" 
                    }).show(); 
                },            
                success: function(response){                
                    //$('div.aviso2').append('Descarga tu contrato firmado <a href="'+response+'" target="_blank">'+response+'</a>');
                    $('div.aviso2').append('Descarga tu contrato firmado <a href="'+response+'" target="_blank">'+response+'</a>');
                    $('div.aviso2').fadeIn();                 
                    
                }
            });
        });
    }

}

function init_pad(){

    $("div.firma canvas").attr("width",$("div.firma").outerWidth());
    $("div.firma canvas").attr("height",$("div.firma").outerHeight());

    canvas = document.querySelector("canvas");
    signaturePad = new SignaturePad(canvas);

    $(window).resize(function(){ 
        resizeCanvas(); 
    });

    $("button[data-action=clear]").off();
    $("button[data-action=clear]").on("click",function () {
        signaturePad.clear(); 
    });

    $("button[data-action=save-png]").off();
    $("button[data-action=save-png]").on("click",function () {

        $(this).attr("disabled","disabled");

        if (signaturePad.isEmpty() || ($('#id').length &&  $('#id').val()=='')) {
            $('div.aviso').html('Falta algún dato, revise la firma o los datos solicitados en este documento.');
            $('div.aviso').fadeIn(); 
            setTimeout(function () { 
                $('div.aviso').fadeOut(); 
            }, 3000);
            $(this).removeAttr("disabled");
        } else {
            $(".eliminar").remove();
            $("input[name=consentimiento]:checked").attr("checked","checked");
            var dataURL = signaturePad.toDataURL();
            firma = (dataURL);
            console.log('init_pad');
            setTimeout(function () { 
                load_script (encodeURI($(".documento_pdf").html()) , encodeURI(firma)) 
            }, 500);
        }

    });

}

function init_pad_rgpd(){

    $("div.firma canvas").attr("width",$("div.firma").outerWidth());
    $("div.firma canvas").attr("height",$("div.firma").outerHeight());

    canvas = document.querySelector("canvas");
    signaturePad = new SignaturePad(canvas);

    $(window).resize(function () { 
        resizeCanvas(); 
    });

    $("button[data-action=clear]").off();
    $("button[data-action=clear]").on("click",function () { 
        signaturePad.clear(); 
    });

    $("button[data-action=save-png]").off();
    $("button[data-action=save-png]").on("click",function () {

        $(this).attr("disabled","disabled");

        if (signaturePad.isEmpty()) {
            $('div.aviso').html('Falta algÃºn dato, revise la firma o los datos de este documento.');
            $('div.aviso').fadeIn(); setTimeout(function () { 
                $('div.aviso').fadeOut(); 
            }, 3000);
            $(this).removeAttr("disabled");
        } else if ( $('#check1').length && (!($('#check1').prop('checked')) && !($('#check2').prop('checked')))){
            $('div.aviso').html('Es necesario marcar la existencia o no de delegado de datos, y suministrar los datos del mismo.');
            $('div.aviso').fadeIn(); setTimeout(function () { 
                $('div.aviso').fadeOut(); 
            }, 3000);
            $(this).removeAttr("disabled");
        } else if ( $('#check1').length && (($('#check1').prop('checked') && ($('#identidad1').val()=='' || $('#email1').val()=='' || $('#telefono1').val()=='')) || ($('#check2').prop('checked') && ($('#identidad2').val()=='' || $('#email2').val()=='' || $('#telefono2').val()=='')))){
            $('div.aviso').html('Es necesario suministrar los datos del delegado o contacto de los datos.');
            $('div.aviso').fadeIn(); 
            setTimeout(function () { 
                $('div.aviso').fadeOut(); 
            }, 3000);
            $(this).removeAttr("disabled");
        } else {
            if ($('#check1').is(":checked")){
                identidad = $('#identidad1').val();
                email = $('#email1').val();
                telefono = $('#telefono1').val();
                tipodelegado = 1;
            }else if ($('#check2').is(":checked")){
                identidad = $('#identidad2').val();
                email = $('#email2').val();
                telefono = $('#telefono2').val();
                tipodelegado = 2;
            }else{
                identidad = '';
                email = '';
                telefono = '';
                tipodelegado = 0;
            }

            $(".edita-input").remove();
            $(".texto-input").show();

            $(".eliminar").remove();
            $("input[name=consentimiento]:checked").attr("checked","checked");
            var dataURL = signaturePad.toDataURL();
            firma = (dataURL);

            setTimeout(function () { 
                load_script (encodeURI($(".documento_pdf").html()), encodeURI(firma)) 
            }, 500);
        //////////////////////////////////////
        /*
            setTimeout(function () {
            $(".edita-input").show();
            $(".texto-input").hide();
            }, 750);
        */
        }

    });

}

function init_pad_sepa(){

    $("div.firma canvas").attr("width",$("div.firma").outerWidth());
    $("div.firma canvas").attr("height",$("div.firma").outerHeight());

    canvas = document.querySelector("canvas");
    signaturePad = new SignaturePad(canvas);

    $(window).resize(function () { 
        resizeCanvas(); 
    });

    $("button[data-action=clear]").off();
    $("button[data-action=clear]").on("click",function () {
         signaturePad.clear(); 
    });

    $("button[data-action=save-png]").off();
    $("button[data-action=save-png]").on("click",function () {

        $(this).attr("disabled","disabled");

        if (signaturePad.isEmpty()) {
            $('div.aviso').html('Falta algÃºn dato, revise la firma o los datos de este documento.');
            $('div.aviso').fadeIn(); setTimeout(function () { 
                $('div.aviso').fadeOut(); 
            }, 3000);
            $(this).removeAttr("disabled");
        } else {

            $(".eliminar").remove();
            $("input[name=consentimiento]:checked").attr("checked","checked");
            var dataURL = signaturePad.toDataURL();
            firma = (dataURL);

            setTimeout(function () { 
                load_script ("funciones","acc=graba_sepa&documento=" + encodeURI($("div.documento_pdf").html()) + "&token=" + token + "&firma=" + encodeURI(firma)) 
            }, 500);
        }

    });

}

function init_pad_cliente_contrato(){

    $("div.firma canvas").attr("width",$("div.firma").outerWidth());
    $("div.firma canvas").attr("height",$("div.firma").outerHeight());

    canvas = document.querySelector("canvas");
    signaturePad = new SignaturePad(canvas);

    $(window).resize(function () { 
        resizeCanvas(); 
    });

    $("button[data-action=clear]").off();
    $("button[data-action=clear]").on("click",function () { 
        signaturePad.clear(); 
    });

    $("button[data-action=save-png]").off();
    $("button[data-action=save-png]").on("click",function () {

        $(this).attr("disabled","disabled");

        if (signaturePad.isEmpty()) {
            $('div.aviso').html('Falta algÃºn dato, revise la firma o los datos de este documento.');
            $('div.aviso').fadeIn(); setTimeout(function () { 
                $('div.aviso').fadeOut(); 
            }, 3000);
            $(this).removeAttr("disabled");
        } else {

            $(".eliminar").remove();
            $("input[name=consentimiento]:checked").attr("checked","checked");
            var dataURL = signaturePad.toDataURL();
            firma = (dataURL);

            setTimeout(function () { 
                load_script ("funciones","acc=graba_cliente_documento&documento=" + encodeURI($("div.documento_pdf").html()) + "&token=" + token + "&firma=" + encodeURI(firma)) 
            }, 500);
        }

    });

}

function resizeCanvas(){

    $("div.firma canvas").attr("width",$("div.firma").outerWidth());
    $("div.firma canvas").attr("height",$("div.firma").outerHeight());
    signaturePad.clear();

}

function isJson(str) {
    try {
        json = JSON.parse(str);
    } catch (e) {
        return false;
    }
    return true;
}