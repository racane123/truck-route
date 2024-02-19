<?php
include('dbconn.php');

// Retrieve form data
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get data sent via AJAX
    $driver_id = $_POST['driver_id'];
    $routeGeometry = $_POST['routeGeometry'];
    $routeName = $_POST['routeName'];
    $status = "Pending"; // Default status
    
    // Check if the driver_id exists in the Drivers table
    $driver_check_query = "SELECT driver_id FROM Drivers WHERE driver_id = ?";
    $stmt_check = $conn->prepare($driver_check_query);
    $stmt_check->bind_param("i", $driver_id);
    $stmt_check->execute();
    $result = $stmt_check->get_result();

    if ($result->num_rows == 0) {
        echo "Error: Driver with ID $driver_id does not exist.";
    } else {
        // Prepare SQL statement to insert data into the database
        $sql = "INSERT INTO Routes (driver_id, end_location, constraints, status) VALUES (?, ST_GeomFromGeoJSON(?), ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("isss", $driver_id, $routeGeometry, $routeName, $status);

        // Execute the statement
        if ($stmt->execute() === TRUE) {
            echo "Route stored successfully.";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }
}

// Close the connection
$conn->close();
?>
