<html><body>
  <div id="mapdiv"></div>
  <script src="http://www.openlayers.org/api/OpenLayers.js"></script>
  <script>
    map = new OpenLayers.Map("mapdiv");
    map.addLayer(new OpenLayers.Layer.OSM());

        var data=<?php 
		$servername = "localhost";
		$username = "nzacha";
		$password = "Password1234!";
		$dbname = "openhab";
		
		$conn = new mysqli($servername, $username, $password, $dbname);		

		if ($conn->connect_error){
			die("Connection failed: " . $conn->connect_error);
		}

		$sql = "Select ID, Name FROM Cars ORDER BY ID";
		$results = $conn->query($sql);

		$retVal = [];
		if($results->num_rows > 0){
			while($row = $results->fetch_assoc()){
				$innerArray = [];
				$inner_sql = "Select Longitude, Latitude, Timestamp,(CURDATE() - INTERVAL 1 DAY) AS diff FROM CarLocations WHERE Car_ID = ".$row[ID];
				$inner_results = $conn->query($inner_sql);
				while($inner_row = $inner_results->fetch_assoc()){
					array_push($innerArray, $inner_row);	
				}
				$object = array(
					"Name" => $row["Name"] , "Locations" => array_values($innerArray) 				       );
 			        array_push($retVal, $object);
			}
			echo json_encode($retVal);
		}
		$conn->close();
        ?>;
    var markers = new OpenLayers.Layer.Markers( "Markers" );
    map.addLayer(markers);    
    var zoom=16;

    var i,j;
    for(i=0; i< data.length; i++){
	if(data[i].Locations.length>0){
	    for(j=0; j< data[i].Locations.length; j++){
    	        var lonLat = new OpenLayers.LonLat([data[i].Locations[j].Longitude, data[i].Locations[j].Latitude])
                    .transform(
                    new OpenLayers.Projection("EPSG:4326"), // transform from WGS 1984
	            map.getProjectionObject() // to Spherical Mercator Projection 
    	            );
    	        markers.addMarker(new OpenLayers.Marker(lonLat));		
	    } 
	    if(i===0){
		console.log("Car "+data[i].Name+" has "+data[i].Locations.length+" locations listed.");
	        map.setCenter(lonLat, zoom);
	    }
	}else{
	    console.log("No Locations found for car: "+data[i].Name);
	}
    }
    console.log(data);
    //console.log(Object.keys(data).length);
    //console.log(data[0]);
  </script>
</body></html>
