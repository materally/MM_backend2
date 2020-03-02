<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method");
header("Access-Control-Allow-Credentials: true");
header("Content-Type: application/json; charset=UTF-8");

class Admin extends KM_Controller {
    public function index()
    {
        header("Location: https://creativesales.hu");
    }

    public function login($email, $password)
    {
        if($_SERVER['REQUEST_METHOD'] == 'POST' AND $_POST['API_SECRET'] == API_SECRET){
            // get user
            $pw = sha1($password);
            $user = UserModel::where('email', $email)->where('password', $pw)->where('scope', 'admin')->where('company_id', -1)->first();
            if($user === NULL){
                http_response_code(200);
                echo json_encode(['error' => 'A felhasználó nem létezik vagy rossz e-mail cím / jelszó páros!']);
                return;
            }
            $return = [
                'user_id'   => $user['user_id'],
                'token'     => $user['token'],
                'name'      => $user['vezeteknev'] . ' ' . $user['keresztnev'],
            ];
            http_response_code(200);
            echo json_encode($return);
        }else{
            http_response_code(405);
            echo json_encode(['error' => 'Bad request']);
        } 
    }

    public function isAdmin($user_id, $token)
    {
        if($_SERVER['REQUEST_METHOD'] == 'POST' AND $_POST['API_SECRET'] == API_SECRET){
            // get admin user
            $admin = UserModel::where('user_id', $user_id)->where('token', $token)->where('scope', 'admin')->where('company_id', -1)->first();
            if($admin === NULL){
                http_response_code(200);
                echo json_encode(['error' => 'A felhasználói fiók nem admin szintű!']);
                return;
            }
            http_response_code(200);
            echo json_encode(['success' => 'OK']);
        }else{
            http_response_code(405);
            echo json_encode(['error' => 'Bad request']);
        }
    }

    public function adminInfo($user_id)
    {
        if($_SERVER['REQUEST_METHOD'] == 'GET' AND $_GET['API_SECRET'] == API_SECRET){
            // get admin user
            $admin = UserModel::where('user_id', $user_id)->where('scope', 'admin')->where('company_id', -1)->first();
            if($admin === NULL){
                http_response_code(200);
                echo json_encode(['error' => 'A felhasználói fiók nem admin szintű!']);
                return;
            }
            http_response_code(200);
            echo json_encode($admin);
        }else{
            http_response_code(405);
            echo json_encode(['error' => 'Bad request']);
        } 
    }
}