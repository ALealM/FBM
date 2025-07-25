var BASE_URL = window.location.protocol + "//" + window.location.host + "/";

function notificacion(titulo, mensaje, tipo) {
    swal(
            {
                title: titulo,
                text: mensaje,
                type: tipo,
                buttonsStyling: false,
                confirmButtonClass: 'btn btn-sm btn-success',
                confirmButtonText: "Aceptar",
                //background: 'rgba(0, 0, 0, 0.96)'
            }
    );
}

function borrarSVG(id) {
    swal(
            {
                title: 'Eliminar solicitud!',
                text: 'La solicitud se eliminará definitivamente. ¿Desea continuar?',
                type: 'warning',
                showCancelButton: true,
                buttonsStyling: false,
                confirmButtonClass: 'btn btn-danger',
                cancelButtonText: "Cancelar",
                confirmButtonText: "Si, borrar",
                cancelButtonClass: 'btn btn-light',
                background: 'rgba(0, 0, 0, 0.96)'
            }).then(function () {
        window.location.href = BASE_URL + "solicitudVG/delete/" + id;
    }
    );
}

function addRowRCE() {

    var tblC = document.getElementById('table_conc');
    var tblM = document.getElementById('table_mont');
    var tblR = document.getElementById('table_rfna');
    var lastRowC = tblC.rows.length;
    var lastRowM = tblM.rows.length;
    var lastRowR = tblR.rows.length;
    var rowC = tblC.insertRow(lastRowC);
    var rowM = tblM.insertRow(lastRowM);
    var rowR = tblR.insertRow(lastRowR);

    var conc = rowC.insertCell(0);
    var mont = rowM.insertCell(0);
    var rfna = rowR.insertCell(0);

    conc.innerHTML = '<div class="form-group" style="margin-bottom: 0px"><input class="form-control form-control-sm" name="concepto[]" type="text"><i class="form-group__bar"></i></div>';
    mont.innerHTML = '<div class="form-group" style="margin-bottom: 0px"><input class="form-control form-control-sm" name="monto[]" type="text"><i class="form-group__bar"></i></div>';
    rfna.innerHTML = '<div class="form-group" style="margin-bottom: 0px"><input class="form-control form-control-sm" name="rfna[]" type="text"><i class="form-group__bar"></i></div>';

    return false;
}

function deleteRowRCE() {

    var tblC = document.getElementById('table_conc');
    var tblM = document.getElementById('table_mont');
    var tblR = document.getElementById('table_rfna');
    var lastRowC = tblC.rows.length;
    var lastRowM = tblM.rows.length;
    var lastRowR = tblR.rows.length;
    if (lastRowC > 1) {
        tblC.deleteRow(lastRowC - 1);
        tblM.deleteRow(lastRowM - 1);
        tblR.deleteRow(lastRowR - 1);
    }
}

function addRowCom() {

    var tblN = document.getElementById('table_nom');
    var tblA = document.getElementById('table_act');
    var tblAS = document.getElementById('table_asis');
    var tblO = document.getElementById('table_obj');
    var tblOB = document.getElementById('table_obs');
    var lastRowN = tblN.rows.length;
    var lastRowA = tblA.rows.length;
    var lastRowAS = tblAS.rows.length;
    var lastRowO = tblO.rows.length;
    var lastRowOB = tblOB.rows.length;
    var rowN = tblN.insertRow(lastRowN);
    var rowA = tblA.insertRow(lastRowA);
    var rowAS = tblAS.insertRow(lastRowAS);
    var rowO = tblO.insertRow(lastRowO);
    var rowOB = tblOB.insertRow(lastRowOB);

    var nom = rowN.insertCell(0);
    var act = rowA.insertCell(0);
    var asis = rowAS.insertCell(0);
    var asin = rowAS.insertCell(1);
    var obj = rowO.insertCell(0);
    var obs = rowOB.insertCell(0);

    nom.innerHTML = '<div class="form-group" style="margin-bottom: 0px"><input class="form-control form-control-sm" name="nomb[]" type="text"><i class="form-group__bar"></i></div>';
    act.innerHTML = '<div class="form-group" style="margin-bottom: 0px"><input class="form-control form-control-sm" name="act[]" type="text"><i class="form-group__bar"></i></div>';
    asis.innerHTML = '<div class="form-group" style="margin-bottom: 0px"><input class="form-control form-control-sm" name="as_si[]" type="text"><i class="form-group__bar"></i></div>';
    asin.innerHTML = '<div class="form-group" style="margin-bottom: 0px"><input class="form-control form-control-sm" name="as_no[]" type="text"><i class="form-group__bar"></i></div>';
    obj.innerHTML = '<div class="form-group" style="margin-bottom: 0px"><input class="form-control form-control-sm" name="obj[]" type="text"><i class="form-group__bar"></i></div>';
    obs.innerHTML = '<div class="form-group" style="margin-bottom: 0px"><input class="form-control form-control-sm" name="obs[]" type="text"><i class="form-group__bar"></i></div>';

    return false;
}

