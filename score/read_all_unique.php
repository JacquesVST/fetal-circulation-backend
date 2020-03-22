<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// include database and object files
include_once '../config/database.php';
include_once '../objects/score.php';

// instantiate database and product object
$database = new Database();
$db = $database->getConnection();

// initialize object
$score = new Score($db);

// query scores
$stmt = $score->readAllUnique();
$num = $stmt->rowCount();

// check if more than 0 record found
if ($num > 0) {

    // scores array
    $scores_arr = array();
    $scores_arr["scores"] = array();

    // retrieve our table contents
    // fetch() is faster than fetchAll()
    // http://stackoverflow.com/questions/2770630/pdofetchall-vs-pdofetch-in-a-loop
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        // extract row
        // this will make $row['name'] to
        // just $name only
        extract($row);

        $score_item = array(
            "id" => $id,
            "name" => $name,
            "device" => $device,
            "score" => $score,
            "id_level" => $id_level,
            "created" => $created
        );

        array_push($scores_arr["scores"], $score_item);
    }
    
    $unique_devices_arr = [];
    $score_by_device_arr = [];
    $unique_scores_arr = [];
    foreach ($scores_arr as $value) {
        if(!in_array($value->device, $unique_devices_arr)){
            $unique_devices_arr->array_push($value->device)
        }
    }
    foreach ($unique_devices_arr as $device) {
        $cont = 0;
        $score_by_device_arr[cont] = [];
        foreach ($scores_arr as $value) {
            if ($value->device == $device){
                $score_by_device_arr[cont]->array_push($value);
            }
        }
        usort($core_by_device_arr[cont], fn($a, $b) => strcmp($a->score, $b->score));
        $cont++;
    }
    foreach ($core_by_device_arr as $device_scores) {
        $unique_scores_arr->array_push = $device_scores[1];
    }

    // set response code - 200 OK
    http_response_code(200);

    // show scores data in json format
    echo json_encode($unique_scores_arr);
} else {

    // set response code - 404 Not found
    http_response_code(404);

    // tell the user no scores found
    echo json_encode(
        array("message" => "No scores found.")
    );
}
