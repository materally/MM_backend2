<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method");
header("Access-Control-Allow-Credentials: true");
header("Content-Type: application/json; charset=UTF-8");

class Ugyfel extends KM_Controller {
    public function index($ugyfel_id = 0)
    {
        if($_SERVER['REQUEST_METHOD'] == 'GET' AND $_GET['API_SECRET'] == API_SECRET){
            // get ugyfelek
            if(empty($ugyfel_id) OR $ugyfel_id === 0){
                // get all ugyfelek
                $ugyfel = UserModel::where('scope', 'ugyfel')->with('ClientCompany')->get();
            }else{
                $ugyfel = UserModel::where('user_id', $ugyfel_id)->with('ClientCompany')->get();
            }
            http_response_code(200);
            echo json_encode($ugyfel);
        }else{
            http_response_code(405);
            echo json_encode(['error' => 'Bad request']);
        }
    }

    public function login($email, $password)
    {
        if($_SERVER['REQUEST_METHOD'] == 'POST' AND $_POST['API_SECRET'] == API_SECRET){
            // get user
            $pw = sha1($password);
            $user = UserModel::where('email', $email)->where('password', $pw)->where('scope', 'ugyfel')->where('company_id', '!=', -1)->first();
            if($user === NULL){
                http_response_code(200);
                echo json_encode(['error' => 'A felhasználó nem létezik vagy rossz e-mail cím / jelszó páros!']);
                return;
            }
            $return = [
                'user_id'   => $user['user_id'],
                'token'     => $user['token'],
                'company_id'=> $user['company_id']
            ];
            http_response_code(200);
            echo json_encode($return);
        }else{
            http_response_code(405);
            echo json_encode(['error' => 'Bad request']);
        } 
    }

    public function isUgyfel($user_id, $token)
    {
        if($_SERVER['REQUEST_METHOD'] == 'POST' AND $_POST['API_SECRET'] == API_SECRET){
            // get ügyfél user
            $ugyfel = UserModel::where('user_id', $user_id)->where('token', $token)->where('scope', 'ugyfel')->where('company_id', '!=', -1)->first();
            if($ugyfel === NULL){
                http_response_code(200);
                echo json_encode(['error' => 'A felhasználói fiók nem ügyfél szintű!']);
                return;
            }
            http_response_code(200);
            echo json_encode(['success' => 'OK']);
        }else{
            http_response_code(405);
            echo json_encode(['error' => 'Bad request']);
        }
    }

    public function ugyfeltorzs($company_id = 0)
    {
        if($_SERVER['REQUEST_METHOD'] == 'GET' AND $_GET['API_SECRET'] == API_SECRET){
            // get ugyfelek
            if(empty($company_id) OR $company_id === 0){
                // get all ugyfelek
                $company = CompanyModel::orderBy('company_id', 'desc')->with('CompanyUsers')->with('CompanyDelivery')->get();
            }else{
                $company = CompanyModel::where('company_id', $company_id)->with('CompanyUsers')->with('CompanyDelivery')->get()->first();
            }
            http_response_code(200);
            echo json_encode($company);
        }else{
            http_response_code(405);
            echo json_encode(['error' => 'Bad request']);
        }
    }

