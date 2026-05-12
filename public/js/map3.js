let map;
let heatmap;
let puntos = new Array();


function enviarInforme() {
    puntos = new Array();
    $('#tarjeta').css('display', 'none');
    $('.preloader').css('display', 'block');    
    var fechaInicio = $('#fechaInicio').val();
    var fechaFin = $('#fechaFin').val();    
    var agrupacion = $('#agrupacion').val();    
    var datos = {
        "fechaInicio": fechaInicio,
        "fechaFin": fechaFin,
        "agrupacion": agrupacion,
        "_token": $("meta[name='csrf-token']").attr("content")
    };
    //console.log(datos);
    $.ajax({
        url: './informes/ventaFechaMaps',
        data: datos,
        type: 'post',
        timeout: 5000,
        async: true,
        success: function(respuesta) {
          //console.log(respuesta);

          for(let i= 0; i<respuesta.length; i++){
            //console.log(result[i].VLatitud, result[i].VLongitud)
            puntos.push(new google.maps.LatLng(respuesta[i].VLatitud, respuesta[i].VLongitud));
            
          }  
          //console.log(puntos);
          getPoints(puntos);
        }
    });
    $('#tarjeta').css('display', 'block');
    $('.preloader').css('display', 'none');
}


function initMap() {  
   
  getPoints(puntos);

}


function getPoints(datos) {
  
  
  var puntos = new google.maps.MVCArray(datos);
  
  // console.log(new google.maps.LatLng(37.4101400000, -4.4867510000))
  var datitos = [ 
    new google.maps.LatLng(37.4101400000, -4.4867510000),
    new google.maps.LatLng(41.38879, 2.15899),
    new google.maps.LatLng(40.484535, -3.38683),
    new google.maps.LatLng(40.41031630000000, -3.6423244),
    new google.maps.LatLng(41.65606, -0.87734),
    new google.maps.LatLng(36.72016, -4.42034),
    new google.maps.LatLng(37.98704, -1.13004),
    new google.maps.LatLng(39.56939, 2.65024),
    new google.maps.LatLng(28.09973, -15.41343),
    new google.maps.LatLng(43.26271, -2.92528),
    new google.maps.LatLng(37.38283, -5.97317),
    new google.maps.LatLng(41.65518, -4.72372),
    new google.maps.LatLng(42.23282, -8.72264),
    new google.maps.LatLng(40.38897, -3.74569),
    new google.maps.LatLng(43.37135, -8.396),
    new google.maps.LatLng(37.18817, -3.60667),
    new google.maps.LatLng(43.36029, -5.84476),
    new google.maps.LatLng(28.46824, -16.25462),
    new google.maps.LatLng(41.45004, 2.24741),
    new google.maps.LatLng(37.60512, -0.98623),
    new google.maps.LatLng(41.56667, 2.01667)
  ]
  
  map = new google.maps.Map(document.getElementById("map"), {
    zoom: 7,
    center: { lat: 40.463667, lng: -3.74922 },
    mapTypeId: "roadmap"    
  });
  
  heatmap = new google.maps.visualization.HeatmapLayer({
    data: puntos,
    map: map,
    maxIntensity: 20,
    radius: 20,
    opacity: 0.70
    
  });
  //heatmap.get('maxIntensity')
}