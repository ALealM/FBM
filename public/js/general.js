/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */



function notificacion_confirm(a,titulo,mensaje,tipo){
    swal(
            {
                title: titulo,
                text: mensaje,
                type: tipo,
                showCancelButton: true,
                confirmButtonClass: "btn-danger",
                confirmButtonText: "Si",
                cancelButtonText: "No",
                closeOnConfirm: false,
                closeOnCancel: true
            }, function(isConfirm){
                if (isConfirm) {
                    window.location.href = a.attr('href');
                } else {

                } }
        );
    return false;
}

function notificacion(titulo,mensaje,tipo){
    swal({   title: titulo,   text:mensaje,   type: tipo, });
}

function isNumberCant(e, valInicial, nEntero, nDecimal)
{
    var dec = nDecimal - 1;
    var obj = e.srcElement || e.target;
    var tecla_codigo = (document.all) ? e.keyCode : e.which;
    var tecla_valor = String.fromCharCode(tecla_codigo);
    var patron2 = /[\d.]/;
    var control = (tecla_codigo === 46 && (/[.]/).test(obj.value)) ? false : true;
    var existePto = (/[.]/).test(obj.value);

    //el tab
    if (tecla_codigo === 8)
        return true;

    if (valInicial !== obj.value) {
        var TControl = obj.value.length;
        if (existePto === false && tecla_codigo !== 46) {
            if (TControl === nEntero) {
                obj.value = obj.value + ".";
            }
        }

        if (existePto === true) {
            var subVal = obj.value.substring(obj.value.indexOf(".") + 1, obj.value.length);

            if (subVal.length > dec) {
                return false;
            }
        }

        return patron2.test(tecla_valor) && control;
    } else {
        if (valInicial === obj.value) {
            obj.value = '';
        }
        return patron2.test(tecla_valor) && control;
    }
}

function addEtapa(){
    var tbl = document.getElementById('tableEtapas');
    var lastRow = tbl.rows.length;
    var row = tbl.insertRow(lastRow);

    var num = row.insertCell(0);
    var desc = row.insertCell(1);
    var feci = row.insertCell(2);
    var fecf = row.insertCell(3);
    var por = row.insertCell(4);
    var pres = row.insertCell(5);
    var obr = row.insertCell(6);
    var ac = row.insertCell(7);

    num.className = 'tdSlim';
    num.innerHTML = lastRow;
    desc.className = 'tdSlim';
    desc.innerHTML = '<textarea class="form-control" placeholder="Descripción de la etapa '+lastRow+'" required="required" rows="3" style="resize:none" name="desc_etapa[]" cols="50"></textarea>';
    feci.className = 'tdSlim';
    feci.innerHTML = '<input class="form-control datepicker" placeholder="Fecha inicial de la etapa '+lastRow+'" required="required" name="fechai_etapa[]" type="text">';
    fecf.className = 'tdSlim';
    fecf.innerHTML = '<input class="form-control datepicker" placeholder="Fecha final de la etapa '+lastRow+'" required="required" name="fechaf_etapa[]" type="text">';
    por.className = 'tdSlim';
    por.innerHTML = '<input class="form-control" placeholder="Porcentaje de la etapa '+lastRow+'" required="required" name="por_etapa[]" type="text">';
    pres.className = 'tdSlim';
    pres.innerHTML = '<input class="form-control" placeholder="Presupuesto de la etapa '+lastRow+'" required="required" name="pres_etapa[]" type="text" onKeyPress="return isNumberCant(event,' + "'0.00'" + ',10,2)">';
    obr.className = 'tdSlim';
    obr.innerHTML = '<input class="form-control" required="required" name="obr_etapa[]" type="text" onKeyPress="return isNumberInt(event)">';
    ac.className = 'tdSlim';
    ac.innerHTML = '<input name="id_etapa[]" type="hidden" value="0"><a class="btn btn-info btn-xs" onclick="deleteRow(this.parentNode.parentNode.rowIndex,' + "'tableEtapas'" + ')" style="cursor:pointer" title="Eliminar etapa">Borrar</a>';

    $('.datepicker').datepicker({
        format: "dd/mm/yyyy"
    });

    return false;
}

function deleteRow(rowIndex, nameTable) {

    var table = document.getElementById(nameTable);
//    var lastRow = table.rows.length -1;
//    if(lastRow<=rowIndex)
    table.deleteRow(rowIndex);
}

function isNumberInt(evt)
{
    var charCode = (evt.which) ? evt.which : event.keyCode;
    if (charCode > 31 && (charCode < 48 || charCode > 57))
        return false;
    return true;
}

function getPrograma()
{
    var rubro = document.getElementById('rubro').value;
    $("#subprograma > option").remove();
    $("#proyecto_ > option").remove();
    $('#subprograma').append('<option  value="">Seleccione subprograma...</option>');
    $('#proyecto_').append('<option  value="">Seleccione proyecto...</option>');
    var BASE_URL = window.location.protocol + "//" + window.location.host+ "/";
    $.get(BASE_URL + "getPrograma", {'rubro': rubro}, function (respuesta) {
        $("#programa > option").remove();
        $('#programa').append('<option  value="">Seleccione programa...</option>');
        $(respuesta).each(function (i, v) { // indice, valor
            $('#programa').append('<option value="'+v.programa+'">'+v.programa+'</option>');
        })
    });
}

function getSubprograma()
{
    var programa = document.getElementById('programa').value;
    $("#proyecto_ > option").remove();
    $('#proyecto_').append('<option  value="">Seleccione proyecto...</option>');
    var BASE_URL = window.location.protocol + "//" + window.location.host+ "/";
    $.get(BASE_URL + "getSubprograma", {'programa': programa}, function (respuesta) {
        $("#subprograma > option").remove();
        $('#subprograma').append('<option  value="">Seleccione subprograma...</option>');
        $(respuesta).each(function (i, v) { // indice, valor
            $('#subprograma').append('<option value="'+v.subprograma+'">'+v.subprograma+'</option>');
        })
    });
}

function getProyecto()
{
    var programa = document.getElementById('programa').value;
    var subprograma = document.getElementById('subprograma').value;
    var BASE_URL = window.location.protocol + "//" + window.location.host+ "/";
    $.get(BASE_URL + "getProyecto", {'subprograma': subprograma,'programa': programa}, function (respuesta) {
        $("#proyecto_ > option").remove();
        $('#proyecto_').append('<option  value="">Seleccione proyecto...</option>');
        $(respuesta).each(function (i, v) { // indice, valor
            if(v.proyecto == null){
                $("#proyecto_ > option").remove();
                $('#proyecto_').append('<option  value="0">No Aplica</option>');
            }
            else{
                $('#proyecto_').append('<option value="'+v.proyecto+'">'+v.proyecto+'</option>');
            }
        })
    });
}

