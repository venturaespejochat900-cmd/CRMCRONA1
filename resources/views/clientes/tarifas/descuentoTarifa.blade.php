<div>
    <?php

use App\Http\Controllers\ArticuloController;

if(round($Descuento,0) <= 0){
    ?>
        
    <?php
        }else{        
    ?>
        {{--{{round($Descuento,2)}}--}}
        {{number_format(round($Descuento,2), 2, ',', '.')}}%
        
    <?php
        }
    ?>

        
</div>