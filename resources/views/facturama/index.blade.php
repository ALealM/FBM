@extends('layouts.app', ['activePage' => @$sActivePage, 'sClassCardBody' => 'm-0 p-0'])
@section('content')
  <div class="col-lg-12 m-0 p-0">
    <div class="nav-tabs-navigation bg-fbm-blue">
      <div class="nav-tabs-wrapper">
        <ul class="row nav nav-tabs" data-tabs="tabs">
          <li class="col-lg-6 nav-item text-center">
            <a class="nav-link active show" href="#emitidas" data-toggle="tab">
              Emitidas
            </a>
          </li>
          <li class="col-lg-6  nav-item text-center">
            <a class="nav-link" href="#recibidas" data-toggle="tab">
              Recibidas
            </a>
          </li>
        </ul>
      </div>
    </div>
    <!--div class="nav-tabs-navigation bg-fbm-blue">
      <div class="nav-tabs-wrapper">
        <ul class="nav nav-tabs" data-tabs="tabs">
          <li class="nav-item mr-auto ml-auto">
            <a class="nav-link active show" href="#emitidas" data-toggle="tab">
              Emitidas
            </a>
          </li>
          <li class="nav-item mr-auto ml-auto">
            <a class="nav-link" href="#recibidas" data-toggle="tab">
              Recibidas
            </a>
          </li>
        </ul>
      </div>
    </div-->
    <div class="card-body">
      <div class="tab-content">
        <div class="tab-pane active show" id="emitidas">
          @include('facturama.facturas.tableEmitidas')
        </div>
        <div class="tab-pane" id="recibidas">
          @include('facturama.facturas.tableRecibidas')
        </div>
      </div>
    </div>
  </div>
@endsection