function getLocalidad()
{
    var municipio = document.getElementById('ubicacion_macro').value;
    var BASE_URL = window.location.protocol + "//" + window.location.host+ "/";
    $.get(BASE_URL + "getLocalidad", {'municipio': municipio}, function (respuesta) {
        $("#ubicacion_micro > option").remove();
        $('#ubicacion_micro').append('<option  value="">Seleccione localidad...</option>');
        $(respuesta).each(function (i, v) { // indice, valor
            $('#ubicacion_micro').append('<option value="'+v.id+'">'+v.localidad+'</option>');
        })
    });
}

function getSituacion()
{
    var situacion = document.getElementById('situacion').value;
    var avance = document.getElementById('avance').value;
    console.log(situacion);
    if(situacion == 1 || situacion == 2 || situacion == 3){
        $('#siguiente_verificacion').attr('readonly','readonly');
        $('#siguiente_verificacion').attr('disabled','disabled');
        $('#avance').attr('readonly','readonly');
        $('#avance').val('100');
        $('#fotografias').removeAttr('readonly');
        $('#fotografias').removeAttr('disabled');
        $('#fotografias').attr('required','required');
    }else{
        if(situacion == 6 || situacion == 7 || situacion == 8 || situacion == 9 ){
        $('#fotografias').attr('readonly','readonly');
        $('#fotografias').attr('disabled','disabled');
        $('#fotografias').removeAttr('required');
        $('#siguiente_verificacion').removeAttr('readonly');
        $('#siguiente_verificacion').removeAttr('disabled');
        $('#avance').removeAttr('readonly');
        $('#avance').attr('required','required');
        $('#avance').val(avance);
        }else{
            $('#siguiente_verificacion').removeAttr('readonly');
            $('#siguiente_verificacion').removeAttr('disabled');
            $('#avance').removeAttr('readonly');
            $('#avance').attr('required','required');
            $('#avance').val(avance);
            $('#fotografias').removeAttr('readonly');
            $('#fotografias').removeAttr('disabled');
            $('#fotografias').attr('required','required');
        }
    }
}

function getLocalidad_()
{
    $("#claveMun").val('');
    $("#claveLoc").val('');
    var municipio = document.getElementById('municipio').value;
    var BASE_URL = window.location.protocol + "//" + window.location.host+ "/";
    $.get(BASE_URL + "getLocalidad", {'municipio': municipio}, function (respuesta) {
        console.log(respuesta[0]);
        $("#claveMun").val(respuesta[0].claveM);
        $("#localidad > option").remove();
        $('#localidad').append('<option  value="">Seleccione localidad...</option>');
        $(respuesta).each(function (i, v) { // indice, valor
            $('#localidad').append('<option value="'+v.id+'">'+v.localidad+'</option>');
        })
    });
}

function getClaveLocalidad()
{
    var localidad = document.getElementById('localidad').value;
    var BASE_URL = window.location.protocol + "//" + window.location.host+ "/";
    $.get(BASE_URL + "getClaveLocalidad", {'localidad': localidad}, function (respuesta) {
        $("#claveLoc").val('');
        $("#claveLoc").val(respuesta);
    });
}



function obraAcc()
{
    var obraAccion = document.getElementById('obraAccion').value;
    if(obraAccion==1){
        $('#benefAccionDiv').removeAttr('style');
        $('#adjuntaCuisDiv').removeAttr('style');
    }
    else{
        $('#benefAccionDiv').attr('style','display:none');
        $('#adjuntaCuisDiv').attr('style','display:none');
    }
}

function getUbicacionMicro()
{
    var municipio = document.getElementById('ubicacion_macro').value;
    var BASE_URL = window.location.protocol + "//" + window.location.host+ "/";
    $.get(BASE_URL + "getLocalidad", {'municipio': municipio}, function (respuesta) {
        $("#ubicacion_micro > option").remove();
        $('#ubicacion_micro').append('<option  value="">Seleccione la ubicacion micro...</option>');
        $(respuesta).each(function (i, v) { // indice, valor
            $('#ubicacion_micro').append('<option value="'+v.id+'">'+v.localidad+'</option>');
        })
    });
}

function cargaCompromiso(id)
{
    var BASE_URL = window.location.protocol + "//" + window.location.host+ "/";
//    $("#estatusComp > option").remove();
//    $('#compromisoDesc').val('');
//    $('#btnCompromiso').removeAttr('onclick');
//    $.get(BASE_URL + "cargaCompromiso", {'id': id}, function (r) {
//        if(r.id_estatus==1){
//            $('#estatusComp').append('<option selected value="1">Cumplido</option><option value="2">En proceso</option><option value="3">Sin iniciar</option>');
//        }
//        if(r.id_estatus==2){
//            $('#estatusComp').append('<option value="1">Cumplido</option><option selected value="2">En proceso</option><option value="3">Sin iniciar</option>');
//        }
//        if(r.id_estatus==3){
//            $('#estatusComp').append('<option value="1">Cumplido</option><option value="2">En proceso</option><option selected value="3">Sin iniciar</option>');
//        }
//        $('#compromisoDesc').val(r.compromiso);
//        $('#btnCompromiso').attr('onclick','editaCompromiso('+r.id+')');
//        $('#modalCompromiso').modal();
//    });
    window.location.href = BASE_URL+"compromisos_gobernador/edit/"+id;
}

function editaCompromiso(id)
{
    var est = document.getElementById('estatusComp').value;
    var comp = document.getElementById('compromisoDesc').value;
    var BASE_URL = window.location.protocol + "//" + window.location.host+ "/";
    $.get(BASE_URL + "editaCompromiso", {'id': id, 'est': est, 'comp': comp}, function (r) {
        swal(
            {
                title: 'Edición exitosa!',
                text: 'Se ha actualizado correctamente la información del compromiso',
                type: 'success'
            }, function(){
                location.reload();
            }
        );
    });
}

