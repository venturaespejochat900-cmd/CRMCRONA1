<?php

use App\Http\Controllers\ArticuloController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LOGIN;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\PrescriptorController;
use App\Http\Controllers\InformesController;
use App\Http\Controllers\PedidoController;
use App\Http\Controllers\OfertaController;
use App\Http\Controllers\PotencialesController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('logs', '\Rap2hpoutre\LaravelLogViewer\LogViewerController@index');

//LOGIN
Route::get('/',[LOGIN::class,'index'])->name('/');
Route::post('/login/validarLogin',[LOGIN::class,'validarLogin'])->name('validarLogin');
Route::get('/inicio',[LOGIN::class,'redirigirInicio'])->name('redirigirInicio');

//Dashboard
Route::get('/dashboard',[DashboardController::class,'index'])->name('dashboard');
Route::get('/inicios', function(){return view('inicio');})->name('inicio');
Route::get('/welcome', function(){return view('welcome');})->name('welcome');

//Clientes
Route::get('clientes', function(){return view('clientes.index');})->name('clientes');
//Route::get('clientes/{cliente}', function(){return view('clientes.show');})->name('clientesShow');
Route::get('clientes/{cliente}', [ClienteController::class, 'cliente'])->name('clientesShow');
Route::post('/clientes/actualizar',[ClienteController::class,'actualizar'])->name('actualizarCliente');
Route::post('/cliente/nuevo',[ClienteController::class,'nuevo'])->name('nuevo');
Route::get('ofertas', function(){return view('clientes.oferta');})->name('oferta');
Route::get('firma/rgpd/{cliente}',[PrescriptorController::class, 'firmaRgpdE'])->name('firmaRgpdE');
Route::get('firma/sepa/{cliente}',[PrescriptorController::class, 'firmasepa'])->name('firmasepa');
Route::post('firma/rgpd', [PrescriptorController::class, 'firmargpd'])->name('firmargpd');
Route::post('firma/sepa', [PrescriptorController::class, 'firmarsepa'])->name('firmarsepa');
Route::post('comprobarExisteDniCifEnBBDD', [ClienteController::class, 'comprobarExisteDniCifEnBBDD'])->name('comprobarExisteDniCifEnBBDD');
Route::post('obtenerIdCliente', [ClienteController::class, 'ObtenerIdCliente'])->name('obtenerIdCliente');

//TARIFAS CLIENTES
Route::post('tarifa/producto', [ClienteController::class, 'tarifaProducto'])->name('tarifaProducto');
Route::post('tarifa/familia', [ClienteController::class, 'tarifaFamilia'])->name('tarifaFamilia');

//comisionistas
Route::get('comisionistas', function(){return view('comisionistas.index');})->name('comisionistas');
Route::post('/comisionistas/insertarNuevoPrescriptores', [PrescriptorController::class, 'insertarNuevoPrescriptores'])->name('insertarNuevoPrescriptores');
Route::post('comisionistas/actualizarComisionista', [PrescriptorController::class, 'actualizarComisionista'])->name('actualizarComisionista');
Route::get('comisionistas/{comisionista}', [ClienteController::class, 'comisionista'])->name('comisionistasShow');
Route::get('change/password/{comisionista}', [PrescriptorController::class, 'modificarPassword'])->name('changePassword'); 
Route::post('change/password', [PrescriptorController::class, 'updatePassword'])->name('updatePassword');

//Stock e incidencias Stock
Route::get('Stock', function(){ return view('comisionistas.stock.stock'); })->name('stock');
Route::get('StockIncidencia', function(){ return view('comisionistas.stock.incidencias'); })->name('stockIncidencias');
Route::post('fichaTecnica', [ArticuloController::class, 'datos'])->name('fichaTecnica');

