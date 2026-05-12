                
            </main>
        </div>
    <!-- <footer class="flex items-center justify-between p-4 bg-white border-t dark:bg-darker dark:border-primary-darker mt-5">
        <div>Ligna Diet &copy; 2021</div>
        <div>
            Made by Ventura Espejo
        </div>
    </footer> -->    
    <script>
        
        function selectCodigoCliente(producto,cli){
            console.log(producto);      
            var pro  = '';
            if(producto.length == 1){
                pro = '00000' + producto;
            }else if(producto.length == 2){
                pro = '0000' + producto;
            }else if(producto.length == 3){
                pro = '000' + producto;
            }else if(producto.length == 4){
                pro = '00' + producto;
            }else if(producto.length == 5){
                pro = '0' + producto;
            }else{
                pro  = producto;
            }
            console.log(pro);
            $('#codigoOculto').val(pro);  
            //$('#codigoOculto').val(producto);
            $('#nombreOculto').val(cli);
            $('#clienteinput').val(pro+"-"+cli)       
            $(".clienteResultado-box").hide();        
        }

        function irPedido(tipo){
            var dato = $('#clienteinput').val();
            var cliente = dato.split("-");
            console.log(cliente[0]);
            let id = ''

            datos = {
                'codigo': cliente[0],
                "_token": $("meta[name='csrf-token']").attr("content")
            };

            $.ajax({
                data: datos,
                url: '/obtenerIdCliente',
                type: 'post',
                timeout: 2000,
                async: true,
                success: function(response) {
                    console.log(response)
                    if(tipo == 'p'){
                        window.open('https://cronadis.abmscloud.com/inicio?cod='+response)
                    }
                    if(tipo == 'o'){
                        window.open('https://cronadis.abmscloud.com/inicioOferta2?cod='+response)
                    }
                }
            });    
        }    
    </script>
</div>