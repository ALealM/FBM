
        function agrega_articulo(){
            // Obtenemos el numero de filas (td) que tiene la primera columna
            // (tr) del id "tabla"
            var tds=$("#t_articulos tr:first td").length;
            // Obtenemos el total de columnas (tr) del id "tabla"
            var trs=$("#t_articulos tr").length;
            var nuevaFila="<tr>";
            for(var i=0;i<tds;i++){
                // añadimos las columnas
                nuevaFila+='<td style="width: 5%;"><input type="number" name="numero_art'+trs+'" id="numero_art'+trs+'" class="form-control"></td>';
                nuevaFila+='<td style="width: 30%;"><input type="text" name="concepto_art'+trs+'" id="concepto_art'+trs+'" class="form-control"></td>';
                nuevaFila+='<td style="width: 5%;"><input type="number" name="cantidad_art'+trs+'" id="cantidad_art'+trs+'" value="0" class="form-control"></td>';
                nuevaFila+='<td style="width: 5%;"><input type="number" name="precio_art'+trs+'" id="precio_art'+trs+'" value="0" class="form-control"></td>';
                nuevaFila+='<td style="width: 40%;"><input type="text" name="descripcion_art'+trs+'" id="descripcion_art'+trs+'" class="form-control"></td>';
                nuevaFila+='<td style="width: 10%;"><input type="text" name="total_art'+trs+'" id="total_art'+trs+'" class="form-control"></td>';
                nuevaFila+='<td><input type="text" name="elegible_art'+trs+'" id="elegible_art'+trs+'" class="form-control"></td>';
            }
            // Añadimos una columna con el numero total de filas.
            // Añadimos uno al total, ya que cuando cargamos los valores para la
            // columna, todavia no esta añadida
            nuevaFila+='<td style="width: 5%;"><input type="number" name="numero_art'+trs+'" id="numero_art'+trs+'" class="form-control"></td>';
                nuevaFila+='<td style="width: 30%;"><input type="text" name="concepto_art'+trs+'" id="concepto_art'+trs+'" class="form-control"></td>';
                nuevaFila+='<td style="width: 5%;"><input type="number" name="cantidad_art'+trs+'" id="cantidad_art'+trs+'" value="0" class="form-control"></td>';
                nuevaFila+='<td style="width: 5%;"><input type="number" name="precio_art'+trs+'" id="precio_art'+trs+'" value="0" class="form-control"></td>';
                nuevaFila+='<td style="width: 40%;"><input type="text" name="descripcion_art'+trs+'" id="descripcion_art'+trs+'" class="form-control"></td>';
                nuevaFila+='<td style="width: 10%;"><input type="text" name="total_art'+trs+'" id="total_art'+trs+'" class="form-control"></td>';
                nuevaFila+='<td><input type="text" name="elegible_art'+trs+'" id="elegible_art'+trs+'" class="form-control"></td>';
            nuevaFila+="</tr>";
            $("#t_articulos").append(nuevaFila);
        };
 
        /**
         * Funcion para eliminar la ultima columna de la tabla.
         * Si unicamente queda una columna, esta no sera eliminada
         */
        function elimina_articulo(){
            // Obtenemos el total de columnas (tr) del id "tabla"
            var trs=$("#t_articulos tr").length;
            if(trs>2)
            {
                // Eliminamos la ultima columna
                $("#t_articulos tr:last").remove();
            }
        };

        function agrega_aprobado(){
            // Obtenemos el numero de filas (td) que tiene la primera columna
            // (tr) del id "tabla"
            var tds2=$("#t_aprobados tr:first td").length;
            // Obtenemos el total de columnas (tr) del id "tabla"
            var trs2=$("#t_aprobados tr").length;
            trs2=trs2-1;
            var nuevaFila="<tr>";
            // Añadimos una columna con el numero total de filas.
            // Añadimos uno al total, ya que cuando cargamos los valores para la
            // columna, todavia no esta añadida
                nuevaFila+='<td><input type="number" name="numero_aprob'+trs2+'" id="numero_aprob'+trs2+'" class="form-control"></td>';
                nuevaFila+='<td><input type="text" name="cuenta_clabe'+trs2+'" id="cuenta_clabe'+trs2+'" class="form-control"></td>';
                nuevaFila+='<td><input type="text" name="banco'+trs2+'" id="banco'+trs2+'" class="form-control"></td>';
                nuevaFila+='<td><input type="text" name="rfc'+trs2+'" id="rfc'+trs2+'" class="form-control"></td>';
                nuevaFila+='<td><input type="text" name="razon_social'+trs2+'" id="razon_social'+trs2+'" class="form-control"></td>';
                nuevaFila+='<td><input type="text" name="partida'+trs2+'" id="partida'+trs2+'" class="form-control"></td>';
                nuevaFila+='<td><input type="text" name="especificaciones'+trs2+'" id="especificaciones'+trs2+'" class="form-control"></td>';
                nuevaFila+='<td><input type="text" name="fecha_aplicacion'+trs2+'" id="fecha_aplicacion'+trs2+'" class="form-control datepicker"></td>';
                nuevaFila+='<td><input type="text" name="forma_pago'+trs2+'" id="forma_pago'+trs2+'" class="form-control"></td>';
                nuevaFila+='<td><input type="text" name="precio_fnal'+trs2+'" id="precio_fnal'+trs2+'" class="form-control"></td>';
            nuevaFila+="</tr>";
            $("#t_aprobados").append(nuevaFila);
        };
 
        /**
         * Funcion para eliminar la ultima columna de la tabla.
         * Si unicamente queda una columna, esta no sera eliminada
         */
        function elimina_aprobado(){
            // Obtenemos el total de columnas (tr) del id "tabla"
            var trs=$("#t_aprobados tr").length;
            if(trs>3)
            {
                // Eliminamos la ultima columna
                $("#t_aprobados tr:last").remove();
            }
        };