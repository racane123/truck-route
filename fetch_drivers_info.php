<?php

include ('dbconn.php');

if ($_SERVER['REQUEST_METHOD'] == 'GET') {

    $sql = "SELECT * FROM Drivers";
    $result = mysqli_query($conn, $sql);

    if ($result->num_rows > 0) {
        $data = array();
        while ($row = mysqli_fetch_assoc($result)) {
            $data[] = $row;
        }
        echo json_encode(array('status' => http_response_code(200), 'message' => 'Successfully fetch', 'data' => $data));
    } else {
        echo json_encode(array('status' => http_response_code(404), 'message' => 'No data found'));
    }
} else {
    echo json_encode(array('status' => http_response_code(400), 'message' => 'Bad request'));
    
}


?>
