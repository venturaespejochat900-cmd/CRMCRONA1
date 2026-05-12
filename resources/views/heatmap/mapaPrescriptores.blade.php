@include('layouts.header')
<script src="https://polyfill.io/v3/polyfill.min.js?features=default"></script>
<script src="{{asset('js/map4.js')}}"></script>
@include('layouts.sidebar')
@include('layouts.navbar')
<style>
    /* Always set the map height explicitly to define the size of the div
    * element that contains the map. */
    #map {
        height: 750px;
        width: 1300px;
        overflow: hidden;
        float: left;
        border: thin solid #333;
    }

    @media only screen and (min-width: 600px) {
        #map {
            height: 500px;
            width: 500px;
            overflow: hidden;
            float: left;
            border: thin solid #333;
        }
    }
    @media only screen and (min-width: 768px) {
        #map {
            height: 700px;
            width: 700px;
            overflow: hidden;
            float: left;
            border: thin solid #333;
        }
    }
    @media only screen and (min-width: 1025px) {
        #map {
            height: 650px;
            width: 900px;
            overflow: hidden;
            float: left;
            border: thin solid #333;
        }
    }
    @media only screen and (min-width: 1440px) {
        #map {
            height: 700px;
            width: 1000px;
            overflow: hidden;
            float: left;
            border: thin solid #333;
        }
    }
</style>
<?php
    if(session('codigoComisionista') == 0){  
        header("Location: http://cronadis.abmscloud.com/");
        exit();
    }else{
?>
<script
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyC7hbeiRJZhuZ2l8ybIxVh_hUYB-yLUQnw&callback=initMap&libraries=visualization&v=weekly&channel=2"
    async type="text/javascript"
></script>

<div id="map" class="justify-center"></div>

<!-- Async script executes immediately and must be after any DOM elements used in callback. -->

<?php 
    }
?>
@include('layouts.footer')
@include('layouts.panels')

