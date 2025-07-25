/*
 *  Creado por:Raymundo A. González Grimaldo
 *  Fecha de creacion:   5/11/2015 08:43:00 PM        
 *  Ultima modificacion: 5/11/2015 08:43:00 PM        
 *  Modificado por: Raymundo A. González Grimaldo
 */
    
    $(document).ready(function(){
        
        $( ".pagAjax3" ).each(function() {
            var table=$(this).html();
            var form ='<form id="pagAjax_form3" class ="form-inline" onsubmit="pagAjaxCargaDatos3($(this));return false;"><div style="float: left;"><input type="hidden" id="pagAjax_order3" name="order" /><input type="hidden" id="pagAjax_ot3" name="ot" value="0" />Mostrar <select name="tampag" class="form-control" onchange="$(this).closest(\'#pagAjax_form3\').submit()"> <option value="5">5</option><option value="10">10</option><option value="15">15</option><option value="20">20</option></select>registros por página</div><div class="pull-rigth" style="float: right;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Búsqueda: <input type="text" name="filtro" class="form-control"  onkeyup="$(this).closest(\'#pagAjax_form3\').submit()"/> </div></form>'; 
            var code = '<div id="pagAjax_controles">'+form+'</div><div id="pagAjax_data3">'+table+'</div>';
            $(this).html(code);
            thOrder3($(this)); 
           
        });

        $(document).on('click', '.pagAjax3 #pagAjax_data3 .pagination a', function (event) {
            event.preventDefault();           
            if ( $(this).attr('href') != '#' ) {
                var dataGet;                
                
                dataGet="&"+$('.pagAjax3').data().get;
                $("html, body").animate({ scrollTop: 0 }, "fast");
                $('.pagAjax3 #pagAjax_data3').html('<div class="load"><i class="fa fa-5x fa-spinner fa-pulse"></i></div>');
                $('.pagAjax3 #pagAjax_data3').load($(this).attr('href')+"&"+$('.pagAjax3 #pagAjax_form3').serialize()+dataGet,function() {                    
                    thOrder3($(this).parents().find(".pagAjax3"));
                });
                
            }else{
                
            }
        });
    });
  
function pagAjaxCargaDatos3(obj){
    var dataGet;
    
    obj.closest('.pagAjax3').find('#pagAjax_data3').html('<div class="load"><i class="fa fa-5x fa-spinner fa-pulse"></i></div>');
    dataGet="&"+obj.closest('.pagAjax3').data().get;
    obj.closest('.pagAjax3').find('#pagAjax_data3').load(obj.closest('.pagAjax3').data().url+'?'+obj.serialize()+dataGet,function(){thOrder3(obj.parents().find('table'))});
}
    

 function thOrder3(obj){  
            
    order_actual = obj.parents().find(".pagAjax3").find("#pagAjax_order3").val();
    order_ot = obj.parents().find(".pagAjax3").find("#pagAjax_ot3").val();

    obj.find('th').each(function() {
       $(this).attr('style', 'vertical-align: top !important;');
       if($(this).data().order){
           order = $(this).data().order;
           if(order_actual == order){
               if(order_ot == 0)
                    icono = '<i class="fa fa-sort-amount-asc faa-tada animated-hover" style="color: #fff"></i>';
                else
                    icono = '<i class="fa fa-sort-amount-desc faa-tada animated-hover" style="color: #fff"></i>';
           }else{
               icono = '<i class="fa fa-sort faa-tada animated-hover" style="color: #fff"></i>';
           }

           $(this).html('<div style="position:relative;top:0;right:0;  text-align:right; color: #fff" class="pull-right"><a onclick="thChangeOrder3($(this),\''+order+'\');$(this).parents().find(\'#pagAjax_form3\').submit();">'+icono+'</a></div>'+$(this).html());

       }                
   });
}

function thChangeOrder3(obj,newOrder){
    
    if(obj.parents().find('#pagAjax_order3').val() == newOrder){
        if(obj.parents().find('#pagAjax_ot3').val() == 0)
            obj.parents().find('#pagAjax_ot3').val(1);
        else
            obj.parents().find('#pagAjax_ot3').val(0);
    }else{
        obj.parents().find('#pagAjax_ot3').val(0);
    }
    obj.parents().find('#pagAjax_order3').val(newOrder);
    
}
        
        