function deleteRowCom() {

    var tblN = document.getElementById('table_nom');
    var tblA = document.getElementById('table_act');
    var tblAS = document.getElementById('table_asis');
    var tblO = document.getElementById('table_obj');
    var tblOB = document.getElementById('table_obs');
    var lastRowN = tblN.rows.length;
    var lastRowA = tblA.rows.length;
    var lastRowAS = tblAS.rows.length;
    var lastRowO = tblO.rows.length;
    var lastRowOB = tblOB.rows.length;
    if (lastRowN > 1) {
        tblN.deleteRow(lastRowN - 1);
        tblA.deleteRow(lastRowA - 1);
        tblAS.deleteRow(lastRowAS - 1);
        tblO.deleteRow(lastRowO - 1);
        tblOB.deleteRow(lastRowOB - 1);
    }
}

function borrarFR(id) {
    swal(
            {
                title: 'Eliminar factura recibida!',
                text: 'La factura se eliminará definitivamente. ¿Desea continuar?',
                type: 'warning',
                showCancelButton: true,
                buttonsStyling: false,
                confirmButtonClass: 'btn btn-danger',
                cancelButtonText: "Cancelar",
                confirmButtonText: "Si, borrar",
                cancelButtonClass: 'btn btn-light',
                background: 'rgba(0, 0, 0, 0.96)'
            }).then(function () {
        window.location.href = BASE_URL + "facturas_recibidas/delete/" + id;
    }
    );
}

function borrarFE(id) {
    swal(
            {
                title: 'Eliminar factura emitida!',
                text: 'La factura se eliminará definitivamente. ¿Desea continuar?',
                type: 'warning',
                showCancelButton: true,
                buttonsStyling: false,
                confirmButtonClass: 'btn btn-danger',
                cancelButtonText: "Cancelar",
                confirmButtonText: "Si, borrar",
                cancelButtonClass: 'btn btn-light',
                background: 'rgba(0, 0, 0, 0.96)'
            }).then(function () {
        window.location.href = BASE_URL + "facturasEmitidas/delete/" + id;
    }
    );
}

function buscaFactura() {
    var fac = document.getElementById('factura').value;
    $.get(BASE_URL + "buscaFactura", {'fac': fac}, function (r) {
        console.log(r);
        $('#fechaEmision').val(r.fecha);
        $('#concepto').val(r.concepto);
        $('#metodoPago').val(r.forma_pago);
        $('#subtotal').val(r.subtotal);
        $('#iva').val(r.iva);
        $('#total').val(r.importe);
        $('#ivaRet').val(r.ivaRet);
        $('#isrRet').val(r.isrRet);
        $('#otro').val(r.otro);
    });
}


function buscaFactura2() {
    var fac = document.getElementById('factura').value;
    $.get(BASE_URL + "buscaFactura2", {'fac': fac}, function (r) {
        console.log(r);
        $('#fecha').val(r.fecha2);
        $('#concepto').val('Factura ' + r.factura);
        $('#total').val(r.total);
    });
}

function borrarFCC(id) {
    swal(
            {
                title: 'Eliminar factura!',
                text: 'La factura se eliminará definitivamente. ¿Desea continuar?',
                type: 'warning',
                showCancelButton: true,
                buttonsStyling: false,
                confirmButtonClass: 'btn btn-danger',
                cancelButtonText: "Cancelar",
                confirmButtonText: "Si, borrar",
                cancelButtonClass: 'btn btn-light',
                background: 'rgba(0, 0, 0, 0.96)'
            }).then(function () {
        window.location.href = BASE_URL + "centroCostos/delete/" + id;
    }
    );
}


