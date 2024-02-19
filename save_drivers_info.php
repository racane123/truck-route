<?php
include ('dbconn.php');

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate if a username is selected
    if (isset($_POST['userSelect']) && !empty($_POST['userSelect'])) {
        // Sanitize the selected value (username) and name
        $selectedUsername = htmlspecialchars($_POST['userSelect']);
        $name = htmlspecialchars($_POST['name']);
        
        // Prepare SQL statement to fetch the user ID corresponding to the selected username
        $stmt = $conn->prepare("SELECT user_id FROM Users WHERE username = ?");
        $stmt->bind_param("s", $selectedUsername);
        
        // Execute the statement
        $stmt->execute();
        
        // Bind the result variable
        $stmt->bind_result($userId);
        
        // Fetch the result
        $stmt->fetch();
        
        // Close the statement
        $stmt->close();
        
        // Check if a valid user ID is fetched
        if ($userId !== null) {
            // Prepare SQL statement to insert the fetched user ID into the Drivers table
            $stmt = $conn->prepare("INSERT INTO Drivers (user_id, name) VALUES (?, ?)");
            $stmt->bind_param("is", $userId, $name); // Assuming the user_id column is of type INT, change 'i' accordingly
            
            // Execute the statement
            if ($stmt->execute() === TRUE) {
                echo "Driver information stored successfully.";
            } else {
                echo "Error: " . $stmt->error;
            }
            
            // Close the statement
            $stmt->close();
        } else {
            echo "Invalid username.";
        }

        // Close the connection
        $conn->close();
    } else {
        echo "Please select a username.";
    }
}
?>
