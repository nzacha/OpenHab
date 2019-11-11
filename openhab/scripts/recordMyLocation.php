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
$sql = "INSERT INTO CarLocations(Longitude, Latitude) VALUES ("."'".$_POST["Longitude"]."'".","."'".$_POST["Latitude"]."')";

if (mysqli_query($conn, $sql)) {
    echo "New record created successfully";
} else {
    echo "Error: " . $sql . "" . mysqli_error($conn);
}
$conn->close();
?>
