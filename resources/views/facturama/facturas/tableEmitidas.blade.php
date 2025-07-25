<!--table class="table tile table-hover table-responsive dataTable" role='grid' id="data-table"-->
<table class="table table-condensed table-striped table-hover dataTable" role='grid' id="data-table">
<!--table class="table table-responsive table-striped table-hover table-bordered dataTable" role='grid' id="data-table"-->
  <thead>
    <th class="">Folio</th>
    <th class="text-center">Receptor</th>
    <th class="text-center">RFC</th>
    <th class="text-center">Fecha y hora</th>
    <th class="text-center">Tipo</th>
    <th class="text-center">Total</th>
    <th></th>
  </thead>
  <tbody>
    @foreach($oCfdisEmitidos as $oCfdi)
    <tr id="cfdi{{ $oCfdi->Id }}" data-info='@json($oCfdi)' data-descripcion="FOLIO: {{$oCfdi->Folio}} {{ $oCfdi->TaxName }} por ${{number_format($oCfdi->Total,2,'.',',')}}">
      <td>{{ $oCfdi->Folio }}</td>
      <td class="text-center">{{ $oCfdi->TaxName }}</td>
      <td class="text-center">{{ $oCfdi->Rfc }}</td>
      <td class="text-center">{{ date('d/m/Y H:i',strtotime($oCfdi->Date))}}</td>
      <td class="text-center">{{ $oCfdi->PaymentMethod }}</td>
      <td class="text-right">${{ number_format($oCfdi->Total,2,'.',',') }}</td>
      <td class="text-center">
        <div class="btn-group" role="group">
          <button type="button" class="btn btn-sm btn-success dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
            Acción
          </button>
          <div class="dropdown-menu">
            <a href="{{asset('facturama/get_cfdi').'/'.$oCfdi->Id.'/pdf'}}" class="col-12 btn btn-secondary btn-sm"><i class="fa fa-file-pdf-o mr-2"></i>PDF</a><br>
            <a href="{{asset('facturama/get_cfdi').'/'.$oCfdi->Id.'/xml'}}" class="col-12 btn btn-secondary btn-sm"><i class="fa fa-file-code-o mr-2"></i>XML</a><br>
            <a href="javascript:;" class="col-12 btn btn-secondary btn-sm" onclick="eliminar_cfdi('{{$oCfdi->Id}}')"><i class="fa fa-times mr-2"></i>Cancelar CFDI</a><br>
          </div>
        </div>
      </td>
    </tr>
    @endforeach
  </tbody>
</table>
@include('facturama.facturas.functions')
