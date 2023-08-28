<?php
	session_start();
	require_once "../lib/checkWasUserLoginedAndIsAdmin.php";
	require_once "../lib/db.php";
    require_once '../lib/Psr/autoloader.php';
    require_once '../lib/dompdf/lib/html5lib/Parser.php';
    require_once '../lib/dompdf/lib/php-font-lib/src/FontLib/Autoloader.php';
    require_once '../lib/dompdf/lib/php-svg-lib/src/autoload.php';
    require_once '../lib/dompdf/src/Autoloader.php';
    Dompdf\Autoloader::register();
    use Dompdf\Dompdf;

	$db = DB::getObject();

	if(isset($_GET["edit"])) {
		$id = $_GET["edit"]; 
				
		$result_set = $db->getRowWhere("requests", "id", $id);
		$row = $result_set->fetch_assoc();
		
		$result_set11 = $db->getRowWhere("contractors", "id", $row[customer]);
		$row11 = $result_set11->fetch_assoc();
		$customer = $row11[name];
		
		$result_set12 = $db->getRowWhere("contractors", "id", $row[carrier]);
		$row12 = $result_set12->fetch_assoc();
		$carrier = $row12[name];
		
		$date = date("d.m.Y", $row["date"]);
		
		$isCurrencyPayment = $row[isCurrencyPayment];
		$currency = $row[currency];
		
		$transportType = $row[transportType];
        $dateShipping = date("d.m.Y", $row[dateShipping]);
        
        $dompdf = new Dompdf();
        define("DOMPDF_ENABLE_HTML5PARSER", true);
        define("DOMPDF_ENABLE_FONTSUBSETTING", true);
        define("DOMPDF_UNICODE_ENABLED", true);
        define("DOMPDF_DPI", 240);
        define("DOMPDF_ENABLE_REMOTE", true);
        $dompdf->set_option('fontDir','../lib/dompdf/lib/fonts');
        $dompdf->set_option('defaultFont', 'arial');
        
        require_once '../partsOfPages/pdfTemplates/38p/template.php';

        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        $dompdf->stream('Contract №'.$row['number'].'.pdf'); 
    }else{
        die(); 
    }