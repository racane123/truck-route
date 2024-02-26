<?php
require('dbconn.php');

if($_SERVER['REQUEST_METHOD'] == 'GET'){
    
    $sql = "SELECT routes.route_id as id, drivers.name, routes.constraints as route_name, routes.status as status
    FROM routes
    JOIN drivers
    ON routes.driver_id = drivers.driver_id";

    $result = mysqli_query($conn, $sql);

    while($row = mysqli_fetch_assoc($result)){
        $data[]=$row;
    }

    $routes_data = json_encode($data);

    echo($routes_data);
}
else {
    http_response_code(400);
}

mysqli_close($conn);