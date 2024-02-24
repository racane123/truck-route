<?php
// Assuming you have a MySQL connection established
require('dbconn.php');

// Check if 'endpoint' parameter is set in the URL
if(isset($_GET['endpoint'])) {
    // Get the selected endpoint value from the query string
    $selectedEndpoint = $_GET['endpoint'];

    // Query the database to get the endpoint coordinates based on the selected endpoint value
    $query = "SELECT ST_AsGeoJSON(end_location) as Coordinates FROM Routes WHERE route_id = $selectedEndpoint";
    $result = mysqli_query($conn, $query);

    if ($result) { // Check if query executed successfully
        if(mysqli_num_rows($result) > 0) { // Check if there are rows returned
            $row = mysqli_fetch_assoc($result);
            $coordinates = json_decode($row['Coordinates'], true);

            // Return the endpoint coordinates as JSON
            echo json_encode(array($coordinates));
        } else {
            echo "No data found for the provided endpoint"; // Handle case when no data found
        }
    } else {
        // Handle the case when the query fails
        echo "Error executing query: " . mysqli_error($conn);
    }
} else {
    echo "Please provide the 'endpoint' parameter in the URL"; // Handle case when 'endpoint' parameter is not provided
}
?>
