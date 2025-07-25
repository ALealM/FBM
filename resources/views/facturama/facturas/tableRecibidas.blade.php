<!--table class="table tile table-hover table-responsive dataTable" role='grid' id="data-table"-->
<table class="table table-condensed table-striped table-hover dataTable" role='grid' id="data-table">
<!--table class="table table-responsive table-striped table-hover table-bordered dataTable" role='grid' id="data-table"-->
  <thead>
    <th>Origen</th>
    <th class="text-center">Concepto</th>
    <th class="text-center">Fecha y hora</th>
    <th class="text-center">Total</th>
    <th></th>
  </thead>
  <tbody>
    @foreach($oCfdisRecibidos as $oCfdi)
    <tr id="cfdi{{ $oCfdi->Id }}" data-info="@json($oCfdi)" data-descripcion="FOLIO: {{$oCfdi->Folio}} {{ $oCfdi->TaxName }} por ${{number_format($oCfdi->Total,2,'.',',')}}">
      <td>{{ ( $oCfdi->id_movimiento != null ? 'FBM' : 'Facturama' ) }}</td>
      <td class="text-center">{{ $oCfdi->TaxName }}</td>
      <td class="text-center">{{ date('d/m/Y H:i',strtotime($oCfdi->Date))}}</td>
      <td class="text-right">${{ number_format($oCfdi->Total,2,'.',',') }}</td>
      <td class="text-center">
        <div class="btn-group" role="group">
          <button type="button" class="btn btn-sm btn-success dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
            Acción
          </button>
          <div class="dropdown-menu">
            @if ( $oCfdi->id_movimiento != null )
              @if ($oCfdi->pdf != null)
                <a href="{{asset('pagos/get_pdf').'/'.$oCfdi->Id}}" class="col-12 btn btn-secondary btn-sm"><i class="fa fa-file-pdf-o mr-2"></i>PDF</a><br>
              @endif
              @if ($oCfdi->xml != null)
                <a href="{{asset('pagos/get_xml').'/'.$oCfdi->Id}}" class="col-12 btn btn-secondary btn-sm"><i class="fa fa-file-code-o mr-2"></i>xml</a><br>
              @endif
            @else
              <a href="{{asset('facturama/get_cfdi').'/'.$oCfdi->Id.'/pdf'}}" class="col-12 btn btn-secondary btn-sm"><i class="fa fa-file-pdf-o mr-2"></i>PDF</a><br>
              <a href="{{asset('facturama/get_cfdi').'/'.$oCfdi->Id.'/xml'}}" class="col-12 btn btn-secondary btn-sm"><i class="fa fa-file-code-o mr-2"></i>XML</a><br>
            @endif
          </div>
        </div>
      </td>
    </tr>
    @endforeach
  </tbody>
</table>
