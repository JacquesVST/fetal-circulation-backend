<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// get database connection
include_once '../config/database.php';

// instantiate score object
include_once '../objects/score.php';

$database = new Database();
$db = $database->getConnection();

$score = new Score($db);

// get posted data
$data = json_decode(file_get_contents("php://input"));

// make sure data is not empty
if (
    !empty($data->name) &&
    !empty($data->device) &&
    !empty($data->score) &&
    !empty($data->id_level)
) {

    // set score property values
    $score->name = trim($data->name);
    $score->device = $data->device;
    $score->score = $data->score;
    $score->id_level = $data->id_level;
    $score->created = date('Y-m-d H:i:s');

    // $lastScore = new Score($db);
    // $lastScore->device = $data->device;
    // $lastScore->readOneDevice();

    // if (isset($lastScore->score) && $lastScore->score >> $score->score) {
    //     // set response code - 406 not acceptable
    //     http_response_code(406);

    //     // tell the user
    //     echo json_encode(array("message" => "You have already set a better score on this device."));
    // } else {
    // create the score
    if ($score->create()) {

        // set response code - 201 created
        http_response_code(201);

        // tell the user
        echo json_encode(array("message" => "Score was sent."));
    }

    // if unable to create the product, tell the user
    else {

        // set response code - 503 service unavailable
        http_response_code(503);

        // tell the user
        echo json_encode(array("message" => "Unable to send score."));
    }
}

// tell the user data is incomplete
else {

    // set response code - 400 bad request
    http_response_code(400);

    // tell the user
    echo json_encode(array("message" => "Unable to send Score due to missing data."));
}
