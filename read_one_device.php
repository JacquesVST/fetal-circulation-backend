<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Credentials: true");
header('Content-Type: application/json');
  
// include database and object files
include_once '../config/database.php';
include_once '../objects/score.php';
  
// get database connection
$database = new Database();
$db = $database->getConnection();
  
// prepare product object
$score = new Score($db);
  
// set Device property of record to read
$score->device = isset($_GET['device']) ? $_GET['device'] : die();
  
// read the details of score to be read
$score->readOneDevice();
  
if($score->name!=null){
    // create array
    $score_arr = array(
        "id" =>  $score->id,
        "name" => $score->name,
        "device" => $score->device,
        "score" => $score->score,
        "id_level" => $score->id_level,
        "created" => $score->created
    );
  
    // set response code - 200 OK
    http_response_code(200);
  
    // make it json format
    echo json_encode($score_arr);
}
  
else{
    // set response code - 404 Not found
    http_response_code(404);
  
    // tell the user score does not exist
    echo json_encode(array("message" => "This device hasn't set any scores"));
}
