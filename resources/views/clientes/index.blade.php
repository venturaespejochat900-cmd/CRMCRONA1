@include('layouts.header')
@livewireStyles
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/1.9.2/tailwind.min.css" integrity="sha512-l7qZAq1JcXdHei6h2z8h8sMe3NbMrmowhOl+QkP3UhifPpCW2MC4M0i26Y8wYpbz1xD9t61MLT9L1N773dzlOA==" crossorigin="anonymous" />
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
        <livewire:clientes-datatable
        searchable="Clientes.CifDni, Clientes.Nombre, Clientes.RazonSocial, Clientes.Municipio"
        exportable                
        autorizaciones            
        />
    </div>        
</div>



<?php 
    }
?>

@livewireScripts
@include('layouts.footer')
@include('layouts.panels')