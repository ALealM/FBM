$(document).ready(function() {
	$("#table_banorte1").DataTable({
	    'order' : [],
	    autoWidth:!1,
	    responsive:!0,
	    lengthMenu:[[15,30,45,-1],["15 registros","30 registros","45 registros","Todo"]],
	    language:{searchPlaceholder:"Buscar en la tabla..."},
	    dom:"Blfrtip",
	    buttons:[{extend:"excelHtml5",title:"Export Data"},{extend:"csvHtml5",title:"Export Data"},{extend:"print",title:"Archivo"}],
	    initComplete:function(a,b){$(this).closest(".dataTables_wrapper").prepend('<div class="dataTables_buttons hidden-sm-down actions" style="text-align: right;"><span class="actions__item zmdi zmdi-print" data-table-action="print" /><span class="actions__item zmdi zmdi-fullscreen" data-table-action="fullscreen" /><div class="dropdown actions__item"><i data-toggle="dropdown" class="zmdi zmdi-download" /><ul class="dropdown-menu dropdown-menu-right"><a href="" class="dropdown-item" data-table-action="excel">Excel (.xlsx)</a><a href="" class="dropdown-item" data-table-action="csv">CSV (.csv)</a></ul></div></div>')}
	});
	$("#table_banorte2").DataTable({
	    'order' : [],
	    autoWidth:!1,
	    responsive:!0,
	    lengthMenu:[[15,30,45,-1],["15 registros","30 registros","45 registros","Todo"]],
	    language:{searchPlaceholder:"Buscar en la tabla..."},
	    dom:"Blfrtip",
	    buttons:[{extend:"excelHtml5",title:"Export Data"},{extend:"csvHtml5",title:"Export Data"},{extend:"print",title:"Archivo"}],
	    initComplete:function(a,b){$(this).closest(".dataTables_wrapper").prepend('<div class="dataTables_buttons hidden-sm-down actions" style="text-align: right;"><span class="actions__item zmdi zmdi-print" data-table-action="print" /><span class="actions__item zmdi zmdi-fullscreen" data-table-action="fullscreen" /><div class="dropdown actions__item"><i data-toggle="dropdown" class="zmdi zmdi-download" /><ul class="dropdown-menu dropdown-menu-right"><a href="" class="dropdown-item" data-table-action="excel">Excel (.xlsx)</a><a href="" class="dropdown-item" data-table-action="csv">CSV (.csv)</a></ul></div></div>')}
	});
	$("#table_banorte3").DataTable({
	    'order' : [],
	    autoWidth:!1,
	    responsive:!0,
	    lengthMenu:[[15,30,45,-1],["15 registros","30 registros","45 registros","Todo"]],
	    language:{searchPlaceholder:"Buscar en la tabla..."},
	    dom:"Blfrtip",
	    buttons:[{extend:"excelHtml5",title:"Export Data"},{extend:"csvHtml5",title:"Export Data"},{extend:"print",title:"Archivo"}],
	    initComplete:function(a,b){$(this).closest(".dataTables_wrapper").prepend('<div class="dataTables_buttons hidden-sm-down actions" style="text-align: right;"><span class="actions__item zmdi zmdi-print" data-table-action="print" /><span class="actions__item zmdi zmdi-fullscreen" data-table-action="fullscreen" /><div class="dropdown actions__item"><i data-toggle="dropdown" class="zmdi zmdi-download" /><ul class="dropdown-menu dropdown-menu-right"><a href="" class="dropdown-item" data-table-action="excel">Excel (.xlsx)</a><a href="" class="dropdown-item" data-table-action="csv">CSV (.csv)</a></ul></div></div>')}
	});
	$("#table_banorte4").DataTable({
	    'order' : [],
	    autoWidth:!1,
	    responsive:!0,
	    lengthMenu:[[15,30,45,-1],["15 registros","30 registros","45 registros","Todo"]],
	    language:{searchPlaceholder:"Buscar en la tabla..."},
	    dom:"Blfrtip",
	    buttons:[{extend:"excelHtml5",title:"Export Data"},{extend:"csvHtml5",title:"Export Data"},{extend:"print",title:"Archivo"}],
	    initComplete:function(a,b){$(this).closest(".dataTables_wrapper").prepend('<div class="dataTables_buttons hidden-sm-down actions" style="text-align: right;"><span class="actions__item zmdi zmdi-print" data-table-action="print" /><span class="actions__item zmdi zmdi-fullscreen" data-table-action="fullscreen" /><div class="dropdown actions__item"><i data-toggle="dropdown" class="zmdi zmdi-download" /><ul class="dropdown-menu dropdown-menu-right"><a href="" class="dropdown-item" data-table-action="excel">Excel (.xlsx)</a><a href="" class="dropdown-item" data-table-action="csv">CSV (.csv)</a></ul></div></div>')}
	});

	$("#table_scot").DataTable({
	    'order' : [],
	    autoWidth:!1,
	    responsive:!0,
	    lengthMenu:[[15,30,45,-1],["15 registros","30 registros","45 registros","Todo"]],
	    language:{searchPlaceholder:"Buscar en la tabla..."},
	    dom:"Blfrtip",
	    buttons:[{extend:"excelHtml5",title:"Export Data"},{extend:"csvHtml5",title:"Export Data"},{extend:"print",title:"Archivo"}],
	    initComplete:function(a,b){$(this).closest(".dataTables_wrapper").prepend('<div class="dataTables_buttons hidden-sm-down actions" style="text-align: right;"><span class="actions__item zmdi zmdi-print" data-table-action="print" /><span class="actions__item zmdi zmdi-fullscreen" data-table-action="fullscreen" /><div class="dropdown actions__item"><i data-toggle="dropdown" class="zmdi zmdi-download" /><ul class="dropdown-menu dropdown-menu-right"><a href="" class="dropdown-item" data-table-action="excel">Excel (.xlsx)</a><a href="" class="dropdown-item" data-table-action="csv">CSV (.csv)</a></ul></div></div>')}
	});

	$("body").on("click","[data-table-action]",function(a){
	    a.preventDefault();
	    var b=$(this).data("table-action");if("excel"===b&&$(this).closest(".dataTables_wrapper").find(".buttons-excel").trigger("click"),"csv"===b&&$(this).closest(".dataTables_wrapper").find(".buttons-csv").trigger("click"),"print"===b&&$(this).closest(".dataTables_wrapper").find(".buttons-print").trigger("click"),"fullscreen"===b){var c=$(this).closest(".card");c.hasClass("card--fullscreen")?(c.removeClass("card--fullscreen"),$("body").removeClass("data-table-toggled")):(c.addClass("card--fullscreen"),$("body").addClass("data-table-toggled"))}
	});
	$('#banorteTab').hover(function(){
		$('#tab_banorte').trigger('click')
	  //console.log('hover');
	})
});

