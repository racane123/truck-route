<?php
include("header.php");
?>
<script>
$(document).ready(function(){
    // Fetch user info and populate select options
    $.ajax({
        url: 'fetch_user_info.php',
        type: 'GET',
        success: function(response) {
            // Parse JSON response
            var data = JSON.parse(response).data;
            
            // Populate select element with usernames
            var selectOptions = '';
            data.forEach(function(user) {
                selectOptions += '<option value="' + user.username + '">' + user.username + '</option>';
            });
            
            // Append options to select element
            $('#userSelect').html(selectOptions);
        },
        error: function(xhr, status, error) {
            console.error(xhr.responseText);
        }
    });
    
    // Form submission using AJAX
    $('#submitButton').click(function(event){
        event.preventDefault(); // Prevent default form submission
        
        // Get selected username
        var selectedUsername = $('#userSelect').val();
        var name = $('#nameInput').val(); // Get the name from the input field
        
        // AJAX request to save_drivers_info.php
        $.ajax({
            url: 'save_drivers_info.php',
            type: 'POST',
            data: { userSelect: selectedUsername, name: name }, // Send the name data
            success: function(response) {
                // Display success message or handle response as needed
                console.log(response);
                alert("Data saved successfully!");
            },
            error: function(xhr, status, error) {
                // Display error message or handle error as needed
                console.error(xhr.responseText);
                alert("Error saving data!");
            }
        });
    });
});
</script>

<body>

<h2>User Selection</h2>

<form method="POST"> <!-- Set method to POST -->
    <label for="userSelect">Select a User:</label><br>
    <select id="userSelect" name="userSelect">
        <!-- Usernames will be dynamically added here -->
    </select><br>
    <label for="nameInput">Name:</label><br> <!-- Change id to name -->
    <input type="text" id="nameInput" name="name" placeholder="name"><br> <!-- Set name attribute -->
    <button type="button" id="submitButton">Save</button> <!-- Change type to button to prevent form submission -->
</form>

</body>
