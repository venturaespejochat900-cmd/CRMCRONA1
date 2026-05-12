<div class="flex space-x-1 justify-around">
    <?php

use App\Http\Controllers\ArticuloController;

if($CodigoArticulo){
        $articulo = ArticuloController::findArticulo($CodigoArticulo);    
    ?>
        {{$articulo}}
    <?php
        }else{

        }
        ?>
        
</div>