<script>
function generar_valuacion()
{
  $("#myModalLabel").html('Generar valuación');
  $("#myModalBody").html(
    '<p>Ingresa los datos para generar la valuación.</p><br/>' +
    '<form id="form2" method="GET" action="{{url( "proyecciones/valuacion" )}}" autocomplete="off" accept-charset="UTF-8" class="form-horizontal" enctype="multipart/form-data">' +
      '{{ csrf_field() }}' +
      '<div class="row">'+
      '<label class="col-sm-5 col-form-label">Ingresos <small>($)</small></label>' +
      '<div class="col-sm-7">{!! Form::number('ingresos',null,['id'=>'ingresos','class'=>'form-control inputSlim','required','step'=>'any','min'=>0.01,'placeholder'=>'Ingresos']) !!}</div>' +
      '</div>' +
      '<div class="row">'+
      '<label class="col-sm-5 col-form-label">Egresos <small>($)</small></label>' +
      '<div class="col-sm-7">{!! Form::number('egresos',null,['id'=>'egresos','class'=>'form-control inputSlim','required','step'=>'any','min'=>0.01,'placeholder'=>'Egresos']) !!}</div>' +
      '</div>' +
      '<div class="row">'+
      '<label class="col-sm-5 col-form-label">Tasa de descuento <small>(%)</small></label>' +
      '<div class="col-sm-7">{!! Form::number('descuento',0,['class'=>'form-control inputSlim','required','step'=>'any','min'=>0,'placeholder'=>'Descuento']) !!}</div>' +
      '</div>' +
      '<br><div class="row">' +
      //TASAS DE CRECIMIENTO
      '<div class="col-6">' +
      '<label class="col-12 col-form-label">Tasas de crecimiento</label>' +
      '<div class="row ml-4">'+
      '<label class="col-sm-5 col-form-label">{{date('Y', strtotime('+1 years'))}} <small>(%)</small></label>' +
      '<div class="col-sm-7">{!! Form::number('tasa_0',10,['class'=>'form-control inputSlim','required','step'=>'any','min'=>0.01,'placeholder'=>'%']) !!}</div>' +
      '</div>' +
      '<div class="row ml-4">'+
      '<label class="col-sm-5 col-form-label">{{date('Y', strtotime('+2 years'))}} <small>(%)</small></label>' +
      '<div class="col-sm-7">{!! Form::number('tasa_1',10,['class'=>'form-control inputSlim','required','step'=>'any','min'=>0.01,'placeholder'=>'%']) !!}</div>' +
      '</div>' +
      '<div class="row ml-4">'+
      '<label class="col-sm-5 col-form-label">{{date('Y', strtotime('+3 years'))}} <small>(%)</small></label>' +
      '<div class="col-sm-7">{!! Form::number('tasa_2',10,['class'=>'form-control inputSlim','required','step'=>'any','min'=>0.01,'placeholder'=>'%']) !!}</div>' +
      '</div>' +
      '<div class="row ml-4">'+
      '<label class="col-sm-5 col-form-label">{{date('Y', strtotime('+4 years'))}} <small>(%)</small></label>' +
      '<div class="col-sm-7">{!! Form::number('tasa_3',10,['class'=>'form-control inputSlim','required','step'=>'any','min'=>0.01,'placeholder'=>'%']) !!}</div>' +
      '</div>' +
      '<div class="row ml-4">'+
      '<label class="col-sm-5 col-form-label">{{date('Y', strtotime('+5 years'))}} <small>(%)</small></label>' +
      '<div class="col-sm-7">{!! Form::number('tasa_4',10,['class'=>'form-control inputSlim','required','step'=>'any','min'=>0.01,'placeholder'=>'%']) !!}</div>' +
      '</div>' +
      '</div>' +
      //MARGENES DE UTILIDAD
      '<div class="col-6">' +
      '<label class="col-12 col-form-label">Margen de utilidad</label>' +
      '<div class="row ml-4">'+
      '<label class="col-sm-5 col-form-label">{{date('Y', strtotime('+1 years'))}} <small>(%)</small></label>' +
      '<div class="col-sm-7">{!! Form::number('margen_0',10,['class'=>'form-control inputSlim','required','step'=>'any','min'=>0.01,'placeholder'=>'%']) !!}</div>' +
      '</div>' +
      '<div class="row ml-4">'+
      '<label class="col-sm-5 col-form-label">{{date('Y', strtotime('+2 years'))}} <small>(%)</small></label>' +
      '<div class="col-sm-7">{!! Form::number('margen_1',10,['class'=>'form-control inputSlim','required','step'=>'any','min'=>0.01,'placeholder'=>'%']) !!}</div>' +
      '</div>' +
      '<div class="row ml-4">'+
      '<label class="col-sm-5 col-form-label">{{date('Y', strtotime('+3 years'))}} <small>(%)</small></label>' +
      '<div class="col-sm-7">{!! Form::number('margen_2',10,['class'=>'form-control inputSlim','required','step'=>'any','min'=>0.01,'placeholder'=>'%']) !!}</div>' +
      '</div>' +
      '<div class="row ml-4">'+
      '<label class="col-sm-5 col-form-label">{{date('Y', strtotime('+4 years'))}} <small>(%)</small></label>' +
      '<div class="col-sm-7">{!! Form::number('margen_3',10,['class'=>'form-control inputSlim','required','step'=>'any','min'=>0.01,'placeholder'=>'%']) !!}</div>' +
      '</div>' +
      '<div class="row ml-4">'+
      '<label class="col-sm-5 col-form-label">{{date('Y', strtotime('+5 years'))}} <small>(%)</small></label>' +
      '<div class="col-sm-7">{!! Form::number('margen_4',10,['class'=>'form-control inputSlim','required','step'=>'any','min'=>0.01,'placeholder'=>'%']) !!}</div>' +
      '</div>' +
      '</div>' +
      '</div>' +
      //ACCIONISTAS
      '<br><label class="col-12 col-form-label">Porcentaje de ganancias de accionistas</label>' +
      '<div class="ml-4">' +
      '<div class="row">'+
      '<label class="col-sm-5 col-form-label">Accionista A <small>(%)</small></label>' +
      '<div class="col-sm-7">{!! Form::number('accionista_a',0,['class'=>'form-control inputSlim','step'=>'any','min'=>0,'placeholder'=>'%']) !!}</div>' +
      '</div>' +
      '<div class="row">'+
      '<label class="col-sm-5 col-form-label">Accionista B <small>(%)</small></label>' +
      '<div class="col-sm-7">{!! Form::number('accionista_b',0,['class'=>'form-control inputSlim','step'=>'any','min'=>0,'placeholder'=>'%']) !!}</div>' +
      '</div>' +
      '<div class="row">'+
      '<label class="col-sm-5 col-form-label">Accionista C <small>(%)</small></label>' +
      '<div class="col-sm-7">{!! Form::number('accionista_c',0,['class'=>'form-control inputSlim','step'=>'any','min'=>0,'placeholder'=>'%']) !!}</div>' +
      '</div>' +
      '</div>' +

      '<div class="card-footer text-center">' +
        '<button type="submit" form="form2" class="btn btn-success btn-sm"><i class="fa fa-magic mr-2"></i>Generar</button>' +
        '<a href="javascript:;" class="btn btn-secondary btn-sm" data-dismiss="modal" aria-label="Close"><i class="fa fa-rotate-left mr-2"></i>Cancelar</a>' +
      '</div>' +
    '</form>'
  );
  $("#ingresos").val({{@$fTotalIngresos}});
  $("#egresos").val({{@$fTotalEgresos}});

  $("#myModal").modal();
}
</script>
