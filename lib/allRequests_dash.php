<?php 
session_start();
  ob_start(); 
// Currency exchange rates START.
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

$today = date("d.m.Y");

$zagr = 0;
$razgr = 0;

?>

<form name="" action="" method="post">
    <div class="tableWrapper">
		<table class="table table-striped mt-2 interactiveTable"> 

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
				?>
				<?php if ($today == $row["term"]){//РАЗГРУЖАЕТСЯ 
					$zagr++;
					if ($_SESSION[role] == 2 && $_SESSION[id] != 102){
						if ($_SESSION[id] == $row3[id]) {
					if ($zagr>='1') { ?>
				<tr><td colspan="8"><h3>СЕГОДНЯ РАЗГРУЖАЕТСЯ</h3></td></tr>	
			<tr>
				<th></th>
				<th>Заказчик</th>
				<th>Перевозчик</th>
				<th>№</th>
				<th>Дата</th>
				<th>Куда</th>
				<th>З $</th>
				<th>П $</th>
			</tr>
					<?php } ?>
					
				<tr>
					<td><input type="radio" name="idItem" value="<?=$row[id] ?>"></td>
					<td><?=$row1[name]?></td>
					<td><?=$row2[name]?></td>
					<td><?=$row[number]?></td>
					<td><?=date("d.m.Y", $row["date"])?></td>
					<td><?=$row[to]?></td>
					<td><?=$customerPrice?></td>
					<td><?=$carrierPrice?></td>
				</tr>
				<?php } 
		} elseif ($_SESSION[role] == 1 || $_SESSION[id] == 102){ ?>
			<?php if ($zagr=='1') { ?>
				<tr><td colspan="8"><h3>СЕГОДНЯ РАЗГРУЖАЕТСЯ</h3></td></tr>	
			<?php } ?>
			<tr>
					<td><input type="radio" name="idItem" value="<?=$row[id] ?>"></td>
					<td><?=$row1[name]?></td>
					<td><?=$row2[name]?></td>
					<td><?=$row[number]?></td>
					<td><?=date("d.m.Y", $row["date"])?></td>
					<td><?=$row[to]?></td>
					<td><?=$customerPrice?></td>
					<td><?=$carrierPrice?></td>
				</tr>
			<?php } ?>

				<?php } elseif ($today == date("d.m.Y", $row["dateShipping"])) { //ЗАГРУЖАЕТСЯ 
					$razgr++;
					if ($_SESSION[role] == 2 && $_SESSION[id] != 102){ 
						if ($_SESSION[id] == $row3[id]) {  
					 ?>
					<tr><td colspan="8"><h3>СЕГОДНЯ ЗАГРУЖАЕТСЯ</h3></td></tr>	
			<tr>
				<th></th>
				<th>Заказчик</th>
				<th>Перевозчик</th>
				<th>№</th>
				<th>Дата разгурзки</th>
				<th>Куда</th>
				<th>З $</th>
				<th>П $</th>
			</tr>
					<?php  ?>
				<tr>
					<td><input type="radio" name="idItem" value="<?=$row[id] ?>"></td>
					<td><?=$row1[name]?></td>
					<td><?=$row2[name]?></td>
					<td><?=$row[number]?></td>
					<td><?=date("d.m.Y", $row["dateShipping"])?></td>
					<td><?=$row[to]?></td>
					<td><?=$customerPrice?></td>
					<td><?=$carrierPrice?></td>
				</tr>
			<?php } 
		} elseif ($_SESSION[role] == 1 || $_SESSION[id] == 102){ ?>
			<?php if ($razgr == '1') { ?> 
			<tr><td colspan="8"><h3>СЕГОДНЯ ЗАГРУЖАЕТСЯ</h3></td></tr>	
		<?php } ?>
				<tr>
					<td><input type="radio" name="idItem" value="<?=$row[id] ?>"></td>
					<td><?=$row1[name]?></td>
					<td><?=$row2[name]?></td>
					<td><?=$row[number]?></td>
					<td><?=date("d.m.Y", $row["dateShipping"])?></td>
					<td><?=$row[to]?></td>
					<td><?=$customerPrice?></td>
					<td><?=$carrierPrice?></td>
				</tr>
			<?php } ?>
				<?php } ?>
			<?}?>

		</table>
    </div>
</form>
<style>
	table.interactiveTable th, td {
    font-size: 10px;
}
</style>