<style>
.tooltip{
  visibility: hidden;
  position: absolute;
}
.has-tooltip:hover .tooltip {
  visibility: visible;
  z-index: 100;
}
/* smartphones, touchscreens */
@media (hover: none) and (pointer: coarse) {
    .has-tooltip:hover .tooltip{
    visibility: hidden;    
    }
}
</style>
<div class="flex space-x-1 justify-around">    

    <!-- <a href="{{--{{ route('prescriptor.edit', [$CodigoCliente]) }}--}}" target="_blank" class="p-1 text-blue-600 hover:bg-blue-600 hover:text-white rounded">
        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"></path></svg>
    </a> -->
    @php
        $randomKey = time();
    @endphp
    
    <div x-data="{ show: false }">
        <div class="flex justify-center">
        <button @click={show=true} type="button" class="p-1 text-blue-600 hover:bg-blue-600 hover:text-white rounded has-tooltip">
            <span class='tooltip rounded shadow-lg p-1 bg-black text-white-500 -mt-8'>Editar Potencial</span>
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"></path></svg>
        </button>
        </div>

        <div x-show="show" tabindex="0" class="z-40 overflow-auto left-0 top-0 bottom-0 right-0 w-full h-full fixed">
            <div  @click.away="show = false" class="z-50 relative p-3 mx-auto my-0 max-w-full" style="width: 1000px;">
                <div class="bg-white rounded shadow-lg border flex flex-col overflow-hidden">
                    <button @click={show=false} class="fill-current h-6 w-6 absolute right-0 top-0 m-6 font-3xl font-bold">&times;</button>
                    <div class="px-6 py-3 text-xl border-b font-bold">Editar Potencial</div>
                        <div class="p-6 flex-grow">
                            
                            @include('potenciales.edit', ['codigoCliente'=>$IdCliente, 'IdCliente'=>$CodigoCliente]) 

                        </div>
                    <div class="px-6 py-3 border-t">
                        <div class="flex justify-end">
                            <button @click={show=false} type="button" class="bg-primary text-gray-100 rounded px-4 py-2 mr-1">Cerrar</Button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="z-40 overflow-auto left-0 top-0 bottom-0 right-0 w-full h-full absolute bg-black opacity-50"></div>
        </div>
    </div>

    <div x-data="{ show: false }">
        <div class=" flex justify-center">
            <button @click={show=true} type="button" class="p-1 text-yellow-600  hover:bg-yellow-600 hover:text-white rounded has-tooltip">
                <span class='tooltip rounded shadow-lg p-1 bg-black text-white-500 -mt-8'>Crear Seguimiento</span>
                <!-- <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M 5.9667969 3 C 4.8922226 3 4 3.8922226 4 4.9667969 L 4 7.3867188 C 3.6700827 7.5693007 3.3668503 7.8022905 3.1230469 8.1015625 C 2.5561678 8.7967647 2.3299502 9.7121994 2.5097656 10.591797 L 2.5097656 10.59375 C 3.0409792 13.178038 3.7794422 16.772026 3.8261719 17 C 3.7795089 17.227574 3.0439586 20.806083 2.5136719 23.390625 C 2.33239 24.274562 2.5603689 25.193876 3.1289062 25.892578 L 3.1289062 25.894531 C 3.6989449 26.593193 4.5544182 27 5.4570312 27 L 24.501953 27 C 25.414029 27 26.27875 26.588847 26.855469 25.882812 L 26.855469 25.880859 C 27.431397 25.174017 27.661627 24.244791 27.478516 23.351562 C 26.948342 20.768542 26.220293 17.226607 26.173828 17 C 26.220578 16.771923 26.959908 13.172625 27.490234 10.587891 C 27.670205 9.7105358 27.445474 8.7948488 26.878906 8.0996094 L 26.876953 8.0976562 C 26.633016 7.7986233 26.330145 7.566746 26 7.3847656 L 26 4.9667969 C 26 3.8922226 25.107777 3 24.033203 3 L 5.9667969 3 z M 6 5 L 24 5 L 24 7 L 6 7 L 6 5 z M 12.105469 13.080078 C 12.565469 13.080078 12.985281 13.135094 13.363281 13.246094 C 13.742281 13.357094 14.067844 13.522188 14.339844 13.742188 C 14.611844 13.962188 14.823609 14.234594 14.974609 14.558594 C 15.125609 14.882594 15.199219 15.258547 15.199219 15.685547 C 15.199219 15.882547 15.170375 16.076531 15.109375 16.269531 C 15.048375 16.461531 14.958844 16.6425 14.839844 16.8125 C 14.791844 16.8805 14.720063 16.937953 14.664062 17.001953 L 11.066406 17.001953 L 11.066406 18.390625 L 12.03125 18.390625 C 12.26125 18.390625 12.46925 18.41675 12.65625 18.46875 C 12.84225 18.52075 13.000859 18.601937 13.130859 18.710938 C 13.260859 18.819938 13.358734 18.960766 13.427734 19.134766 C 13.496734 19.308766 13.53125 19.515812 13.53125 19.757812 C 13.53125 19.945812 13.5005 20.116531 13.4375 20.269531 C 13.3745 20.422531 13.283062 20.553063 13.164062 20.664062 C 13.045063 20.775063 12.899516 20.861922 12.728516 20.919922 C 12.556516 20.978922 12.365344 21.007813 12.152344 21.007812 C 11.959344 21.007812 11.781187 20.978922 11.617188 20.919922 C 11.454187 20.860922 11.313359 20.780734 11.193359 20.677734 C 11.074359 20.574734 10.981062 20.452547 10.914062 20.310547 C 10.847062 20.168547 10.814453 20.011844 10.814453 19.839844 L 9 19.839844 C 9 20.291844 9.0895781 20.680766 9.2675781 21.009766 C 9.4455781 21.337766 9.6797031 21.610125 9.9707031 21.828125 C 10.261703 22.046125 10.591844 22.2085 10.964844 22.3125 C 11.336844 22.4175 11.718422 22.46875 12.107422 22.46875 C 12.567422 22.46875 12.994625 22.408062 13.390625 22.289062 C 13.785625 22.170063 14.128969 21.997531 14.417969 21.769531 C 14.706969 21.541531 14.932656 21.260781 15.097656 20.925781 C 15.262656 20.590781 15.345703 20.210203 15.345703 19.783203 C 15.345703 19.281203 15.219797 18.846516 14.966797 18.478516 C 14.713797 18.110516 14.329453 17.834391 13.814453 17.650391 C 14.036453 17.550391 14.232297 17.427203 14.404297 17.283203 C 14.507297 17.196203 14.581016 17.096953 14.666016 17.001953 L 19.185547 17.001953 L 19.185547 22.34375 L 21 22.34375 L 21 17 L 19.185547 17 L 19.185547 15.353516 L 17.019531 16.025391 L 17.019531 14.550781 L 20.804688 13.193359 L 21 13.193359 L 21 17 L 24.132812 17 L 24.173828 17.201172 C 24.173828 17.201172 24.970539 21.088714 25.517578 23.753906 C 25.579608 24.059411 25.503358 24.374804 25.306641 24.617188 C 25.107355 24.861152 24.815878 25 24.501953 25 L 5.4570312 25 C 5.1536443 25 4.871649 24.864198 4.6796875 24.628906 C 4.4891549 24.393814 4.4120716 24.088382 4.4726562 23.792969 C 5.0196814 21.126846 5.8261719 17.201172 5.8261719 17.201172 L 5.8671875 17 L 11.064453 17 L 12 17 C 12.353 17 12.833687 16.856953 13.054688 16.626953 C 13.275688 16.396953 13.386719 16.090938 13.386719 15.710938 C 13.386719 15.543938 13.3625 15.387188 13.3125 15.242188 C 13.2625 15.098187 13.184031 14.973094 13.082031 14.871094 C 12.979031 14.769094 12.852266 14.689859 12.697266 14.630859 C 12.542266 14.571859 12.359391 14.542969 12.150391 14.542969 C 11.983391 14.542969 11.826734 14.565281 11.677734 14.613281 C 11.529734 14.661281 11.398109 14.730359 11.287109 14.818359 C 11.176109 14.906359 11.088437 15.013625 11.023438 15.140625 C 10.958438 15.268625 10.927734 15.411313 10.927734 15.570312 L 9.1132812 15.570312 C 9.1132812 15.193312 9.1906562 14.851922 9.3476562 14.544922 C 9.5046563 14.237922 9.7182812 13.976719 9.9882812 13.761719 C 10.258281 13.545719 10.575453 13.378766 10.939453 13.259766 C 11.303453 13.140766 11.691469 13.080078 12.105469 13.080078 z"/></svg> -->
                <svg fill="currentColor" xmlns="http://www.w3.org/2000/svg"  viewBox="0 0 30 30" width="25px" height="25px"><path d="M 5.9667969 3 C 4.8922226 3 4 3.8922226 4 4.9667969 L 4 7.3867188 C 3.6700827 7.5693007 3.3668503 7.8022905 3.1230469 8.1015625 C 2.5561678 8.7967647 2.3299502 9.7121994 2.5097656 10.591797 L 2.5097656 10.59375 C 3.0409792 13.178038 3.7794422 16.772026 3.8261719 17 C 3.7795089 17.227574 3.0439586 20.806083 2.5136719 23.390625 C 2.33239 24.274562 2.5603689 25.193876 3.1289062 25.892578 L 3.1289062 25.894531 C 3.6989449 26.593193 4.5544182 27 5.4570312 27 L 24.501953 27 C 25.414029 27 26.27875 26.588847 26.855469 25.882812 L 26.855469 25.880859 C 27.431397 25.174017 27.661627 24.244791 27.478516 23.351562 C 26.948342 20.768542 26.220293 17.226607 26.173828 17 C 26.220578 16.771923 26.959908 13.172625 27.490234 10.587891 C 27.670205 9.7105358 27.445474 8.7948488 26.878906 8.0996094 L 26.876953 8.0976562 C 26.633016 7.7986233 26.330145 7.566746 26 7.3847656 L 26 4.9667969 C 26 3.8922226 25.107777 3 24.033203 3 L 5.9667969 3 z M 6 5 L 24 5 L 24 7 L 6 7 L 6 5 z M 12.105469 13.080078 C 12.565469 13.080078 12.985281 13.135094 13.363281 13.246094 C 13.742281 13.357094 14.067844 13.522188 14.339844 13.742188 C 14.611844 13.962188 14.823609 14.234594 14.974609 14.558594 C 15.125609 14.882594 15.199219 15.258547 15.199219 15.685547 C 15.199219 15.882547 15.170375 16.076531 15.109375 16.269531 C 15.048375 16.461531 14.958844 16.6425 14.839844 16.8125 C 14.791844 16.8805 14.720063 16.937953 14.664062 17.001953 L 11.066406 17.001953 L 11.066406 18.390625 L 12.03125 18.390625 C 12.26125 18.390625 12.46925 18.41675 12.65625 18.46875 C 12.84225 18.52075 13.000859 18.601937 13.130859 18.710938 C 13.260859 18.819938 13.358734 18.960766 13.427734 19.134766 C 13.496734 19.308766 13.53125 19.515812 13.53125 19.757812 C 13.53125 19.945812 13.5005 20.116531 13.4375 20.269531 C 13.3745 20.422531 13.283062 20.553063 13.164062 20.664062 C 13.045063 20.775063 12.899516 20.861922 12.728516 20.919922 C 12.556516 20.978922 12.365344 21.007813 12.152344 21.007812 C 11.959344 21.007812 11.781187 20.978922 11.617188 20.919922 C 11.454187 20.860922 11.313359 20.780734 11.193359 20.677734 C 11.074359 20.574734 10.981062 20.452547 10.914062 20.310547 C 10.847062 20.168547 10.814453 20.011844 10.814453 19.839844 L 9 19.839844 C 9 20.291844 9.0895781 20.680766 9.2675781 21.009766 C 9.4455781 21.337766 9.6797031 21.610125 9.9707031 21.828125 C 10.261703 22.046125 10.591844 22.2085 10.964844 22.3125 C 11.336844 22.4175 11.718422 22.46875 12.107422 22.46875 C 12.567422 22.46875 12.994625 22.408062 13.390625 22.289062 C 13.785625 22.170063 14.128969 21.997531 14.417969 21.769531 C 14.706969 21.541531 14.932656 21.260781 15.097656 20.925781 C 15.262656 20.590781 15.345703 20.210203 15.345703 19.783203 C 15.345703 19.281203 15.219797 18.846516 14.966797 18.478516 C 14.713797 18.110516 14.329453 17.834391 13.814453 17.650391 C 14.036453 17.550391 14.232297 17.427203 14.404297 17.283203 C 14.507297 17.196203 14.581016 17.096953 14.666016 17.001953 L 19.185547 17.001953 L 19.185547 22.34375 L 21 22.34375 L 21 17 L 19.185547 17 L 19.185547 15.353516 L 17.019531 16.025391 L 17.019531 14.550781 L 20.804688 13.193359 L 21 13.193359 L 21 17 L 24.132812 17 L 24.173828 17.201172 C 24.173828 17.201172 24.970539 21.088714 25.517578 23.753906 C 25.579608 24.059411 25.503358 24.374804 25.306641 24.617188 C 25.107355 24.861152 24.815878 25 24.501953 25 L 5.4570312 25 C 5.1536443 25 4.871649 24.864198 4.6796875 24.628906 C 4.4891549 24.393814 4.4120716 24.088382 4.4726562 23.792969 C 5.0196814 21.126846 5.8261719 17.201172 5.8261719 17.201172 L 5.8671875 17 L 11.064453 17 L 12 17 C 12.353 17 12.833687 16.856953 13.054688 16.626953 C 13.275688 16.396953 13.386719 16.090938 13.386719 15.710938 C 13.386719 15.543938 13.3625 15.387188 13.3125 15.242188 C 13.2625 15.098187 13.184031 14.973094 13.082031 14.871094 C 12.979031 14.769094 12.852266 14.689859 12.697266 14.630859 C 12.542266 14.571859 12.359391 14.542969 12.150391 14.542969 C 11.983391 14.542969 11.826734 14.565281 11.677734 14.613281 C 11.529734 14.661281 11.398109 14.730359 11.287109 14.818359 C 11.176109 14.906359 11.088437 15.013625 11.023438 15.140625 C 10.958438 15.268625 10.927734 15.411313 10.927734 15.570312 L 9.1132812 15.570312 C 9.1132812 15.193312 9.1906562 14.851922 9.3476562 14.544922 C 9.5046563 14.237922 9.7182812 13.976719 9.9882812 13.761719 C 10.258281 13.545719 10.575453 13.378766 10.939453 13.259766 C 11.303453 13.140766 11.691469 13.080078 12.105469 13.080078 z"/></svg>
            </button>
        </div>
        <div x-show="show" tabindex="0" class="z-40 overflow-auto left-0 top-0 bottom-0 right-0 w-full h-full fixed">
            <div  @click.away="show = false" class="z-50 relative p-3 mx-auto my-0 max-w-full" style="width: 1000px;">
                <div class="bg-white rounded shadow-lg border flex flex-col overflow-hidden">
                    <button @click={show=false} class="fill-current h-6 w-6 absolute right-0 top-0 m-6 font-3xl font-bold">&times;</button>
                    <div class="px-6 py-3 text-xl border-b font-bold">Crear Seguimiento</div>
                    
                    @include('potenciales.crearSeguimiento', ['codigoCliente'=>$CodigoCliente, 'IdCliente'=>$IdCliente])                                        
                                                                            
                </div>
            </div>
            <div class="z-40 overflow-auto left-0 top-0 bottom-0 right-0 w-full h-full absolute bg-black opacity-50"></div>
        </div>
    </div>

    <div x-data="{ show: false }">
        <div class=" flex justify-center">
            <button @click={show=true} type="button" class="p-1 text-green-600 hover:bg-green-600 hover:text-white rounded has-tooltip">
                <span class='tooltip rounded shadow-lg p-1 bg-black text-white-500 -mt-8'>Acciones Comerciales</span>
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 30 30"  xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M24.707,8.793l-6.5-6.5C18.019,2.105,17.765,2,17.5,2H7C5.895,2,5,2.895,5,4v22c0,1.105,0.895,2,2,2h16c1.105,0,2-0.895,2-2 V9.5C25,9.235,24.895,8.981,24.707,8.793z M18,21h-8c-0.552,0-1-0.448-1-1c0-0.552,0.448-1,1-1h8c0.552,0,1,0.448,1,1 C19,20.552,18.552,21,18,21z M20,17H10c-0.552,0-1-0.448-1-1c0-0.552,0.448-1,1-1h10c0.552,0,1,0.448,1,1C21,16.552,20.552,17,20,17 z M18,10c-0.552,0-1-0.448-1-1V3.904L23.096,10H18z" clip-rule="evenodd"></path></svg>
            </button>
        </div>
        <div  x-show="show" tabindex="0" class="z-40 overflow-auto left-0 top-0 bottom-0 right-0 w-full h-full fixed">
            <div  @click.away="show = false" id="modalTarifas" class="z-50 relative p-3 mx-auto my-0 max-w-full" style="width: 1200px;">
                <div class="bg-white rounded shadow-lg border flex flex-col overflow-hidden">
                    <button @click={show=false} class="fill-current h-6 w-6 absolute right-0 top-0 m-6 font-3xl font-bold">&times;</button>
                    <div class="px-6 py-3 text-xl border-b font-bold">Acciones Comerciales</div>
                    <div class="p-6 flex-grow">
                            <livewire:accionesc-datatable :post='$CodigoCliente' :key='$randomKey' 
                                modal
                                searchable="LcComisionistaAgenda.AccionPosicionLc, LcComisionistaAgenda.CodigoAccionComercialLc, LcComisionistaAgenda.Observaciones, LcComisionistaAcciones.Observaciones"                         
                                />                                            
                    </div>
                    <div class="px-6 py-3 border-t">
                        <div class="flex justify-end">
                            <button @click={show=false}  type="button" class="bg-primary text-gray-100 rounded px-4 py-2 mr-1">Cerrar</Button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="z-40 overflow-auto left-0 top-0 bottom-0 right-0 w-full h-full absolute bg-black opacity-50"></div>
        </div>
    </div>

    <div class="flex justify-center">
        <a type="button" class="p-1 text-orange-600 hover:bg-orange-600 hover:text-white rounded has-tooltip" href="{{route('redirigirInicioOferta',['cod'=>$IdCliente])}}" target="_blank">
            <span class='tooltip rounded shadow-lg p-1 bg-black text-white-500 -mt-8'>Ofertas</span>
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 30 30"  xmlns="http://www.w3.org/2000/svg"><path d="M4,4v20c0,1.105,0.895,2,2,2h18c1.105,0,2-0.895,2-2V4H4z M18,8h-6c-0.552,0-1-0.448-1-1c0-0.552,0.448-1,1-1h6 c0.552,0,1,0.448,1,1C19,7.552,18.552,8,18,8z"/></svg>
        </a>
    </div>    

    <div x-data="{ show: false }">
        <div class="hidden flex justify-center">
            <button @click={show=true} type="button" class="p-1 text-pink-600 hover:bg-pink-600 hover:text-white rounded has-tooltip">
                <span class='tooltip rounded shadow-lg p-1 bg-black text-white-500 -mt-8'>Crear Oportunidad</span>
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M17.414 2.586a2 2 0 00-2.828 0L7 10.172V13h2.828l7.586-7.586a2 2 0 000-2.828z"></path><path fill-rule="evenodd" d="M2 6a2 2 0 012-2h4a1 1 0 010 2H4v10h10v-4a1 1 0 112 0v4a2 2 0 01-2 2H4a2 2 0 01-2-2V6z" clip-rule="evenodd"></path></svg>                
            </button>
        </div>
        <div  x-show="show" tabindex="0" class="z-40 overflow-auto left-0 top-0 bottom-0 right-0 w-full h-full fixed">
            <div  @click.away="show = false" id="modalTarifas" class="z-50 relative p-3 mx-auto my-0 max-w-full" style="width: 1200px;">
                <div class="bg-white rounded shadow-lg border flex flex-col overflow-hidden">
                    <button @click={show=false} class="fill-current h-6 w-6 absolute right-0 top-0 m-6 font-3xl font-bold">&times;</button>
                    <div class="px-6 py-3 text-xl border-b font-bold">Crear Oportunidad</div>
                    <div class="p-6 flex-grow">
                                                                     
                    </div>
                    <div class="px-6 py-3 border-t">
                        <div class="flex justify-end">
                            <button @click={show=false}  type="button" class="bg-primary text-gray-100 rounded px-4 py-2 mr-1">Cerrar</Button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="z-40 overflow-auto left-0 top-0 bottom-0 right-0 w-full h-full absolute bg-black opacity-50"></div>
        </div>
    </div>
    
</div>

<script>
      var clipboard = new ClipboardJS('.btn');

      clipboard.on('success', function (e) {
        //console.log(e);
      });

      clipboard.on('error', function (e) {
        //console.log(e);
      });
    </script>