    public function create()
    {
        if($_SERVER['REQUEST_METHOD'] == 'POST' AND $_POST['API_SECRET'] == API_SECRET){
            $cegnev         = $_POST['cegnev'];
            $szamlazasi_cim = $_POST['szamlazasi_cim'];
            $adoszam        = (isset($_POST['adoszam'])) ? $_POST['adoszam'] : NULL;
            $kozponti_telefonszam = (isset($_POST['kozponti_telefonszam'])) ? $_POST['kozponti_telefonszam'] : NULL;
            $price_scope    = (isset($_POST['price_scope'])) ? $_POST['price_scope'] : 'kisker';
            if(!empty($cegnev) AND !empty($szamlazasi_cim)){
                $company = new CompanyModel;
                $company->cegnev = $cegnev;
                $company->szamlazasi_cim = $szamlazasi_cim;
                $company->adoszam = $adoszam;
                $company->kozponti_telefonszam = $kozponti_telefonszam;
                $company->price_scope = $price_scope;
                $company->save();
                if($company){
                    $company_id = $company->company_id;
                    // összeállítjuk az árjegyzéket
                    $arjegyzek = ArjegyzekModel::all();
                    foreach ($arjegyzek as $key => $a) {
                        $ca = new UgyfelArjegyzekModel;
                        $ca->ar_id = $a['ar_id'];
                        $ca->megnevezes = $a['megnevezes'];
                        $ca->mennyiseg_egysege = $a['mennyiseg_egysege'];
                        $ca->mennyiseg = $a['mennyiseg'];
                        $ca->megjegyzes = $a['megjegyzes'];
                        $ca->company_id = $company_id;
                        if($price_scope == 'kisker') $ca->eladasi_ar = $a['eladasi_netto_kisker_ar'];
                        if($price_scope == 'nagyker') $ca->eladasi_ar = $a['eladasi_netto_nagyker_ar'];
                        if($price_scope == 'vip') $ca->eladasi_ar = $a['eladasi_netto_vip_ar'];
                        $ca->save();
                    }

                    http_response_code(200);
                    echo json_encode(['success' => 'Az ügyfél létrehozva!', 'company_id' => $company->company_id]);
                }else{
                    http_response_code(200);
                    echo json_encode(['error' => 'A feltöltés nem sikerült!']);
                }
            }else{
                http_response_code(200);
                echo json_encode(['error' => 'Minden mező kitöltése kötelező!']);
            }
        }else{
            http_response_code(405);
            echo json_encode(['error' => 'Bad request']);
        }
    }

    public function deleteDeliveryAddress()
    {
        if($_SERVER['REQUEST_METHOD'] == 'POST' AND $_POST['API_SECRET'] == API_SECRET){
            $del = CompanyDeliveryModel::where('delivery_id', $_POST['delivery_id'])->where('company_id', $_POST['company_id'])->first()->delete();
            if($del){
                http_response_code(200);
                echo json_encode(['success' => 'Sikeres törlés!']);
            }else{
                http_response_code(200);
                echo json_encode(['error' => 'Nem sikerült a törlés!']);
            }
        }else{
            http_response_code(405);
            echo json_encode(['error' => 'Bad request']);
        }
        
    }

    public function createDeliveryAddress()
    {
        if($_SERVER['REQUEST_METHOD'] == 'POST' AND $_POST['API_SECRET'] == API_SECRET){
            $address     = $_POST['address'];
            $company_id  = $_POST['company_id'];
            if(!empty($address) AND !empty($company_id)){
                $company_delivery = new CompanyDeliveryModel;
                $company_delivery->address = $address;
                $company_delivery->company_id = $company_id;
                $company_delivery->save();
                if($company_delivery){
                    http_response_code(200);
                    echo json_encode(['success' => 'A kiszállítási cím hozzáadva!']);
                }else{
                    http_response_code(200);
                    echo json_encode(['error' => 'A feltöltés nem sikerült!']);
                }
            }else{
                http_response_code(200);
                echo json_encode(['error' => 'Minden mező kitöltése kötelező!']);
            }
        }else{
            http_response_code(405);
            echo json_encode(['error' => 'Bad request']);
        }
    }

    public function editDeliveryAddress($delivery_id, $company_id)
    {
        if($_SERVER['REQUEST_METHOD'] == 'POST' AND $_POST['API_SECRET'] == API_SECRET){
            $delivery_address = CompanyDeliveryModel::where('delivery_id', $delivery_id)->where('company_id', $company_id)->first();
            $delivery_address->address = $_POST['address'];
            $delivery_address->save();
            if($delivery_address){
                http_response_code(200);
                echo json_encode(['success' => 'Sikeres módosítást!']);
            }else{
                http_response_code(200);
                echo json_encode(['error' => 'A módosítás nem sikerült!']);
            }
        }else{
            http_response_code(405);
            echo json_encode(['error' => 'Bad request']);
        }
    }

    public function getCompanyUsers($company_id, $user_id = 0)
    {
        if($_SERVER['REQUEST_METHOD'] == 'GET' AND $_GET['API_SECRET'] == API_SECRET){
            // get ugyfelek
            if(empty($user_id) OR $user_id === 0){
                // get all ugyfelek
                $user = UserModel::where('company_id', $company_id)->get();
            }else{
                $user = UserModel::where('user_id', $user_id)->where('company_id', $company_id)->get()->first();
            }
            http_response_code(200);
            echo json_encode($user);
        }else{
            http_response_code(405);
            echo json_encode(['error' => 'Bad request']);
        }
    }

