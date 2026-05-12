@include('layouts.header')
@livewireStyles
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/1.9.2/tailwind.min.css" integrity="sha512-l7qZAq1JcXdHei6h2z8h8sMe3NbMrmowhOl+QkP3UhifPpCW2MC4M0i26Y8wYpbz1xD9t61MLT9L1N773dzlOA==" crossorigin="anonymous" />
<link rel="dns-prefetch" href="//cdn.jsdelivr.net" />
<link rel="stylesheet" href="https://unpkg.com/tailwindcss@^1.0/dist/tailwind.min.css">
@include('layouts.sidebar')
@include('layouts.navbar')
<?php
    if(session('codigoComisionista') == 0){  
        header("Location: http://cronadis.abmscloud.com/");
        exit();
    }else{
?>
<div class="grid grid-cols-1 p-4 space-y-8 lg:gap-8 lg:space-y-0 lg:grid-cols-4 border-b">
    <div class="col-span-4 bg-white rounded-md dark:bg-darker">
       <!-- This is an example component -->
       
<div>

<script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.x.x/dist/alpine.js" defer></script>
<style>
    [x-cloak] {
        display: none;
    }
</style>

<div class="antialiased sans-serif bg-gray-100 ">

<div x-data="app()" x-init="[initDate(), getNoOfDays()]" x-cloak>
    <div class="container mx-auto px-4 py-2 md:py-2">
    
        <div class="font-bold text-gray-800 text-xl mb-4 align-content-center">
            Calendario Comercial
        </div> 

        <div class="bg-white rounded-lg shadow overflow-hidden">

            <div class="flex items-center justify-between py-2 px-6">
                <div>
                    <span x-text="MONTH_NAMES[month]" class="text-lg font-bold text-gray-800"></span>
                    <span x-text="year" class="ml-1 text-lg text-gray-600 font-normal"></span>
                </div>
                <div class="border rounded-lg px-1" style="padding-top: 2px;">
                    <button 
                        type="button"
                        class="leading-none rounded-lg transition ease-in-out duration-100 inline-flex cursor-pointer hover:bg-gray-200 p-1 items-center" 
                        :class="{'cursor-not-allowed opacity-25': month == 0 }"
                        :disabled="month == 0 ? true : false"
                        @click="month--; getNoOfDays()">
                        <svg class="h-6 w-6 text-gray-500 inline-flex leading-none"  fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                        </svg>  
                    </button>
                    <div class="border-r inline-flex h-6"></div>		
                    <button 
                        type="button"
                        class="leading-none rounded-lg transition ease-in-out duration-100 inline-flex items-center cursor-pointer hover:bg-gray-200 p-1" 
                        :class="{'cursor-not-allowed opacity-25': month == 11 }"
                        :disabled="month == 11 ? true : false"
                        @click="month++; getNoOfDays()">
                        <svg class="h-6 w-6 text-gray-500 inline-flex leading-none"  fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>									  
                    </button>
                </div>
            </div>	

            <div class="-mx-1 -mb-1">
                <div class="flex flex-wrap" style="margin-bottom: -40px;">
                    <template x-for="(day, index) in DAYS" :key="index">	
                        <div style="width: 14.26%" class="px-2 py-2">
                            <div
                                x-text="day" 
                                class="text-gray-600 text-sm uppercase tracking-wide font-bold text-center"></div>
                        </div>
                    </template>
                </div>

                <div class="flex flex-wrap border-t border-l">
                    <template x-for="blankday in blankdays">
                        <div 
                            style="width: 14.28%; height: 120px"
                            class="text-center border-r border-b px-4 pt-2"	
                        ></div>
                    </template>	
                    <template x-for="(date, dateIndex) in no_of_days" :key="dateIndex">	
                        <div style="width: 14.28%; height: 120px" class="px-4 pt-2 border-r border-b relative">
                            <div
                                @click="showEventModal(date)"
                                x-text="date"
                                class="inline-flex w-6 h-6 items-center justify-center cursor-pointer text-center leading-none rounded-full transition ease-in-out duration-100"
                                :class="{'bg-blue-500 text-white': isToday(date) == true, 'text-gray-700 hover:bg-blue-200': isToday(date) == false }"	
                            ></div>
                            <div style="height: 80px;" class="overflow-y-auto mt-1">
                                <!-- <div 
                                    class="absolute top-0 right-0 mt-2 mr-2 inline-flex items-center justify-center rounded-full text-sm w-6 h-6 bg-gray-700 text-white leading-none"
                                    x-show="events.filter(e => e.event_date === new Date(year, month, date).toDateString()).length"
                                    x-text="events.filter(e => e.event_date === new Date(year, month, date).toDateString()).length"></div> -->

                                <template x-for="event in events.filter(e => new Date(e.event_date).toDateString() ===  new Date(year, month, date).toDateString() )">	
                                    <div
                                        @click="showPostModal(date, event.event_title, event.event_hour, event.event_theme)"
                                        class="px-2 py-1 rounded-lg mt-1 cursor-pointer overflow-hidden border"
                                        :class="{
                                            'border-blue-200 text-blue-800 bg-blue-100': event.event_theme === 'blue',
                                            'border-red-200 text-red-800 bg-red-100': event.event_theme === 'red',
                                            'border-yellow-200 text-yellow-800 bg-yellow-100': event.event_theme === 'yellow',
                                            'border-green-200 text-green-800 bg-green-100': event.event_theme === 'green',
                                            'border-purple-200 text-purple-800 bg-purple-100': event.event_theme === 'purple'
                                        }"                                        
                                    >
                                        <p x-text="event.event_title" class="text-sm truncate leading-tight"></p>                                        
                                    </div>
                                </template>
                            </div>
                        </div>
                    </template>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div style=" background-color: rgba(0, 0, 0, 0.8)" class="fixed z-40 top-0 right-0 left-0 bottom-0 h-full w-full" x-show.transition.opacity="openEventModal">
        <div class="p-4 max-w-full mx-auto relative absolute left-0 right-0 overflow-auto mt-24" style="width: 1000px;">
            <div class="shadow absolute right-0 top-0 w-10 h-10 rounded-full bg-white text-gray-500 hover:text-gray-800 inline-flex items-center justify-center cursor-pointer"
                x-on:click="openEventModal = !openEventModal">
                <svg class="fill-current w-6 h-6" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                    <path
                        d="M16.192 6.344L11.949 10.586 7.707 6.344 6.293 7.758 10.535 12 6.293 16.242 7.707 17.656 11.949 13.414 16.192 17.656 17.606 16.242 13.364 12 17.606 7.758z" />
                </svg>
            </div>

            <div class="shadow w-full rounded-lg bg-white overflow-hidden w-full block p-8">
                <h2 class="font-bold text-2xl mb-6 text-gray-800 border-b pb-2">Agregar evento en Calendario</h2>
                
                @include('potenciales.modalAgenda')

                <div class="px-4 py-3 bg-gray-50 text-right sm:px-6">        
                    <button type="button" class="bg-white hover:bg-gray-100 text-gray-700 font-semibold py-2 px-4 border border-gray-300 rounded-lg shadow-sm mr-2" @click="openEventModal = !openEventModal">
                        Cancelar
                    </button>
                    <button type="button" class="bg-gray-800 hover:bg-gray-700 text-white font-semibold py-2 px-4 border border-gray-700 rounded-lg shadow-sm" @click="addEvent()">
                        Guardar
                    </button>
                </div>
            </div>
            
        </div>
    </div>
    <!-- /Modal -->



    <!-- Modal -->
    <div style=" background-color: rgba(0, 0, 0, 0.8)" class="fixed z-40 top-0 right-0 left-0 bottom-0 h-full w-full" x-show.transition.opacity="openPostModal">
        <div class="p-4 max-w-full mx-auto relative absolute left-0 right-0 overflow-auto mt-24" style="width: 1000px;">
            <div class="shadow absolute right-0 top-0 w-10 h-10 rounded-full bg-white text-gray-500 hover:text-gray-800 inline-flex items-center justify-center cursor-pointer"
                x-on:click="openPostModal = !openPostModal">
                <svg class="fill-current w-6 h-6" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                    <path
                        d="M16.192 6.344L11.949 10.586 7.707 6.344 6.293 7.758 10.535 12 6.293 16.242 7.707 17.656 11.949 13.414 16.192 17.656 17.606 16.242 13.364 12 17.606 7.758z" />
                </svg>
            </div>

            <div class="shadow w-full rounded-lg bg-white overflow-hidden w-full block p-8">
                <h2 class="font-bold text-2xl mb-6 text-gray-800 border-b pb-2">Cita Accion Comercial</h2>
                
                @include('potenciales.modalPost')

                <div class="px-4 py-3 bg-gray-50 text-right sm:px-6">        
                    <button type="button" class="bg-white hover:bg-gray-100 text-gray-700 font-semibold py-2 px-4 border border-gray-300 rounded-lg shadow-sm mr-2" @click="openPostModal = !openPostModal">
                        Cancelar
                    </button>
                    <button type="button" class="bg-gray-800 hover:bg-gray-700 text-white font-semibold py-2 px-4 border border-gray-700 rounded-lg shadow-sm" @click="addAcction()">
                        Guardar
                    </button>
                </div>
            </div>
            
        </div>
    </div>
    <!-- /Modal -->
    
