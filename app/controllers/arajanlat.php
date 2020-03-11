<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method");
header("Access-Control-Allow-Credentials: true");
header("Content-Type: application/json; charset=UTF-8");

class Arajanlat extends KM_Controller {

    public function index($company_id, $user_id = 0, $arajanlat_id = 0)
    {
        if($_SERVER['REQUEST_METHOD'] == 'GET' AND $_GET['API_SECRET'] == API_SECRET){
            if(empty($user_id) OR $user_id === 0){
                // get all ugyfelek
                $arajanlat = ArajanlatModel::where('company_id', $company_id)->with('UserData')->with('ToUgyfel')->orderBy('arajanlat_id', 'desc')->get();
            }else{
                $arajanlat = ArajanlatModel::where('company_id', $company_id)->where('user_id', $user_id)->with('UserData')->orderBy('arajanlat_id', 'desc')->get();
            }
            if(!empty($arajanlat_id) AND $arajanlat_id != 0){
                $arajanlat = ArajanlatModel::where('company_id', $company_id)->where('arajanlat_id', $arajanlat_id)->with('UserData')->first();
            }
            http_response_code(200);
            echo json_encode($arajanlat);
        }else{
            http_response_code(405);
            echo json_encode(['error' => 'Bad request']);
        }
    }

    public function adminGet($arajanlat_id = 0)
    {
        if($_SERVER['REQUEST_METHOD'] == 'GET' AND $_GET['API_SECRET'] == API_SECRET){
            if($arajanlat_id === 0){
                // get all ugyfelek
                $arajanlat = ArajanlatModel::with('UserData')->with('CompanyData')->orderBy('arajanlat_id', 'desc')->get();
            }else{
                $arajanlat = ArajanlatModel::where('arajanlat_id', $arajanlat_id)->with('UserData')->with('CompanyData')->with('Comments')->first();
            }
            http_response_code(200);
            echo json_encode($arajanlat);
        }else{
            http_response_code(405);
            echo json_encode(['error' => 'Bad request']);
        }
    }

