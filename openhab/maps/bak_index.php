<html><body>
  <div id="mapdiv"></div>
  <script src="http://www.openlayers.org/api/OpenLayers.js"></script>
  <script>
    map = new OpenLayers.Map("mapdiv");
    map.addLayer(new OpenLayers.Layer.OSM());

    var lonLat = new OpenLayers.LonLat(
<?php
$servername = "localhost";
$username = "nzacha";
$password = "Password1234!";
$dbname = "openhab";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT * FROM CarLocations";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
	// output data of each row
    $row = $result->fetch_assoc();
    echo $row['Latitude'].",".$row['Longitude'];
} else {
    echo "0 results";
}
$conn->close();
?>
)
         .transform(
            new OpenLayers.Projection("EPSG:4326"), // transform from WGS 1984
            map.getProjectionObject() // to Spherical Mercator Projection
          );

    var zoom=16;

    var markers = new OpenLayers.Layer.Markers( "Markers" );
    map.addLayer(markers);

    markers.addMarker(new OpenLayers.Marker(lonLat));

    map.setCenter (lonLat, zoom);
  </script>
</body></html>
