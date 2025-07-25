/*
 *  Creado por:Raymundo A. González Grimaldo
 *  Fecha de creacion:   5/11/2015 08:43:00 PM        
 *  Ultima modificacion: 5/11/2015 08:43:00 PM        
 *  Modificado por: Raymundo A. González Grimaldo
 */
    
    $(document).ready(function(){
        
        $( ".pagAjax14" ).each(function() {
            var table=$(this).html();
            var form ='<form id="pagAjax_form14" class ="form-inline" onsubmit="pagAjaxCargaDatos14($(this));return false;"><div style="float: left;"><input type="hidden" id="pagAjax_order14" name="order" /><input type="hidden" id="pagAjax_ot14" name="ot" value="0" />Mostrar <select name="tampag" class="form-control" onchange="$(this).closest(\'#pagAjax_form14\').submit()"> <option value="5">5</option><option value="10">10</option><option value="15">15</option><option value="20">20</option></select>registros por página</div><div class="pull-rigth" style="float: right;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Búsqueda: <input type="text" name="filtro" class="form-control"  onkeyup="$(this).closest(\'#pagAjax_form14\').submit()"/> </div></form>'; 
            var code = '<div id="pagAjax_controles">'+form+'</div><div id="pagAjax_data14">'+table+'</div>';
            $(this).html(code);
            thOrder14($(this)); 
           
        });

        $(document).on('click', '.pagAjax14 #pagAjax_data14 .pagination a', function (event) {
            event.preventDefault();           
            if ( $(this).attr('href') != '#' ) {
                var dataGet;                
                
                dataGet="&"+$('.pagAjax14').data().get;
                $("html, body").animate({ scrollTop: 0 }, "fast");
                $('.pagAjax14 #pagAjax_data14').html('<div class="load"><i class="fa fa-5x fa-spinner fa-pulse"></i></div>');
                $('.pagAjax14 #pagAjax_data14').load($(this).attr('href')+"&"+$('.pagAjax14 #pagAjax_form14').serialize()+dataGet,function() {                    
                    thOrder14($(this).parents().find(".pagAjax14"));
                });
                
            }else{
                
            }
        });
    });
  
function pagAjaxCargaDatos14(obj){
    var dataGet;
    
    obj.closest('.pagAjax14').find('#pagAjax_data14').html('<div class="load"><i class="fa fa-5x fa-spinner fa-pulse"></i></div>');
    dataGet="&"+obj.closest('.pagAjax14').data().get;
    obj.closest('.pagAjax14').find('#pagAjax_data14').load(obj.closest('.pagAjax14').data().url+'?'+obj.serialize()+dataGet,function(){thOrder14(obj.parents().find('table'))});
}
    

 function thOrder14(obj){  
            
    order_actual = obj.parents().find(".pagAjax14").find("#pagAjax_order14").val();
    order_ot = obj.parents().find(".pagAjax14").find("#pagAjax_ot14").val();

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

           $(this).html('<div style="position:relative;top:0;right:0;  text-align:right; color: #fff" class="pull-right"><a onclick="thChangeOrder14($(this),\''+order+'\');$(this).parents().find(\'#pagAjax_form14\').submit();">'+icono+'</a></div>'+$(this).html());

       }                
   });
}

function thChangeOrder14(obj,newOrder){
    
    if(obj.parents().find('#pagAjax_order14').val() == newOrder){
        if(obj.parents().find('#pagAjax_ot14').val() == 0)
            obj.parents().find('#pagAjax_ot14').val(1);
        else
            obj.parents().find('#pagAjax_ot14').val(0);
    }else{
        obj.parents().find('#pagAjax_ot14').val(0);
    }
    obj.parents().find('#pagAjax_order14').val(newOrder);
    
}
        
        