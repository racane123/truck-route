<?php
include('header.php');
?>

<style>
    body { margin: 0; padding: 0; }
    #map { position: absolute; top: 0; bottom: 0; width: 100%; }
    .mapboxgl-ctrl-group.mapboxgl-ctrl { display: block; }
    #routeForm{
        position: absolute;
        top: 20px;
        left: 20px;
        background-color: white;
        padding: 10px;
        border-radius: 5px;
    }
</style>

<div id='map'></div>

<div id="routeForm">
    <h3>Route Information</h3>
    <form id="drawForm">
        <label for="routeName">Route Name:</label>
        <input type="text" id="routeName" name="routeName" required><br><br>
        <label for="driver">Select Driver:</label>
        <select id="driver" name="driver">
            <option value="">Select a driver</option>
        </select><br><br>
        <input type="submit" value="Submit">
    </form>
</div>

<script>
mapboxgl.accessToken = 'pk.eyJ1IjoicmFjYW5lMTIzIiwiYSI6ImNscDJhZ2xmbDBwdmEybG9pa2w4Yms0emEifQ.vyLoKd0CBDl14MKI_9JDCQ';
var map = new mapboxgl.Map({
    container: 'map',
    style: 'mapbox://styles/mapbox/streets-v11',
    center: [121.04207, 14.75782],
    zoom: 16
});

map.on('load', function () {
    var draw = new MapboxDraw({
        displayControlsDefault: false,
        controls: {
            point: true,
            trash: true
        }
    });

    map.addControl(draw);

    // Fetch data from fetch_drivers_info.php
    fetch('fetch_drivers_info.php')
        .then(response => response.json())
        .then(data => {
            console.log(data);
            if (data.status === 200) {
                const driversSelect = document.getElementById('driver');
                data.data.forEach(driver => {
                    const option = document.createElement('option');
                    option.value = driver.driver_id; // Assuming 'id' is the field containing driver IDs
                    option.textContent = driver.name; // Assuming 'name' is the field containing driver names
                    driversSelect.appendChild(option);
                });
            } else {
                console.error('Error fetching driver data:', data.message);
            }
        })
        .catch(error => {
            console.error('Error fetching driver data:', error);
        });

    map.on('draw.create', function (event) {
        var data = draw.getAll();
        console.log(data);

        // Populate form fields with draw data
        document.getElementById("routeForm").style.display = "block";
        document.getElementById("routeForm").addEventListener("submit", function (e) {
            e.preventDefault();
            var routeName = document.getElementById("routeName").value;
            var driverId = document.getElementById("driver").value;
            var routeGeometry = JSON.stringify(data.features[0].geometry); // Convert geometry to JSON string

            // AJAX request to save route data
            $.ajax({
                url: 'save-drivers-router.php',
                type: 'POST',
                data: {
                    driver_id: driverId,
                    routeName: routeName,
                    routeGeometry: routeGeometry
                },
                success: function(response) {
                    // Display success message or handle response as needed
                    console.log(response);
                    alert("Route stored successfully!");
                },
                error: function(xhr, status, error) {
                    // Display error message or handle error as needed
                    console.error(xhr.responseText);
                    alert("Error storing route!");
                }
            });
        });
    });
});

</script>

