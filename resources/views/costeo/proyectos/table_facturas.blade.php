<table class="table table-condensed table-striped table-hover dataTable" role='grid' id="data-table">
  <thead>
    <th>Folio</th>
    <th class="text-center">Receptor</th>
    <th class="text-center">Fecha</th>
    <th class="text-center">Total</th>
    <th></th>
  </thead>
  <tbody>
    @foreach($oCfdis as $oCfdi)
    <tr id="cfdi{{$oCfdi->cfdi}}" data-info="{{$oCfdi}}" data-descripcion="FOLIO: {{$oCfdi->folio}} {{$oCfdi->receptor}} por ${{number_format($oCfdi->total,2,'.',',')}}">
      <td>
        {{ $oCfdi->folio }}
      </td>
      <td class="text-center">
        {{ $oCfdi->receptor }}
      </td>
      <td class="text-center">
        {{ date('d/m/Y',strtotime($oCfdi->fecha_registro)) }}
      </td>
      <td class="text-right">
        $ {{ number_format($oCfdi->total,2,".",",") }}
      </td>
      <td class="text-center">
        <div class="btn-group" role="group">
          <button type="button" class="btn btn-sm btn-success dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
            Acción
          </button>
          <div class="dropdown-menu">
            <a href="{{asset('facturama/get_cfdi').'/'.$oCfdi->cfdi.'/pdf'}}" class="col-12 btn btn-secondary btn-sm"><i class="fa fa-file-pdf-o mr-2"></i>PDF</a><br>
            <a href="{{asset('facturama/get_cfdi').'/'.$oCfdi->cfdi.'/xml'}}" class="col-12 btn btn-secondary btn-sm"><i class="fa fa-file-code-o mr-2"></i>XML</a><br>
            <a href="javascript:;" class="col-12 btn btn-secondary btn-sm" onclick="eliminar_cfdi('{{$oCfdi->cfdi}}')"><i class="fa fa-times mr-2"></i>Cancelar CFDI</a><br>
          </div>
        </div>
      </td>
    </tr>
    @endforeach
  </tbody>
</table>
@include('facturama.facturas.functions')
