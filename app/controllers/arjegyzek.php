<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method");
header("Access-Control-Allow-Credentials: true");
header("Content-Type: application/json; charset=UTF-8");

class Arjegyzek extends KM_Controller {

    public function index($ar_id = 0)
    {
        if($_SERVER['REQUEST_METHOD'] == 'GET' AND $_GET['API_SECRET'] == API_SECRET){
            // get ár
            if(empty($ar_id) OR $ar_id === 0){
                // get all alvállalkozó
                $arjegyzek = ArjegyzekModel::orderBy('megnevezes', 'asc')->get();
            }else{
                $arjegyzek = ArjegyzekModel::where('ar_id', $ar_id)->first();
            }
            http_response_code(200);
            echo json_encode($arjegyzek);
        }else{
            http_response_code(405);
            echo json_encode(['error' => 'Bad request']);
        }
    }

    public function create()
    {
        if($_SERVER['REQUEST_METHOD'] == 'POST' AND $_POST['API_SECRET'] == API_SECRET){

            $megnevezes                     = (isset($_POST['megnevezes'])) ? $_POST['megnevezes'] : NULL;
            $mennyiseg_egysege              = (isset($_POST['mennyiseg_egysege'])) ? $_POST['mennyiseg_egysege'] : NULL;
            $mennyiseg                      = (isset($_POST['mennyiseg'])) ? $_POST['mennyiseg'] : NULL;
            $alapanyag_netto_bekereules_ar  = (isset($_POST['alapanyag_netto_bekereules_ar'])) ? $_POST['alapanyag_netto_bekereules_ar'] : NULL;
            $nyomtatas_netto_bekerules_ar   = (isset($_POST['nyomtatas_netto_bekerules_ar'])) ? $_POST['nyomtatas_netto_bekerules_ar'] : NULL;
            $egyeb_koltseg                  = (isset($_POST['egyeb_koltseg'])) ? $_POST['egyeb_koltseg'] : NULL;
            $bekerules_netto_ar             = (isset($_POST['bekerules_netto_ar'])) ? $_POST['bekerules_netto_ar'] : NULL;
            $megjegyzes                     = (isset($_POST['megjegyzes'])) ? $_POST['megjegyzes'] : NULL;
            $eladasi_netto_vip_ar           = (isset($_POST['eladasi_netto_vip_ar'])) ? $_POST['eladasi_netto_vip_ar'] : NULL;
            $eladasi_netto_nagyker_ar       = (isset($_POST['eladasi_netto_nagyker_ar'])) ? $_POST['eladasi_netto_nagyker_ar'] : NULL;
            $eladasi_netto_kisker_ar        = (isset($_POST['eladasi_netto_kisker_ar'])) ? $_POST['eladasi_netto_kisker_ar'] : NULL;

            if(!empty($megnevezes) AND !empty($mennyiseg_egysege)){
                $ar = new ArjegyzekModel;
                $ar->megnevezes = $megnevezes;
                $ar->mennyiseg_egysege = $mennyiseg_egysege;
                $ar->mennyiseg = $mennyiseg;
                $ar->alapanyag_netto_bekereules_ar = $alapanyag_netto_bekereules_ar;
                $ar->nyomtatas_netto_bekerules_ar = $nyomtatas_netto_bekerules_ar;
                $ar->egyeb_koltseg = $egyeb_koltseg;
                $ar->bekerules_netto_ar = $bekerules_netto_ar;
                $ar->megjegyzes = $megjegyzes;
                $ar->eladasi_netto_vip_ar = $eladasi_netto_vip_ar;
                $ar->eladasi_netto_nagyker_ar = $eladasi_netto_nagyker_ar;
                $ar->eladasi_netto_kisker_ar = $eladasi_netto_kisker_ar;
                $ar->save();
                if($ar){

                    $ar_id = $ar->ar_id;
                    $companies = CompanyModel::all();
                    foreach ($companies as $key => $c) {
                        $ar2 = new UgyfelArjegyzekModel;
                        $ar2->ar_id = $ar_id;
                        $ar2->megnevezes = $megnevezes;
                        $ar2->mennyiseg_egysege = $mennyiseg_egysege;
                        $ar2->mennyiseg = $mennyiseg;
                        $ar2->megjegyzes = $megjegyzes;
                        $ar2->company_id = $c['company_id'];
                        if($c['price_scope'] == 'vip') $ar2->eladasi_ar = $eladasi_netto_vip_ar;
                        if($c['price_scope'] == 'kisker') $ar2->eladasi_ar = $eladasi_netto_kisker_ar;
                        if($c['price_scope'] == 'nagyker') $ar2->eladasi_ar = $eladasi_netto_nagyker_ar;
                        $ar2->save();
                    }

                    http_response_code(200);
                    echo json_encode(['success' => 'Az ár létrehozva!']);
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

    public function update($ar_id)
    {
        if($_SERVER['REQUEST_METHOD'] == 'POST' AND $_POST['API_SECRET'] == API_SECRET){

            $megnevezes                     = (isset($_POST['megnevezes'])) ? $_POST['megnevezes'] : NULL;
            $mennyiseg_egysege              = (isset($_POST['mennyiseg_egysege'])) ? $_POST['mennyiseg_egysege'] : NULL;
            $mennyiseg                      = (isset($_POST['mennyiseg'])) ? $_POST['mennyiseg'] : NULL;
            $alapanyag_netto_bekereules_ar  = (isset($_POST['alapanyag_netto_bekereules_ar'])) ? $_POST['alapanyag_netto_bekereules_ar'] : NULL;
            $nyomtatas_netto_bekerules_ar   = (isset($_POST['nyomtatas_netto_bekerules_ar'])) ? $_POST['nyomtatas_netto_bekerules_ar'] : NULL;
            $egyeb_koltseg                  = (isset($_POST['egyeb_koltseg'])) ? $_POST['egyeb_koltseg'] : NULL;
            $bekerules_netto_ar             = (isset($_POST['bekerules_netto_ar'])) ? $_POST['bekerules_netto_ar'] : NULL;
            $megjegyzes                     = (isset($_POST['megjegyzes'])) ? $_POST['megjegyzes'] : NULL;
            $eladasi_netto_vip_ar           = (isset($_POST['eladasi_netto_vip_ar'])) ? $_POST['eladasi_netto_vip_ar'] : NULL;
            $eladasi_netto_nagyker_ar       = (isset($_POST['eladasi_netto_nagyker_ar'])) ? $_POST['eladasi_netto_nagyker_ar'] : NULL;
            $eladasi_netto_kisker_ar        = (isset($_POST['eladasi_netto_kisker_ar'])) ? $_POST['eladasi_netto_kisker_ar'] : NULL;

            if(!empty($megnevezes) AND !empty($mennyiseg_egysege)){
                $ar = ArjegyzekModel::where('ar_id', $ar_id)->first();
                $ar->megnevezes = $megnevezes;
                $ar->mennyiseg_egysege = $mennyiseg_egysege;
                $ar->mennyiseg = $mennyiseg;
                $ar->alapanyag_netto_bekereules_ar = $alapanyag_netto_bekereules_ar;
                $ar->nyomtatas_netto_bekerules_ar = $nyomtatas_netto_bekerules_ar;
                $ar->egyeb_koltseg = $egyeb_koltseg;
                $ar->bekerules_netto_ar = $bekerules_netto_ar;
                $ar->megjegyzes = $megjegyzes;
                $ar->eladasi_netto_vip_ar = $eladasi_netto_vip_ar;
                $ar->eladasi_netto_nagyker_ar = $eladasi_netto_nagyker_ar;
                $ar->eladasi_netto_kisker_ar = $eladasi_netto_kisker_ar;
                $ar->save();

                if($ar){

                    $arjegyzek = UgyfelArjegyzekModel::where('ar_id', $ar_id)->get();
                    $arjegyzekAr = ArjegyzekModel::where('ar_id', $ar_id)->first();
                    foreach ($arjegyzek as $key => $value) {
                        $cc = CompanyModel::where('company_id', $value['company_id'])->first();
                        $ar3 = UgyfelArjegyzekModel::where('u_a_id', $value['u_a_id'])->first();
                        if($cc['price_scope'] == 'kisker') $ar3->eladasi_ar = $arjegyzekAr['eladasi_netto_kisker_ar'];
                        if($cc['price_scope'] == 'nagyker') $ar3->eladasi_ar = $arjegyzekAr['eladasi_netto_nagyker_ar'];
                        if($cc['price_scope'] == 'vip') $ar3->eladasi_ar = $arjegyzekAr['eladasi_netto_vip_ar'];
                        $ar3->save();
                    }

                    http_response_code(200);
                    echo json_encode(['success' => 'Sikeres módosítás!']);
                }else{
                    http_response_code(200);
                    echo json_encode(['error' => 'A módosítás nem sikerült!']);
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

    public function delete($ar_id)
    {
        if($_SERVER['REQUEST_METHOD'] == 'POST' AND $_POST['API_SECRET'] == API_SECRET){
            $isset = ArjegyzekModel::where('ar_id', $ar_id)->first();
            if($isset != null){
                if(!empty($ar_id)){
                    $del = ArjegyzekModel::where('ar_id', $ar_id)->first()->delete();
                    if($del){
                        UgyfelArjegyzekModel::where('ar_id', $ar_id)->delete();
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
                echo json_encode(['error' => 'Az ár nem létezik!']);
            }
        }else{
            http_response_code(405);
            echo json_encode(['error' => 'Bad request']);
        }
    }

}