//informes
Route::get('informes1', [informesController::class, 'informes1'])->name('informes1');
Route::get('informes2', [informesController::class, 'informes2'])->name('informes2');
Route::get('informes3', [informesController::class, 'informes3'])->name('informes3');
Route::get('informes4', [informesController::class, 'informes4'])->name('informes4');
Route::post('informes/prescriptores', [InformesController::class, 'prescriptores'])->name('informesPrescriptores');
Route::post('informes/clientes', [InformesController::class, 'clientes'])->name('informesClientes');
Route::post('informes/productos', [InformesController::class, 'productos'])->name('informesProductos');
Route::post('informes/aniomes', [InformesController::class, 'aniomes'])->name('informesaniomes');
Route::post('informes/inforprescriptores', [InformesController::class, 'inforprescriptores'])->name('inforprescriptores');
Route::post('/llamarComisionistas', [InformesController::class, 'comisionistasComercial'])->name('comisionistasComercial');
Route::post('informes/ventaFecha', [InformesController::class, 'ventaFecha'])->name('informesventaFecha');
Route::post('informes/ventaFechaFamilia', [InformesController::class, 'ventaFechaFamilia'])->name('informesventaFechaFamilia');
Route::post('informes/ventaFechaMaps', [InformesController::class, 'ventaFechaMaps'])->name('informesventaFechaMaps');

//potenciales
//Route::get('potenciales', [PotencialesController::class, 'potenciales'])->name('potenciales');
Route::get('potenciales', function(){return view('potenciales.datosPotenciales');})->name('potenciales');
Route::post('/potencial/insertarNuevo',[PotencialesController::class,'nuevo'])->name('nuevo');
Route::post('/potencial/actualizar',[PotencialesController::class,'actualizar'])->name('actualizar');
Route::post('/potencial/convertirCliente',[ClienteController::class,'convertirEnCliente'])->name('convertirCliente');
Route::post('/seguimiento',[PotencialesController::class,'seguimiento'])->name('seguimiento');
Route::post('/accionAgenda',[PotencialesController::class,'accionAgenda'])->name('accionAgenda');
Route::post('/accionAgendaBlue',[PotencialesController::class,'accionAgendaBlue'])->name('accionAgendaBlue');
Route::post('/guardarUpdatearAccion',[PotencialesController::class,'guardarUpdatearAccion'])->name('guardarUpdatearAccion');
Route::post('/citasCalendario',[PotencialesController::class,'citasCalendario'])->name('citasCalendario');
Route::post('/accionComercial',[ClienteController::class, 'accionComercial'])->name('accionComercial');
Route::post('/prioridad',[ClienteController::class, 'prioridad'])->name('prioridad');
Route::post('/temaComercial',[ClienteController::class, 'temaComercial'])->name('temaComercial');
// Route::get('calendario',function(){return view('potenciales.calendario');})->name('calendario');
Route::get('calendario',[App\Http\Controllers\EventoController::class, 'index'])->name('calendario');
Route::get('recuentoAcciones',function(){return view('potenciales.recuentoAcciones');})->name('recuentoAcciones');
Route::get('recuentoEmpresas',function(){return view('potenciales.recuentoEmpresas');})->name('recuentoEmpresas');

Route::post('/accionComercial',[ClienteController::class, 'accionComercial'])->name('accionComercial');
Route::post('/prioridad',[ClienteController::class, 'prioridad'])->name('prioridad');

