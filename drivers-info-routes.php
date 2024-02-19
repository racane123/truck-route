<?php
include('dbconn.php');
// SQL query to fetch data from Routes table
if($_SERVER['REQUEST_METHOD'] == 'GET'){
    $sql = "SELECT *, ST_AsGeoJSON(end_location) as geojson_end_location FROM Routes";

    // Execute query
    $result = $conn->query($sql);

    $response = array(); // Initialize an array to hold responses

    if ($result->num_rows > 0) {
        // Output data of each row
        while($row = $result->fetch_assoc()) {
            // Decode GeoJSON to get the coordinates
            $geojson_end_location = json_decode($row['geojson_end_location'], true);
            $coordinates = $geojson_end_location['coordinates'];

            // Building response for each row
            $response[] = array(
                'Id' => $row['driver_id'], 
                'Coordinates' => $coordinates, 
                'Constraints' => $row['constraints'], 
                'Status' => $row['status']
            );
        }
        // Outputting the JSON encoded response
        echo json_encode($response);
    } else {
        echo "0 results";
    }

} else {
    die("Invalid HTTPS request");
}

// Close connection
$conn->close();
?>
