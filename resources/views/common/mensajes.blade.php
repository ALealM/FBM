<?php

/*
 *  Creado por:Cristian de Jesús Martínez Salazar 
 *  Fecha de creación:   22/09/2015 03:34:19 PM        
 *  Ultima modificación: 22/09/2015 03:34:19 PM       
 *  Modificado por: Cristian de Jesús Martínez Salazar   
 */
?>
@if(Session::has('mensaje'))
     <?php echo "<script> notificacion('".Session::get('tituloMsg') ."','".Session::get('mensaje') ."', '".Session::get('tipoMsg')."');</script>" ?>
@endif