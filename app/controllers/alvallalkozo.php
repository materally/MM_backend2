<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method");
header("Access-Control-Allow-Credentials: true");
header("Content-Type: application/json; charset=UTF-8");

class Alvallalkozo extends KM_Controller {

    public function index($av_id = 0)
    {
        if($_SERVER['REQUEST_METHOD'] == 'GET' AND $_GET['API_SECRET'] == API_SECRET){
            // get alvállalkozó
            if(empty($av_id) OR $av_id === 0){
                // get all alvállalkozó
                $av = AlvallalkozoModel::all();
            }else{
                $av = AlvallalkozoModel::where('alvallalkozo_id', $av_id)->first();
            }
            http_response_code(200);
            echo json_encode($av);
        }else{
            http_response_code(405);
            echo json_encode(['error' => 'Bad request']);
        }
    }

    public function create()
    {
        if($_SERVER['REQUEST_METHOD'] == 'POST' AND $_POST['API_SECRET'] == API_SECRET){

            $adoszam        = (isset($_POST['adoszam'])) ? $_POST['adoszam'] : NULL;
            $email          = (isset($_POST['email'])) ? $_POST['email'] : NULL;
            $vezeteknev     = (isset($_POST['vezeteknev'])) ? $_POST['vezeteknev'] : NULL;
            $keresztnev     = (isset($_POST['keresztnev'])) ? $_POST['keresztnev'] : NULL;
            $telefon        = (isset($_POST['telefon'])) ? $_POST['telefon'] : NULL;
            $cegnev         = (isset($_POST['cegnev'])) ? $_POST['cegnev'] : NULL;
            $megnevezes     = (isset($_POST['megnevezes'])) ? $_POST['megnevezes'] : NULL;
            $bankszamlaszam = (isset($_POST['bankszamlaszam'])) ? $_POST['bankszamlaszam'] : NULL;
            $megjegyzes     = (isset($_POST['megjegyzes'])) ? $_POST['megjegyzes'] : NULL;

            if(!empty($email) AND !empty($cegnev)){
                $token = KM_Helpers::generateToken();
                $av = new AlvallalkozoModel;
                $av->token = $token;
                $av->adoszam = $adoszam;
                $av->email = $email;
                $av->vezeteknev = $vezeteknev;
                $av->keresztnev = $keresztnev;
                $av->telefon = $telefon;
                $av->cegnev = $cegnev;
                $av->megnevezes = $megnevezes;
                $av->bankszamlaszam = $bankszamlaszam;
                $av->megjegyzes = $megjegyzes;
                $av->save();
                if($av){
                    http_response_code(200);
                    echo json_encode(['success' => 'Az alvállalkozó létrehozva!', 'alvallalkozo_id' => $av->alvallalkozo_id]);
                }else{
                    http_response_code(200);
                    echo json_encode(['error' => 'A feltöltés nem sikerült!']);
                }
            }else{
                http_response_code(200);
                echo json_encode(['error' => 'Csillaggal jelölt mezők kitöltése kötelező!']);
            }
        }else{
            http_response_code(405);
            echo json_encode(['error' => 'Bad request']);
        }
    }

    public function delete($av_id, $token)
    {
        if($_SERVER['REQUEST_METHOD'] == 'POST' AND $_POST['API_SECRET'] == API_SECRET){
            $isset = AlvallalkozoModel::where('alvallalkozo_id', $av_id)->where('token', $token)->first();
            if($isset != null){
                if(!empty($av_id) AND !empty($token)){
                    $del = AlvallalkozoModel::where('alvallalkozo_id', $av_id)->where('token', $token)->first()->delete();
                    if($del){
                        http_response_code(200);
                        echo json_encode(['success' => 'Sikeres törlés!']);
                    }else{
                        http_response_code(200);
                        echo json_encode(['error' => 'Nem sikerült a törlés!']);
                    }
                }else{
                    http_response_code(200);
                    echo json_encode(['error' => 'Missing parameter!']);
                }
            }else{
                http_response_code(200);
                echo json_encode(['error' => 'Az alvállalkozó nem létezik!']);
            }
        }else{
            http_response_code(405);
            echo json_encode(['error' => 'Bad request']);
        }
    }

    public function update($av_id)
    {
        if($_SERVER['REQUEST_METHOD'] == 'POST' AND $_POST['API_SECRET'] == API_SECRET){
            $av = AlvallalkozoModel::where('alvallalkozo_id', $av_id)->first();
            $av->adoszam        = $_POST['adoszam'];
            $av->email          = $_POST['email'];
            $av->vezeteknev     = $_POST['vezeteknev'];
            $av->keresztnev     = $_POST['keresztnev'];
            $av->telefon        = $_POST['telefon'];
            $av->cegnev         = $_POST['cegnev'];
            $av->megnevezes     = $_POST['megnevezes'];
            $av->bankszamlaszam = $_POST['bankszamlaszam'];
            $av->megjegyzes     = $_POST['megjegyzes'];
            $av->save();
            if($av){
                http_response_code(200);
                echo json_encode(['success' => 'Sikeres módosítás!']);
            }else{
                http_response_code(200);
                echo json_encode(['error' => 'A módosítás nem sikerült!']);
            }
        }else{
            http_response_code(405);
            echo json_encode(['error' => 'Bad request']);
        }
    }

}