    public function deleteUser()
    {
        if($_SERVER['REQUEST_METHOD'] == 'POST' AND $_POST['API_SECRET'] == API_SECRET){
            $del = UserModel::where('company_id', $_POST['company_id'])->where('user_id', $_POST['user_id'])->first()->delete();
            if($del){
                http_response_code(200);
                echo json_encode(['success' => 'Sikeres törlés!']);
            }else{
                http_response_code(200);
                echo json_encode(['error' => 'Nem sikerült a törlés!']);
            }
        }else{
            http_response_code(405);
            echo json_encode(['error' => 'Bad request']);
        }
    }

    public function editCompanyUser($company_id, $user_id)
    {
        if($_SERVER['REQUEST_METHOD'] == 'POST' AND $_POST['API_SECRET'] == API_SECRET){
            $user = UserModel::where('company_id', $company_id)->where('user_id', $user_id)->first();
            $user->vezeteknev = $_POST['vezeteknev'];
            $user->keresztnev = $_POST['keresztnev'];
            $user->email = $_POST['email'];
            $user->telefonszam = $_POST['telefonszam'];
            $user->save();
            if($user){
                http_response_code(200);
                echo json_encode(['success' => 'Sikeres módosítást!']);
            }else{
                http_response_code(200);
                echo json_encode(['error' => 'A módosítás nem sikerült!']);
            }
        }else{
            http_response_code(405);
            echo json_encode(['error' => 'Bad request']);
        }
    }

    public function createUser($company_id)
    {
        if($_SERVER['REQUEST_METHOD'] == 'POST' AND $_POST['API_SECRET'] == API_SECRET){
            $email          = (isset($_POST['email'])) ? $_POST['email'] : NULL;
            $telefonszam    = (isset($_POST['telefonszam'])) ? $_POST['telefonszam'] : NULL;
            $vezeteknev     = (isset($_POST['vezeteknev'])) ? $_POST['vezeteknev'] : NULL;
            $keresztnev     = (isset($_POST['keresztnev'])) ? $_POST['keresztnev'] : NULL;

            if(!empty($email) AND !empty($keresztnev) AND !empty($vezeteknev)){
                $pw = KM_Helpers::generatePassword();
                $pw_sha = sha1($pw);
                $token = KM_Helpers::generateToken();
                
                $user = new UserModel;
                $user->company_id = $company_id;
                $user->email = $email;
                $user->telefonszam = $telefonszam;
                $user->vezeteknev = $vezeteknev;
                $user->keresztnev = $keresztnev;
                $user->token = $token;
                $user->password = $pw_sha;
                $user->scope = 'ugyfel';
                $user->save();

                if($user){
                    KM_Helpers::sendEmail($email, "Regisztráció az MM ügyfélportálra", "Szia! Az új jelszavad: ".$pw, false, false);
                    http_response_code(200);
                    echo json_encode(['success' => 'A felhasználó hozzáadva!']);
                }else{
                    http_response_code(200);
                    echo json_encode(['error' => 'A feltöltés nem sikerült!']);
                }

            }else{
                http_response_code(200);
                echo json_encode(['error' => 'A csillaggal jelölt mezők kitöltése kötelező!']);
            }
        }
    }

    public function editUserPassword($user_id)
    {
        if($_SERVER['REQUEST_METHOD'] == 'POST' AND $_POST['API_SECRET'] == API_SECRET){
            $oldpw = sha1($_POST['oldpw']);
            $newpw = sha1($_POST['newpw']);
            $user = UserModel::where('password', $oldpw)->where('user_id', $user_id)->first();
            if($user === NULL){
                http_response_code(200);
                echo json_encode(['error' => 'A felhasználó nem létezik vagy a régi jelszó nem megfelelő!']);
                return;
            }
            $user->password = $newpw;
            $user->save();
            if($user){
                http_response_code(200);
                echo json_encode(['success' => 'Sikeres módosítást!']);
            }else{
                http_response_code(200);
                echo json_encode(['error' => 'A módosítás nem sikerült!']);
            }
        }else{
            http_response_code(405);
            echo json_encode(['error' => 'Bad request']);
        }
    }

