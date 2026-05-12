<!DOCTYPE html>
<html>
  <head>
    <title>Mapa Calor</title>
    <script src="https://polyfill.io/v3/polyfill.min.js?features=default"></script>
    <script src="{{asset('js/map.js')}}"></script>
    <style>
      /* Always set the map height explicitly to define the size of the div
      * element that contains the map. */
      #map {
        height: 100%;
      }

      /* Optional: Makes the sample page fill the window. */
      html,
      body {
        height: 100%;
        margin: 0;
        padding: 0;
      }
    </style>
    <!-- jsFiddle will insert css and js -->
  </head>
  <body>
    
    <div id="map"></div>

    <!-- Async script executes immediately and must be after any DOM elements used in callback. -->
    <script
      src="https://maps.googleapis.com/maps/api/js?key=AIzaSyC7hbeiRJZhuZ2l8ybIxVh_hUYB-yLUQnw&callback=initMap&libraries=visualization&v=weekly&channel=2"
      async
    ></script>
  </body>
</html>
