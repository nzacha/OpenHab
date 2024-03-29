<html><body>
  <div id="mapdiv"></div>
  <div id="popup" class="ol‐popup">
      <a href="#" id="popup‐closer" class="ol‐popup‐closer"></a>
      <div id="popup‐content"></div>
  </div>
  <script src="http://www.openlayers.org/api/OpenLayers.js"></script>
  <script>
    map = new OpenLayers.Map("mapdiv");
    map.addLayer(new OpenLayers.Layer.OSM());
    
    var container = document.getElementById('popup');
    var content = document.getElementById('popup‐content');
    var closer = document.getElementById('popup‐closer');


    var data=<?php	
		if(!isset($_GET["ID"])){
			echo("null");	
		}else{		

		$servername = "localhost";
		$username = "nzacha";
		$password = "Password1234!";
		$dbname = "openhab";
		
		$conn = new mysqli($servername, $username, $password, $dbname);		

		if ($conn->connect_error){
			die("Connection failed: " . $conn->connect_error);
		}

		$sql = "Select Name FROM Cars WHERE ID='".$_GET["ID"]."';";
		$results = $conn->query($sql);

		$retVal;
		if($results->num_rows > 0){
			$name = $results->fetch_assoc();
			$innerArray = [];
			$inner_sql = "Select Longitude, Latitude, Timestamp FROM CarLocations WHERE Car_ID='".$_GET["ID"]."' and Timestamp >= DATE_ADD(CURDATE(), INTERVAL -1 DAY);";
			$inner_results = $conn->query($inner_sql);
			while($inner_row = $inner_results->fetch_assoc()){
				array_push($innerArray, $inner_row);	
			}
			$retVal = array("Name" => array_values($name) , "Locations" => array_values($innerArray));			
			echo json_encode($retVal);
		}
		$conn->close();

		if(!($retVal))
	            echo '""';
		}
	?>;
    if(data===""){
            throw new Error("The car specified cannot be found");
    }

    var markers = new OpenLayers.Layer.Markers("Markers");
    map.addLayer(markers);    
    var zoom=16;

    var i;
    if(data.Locations.length>0){
        for(i=0; i< data.Locations.length; i++){
    	    var lonLat = new OpenLayers.LonLat([data.Locations[i].Longitude, data.Locations[i].Latitude])
                .transform(
                new OpenLayers.Projection("EPSG:4326"), // transform from WGS 1984
	        map.getProjectionObject() // to Spherical Mercator Projection 
    	        );
    	    markers.addMarker(new OpenLayers.Marker(lonLat));		 
	    if(i===0){
	        console.log("Car "+data.Name+" has "+data.Locations.length+" locations listed.");
	    }
	    map.setCenter(lonLat, zoom);
	}
    }else{
        console.log("No Locations found for car: "+data.Name);
    }
    console.log(data);

    map.events.register('click',map, function(evt) {
	var clickCoord = map.getLonLatFromPixel(evt.xy);
	clickCoord = new OpenLayers.LonLat(clickCoord.lon , clickCoord.lat).transform( new OpenLayers.Projection("EPSG:900913"), new OpenLayers.Projection("EPSG:4326") );	    
	console.log("You clicked on (lon,lat):" + clickCoord);

	var threshhold = 0.0001;
	for (var i in markers.markers) {
	    var markerCoord = new OpenLayers.LonLat(markers.markers[i].lonlat.lon , markers.markers[i].lonlat.lat).transform( new OpenLayers.Projection("EPSG:900913"), new OpenLayers.Projection("EPSG:4326") );	    
	    //console.log("There is a marker on (lon,lat): "+markerCoord);
	    if(Math.abs(markerCoord.lon - clickCoord.lon) <threshhold && Math.abs(markerCoord.lat - clickCoord.lat) < threshhold)
		    console.log("marker clicked!");
	}
    });  
    
    
  </script>
</body></html>
