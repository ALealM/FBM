@if ($oProyectos->count() > 0)
  <center><h3>Facturación</h3></center>
  <div class="table-responsive table-striped">
    <table class="table">
      <thead>
        <th class="text-center">Proyecto</th>
        <th class="text-center">Monto</th>
        <th class="text-center">Pagado</th>
        <th class="text-center">Restante</th>
        <th class="text-center"></th>
      </thead>
      <tbody>
        @foreach($oProyectos as $key => $oProyecto )
          @php
            $aPresupuesto = $oProyecto->presupuesto();
            $aCobros = $oProyecto->cobros();
            $fTotalRestante = $aPresupuesto['precio_venta']-$aCobros['total_cobrado'];
            $boolPagado = ($fTotalRestante <= 0 ? true : false);
          @endphp
          <tr class="m-0 p-0">
            <td class="m-0 p-0">{{$oProyecto->nombre}}</td>
            <td class="m-0 p-0 text-right">$ {{ number_format( $aPresupuesto['precio_venta'] ,2,".",",") }}</td>
            <td class="m-0 p-0 text-right">$ {{ number_format( $aCobros['total_cobrado'] ,2,".",",") }}</td>
            <td class="m-0 p-0 text-right">$ {{ number_format( $fTotalRestante ,2,".",",") }}</td>
            <td class="m-0 p-0 text-center">
              <div class="btn-group" role="group">
                <button type="button" class="btn btn-sm btn-success dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                  Acción
                </button>
                <div class="dropdown-menu">
                  <a href="{{url('/proyectos/editar/'.$oProyecto->id)}}#facturas" class="col-12 btn btn-secondary btn-sm"><i class="fa fa-file mr-2"></i>Facturar</a><br>
                </div>
              </div>
            </td>
          </tr>
        @endforeach
      </tbody>
    </table>
  </div>
@else
  <div class="col-lg-12">
    <div class="card">
      <img class="card-img-top" src="{{ asset('images/banner_w_1.png')}}">
      <div class="card-body">
        <h5 class="card-title">Ingresar nuevos proyectos</h5>
        <p class="card-text">Al ingresar proyectos y planear sus fases se calculará su precio de venta y se comenzará a recopilar información útil para la toma de decisiones.</p>
        <a href="{{url('/proyectos')}}" class="btn bg-fbm-blue text-white">Nuevo proyecto</a>
      </div>
    </div>
  </div>
@endif
