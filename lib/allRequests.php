
<?php 
session_start();
  ob_start(); 
// Currency exchange rates START.

	/*
	 * Class CursBNR v1.02
	 * This class parses BNR's XML and returns the current exchange rate
	 *
	 * Requirements: PHP5 
	 *
	 * Last update: October 2011, 27	 
	 * More info: https://www.curs-valutar-bnr.ro
	 */
	
 	class CursBNR
 	{
 		/**
		 * xml document
		 * @var string
		 */
 		var $xmlDocument = "";
 		
 		
 		/**
		 * exchange date
		 * BNR date format is Y-m-d
		 * @var string
		 */
 		var $date = "";
 		
 		
 		/**
		 * currency
		 * @var associative array
		 */
 		var $currency = array();
 		
 		
 		/**
		 * cursBnrXML class constructor
		 *
		 * @access		public
		 * @param 		$url		string
		 * @return		void
		 */
		function __construct($url)
		{
			$this->xmlDocument = file_get_contents($url);
			$this->parseXMLDocument();
		}
 		
		/**
		 * parseXMLDocument method
		 *
		 * @access		public
		 * @return 		void
		 */
		function parseXMLDocument()
		{
	 		$xml = new SimpleXMLElement($this->xmlDocument);
	 		
	 		$this->date=$xml->Header->PublishingDate;
	 		
	 		foreach($xml->Body->Cube->Rate as $line)	
	 		{ 		 			
	 			$this->currency[]=array("name"=>$line["currency"], "value"=>$line, "multiplier"=>$line["multiplier"]);
	 		}
		}
		
		/**
		 * getCurs method
		 * 
		 * get current exchange rate: example getExchangeRate("USD")
		 * 
		 * @access		public
		 * @return 		double
		 */
		function getExchangeRate($currency)
		{
			foreach($this->currency as $line)
			{
				if($line["name"]==$currency)
				{
					return $line["value"];
				}
			}
			
			return "Incorrect currency!";
		}
 	}
 	
 	//-----------------------------------------------------------------------------------------------------------------------------
    //@an example of using the CursBNR class
     
     $curs=new CursBNR("https://www.bnr.ro/nbrfxrates.xml");

$EUR = bcdiv((float)$curs->getExchangeRate("EUR")/(float)$curs->getExchangeRate("MDL"), 1, 4);
$USD = bcdiv((float)$curs->getExchangeRate("USD")/(float)$curs->getExchangeRate("MDL"), 1, 4);


$dif_currency =  bcdiv($EUR, 1, 4)/bcdiv($USD, 1, 4);


?>

<form name="" action="" method="post" class="req_form">
	<!-- <input class="btn btn-secondary btn-sm" type="submit" name="editMarkedItem" value="Редактировать"> -->
<!-- 	<div id="buttons_request">
<a class="addrequest" id="edit_request" data-toggle="modal" data-target="#client_modal" data-client_id="">Редактировать</a>

<? if($_SESSION["role"] == 1) {?>
	<input class="btn btn-danger btn-sm" type="submit" name="deleteMarkedItems" value="Удалить"> 
    <?}?>
