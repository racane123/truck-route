<?php
include('dbconn.php');

$sql = "SELECT ST_AsGeoJSON(end_location) AS geojson_end_location FROM Routes LIMIT 1";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    echo $row['geojson_end_location'];
} else {
    echo json_encode(array('error' => 'No endpoint found in the database'));
}

$conn->close();
?>
