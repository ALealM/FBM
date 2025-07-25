<div>
  <div class="row">
    <label class="col-sm-2 col-form-label">Código unidad de medida <small>(SAT)</small></label>
    <div class="col-sm-7">
      {!! Form::text('unit_code',null,['id'=>'unit_code','class'=>'form-control inputSlim']) !!}
    </div>
    <div>
      <a href="javascript:" class="btn btn-secondary btn-sm" onclick="{  $('#divBusquedaUnitCode').slideDown() }"><i class="fa fa-search mr-2"></i>Unidades de medida frecuentes</a>
    </div>
  </div>
  <div id="divBusquedaUnitCode" class="m-2">
    <div class="row">
      <div class="col-lg-12 bg-light" style="max-height:150px; overflow: scroll;">
        @php
          $aUnitCodes = app("App\Http\Controllers\FacturamaController")->aUnitCodes;
        @endphp
        <table>
          <tbody id="tbodyResultadosUnitCode">
            @foreach ($aUnitCodes as $sCode => $sDecripcion)
              <tr>
                <td>{{$sDecripcion}}</td>
                <td>
                  {{$sCode}}
                </td>
                <td>
                  <a href="javascript:;" class="btn btn-success btn-sm" onclick="agregar_unit_code('{{$sCode}}')"><i class="fa fa-plus"></i></a>
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
<script>
  $("#divBusquedaUnitCode").hide();

  function agregar_unit_code(sCodigo)
  {
    $("#unit_code").val(sCodigo);
    $('#divBusquedaUnitCode').slideUp();
  }
</script>