    public function create()
    {
        if($_SERVER['REQUEST_METHOD'] == 'POST' AND $_POST['API_SECRET'] == API_SECRET){

            $user_id            = (isset($_POST['user_id'])) ? $_POST['user_id'] : NULL;
            $company_id         = (isset($_POST['company_id'])) ? $_POST['company_id'] : NULL;
            $megnevezes         = (isset($_POST['megnevezes'])) ? $_POST['megnevezes'] : NULL;
            $tartalom           = (isset($_POST['tartalom'])) ? $_POST['tartalom'] : NULL;
            $gyartasi_hatarido  = (isset($_POST['gyartasi_hatarido'])) ? $_POST['gyartasi_hatarido'] : NULL;
            $datum              = date("Y-m-d");
            
            if(!empty($megnevezes) AND !empty($tartalom) AND !empty($gyartasi_hatarido)){
                $arajanlat = new ArajanlatModel;
                $arajanlat->user_id = $user_id;
                $arajanlat->company_id = $company_id;
                $arajanlat->megnevezes = $megnevezes;
                $arajanlat->tartalom = $tartalom;
                $arajanlat->gyartasi_hatarido = $gyartasi_hatarido;
                $arajanlat->datum = $datum;
                $arajanlat->save();
                if($arajanlat){
                    http_response_code(200);
                    echo json_encode(['success' => 'Az árajánlatkérés sikeres!']);
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

    public function sablon($sablon_id = 0)
    {
        if($_SERVER['REQUEST_METHOD'] == 'GET' AND $_GET['API_SECRET'] == API_SECRET){
            // get alvállalkozó
            if(empty($sablon_id) OR $sablon_id === 0){
                // get all alvállalkozó
                $sablon = ArajanlatSablonModel::all();
            }else{
                $sablon = ArajanlatSablonModel::where('sablon_id', $sablon_id)->first();
            }
            http_response_code(200);
            echo json_encode($sablon);
        }else{
            http_response_code(405);
            echo json_encode(['error' => 'Bad request']);
        }
    }

    public function createSablon()
    {
        if($_SERVER['REQUEST_METHOD'] == 'POST' AND $_POST['API_SECRET'] == API_SECRET){

            $megnevezes = (isset($_POST['megnevezes'])) ? $_POST['megnevezes'] : NULL;
            $sablon     = (isset($_POST['sablon'])) ? $_POST['sablon'] : NULL;
            
            if(!empty($megnevezes) AND !empty($sablon)){
                $s = new ArajanlatSablonModel;
                $s->megnevezes = $megnevezes;
                $s->sablon = $sablon;
                $s->save();
                if($s){
                    http_response_code(200);
                    echo json_encode(['success' => 'Az sablon létrehozva!']);
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

    public function deleteSablon($sablon_id)
    {
        if($_SERVER['REQUEST_METHOD'] == 'POST' AND $_POST['API_SECRET'] == API_SECRET){
            $isset = ArajanlatSablonModel::where('sablon_id', $sablon_id)->first();
            if($isset != null){
                if(!empty($sablon_id) ){
                    $del = ArajanlatSablonModel::where('sablon_id', $sablon_id)->first()->delete();
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
                echo json_encode(['error' => 'A komment nem létezik!']);
            }
        }else{
            http_response_code(405);
            echo json_encode(['error' => 'Bad request']);
        }
    }

    public function updateSablon($sablon_id)
    {
        if($_SERVER['REQUEST_METHOD'] == 'POST' AND $_POST['API_SECRET'] == API_SECRET){

            $megnevezes = (isset($_POST['megnevezes'])) ? $_POST['megnevezes'] : NULL;
            $sablon     = (isset($_POST['sablon'])) ? $_POST['sablon'] : NULL;
            
            if(!empty($megnevezes) AND !empty($sablon)){
                $s = ArajanlatSablonModel::where('sablon_id', $sablon_id)->first();
                $s->megnevezes = $megnevezes;
                $s->sablon = $sablon;
                $s->save();

                if($s){
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

    public function createMegjegyzes($arajanlat_id)
    {
        if($_SERVER['REQUEST_METHOD'] == 'POST' AND $_POST['API_SECRET'] == API_SECRET){

            $user_id        = (isset($_POST['user_id'])) ? $_POST['user_id'] : NULL;
            $name           = (isset($_POST['name'])) ? $_POST['name'] : NULL;
            $email          = (isset($_POST['email'])) ? $_POST['email'] : NULL;
            $comment        = (isset($_POST['comment'])) ? $_POST['comment'] : NULL;
            $date           = date("Y-m-d H:i:s");
            
            if(!empty($name) AND !empty($email) AND !empty($comment)){
                $a = new ArajanlatMegjegyzesModel;
                $a->arajanlat_id = $arajanlat_id;
                $a->user_id = $user_id;
                $a->name = $name;
                $a->email = $email;
                $a->comment = $comment;
                $a->date = $date;
                $a->save();
                if($a){
                    http_response_code(200);
                    echo json_encode(['success' => 'Sikeres!']);
                }else{
                    http_response_code(200);
                    echo json_encode(['error' => 'Nem sikerült!']);
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

    public function deleteMegjegyzes($megjegyzes_id)
    {
        if($_SERVER['REQUEST_METHOD'] == 'POST' AND $_POST['API_SECRET'] == API_SECRET){
            $isset = ArajanlatMegjegyzesModel::where('megjegyzes_id', $megjegyzes_id)->first();
            if($isset != null){
                if(!empty($megjegyzes_id) ){
                    $del = ArajanlatMegjegyzesModel::where('megjegyzes_id', $megjegyzes_id)->first()->delete();
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
                echo json_encode(['error' => 'A komment nem létezik!']);
            }
        }else{
            http_response_code(405);
            echo json_encode(['error' => 'Bad request']);
        }
    }

    public function sendEmailToAlvallalkozo()
    {
        if($_SERVER['REQUEST_METHOD'] == 'POST' AND $_POST['API_SECRET'] == API_SECRET){
            $alvallalkozo_id    = (isset($_POST['alvallalkozo_id'])) ? $_POST['alvallalkozo_id'] : NULL;
            $arajanlat_id       = (isset($_POST['arajanlat_id'])) ? $_POST['arajanlat_id'] : NULL;
            $email              = (isset($_POST['email'])) ? $_POST['email'] : NULL;
            $targy              = (isset($_POST['targy'])) ? $_POST['targy'] : NULL;
            $tartalom           = (isset($_POST['tartalom'])) ? $_POST['tartalom'] : NULL;
            $user_id            = (isset($_POST['user_id'])) ? $_POST['user_id'] : NULL;
            $datum              = date("Y-m-d H:i:s");
            $token              = KM_Helpers::generateToken(24);
            
            if(!empty($alvallalkozo_id) AND !empty($email) AND !empty($targy) AND !empty($tartalom)){
                $a = new ArajanlatToAlvallalkozoModel;
                $a->arajanlat_id = $arajanlat_id;
                $a->alvallalkozo_id = $alvallalkozo_id;
                $a->user_id = $user_id;
                $a->email = $email;
                $a->targy = $targy;
                $a->tartalom = $tartalom;
                $a->datum = $datum;
                $a->token = $token;
                $a->save();
                if($a){
                    $tartalom = nl2br($tartalom);
                    $url = SITE_URL_PUBLIC."reply/".$token;
                    $tartalom = str_replace("{url}", $url, $tartalom);
                    KM_Helpers::sendEmail($email, $targy, $tartalom, false, false);
                    http_response_code(200);
                    echo json_encode(['success' => 'Sikeres!']);
                }else{
                    http_response_code(200);
                    echo json_encode(['error' => 'Nem sikerült!']);
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

    public function arajanlatokToAlvallalkozo($arajanlat_id = 0)
    {
        if($_SERVER['REQUEST_METHOD'] == 'GET' AND $_GET['API_SECRET'] == API_SECRET){
            $arajanlat = ArajanlatToAlvallalkozoModel::where('arajanlat_id', $arajanlat_id)->with('Alvallalkozo')->with('Admin')->with('Arajanlat')->orderBy('valasz_datum', 'desc')->get();
            http_response_code(200);
            echo json_encode($arajanlat);
        }else{
            http_response_code(405);
            echo json_encode(['error' => 'Bad request']);
        }
    }

    public function arajanlatToUgyfel()
    {
        if($_SERVER['REQUEST_METHOD'] == 'POST' AND $_POST['API_SECRET'] == API_SECRET){
            $admin_user_id  = $_POST['admin_user_id'];
            $company_id     = $_POST['company_id'];
            $user_id        = $_POST['user_id'];
            $arajanlat_id   = $_POST['arajanlat_id'];
            $email          = $_POST['email'];
            $targy          = $_POST['targy'];
            $tartalom       = (isset($_POST['tartalom'])) ? $_POST['tartalom'] : '';
            $datum          = date('Y-m-d H:i:s');
            $token          = KM_Helpers::generateToken(24);
            $arjegyzek      = json_decode($_POST['arjegyzek'], true);

            $last_azonosito = BeallitasokModel::where('beallitas_key', 'pdf_counter')->first();
            $novelt = $last_azonosito['beallitas_value']+1;
            $new_azonosito = date('Y').'/'.$novelt;
            $last_azonosito->beallitas_value = $novelt;
            $last_azonosito->save();

            // create
            $ato = new ArajanlatToUgyfelModel;
            $ato->admin_user_id = $admin_user_id;
            $ato->company_id = $company_id;
            $ato->user_id = $user_id;
            $ato->arajanlat_id = $arajanlat_id;
            $ato->email = $email;
            $ato->targy = $targy;
            $ato->tartalom = $tartalom;
            $ato->datum = $datum;
            $ato->token = $token;
            $ato->azonosito = $new_azonosito;
            $ato->save();
            $ato_id = $ato->arajanlat_to_ugyfel_id;
            
            if(!empty($arjegyzek)){
                foreach ($arjegyzek as $key => $value) {
                    $ato_arjegyzek = new ArajanlatToUgyfelArjegyzekModel;
                    $ato_arjegyzek->arajanlat_to_ugyfel_id = $ato_id;
                    $ato_arjegyzek->ar_id             = $value['ar_id'];
                    $ato_arjegyzek->megnevezes        = $value['megnevezes'];
                    $ato_arjegyzek->mennyiseg         = $value['mennyiseg'];
                    $ato_arjegyzek->mennyiseg_egysege = $value['mennyiseg_egysege'];
                    $ato_arjegyzek->netto_egysegar    = $value['netto_egysegar'];
                    $ato_arjegyzek->megjegyzes        = $value['megjegyzes'];
                    $ato_arjegyzek->save();
                }
            }

            $arajanlat = ArajanlatModel::where('arajanlat_id', $arajanlat_id)->first();
            $arajanlat->feldolgozva = 1;
            $arajanlat->save();

            $arajanlat_pdf = $this->createPDF($ato_id);
            $atopdf = ArajanlatToUgyfelModel::where('arajanlat_to_ugyfel_id', $ato_id)->first();
            $atopdf->pdf = $arajanlat_pdf['url'];
            $atopdf->save();

            $keresztnev = $_POST['keresztnev'];
            $admin_nev = $_POST['admin_nev'];
            $telszam = $_POST['telszam'];
            $tartalom_email = 'Szia '.$keresztnev.',<br><br>Az árajánlatkérésedre az alábbi legkedvezőbb ajánlatot tudjuk adni:<br><a href="https://webiroda.magentamedia.hu/ugyfel/arajanlataim/'.$ato_id.'" target="_blank">Árajánlat megtekintése a webirodában</a><br><br>Ajánlatunkat letölthető, nyomtatható PDF-ben csatoltuk jelen levelünkhöz.<br>Ha kérdésed merülne fel, vagy valami nem egyértelmű írj nyugodtan vagy hívj minket.<br><br>Köszönettel és üdvözlettel<br><b>'.$admin_nev.'</b><br>MM Nyomdaipari Kft.<br><a href="tel:'.$telszam.'">'.$telszam.'</a><br><a href="https://magentamedia.hu/" target="_blank">www.magentamedia.hu</a>';

            KM_Helpers::sendEmail($email, $targy, $tartalom_email, true, false, [], $arajanlat_pdf['file']);
            
            http_response_code(200);
            echo json_encode(['success' => 'Sikeres!']);
        }else{
            http_response_code(405);
            echo json_encode(['error' => 'Bad request']);
        }
    }

    public function getArajanlatToUgyfel($id)
    {
        if($_SERVER['REQUEST_METHOD'] == 'GET' AND $_GET['API_SECRET'] == API_SECRET){
            $arajanlat = ArajanlatToUgyfelModel::where('arajanlat_id', $id)->with('User')->with('Arjegyzek')->first();
            http_response_code(200);
            echo json_encode($arajanlat);
        }else{
            http_response_code(405);
            echo json_encode(['error' => 'Bad request']);
        }
    }

    public function getArajanlatToUgyfelUgyfel($id, $company_id)
    {
        if($_SERVER['REQUEST_METHOD'] == 'GET' AND $_GET['API_SECRET'] == API_SECRET){
            $arajanlat = ArajanlatToUgyfelModel::where('arajanlat_id', $id)->where('company_id', $company_id)->with('User')->with('Arjegyzek')->with('Admin')->first();
            http_response_code(200);
            echo json_encode($arajanlat);
        }else{
            http_response_code(405);
            echo json_encode(['error' => 'Bad request']);
        }
    }

    public function createPDF($arajanlat_to_ugyfel_id)
    {
        $arajanlat = ArajanlatToUgyfelModel::where('arajanlat_to_ugyfel_id', $arajanlat_to_ugyfel_id)->with('User')->with('Arjegyzek')->with('Admin')->first();

        $datum = new DateTime($arajanlat['datum']);
        $datum = $datum->format('Y-m-d');
        $tartalom = nl2br($arajanlat['tartalom']);
        $azonosito = $arajanlat['azonosito'];

        $pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('MagentaMedia');
        $pdf->SetTitle('Árajánlat');
        $pdf->SetSubject('Árajánlat');
        $pdf->SetKeywords('magentamedia, árajánlat');
        $pdf->setPrintHeader(true);
        $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
        $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
        $pdf->SetHeaderMargin(0);
        $pdf->SetFooterMargin(0);
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
        if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
            require_once(dirname(__FILE__).'/lang/eng.php');
            $pdf->setLanguageArray($l);
        }
        $pdf->setFontSubsetting(true);
        $pdf->SetFont('freesans', '', 13, '', false);

        $pdf->AddPage();

        $tagvs_p = array('p' => array(0 => array('h' => 0, 'n' => 0), 1 => array('h' => 0, 'n' => 0)));
        $pdf->setHtmlVSpace($tagvs_p);

        $html1  = '<h2 style="text-align:center;"><u>Árajánlat</u></h2>';
        $html1 .= '<h6><span style="color:#E80D8A">Feladó:</span> '.$arajanlat['admin']['vezeteknev'].' '.$arajanlat['admin']['keresztnev'].'</h6>';
        $html1 .= '<h6><span style="color:#E80D8A">Elérhetőség:</span> '.$arajanlat['admin']['email'].' - '.$arajanlat['admin']['telefonszam'].'</h6>';
        $html1 .= '<h6><span style="color:#E80D8A">Tárgy:</span> '.$arajanlat['targy'].'</h6>';
        $html1 .= '<h6><span style="color:#E80D8A">Dátum:</span> '.$datum.'</h6>';
        $html1 .= '<h6><span style="color:#E80D8A">Azonosító:</span> '.$azonosito.'</h6>';
        $html1 .= '<hr>';
        $pdf->writeHTMLCell(0, 0, '', '', $html1, 0, 1, 0, true, '', true);
        /* -------------------------------------------------------------------- */
        $html2 = '<h6>'.$arajanlat['user']['vezeteknev'].' '.$arajanlat['user']['keresztnev'].' részére!</h6>';
        $html2 .= '<p style="font-size:13px;">'.$tartalom.'</p><br>';
        $pdf->writeHTMLCell(0, 0, '', '', $html2, 0, 1, 0, true, '', true);
        /* -------------------------------------------------------------------- */
        $html3 = '<h6>Részletes:</h6>';

        $html3 .= '<table style="font-size:12px; border-spacing: 0 5px;" cellspacing="0" cellpadding="5"><thead class="padding:10px;">';
        $html3 .= '<tr style="background-color: #eaeaea; font-weight:bold; padding:10px;">';
            $html3 .= '<th style="text-align:center; width:50px; border: 1px solid #d1d1d1">#</th>';
            $html3 .= '<th style="text-align:center; width:200px; border: 1px solid #d1d1d1">Megnevezés</th>';
            $html3 .= '<th style="text-align:center; border: 1px solid #d1d1d1">Mennyiség</th>';
            $html3 .= '<th style="text-align:center; width: 50px; border: 1px solid #d1d1d1">Me. egys.</th>';
            $html3 .= '<th style="text-align:center; border: 1px solid #d1d1d1">Nettó egységár</th>';
            $html3 .= '<th style="text-align:center; border: 1px solid #d1d1d1">Nettó ár összesen</th>';
        $html3 .= '</tr></thead><tbody style="">';

        $i = 1;
        $sum_netto = 0;
        foreach($arajanlat['arjegyzek'] as $a){
            $bg = ($i % 2 == 0 ) ? 'background-color:#efefef;' : '';
            $sum_egysegar = $a['mennyiseg']*$a['netto_egysegar'];
            $megjegyzes = (!empty($a['megjegyzes'])) ? '<br><i style="font-size:11px;">'.$a['megjegyzes'].'</i>' : '';
            $html3 .= '<tr>';
                $html3 .= '<td style="text-align:center; width:50px; border: 1px solid #d1d1d1; padding:3px; '.$bg.'">'.$i.'.</td>';
                $html3 .= '<td style="text-align:left; width:200px; border: 1px solid #d1d1d1; padding:3px; '.$bg.'">'.$a['megnevezes'].' '.$megjegyzes.'</td>';
                $html3 .= '<td style="text-align:center; border: 1px solid #d1d1d1; padding:3px; '.$bg.'">'.$a['mennyiseg'].'</td>';
                $html3 .= '<td style="text-align:center; width: 50px; border: 1px solid #d1d1d1; padding:3px; '.$bg.'">'.$a['mennyiseg_egysege'].'</td>';
                $html3 .= '<td style="text-align:right; border: 1px solid #d1d1d1; padding:3px; '.$bg.'">'.KM_Helpers::nicePrice($a['netto_egysegar']).'</td>';
                $html3 .= '<td style="text-align:right; border: 1px solid #d1d1d1; padding:3px; '.$bg.'">'.KM_Helpers::nicePrice($sum_egysegar).'</td>';
            $html3 .= '</tr>';
            $sum_netto += $sum_egysegar;
            $i++;
        }
        
        $sum_brutto = $sum_netto*1.27;
        $sum_afa    = $sum_brutto-$sum_netto;

        $html3 .= '<tfoot style="padding:10px;">';
            $html3 .= '<tr style="background-color: #eaeaea;">';
                $html3 .= '<td colspan="5" style="text-align:right; font-weight:bold; border: 1px solid #d1d1d1">Összesen nettó: </td><td style="text-align:right; color: rgb(232, 13, 138); font-weight:bold; border: 1px solid #d1d1d1">'.KM_Helpers::nicePrice($sum_netto).'</td>';
            $html3 .= '</tr>';
            $html3 .= '<tr style="background-color: #eaeaea;">';
                $html3 .= '<td colspan="5" style="text-align:right; border: 1px solid #d1d1d1">Összesen ÁFA: </td><td style="text-align:right; border: 1px solid #d1d1d1">'.KM_Helpers::nicePrice($sum_afa).'</td>';
            $html3 .= '</tr>';
            $html3 .= '<tr style="background-color: #eaeaea;">';
                $html3 .= '<td colspan="5" style="text-align:right; border: 1px solid #d1d1d1">Összesen bruttó: </td><td style="text-align:right; border: 1px solid #d1d1d1">'.KM_Helpers::nicePrice($sum_brutto).'</td>';
            $html3 .= '</tr>';
        
        $html3 .= '</tfoot></tbody></table>';
        $pdf->writeHTMLCell(0, 0, '', '', $html3, 0, 1, 0, true, '', true);
        /* -------------------------------------------------------------------- */
        $html4 = '<br><hr><h6>Üdvözlettel:</h6>';
        $html4 .= '<p style="font-weight:bold; font-size:13px;">'.$arajanlat['admin']['vezeteknev'].' '.$arajanlat['admin']['keresztnev'].'</p>';
        $html4 .= '<p style="font-size:13px; color: rgb(232, 13, 138); font-weight:bold;">MM Nyomdaipari Kft.</p>';
        $html4 .= '<p style="font-size:13px;">'.$arajanlat['admin']['telefonszam'].'</p>';
        $html4 .= '<p style="font-size:13px;"><a href="https://magentamedia.hu/" target="_blank">www.magentamedia.hu</a></p>';
        $pdf->writeHTMLCell(0, 0, '', '', $html4, 0, 1, 0, true, '', true);
        /* -------------------------------------------------------------------- */

        $filename = 'magentamedia_'.date('Y').'_'.substr($arajanlat['azonosito'],-4).'_'.substr($arajanlat['token'], 0, 8);
        
        ob_end_clean();
        $pdf->Output(getcwd().'/pdf/'.$filename.'.pdf', 'F');
        //$pdf->Output('asd.pdf', 'I');
        // F a create

        $return = [
            'url' => SITE_URL_PUBLIC.'pdf/'.$filename.'.pdf',
            'file' => $filename.'.pdf'
        ];
        return $return;
    }

    public function createPDFUj($uj_arajanlat_id)
    {
        $arajanlat = UjArajanlatModel::where('uj_arajanlat_id', $uj_arajanlat_id)->with('User')->with('Arjegyzek')->with('Admin')->first();

        $datum = new DateTime($arajanlat['datum']);
        $datum = $datum->format('Y-m-d');
        $tartalom = nl2br($arajanlat['tartalom']);
        $azonosito = $arajanlat['azonosito'];

        $pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('MagentaMedia');
        $pdf->SetTitle('Árajánlat');
        $pdf->SetSubject('Árajánlat');
        $pdf->SetKeywords('magentamedia, árajánlat');
        $pdf->setPrintHeader(true);
        $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
        $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
        $pdf->SetHeaderMargin(0);
        $pdf->SetFooterMargin(0);
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
        if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
            require_once(dirname(__FILE__).'/lang/eng.php');
            $pdf->setLanguageArray($l);
        }
        $pdf->setFontSubsetting(true);
        $pdf->SetFont('freesans', '', 13, '', false);

        $pdf->AddPage();

        $tagvs_p = array('p' => array(0 => array('h' => 0, 'n' => 0), 1 => array('h' => 0, 'n' => 0)));
        $pdf->setHtmlVSpace($tagvs_p);

        $html1  = '<h2 style="text-align:center;"><u>Árajánlat</u></h2>';
        $html1 .= '<h6><span style="color:#E80D8A">Feladó:</span> '.$arajanlat['admin']['vezeteknev'].' '.$arajanlat['admin']['keresztnev'].'</h6>';
        $html1 .= '<h6><span style="color:#E80D8A">Elérhetőség:</span> '.$arajanlat['admin']['email'].' - '.$arajanlat['admin']['telefonszam'].'</h6>';
        $html1 .= '<h6><span style="color:#E80D8A">Megnevezés:</span> '.$arajanlat['megnevezes'].'</h6>';
        $html1 .= '<h6><span style="color:#E80D8A">Dátum:</span> '.$datum.'</h6>';
        $html1 .= '<h6><span style="color:#E80D8A">Azonosító:</span> '.$azonosito.'</h6>';
        $html1 .= '<hr>';
        $pdf->writeHTMLCell(0, 0, '', '', $html1, 0, 1, 0, true, '', true);
        /* -------------------------------------------------------------------- */
        $html2 = '<h6>'.$arajanlat['user']['vezeteknev'].' '.$arajanlat['user']['keresztnev'].' részére!</h6>';
        $html2 .= '<p style="font-size:13px;">'.$tartalom.'</p><br>';
        $pdf->writeHTMLCell(0, 0, '', '', $html2, 0, 1, 0, true, '', true);
        /* -------------------------------------------------------------------- */
        $html3 = '<h6>Részletes:</h6>';

        $html3 .= '<table style="font-size:12px; border-spacing: 0 5px;" cellspacing="0" cellpadding="5"><thead class="padding:10px;">';
        $html3 .= '<tr style="background-color: #eaeaea; font-weight:bold; padding:10px;">';
            $html3 .= '<th style="text-align:center; width:50px; border: 1px solid #d1d1d1">#</th>';
            $html3 .= '<th style="text-align:center; width:200px; border: 1px solid #d1d1d1">Megnevezés</th>';
            $html3 .= '<th style="text-align:center; border: 1px solid #d1d1d1">Mennyiség</th>';
            $html3 .= '<th style="text-align:center; width: 50px; border: 1px solid #d1d1d1">Me. egys.</th>';
            $html3 .= '<th style="text-align:center; border: 1px solid #d1d1d1">Nettó egységár</th>';
            $html3 .= '<th style="text-align:center; border: 1px solid #d1d1d1">Nettó ár összesen</th>';
        $html3 .= '</tr></thead><tbody style="">';

        $i = 1;
        $sum_netto = 0;
        foreach($arajanlat['arjegyzek'] as $a){
            $bg = ($i % 2 == 0 ) ? 'background-color:#efefef;' : '';
            $sum_egysegar = $a['mennyiseg']*$a['netto_egysegar'];
            $megjegyzes = (!empty($a['megjegyzes'])) ? '<br><i style="font-size:11px;">'.$a['megjegyzes'].'</i>' : '';
            $html3 .= '<tr>';
                $html3 .= '<td style="text-align:center; width:50px; border: 1px solid #d1d1d1; padding:3px; '.$bg.'">'.$i.'.</td>';
                $html3 .= '<td style="text-align:left; width:200px; border: 1px solid #d1d1d1; padding:3px; '.$bg.'">'.$a['megnevezes'].' '.$megjegyzes.'</td>';
                $html3 .= '<td style="text-align:center; border: 1px solid #d1d1d1; padding:3px; '.$bg.'">'.$a['mennyiseg'].'</td>';
                $html3 .= '<td style="text-align:center; width: 50px; border: 1px solid #d1d1d1; padding:3px; '.$bg.'">'.$a['mennyiseg_egysege'].'</td>';
                $html3 .= '<td style="text-align:right; border: 1px solid #d1d1d1; padding:3px; '.$bg.'">'.KM_Helpers::nicePrice($a['netto_egysegar']).'</td>';
                $html3 .= '<td style="text-align:right; border: 1px solid #d1d1d1; padding:3px; '.$bg.'">'.KM_Helpers::nicePrice($sum_egysegar).'</td>';
            $html3 .= '</tr>';
            $sum_netto += $sum_egysegar;
            $i++;
        }
        
        $sum_brutto = $sum_netto*1.27;
        $sum_afa    = $sum_brutto-$sum_netto;

        $html3 .= '<tfoot style="padding:10px;">';
            $html3 .= '<tr style="background-color: #eaeaea;">';
                $html3 .= '<td colspan="5" style="text-align:right; font-weight:bold; border: 1px solid #d1d1d1">Összesen nettó: </td><td style="text-align:right; color: rgb(232, 13, 138); font-weight:bold; border: 1px solid #d1d1d1">'.KM_Helpers::nicePrice($sum_netto).'</td>';
            $html3 .= '</tr>';
            $html3 .= '<tr style="background-color: #eaeaea;">';
                $html3 .= '<td colspan="5" style="text-align:right; border: 1px solid #d1d1d1">Összesen ÁFA: </td><td style="text-align:right; border: 1px solid #d1d1d1">'.KM_Helpers::nicePrice($sum_afa).'</td>';
            $html3 .= '</tr>';
            $html3 .= '<tr style="background-color: #eaeaea;">';
                $html3 .= '<td colspan="5" style="text-align:right; border: 1px solid #d1d1d1">Összesen bruttó: </td><td style="text-align:right; border: 1px solid #d1d1d1">'.KM_Helpers::nicePrice($sum_brutto).'</td>';
            $html3 .= '</tr>';
        
        $html3 .= '</tfoot></tbody></table>';
        $pdf->writeHTMLCell(0, 0, '', '', $html3, 0, 1, 0, true, '', true);
        /* -------------------------------------------------------------------- */
        $html4 = '<br><hr><h6>Üdvözlettel:</h6>';
        $html4 .= '<p style="font-weight:bold; font-size:13px;">'.$arajanlat['admin']['vezeteknev'].' '.$arajanlat['admin']['keresztnev'].'</p>';
        $html4 .= '<p style="font-size:13px; color: rgb(232, 13, 138); font-weight:bold;">MM Nyomdaipari Kft.</p>';
        $html4 .= '<p style="font-size:13px;">'.$arajanlat['admin']['telefonszam'].'</p>';
        $html4 .= '<p style="font-size:13px;"><a href="https://magentamedia.hu/" target="_blank">www.magentamedia.hu</a></p>';
        $pdf->writeHTMLCell(0, 0, '', '', $html4, 0, 1, 0, true, '', true);
        /* -------------------------------------------------------------------- */

        $filename = 'magentamedia_'.date('Y').'_'.substr($arajanlat['azonosito'],-4).'_'.substr($arajanlat['token'], 0, 8);
        
        ob_end_clean();
        $pdf->Output(getcwd().'/pdf/'.$filename.'.pdf', 'F');
        //$pdf->Output('asd.pdf', 'I');
        // F a create

        $return = [
            'url' => SITE_URL_PUBLIC.'pdf/'.$filename.'.pdf',
            'file' => $filename.'.pdf'
        ];
        return $return;
    }

    public function ujArajanlat()
    {
        if($_SERVER['REQUEST_METHOD'] == 'POST' AND $_POST['API_SECRET'] == API_SECRET){

            $admin_user_id          = $_POST['admin_user_id'];
            $company_id             = $_POST['company_id'];
            $user_id                = $_POST['user_id'];
            $felado_email           = $_POST['felado_email'];
            $felado_telefon         = $_POST['felado_telefon'];
            $felado_nev             = $_POST['felado_nev'];
            $cimzett_email          = $_POST['cimzett_email'];
            $cimzett_telefonszam    = $_POST['cimzett_telefonszam'];
            $cimzett_nev            = $_POST['cimzett_nev'];
            $megnevezes             = $_POST['megnevezes'];
            $tartalom               = (isset($_POST['tartalom'])) ? $_POST['tartalom'] : '';
            $datum                  = date('Y-m-d H:i:s');
            $token                  = KM_Helpers::generateToken(24);
            $arjegyzek              = json_decode($_POST['arjegyzek'], true);

            $last_azonosito = BeallitasokModel::where('beallitas_key', 'pdf_counter')->first();
            $novelt = $last_azonosito['beallitas_value']+1;
            $new_azonosito = date('Y').'/'.$novelt;
            $last_azonosito->beallitas_value = $novelt;
            $last_azonosito->save();

            // create
            $ato = new UjArajanlatModel;
            $ato->admin_user_id = $admin_user_id;
            $ato->company_id = $company_id;
            $ato->user_id = $user_id;
            $ato->felado_email = $felado_email;
            $ato->felado_telefon = $felado_telefon;
            $ato->felado_nev = $felado_nev;
            $ato->cimzett_email = $cimzett_email;
            $ato->cimzett_telefonszam = $cimzett_telefonszam;
            $ato->cimzett_nev = $cimzett_nev;
            $ato->megnevezes = $megnevezes;
            $ato->tartalom = $tartalom;
            $ato->datum = $datum;
            $ato->token = $token;
            $ato->azonosito = $new_azonosito;
            $ato->save();
            $ato_id = $ato->uj_arajanlat_id;
            
            if(!empty($arjegyzek)){
                foreach ($arjegyzek as $key => $value) {
                    $ato_arjegyzek = new UjArajanlatArjegyzekModel;
                    $ato_arjegyzek->uj_arajanlat_id     = $ato_id;
                    $ato_arjegyzek->ar_id             = $value['ar_id'];
                    $ato_arjegyzek->megnevezes        = $value['megnevezes'];
                    $ato_arjegyzek->mennyiseg         = $value['mennyiseg'];
                    $ato_arjegyzek->mennyiseg_egysege = $value['mennyiseg_egysege'];
                    $ato_arjegyzek->netto_egysegar    = $value['netto_egysegar'];
                    $ato_arjegyzek->megjegyzes        = $value['megjegyzes'];
                    $ato_arjegyzek->save();
                }
            }

            $arajanlat_pdf = $this->createPDFUj($ato_id);
            $atopdf = UjArajanlatModel::where('uj_arajanlat_id', $ato_id)->first();
            $atopdf->pdf = $arajanlat_pdf['url'];
            $atopdf->save();

            $tartalom_email = 'Szia '.$cimzett_nev.',<br><br>Az alábbi legkedvezőbb ajánlatot tudjuk adni:<br><a href="https://webiroda.magentamedia.hu/ugyfel/uj_arajanlataim/'.$ato_id.'" target="_blank">Árajánlat megtekintése a webirodában</a><br><br>Ajánlatunkat letölthető, nyomtatható PDF-ben csatoltuk jelen levelünkhöz.<br>Ha kérdésed merülne fel, vagy valami nem egyértelmű írj nyugodtan vagy hívj minket.<br><br>Köszönettel és üdvözlettel<br><b>'.$felado_nev.'</b><br>MM Nyomdaipari Kft.<br><a href="tel:'.$felado_telefon.'">'.$felado_telefon.'</a><br><a href="https://magentamedia.hu/" target="_blank">www.magentamedia.hu</a>';

            KM_Helpers::sendEmail($cimzett_email, $megnevezes, $tartalom_email, true, false, [], $arajanlat_pdf['file']);
            
            http_response_code(200);
            echo json_encode(['success' => 'Sikeres!']);

        }
    }

    public function ujArajanlatok($uj_arajanlat_id = 0)
    {
        if($_SERVER['REQUEST_METHOD'] == 'GET' AND $_GET['API_SECRET'] == API_SECRET){
            if($uj_arajanlat_id === 0){
                // get all
                $arajanlat = UjArajanlatModel::with('Company')->orderBy('uj_arajanlat_id', 'desc')->get();
            }else{
                $arajanlat = UjArajanlatModel::where('uj_arajanlat_id', $uj_arajanlat_id)->with('User')->with('Company')->with('Arjegyzek')->first();
            }
            http_response_code(200);
            echo json_encode($arajanlat);
        }else{
            http_response_code(405);
            echo json_encode(['error' => 'Bad request']);
        }
    }

    public function getUjArajanlatok($company_id, $uj_arajanlat_id = 0)
    {
        if($_SERVER['REQUEST_METHOD'] == 'GET' AND $_GET['API_SECRET'] == API_SECRET){
            if($uj_arajanlat_id === 0){
                // get all
                $arajanlat = UjArajanlatModel::with('Company')->orderBy('uj_arajanlat_id', 'desc')->get();
            }else{
                $arajanlat = UjArajanlatModel::where('company_id', $company_id)->where('uj_arajanlat_id', $uj_arajanlat_id)->with('User')->with('Company')->with('Arjegyzek')->first();
            }
            http_response_code(200);
            echo json_encode($arajanlat);
        }else{
            http_response_code(405);
            echo json_encode(['error' => 'Bad request']);
        }
    }

}

class MYPDF extends TCPDF {
    public function Header() {
        $image_file = SITE_URL_PUBLIC.'assets/img/pdf_logo.png';
        $this->Image($image_file, 10, 10, 50, '', 'PNG', '', 'T', false, 300, '', false, false, 0, false, false, false);
        $this->SetFont('helvetica', 'B', 10);
        // Title
    }
}