function addIntegrante(){
    var tbl = document.getElementById('integrantes');
    var tbl2 = document.getElementById('integrantes2');
    var tbl3 = document.getElementById('integrantes3');
    var tbl4 = document.getElementById('integrantes4');
    var tbl5 = document.getElementById('integrantes5');
    var lastRow = tbl.rows.length;
    var row = tbl.insertRow(lastRow);
    var lastRow2 = tbl2.rows.length;
    var row2 = tbl2.insertRow(lastRow2);
    var lastRow3 = tbl3.rows.length;
    var row3 = tbl3.insertRow(lastRow3);
    var lastRow4 = tbl4.rows.length;
    var row4 = tbl4.insertRow(lastRow4);
    var lastRow5 = tbl5.rows.length;
    var row5 = tbl5.insertRow(lastRow5);

    var num = row.insertCell(0);
    var pat = row.insertCell(1);
    var mat = row.insertCell(2);
    var nom = row.insertCell(3);
    var num2 = row2.insertCell(0);
    var par = row2.insertCell(1);
    var tcurp = row2.insertCell(2);
    var curp = row2.insertCell(3);
    var num3 = row3.insertCell(0);
    var fnac = row3.insertCell(1);
    var sex = row3.insertCell(2);
    var lnac = row3.insertCell(3);
    var num4 = row4.insertCell(0);
    var seg = row4.insertCell(1);
    var seg2 = row4.insertCell(2);
    var niv = row4.insertCell(3);
    var grad = row4.insertCell(4);
    var asist = row4.insertCell(5);
    var num5 = row5.insertCell(0);
    var trab = row5.insertCell(1);
    var puesto = row5.insertCell(2);
    var ing = row5.insertCell(3);
    var per = row5.insertCell(4);

    num.className = 'tdSlim';
    num.innerHTML = lastRow;
    pat.className = 'tdSlim';
    pat.innerHTML = '<input class="form-control" placeholder="Apellido paterno" name="apPat[]" type="text">';
    mat.className = 'tdSlim';
    mat.innerHTML = '<input class="form-control" placeholder="Apellido materno" name="apMat[]" type="text">';
    nom.className = 'tdSlim';
    nom.innerHTML = '<input class="form-control" placeholder="Nombre(s)" name="nombre[]" type="text">';
    num2.className = 'tdSlim';
    num2.innerHTML = lastRow2;
    par.className = 'tdSlim';
    par.innerHTML = '<select class="form-control inputSlim" name="paren[]"><option value="1">01 - Jefe(a) del hogar</option><option value="2">02 - Conyugue/compañero(a)</option><option value="3">03 - Hijo(a)</option><option value="4">04 - Padre/madre</option><option value="5">05 - Hermano(a)</option><option value="6">06 - Nieto(a)</option><option value="7">07 - Nuera/Yerno</option><option value="8">08 - Suegro(a)</option><option value="9">09 - Hijastro(a)/Entenado(a)</option><option value="10">10 - Sobrino(a)</option><option value="11">11 - Otro parentesco</option><option value="12">12 - No tiene parentesco</option></select>';
    tcurp.className = 'tdSlim';
    tcurp.innerHTML = '<select class="form-control inputSlim" name="curpT[]"><option value="0">No</option><option value="1" selected="selected">Si</option></select>';
    curp.className = 'tdSlim';
    curp.innerHTML = '<input class="form-control inputSlim" placeholder="CURP " name="curp[]" type="text">';
    num3.innerHTML = lastRow3;
    fnac.className = 'tdSlim';
    fnac.innerHTML = '<input class="form-control inputSlim datepicker" placeholder="Fecha de nacimiento" name="fNac[]" type="text">';
    sex.className = 'tdSlim';
    sex.innerHTML = '<select class="form-control inputSlim" name="sex[]"><option value="1" selected="selected">Hombre</option><option value="2">Mujer</option></select>';
    lnac.className = 'tdSlim';
    lnac.innerHTML = '<select class="form-control inputSlim" name="lNac[]"><option value="1">01 - Aguascalientes</option><option value="2">02 - Baja Califormia</option><option value="3">03 - Baja California Sur</option><option value="4">04 - Campeche</option><option value="5">05 - Coahuila</option><option value="6">06 - Colima</option><option value="7">07 - Chiapas</option><option value="8">08 - Chihuahua</option><option value="9">09 - Distrito Federal</option><option value="10">10 - Durango</option><option value="11">11 - Guanajuato</option><option value="12">12 - Guerrero</option><option value="13">13 - Hidalgo</option><option value="14">14 - Jalisco</option><option value="15">15 - México</option><option value="16">16 - Michoacán</option><option value="17">17 - Morelos</option><option value="18">18 - Nayarit</option><option value="19">19 - Nuevo León</option><option value="20">20 - Oaxaca</option><option value="21">21 - Puebla</option><option value="22">22 - Querétaro</option><option value="23">23 - Quintana Roo</option><option value="24">24 - San Luis Potosí</option><option value="25">25 - Sinaloa</option><option value="26">26 - Sonora</option><option value="27">27 - Tabasco</option><option value="28">28 - Tamaulipas</option><option value="29">29 - Tlaxcala</option><option value="30">30 - Veracruz</option><option value="31">31 - Yucatán</option><option value="32">32 - Zacatecas</option><option value="33">33 - Extranjero</option></select>';
    num4.innerHTML = lastRow4-1;
    seg.className = 'tdSlim';
    seg.innerHTML = '<select class="form-control inputSlim" name="seguro[]"><option selected="selected" value="">Seleccione opción...</option><option value="1">01 - Seguro Popular</option><option value="2">02 - IMSS</option><option value="3">03 - ISSSTE</option><option value="4">04 - PEMEX, Defensa o Marina</option><option value="5">05 - Clínica u hospital privado</option><option value="6">99 - A ninguna</option></select>';
    seg2.className = 'tdSlim';
    seg2.innerHTML = '<select class="form-control inputSlim" name="seguro2[]"><option selected="selected" value="">Seleccione opción...</option><option value="1">01 - Seguro Popular</option><option value="2">02 - IMSS</option><option value="3">03 - ISSSTE</option><option value="4">04 - PEMEX, Defensa o Marina</option><option value="5">05 - Clínica u hospital privado</option><option value="6">99 - A ninguna</option></select>';
    niv.className = 'tdSlim';
    niv.innerHTML = '<select class="form-control inputSlim" name="nivel[]"><option selected="selected" value="">Seleccione opción...</option><option value="1">01 - Kinder/Preescolar</option><option value="2">02 - Primaria</option><option value="3">03 - Secundaria</option><option value="4">04 - Preparatoria/Bachillerato</option><option value="5">05 - Normal básica</option><option value="6">06 - Carrera técnica/comercial con primaria completa</option><option value="7">07 - Carrera técnica/comercial con secundaria completa</option><option value="8">08 - Carrera técnica/comercial con preparatoria completa</option><option value="9">09 - Profesional</option><option value="10">10 - Posgrado (maestría/doctorado)</option><option value="11">11 - Ninguno</option></select>';
    grad.className = 'tdSlim';
    grad.innerHTML = '<select class="form-control inputSlim" name="grado[]"><option selected="selected" value="">Seleccione opción...</option><option value="1">1 año</option><option value="2">2 años</option><option value="3">3 años</option><option value="4">4 años</option><option value="5">5 años</option><option value="6">6 años</option></select>';
    asist.className = 'tdSlim';
    asist.innerHTML = '<select class="form-control inputSlim" name="asistencia[]"><option selected="selected" value="">Seleccione opción...</option><option value="1">Sí</option><option value="2">No</option></select>';
    num5.innerHTML = lastRow5;
    trab.className = 'tdSlim';
    trab.innerHTML = '<select class="form-control inputSlim" name="trabajo[]"><option selected="selected" value="">Seleccione opción...</option><option value="1">01 - Trabajó</option><option value="2">02 - Tenía trabajo pero no trabajó</option><option value="3">03 - Estudió y trabajó</option><option value="4">04 - No trabajó ni buscó trabajo</option><option value="5">05 - Buscó trabajo</option><option value="6">99 - Estudió</option><option value="7">07 - Realizó quehaceres domésticos</option></select>';
    puesto.className = 'tdSlim';
    puesto.innerHTML = '<select class="form-control inputSlim" name="desempeno[]"><option selected="selected" value="">Seleccione opción...</option><option value="1">01 - Albañil</option><option value="2">02 - Artesano</option><option value="3">03 - Ayudante de algún oficio</option><option value="4">04 - Ayudante en rancho o negocio familiar sin retribución</option><option value="5">05 - Ayudante en rancho o negocio no familiar sin retribución</option><option value="6">06 - Chofer (transporte de pasajero o carga)</option><option value="7">07 - Ejidatario/Comunero</option><option value="8">08 - Empleado del gobierno</option><option value="9">09 - Empleado del sector privado</option><option value="10">10 - Empleado doméstico</option><option value="11">11 - Jornalero agrícola</option><option value="12">12 - Miembro de un grupo o organización de productores</option><option value="13">13 - Miembro de una cooperativa (de producción o servicios)</option><option value="14">14 - Obrero</option><option value="15">15 - Patrón o empleador de un negocio</option><option value="16">16 - Profesionista independiente</option><option value="17">17 - Promotor de desarrollo humano o gestor social</option><option value="18">18 - Trabajador por cuenta propia</option><option value="19">19 - Vendedor ambulante</option><option value="20">20 - Otra ocupación</option><option value="98">98 - No sabe/No responde</option></select>';
    ing.className = 'tdSlim';
    ing.innerHTML = '<input class="form-control inputSlim" onkeypress="return isNumberInt(event)" name="ingreso[]" type="text">';
    per.className = 'tdSlim';
    per.innerHTML = '<select class="form-control inputSlim" name="pago[]"><option selected="selected" value="">Seleccione opción...</option><option value="1">Diario</option><option value="2">Cada semana</option><option value="3">Cada 15 días</option><option value="4">Cada mes</option><option value="5">Cada año</option></select>';

    $('.datepicker').datepicker({
        format: "dd/mm/yyyy"
    });

    return false;
}

