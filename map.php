<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>Admin Route Drawing with Truck Location</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <script src="https://api.mapbox.com/mapbox-gl-js/v2.6.1/mapbox-gl.js"></script>
  <link href="https://api.mapbox.com/mapbox-gl-js/v2.6.1/mapbox-gl.css" rel="stylesheet">
  <script src="https://api.mapbox.com/mapbox-gl-js/plugins/mapbox-gl-draw/v1.4.0/mapbox-gl-draw.js"></script>
  <link href="https://api.mapbox.com/mapbox-gl-js/plugins/mapbox-gl-draw/v1.4.0/mapbox-gl-draw.css" rel="stylesheet" />

  <style>
    #map {
      width: 100%;
      height: 600px;
    }
    #div{
        position: absolute;
        top:10px;
    }


  </style>
</head>
<body>

<div id="map"></div>
<div id="info"></div>
<div id="div">
  <select id="endpoint-select">
    <option value="">Select Endpoint</option>
  </select>
</div>
<script>
mapboxgl.accessToken = 'pk.eyJ1IjoicmFjYW5lMTIzIiwiYSI6ImNscDJhZ2xmbDBwdmEybG9pa2w4Yms0emEifQ.vyLoKd0CBDl14MKI_9JDCQ';

// Create a map container
var map = new mapboxgl.Map({
  container: 'map',
  style: 'mapbox://styles/mapbox/streets-v11',
  center: [121.04207, 14.75782], // Default center coordinates
  zoom: 12
});


navigator.geolocation.getCurrentPosition(successLocation, errorLocation, { enableHighAccuracy: true });

function successLocation(position) {
  var startPoint = [position.coords.longitude, position.coords.latitude]; // Starting point coordinates based on user's current location

  var endpointSelect = document.getElementById('endpoint-select');
  endpointSelect.addEventListener('change', function() {
    var selectedEndpoint = endpointSelect.value;
    console.log(selectedEndpoint);
    if (selectedEndpoint !== '') {
      // Fetch the selected endpoint coordinates from the database
      fetch('drivers-info-routes.php?endpoint=' + selectedEndpoint)
        .then(function(response) {
          return response.json();
        })
        .then(function(data) {

          if (data.length > 0 && data[0].coordinates){
            var endPoint = data[0].coordinates; // Assuming 'coordinates' is the key for endpoint coordinates

          // Define the API request URL
          var apiUrl = 'https://api.mapbox.com/directions/v5/mapbox/driving/' +
            startPoint[0] + ',' + startPoint[1] + ';' +
            endPoint[0] + ',' + endPoint[1] +
            '?steps=true&geometries=geojson&access_token=' + mapboxgl.accessToken;

          // Make the API request and display the route
          fetch(apiUrl)
            .then(function(response) {
              return response.json();
            })
            .then(function(data) {
              var route = data.routes[0]; // Retrieve the first route (the most optimal one)
              var routeGeometry = route.geometry; // Retrieve the route geometry

              // Create a GeoJSON source with the route geometry
              var routeSource = {
                type: 'geojson',
                data: {
                  type: 'Feature',
                  properties: {},
                  geometry: routeGeometry
                }
              };

              // Remove existing route layer if it exists
              if (map.getSource('route')) {
                map.removeLayer('route');
                map.removeSource('route');
              }

              // Add the route source to the map
              map.addSource('route', routeSource);

              // Add a layer to display the route
              map.addLayer({
                id: 'route',
                type: 'line',
                source: 'route',
                layout: {
                  'line-join': 'round',
                  'line-cap': 'round'
                },
                paint: {
                  'line-color': '#888',
                  'line-width': 8
                }
              });
            })
            .catch(function(error) {
              console.error('Error fetching endpoint coordinates:', error);
            });
          }
          else{
            console.error('Invalid response Format');
          }
        })
        .catch(function(error) {
          console.error('Error fetching endpoint coordinates:', error);
        });
    } else {
      // Clear the map if no endpoint is selected
      if (map.getSource('route')) {
        map.removeLayer('route');
        map.removeSource('route');
      }
    }
  });
}

function errorLocation() {
  alert("Unable to retrieve your location.");
}

//dynamic selection option

fetch('getting_routes.php')
  .then(response => response.json())
  .then(data => {
    const routeSelect = document.getElementById('endpoint-select');

    data.forEach(route => {
      let option = document.createElement('option');
      option.value = route.id; // Assuming 'id' is the property name for route_id
      option.text = `${route.route_name} - ${route.name}`; // Assuming 'route_name' and 'name' are the property names for constraints and driver name respectively
      routeSelect.appendChild(option);
    });
  })
  .catch(error => {
    console.error("Error fetching routes", error);
  });

</script>

</body>
</html>