function borrarDep(id) {
    swal(
            {
                title: 'Eliminar depósito!',
                text: 'El depósito se eliminará definitivamente. ¿Desea continuar?',
                type: 'warning',
                showCancelButton: true,
                buttonsStyling: false,
                confirmButtonClass: 'btn btn-danger',
                cancelButtonText: "Cancelar",
                confirmButtonText: "Si, borrar",
                cancelButtonClass: 'btn btn-light',
                background: 'rgba(0, 0, 0, 0.96)'
            }).then(function () {
        window.location.href = BASE_URL + "depositos/delete/" + id;
    }
    );
}

function borrarAr(id) {
    $('#idArDel').val(id);
    $('#modalDel').modal();
}

function confirmDoc(id) {
    $('#idArConf').val(id);
    $('#modalConf').modal();
}

function confirmVistoBueno(id) {
    $('#idVistoBueno').val(id);
    $('#modalVistoBueno').modal();
}

function porcentaje(id) {
    var av = $('#' + id).val();
    if (av > 100)
        $('#' + id).val('100');
}

function cargaXMLFR() {
    var inputFile = document.getElementById("xml");
    var file = inputFile.files[0];
    var data = new FormData();
    data.append('file', file);
    $.ajax({
        url: BASE_URL + "cargaXMLFR",
        type: 'POST',
        contentType: false,
        data: data,
        processData: false,
        cache: false
    })
    .done(function (v) {
        console.log(v);
        if (v.error == 0) {
            $("#fecha").val(v.valores.Comprobante.Fecha);
            $("#num_factura").val(v.valores.Comprobante.Folio[0].substring(0, 8));
            $("#subtotal").val(v.valores.Comprobante.SubTotal);
            $("#importe").val(v.valores.Comprobante.Total);
            $("#rfc").val(v.valores.Emisor.RFC);
            $("#denominacion").val(v.valores.Emisor.Nombre);
            $("#iva").val(Math.abs(Math.round(parseFloat((v.valores.Comprobante.Total) - parseFloat(v.valores.Comprobante.SubTotal))*100)/100));
        }
        else {
            swal({
                title: "¡Carga de archivo fallida!",
                text: v.valores,
                type: "error",
                buttonsStyling: false,
                confirmButtonClass: 'btn btn-sm btn-light',
                background: 'rgba(0, 0, 0, 0.96)'
            });
        }
    });
}

function cargaXMLFE() {
    var inputFile = document.getElementById("xml");
    var file = inputFile.files[0];
    var data = new FormData();
    data.append('file', file);
    $.ajax({
        url: BASE_URL + "cargaXMLFE",
        type: 'POST',
        contentType: false,
        data: data,
        processData: false,
        cache: false
    })
    .done(function (v) {
        console.log(v);
        if (v.error == 0) {
            $("#fecha").val(v.valores.Comprobante.Fecha);
            $("#factura").val(v.valores.Comprobante.Folio);
            $("#subtotal").val(v.valores.Comprobante.SubTotal);
            $("#total").val(v.valores.Comprobante.Total);
            $("#rfc").val(v.valores.Receptor.RFC);
            $("#nombre").val(v.valores.Receptor.Nombre);
            $("#concepto").val(v.valores.Concepto.Descripcion);
            $("#iva").val(Math.round(parseFloat((v.valores.Comprobante.Total) - parseFloat(v.valores.Comprobante.SubTotal))*100)/100);
        }
        else {
            swal({
                title: "¡Carga de archivo fallida!",
                text: v.valores,
                type: "error",
                buttonsStyling: false,
                confirmButtonClass: 'btn btn-sm btn-light',
                background: 'rgba(0, 0, 0, 0.96)'
            });
        }
    });
}

function addRowAnexo() {

    var anexo = $('#anexo').html();
    var desc = $('#anexo_desc').html();
    var tblP = document.getElementById('table_anexo');
    var lastRowP = tblP.rows.length;
    var rowP = tblP.insertRow(lastRowP);
    var ane = rowP.insertCell(0);
    var des = rowP.insertCell(1);

    ane.innerHTML = anexo;
    des.innerHTML = desc;

    return false;
}

