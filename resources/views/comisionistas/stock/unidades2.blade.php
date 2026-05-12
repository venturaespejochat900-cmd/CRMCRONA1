<div class="flex space-x-1 justify-around">
    <?php
    if($PendienteServir > 0){
    ?>
        <button type="button" class="rounded-full px-4 bg-green-600 text-white p-2 rounded  leading-none flex items-center">
            <span>{{round($PendienteServir,0)}}</span>
        </button>
    <?php
    }else{
    ?>
        <button type="button" class="rounded-full px-4 bg-red-600 text-white p-2 rounded  leading-none flex items-center">
            <span>{{round($PendienteServir,0)}}</span>
        </button>

    <?php 
    }
    if($StockMinimo > 0){
        if(round($Unidades,0) <= round($StockMinimo, 0)){
    ?>
        <button type="button" class="rounded-full px-4 bg-red-600 text-white p-2 rounded  leading-none flex items-center">
            <span>{{round($Unidades,0)}}</span>
        </button>
    <?php
        }elseif(round($Unidades,0)  < (round($StockMinimo, 0)*1.1)){
    ?>
        <button type="button" class="rounded-full px-4 bg-orange-600 text-white p-2 rounded  leading-none flex items-center">
            <span>{{round($Unidades,0)}}</span>
        </button>
    <?php   
        }else{
    ?>
        <button type="button" class="rounded-full px-4 bg-green-600 text-white p-2 rounded  leading-none flex items-center">
            <span>{{round($Unidades,0)}}</span>
        </button>
        
    <?php
        }
    } else {
    ?>    

    <?php 
        if(round($Unidades,0) <= 0){
    ?>
        <button type="button" class="rounded-full px-4 bg-red-600 text-white p-2 rounded  leading-none flex items-center">
            <span>{{round($Unidades,0)}}</span>
        </button>
    <?php
        }elseif(round($Unidades,0)  < 50){
    ?>
        <button type="button" class="rounded-full px-4 bg-orange-600 text-white p-2 rounded  leading-none flex items-center">
            <span>{{round($Unidades,0)}}</span>
        </button>
    <?php   
        }else{
    ?>
        <button type="button" class="rounded-full px-4 bg-green-600 text-white p-2 rounded  leading-none flex items-center">
            <span>{{round($Unidades,0)}}</span>
        </button>
        
    <?php
        }
    }

    ?>

    <?php
    if($PendienteServir > 0){
    ?>
        <button type="button" class="rounded-full px-4 bg-green-600 text-white p-2 rounded  leading-none flex items-center">
            <span>{{round($PendienteRecibir,0)}}</span>
        </button>
    <?php
    }else{
    ?>
        <button type="button" class="rounded-full px-4 bg-red-600 text-white p-2 rounded  leading-none flex items-center">
            <span>{{round($PendienteRecibir,0)}}</span>
        </button>
    <?php
    }
    ?>
</div>