//RUTA PEDIDOS
Route::get('/inicio',[LOGIN::class, 'inicioNuevoCliente'])->name('redirigirInicio2');
Route::get('/pruebaPedido',[LOGIN::class, 'inicioNuevoCliente2'])->name('redirigirInicio');
Route::post('/insercionPedido',[PedidoController::class,'insercionPedido'])->name('insercionPedido');
Route::post('/insercionPagos',[PedidoController::class,'insertarPagosBBDD'])->name('insercionPagos');
Route::post('/guardarBorrador',[PedidoController::class,'guardarBorradorPedido'])->name('borradorPedido');
Route::post('/obtenerDatosPedido',[PedidoController::class,'buscarPedido'])->name('buscarPedido');
Route::post('/borrarPedido',[PedidoController::class,'eliminarPedido'])->name('eliminarPedido');
Route::post('/insertarMuestra',[PedidoController::class,'insertarMuestra'])->name('insertarMuestra');
Route::post('/actualizarCantidad',[PedidoController::class,'cambioUnidadesProducto'])->name('actualizarCantidad');
Route::post('/actualizarDescuentoPedido',[PedidoController::class,'actualizarDescuentoEnvio']);
Route::post('/actualizarDescuentoArticulo',[PedidoController::class,'actualizarDescuentoProducto']);
Route::post('/actualizarContadorPedido',[PedidoController::class,'actualizarContadorPedido']);
Route::post('/obtenerUltimoPedidoPendiente',[PedidoController::class,'obtenerUltimoPedidoPendientePuntoVenta']);
Route::post('/obtenerCabeceraPedidoPendiente',[PedidoController::class,'obtenerCabeceraPedidoPendientePuntoVenta']);
Route::post('/eliminarLineaPedido',[PedidoController::class,'eliminarLineaPedido']);
Route::post('/comprobarEstadoPedido',[PedidoController::class,'comprobarEstadoPedido']);
Route::post('/buscadorPedidos',[PedidoController::class,'buscadorPedidos']);
Route::post('/obtenerLineasAlbaranSeleccionado',[PedidoController::class,'obtenerLineasAlbaranSeleccionado']);
Route::post('/obtenerPartidasArticulo',[ArticuloController::class,'obtenerPartida']);
Route::post('/buscadorPedidoIncidencia',[PedidoController::class,'buscadorPedidoIncidencia']);
Route::post('/ordenpedido', [pedidoController::class,'ultimoOrdenPedido']);
Route::post('/contador',[PedidoController::class,'contador']);
Route::post('/observacionPedido',[PedidoController::class,'observacionPedido']);
Route::post('/guardarSuPedido',[PedidoController::class,'guardarSuPedido']);
Route::post('/guardarProntoPago',[PedidoController::class,'guardarProntoPago']);
Route::post('/observacionSuPedido',[PedidoController::class,'observacionSuPedido']);
Route::post('/tabla',[PedidoController::class,'tabla']);

Route::post('/busquedaArticuloPedido',[ArticuloController::class,'buscarArticulosPedido'])->name('buscarArticuloPedido');
Route::post('/ivayrecargo',[ArticuloController::class,'ivayrecargo'])->name('ivayrecargo');
Route::post('/comprobarTratamientoPartidas',[ArticuloController::class,'comprobarTratamientoPartidas']);
Route::post('/comprobarTarifas',[ArticuloController::class,'comprobarTarifasStandar']);
Route::post('/datosClientePedido',[ArticuloController::class,'datosClientePedido'])->name('datosClientePedido');

Route::post('/lineasPedidoArticulo',[PedidoController::class, 'lineasPedidoArticulo'])->name('lineasPedidoArticulo');
Route::post('/repetirPedido',[PedidoController::class, 'repetirPedido'])->name('repetirPedido');
Route::post('/ultimosPedidos',[PedidoController::class, 'ultimosPedidos'])->name('ultimosPedidos');
Route::post('/datosArticulo',[ArticuloController::class, 'datosArticulo'])->name('datosArticulo');
Route::post('/datosPedido',[PedidoController::class, 'datosPedido'])->name('datosPedido');
Route::post('/observacionArticulo',[PedidoController::class, 'observacionArticulo'])->name('observacionArticulo');
Route::post('/comprobacionPedido',[PedidoController::class, 'comprobacionPedido'])->name('comprobacionPedido');
Route::post('/observacionArticuloOferta',[OfertaController::class, 'observacionArticuloOferta'])->name('observacionArticuloOferta');