function tipoevento()
{
    var formato = document.getElementById('formato').value;

    $("#tipo_evento > option").remove();
    $("#masEventos").empty();
    $('#tipo_evento').append('<option  value="">Seleccione el tipo de evento...</option>');
    if(formato=='0'){
        $('.btnEv').removeAttr('style');
        $('.btnEv').attr('style','cursor:pointer');
        $('#tipo_evento').append('<option value="1">Inauguración de obra</option>');
        $('#tipo_evento').append('<option value="2">Entrega de apoyos</option>');
        $('#tipo_evento').append('<option value="3">Foro(académico, empresarial, social...)</option>');
        $('#tipo_evento').append('<option value="4">Firma de convenio</option>');
        $('#tipo_evento').append('<option value="5">Otro</option>');

    }
    else{
        $('.btnEv').removeAttr('style');
        $('.btnEv').attr('style','display:none');
        if(formato=='1'){
            $('#tipo_evento').append('<option value="6">Reunión de trabajo con algún grupo/institución/sector en el que no se considere la presencia de medios de comunicación(incluir información relevante)</option>');
        }
        else{

        }

    }
}

function otro()
{
    $('#otros').removeClass('oculto');
    var tipoE = document.getElementById('tipo_evento').value;
    if(tipoE < 5)
        $('#otros').addClass('oculto');
}

function addInversion(id){

    var tbl = document.getElementById('inversion');
    var lastRow = tbl.rows.length - 1;
    var row = tbl.insertRow(lastRow);

    var loc = row.insertCell(0);
    var fon = row.insertCell(1);
    var dep = row.insertCell(2);
    var desc = row.insertCell(3);
    var tot = row.insertCell(4);
    var fed = row.insertCell(5);
    var est = row.insertCell(6);
    var mun = row.insertCell(7);
//    var ac = row.insertCell(7);
    var localidades = '';
    var BASE_URL = window.location.protocol + "//" + window.location.host+ "/";
    $.get(BASE_URL + "getLocalidad", {'municipio': id}, function (respuesta) {
        $(respuesta).each(function (i, v) { // indice, valor
            localidades += '<option value="'+v.id+'">'+v.localidad+'</option>';
        })
        console.log(localidades);

    loc.className = 'tdSlim ';
    loc.innerHTML = '<select class="form-control SelectAuto" placeholder="Localidad" required="required" name="localidad[]">'+localidades+'</select><input name="idInv[]" type="hidden" value="0">';
    $('.SelectAuto').select2();
    fon.className = 'tdSlim';
    fon.innerHTML = '<input class="form-control" placeholder="Fondo" required="required" name="fondo[]" type="text">';
    dep.className = 'tdSlim';
    dep.innerHTML = '<input class="form-control" placeholder="Dep. Promotora" required="required" name="dependencia[]" type="text">';
    desc.className = 'tdSlim';
    desc.innerHTML = '<textarea class="form-control" placeholder="Descripción" required="required" rows="1" style="resize:none" name="descripcion[]" cols="50"></textarea>';
    tot.className = 'tdSlim';
    tot.innerHTML = '<input class="form-control total" placeholder="Total" required="required" name="total[]" type="text" onKeyPress="return isNumberCant(event,' + "'0.00'" + ',10,2)" onKeyUp="totales('+"'total','TotalInv'"+')">';
    fed.className = 'tdSlim';
    fed.innerHTML = '<input class="form-control federal" placeholder="Federal" required="required" name="federal[]" type="text" onKeyPress="return isNumberCant(event,' + "'0.00'" + ',10,2)" onKeyUp="totales('+"'federal','FederalInv'"+')">';
    est.className = 'tdSlim';
    est.innerHTML = '<input class="form-control estatal" placeholder="Estatal" required="required" name="estatal[]" type="text" onKeyPress="return isNumberCant(event,' + "'0.00'" + ',10,2)" onKeyUp="totales('+"'estatal','EstatalInv'"+')">';
    mun.className = 'tdSlim';
    mun.innerHTML = '<input class="form-control municipal" placeholder="Municipal" required="required" name="municipal[]" type="text" onKeyPress="return isNumberCant(event,' + "'0.00'" + ',10,2)" onKeyUp="totales('+"'municipal','MunicipalInv'"+')">';
//    ac.className = 'tdSlim';
//    ac.innerHTML = '<input name="id_etapa[]" type="hidden" value="0"><a class="btn btn-info btn-xs" onclick="deleteRow(this.parentNode.parentNode.rowIndex,' + "'tableEtapas'" + ')" style="cursor:pointer" title="Eliminar etapa">Borrar</a>';

    });

    return false;
}

function totales(clase,id) {
    var total = 0;
    $('.' + clase).each(
            function (index, value) {
                if($(this).val()=='')
                total = total;
            else
                total = total + eval($(this).val());
            });

    document.getElementById(id).value = total;
}