function deleteRowAnexo() {

    var tblP = document.getElementById('table_anexo');
    var lastRowP = tblP.rows.length;
    if (lastRowP > 1) {
        tblP.deleteRow(lastRowP - 1);
    }
}


function addRowPart() {

    var part = $('#part').html();
    var tblP = document.getElementById('table_part');
    var lastRowP = tblP.rows.length;
    var rowP = tblP.insertRow(lastRowP);
    var area = rowP.insertCell(0);
    area.innerHTML = part;

    return false;
}

function deleteRowPart() {

    var tblP = document.getElementById('table_part');
    var lastRowP = tblP.rows.length;
    if (lastRowP > 1) {
        tblP.deleteRow(lastRowP - 1);
    }
}

function addRowPartEv() {

    var part = $('#part_').html();
    var tblP = document.getElementById('table_partEv');
    var lastRowP = tblP.rows.length;
    var rowP = tblP.insertRow(lastRowP);
    console.log(part);
    var nom = rowP.insertCell(0);

    nom.innerHTML = part;

    return false;
}

function deleteRowPartEv() {

    var tblP = document.getElementById('table_partEv');
    var lastRowP = tblP.rows.length;
    if (lastRowP > 1) {
        tblP.deleteRow(lastRowP - 1);
    }
}

function guardaTema(tema) {
    $.get(BASE_URL + "guardaTema", {'tema': tema});
}

function verificaCorreo() {
    var correo = document.getElementById('correo').value;
    $.get(BASE_URL + "verificaCorreo", {'correo': correo}, function (r) {
        if(r>0){
        swal({
                title: "¡Error de email!",
                text: 'El correo electrónico proporcionado ya está en uso, favor de elegir otro.',
                type: "error",
                buttonsStyling: false,
                confirmButtonClass: 'btn btn-sm btn-light',
                background: 'rgba(0, 0, 0, 0.96)',
                onClose: $('#correo').val('')
            });

        }
    });
}

function verificaCorreoEdit(correo) {
    var correoN = document.getElementById('correo').value;
    $.get(BASE_URL + "verificaCorreoEdit", {'correo':  correo,'correoN': correoN}, function (r) {
        if(r>0){
        swal({
                title: "¡Error de email!",
                text: 'El correo electrónico proporcionado ya está en uso, favor de elegir otro.',
                type: "error",
                buttonsStyling: false,
                confirmButtonClass: 'btn btn-sm btn-light',
                background: 'rgba(0, 0, 0, 0.96)',
                onClose: $('#correo').val(correo)
            });

        }
    });
}

function obtenMonto() {
    var fac = document.getElementById('factura').value;
    $.get(BASE_URL + "obtenMonto", {'fac': fac}, function (r) {
        console.log(r);
        $('#monto').val(r);
    });
}

function enviaMensaje() {
    var nombre = $('#name').val();
    var correo = $('#email').val();
    var msj = $('#message').val();
    if(nombre == '' || correo == '' || msj == ''){
        swal({
            title: "¡Error!",
            text: 'Todos los campos son requeridos.',
            type: "error",
            buttonsStyling: false,
            confirmButtonClass: 'btn btn-sm btn-light',
            background: 'rgba(0, 0, 0, 0.96)'
        });
    }
    else{
        $.get(BASE_URL + "enviaMensaje", {'nombre': nombre, 'correo': correo, 'msj':msj}, function (r) {
            swal({
                title: "Mensaje enviado!",
                text: 'Tu información ha sido enviada, nuestro equipo se pondrá en contacto contigo lo más pronto posible.',
                type: "success",
                buttonsStyling: false,
                confirmButtonClass: 'btn btn-sm btn-light',
                background: 'rgba(0, 0, 0, 0.96)'
            });
        });
    }
}

function borrarCom(id) {
    swal(
            {
                title: 'Eliminar comentario!',
                text: 'El comentario se eliminará definitivamente. ¿Desea continuar?',
                type: 'warning',
                showCancelButton: true,
                buttonsStyling: false,
                confirmButtonClass: 'btn btn-danger',
                cancelButtonText: "Cancelar",
                confirmButtonText: "Si, borrar",
                cancelButtonClass: 'btn btn-light',
                background: 'rgba(0, 0, 0, 0.96)'
            }).then(function () {
        window.location.href = BASE_URL + "centroCostos/deleteComent/" + id;
    }
    );
}