<input class="btn btn-secondary btn-sm" type="submit" name="pdf1" value="Заявка заказчик">
<input class="btn btn-secondary btn-sm" type="submit" name="pdf2" value="Заявка перевозчик">
</div> -->
    <? if(isset($error_delete)&&$error_delete!='') {?>
        <div class="col-md-4 mt-2 mb-2"> 
            <div class="alert alert-danger" role="alert">
            <?=$error_delete ?>
            </div> 
        </div>
    <?}?>
    <br />
    <div class="tableWrapper">
		<table class="table table-striped mt-2 interactiveTable"> 
			<tr>
				<th></th>
				<th>Дата</th>
				<th>Заказчик</th>
				<th>Перевозчик</th>
				<th>№</th>
				<th>Откуда</th>
				<th>Куда</th>
				<th>Груз</th>
				<th>А/М</th>
				<!-- <th>Вод. ФИО</th> -->
				<th>З $</th>
				<th>П $</th>
				<!-- <th>Валюта</th> -->
				<? if($_SESSION["showZP"] != 1 & $_SESSION["role"] != 1) {?> <th>Менеджер</th> <?}?>
				<th>В.Платёж</th>
				<? if($_SESSION["role"] == 1) {?>
					<th>Комиссион</th>
				<?}?>

				<? if($_SESSION["role"] == 2 & $_SESSION["showZP"] == "1") {?>
					<th>Менеджер</th>
				<?}?>	
			
					<th></th>
				
			</tr>
			<? $totalSumForAdmin = 0;

			while (($row = $result_set->fetch_assoc()) != false) { ?>
				<?

					$result_set1 = $db->getRowWhere("contractors", "id", $row[customer]);
					$row1 = $result_set1->fetch_assoc();
					
					$result_set2 = $db->getRowWhere("contractors", "id", $row[carrier]);
					$row2 = $result_set2->fetch_assoc();
					
					$result_set3 = $db->getRowWhere("users", "id", $row[idUser]);
					$row3 = $result_set3->fetch_assoc();
					
					($row[isCurrencyPayment] == 1) ? $isCurrencyPayment = "+" : $isCurrencyPayment = "-";

					if ($row[currency] == 'EURO') {
						$customerPrice = round($row[customerPrice]*$dif_currency);
						$carrierPrice = round($row[carrierPrice]*$dif_currency);
					} elseif ($row[currency] == 'USD') {
						$customerPrice = $row[customerPrice];
						$carrierPrice = $row[carrierPrice];
					}
					 
					// if($_SESSION["role"] == 1) {
						$totalSum = $customerPrice - $carrierPrice - 5;
						if($isCurrencyPayment == "+") $totalSum = $totalSum - $row[comision_static];
					// } 
					
			
						

						if ($row1[comments] == 'Дубль, на удаление') { $color = '#ff9191';} else {$color = 'transparent';}
						if ($row2[comments] == 'Дубль, на удаление') { $color2 = '#ff9191';} else {$color2 = 'transparent';}
				?>
				<tr <?php if ($row[pay_variant] == 'Наличный расчёт') {?>class="nalichka"<?php } ?>>
					<td>
						<ul class="right_menu align_left">
							<li class="add_menu">
							<img src="/images/items.png" alt="">
								<ul>
									<li><a class="addrequest" data-toggle="modal" data-target="#client_modal" data-client_id="editor/pdfInvoice.php?edit=<?=$row[id] ?>">Счет в MDL</a></li> 
									<li><a class="addrequest" data-toggle="modal" data-target="#client_modal" data-client_id="editor/pdfInvoiceDollars.php?edit=<?=$row[id] ?>">Счет в $</a></li>
									<li><a class="addrequest" data-toggle="modal" data-target="#client_modal" data-client_id="editor/pdfInvoiceEuro.php?edit=<?=$row[id] ?>">Счет в Евро</a></li>
									<li><a class="addrequest" data-toggle="modal" data-target="#client_modal" data-client_id="editor/pdfAkt.php?edit=<?=$row[id] ?>">Акт</a></li>
									<li><a class="addrequest" data-toggle="modal" data-target="#client_modal" data-client_id="editor/pdfDogovor.php?edit=<?=$row[id] ?>">Договор</a></li>
									<li><a class="addrequest" data-toggle="modal" data-target="#client_modal" data-client_id="editor/pdfPretenzia.php?edit=<?=$row[id] ?>">Срыв погрузки</a></li>
									<li><a class="addrequest" data-toggle="modal" data-target="#client_modal" data-client_id="editor/pdfPretenzia_tovar.php?edit=<?=$row[id] ?>">Порча товара</a></li>
									<li><a class="addrequest" data-toggle="modal" data-target="#client_modal" data-client_id="editor/pdfPretenzia_opozdanie.php?edit=<?=$row[id] ?>">Опоздание</a></li>
									<li><a class="addrequest" data-toggle="modal" data-target="#client_modal" data-client_id="editor/pdfRashodi.php?edit=<?=$row[id] ?>">Расходы</a></li>

								</ul>
							</li>
						</ul>
						<input type="radio" name="idItem" value="<?=$row[id] ?>"></td>
					<td><?=date("d.m.Y", $row["date"])?></td>
					<td style="background-color:<?= $color ?>";><?=$row1[name]?></td>
					<td style="background-color:<?= $color2 ?>";><?=$row2[name]?></td>
					<td><?=$row[number]?></td>
					<td><?=$row[from]?></td>
					<td><?=$row[to]?></td>
					<td><?=$row[cargo]?></td>
					<td><?=$row[carNumber]?></td>
					<!-- <td><?=$row[fio]?></td> -->
					<td><?=$customerPrice?></td>
					<td><?=$carrierPrice?></td>
					<!-- <td><?=$row[currency]?></td> -->
					<? if($_SESSION["showZP"] != 1 & $_SESSION["role"] != 1) {?> <td><?=$row3[name]?></td> <?}?>
					<td><?=$isCurrencyPayment?></td>
					<? if($_SESSION["role"] == 1) {?> <td><?=$totalSum?></td> <?}?>
					<? if($_SESSION["role"] == 2 & $_SESSION["showZP"] == "1") {?> <td><?= $row3[name] ?></td> <?}?>
					<? if($_SESSION["role"] == 1) $totalSumForAdmin += $totalSum; ?>
					<td>
						<ul class="right_menu">
							<li class="add_menu">
							<img src="/images/items.png" alt="">
								<ul>
									<li><a class="addrequest" data-toggle="modal" data-target="#client_modal" data-client_id="duplicateRequest.php?duplicate=<?=$row[id] ?>">
									Дублировать</a></li>
									<li><a class="addrequest" data-toggle="modal" data-target="#client_modal" data-client_id="editRequest.php?edit=<?=$row[id] ?>">Редактировать</a></li>
									<li><a class="addrequest" data-toggle="modal" data-target="#client_modal" data-client_id="editor/pdfCustomer.php?edit=<?=$row[id] ?>">Заявка заказчик</a></li>
									<li><a class="addrequest" data-toggle="modal" data-target="#client_modal" data-client_id="editor/pdfCarrier.php?edit=<?=$row[id] ?>">Заявка перевозчик</a></li> 
									<? if($_SESSION["role"] == 1) {?>
									<li><input class="btn btn-danger btn-sm" type="submit" name="deleteMarkedItems" value="Удалить"> </li>
									<?}?>
								</ul>
							</li>
						</ul>
				</td>
			
				</tr>
			<?}?>
	
			<? if($_SESSION["role"] == 1) {?>
				<tr>
					<td colspan="12"><b>Итого:</b></td>
					<td><b><?=number_format($totalSumForAdmin, 0, '', ' ')?></b></td>
				</tr>
			<?}?>	
		</table>
    </div>
</form>
<style>
	table.interactiveTable th, td {
    font-size: 10px;
}
</style>
<script>
	
        $('.right_menu li.add_menu').click(function () {
            $('.right_menu li.add_menu').not(this).children('ul').removeClass('active');
            $(this).children('ul').toggleClass('active');
        });
</script>