function totalesCedula(clase,id) {
    var total = 0;
    $('.' + clase).each(
            function (index, value) {
                if($(this).val()=='')
                total = total;
            else
                total = total + eval($(this).val());
            });

    document.getElementById(id).value = total;
}


function otraObra() {
    var tipoObra = document.getElementById('tipo_obra').value;
    if(tipoObra == 18){
        $('#otra').attr('required','required');
    }
    else{
        $('#otra').removeAttr('required');
    }
}


function borrarObra(id) {
    var BASE_URL = window.location.protocol + "//" + window.location.host+ "/";
    swal({title: "Borrar reporte de obras", text: "¡El reporte de obras seleccionado será borrado del sistema! \n¿Desea continuar?", type: "warning", showCancelButton: true, cancelButtonText: "Cancelar", confirmButtonText: "Si, borrar", closeOnConfirm: false}, function () {
        window.location.href= BASE_URL + "reporte_obras_adl/delete/"+id;
    });
}

function addServidor(){
    var tbl = document.getElementById('servidores');
    var lastRow = tbl.rows.length;
    var row = tbl.insertRow(lastRow);

    var nom = row.insertCell(0);
    var car = row.insertCell(1);
    var cor = row.insertCell(2);
    var tel = row.insertCell(3);
    var ac = row.insertCell(4);

    nom.className = 'tdSlim';
    nom.innerHTML = '<input class="form-control" placeholder="Nombre" required="required" name="servidor_nombre[]" type="text">';
    car.className = 'tdSlim';
    car.innerHTML = '<input class="form-control" placeholder="Cargo" required="required" name="servidor_cargo[]" type="text">';
    cor.className = 'tdSlim';
    cor.innerHTML = '<input class="form-control" placeholder="Correo" required="required" name="servidor_correo[]" type="email">';
    tel.className = 'tdSlim';
    tel.innerHTML = '<input class="form-control" placeholder="Teléfono" required="required" name="servidor_tel[]" type="text">';
    ac.className = 'tdSlim';
    ac.innerHTML = '<a class="btn btn-danger btn-xs" onclick="deleteRow(this.parentNode.parentNode.rowIndex,' + "'servidores'" + ')" style="cursor:pointer" title="Eliminar registro">Borrar</a>';

    return false;
}


function borrarCapRec(id) {
   var BASE_URL = window.location.protocol + "//" + window.location.host+ "/";
   swal({title: "Borrar capacitación recibida", text: "¡La capacitación seleccionada será borrada del sistema! \n¿Desea continuar?", type: "warning", showCancelButton: true, cancelButtonText: "Cancelar", confirmButtonText: "Sí, borrar", closeOnConfirm: false}, function () {
       window.location.href= BASE_URL + "capacitaciones_recibidas/delete/"+id;
   });
}

function borrarInforme(id) {
   var BASE_URL = window.location.protocol + "//" + window.location.host+ "/";
   swal({title: "Borrar Informe FAIS", text: "¡El informe será borrada del sistema! \n¿Desea continuar?", type: "warning", showCancelButton: true, cancelButtonText: "Cancelar", confirmButtonText: "Sí, borrar", closeOnConfirm: false}, function () {
       window.location.href= BASE_URL + "informe_fais/delete/"+id;
   });
}

function borrarSeguimiento(id) {
   var BASE_URL = window.location.protocol + "//" + window.location.host+ "/";
   swal({title: "Borrar reporte estatal", text: "¡El reporte será borrada del sistema! \n¿Desea continuar?", type: "warning", showCancelButton: true, cancelButtonText: "Cancelar", confirmButtonText: "Sí, borrar", closeOnConfirm: false}, function () {
       window.location.href= BASE_URL + "reporte_estatal_seguimiento/delete/"+id;
   });
}

function borrarVerificacion(id) {
   var BASE_URL = window.location.protocol + "//" + window.location.host+ "/";
   swal({title: "Borrar reporte estatal", text: "¡El reporte será borrada del sistema! \n¿Desea continuar?", type: "warning", showCancelButton: true, cancelButtonText: "Cancelar", confirmButtonText: "Sí, borrar", closeOnConfirm: false}, function () {
       window.location.href= BASE_URL + "reporte_estatal_verificacion/delete/"+id;
   });
}

function borrarCalendario(id) {
   var BASE_URL = window.location.protocol + "//" + window.location.host+ "/";
   swal({title: "Borrar calendario", text: "¡El caledario será borrada del sistema! \n¿Desea continuar?", type: "warning", showCancelButton: true, cancelButtonText: "Cancelar", confirmButtonText: "Sí, borrar", closeOnConfirm: false}, function () {
       window.location.href= BASE_URL + "calendario_avance_fisico/delete/"+id;
   });
}

function borrarImpacto(id) {
   var BASE_URL = window.location.protocol + "//" + window.location.host+ "/";
   swal({title: "Borrar informe", text: "¡El informe será borrada del sistema! \n¿Desea continuar?", type: "warning", showCancelButton: true, cancelButtonText: "Cancelar", confirmButtonText: "Sí, borrar", closeOnConfirm: false}, function () {
       window.location.href= BASE_URL + "informe_impacto_global/delete/"+id;
   });
}

function borrarPlanosCroquis(id) {
   var BASE_URL = window.location.protocol + "//" + window.location.host+ "/";
   swal({title: "Borrar archivo", text: "¡El archivo será borrada del sistema! \n¿Desea continuar?", type: "warning", showCancelButton: true, cancelButtonText: "Cancelar", confirmButtonText: "Sí, borrar", closeOnConfirm: false}, function () {
       window.location.href= BASE_URL + "archivo_planos_croquis/delete/"+id;
   });
}

function borrarMemoria(id) {
   var BASE_URL = window.location.protocol + "//" + window.location.host+ "/";
   swal({title: "Borrar archivo", text: "¡La memoria descriptiva será borrada del sistema! \n¿Desea continuar?", type: "warning", showCancelButton: true, cancelButtonText: "Cancelar", confirmButtonText: "Sí, borrar", closeOnConfirm: false}, function () {
       window.location.href= BASE_URL + "archivo_memoria_descriptiva/delete/"+id;
   });
}

function borrarAceptacion(id) {
   var BASE_URL = window.location.protocol + "//" + window.location.host+ "/";
   swal({title: "Borrar archivo", text: "¡El acta de aceptación será borrada del sistema! \n¿Desea continuar?", type: "warning", showCancelButton: true, cancelButtonText: "Cancelar", confirmButtonText: "Sí, borrar", closeOnConfirm: false}, function () {
       window.location.href= BASE_URL + "archivo_acta_aceptacion/delete/"+id;
   });
}

