<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="initial-scale=1.0, user-scalable=no" />
<style type="text/css">
html { height: 100% }
body { height: 100%; margin: 0; padding: 0; font-family: Gotham, "Helvetica Neue", Helvetica, Arial, sans-serif; }
h1 { font-size: 24px; margin: 0 0 10px 0; }
p { font-size: 14px; line-height: 5px; }
#map-canvas { height: 100% }
a:link { text-decoration: none; color: #00F; }
a:visited { color: #00F; }
a:hover { text-decoration: none; }
#panel { position:absolute; width:235px; z-index:999999; margin:20px auto; left: 50%; margin-left:-127px; background:#FFF; box-shadow:0px 2px 3px rgba(0,0,0,.5); padding:10px; height:30px; }
#target { width:200px;height:25px;padding-right: 30px;font-size:17px; }
#submit { width:20px; height: 20px; float: right; top: 16px; right: 17px; position: absolute; border: none; background: url(images/search.jpg) no-repeat; background-size: 20px; }
#submit:hover { cursor:pointer; }
#submit:after { content: " "; visibility: hidden; display: block; clear: both; }
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
			// var mc = new MarkerClusterer(map,markers,mcOptions);
		}
	});
	
    
    var infoWindow = new google.maps.InfoWindow();
  }
  google.maps.event.addDomListener(window, 'load', initialize);
</script>
</head>
<body>
	<!-- <div id="panel">
    <form id="form">
      <input id="target" type="text" placeholder="SEARCH"><input id="submit" type="submit" value="">
    </form>
    </div> -->
    <div id="map-canvas"/>
</body>
</html>