<?php
include('header.php');

?>

<form>
    <label for="driverSelect">Select a Driver</label><br>
    <select name="driverSelect" id="driverSelect">
    </select><br>
    <button type="button" id="submitButton">Save</button>

</form>

<script>
$(document).ready(function(){
    // Fetch drivers info
    $.ajax({
        url:'fetch_drivers_info.php',
        type:'GET',
        success: function(response){
            var data = JSON.parse(response).data;

            var selectOptions = '';
            data.forEach(function(driver){
                selectOptions += ' <option value ="' + driver.drive_id + '">' + driver.driver_id + '</option>';
            });

            $('#driverSelect').html(selectOptions);
        },
        error: function(xhr, status, error){
            console.error(xhr.responseText);
        }
    });

    // Submit button click event
    $('#submitButton').click(function(event){
        event.preventDefault(); // Prevent default form submission
        
        // Get selected value from driverSelect
        var selectedDriverId = $('#driverSelect').val();
        
        // AJAX request to save_drivers_info.php
        $.ajax({
            url: 'save_drivers_info.php',
            type: 'POST',
            data: { driverId: selectedDriverId }, // Assuming you want to send driverId instead of userSelect
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