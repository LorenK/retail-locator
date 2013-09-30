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
	height: 100% 
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
    overflow: hidden;
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
		padding-left: 10px;
		width: 88%;
	}
	#submit {
		position: absolute;
		top: 50%;
		margin-top: -10px;
		right: 10px;
	}
	#map-canvas { 
		height: 90%;
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
<script type="text/javascript" src="markerclusterer_compiled.js"></script>
<script type="text/javascript" src="http://code.jquery.com/jquery-1.9.1.min.js"></script>
<script type="text/javascript">
  function initialize() {
    var mapOptions = {
      center: new google.maps.LatLng(39.50, -98.35),
      zoom: 4,
      mapTypeId: google.maps.MapTypeId.ROADMAP
    };
	
	var map = new google.maps.Map(document.getElementById("map-canvas"),mapOptions);
	
	// Try HTML5 geolocation
    if(navigator.geolocation) { navigator.geolocation.getCurrentPosition(function(position) {
        var pos = new google.maps.LatLng(position.coords.latitude,position.coords.longitude);
		map.setCenter(pos);
		map.setZoom(11);
      });
    }
    
    
    
    
    
    // Connect to home base and get the markers for the appropriate store
    $.ajax({
		url : "http://s33796.gridserver.com/newlocator/stores.php?store=<?php echo $_GET['store']; ?>",                          
		type: 'GET',                   
		dataType:'json',                   
		success : function(data) {  
			// console.log(data); // For dubigging purposes
			var markers = [];
    		var bounds = new google.maps.LatLngBounds();
			for (var i = 0, len = data.length; i < len; ++i) {
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
		 	// map.fitBounds(bounds);
			var mcOptions = {gridSize: 100, maxZoom: 7};
			<?php if ($_GET['nc'] != "1") { ?>
				var mc = new MarkerClusterer(map,markers,mcOptions);
			<?php } ?>
		}
	});
	
	
	
	//set the geocoder
	var geocoder = new google.maps.Geocoder();
	

	$('#form').submit(function(event) {
		event.preventDefault();
		
		var address = $('#target').val();
		geocoder.geocode( { 'address': address}, function(results, status) {
			console.log(address);
			if (status == google.maps.GeocoderStatus.OK) 
			{
				map.setCenter(results[0].geometry.location);
				map.setZoom(12);
			} 
			else 
			{
				alert(address + " could not be found.");
			}
		});
		
		
	});
	
	
    
    var infoWindow = new google.maps.InfoWindow();	
  }
  google.maps.event.addDomListener(window, 'load', initialize);
</script>
</head>
<body>
	<div id="panel">
    <form id="form">
      <input id="target" type="text" placeholder="SEARCH ADDRESS OR ZIP"><input id="submit" type="submit" value="">
    </form>
    </div>
    <div id="map-canvas"/>
</body>
</html>