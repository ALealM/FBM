<?php
use App\Http\Middleware\ValidarPermisos;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\SoporteServiciosController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\EmpresasController;
use App\Http\Controllers\ProyectosController;
use App\Http\Controllers\ProductosController;
use App\Http\Controllers\CostosFijosController;
use App\Http\Controllers\ManoDeObraController;
use App\Http\Controllers\MateriaPrimaController;
use App\Http\Controllers\CostosIndirectosController;
use App\Http\Controllers\VentasController;
use App\Http\Controllers\CuentasController;
use App\Http\Controllers\ProyeccionesController;
use App\Http\Controllers\EscenariosController;
use App\Http\Controllers\PagosController;
use App\Http\Controllers\FacturamaController;
use App\Http\Controllers\StripeController;

Route::get('/php-clear', function () {
  //\Artisan::call('config:clear');
  //\Artisan::call('config:cache');

  //\Artisan::call('route:clear');
  //\Artisan::call('route:cache');

  //\Artisan::call('view:clear');
  //\Artisan::call('view:cache');

  //\Artisan::call('event:clear');
  //\Artisan::call('event:cache');

  //\Artisan::call('cache:clear');

  //Artisan::call('storage:link');

  \Artisan::call('optimize:clear');//<--

});


// Route::get('/logout', [LoginController::class, 'logout'])->name("logout");

// Route::get('logout', ['as' => 'logout','uses' => 'Auth\LoginController::class,'logout']);

Auth::routes();


Route::get('login_consultor/{id_empresa}', [LoginController::class,'login_consultor']);

Route::get('/olvidaste_contrasena', function () {return view('auth.passwords.email');});

Route::get('soporte_servicios/licenciamiento/solicitud_licencia_store', [SoporteServiciosController::class,'solicitud_licencia_store']);