function borrarConstitutiva(id) {
   var BASE_URL = window.location.protocol + "//" + window.location.host+ "/";
   swal({title: "Borrar acta", text: "¡El acta constitutiva será borrada del sistema! \n¿Desea continuar?", type: "warning", showCancelButton: true, cancelButtonText: "Cancelar", confirmButtonText: "Sí, borrar", closeOnConfirm: false}, function () {
       window.location.href= BASE_URL + "acta_constitutiva_comite/delete/"+id;
   });
}

function borrarPriorizacion(id) {
   var BASE_URL = window.location.protocol + "//" + window.location.host+ "/";
   swal({title: "Borrar acta", text: "¡El acta será borrada del sistema! \n¿Desea continuar?", type: "warning", showCancelButton: true, cancelButtonText: "Cancelar", confirmButtonText: "Sí, borrar", closeOnConfirm: false}, function () {
       window.location.href= BASE_URL + "archivo_acta_priorizacion/delete/"+id;
   });
}


function borrarReunion(id) {
    var BASE_URL = window.location.protocol + "//" + window.location.host+ "/";
    swal({title: "Borrar formato de reuniones ciudadanas", text: "¡El formato de reuniones ciudadanas seleccionado será borrado del sistema! \n¿Desea continuar?", type: "warning", showCancelButton: true, cancelButtonText: "Cancelar", confirmButtonText: "Si, borrar", closeOnConfirm: false}, function () {
        window.location.href= BASE_URL + "reuniones_ciudadanas/delete/"+id;
    });
}

function borrarCuis(id) {
    var BASE_URL = window.location.protocol + "//" + window.location.host+ "/";
    swal({title: "Borrar reporte de CUIS del ADL", text: "¡El reporte de CUIS del ADL seleccionado será borrado del sistema! \n¿Desea continuar?", type: "warning", showCancelButton: true, cancelButtonText: "Cancelar", confirmButtonText: "Si, borrar", closeOnConfirm: false}, function () {
        window.location.href= BASE_URL + "reporte_cuis_adl/delete/"+id;
    });
}

function borrarCapRealizada(id) {
    var BASE_URL = window.location.protocol + "//" + window.location.host+ "/";
    swal({title: "Borrar reporte de capacitaciones realizadas", text: "¡El reporte de capacitaciones realizadas seleccionado será borrado del sistema! \n¿Desea continuar?", type: "warning", showCancelButton: true, cancelButtonText: "Cancelar", confirmButtonText: "Si, borrar", closeOnConfirm: false}, function () {
        window.location.href= BASE_URL + "capacitaciones_realizadas/delete/"+id;
    });
}

function borrarAsesoria(id) {
    var BASE_URL = window.location.protocol + "//" + window.location.host+ "/";
    swal({title: "Borrar reporte de asesorias consultas", text: "¡El reporte de asesorías consultas seleccionado será borrado del sistema! \n¿Desea continuar?", type: "warning", showCancelButton: true, cancelButtonText: "Cancelar", confirmButtonText: "Si, borrar", closeOnConfirm: false}, function () {
        window.location.href= BASE_URL + "asesorias_consultas_adl/delete/"+id;
    });
}

function borrarIncidencia(id) {
    var BASE_URL = window.location.protocol + "//" + window.location.host+ "/";
    swal({title: "Borrar formato de incidencias", text: "¡El formato de incidencias seleccionado será borrado del sistema! \n¿Desea continuar?", type: "warning", showCancelButton: true, cancelButtonText: "Cancelar", confirmButtonText: "Si, borrar", closeOnConfirm: false}, function () {
        window.location.href= BASE_URL + "incidencias/delete/"+id;
    });
}

function borrarEntrega(id) {
    var BASE_URL = window.location.protocol + "//" + window.location.host+ "/";
    swal({title: "Borrar formato de entrega-recepción", text: "¡El formato de entrega-recepción de obras realizadas con recursos FAIS seleccionado será borrado del sistema! \n¿Desea continuar?", type: "warning", showCancelButton: true, cancelButtonText: "Cancelar", confirmButtonText: "Si, borrar", closeOnConfirm: false}, function () {
        window.location.href= BASE_URL + "entrega_recepcion_obras/delete/"+id;
    });
}

function borrarCedula(id) {
    var BASE_URL = window.location.protocol + "//" + window.location.host+ "/";
    swal({title: "Borrar cédula de registro de obra", text: "¡La cédula de registro de obra seleccionada será borrada del sistema! \n¿Desea continuar?", type: "warning", showCancelButton: true, cancelButtonText: "Cancelar", confirmButtonText: "Si, borrar", closeOnConfirm: false}, function () {
        window.location.href= BASE_URL + "cedula_registro_obra/delete/"+id;
    });
}

function borrarCumplimiento(id) {
    var BASE_URL = window.location.protocol + "//" + window.location.host+ "/";
    swal({title: "Borrar formato de cumplimiento de objetivos del art. 33", text: "¡El formato de entrega-recepción de obras realizadas con recursos FAIS seleccionado será borrado del sistema! \n¿Desea continuar?", type: "warning", showCancelButton: true, cancelButtonText: "Cancelar", confirmButtonText: "Si, borrar", closeOnConfirm: false}, function () {
        window.location.href= BASE_URL + "cumplimiento_objetivos_33/delete/"+id;
    });
}

function borrarDictamen(id) {
    var BASE_URL = window.location.protocol + "//" + window.location.host+ "/";
    swal({title: "Borrar dictamen de factibilidad", text: "¡El dictamen de factibilidad seleccionado será borrado del sistema! \n¿Desea continuar?", type: "warning", showCancelButton: true, cancelButtonText: "Cancelar", confirmButtonText: "Si, borrar", closeOnConfirm: false}, function () {
        window.location.href= BASE_URL + "dictamen_factibilidad/delete/"+id;
    });
}

function borrarPresupuesto(id) {
    var BASE_URL = window.location.protocol + "//" + window.location.host+ "/";
    swal({title: "Borrar formato de presupuesto por concepto", text: "¡El formato de presupuesto por concepto seleccionado será borrado del sistema! \n¿Desea continuar?", type: "warning", showCancelButton: true, cancelButtonText: "Cancelar", confirmButtonText: "Si, borrar", closeOnConfirm: false}, function () {
        window.location.href= BASE_URL + "presupuesto_concepto/delete/"+id;
    });
}

function addEvento(){
    var tipo = document.getElementById('formato').value;
    if(tipo == '0'){

        $('#masEventos').append('<select class="form-control " required="required" name="tipo_eventos[]">'+
                                    '<option value="">Seleccione el tipo de evento...</option>'+
                                    '<option value="1">Inauguración de obra</option>'+
                                    '<option value="2">Entrega de apoyos</option>'+
                                    '<option value="3">Foro(académico, empresarial, social...)</option>'+
                                    '<option value="4">Firma de convenio</option>'+
                                '</select>');
    }
    else{

        if(tipo == '1'){
            $('#masEventos').append('<select class="form-control " required="required" name="tipo_eventos[]">'+
                                        '<option value="">Seleccione el tipo de evento...</option>'+
                                        '<option value="6">Reunión de trabajo con algún grupo/institución/sector en el que no se considere la presencia'+
                                        'de medios de comunicación(incluir información relevante)</option></select>');
        }
        else{
            swal(
            {
                title: 'Tipo de evento!',
                text: 'Seleccione el tipo de evento.',
                type: 'warning'
            });
        }
    }
}