    public function lostpw($email)
    {
        if($_SERVER['REQUEST_METHOD'] == 'POST' AND $_POST['API_SECRET'] == API_SECRET){
            $user = UserModel::where('email', $email)->first();
            if($user === NULL){
                http_response_code(200);
                echo json_encode(['error' => 'A felhasználó nem létezik!']);
                return;
            }
            $pw = KM_Helpers::generatePassword();
            $pw_sha = sha1($pw);
            $user->password = $pw_sha;
            $user->save();
            if($user){
                KM_Helpers::sendEmail($email, "Elfelejtett jelszó MM ügyfélportálon", "Szia! Az új ideiglenes jelszavad: ".$pw, false, false);
                http_response_code(200);
                echo json_encode(['success' => 'Az e-mail címedre kiküldtünk egy új, ideiglenes jelszót!']);
            }else{
                http_response_code(200);
                echo json_encode(['error' => 'A módosítás nem sikerült!']);
            }
        }else{
            http_response_code(405);
            echo json_encode(['error' => 'Bad request']);
        }
    }

    public function changePriceScope()
    {
        if($_SERVER['REQUEST_METHOD'] == 'POST' AND $_POST['API_SECRET'] == API_SECRET){
            $company = CompanyModel::where('company_id', $_POST['company_id'])->first();
            if($company){
                $company->price_scope = $_POST['price_scope'];
                $company->save();
                http_response_code(200);
                echo json_encode(['success' => 'Sikeres módosítás']);
            }else{
                http_response_code(200);
                echo json_encode(['error' => 'A módosítás nem sikerült! Az ügyfél nem található!']);
            }
        }else{
            http_response_code(405);
            echo json_encode(['error' => 'Bad request']);
        }
    }

    public function deleteClient()
    {
        if($_SERVER['REQUEST_METHOD'] == 'POST' AND $_POST['API_SECRET'] == API_SECRET){

            $del1 = UserModel::where('company_id', $_POST['company_id'])->first();
            if($del1) UserModel::where('company_id', $_POST['company_id'])->first()->delete();

            $del2 = CompanyModel::where('company_id', $_POST['company_id'])->first();
            if($del2) CompanyModel::where('company_id', $_POST['company_id'])->first()->delete();

            $del3 = CompanyDeliveryModel::where('company_id', $_POST['company_id'])->first();
            if($del3) CompanyDeliveryModel::where('company_id', $_POST['company_id'])->first()->delete();

            $del4 = ArajanlatModel::where('company_id', $_POST['company_id'])->first();
            if($del4) ArajanlatModel::where('company_id', $_POST['company_id'])->first()->delete();
             
            $del5 = ArajanlatToUgyfelModel::where('company_id', $_POST['company_id'])->first();
            if($del5) ArajanlatToUgyfelModel::where('company_id', $_POST['company_id'])->first()->delete();

            $del6 = UgyfelArjegyzekModel::where('company_id', $_POST['company_id'])->first();
            if($del6) UgyfelArjegyzekModel::where('company_id', $_POST['company_id'])->first()->delete();
            
            http_response_code(200);
            echo json_encode(['success' => 'Sikeres törlés!']);
        }else{
            http_response_code(405);
            echo json_encode(['error' => 'Bad request']);
        }
    }

    public function ugyfelArjegyzek($company_id)
    {
        if($_SERVER['REQUEST_METHOD'] == 'GET' AND $_GET['API_SECRET'] == API_SECRET){
            // get ugyfelek
            if(empty($company_id) OR $company_id === 0){
                $return = ['error' => 'Missing parameter!'];
            }else{
                $return = UgyfelArjegyzekModel::where('company_id', $company_id)->get();
            }
            http_response_code(200);
            echo json_encode($return);
        }else{
            http_response_code(405);
            echo json_encode(['error' => 'Bad request']);
        }
    }

    public function changeUgyfelArjegyzek($company_id, $u_a_id)
    {
        if($_SERVER['REQUEST_METHOD'] == 'POST' AND $_POST['API_SECRET'] == API_SECRET){

            $ar = UgyfelArjegyzekModel::where('company_id', $company_id)->where('u_a_id', $u_a_id)->first();
            if($ar){
                $ar->eladasi_ar = intval($_POST['eladasi_ar']);
                $ar->save();
                http_response_code(200);
                echo json_encode(['success' => 'Sikeres módosítás!']);
            }

        }else{
            http_response_code(405);
            echo json_encode(['error' => 'Bad request']);
        }
    }

}