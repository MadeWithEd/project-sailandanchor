/* Mapping */

var markers = [];
var IE6 = false /*@cc_on || @_jscript_version < 5.7 @*/;
NumMarkers=0; // increments each time a marker is added so we can access it by id
var gmarkers = [];
var idmarkers = [];
var markerkeys = [];
var map;
var markerClusterer = null;



	
function InitMap(Lat,Long,Zoom,Keyword) {
  	var savedLocation = null;
  	//alert(Zoom);
  	if(Keyword===undefined) {
	Keyword='';
	}
  var myOptions = {
    zoom: Zoom,
    center: new google.maps.LatLng(Lat, Long),
    mapTypeControl: false,
    panControl: false,
    panControlOptions: {
        position: google.maps.ControlPosition.TOP_LEFT
    },
    zoomControl: false,
    zoomControlOptions: {
        style: google.maps.ZoomControlStyle.LARGE,
        position: google.maps.ControlPosition.TOP_LEFT
    },
    scaleControl: false,
    scaleControlOptions: {
        position: google.maps.ControlPosition.TOP_LEFT
    },
    streetViewControl: false,
    mapTypeId: google.maps.MapTypeId.ROADMAP
    //styles:[ { "featureType": "water", "stylers": [ { "color": "#ffc000" } ] } ]
    
  }
  map = new google.maps.Map(document.getElementById("Map"),
        myOptions);
        
	infowindow = new google.maps.InfoWindow({
	content: "holding..."
	});
        
   bounds = new google.maps.LatLngBounds();
   
   google.maps.event.addListener(map, 'zoom_changed', function() {
     if (map.getZoom() < 2) map.setZoom(2);
   });
   
  	google.maps.event.addListener(infowindow, 'closeclick', function() {
	  returnToSavedPosition();
	});
   
  // DisplaySites('','','');
}

function savePosition() {
    savedLocation = map.getCenter();
}

function returnToSavedPosition() {
    if (savedLocation) {
    map.setCenter(savedLocation);
	}
}

function toggleOverlay() {
  if (!overlayInstance) {
    overlayInstance = new GStreetviewOverlay();
    map.addOverlay(overlayInstance);
  } else {
    map.removeOverlay(overlayInstance);
    overlayInstance = null;
  }
}

// Displays all pins from json file

var DisplaySites = function(lat,lng,showlabels) {
	
	for(x=0;x<markers.length;x++) {
		markers[x].setMap(null);
		//markers[x]=null;
	}
	
	markers=[];
	
	if(lat!='' && lng!='') {
	var query='/_mapdata.php?lat='+lat+'&lng='+lng;
	}
	else {
	var query='/_mapdata.php?';
	}
	
	var latlngbounds = new google.maps.LatLngBounds();
	var x=1;
	$.getJSON(query,function(json) {	
		$.each(json.markers, function(i,data){
			var jmarker = NewMarker(new google.maps.LatLng(data.lat, data.lng),data.store_id,data.type,x,showlabels);
    		markers.push(jmarker);
    		latlngbounds.extend(new google.maps.LatLng(data.lat, data.lng));
    		x++;
    	});
    map.fitBounds(latlngbounds);
    });
    
};

function NewMarker(markerLocation,store_id,store_type,number,showlabels) {
	if(showlabels!='') {
	var image='/pin.php?number='+number;
	}
	else {
	var image='/images/PIN.png';
	}
	var themarker = new google.maps.Marker({
      	position: markerLocation,
      	map: map,
      	icon: new google.maps.MarkerImage(image,
			new google.maps.Size(22, 28),
			new google.maps.Point(0,0),
			new google.maps.Point(11,28)),
      	type: store_type,
      	id: store_id
    });
   // google.maps.event.addListener(themarker, "mouseover", function() {
   // 	$('#S'+this.id).addClass('current');
    	//load_content(this);
   // });
   // google.maps.event.addListener(themarker, "mouseout", function() {
   // 	$('#S'+this.id).removeClass('current');
    	//load_content(this);
   // });
    google.maps.event.addListener(themarker, "click", function() {
    	load_content(this);
    });
	
return themarker;
}

function offsetCenter(latlng,offsetx,offsety) {
	var scale = Math.pow(2, map.getZoom());
	var nw = new google.maps.LatLng(
	    map.getBounds().getNorthEast().lat(),
	    map.getBounds().getSouthWest().lng()
	);

	var worldCoordinateCenter = map.getProjection().fromLatLngToPoint(latlng);
	var pixelOffset = new google.maps.Point((offsetx/scale) || 0,(offsety/scale) ||0)
	
	var worldCoordinateNewCenter = new google.maps.Point(
	    worldCoordinateCenter.x - pixelOffset.x,
	    worldCoordinateCenter.y + pixelOffset.y
	);

	var newCenter = map.getProjection().fromPointToLatLng(worldCoordinateNewCenter);
	map.setCenter(newCenter);
}

var load_content = function(marker) {
	
	$.ajax({
	url: "/_mapinfo.php?store_id=" + marker.id,
		success: function(data){
		infowindow.setContent(data);
		infowindow.open(map, marker);
		savePosition();
		}
	});

}


/* end mapping */