function removeEvento(){
    $('#masEventos').empty();
}

function addInversion(){

    $('#masMonto1').append('<label for="monto" class="control-label">Monto:</label><br>');
    $('#masMonto2').append('<input class="form-control" placeholder="Monto" required="required" autofocus="autofocus" '+
            'onkeypress="return isNumberCant(event,&quot;0.00&quot;,10,2)" style="padding-top: 3px;padding-bottom: 3px;'+
            'height: 28px;" name="monto2[]" type="text">');
    $('#masOrigen1').append('<label for="origen" class="control-label">Origen:</label><br>');
    $('#masOrigen2').append('<select class="form-control SelectAuto" required="required" name="origen2[]"><option selected="selected" '+
            'value="">Seleccione el origen...</option><option value="0">Federal</option><option value="1">Estatal</option>'+
            '<option value="2">Municipal</option><option value="3">Otros</option></select>');
    $('.SelectAuto').select2();
}

function removeInversion(){
    $('#masMonto1').empty();
    $('#masMonto2').empty();
    $('#masOrigen1').empty();
    $('#masOrigen2').empty();
}

function borraArchivo(id,zona){
    var BASE_URL = window.location.protocol + "//" + window.location.host + "/";
    swal(
        {
            title: "Borrar archivo",
            text: "¡La información no se podrá recuperar! \n¿Desea continuar?",
            type: "warning",
            showCancelButton: true,
            cancelButtonText: "Cancelar",
            confirmButtonText: "Si, borrar",
            closeOnConfirm: false
        },
        function () {
            $.get(BASE_URL + "borraArchivo", {'id': id}, function (respuesta) {
                if (respuesta == 0) {
                    swal({
                        title: "¡Borrado fallido!",
                        text: "No se ha podido borrar el archivo, favor de intentar de nuevo",
                        type: "error"
                    });
                } else {
                    swal({
                        title: "¡Borrado exitoso!",
                        text: "Se ha eliminado el archivo correctamente",
                        type: "success"
                    },function(){
                        window.location.href = BASE_URL + "juridica/index/2";
                    }
                    );
                }
            });
        }
    );
}

function borrarActM(id){
    var BASE_URL = window.location.protocol + "//" + window.location.host + "/";
    swal(
        {
            title: "Borrar reporte de actividades mensuales",
            text: "¡La información no se podrá recuperar! \n¿Desea continuar?",
            type: "warning",
            showCancelButton: true,
            cancelButtonText: "Cancelar",
            confirmButtonText: "Si, borrar",
            closeOnConfirm: false
        },
        function () {
            window.location.href = BASE_URL + "delegaciones/eliminar_act/"+id;
        }
    );
}

function borrarVRO(id){
    var BASE_URL = window.location.protocol + "//" + window.location.host + "/";
    swal(
        {
            title: "Borrar verificación y revisión de obra",
            text: "¡La información no se podrá recuperar! \n¿Desea continuar?",
            type: "warning",
            showCancelButton: true,
            cancelButtonText: "Cancelar",
            confirmButtonText: "Si, borrar",
            closeOnConfirm: false
        },
        function () {
            window.location.href = BASE_URL + "delegaciones/eliminar/"+id;
        }
    );
}

function sumaPres()
{
    var total =parseFloat($('#inv_municipal').val()*1)+parseFloat($('#inv_estatal').val()*1)+parseFloat($('#inv_federal').val()*1)+parseFloat($('#inv_beneficiarios').val()*1)+parseFloat($('#otros').val()*1);
    $('#presupuesto').val(total);
    $('#resPresP').empty();
    if(parseFloat($('#presupuesto').val()*1) < parseFloat($('#totalProgMeta').val()*1)){
        $('#resPresP').append('Programación mayor a presupuesto disponible!');
    }

}

function sumaMeses()
{
    var total = parseFloat($('#enero').val()*1)+parseFloat($('#febrero').val()*1)+parseFloat($('#marzo').val()*1)+
    parseFloat($('#abril').val()*1)+parseFloat($('#mayo').val()*1)+parseFloat($('#junio').val()*1)+parseFloat($('#julio').val()*1)+
    parseFloat($('#agosto').val()*1)+parseFloat($('#septiembre').val()*1)+parseFloat($('#octubre').val()*1)+parseFloat($('#noviembre').val()*1)+
    parseFloat($('#diciembre').val()*1);
    $('#totalProgMeta').val(total);
    $('#resPresP').empty();
    if(parseFloat($('#presupuesto').val()*1) < parseFloat($('#totalProgMeta').val()*1)){
        $('#resPresP').append('Programación mayor a presupuesto disponible!');
    }


}

function sumaMun()
{
    var m1 = parseFloat($('#m1').val()*1)+parseFloat($('#m2').val()*1)+parseFloat($('#m3').val()*1)+
    parseFloat($('#m4').val()*1)+parseFloat($('#m5').val()*1)+parseFloat($('#m6').val()*1)+parseFloat($('#m7').val()*1)+
    parseFloat($('#m8').val()*1)+parseFloat($('#m9').val()*1)+parseFloat($('#m10').val()*1)+parseFloat($('#m11').val()*1)+
    parseFloat($('#m12').val()*1)+parseFloat($('#m13').val()*1)+parseFloat($('#m14').val()*1)+parseFloat($('#m15').val()*1)+
    parseFloat($('#m16').val()*1)+parseFloat($('#m17').val()*1)+parseFloat($('#m18').val()*1)+parseFloat($('#m19').val()*1)+
    parseFloat($('#m20').val()*1);
    var m2 = parseFloat($('#m21').val()*1)+parseFloat($('#m22').val()*1)+parseFloat($('#m23').val()*1)+
    parseFloat($('#m24').val()*1)+parseFloat($('#m25').val()*1)+parseFloat($('#m26').val()*1)+parseFloat($('#m27').val()*1)+
    parseFloat($('#m28').val()*1)+parseFloat($('#m29').val()*1)+parseFloat($('#m30').val()*1)+parseFloat($('#m31').val()*1)+
    parseFloat($('#m32').val()*1)+parseFloat($('#m33').val()*1)+parseFloat($('#m34').val()*1)+parseFloat($('#m35').val()*1)+
    parseFloat($('#m36').val()*1)+parseFloat($('#m37').val()*1)+parseFloat($('#m38').val()*1)+parseFloat($('#m39').val()*1)+
    parseFloat($('#m40').val()*1);
    var m3 =parseFloat($('#m41').val()*1)+parseFloat($('#m42').val()*1)+parseFloat($('#m43').val()*1)+
    parseFloat($('#m44').val()*1)+parseFloat($('#m45').val()*1)+parseFloat($('#m46').val()*1)+parseFloat($('#m47').val()*1)+
    parseFloat($('#m48').val()*1)+parseFloat($('#m49').val()*1)+parseFloat($('#m50').val()*1)+parseFloat($('#m51').val()*1)+
    parseFloat($('#m52').val()*1)+parseFloat($('#m53').val()*1)+parseFloat($('#m54').val()*1)+parseFloat($('#m55').val()*1)+
    parseFloat($('#m56').val()*1)+parseFloat($('#m57').val()*1)+parseFloat($('#m58').val()*1);
    $('#totalZonMeta').val(m1+m2+m3);

}

