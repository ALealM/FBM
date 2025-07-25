@if ($oCostosFijos->count() > 0)
  <center><h3>Costos fijos</h3></center>
  <div class="table-responsive table-striped">
    <table class="table">
      <thead>
        <th class="text-center">Concepto</th>
        <th class="text-center">Pagos</th>
        <th class="text-center"></th>
      </thead>
      <tbody>
        @foreach($oCostosFijos as $key => $oCostoFijo )
          @php
            $aPagos = $oCostoFijo->pagos();
          @endphp
          <tr class="m-0 p-0">
            <td class="m-0 p-0">{{$oCostoFijo->concepto}}</td>
            <td class="text-center">
              <span class="badge badge-{{($aPagos["numero_pagos_pendientes"] > 0 ? 'danger' : 'success' )}}">{{ $aPagos["numero_pagos_pendientes"] }} pendientes</span>
            </td>
            <td class="m-0 p-0 text-center">
              <div class="btn-group" role="group">
                <button type="button" class="btn btn-sm btn-success dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                  Acción
                </button>
                <div class="dropdown-menu">
                  <a href="{{url('/costos_fijos/editar/'.$oCostoFijo->id)}}#pagos" class="col-12 btn btn-secondary btn-sm"><i class="fa fa-money mr-2"></i>Pagar</a><br>
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
      <img class="card-img-top" src="{{ asset('images/banner_w_2.png')}}">
      <div class="card-body">
        <h5 class="card-title">Registrar costos fijos</h5>
        <p class="card-text">Al registrar los costos fijos podrás dar seguimiento de los pagos a realizar en determinado periodo de tiempo.</p>
        <a href="{{url('/costos_fijos')}}" class="btn bg-fbm-blue text-white">Nuevo costo fijo</a>
      </div>
    </div>
  </div>
@endif
