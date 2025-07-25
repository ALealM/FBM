$(document).ready(function(){
 $('#form').validate(
 {
  rules: {
  },
  highlight: function(element) {
       var id_attr = "#" + $( element ).attr("id") + "1";
       $(element).closest('.form-group').removeClass('has-success').addClass('has-error');
       $(id_attr).removeClass('glyphicon-ok').addClass('glyphicon-remove');         
   },
 unhighlight: function(element) {
       var id_attr = "#" + $( element ).attr("id") + "1";
       $(element).closest('.form-group').removeClass('has-error').addClass('has-success');
       $(id_attr).removeClass('glyphicon-remove').addClass('glyphicon-ok');         
   },
        errorElement: 'span',
        errorClass: 'color',
        errorPlacement: function(error, element) {
            error.insertAfter(element);
       } 
 });
}); 