//RUTA Ofertas
Route::get('/inicioOferta',[LOGIN::class, 'inicioNuevoClienteOferta'])->name('redirigirInicioOferta2');
Route::get('/inicioOferta2',[LOGIN::class, 'inicioNuevoClienteOferta2'])->name('redirigirInicioOferta');
Route::post('/insercionOferta',[OfertaController::class,'insercionOferta'])->name('insercionOferta');
//Route::post('/insercionPagos',[OfertaController::class,'insertarPagosBBDD'])->name('insercionPagos');
//Route::post('/guardarBorrador',[OfertaController::class,'guardarBorradorOferta'])->name('borradorOferta');
Route::post('/obtenerDatosOferta',[OfertaController::class,'buscarOferta'])->name('buscarOferta');
Route::post('/borrarOferta',[OfertaController::class,'eliminarOferta'])->name('eliminarOferta');
//Route::post('/insertarMuestra',[OfertaController::class,'insertarMuestra'])->name('insertarMuestra');
Route::post('/actualizarCantidadOferta',[OfertaController::class,'cambioUnidadesProducto'])->name('actualizarCantidadOferta');
Route::post('/actualizarDescuentoOferta',[OfertaController::class,'actualizarDescuentoEnvio']);
Route::post('/actualizarDescuentoArticuloOferta',[OfertaController::class,'actualizarDescuentoProductoOferta']);
Route::post('/actualizarContadorOferta',[OfertaController::class,'actualizarContadorOferta']);
Route::post('/obtenerUltimoOfertaPendiente',[OfertaController::class,'obtenerUltimoOfertaPendientePuntoVenta']);
Route::post('/obtenerCabeceraOfertaPendiente',[OfertaController::class,'obtenerCabeceraOfertaPendientePuntoVenta']);
Route::post('/eliminarLineaOferta',[OfertaController::class,'eliminarLineaOferta']);
Route::post('/comprobarEstadoOferta',[OfertaController::class,'comprobarEstadoOferta']);
Route::post('/buscadorOfertas',[OfertaController::class,'buscadorOfertas']);
//Route::post('/obtenerLineasAlbaranSeleccionado',[OfertaController::class,'obtenerLineasAlbaranSeleccionado']);
Route::post('/obtenerPartidasArticuloOferta',[ArticuloController::class,'obtenerPartidaOferta']);
Route::post('/buscadorOfertaIncidencia',[OfertaController::class,'buscadorOfertaIncidencia']);
Route::post('/ordenoferta', [OfertaController::class,'ultimoOrdenOferta']);
Route::post('/contadorOferta',[OfertaController::class,'contador']);
Route::post('/tablaOferta',[OfertaController::class,'tabla']);
Route::post('/observacionOferta',[OfertaController::class,'observacionOferta']);

Route::post('/busquedaArticuloOferta',[ArticuloController::class,'buscarArticulosPedido'])->name('buscarArticuloOferta');
Route::post('/comprobarTratamientoPartidasOferta',[ArticuloController::class,'comprobarTratamientoPartidas']);
Route::post('/comprobarTarifasOferta',[ArticuloController::class,'comprobarTarifas']);
//Route::post('/comprobarTarifasOferta',[ArticuloController::class,'comprobarTarifasStandar']);
Route::post('/datosClienteOferta',[ArticuloController::class,'datosClientePedido'])->name('datosClienteOferta');
Route::post('descripcion0Oferta',[OfertaController::class, 'descripcion0Oferta'])->name('descripcion0Oferta');
Route::post('precioCosteProductoOferta',[OfertaController::class, 'precioCosteProductoOferta'])->name('precioCosteProductoOferta');
Route::post('/datosOferta',[OfertaController::class, 'datosOferta'])->name('datosOferta');
Route::get('ofertasClientes', function(){return view('oferta.index');})->name('ofertasClientes');
Route::post('/aprobarOferta',[OfertaController::class, 'pasarOfertaPedido'])->name('aprobarOferta');



//mapa de calor 
//Route::get('heatmap', function(){return view('heatmap.mapaprueba');})->name('heatmap');
Route::get('heatmap', function(){return view('heatmap.mapa');})->name('heatmap');
Route::get('heatmapotenciales', function(){return view('heatmap.mapaPotenciales');})->name('heatmapotenciales');
Route::get('heatmapinforme', function(){return view('heatmap.mapaInforme');})->name('heatmapinforme');
Route::get('heatmaprescriptores', function(){return view('heatmap.mapaPrescriptores');})->name('heatmaprescriptores');
Route::post('direccionesh',[ClienteController::class, 'direccionesh'])->name('direccionesh');
Route::post('direccionesPotenciales',[ClienteController::class, 'direccionesPotenciales'])->name('direccionesPotenciales');
Route::post('direccionesInforme',[ClienteController::class, 'direccionesInforme'])->name('direccionesInforme');
Route::post('direccionesPrescriptores',[PrescriptorController::class, 'direccionesPrescriptores'])->name('direccionesPrescriptores');

