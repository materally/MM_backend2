<?php

class Reply extends KM_Controller {
    public function index($token, $success = 0)
    {
        if(!isset($token) OR empty($token)) header("Location: https://creativesales.hu");
        // check if token isset
        $arajanlat = ArajanlatToAlvallalkozoModel::where('token', $token)->with('Arajanlat')->first();
        if(empty($arajanlat)) header("Location: https://creativesales.hu");

        $title = $arajanlat['targy'];

        $this->view('reply/index', ['title' => $title, 'arajanlat' => $arajanlat, 'success' => $success]);
    }

    public function valasz($token)
    {
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $arajanlat = ArajanlatToAlvallalkozoModel::where('token', $token)->first();
            $arajanlat->valasz = $_POST['valasz'];
            $arajanlat->valasz_datum = date('Y-m-d H:i:s');
            $arajanlat->save();
            if($arajanlat){
                header("Location: ".SITE_URL_PUBLIC."/reply/".$token."/1");
            }
        }
    }
}