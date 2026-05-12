
      document.addEventListener('DOMContentLoaded', function() {

        let formulario = document.querySelector('#formularioEventos');        
        var calendarEl = document.getElementById('agenda');
        var calendar = new FullCalendar.Calendar(calendarEl, {
          initialView: 'dayGridMonth',
          locale:'es',
          now: Date.now(),
          nowIndicator: true,                    
          navLinks: true,
          selectable: true,
          editable: true,
          dayMaxEvents: true,          
          headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,timeGridDay,listYear',                    
          },       
          eventSources:{              
              url: 'https://cronadis.abmscloud.com/calendario/mostrar',
              metohd: "GET",
              extraParams: {                  
                  _token: formulario._token,    
              }
          },             

            dateClick: function(info) {
                console.log(info)
                formulario.reset();
                formulario.start.value=info.dateStr;
                formulario.end.value=info.dateStr;
                formulario.startH.value = '00:00:00.000';
                formulario.endH.value = '23:59:00.000';
                $('#accionComercial2').show();
                $('#temasComerciales').show();
                $('#resultados').hide();
                $('#btnModificar').hide();
                $('#btnEliminar').hide();
                $('#btnGuardar').show();
                $('#evento').removeClass("hidden");
            },

            select: function(info) {
                console.log(info)
                formulario.reset();
                var start = info.startStr;                
                var end = info.endStr;            
                start = start.split(/T|[+]/);                         
                formulario.start.value=start[0];
                formulario.startH.value=start[1];            
                end = end.split(/T|[+]/);
                formulario.end.value=end[0];
                formulario.endH.value=end[1];
                $('#accionComercial2').show();
                $('#temasComerciales').show();
                $('#resultados').hide();
                $('#btnModificar').hide();
                $('#btnEliminar').hide();
                $('#btnGuardar').show();
                $('#evento').removeClass("hidden");
            },

            eventClick: function(info){
                var evento = info.event.extendedProps.publicId;
                formulario.reset();
                $('#accionComercial2').hide();
                $('#temasComerciales').hide();                
                $('#resultados').show();
                $('#btnModificar').show();
                $('#btnEliminar').show();
                $('#btnGuardar').hide();
                console.log(evento);
                axios.post('https://cronadis.abmscloud.com/calendario/editar/'+evento).
                then(
                    (respuesta)=>{
                        //console.log(respuesta.data[0].AccionPosicionLc);
                        formulario.accionPosicionId.value=respuesta.data[0].AccionPosicionLc;
                        formulario.agendaInput.value=respuesta.data[0].title;
                        formulario.tituloAgenda.value=respuesta.data[0].title;
                        formulario.codigoCategoriaCliente.value=respuesta.data[0].CodigoCategoriaCliente_;
                        formulario.comisionistaOculto.value=respuesta.data[0].CodigoCliente;
                        formulario.objetivo.value=respuesta.data[0].Observaciones;
                        formulario.start.value=respuesta.data[0].FechaInicialLc;
                        formulario.end.value=respuesta.data[0].FechaFinalLc;
                        formulario.startH.value=respuesta.data[0].HoraInicialLc;
                        formulario.endH.value=respuesta.data[0].HoraFinalLc;
                        formulario.color.value=respuesta.data[0].BgColor;
                        formulario.textColor.value=respuesta.data[0].TxColor;                        
                        formulario.estado.value=respuesta.data[0].StatusTareaLc;
                        formulario.prioridad.value=respuesta.data[0].CodigoTipoPrioridadLc;
                        // formulario.accionComercial2.value=respuesta.data[0].CodigoAccionComercialLc;
                        // formulario.temaComercial2.value=respuesta.data[0].CodigoTemaComercialLc;

                        $('#evento').removeClass("hidden");
                        //$('#evento').show();
                        
                    }
                ).catch(
                    error=>{
                        if(error.response){
                            console.log(error.response.data);
                        }
                    }
                )
            },
            
            eventChange: function(info){
                console.log(info.event.startStr);
                console.log(info);
                
                formulario.reset();
                var start = info.event.startStr;                
                var end = info.event.endStr; 
                formulario.accionPosicionId.value = info.event.extendedProps.publicId;
                formulario.comisionistaOculto.value = info.event.extendedProps.CodigoCliente;
                start = start.split(/T|[+]/);                         
                formulario.start.value=start[0];
                formulario.startH.value=start[1];            
                end = end.split(/T|[+]/);
                formulario.end.value=end[0];
                formulario.endH.value=end[1];
    
                $('#btnMoveDate').trigger( "click" );        
            },            
            //events: 'https://fullcalendar.io/api/demo-feeds/events.json?overload-day'
        });
        
        calendar.render();

        document.getElementById('btnGuardar').addEventListener('click', function(){
            enviarDatos("/calendario/agregar");
        });

        document.getElementById('btnEliminar').addEventListener('click', function(){
            enviarDatos("/calendario/borrar/"+formulario.accionPosicionId.value);
        });

        document.getElementById('btnModificar').addEventListener('click', function(){
            enviarDatos("/calendario/actualizar/"+formulario.accionPosicionId.value);
        });

        document.getElementById('btnMoveDate').addEventListener('click', function(){
            enviarDatos("/calendario/move/"+formulario.accionPosicionId.value);
        });

        document.getElementById('btnCerrar').addEventListener('click', function(){
            $('#evento').addClass('hidden');
        });

        document.getElementById('cerrar').addEventListener('click', function(){
            $('#evento').addClass('hidden');
        });
        
        function enviarDatos(url){
            const datos = new FormData(formulario);
            console.log(url)
            nuevaURL = url;
        
            axios.post(nuevaURL, datos).
            then(
                    (respuesta)=>{
                        calendar.refetchEvents();
                        $('#evento').modal('hide');
                    }
                ).catch(
                    error=>{
                        if(error.response){console.log(error.response.data);}
                    }
                )
        }

      });