//OTRAS RUTAS
        //Route::any('/cerrarSesion',[Controller::class,'cerrarSesion'])->name('cerrarSesion');
        //Route::post('/limpiarVariableSesionUsuario',[Controller::class,'eliminarVariableSesionCliente'])->name('limpiarSesionCliente');

//pedidos nuevos 
Route::post('direccionesPotenciales',[ClienteController::class, 'direccionesPotenciales'])->name('direccionesPotenciales');
Route::get('pedidosClientes', function(){return view('pedido.index');})->name('pedidosClientes');

//correos
Route::post('correoOferta',[OfertaController::class, 'correoOferta'])->name('correoOferta');
Route::post('correoPedido',[PedidoController::class, 'correoPedido'])->name('correoPedido');
Route::post('correoArticulosPedidos',[PedidoController::class, 'correoPedido'])->name('correoArticulosPedidos');
Route::post('correoArticulosFicha',[PedidoController::class, 'correoFicha'])->name('correoArticulosFicha');


Route::post('direccionPedido',[PedidoController::class, 'direccionPedido'])->name('direccionPedido');
Route::post('direccionOferta',[OfertaController::class, 'direccionPedido'])->name('direccionOferta');
Route::post('direcciones',[PedidoController::class, 'direcciones'])->name('direcciones');
Route::post('descripcion0',[PedidoController::class, 'descripcion0'])->name('descripcion0');
Route::post('precioCosteProducto',[PedidoController::class, 'precioCosteProducto'])->name('precioCosteProducto');



//tarifa articulos
Route::get('articulosTarifa', [ArticuloController::class, 'tarifario'])->name('articulosTarifa');

//editar Pedidos
Route::post('recuperarPedido',[PedidoController::class, 'recuperarPedido'])->name('recuperarPedido');
Route::post('/pedidomod',[PedidoController::class, 'pedidomod'])->name('pedidomod');
Route::post('/eliminarmod',[PedidoController::class, 'eliminarmod'])->name('eliminarmod');
Route::post('/estadoPedido',[PedidoController::class, 'estadoPedido'])->name('estadoPedido');


Route::post('/busquedaClientes',[ClienteController::class, 'busquedaClientes'])->name('busquedaClientes');


//Nuevo Calendario
//Route::get('/evento', [App\Http\Controllers\EventoController::class, 'index']);
Route::get('/calendario/mostrar', [App\Http\Controllers\EventoController::class, 'show']);
Route::post('/calendario/agregar', [App\Http\Controllers\EventoController::class, 'store']);
Route::post('/calendario/editar/{id}', [App\Http\Controllers\EventoController::class, 'edit']);
Route::post('/calendario/actualizar/{evento}', [App\Http\Controllers\EventoController::class, 'update']);
Route::post('/calendario/move/{id}', [App\Http\Controllers\EventoController::class, 'updateDate']);
Route::post('/calendario/borrar/{id}', [App\Http\Controllers\EventoController::class, 'destroy']);

Route::get('/prueba', function(){return session('tipo');})->name('prueba');


Route::post('recuperarOferta', [OfertaController::class, 'recuperarOferta'])->name('recuperarOferta');
Route::post('/ofertamod', [OfertaController::class, 'ofertamod'])->name('ofertamod');

Route::post('/eliminarOfertamod', [OfertaController::class, 'eliminarmod'])->name('eliminarOfertamod');

Route::post('/eliminarPedido', [PedidoController::class, 'eliminarPedido2'])->name('eliminarPedido');
Route::post('/duplicarPedido', [PedidoController::class, 'duplicarPedido'])->name('duplicarPedido');