Route::group(['middleware' => 'auth'], function () {
    //Home
    Route::get('/', [HomeController::class,'index'])->name('inicio')->middleware('ValidarPermisos:general');;
    Route::get('/home', [HomeController::class,'index'])->name('home')->middleware('ValidarPermisos:general');;


    Route::group(['prefix' => 'dashboard'], function () {
      Route::get('/generar_calculo_por_ventas', [HomeController::class,'generar_calculo_por_ventas'])->middleware('ValidarPermisos:general');;
    });

    Route::group(['prefix' => 'usuarios'], function () {
      Route::get('/editar/{id}', [UserController::class,'edit'])->middleware('ValidarPermisos:general');;
      Route::put('/update', [UserController::class,'update'])->middleware('ValidarPermisos:general');;
    });

    Route::group(['prefix' => 'empresa'], function () {
      Route::get('/', [EmpresasController::class,'index'])->middleware('ValidarPermisos:general');;
      Route::put('/update', [EmpresasController::class,'update'])->middleware('ValidarPermisos:general');;
    });

    Route::group(['prefix' => 'proyectos'], function () {
      //Proyectos
      Route::get('/', [ProyectosController::class,'index'])->middleware('ValidarPermisos:m_proyectos');
      Route::get('/editar/{id}', [ProyectosController::class,'edit'])->middleware('ValidarPermisos:m_proyectos');
      Route::get('/nuevo', [ProyectosController::class,'create'])->middleware('ValidarPermisos:m_proyectos');
      Route::post('/store', [ProyectosController::class,'store'])->middleware('ValidarPermisos:m_proyectos');
      Route::put('/update', [ProyectosController::class,'update'])->middleware('ValidarPermisos:m_proyectos');
      Route::get('/destroy', [ProyectosController::class,'destroy'])->middleware('ValidarPermisos:m_proyectos');
      Route::get('/imprimir/{id}', [ProyectosController::class,'imprimir'])->middleware('ValidarPermisos:m_proyectos');
      Route::post('/duplicar/{id}', [ProyectosController::class,'duplicar'])->middleware('ValidarPermisos:m_proyectos');
      Route::get('/edit_factura', [ProyectosController::class,'edit_factura'])->middleware('ValidarPermisos:m_proyectos');
      //Costos Variables
      Route::get('/edit_costo_variable/{id_proyecto}/{id}', [ProyectosController::class,'edit_costo_variable'])->middleware('ValidarPermisos:m_proyectos');
      Route::post('/store_costo_variable', [ProyectosController::class,'store_costo_variable'])->middleware('ValidarPermisos:m_proyectos');
      Route::put('/update_costo_variable', [ProyectosController::class,'update_costo_variable'])->middleware('ValidarPermisos:m_proyectos');
      Route::get('/destroy_costo_variable', [ProyectosController::class,'destroy_costo_variable'])->middleware('ValidarPermisos:m_proyectos');
      //Costos indirectos
      Route::get('/edit_costo_indirecto/{id}', [ProyectosController::class,'edit_costo_indirecto'])->middleware('ValidarPermisos:m_proyectos');
      Route::post('/store_costo_indirecto', [ProyectosController::class,'store_costo_indirecto'])->middleware('ValidarPermisos:m_proyectos');
      Route::put('/update_costo_indirecto', [ProyectosController::class,'update_costo_indirecto'])->middleware('ValidarPermisos:m_proyectos');
      Route::put('/marcar_comprado_costo_indirecto', [ProyectosController::class,'marcar_comprado_costo_indirecto'])->middleware('ValidarPermisos:m_proyectos');
      Route::get('/destroy_costo_indirecto', [ProyectosController::class,'destroy_costo_indirecto'])->middleware('ValidarPermisos:m_proyectos');
      //Fases
      Route::post('/store_fase', [ProyectosController::class,'store_fase'])->middleware('ValidarPermisos:m_proyectos');
      Route::put('/update_fase', [ProyectosController::class,'update_fase'])->middleware('ValidarPermisos:m_proyectos');
      Route::delete('/destroy_fase', [ProyectosController::class,'destroy_fase'])->middleware('ValidarPermisos:m_proyectos');
      Route::get('/get_fases', [ProyectosController::class,'get_fases'])->middleware('ValidarPermisos:m_proyectos');
      //Roles
      Route::post('/store_rol', [ProyectosController::class,'store_rol'])->middleware('ValidarPermisos:m_proyectos');
      Route::put('/update_rol', [ProyectosController::class,'update_rol'])->middleware('ValidarPermisos:m_proyectos');
      Route::delete('/destroy_rol', [ProyectosController::class,'destroy_rol'])->middleware('ValidarPermisos:m_proyectos');
      //Participantes
      Route::post('/store_participante', [ProyectosController::class,'store_participante'])->middleware('ValidarPermisos:m_proyectos');
      Route::put('/update_participante', [ProyectosController::class,'update_participante'])->middleware('ValidarPermisos:m_proyectos');
      Route::delete('/destroy_participante', [ProyectosController::class,'destroy_participante'])->middleware('ValidarPermisos:m_proyectos');
    });

    Route::group(['prefix' => 'productos'], function () {
      //Productos
      Route::get('/', [ProductosController::class,'index'])->middleware('ValidarPermisos:m_productos');
      Route::get('/editar/{id}', [ProductosController::class,'edit'])->middleware('ValidarPermisos:m_productos');
      Route::get('/nuevo', [ProductosController::class,'create'])->middleware('ValidarPermisos:m_productos');
      Route::post('/store', [ProductosController::class,'store'])->middleware('ValidarPermisos:m_productos');
      Route::put('/update', [ProductosController::class,'update'])->middleware('ValidarPermisos:m_productos');
      Route::get('/destroy', [ProductosController::class,'destroy'])->middleware('ValidarPermisos:m_productos');
      Route::get('/buscador', [ProductosController::class,'buscador'])->middleware('ValidarPermisos:m_productos');
      //Costos indirectos
      Route::get('/edit_costo_indirecto/{id}', [ProductosController::class,'edit_costo_indirecto'])->middleware('ValidarPermisos:m_productos');
      Route::post('/store_costo_indirecto', [ProductosController::class,'store_costo_indirecto'])->middleware('ValidarPermisos:m_productos');
      Route::put('/update_costo_indirecto', [ProductosController::class,'update_costo_indirecto'])->middleware('ValidarPermisos:m_productos');
      Route::get('/destroy_costo_indirecto', [ProductosController::class,'destroy_costo_indirecto'])->middleware('ValidarPermisos:m_productos');
      //Costo por producto
      Route::get('/edit_costo_por_producto/{id}', [ProductosController::class,'edit_costo_por_producto'])->middleware('ValidarPermisos:m_productos');
      Route::post('/store_costo_por_producto', [ProductosController::class,'store_costo_por_producto'])->middleware('ValidarPermisos:m_productos');
      Route::put('/update_costo_por_producto', [ProductosController::class,'update_costo_por_producto'])->middleware('ValidarPermisos:m_productos');
      Route::get('/destroy_costo_por_producto', [ProductosController::class,'destroy_costo_por_producto'])->middleware('ValidarPermisos:m_productos');
    });

    //Catalogos
    //Route::get('catalogos/', [CatalogosController::class,'index'])->name('catalogos');

    Route::group(['prefix' => 'costos_fijos'], function () {
      //Costos fijos
      Route::get('/', [CostosFijosController::class,'index'])->middleware('ValidarPermisos:m_costos_fijos');
      Route::get('/nuevo', [CostosFijosController::class,'create'])->middleware('ValidarPermisos:m_costos_fijos');
      Route::post('/store', [CostosFijosController::class,'store'])->middleware('ValidarPermisos:m_costos_fijos');
      Route::get('/editar/{id}', [CostosFijosController::class,'edit'])->middleware('ValidarPermisos:m_costos_fijos');
      Route::put('/update', [CostosFijosController::class,'update'])->middleware('ValidarPermisos:m_costos_fijos');
      Route::get('/destroy', [CostosFijosController::class,'destroy'])->middleware('ValidarPermisos:m_costos_fijos');
    });

    Route::group(['prefix' => 'mano_de_obra'], function () {
      //Mano de obra
      Route::get('/', [ManoDeObraController::class,'index'])->middleware('ValidarPermisos:m_mano_obra');
      Route::get('/nuevo', [ManoDeObraController::class,'create'])->middleware('ValidarPermisos:m_mano_obra');

      Route::post('/store', [ManoDeObraController::class,'store'])->middleware('ValidarPermisos:m_mano_obra');
      Route::get('/editar/{id}', [ManoDeObraController::class,'edit'])->middleware('ValidarPermisos:m_mano_obra');
      Route::put('/update', [ManoDeObraController::class,'update'])->middleware('ValidarPermisos:m_mano_obra');
      Route::get('/destroy', [ManoDeObraController::class,'destroy'])->middleware('ValidarPermisos:m_mano_obra');
      Route::get('/get_grupos', [ManoDeObraController::class,'get_grupos'])->middleware('ValidarPermisos:m_mano_obra');
      Route::post('/store_update_grupos', [ManoDeObraController::class,'store_update_grupos'])->middleware('ValidarPermisos:m_mano_obra');
      Route::delete('/destroy_grupos', [ManoDeObraController::class,'destroy_grupos'])->middleware('ValidarPermisos:m_mano_obra');

    });

    Route::group(['prefix' => 'materia_prima'], function () {
      //Materia prima
      Route::get('/', [MateriaPrimaController::class,'index'])->middleware('ValidarPermisos:m_materia_prima');
      Route::get('/nuevo', [MateriaPrimaController::class,'create'])->middleware('ValidarPermisos:m_materia_prima');
      Route::post('/store', [MateriaPrimaController::class,'store'])->middleware('ValidarPermisos:m_materia_prima');
      Route::get('/editar/{id}', [MateriaPrimaController::class,'edit'])->middleware('ValidarPermisos:m_materia_prima');
      Route::put('/update', [MateriaPrimaController::class,'update'])->middleware('ValidarPermisos:m_materia_prima');
      Route::get('/destroy', [MateriaPrimaController::class,'destroy'])->middleware('ValidarPermisos:m_materia_prima');
    });

    Route::group(['prefix' => 'costos_indirectos'], function () {
      //Costos indirectos
      Route::get('/', [CostosIndirectosController::class,'index'])->middleware('ValidarPermisos:m_costos_indirectos');
      Route::get('/nuevo', [CostosIndirectosController::class,'create'])->middleware('ValidarPermisos:m_costos_indirectos');
      Route::post('/store', [CostosIndirectosController::class,'store'])->middleware('ValidarPermisos:m_costos_indirectos');
      Route::get('/editar/{id}', [CostosIndirectosController::class,'edit'])->middleware('ValidarPermisos:m_costos_indirectos');
      Route::put('/update', [CostosIndirectosController::class,'update'])->middleware('ValidarPermisos:m_costos_indirectos');
      Route::get('/destroy', [CostosIndirectosController::class,'destroy'])->middleware('ValidarPermisos:m_costos_indirectos');
    });

    /*Route::group(['prefix' => 'proveedores'], function () {
      //Proveedores
      Route::get('/', [ProveedoresController::class,'index']);
      Route::get('/nuevo', [ProveedoresController::class,'create']);
      Route::post('/store', [ProveedoresController::class,'store']);
      Route::get('/editar/{id}', [ProveedoresController::class,'edit']);
      Route::put('/update', [ProveedoresController::class,'update']);
      Route::get('/destroy', [ProveedoresController::class,'destroy']);
    });*/

    /*Route::group(['prefix' => 'reportes'], function () {
      //Reportes
      Route::get('/', [ReportesController::class,'reporte'])->name('reporte');
      Route::get('/actividad', [ReportesController::class,'actividad'])->name('actividad');
      Route::get('/getActividad', [ReportesController::class,'getActividad'])->name('getActividad');
      Route::get('/getReporte', [ReportesController::class,'getReporte'])->name('getReporte');
    });*/

    Route::group(['prefix' => 'ventas'], function () {
      //Ventas
      Route::get('/', [VentasController::class,'index'])->middleware('ValidarPermisos:m_ventas');
      Route::post('/store', [VentasController::class,'store'])->middleware('ValidarPermisos:m_ventas');
      Route::get('/getVentas', [VentasController::class,'getVentas'])->middleware('ValidarPermisos:m_ventas');
    });

    Route::group(['prefix' => 'cuentas'], function () {
      //Cuentas
      Route::get('/', [CuentasController::class,'index']);
      Route::post('/store', [CuentasController::class,'store']);
      Route::put('/update', [CuentasController::class,'update']);
      Route::put('/movimiento', [CuentasController::class,'movimiento']);
      Route::get('/historial_movimientos', [CuentasController::class,'historial_movimientos']);
      Route::put('/destroy', [CuentasController::class,'destroy']);
    });

    Route::group(['prefix' => 'proyecciones'], function () {
      //Proyecciones
      Route::get('/', [ProyeccionesController::class,'index'])->middleware('ValidarPermisos:m_proyecciones');
      Route::get('/calculo_anual', [ProyeccionesController::class,'calculo_anual'])->middleware('ValidarPermisos:m_proyecciones');
      Route::get('/calculo_anual_inverso', [ProyeccionesController::class,'calculo_anual_inverso'])->middleware('ValidarPermisos:m_proyecciones');
      Route::get('/calculo_anual_incremento', [ProyeccionesController::class,'calculo_anual_incremento'])->middleware('ValidarPermisos:m_proyecciones');
      Route::get('/generar_anual', [ProyeccionesController::class,'generar_anual'])->middleware('ValidarPermisos:m_proyecciones');
      Route::get('/generar_anual_inverso', [ProyeccionesController::class,'generar_anual_inverso'])->middleware('ValidarPermisos:m_proyecciones');
      Route::get('/generar_anual_incremento', [ProyeccionesController::class,'generar_anual_incremento'])->middleware('ValidarPermisos:m_proyecciones');
      Route::get('/valuacion', [ProyeccionesController::class,'valuacion'])->middleware('ValidarPermisos:m_proyecciones');
    });

    Route::group(['prefix' => 'escenarios'], function () {
      //Escenario
      Route::get('/', [EscenariosController::class,'index'])->middleware('ValidarPermisos:m_escenarios');
      Route::get('/calculo_temporalidad', [EscenariosController::class,'calculo_temporalidad'])->middleware('ValidarPermisos:m_escenarios');
      Route::get('/generar_temporalidad', [EscenariosController::class,'generar_temporalidad'])->middleware('ValidarPermisos:m_escenarios');
      Route::get('/calculo_continuo', [EscenariosController::class,'calculo_continuo'])->middleware('ValidarPermisos:m_escenarios');
      Route::get('/generar_continuo', [EscenariosController::class,'generar_continuo'])->middleware('ValidarPermisos:m_escenarios');
    });

    /*Route::group(['prefix' => 'almacen'], function () {
      //Almacen
      Route::get('/', [AlmacenController::class,'index']);
      Route::get('/edit', [AlmacenController::class,'edit']);
      Route::post('/store', [AlmacenController::class,'store']);
      Route::get('/getMedida', [AlmacenController::class,'getMedida']);
      Route::get('/historial_movimientos', [AlmacenController::class,'historial_movimientos']);
    });*/

    Route::group(['prefix' => 'soporte_servicios'], function () {
      //Soporte y servicios
      Route::get('/', [SoporteServiciosController::class,'index'])->middleware('ValidarPermisos:general');
      //Asesoramiento
      Route::get('/asesoramiento', [SoporteServiciosController::class,'asesoramiento_index'])->middleware('ValidarPermisos:general');
      Route::get('/asesoramiento/solicitud_asesoramiento_store', [SoporteServiciosController::class,'solicitud_asesoramiento_store'])->middleware('ValidarPermisos:general');
      Route::get('/asesoramiento/solicitudes', [SoporteServiciosController::class,'solicitudes_asesoramiento_index'])->middleware('ValidarPermisos:general');
      //Licencias
      Route::get('/licenciamiento', [SoporteServiciosController::class,'licenciamiento_index'])->middleware('ValidarPermisos:general');
      Route::get('/licenciamiento/suscripcion/{id_licencia}', [SoporteServiciosController::class,'suscripcion'])->middleware('ValidarPermisos:general');
      Route::post('/licenciamiento/suscribirse', [SoporteServiciosController::class,'suscribirse'])->middleware('ValidarPermisos:general');
      Route::post('/licenciamiento/cancelar_suscripcion', [SoporteServiciosController::class,'cancelar_suscripcion'])->middleware('ValidarPermisos:general');

      //Route::get('/licenciamiento/solicitud_licencia_store', [SoporteServiciosController::class,'solicitud_licencia_store'])->middleware('ValidarPermisos:general');
      //Preguntas frecuentes
      Route::get('/preguntas_frecuentes', [SoporteServiciosController::class,'preguntas_frecuentes_index'])->middleware('ValidarPermisos:general');
      //Tickets
      Route::get('/tickets', [SoporteServiciosController::class,'tickets_index'])->middleware('ValidarPermisos:general');
      Route::get('/tickets/tickets_store', [SoporteServiciosController::class,'tickets_store'])->middleware('ValidarPermisos:general');
      Route::get('/tickets/tickets_update', [SoporteServiciosController::class,'tickets_update'])->middleware('ValidarPermisos:general');
    });

    Route::group(['prefix' => 'pagos'], function () {
      Route::post('/store', [PagosController::class,'store']);
      Route::put('/update', [PagosController::class,'update']);
      Route::delete('/destroy', [PagosController::class,'destroy']);
      Route::get('/get_pdf/{id_pago}', [PagosController::class,'get_pdf']);
      Route::get('/get_xml/{id_pago}', [PagosController::class,'get_xml']);
    });

    Route::group(['prefix' => 'cobros'], function () {
      Route::post('/store', [CobrosController::class,'store']);
    });

    Route::group(['prefix' => 'facturama'], function () {
      Route::get('/', [FacturamaController::class,'index'])->middleware('ValidarPermisos:general');
      Route::get('/enlace', [FacturamaController::class,'edit'])->middleware('ValidarPermisos:general');
      Route::put('/update', [FacturamaController::class,'update'])->middleware('ValidarPermisos:general');
      Route::delete('/remover_enlace', [FacturamaController::class,'remover_enlace'])->middleware('ValidarPermisos:general');

      Route::post('/store_cfdi', [FacturamaController::class,'store_cfdi'])->middleware('ValidarPermisos:general');
      Route::delete('/delete_cfdi', [FacturamaController::class,'delete_cfdi'])->middleware('ValidarPermisos:general');

      Route::get('/get_cfdis', [FacturamaController::class,'get_cfdis'])->middleware('ValidarPermisos:general');
      Route::get('/get_cfdi/{id}/{tipo?}', [FacturamaController::class,'get_cfdi'])->middleware('ValidarPermisos:general');
      Route::get('/get_product_code', [FacturamaController::class,'get_product_code'])->middleware('ValidarPermisos:general');
      Route::get('/get_usos_cfdi', [FacturamaController::class,'get_usos_cfdi'])->middleware('ValidarPermisos:general');

    });

    Route::group(['prefix' => 'stripe'], function () {
      Route::get('/pago', [StripeController::class,'pago']);
      Route::post('/store_cargo', [StripeController::class,'store_cargo']);
      Route::get('/suscripcion', [StripeController::class,'suscripcion']);
      Route::post('/store_suscripcion', [StripeController::class,'store_suscripcion']);
      Route::post('/update_default_payment_method', [StripeController::class,'update_default_payment_method']);
    });
});
