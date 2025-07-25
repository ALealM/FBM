@extends('layouts.app', ['activePage' => @$sActivePage])
@section('content')
  <style>
  .project-status{
    padding-top: 1px !important;
    padding-bottom: 1px !important;
  }
  </style>

  <div class="mb-2 mt-2">
    <a href="javascript:;" class="btn btn-success" onclick="imprimir()"><i class="fa fa-print mr-2"></i>Imprimir</a>
  </div>
  
  <div class="card-body row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 table-responsive">
      <table class="table table-condensed table-striped table-hover dataTable" role='grid' id="example1">
        <tbody>
          <tr>
            <th>Tasa de crecimiento de ingresos anualizada</th>
            <th></th>
            <th>
              {{$aTasas[0]}}%
              <!--Form::text('taza1',$aTasas[0],['placeholder'=>'0','style'=>'width:50px','id'=>'taza1','onkeyup'=>'cambiaTaza(1)'])-->
            </th>
            <th>
              {{$aTasas[1]}}%
              <!--Form::text('taza2',$aTasas[1],['placeholder'=>'0','style'=>'width:50px','id'=>'taza2','onkeyup'=>'cambiaTaza(2)'])-->
            </th>
            <th>
              {{$aTasas[2]}}%
              <!--Form::text('taza3',$aTasas[2],['placeholder'=>'0','style'=>'width:50px','id'=>'taza3','onkeyup'=>'cambiaTaza(3)'])-->
            </th>
            <th>
              {{$aTasas[3]}}%
              <!--Form::text('taza4',$aTasas[3],['placeholder'=>'0','style'=>'width:50px','id'=>'taza4','onkeyup'=>'cambiaTaza(4)'])-->
            </th>
            <th>
              {{$aTasas[4]}}%
              <!--Form::text('taza5',$aTasas[4],['placeholder'=>'0','style'=>'width:50px','id'=>'taza5','onkeyup'=>'cambiaTaza(5)'])-->
            </th>
          </tr>
          <tr>
            <th>Año</th>
            <th>{{date('Y')}}</th>
            <th>{{date('Y', strtotime('+1 years'))}}</th>
            <th>{{date('Y', strtotime('+2 years'))}}</th>
            <th>{{date('Y', strtotime('+3 years'))}}</th>
            <th>{{date('Y', strtotime('+4 years'))}}</th>
            <th>{{date('Y', strtotime('+5 years'))}}</th>
          </tr>
          <tr>
            <th>Ingresos</th>
            <td><small>$</small>{{number_format( $fIngresos ,2,".",",")}}</td>
            <td><small>$</small>{{number_format($aIngresos[0],2,".",",")}}</td>
            <td><small>$</small>{{number_format($aIngresos[1],2,".",",")}}</td>
            <td><small>$</small>{{number_format($aIngresos[2],2,".",",")}}</td>
            <td><small>$</small>{{number_format($aIngresos[3],2,".",",")}}</td>
            <td><small>$</small>{{number_format($aIngresos[4],2,".",",")}}</td>
          </tr>
          <tr>
            <?php
            $e1 = $aIngresos[0]*(1-$aMargenes[0]/100);
            $e2 = $aIngresos[1]*(1-$aMargenes[1]/100);
            $e3 = $aIngresos[2]*(1-$aMargenes[2]/100);
            $e4 = $aIngresos[3]*(1-$aMargenes[3]/100);
            $e5 = $aIngresos[4]*(1-$aMargenes[4]/100);
            ?>
            <th>Egresos</th>
            <td><small>$</small>{{number_format($fEgresos,2,".",",")}}</td>
            <td><small>$</small>{{number_format($e1,2,".",",")}}</td>
            <td><small>$</small>{{number_format($e2,2,".",",")}}</td>
            <td><small>$</small>{{number_format($e3,2,".",",")}}</td>
            <td><small>$</small>{{number_format($e4,2,".",",")}}</td>
            <td><small>$</small>{{number_format($e5,2,".",",")}}</td>
          </tr>
          <tr>
            <th>Margen de Utilidad</th>
            <td>{{round( ($fEgresos/$fIngresos) * 100 )}}%</td>
            <td>
              {{$aMargenes[0]}}%
              <!--Form::text('margen1',$aMargenes[0],['placeholder'=>'0','style'=>'width:50px','id'=>'margen1','onkeyup'=>'cambiaMargen(1)'])-->
            </td>
            <td>
              {{$aMargenes[1]}}%
              <!--Form::text('margen2',$aMargenes[1],['placeholder'=>'0','style'=>'width:50px','id'=>'margen2','onkeyup'=>'cambiaMargen(2)'])-->
            </td>
            <td>
              {{$aMargenes[2]}}%
              <!--Form::text('margen3',$aMargenes[2],['placeholder'=>'0','style'=>'width:50px','id'=>'margen3','onkeyup'=>'cambiaMargen(3)'])-->
            </td>
            <td>
              {{$aMargenes[3]}}%
              <!--Form::text('margen4',$aMargenes[3],['placeholder'=>'0','style'=>'width:50px','id'=>'margen4','onkeyup'=>'cambiaMargen(4)'])-->
            </td>
            <td>
              {{$aMargenes[4]}}%
              <!--Form::text('margen5',$aMargenes[4],['placeholder'=>'0','style'=>'width:50px','id'=>'margen5','onkeyup'=>'cambiaMargen(5)'])-->
            </td>
          </tr>
          <tr>
            <th>EBIDTA</th>
            <td><small>$</small>{{number_format( $fIngresos - $fEgresos ,2,".",",")}}</td>
            <td><small>$</small>{{number_format($aIngresos[0]-$e1,2,".",",")}}</td>
            <td><small>$</small>{{number_format($aIngresos[1]-$e2,2,".",",")}}</td>
            <td><small>$</small>{{number_format($aIngresos[2]-$e3,2,".",",")}}</td>
            <td><small>$</small>{{number_format($aIngresos[3]-$e4,2,".",",")}}</td>
            <td><small>$</small>{{number_format($aIngresos[4]-$e5,2,".",",")}}</td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
  <div class="card-body row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 table-responsive">
      <table class="table table-condensed table-striped table-hover dataTable" role='grid' id="example1">
        <tbody>
          <tr>
            <th style="width: 200px"><div style="width: 200px">VPN de EBITDA a 5 años con Tasa de Descuento</div></th>
            <th style="width: 200px">
              <div style="width: 200px">
                {{$fDescuento}}%
                <!--Form::text('descuento',$fDescuento,['placeholder'=>'0','style'=>'width:50px','id'=>'descuento','onkeyup'=>'cambiaDesc(1)'])-->
              </div>
            </th>
            <?php $vpn = (($aIngresos[0]-$e1)/(1+$fDescuento/100))+(($aIngresos[1]-$e2)/((1+$fDescuento/100)**2))+(($aIngresos[2]-$e3)/((1+$fDescuento/100)**3))+(($aIngresos[3]-$e4)/((1+$fDescuento/100)**4))+(($aIngresos[4]-$e5)/((1+$fDescuento/100)**5))?>
            <th style="width: 200px"><div style="width: 200px"><small>$</small>{{number_format($vpn,2,".",",")}}</div></th>
            <th></th>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
  @if ($aAccionistas[0] > 0 || $aAccionistas[1] > 0 || $aAccionistas[2] > 0)
    <div class="card-body row">
      <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 table-responsive">
        <table class="table table-condensed table-striped table-hover dataTable" role='grid' id="example1">
          <tbody>
            <tr>
              <th>Accionistas</th>
              <th>%</th>
              <th></th>
              <th></th>
            </tr>
            @if ($aAccionistas[0] > 0)
              <tr>
                <td style="width: 200px"><div style="width: 200px">Socio A</div></td>
                <td style="width: 200px"><div style="width: 200px">{{$aAccionistas[0]}}%</div></td>
                <td style="width: 200px"><div style="width: 200px"><small>$</small>{{number_format($vpn * ($aAccionistas[0]/100),2,".",",")}}</div></td>
                <td></td>
              </tr>
            @endif
            @if ($aAccionistas[1] > 0)
              <tr>
                <td style="width: 200px"><div style="width: 200px">Socio B</div></td>
                <td style="width: 200px"><div style="width: 200px">{{$aAccionistas[1]}}%</div></td>
                <td style="width: 200px"><div style="width: 200px"><small>$</small>{{number_format($vpn * ($aAccionistas[1]/100),2,".",",")}}</div></td>
                <td></td>
              </tr>
            @endif
            @if ($aAccionistas[2] > 0)
              <tr>
                <td style="width: 200px"><div style="width: 200px">Socio C</div></td>
                <td style="width: 200px"><div style="width: 200px">{{$aAccionistas[2]}}%</div></td>
                <td style="width: 200px"><div style="width: 200px"><small>$</small>{{number_format($vpn * ($aAccionistas[2]/100),2,".",",")}}</div></td>
                <td></td>
              </tr>
            @endif
          </tbody>
        </table>
      </div>
    </div>
  @endif



@endsection
