<div>
    <?php 
        if(round($Precio,0) <= 0){
    ?>

    <?php
        }else{
    ?>
        {{number_format(round($Precio,2), 2, ',', '.')}}€
        
    <?php
        }
    ?>

        
</div>