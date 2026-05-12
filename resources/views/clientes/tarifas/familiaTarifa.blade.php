<div class="flex space-x-1 justify-around">
    <?php

use App\Http\Controllers\ArticuloController;

if(round($CodigoFamilia,0) <= 0){
    ?>
        
    <?php
        }else{
            $familia = ArticuloController::findFamilia($CodigoFamilia);
    ?>
        {{$familia}}
        
    <?php
        }
    ?>

        
</div>