</div>





<script>
    const MONTH_NAMES = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
    const DAYS = ['Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'Sabado', 'Domingo'];
    let datosTitulo;
    var tiempo = new Date();
    var hora = tiempo.getHours() + ':' + tiempo.getMinutes()

    function sleep(ms) {
        return new Promise(resolve => setTimeout(resolve, ms));
        }

    function app() {
        return {
            month: '',
            year: '',
            no_of_days: [],
            blankdays: [],
            days: ['Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'Sabado', 'Domingo'],

            events: [                
            ],

            event_title: '',
            event_date: '',
            event_hour: '',
            event_theme: 'red',

            themes: [
                {
                    value: "red",
                    label: "Pendiente"
                },
                {
                    value: "blue",
                    label: "Abierto"
                },                
                {
                    value: "green",
                    label: "Cerrado"
                }
            ],

            openEventModal: false,
            openPostModal: false,

            async initDate() {
                let today = new Date();
                this.month = today.getMonth();
                //this.year = 2022;                
                this.year = today.getFullYear();
                this.datepickerValue = new Date(this.year, this.month, today.getDate()).toDateString();

                
                var citas = [];
                datos={                                       
                    "_token": $("meta[name='csrf-token']").attr("content")
                };

                $.ajax({
                    url: './citasCalendario',
                    data: datos,
                    type: 'post',
                    timeout: 2000,
                    async: true,
                    success: function(result) {                        
                        console.log(result);                        
                        citas = result;
                    }
                });

                await sleep(400);
                var theme;
                citas.forEach(                    
                    cit =>{
                        if(cit.StatusTareaLc == 0){
                            this.events.push({
                                event_date: cit.FechaInicialLc,
                                event_hour: cit.HoraInicialLc,
                                event_title: cit.CodigoCategoriaCliente_+'-'+cit.NombreContactoLc+'-'+cit.CodigoCliente,
                                event_theme: 'red'
                            })
                        }
                        if(cit.StatusTareaLc == 1){
                            this.events.push({
                                event_date: cit.FechaInicialLc,
                                event_hour: cit.HoraInicialLc,
                                event_title: cit.CodigoCategoriaCliente_+'-'+cit.NombreContactoLc+'-'+cit.CodigoCliente,
                                event_theme: 'blue'
                            })
                        }
                        if(cit.StatusTareaLc == 3){
                            this.events.push({
                                event_date: cit.FechaInicialLc,
                                event_hour: cit.HoraInicialLc,
                                event_title: cit.CodigoCategoriaCliente_+'-'+cit.NombreContactoLc+'-'+cit.CodigoCliente,
                                event_theme: 'green'
                            })
                        }
                    }
                ); 
                
                // for(var i=0; i<citas; i++){
                            
                //             this.events.push({
                //                 event_date: 'Thu Oct 14 2021',
                //                 event_hour: '16:00',
                //                 event_title: '1',
                //                 event_theme: 'red'
                //             });
                //             console.log(i);
                //         }       
                        
                await sleep(1000);
    
                   // this.events = citas;
                //console.log(this.events);
                //console.log(this.events[0].event_title);

                    
            },

            isToday(date) {
                const today = new Date();
                const d = new Date(this.year, this.month, date);

                return today.toDateString() === d.toDateString() ? true : false;
            },

            showEventModal(date) {
                // open the modal
                this.openEventModal = true;                
                this.event_date = new Date(this.year, this.month, date).toDateString();                
                //console.log(this.month);
                this.month = parseInt(this.month);                
                //this.month = this.month+1;
                var mes = this.month+1;
                if(mes<10)
                    mes ='0'+(this.month+1);
                   // this.month='0'+this.month; //agrega cero si el menor de 10
                if(date<10)
                    date='0'+date //agrega cero si el menor de 10
                console.log(date+'-'+mes+'-'+this.year);
                $('#fechaInicio').val(this.year+'-'+mes+'-'+date);
                $('#fechaFin').val(this.year+'-'+mes+'-'+date);
                //var fecha = this.year+'-'+this.month+'-'+date;
                //fechiInicio(fecha);
            },

            showPostModal (date, datos, hora, tema) {
                // open the modal                
                datosTitulo = datos;                
                if(tema == 'green'){
                    this.openPostModal = true; 

                    var partido = datos.split('-', 3);                
                    this.month = parseInt(this.month);                                
                    var mes = this.month+1;
                    if(mes<10)
                        mes ='0'+(this.month+1);
                    if(date<10)
                        date='0'+date //agrega cero si el menor de 10
                    var fechaFormada = this.year+'-'+mes+'-'+date;
                    console.log(partido);

                    datos={
                        'tipo':partido[0],
                        'codigo':partido[2],
                        'fecha':fechaFormada,
                        'hora':hora,
                        "_token": $("meta[name='csrf-token']").attr("content")
                    };
                    $.ajax({
                        url: './accionAgendaBlue',
                        data: datos,
                        type: 'post',
                        timeout: 2000,
                        async: true,
                        success: function(result) {
                            console.log(result);                    
                        $('#codigoModal').val(result.agenda[0].CodigoCliente+'-'+result.agenda[0].NombreContactoLc);
                        $('#fechaModal').val(fechaFormada);
                        $('#horaModal').val(result.agenda[0].HoraInicialLc);
                        $('#accionComercialPost').val(result.agenda[0].CodigoAccionComercialLc);       
                        $('#temasComercialesPost').val(result.agenda[0].CodigoTemaComercialLc);       
                        $('#prioridadPost').val(result.agenda[0].CodigoTipoPrioridadLc);       
                        $('#themePost').val(tema);
                        $('#objetivoPost').val(result.agenda[0].Observaciones);
                        $('#codigoClientePost').val(result.agenda[0].CodigoCliente);
                        $('#codigoClienteCategoriaPost').val(result.agenda[0].CodigoCategoriaCliente_);
                        $('#accionPosicionLcPost').val(result.agenda[0].AccionPosicionLc);
                        $('#idDelegacionPost').val(result.agenda[0].IdDelegacion);
                        $('#CodigoGrupoComercialPost').val(result.agenda[0].CodigoGrupoComercialLc);
                        $('#resultadoPost').val(result.accion[0].Observaciones);
                        $('#estadoBandera').val(tema);
                        $('#estadoPost').val(result.agenda[0].StatusTareaLc);
                        $('#estadoPost').prop('disabled', true);
                        $('#themePost').prop('disabled', true);
                        $('#horaModal').prop('disabled', true);                                                
                        $('#resultadoPost').prop('disabled', true);

                        
                        }          
                    });
                }
                if(tema == 'blue'){
                    this.openPostModal = true;
                    
                    var partido = datos.split('-', 3);                
                    this.month = parseInt(this.month);                                
                    var mes = this.month+1;
                    if(mes<10)
                        mes ='0'+(this.month+1);
                    if(date<10)
                        date='0'+date //agrega cero si el menor de 10
                    var fechaFormada = this.year+'-'+mes+'-'+date;
                    console.log(partido);

                    datos={
                        'tipo':partido[0],
                        'codigo':partido[2],
                        'fecha':fechaFormada,
                        'hora':hora,
                        "_token": $("meta[name='csrf-token']").attr("content")
                    };
                    $.ajax({
                        url: './accionAgendaBlue',
                        data: datos,
                        type: 'post',
                        timeout: 2000,
                        async: true,
                        success: function(result) {
                            console.log(result);                    
                        $('#codigoModal').val(result.agenda[0].CodigoCliente+'-'+result.agenda[0].NombreContactoLc);
                        $('#fechaModal').val(fechaFormada);
                        $('#horaModal').val(result.agenda[0].HoraInicialLc);
                        $('#accionComercialPost').val(result.agenda[0].CodigoAccionComercialLc);       
                        $('#temasComercialesPost').val(result.agenda[0].CodigoTemaComercialLc);       
                        $('#prioridadPost').val(result.agenda[0].CodigoTipoPrioridadLc);       
                        $('#themePost').val(tema);
                        $('#objetivoPost').val(result.agenda[0].Observaciones);
                        $('#codigoClientePost').val(result.agenda[0].CodigoCliente);
                        $('#codigoClienteCategoriaPost').val(result.agenda[0].CodigoCategoriaCliente_);
                        $('#accionPosicionLcPost').val(result.agenda[0].AccionPosicionLc);
                        $('#idDelegacionPost').val(result.agenda[0].IdDelegacion);
                        $('#CodigoGrupoComercialPost').val(result.agenda[0].CodigoGrupoComercialLc);
                        $('#resultadoPost').val(result.accion[0].Observaciones);
                        $('#estadoBandera').val(tema);
                        $('#estadoPost').val(result.agenda[0].StatusTareaLc);
                        
                        }          
                    });
                }
                if(tema == 'red'){                    
                    this.openPostModal = true; 
                    //console.log(hora);               
                    var partido = datos.split('-', 3);                
                    this.month = parseInt(this.month);                                
                    var mes = this.month+1;
                    if(mes<10)
                        mes ='0'+(this.month+1);
                    if(date<10)
                        date='0'+date //agrega cero si el menor de 10
                    var fechaFormada = this.year+'-'+mes+'-'+date;
                    console.log(partido);

                    datos={
                        'tipo':partido[0],
                        'codigo':partido[2],
                        'fecha':fechaFormada,
                        'hora':hora,
                        "_token": $("meta[name='csrf-token']").attr("content")
                    };
                    $.ajax({
                        url: './accionAgenda',
                        data: datos,
                        type: 'post',
                        timeout: 2000,
                        async: true,
                        success: function(result) {
                            console.log(result);                    
                        $('#codigoModal').val(result[0].CodigoCliente+'-'+result[0].NombreContactoLc);
                        $('#fechaModal').val(fechaFormada);
                        $('#horaModal').val(result[0].HoraInicialLc);
                        $('#accionComercialPost').val(result[0].CodigoAccionComercialLc);       
                        $('#temasComercialesPost').val(result[0].CodigoTemaComercialLc);       
                        $('#prioridadPost').val(result[0].CodigoTipoPrioridadLc);       
                        $('#themePost').val(tema);
                        $('#objetivoPost').val(result[0].Observaciones);
                        $('#codigoClientePost').val(result[0].CodigoCliente);
                        $('#codigoClienteCategoriaPost').val(result[0].CodigoCategoriaCliente_);
                        $('#accionPosicionLcPost').val(result[0].AccionPosicionLc);
                        $('#idDelegacionPost').val(result[0].IdDelegacion);
                        $('#CodigoGrupoComercialPost').val(result[0].CodigoGrupoComercialLc);
                        $('#estadoBandera').val(tema);
                        $('#estadoPost').val(result[0].StatusTareaLc);

                        }          
                    });
                }
            },

            addEvent() {
                if (this.event_title == '') {
                    this.event_title = $('#tituloAgenda').val();
                    console.log($('#tituloAgenda').val());
                    if(this.event_title == '') {
                    //console.log('titulo vacio')
                    return;
                    }
                }

                //console.log('hola');

                this.events.push({
                    event_date: this.event_date,
                    event_hour: this.event_hour,
                    event_title: this.event_title,
                    event_theme: this.event_theme
                });

                console.log(this.events);

                // clear the form data
                this.event_title = '';
                this.event_date = '';
                this.event_hour = '';
                this.event_theme = 'red';

                //close the modal
                this.openEventModal = false;

                seguimiento();
            },

            addAcction(){
                let llega = accion();
                console.log(llega);
                console.log(llega[2].toDateString);
               
                for(let i = 0; i<this.events.length; i++){
                    if(this.events[i].event_title == llega[0] && this.events[i].event_date == llega[2]+' 00:00:00.000' && this.events[i].event_hour == llega[3] || this.events[i].event_title == llega[0] && this.events[i].event_hour == llega[3]){
                        console.log('coincidencia completa')
                        if(llega[1] == '0'){
                            this.events[i].event_theme = 'blue';
                        }
                        if(llega[1] == '1'){
                            this.events[i].event_theme = 'blue';
                        }     
                        if(llega[1] == '3'){
                            this.events[i].event_theme = 'green';
                        }                         
                    }
                }
                console.log(this.events);
                             
                //close the modal
                this.openPostModal = false;
            },

            getNoOfDays() {
                let daysInMonth = new Date(this.year, this.month + 1, 0).getDate();

                // find where to start calendar day of week
                let dayOfWeek = new Date(this.year, this.month).getDay();
                let blankdaysArray = [];
                for ( var i=2; i <= dayOfWeek; i++) {
                    blankdaysArray.push(i);
                }

                let daysArray = [];
                for ( var i=1; i <= daysInMonth; i++) {
                    daysArray.push(i);
                }
                
                this.blankdays = blankdaysArray;
                this.no_of_days = daysArray;
            }

            
        }
        
    }

    function selectCodigoAgenda(id,codigo,tipo,nombre){
        //console.log(id);        
        //document.getElementById('titulo').text= tipo+'-'+nombre;
        //$('#tituloAgenda').writevalue(tipo+'-'+nombre);
        $('#tituloAgenda').val(tipo+'-'+nombre+'-'+codigo);        
        $('#comisionistaOculto').val(codigo);
        $('#codigoCategoriaCliente').val(tipo);
        $('#agendaInput').val(tipo+"-"+nombre)       
        $(".angendaResultado-box").hide();        
    }
    

    
    function estado(estado){
        console.log(estado);
        if(estado == 'red'){
           $('#estado').val(0);
        }else if(estado == 'blue'){
            $('#estado').val(1);
        }else if(estado == 'green'){
            $('#estado').val(3);
        }0
    }

    function estadoPost(estado){
        console.log(estado);
        if(estado == 'red'){
           $('#estadoPost').val(0);
        }else if(estado == 'blue'){
            $('#estadoPost').val(1);
        }else if(estado == 'green'){
            $('#estadoPost').val(3);
        }0
    }
    

    function fechiInicio(date){
        console.log(date);
        $('#fechaInicio').val(date);

    }

    function limpiar(valor){        
            $('#codigoOculto').val('')        
    }

    function temaComercialA(valor ){
        
        var temaComercial;
        datos={
            'accion':valor,
            "_token": $("meta[name='csrf-token']").attr("content")
        };
        $.ajax({
        url: './temaComercial',
        data: datos,
        type: 'post',
        timeout: 2000,
        async: true,
        success: function(result) {
           //console.log(result);
           temaComercial = result;
           $('#temasComerciales').empty();
           var html2 = '<label for="last-name" class="block text-sm font-medium text-gray-700">Tema Comercial</label>'+
            '<select id="temaComercial" name="temaComercial" class="mt-1 block w-full  border border-gray-300 bg-white rounded-md shadow-md ">'+
                '<option value=""></option>';
                for(let l = 0; l<temaComercial.length; l++){ html2 +='<option value="' +temaComercial[l].CodigoTemaComercialLc+'">'+temaComercial[l].TemaComercialLc+'</option>';
                    }
            html2 +='</select>';
            $('#temasComerciales').append(html2);
        }
    });
        
    }
        
     

    function seguimiento(){
        datos={
            'fechaInicio': $('#fechaInicio').val(),
            'horaInicio': $('#horaInicio').val(),
            'fechaFin': $('#fechaFin').val(),
            'horaFin': $('#horaFin').val(),
            'comisionistaOculto': $('#comisionistaOculto').val(),
            'accionComercial': $('#accionComercial').val(),
            'temaComercial':$('#temaComercial').val(),
            'estado': $('#estado').val(),
            'prioridad': $('#prioridad').val(),
            'objetivo': $('#objetivo').val(),
            'codigoCategoriaCliente':$('#codigoCategoriaCliente').val(),
            //'resultado': $('#resultado'+id+'').val(),
            "_token": $("meta[name='csrf-token']").attr("content")
        };
        $.ajax({
        url: './seguimiento',
        data: datos,
        type: 'post',
        timeout: 2000,
        async: true,
        success: function(result) {
           //console.log(result);
           
        }
    });
        console.log(datos);
    }

    function accion(){
        datos={
            'fechaInicio': $('#fechaModal').val(),
            'horaInicio': $('#horaModal').val(),
            'fechaFin': $('#fechaModal').val(),
            //'horaFin': $('#horaFin').val(),
            'comisionistaOculto': $('#codigoClientePost').val(),
            'accionComercial': $('#accionComercialPost').val(),
            'temaComercial':$('#temasComercialesPost').val(),
            'estado': $('#estadoPost').val(),
            'estadoBandera': $('#estadoBandera').val(),            
            'prioridad': $('#prioridad').val(),
            //'objetivo': $('#objetivo').val(),
            'codigoCategoriaCliente':$('#codigoClienteCategoriaPost').val(),
            'resultado': $('#resultadoPost').val(),
            'accionPosicionLcPost': $('#accionPosicionLcPost').val(),
            'idDelegacionPost': $('#idDelegacionPost').val(),
            'codigoClienteCategoriaPost': $('#codigoClienteCategoriaPost').val(),
            'CodigoGrupoComercialPost': $('#CodigoGrupoComercialPost').val(),
            "_token": $("meta[name='csrf-token']").attr("content")
        };
        $.ajax({
            url: './guardarUpdatearAccion',
            data: datos,
            type: 'post',
            timeout: 2000,
            async: true,
            success: function(result) {
                //console.log(result);
            }
        });
        console.log(datos);                
        var devolver = [datosTitulo, $('#estadoPost').val(), $('#fechaModal').val(), $('#horaModal').val()];
        return devolver; 
    }

</script>
</div>
    </div>
</div>

<?php 
    }
?>
@livewireScripts
@include('layouts.footer')
@include('layouts.panels')

