@extends('layouts.app', ['activePage' => @$sActivePage])
@section('content')
  @php
  $i = 0;
  @endphp
  <div id="accordion">
    @foreach ($aPreguntas as $sSeccion => $aSeccionPreguntas)
      <div class="col-12">
        <div class="card">
          <a href="javascript:;" class="card-header" id="heading{{$i}}" data-toggle="collapse" data-target="#collapse{{$i}}" aria-expanded="true" aria-controls="collapse{{$i}}">
            <strong class="color-fbm-blue">{!!strtoupper($sSeccion)!!}</strong>
          </a>
          <div id="collapse{{$i}}" class="collapse" aria-labelledby="heading{{$i}}">
            <div class="card-body">
              @foreach ($aSeccionPreguntas as $sPregunta => $sRespuesta)
                <div class="row mb-4">
                  <div class="col-12"><strong>{!!$sPregunta!!}</strong></div>
                  <div class="col-12">{!!$sRespuesta!!}</div>
                </div>
              @endforeach
            </div>
          </div>
        </div>
      </div>
      @php
      $i++;
      @endphp
    @endforeach
  </div>
@endsection
