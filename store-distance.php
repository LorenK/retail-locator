<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="initial-scale=1.0, user-scalable=no" />
<style type="text/css">
html { 
	height: 100% 
}
body { 
	height: 100%; 
	margin: 0; 
	padding: 0; 
	font-family: Gotham, "Helvetica Neue", Helvetica, Arial, sans-serif; 
}
h1 { 
	font-size: 24px; 
	margin: 0 0 10px 0; 
}
p { 
	font-size: 14px; 
	line-height: 5px; 
}
#map-canvas { 
	height: 65%;
}
a:link { 
	text-decoration: none; 
	color: #00F; 
}
a:visited { 
	color: #00F; 
}
a:hover { 
	text-decoration: none; 
}
#panel { 
	position:absolute; 
	width:255px; 
	z-index:999999; 
	margin:20px auto; 
	left: 50%; 
	margin-left:-127px; 
	background:#FFF; 
	padding: 8px; 
	height: 30px; 
	-webkit-border-radius: 6px; 
	-moz-border-radius: 6px; 
	-o-border-radius: 6px; 
	-ms-border-radius: 6px; 
	border-radius: 6px; 
	-webkit-box-shadow:0px 2px 6px rgba(0,0,0,.75); 
	-moz-box-shadow:0px 2px 6px rgba(0,0,0,.75); 
	-o-box-shadow:0px 2px 6px rgba(0,0,0,.75); 
	-ms-box-shadow:0px 2px 6px rgba(0,0,0,.75); 
	box-shadow:0px 2px 6px rgba(0,0,0,.75); 
}
#target { 
	width:240px;
	height: 31px;
	padding-right: 30px;
	font-size:17px; 
	border: none; 
	-webkit-box-sizing: border-box;
	-moz-box-sizing: border-box;
	-o-box-sizing: border-box;
	-ms-box-sizing: border-box;
	box-sizing: border-box;
}
#target:focus { 
	outline: none; 
}
#submit { 
	width:20px; 
	height: 20px; 
	float: right; 
	top: 14px; 
	right: 17px; 
	position: absolute; 
	border: none;
	background: url(images/search.jpg) no-repeat; 
	-webkit-background-size: 20px; 
	-moz-background-size: 20px; 
	-o-background-size: 20px; 
	-ms-background-size: 20px; 
	background-size: 20px; 
	filter: progid:DXImageTransform.Microsoft.AlphaImageLoader(src='images/search.jpg',sizingMethod='scale');
}
#submit:hover { 
	cursor:pointer; 
}
#submit:after { 
	content: " "; 
	visibility: hidden; 
	display: block; 
	clear: both; 
}
#store-info {
	width: 100%;
	display: none;
	padding: 20px;
	color: #333;
	border-top: 4px solid #333;
	-webkit-box-sizing: border-box;
	-moz-box-sizing: border-box;
	-o-box-sizing: border-box;
	-ms-box-sizing: border-box;
	box-sizing: border-box;
}
#store-info h1, #store-info h3 {
	text-transform: uppercase;
}
#store-info .close {
	color: #F00;
}
#store-info .far {
	color: #0F0;
}
@media only screen and (max-width: 570px) {
	#panel {
		margin-left: -157px;
	}
}
@media only screen and (max-width:480px) {
	#panel { 
		position: relative; 
		width: 100%; 
		height: 10%; 
		left: auto;
		margin: 0;
		border-radius: 0;
		padding: 0;
	}
	#target {
		position: absolute;
		top: 50%;
		margin-top: -16px;
		padding-left: 20px;
		width: 88%;
	}
	#submit {
		position: absolute;
		top: 50%;
		margin-top: -10px;
		right: 20px;
	}
}
@media only screen and (min-width: 873px) {
	#panel {
		width: 580px;
		margin-left: -290px;
	}
	#target {
		width: 540px;
	}
}
</style>
<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBK6IoTWCxBfhjaGIq5hiF_XKIfmuqO1KI&sensor=true"></script>
<script type="text/javascript" src="http://code.jquery.com/jquery-1.9.1.min.js"></script>
<script type="text/javascript">
  function initialize() {
    var mapOptions = {
      center: new google.maps.LatLng(39.50, -98.35),
      zoom: 4,
      mapTypeId: google.maps.MapTypeId.ROADMAP
    };
	
	var map = new google.maps.Map(document.getElementById("map-canvas"),mapOptions);
	var markers = [];
	var searchMarkers = [];
	
	/* Try HTML5 geolocation
    if(navigator.geolocation) { navigator.geolocation.getCurrentPosition(function(position) {
        var pos = new google.maps.LatLng(position.coords.latitude,position.coords.longitude);
		map.setCenter(pos);
		map.setZoom(11);
      });
    } */
    
    
    
    
    
    // Connect to home base and get the markers for the appropriate store
    $.ajax({
		url : "http://s33796.gridserver.com/newlocator/stores.php?store=<?php echo $_GET['store']; ?>",                          
		type: 'GET',                   
		dataType:'json',                   
		success : function(data) {  
			// console.log(data); // For dubigging purposes
    		var bounds = new google.maps.LatLngBounds();
			for (var i = 0, len = data.length; i < len; ++i) {
				var n = data[i].name.toLowerCase();
				var searchTerm = "<?php echo strtolower(urlencode($_GET['store2'])); ?>";
					if (n.search(searchTerm) > -1) {
					var marker = new google.maps.Marker({
						position: new google.maps.LatLng(data[i].lat, data[i].long),
						map: map,
						title: data[i].name,
						data: data[i].description
					});
						markers.push(marker);
						google.maps.event.addListener(marker, 'click', function() {
							infoWindow.setContent(this.data);  
							infoWindow.open(map,this) 
						});
						bounds.extend(marker.position);
					}
			}
		 	map.fitBounds(bounds);
			var mcOptions = {gridSize: 100, maxZoom: 7};
		}
	});
	
	
	
	//set the geocoder
	var geocoder = new google.maps.Geocoder();
	

	$('#form').submit(function(event) {
		event.preventDefault();
		
		var address = $('#target').val();
		geocoder.geocode( { 'address': address}, function(results, status) {
			if (status == google.maps.GeocoderStatus.OK) 
			{	
				find_closest_marker(results[0].geometry.location.kb,results[0].geometry.location.lb);
			} 
			else 
			{
				alert(address + " could not be found.");
			}
		});
		
		
	});
	
	
	// Find closest marker to....CLICK!
	function rad(x) {return x*Math.PI/180;}
	function find_closest_marker( lat,lng ) {
		//Clearing markers, if they exist
        if(searchMarkers && searchMarkers.length !== 0){
            for(var i = 0; i < searchMarkers.length; ++i){
                searchMarkers[i].setMap(null);
            }
        }
		// var lat = location.latLng.lat();
		// var lng = location.latLng.lng();
		var image = "images/reticle.png";
		var marker = new google.maps.Marker({
			position: new google.maps.LatLng(lat,lng),
			map: map,
			icon: image
		});
		searchMarkers.push(marker);
		var bounds2 = new google.maps.LatLngBounds();
		bounds2.extend(marker.position);
		var R = 6371; // radius of earth in km
		var distances = [];
		var closest = -1;
		for( i=0;i<markers.length; i++ ) {
			var mlat = markers[i].position.lat();
			var mlng = markers[i].position.lng();
			var dLat  = rad(mlat - lat);
			var dLong = rad(mlng - lng);
			var a = Math.sin(dLat/2) * Math.sin(dLat/2) +
				Math.cos(rad(lat)) * Math.cos(rad(lat)) * Math.sin(dLong/2) * Math.sin(dLong/2);
			var c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
			var d = R * c;
			distances[i] = d;
			if (closest == -1 || d < distances[closest]) {
				closest = i;
				var distance = (d).toFixed(2);
			}
		}
		bounds2.extend(markers[closest].position);
		map.fitBounds(bounds2);
		$('#store-info').css("display","block");
		$('#store-name').html("The closest "+markers[closest].title+" is "+distance + " miles");
		$('#store-details').html(markers[closest].data);
	}
    
	
    var infoWindow = new google.maps.InfoWindow();	
  }
  google.maps.event.addDomListener(window, 'load', initialize);
</script>
</head>
<body>
	<div id="panel">
    <form id="form">
      <input id="target" type="text" placeholder="STORE ADDRESS"><input id="submit" type="submit" value="">
    </form>
    </div>
    <div id="map-canvas"></div>
    <div id="store-info">
        <h1 id="store-name">Store</h1>
        <p>------------</p>
        <div id="store-details"></div>
    </div>
</body>
</html>