function active_banorte(){
	$("#tab_scot").removeClass("active");
	$( "#tab_banorte" ).addClass( "active" );
}
function tiporegistro(){
	dato=$('#tipo').val();
	html='';
	$( "#tipomovimiento" ).empty();
	if (dato=='ingreso'){
		$("#facturasemitidas").css("display", "none");
		$("#facturasrecibidas").css("display", "none");
		html='<select class="form-control" name="movimiento" id="movimiento" required onchange="tipomovimiento();">';
		html=html+'<option selected>Selecciona...</option>';
		html=html+'<option value="factura">Factura</option>';
        html=html+'<option value="deposito">Deposito</option>';
        html=html+'<option value="transferencia">Transferencia</option>';
        html=html+'</select>';
		$('#tipomovimiento').html(html);
	}else if (dato=='egreso'){
		$("#facturasemitidas").css("display", "none");
		$("#facturasrecibidas").css("display", "none");
		html='<select class="form-control" name="movimiento" id="movimiento" required onchange="tipomovimiento();">';
		html=html+'<option selected>Selecciona...</option>';
		html=html+'<option value="factura">Factura</option>';
        html=html+'<option value="transferencia">Transferencia</option>';
        html=html+'</select>';
		$('#tipomovimiento').html(html);
	}


}
function tipomovimiento(){
	dato=$('#movimiento').val();

	dato2=$('#tipo').val();

	if (dato=='factura'){
		if (dato2=='ingreso'){
			$("#facturasemitidas").css("display", "block");
			$("#facturasrecibidas").css("display", "none");

		}
		if(dato2=='egreso'){
			$("#facturasemitidas").css("display", "none");
			$("#facturasrecibidas").css("display", "block");
		}
	}else{
		$("#facturasemitidas").css("display", "none");
		$("#facturasrecibidas").css("display", "none");
	}
}
function getFactura(id){
	var BASE_URL = window.location.protocol + "//" + window.location.host + "/";
    $.post(BASE_URL+"datosFactura", {id: id}, function(result){
        console.log(result.id+','+result.factura+','+result.fecha+','+result.rfc+','+result.nombre+','+result.total);
        $('#nombre_registro').val(result.nombre);
		$('#concepto_registro').val(result.concepto);
		$('#cantidad').val(result.total);

    })
}