function disableM(){
    $('#checkMun').removeAttr('onchange').attr('onchange','enableM()');
    $('#inv_municipal').removeAttr('readonly');
    sumaPres();

}

function enableM(){
    $('#checkMun').removeAttr('onchange').attr('onchange','disableM()');
    $('#inv_municipal').val('0');
    $('#inv_municipal').attr('readonly','readonly');
    sumaPres();
}

function disableB(){
    $('#checkBen').removeAttr('onchange').attr('onchange','enableB()');
    $('#inv_beneficiarios').removeAttr('readonly');
    sumaPres();
}

function enableB(){
    $('#checkBen').removeAttr('onchange').attr('onchange','disableB()');
    $('#inv_beneficiarios').val('0');
    $('#inv_beneficiarios').attr('readonly','readonly');
    sumaPres();
}

function eiminarPrg(id) {
    var BASE_URL = window.location.protocol + "//" + window.location.host+ "/";
    swal({title: "Borrar Programa", text: "¡El programa seleccionado será borrado del sistema! \n¿Desea continuar?", type: "warning", showCancelButton: true, cancelButtonText: "Cancelar", confirmButtonText: "Si, borrar", closeOnConfirm: false}, function () {
        window.location.href= BASE_URL + "poa_borrar/"+id;
    });
}

function getMunicipio()
{
    var BASE_URL = window.location.protocol + "//" + window.location.host+ "/";
    var id_zona = document.getElementById('id_zona').value;
    $.get(BASE_URL + "getMunicipio", {'id_zona': id_zona}, function (respuesta) {
        $("#origen > option").remove();
        $("#destino > option").remove();
        $(respuesta).each(function (i, v) { // indice, valor
            $('#origen').append('<option value="'+v.id+'">'+v.municipio+'</option>');
        });
    });
}

function getNumMun()
{
    var BASE_URL = window.location.protocol + "//" + window.location.host+ "/";
    var id_zona = document.getElementById('id_zona').value;
    $.get(BASE_URL + "getNumMun", {'id_zona': id_zona}, function (r) {
        $("#municipios > option").remove();
        $("#origen > option").remove();
        $("#destino > option").remove();
        $('#municipios').append('<option  value="">Seleccione...</option>');
        for(var i = 1; i <= r; i++){
            $('#municipios').append('<option value="'+i+'">'+i+'</option>');
        }
    });
}

function validaGira()
{
    var mun = parseInt($('#municipios').val())*1;
    var dest = parseInt($('#destino option').length)*1;
    if( dest < mun ){
        var cant = mun - dest;
        if(cant === 1){
            swal({
                title: "¡Atención!",
                text: "Aún queda 1 municipio pendiente. Favor de seleccionar el municipio restante!",
                type: "warning"
            });
        }
        else{
            swal({
                title: "¡Atención!",
                text: "Aún quedan "+cant+" municipios pendientes. Favor de seleccionar los municipios restantes!",
                type: "warning"
            });
        }
        return false;
    }
    if( dest > mun ){
        var cant = dest - mun;
        if(cant === 1){
            swal({
                title: "¡Atención!",
                text: "La cantidad de municipios seleccionados es mayor que la solicitada. Favor de quitar 1 municipio.",
                type: "warning"
            });
        }
        else{
            swal({
                title: "¡Atención!",
                text: "La cantidad de municipios seleccionados es mayor que la solicitada. Favor de quitar "+cant+" municipios.",
                type: "warning"
            });
        }
        return false;
    }
    if( dest === mun ){
        $('#destino option').prop('selected', true);
        $('#form-giras').submit();
    }

}

function getMun()
{
    var BASE_URL = window.location.protocol + "//" + window.location.host+ "/";
    var id_zona = document.getElementById('id_zona').value;
    var id_gira = document.getElementById('id_gira').value;
    $.get(BASE_URL + "getMun", {'id_zona': id_zona,'id_gira': id_gira}, function (respuesta) {
        $("#id_municipio > option").remove();
        $(respuesta).each(function (i, v) { // indice, valor
            $('#id_municipio').append('<option value="'+v.id+'">'+v.municipio+'</option>');
        });
    });
}

function evidencia(nombre)
{
    var BASE_URL = window.location.protocol + "//" + window.location.host+ "/";
    $('<a href="'+BASE_URL+'evidencia/'+nombre+'" target="blank"></a>')[0].click();

}

function programaNo()
{
    $('#programa').attr('disabled','disabled').removeAttr('required').val('');
    $('#monto').attr('disabled','disabled').removeAttr('required').val('');

}

function programaSi()
{
    $('#programa').attr('required','required').removeAttr('disabled');
    $('#monto').attr('required','required').removeAttr('disabled');

}

function listadoComp(dep)
{
    var BASE_URL = window.location.protocol + "//" + window.location.host+ "/";
    window.location.href= BASE_URL + "listado_compromisos/"+dep;

}

function getZona()
{
    var BASE_URL = window.location.protocol + "//" + window.location.host+ "/";
    var id_gira = document.getElementById('id_gira').value;
    $.get(BASE_URL + "getZona", {'id_gira': id_gira}, function (respuesta) {
        $("#id_zona > option").remove();
        $("#id_municipio > option").remove();
        $('#id_zona').append('<option  value="">Seleccione...</option>');
        $('#id_municipio').append('<option  value="">Seleccione...</option>');
        $(respuesta).each(function (i, v) { // indice, valor
            $('#id_zona').append('<option value="'+v.id+'">'+v.zona+'</option>');
        });
    });
}

/*function ventas()
{
    var BASE_URL = window.location.protocol + "//" + window.location.host+ "/";
    var fecha = document.getElementById('fecha').value;
    $("#ventas").empty();
    $.get(BASE_URL + "getVentas", {'fecha': fecha}, function (r) {
        $("#ventas").append(r);
        $("#fecha_venta").val(fecha);
    });
}*/

function cambiaColor(color)
{
    var BASE_URL = window.location.protocol + "//" + window.location.host+ "/";
    $.get(BASE_URL + "cambiaColor", {'color': color});
}

function cambiaImagen(imagen)
{
    var BASE_URL = window.location.protocol + "//" + window.location.host+ "/";
    $.get(BASE_URL + "cambiaImagen", {'imagen': imagen});
}
