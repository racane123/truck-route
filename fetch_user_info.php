<?php

include ('dbconn.php');


$sql = "SELECT * FROM Users";

$result =mysqli_query($conn, $sql);


if ($result->num_rows > 0){
    while($row = mysqli_fetch_assoc($result)){
        $data[] = $row ;
    }

    $response = array('status' => 'success', 'data' => $data);
    echo json_encode($response);
}
else {
    echo "No data is being fetch";
}