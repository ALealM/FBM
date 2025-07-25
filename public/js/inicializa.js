$(document).ready(function(){
	//Asigna las coordenadas apuntando al palacio de gobierno
	var sanluisMark = {lat: 22.151755,lng: -100.976943};
	// parámetros del mapa
	var mapOptions = {
        //deshabilita el street view
        streetViewControl: false,

		// Posiciona el centro del mapa en san luis potosí
		center: sanluisMark,
		// Asigna el zoom de manera que se vea el estado
		zoom: 12,
		// Se dibuja para ver solo calles
	  	mapTypeId: google.maps.MapTypeId.ROADMAP
	};
	//Crea el mapa
	var map = new google.maps.Map(document.getElementById('mapa'), mapOptions);
	// Crea el marcador
	var marker = new google.maps.Marker({
		//Identifica el mapa en el que se muestra
    	map: map,
    	// Se especifica que el usuario pueda interactuar con el marcador
	    draggable: true,
	    //Asigna una animación
	    animation: google.maps.Animation.DROP,
	    //Posicion donde se muestra el mapa
	    position: sanluisMark
	});
	//Inicializa el geolocalizador
	var geolocalizador = new google.maps.Geocoder();
	//Ventana de información que aparece arriba del marcador
	var popup = new google.maps.InfoWindow();

	//Agrega el evento al marcador que al dar clik "brinca" el marcador
	marker.addListener('click', brincaMarcador);
	//Evento para cuando sueltan el marcador en el mapa
	marker.addListener('dragend',function(evento){
		obtieneCalleDeMapa(marker,evento.latLng,popup);
	});

	map.addListener('rightclick',function(event){
		cambiaPosicionMarcador(marker,event.latLng,geolocalizador,popup);
	});
	
	document.getElementById('posiciona').addEventListener('click', function() {
			localizaEnMapa(geolocalizador, map,marker,popup);
	});

});
				

function brincaMarcador() {
	if (this.getAnimation() !== null) {
		this.setAnimation(null);
	} else {
		this.setAnimation(google.maps.Animation.BOUNCE);
	}
}

function localizaEnMapa(geolocalizador,mapa,marcador,popup){
	var direccion = document.getElementById('calle').value;
	var numExt = document.getElementById('num-ext').value;
	var colonia = document.getElementById('colonia').value;
	console.log(direccion + " " +numExt + " " + colonia + ", San Luis Potosí");
	geolocalizador.geocode({'address':direccion + " " +numExt + " " + colonia + ", San Luis Potosí"},function(respuesta,status){
		if(status == 'OK'){
			mapa.setCenter(respuesta[0].geometry.location);
			 marcador.setPosition(respuesta[0].geometry.location);
             presicion(respuesta[0].geometry.location_type);
			 mapa.setZoom(18);
			 asignaEnInputs(respuesta[0]);
			 popup.setContent(respuesta[0].formatted_address);
			 popup.open(mapa,marcador);
          } else {
            alert('No fue posible localizar. Detalles: ' + status + '\n' + respuesta.error_message);
          }
	});
}

function presicion(location_type) {

	color = "black";

	info = document.getElementById("accuracy-info");

	text = "Verifica la posición del marcador en el mapa";

	switch(location_type) {

		case "ROOFTOP" :

			color = "limegreen";

			//text = "Muy cerca de la dirección ingresada";

		break;

		case "RANGE_INTERPOLATED" :

			color = "goldenrod";

			//text = "Cerca, es necesario posicionar el marcador manualmente";

		break;

		case "GEOMETRIC_CENTER" :

			color = "darkorange";

			//text = "Posicionar el marcador manualmente al punto exacto";

		break;

		case "APPROXIMATE" :

			color = "red";

			//text = "Posicionar el marcador manualmente al punto exacto";

		break;

	}

	info.style.display = "inline-block";

	info.style.color = color;

	info.innerHTML = text;

}

function obtieneCalleDeMapa(marcador,posicion,popup){
	var info = new Map();

	geolocalizador = new google.maps.Geocoder();
	geolocalizador.geocode({'location':posicion}, function(results, status) {
		if (status === 'OK') {
			if(results[0]){
				asignaEnInputs(results[0]);
				popup.setContent(results[0].formatted_address);
  				popup.open(this, marcador);
				}else{
					window.alert('No se encontraron resultados');
				}
		} else {
			window.alert('No se pudo efectuar la geolocalización: ' + status);
		}
	});
}

//pone el a posición indicada el marcador
function cambiaPosicionMarcador(marcador,posicion,geolocalizador,popup){
	geolocalizador.geocode({'location':posicion},function(respuesta,status){
		if(status=='OK'){
			marcador.setPosition(respuesta[0].geometry.location);
			asignaEnInputs(respuesta[0]);
			popup.setContent(respuesta[0].formatted_address);
				popup.open(this, marcador);
		}
	});
}

function asignaEnInputs(datos){
	var calle;
	var numExt;
	var colonia;
	var latitud;
	var longitud;
	var info = new Map();

	if(datos){
		for(com in datos.address_components){
			info.set(datos.address_components[com].types[0],datos.address_components[com].long_name);
		}
		if(info.get('administrative_area_level_1') == 'San Luis Potosí'){
			info.set('latitud',datos.geometry.location.lat());
			info.set('longitud',datos.geometry.location.lng());
			calle  = info.has('route') ? info.get('route') : '';
			numExt = info.has('street_number') ? info.get('street_number') : '';
			colonia = info.has('political') ? info.get('political') : '';
			latitud = info.get('latitud');
			longitud = info.get('longitud');
		}
		else{
			calle  = '';
			numExt =  '';
			colonia = '';
			latitud = '';
			longitud = '';
			window.alert('La falla se debe encontrar en el área de San Luis Potosí');
		}
		//document.getElementById('calle').value =calle;
		//document.getElementById('numExt').value = numExt;
		//document.getElementById('colonia').value = colonia;
		document.getElementById('latitud').value = latitud;
		document.getElementById('longitud').value = longitud;
	}
}
