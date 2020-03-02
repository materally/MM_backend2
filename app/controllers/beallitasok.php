<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method");
header("Access-Control-Allow-Credentials: true");
header("Content-Type: application/json; charset=UTF-8");

class Beallitasok extends KM_Controller {

    public function index($beallitas_key)
    {
        if($_SERVER['REQUEST_METHOD'] == 'GET' AND $_GET['API_SECRET'] == API_SECRET){
            $beallitas = BeallitasokModel::where('beallitas_key', $beallitas_key)->first();
            http_response_code(200);
            echo json_encode($beallitas);
        }else{
            http_response_code(405);
            echo json_encode(['error' => 'Bad request']);
        }
    }

}