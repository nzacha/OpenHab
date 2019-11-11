<html><body>
  <div id="mapdiv"></div>
  <script src="http://www.openlayers.org/api/OpenLayers.js"></script>
  <script>
    map = new OpenLayers.Map("mapdiv");
    map.addLayer(new OpenLayers.Layer.OSM());

	var results =<?php 
		$servername = "localhost";
		$username = "nzacha";
		$password = "Password1234!";
		$dbname = "openhab";
		
		$conn = new mysqli($servername, $username, $password, $dbname);		

		if ($conn->connect_error){
			die("Connection failed: " . $conn->connect_error);
		}

		$sql = "Select * FROM CarLocations ORDER BY ID DESC LIMIT 5";
		$results = $conn->query($sql);

		$retVal = [];
		if($results->num_rows > 0){
			$i=0;
			while($row = $results->fetch_assoc() and $i<5){
				array_push($retVal, $row);			
				$i++;
			}
			echo json_encode($retVal);
		}
		$conn->close();
	?>;
 
    var markers = new OpenLayers.Layer.Markers( "Markers" );
    map.addLayer(markers);    
    var zoom=16;

    for(i=0; i<5; i++){
    var lonLat = new OpenLayers.LonLat([results[i].Latitude,results[i].Longitude])
          .transform(
            new OpenLayers.Projection("EPSG:4326"), // transform from WGS 1984
	    map.getProjectionObject() // to Spherical Mercator Projection 
    );
    markers.addMarker(new OpenLayers.Marker(lonLat));
    if(i===0)      
        map.setCenter (lonLat, zoom);
    } 
  </script>
</body></html>
