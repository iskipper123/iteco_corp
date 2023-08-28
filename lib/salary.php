<?php



class Client
{
    /**
     * @var string
     */
    protected $cacheDir;

    /**
     * @param string $cacheDir
     *
     * @throws Exception\BnmException
     */
    public function __construct($cacheDir = null)
    {
        $this->cacheDir = $cacheDir;
    }

    /**
     * @param DateTime $date
     * @param string   $lang
     *
     * @return Rates
     * @throws BnmException
     */
    public function get(DateTime $date, $lang = 'en')
    {
        $lang = $this->getValidLang($lang);

        if (!$data = $this->loadFromCache($date, $lang)) {
            $data = $this->load($date, $lang);
            $this->save($date, $lang, $data);
        }

        return $this->parse($data);
    }

    /**
     * @param string $lang
     *
     * @return string
     * @throws BnmException
     */
    protected function getValidLang($lang)
    {
        $lang = strtolower($lang);

        if(!in_array($lang, array('en', 'ru', 'md'))) {
            throw new BnmException('Invalid lang');
        }

        return $lang;
    }

    /**
     * Load XML file
     *
     * @param DateTime $date
     * @param string   $lang
     *
     * @throws BnmException
     * @return string
     */
    protected function load(DateTime $date, $lang)
    {
        $data = @file_get_contents(sprintf('http://www.bnm.md/%s/official_exchange_rates?get_xml=1&date=%s', $lang, $date->format('d.m.Y')));

        if (!$data) {
            throw new BnmException('Error loading data');
        }

        return $data;
    }

    /**
     * Load XML file
     *
     * @param DateTime $date
     * @param string   $lang
     *
     * @return string|false
     */
    protected function loadFromCache(DateTime $date, $lang)
    {
        $file = $this->getCacheFileName($date, $lang);

        return file_exists($file) ? file_get_contents($file) : false;
    }

    /**
     * @param string $data
     *
     * @return Rates
     * @throws BnmException
     */
    protected function parse($data)
    {
        try {
            $xml = new SimpleXMLElement($data);
        }
        catch (\Exception $e) {
            throw new BnmException('Error loading xml', $e->getCode());
        }

        if (!isset($xml, $xml->Valute)) {
            throw new BnmException('Error parse data. Wrong xml structure');
        }

        $rates = array();
        foreach ($xml->Valute as $xmlRate) {
            $rate = new Rate($xmlRate);
            $rates[strtoupper($rate->getCharCode())] = $rate;
        }

        return new Rates($rates);
    }

    /**
     * Save data to XML File
     *
     * @param DateTime $date
     * @param string   $lang
     * @param string   $data
     *
     * @return bool
     * @throws BnmException
     */
    protected function save(dateTime $date, $lang, $data)
    {
        if (!$this->cacheDir) {
            return false;
        }

        $dir = $this->getCacheDirWithLang($lang);
        $file = $dir.'/'.$date->format('Y-m-d').'.xml';

        if (!is_dir($dir)) {
            if (!mkdir($dir, 0755, true)) {
                throw new BnmException(sprintf('Can not create cache directory %s', $dir));
            }
        }

        if (false === file_put_contents($file, $data, LOCK_EX)) {
            throw new BnmException('Error saving data');
        }

        return true;
    }

    /**
     * @param string $lang
     *
     * @return string
     */
    protected function getCacheDirWithLang($lang)
    {
        return rtrim($this->cacheDir, '/').'/'.$lang;
    }

    /**
     * @param DateTime $date
     * @param string   $lang
     *
     * @return string
     */
    protected function getCacheFileName(DateTime $date, $lang)
    {
        return $this->getCacheDirWithLang($lang).'/'.$date->format('Y-m-d').'.xml';
    }
}

class Rate
{
    /**
     * @var \SimpleXMLElement
     */
    protected $node;

    /**
     * @param \SimpleXMLElement $node
     */
    public function __construct(\SimpleXMLElement $node)
    {
        $this->node = $node;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return (int) $this->node['ID'];
    }

    /**
     * @return int
     */
    public function getNumCode()
    {
        return (int) $this->node->NumCode;
    }

    /**
     * @return string
     */
    public function getCharCode()
    {
        return (string) $this->node->CharCode;
    }

    /**
     * @return float
     */
    public function getNominal()
    {
        return (int) $this->node->Nominal;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return (string) $this->node->Name;
    }

    /**
     * @return float
     */
    public function getValue()
    {
        return (double) $this->node->Value;
    }

    /**
     * Convert MDL to current currency
     *
     * @param float $quantity
     *
     * @return float
     */
    public function exchangeTo($quantity)
    {
        $rate    = $this->getValue();
        $nominal = $this->getNominal();

        return (double) ($quantity / $nominal / $rate);
    }

    /**
     * Convert current currency to MDL
     *
     * @param float $quantity
     *
     * @return float
     */
    public function exchangeFrom($quantity)
    {
        $rate    = $this->getValue();
        $nominal = $this->getNominal();

        return (double) ($quantity * $rate / $nominal);
    }
}

class Rates extends \ArrayIterator
{
    /**
     * @var string
     */
    const CURRENCY = 'MDL';

    /**
     * Get concrete rate by currency code
     *
     * @param string $currencyCode
     *
     * @return Rate
     * @throws BnmException
     */
    public function get($currencyCode)
    {
        $currencyCode = strtoupper($currencyCode);
        if ($this->offsetExists($currencyCode)) {
            return $this->offsetGet($currencyCode);
        }

        throw new BnmException(sprintf('%s currency not found', $currencyCode));
    }

    /**
     * Converts one currency to another within current rate
     *
     * @param string $fromCurrencyCode
     * @param float  $quantity
     * @param string $toCurrencyCode
     *
     * @return float
     */
    public function exchange($fromCurrencyCode, $quantity, $toCurrencyCode)
    {
        if ($fromCurrencyCode === $toCurrencyCode) {
            return $quantity;
        }

        $fromQuantity = strtoupper($fromCurrencyCode) === static::CURRENCY ? $quantity : $this->get($fromCurrencyCode)->exchangeFrom($quantity);
        if (empty($toCurrencyCode) || strtoupper($toCurrencyCode) === static::CURRENCY) {
            return $fromQuantity;
        }

        return $this->get($toCurrencyCode)->exchangeTo($fromQuantity);
    }
}

class BnmException extends \Exception
{
}



$cacheDir = '/tmp/bnm'; // not required
$client = new Client($cacheDir);

// get rates on a specific date
$rates = $client->get(new DateTime(date('d.m.Y')));

$USD = bcdiv((float)$rates->exchange('USD', 1, 'MDL'), 1, 4);
$EUR = bcdiv((float)$rates->exchange('EUR', 1, 'MDL'), 1, 4);


function getCurs($dates, $val_usd){
    $cacheDir = '/tmp/bnm'; 
    $client = new Client($cacheDir);
$rates = $client->get(new DateTime(date('d.m.Y', $dates)));

$exchange = $rates->exchange('USD', $val_usd, 'MDL');
return $exchange;
}





        $error_plan_manager ='';
        $error_to50 =''; 
        $error_from50to100 ='';
        $error_full100 ='';
        $error_to50_v2 ='';
        $error_from50to100_v2 ='';
        $error_full100_v2 ='';


        $dif_currency =  bcdiv($EUR, 1, 4)/bcdiv($USD, 1, 4);
        ?>
        <form name="" action="" method="post">
        <?  $result_set = $db->getRowWhere("plan", "id", "1");
        $row = $result_set->fetch_assoc();

        $plan_manager = $row["plan_manager"];
        $to50 = $row["to50"];
        $from50to100 = $row["from50to100"];
        $full100 = $row["full100"];

        $to50_v2 = $row["to50_v2"];
        $from50to100_v2 = $row["from50to100_v2"];
        $full100_v2 = $row["full100_v2"];

                    if ($_SESSION["role"] == 1) {
                    if(isset($_GET["idManager"])) { 
                        $user_salary = $_GET["idManager"];
                    }
                } else {
                    $user_salary = $_SESSION["id"];
                }

                $user_plan = $db->getUserPlan($user_salary);
                $row2 = $user_plan->fetch_assoc();



        if(isset($_POST["edit"])) {
        $plan_manager = $_POST["plan_manager"];
        $to50 = $_POST["to50"];
        $from50to100 = $_POST["from50to100"];
        $full100 = $_POST["full100"];

        $to50_v2 = $_POST["to50_v2"];
        $from50to100_v2 = $_POST["from50to100_v2"];
        $full100_v2 = $_POST["full100_v2"];
        $error = false;



        if(strlen($plan_manager) == 0) {
            $error_plan_manager = "Не заполнено поле";
            $error = true;
        }       

        if(strlen($to50) == 0) {
            $error_to50 = "Не заполнено поле";
            $error = true;
        }
        if(strlen($from50to100) == 0) {
            $error_from50to100 = "Не заполнено поле";
            $error = true;
        }
         if(strlen($full100) == 0) {
            $error_full100 = "Не заполнено поле";
            $error = true;
        }


        if(strlen($to50_v2) == 0) {
            $error_to50_v2 = "Не заполнено поле";
            $error = true;
        }
        if(strlen($from50to100_v2) == 0) {
            $error_from50to100_v2 = "Не заполнено поле";
            $error = true;
        }
         if(strlen($full100_v2) == 0) {
            $error_full100_v2 = "Не заполнено поле";
            $error = true;
        }


        if(!$error) {
            $db->editPlan($plan_manager, $to50, $from50to100, $full100, $to50_v2, $from50to100_v2, $full100_v2);
            header("Location: ?success");
            exit;   
        }

    } 

    if($_SESSION["role"] == '1') {
        ?>
<div class="col-md-12 pt-3">
         <form name="" action="" method="post">
             <div class="row">
                <div class="form-group col-md-6 mr-6">
                    <label for="contactNameInput" class="required">План менеджер</label>
                    <input class="form-control<?=isset($error_plan_manager)&&$error_plan_manager!=''?' is-invalid':''?>" type="text" name="plan_manager" id="plan_manager" value="<?=isset($_POST["plan_manager"])? $_POST["plan_manager"]:$row[plan_manager]?>">
                    <div class="invalid-feedback" <?=isset($error_plan_manager)&&$error_plan_manager!=''?'style="display:block;"':''?>><?=$error_plan_manager ?></div>
                </div>
            </div>
    <h2 style="clear: both;">Вариант 1</h2>
            <div class="row">
                <div class="form-group col-md-4">
                    <label for="contactNameInput" class="required">до 50% выполнения</label>
                    <input class="form-control<?=isset($error_to50)&&$error_to50!=''?' is-invalid':''?>" type="text" name="to50" id="to50" value="<?=isset($_POST["to50"])? $_POST["to50"]:$row[to50]?>">
                    <div class="invalid-feedback" <?=isset($error_to50)&&$error_to50!=''?'style="display:block;"':''?>><?=$error_to50 ?></div>
                </div>
                <div class="form-group col-md-4">
                    <label for="contactNameInput" class="required">от 50% до 99% выполнения</label>
                    <input class="form-control<?=isset($error_from50to100)&&$error_from50to100!=''?' is-invalid':''?>" type="text" name="from50to100" id="from50to100" value="<?=isset($_POST["from50to100"])? $_POST["from50to100"]:$row[from50to100]?>">
                    <div class="invalid-feedback" <?=isset($error_from50to100)&&$error_from50to100!=''?'style="display:block;"':''?>><?=$error_from50to100 ?></div>
                </div>
                  <div class="form-group col-md-4">
                    <label for="contactNameInput" class="required">100% выполнения плана</label>
                    <input class="form-control<?=isset($error_full100)&&$error_full100!=''?' is-invalid':''?>" type="text" name="full100" id="full100" value="<?=isset($_POST["full100"])? $_POST["full100"]:$row[full100]?>">
                    <div class="invalid-feedback" <?=isset($error_full100)&&$error_full100!=''?'style="display:block;"':''?>><?=$error_full100 ?></div>
                </div>
            </div>
    <h2 style="clear: both;">Вариант 2</h2>
            <div class="row">
                <div class="form-group col-md-4">
                    <label for="contactNameInput" class="required">до 50% выполнения</label>
                    <input class="form-control<?=isset($error_to50_v2)&&$error_to50_v2!=''?' is-invalid':''?>" type="text" name="to50_v2" id="to50_v2" value="<?=isset($_POST["to50_v2"])? $_POST["to50_v2"]:$row[to50_v2]?>">
                    <div class="invalid-feedback" <?=isset($error_to50_v2)&&$error_to50_v2!=''?'style="display:block;"':''?>><?=$error_to50_v2 ?></div>
                </div>
                <div class="form-group col-md-4">
                    <label for="contactNameInput" class="required">от 50% до 99% выполнения</label>
                    <input class="form-control<?=isset($error_from50to100_v2)&&$error_from50to100!=''?' is-invalid':''?>" type="text" name="from50to100_v2" id="from50to100_v2" value="<?=isset($_POST["from50to100_v2"])? $_POST["from50to100_v2"]:$row[from50to100_v2]?>">
                    <div class="invalid-feedback" <?=isset($error_from50to100_v2)&&$error_from50to100_v2!=''?'style="display:block;"':''?>><?=$error_from50to100_v2 ?></div>
                </div>
                  <div class="form-group col-md-4">
                    <label for="contactNameInput" class="required">100% выполнения плана</label>
                    <input class="form-control<?=isset($error_full100_v2)&&$error_full100_v2!=''?' is-invalid':''?>" type="text" name="full100_v2" id="full100_v2" value="<?=isset($_POST["full100_v2"])? $_POST["full100_v2"]:$row[full100_v2]?>">
                    <div class="invalid-feedback" <?=isset($error_full100_v2)&&$error_full100_v2!=''?'style="display:block;"':''?>><?=$error_full100_v2 ?></div>
                </div>
            </div>
             <input class="btn btn-secondary btn-sm" type="submit" name="edit" value="Изменить">
        </form>
    </div>
    <?php } ?>
    <?php 

        $salary_all = round($commission_cusom_clearence,2);

                $stavka = 0;
                if ($row2['variant_pay'] == 1) {
                    $aditional_stavka = 400;
                    $to50 = $to50;
                    $from50to100 = $from50to100;
                    $full100 = $full100;
                } elseif ($row2['variant_pay'] == 2) {
                    $stavka = 400;
                    $to50 = $to50_v2;
                    $from50to100 = $from50to100_v2;
                    $full100 = $full100_v2;
                } elseif ($row2['variant_pay'] == 3) { 
                    $stavka = 200;
                    $to50 = 0;
                    $from50to100 = 0; 
                    $full100 = 0;
                    $aditional_stavka = 0;
                }

                    if ($salary_all < $plan_manager/2) {
                        $percent = $to50/100;
                    } elseif($salary_all >= $plan_manager/2 && $salary_all < $plan_manager){
                        $percent = $from50to100/100;
                    } elseif($salary_all >= $plan_manager){
                        $percent = $full100/100;
                    }

                    $plan_bar = round($salary_all*100/$plan_manager); ?>
    
    <? if(isset($error_delete)&&$error_delete!='') {?>
        <div class="col-md-4 mt-2 mb-2"> 
            <div class="alert alert-danger" role="alert">
            <?=$error_delete ?>
            </div>
        </div>
    <?}?>
    <br /> 

<div class="tableWrapper" style="min-height:800px;">


    <ul class="nav menu_tab salary_menu">
      <li class="active"><a data-toggle="tab" href="#tab_one">Текущий месяц заявки</a></li>
      <li><a data-toggle="tab" href="#tab_two">Не расстаможенные в этом месяце</a></li> 
      <li><a data-toggle="tab" href="#tab_three">Перешли с предыдущего месяца</a></li>
      <li><a data-toggle="tab" href="#tab_four">Перешли но не расстаможились</a></li>
    </ul> 
<div class="tab-content salary">
  <div id="all" class="tab-pane active">
     <div class="col-md-12 hide active" id="tab_one">
        <table  class="table table-striped mt-2 interactiveTable">
                <tr><th colspan="11" style="background:#4da598; color:#fff;text-align: center; line-height:32px;">Текущий месяц заявки</th></tr> 
                    <?php 
    while (($row5 = $getsalary_fromCustomclearence->fetch_assoc()) != false) {
        $result_set6 = $db->getRowWhere("contractors", "id", $row5[customer]);
        $row6 = $result_set6->fetch_assoc();

        if ($row5[currency] == 'EURO') {
                        $customerPrice = round($row5[customerPrice]*$dif_currency,2);
                        $carrierPrice = round($row5[carrierPrice]*$dif_currency,2);
                    } elseif ($row5[currency] == 'USD') {
                        $customerPrice = round($row5[customerPrice],2);
                        $carrierPrice = round($row5[carrierPrice],2);
                    }


        $totalSum = $customerPrice - $carrierPrice - 5;
        ($row5[isCurrencyPayment] == 1) ? $isCurrencyPayment = "+" : $isCurrencyPayment = "-";
                        if($isCurrencyPayment == "+") $totalSum = $totalSum - $row5[comision_static];

                        $result_set7 = $db->getRowWhere("customs_clearance", "number", $row5[number]);
                        $row7 = $result_set7->fetch_assoc();
                        if ($row7[deliveryNote] == 1 && $row7[date] > 0){
                            $ClearenceCursSumm = getCurs($row7[date], $totalSum*$percent);
                        } else {
                            $ClearenceCursSumm = 0;
                        }
                       if (($row5[partener] != $user_salary && $row5[partener] != '') || $row5[idUser] != $user_salary) {
                          $totalSum = $totalSum/2;
                          $ClearenceCursSumm = $ClearenceCursSumm/2;
                       }
                        
                         ?>
            <tr <?php if ($row7[deliveryNote] == 0 || $row7[date] == 0) {?> style="background: red; color:#fff;" <?php } ?> 
            <?php if (($row5[partener] != $user_salary && $row5[partener] != '') || $row5[idUser] != $user_salary) {?>  style="background: #a4a4ff;" <?php } ?>>
                <!-- <td><?=date("d.m.Y", $row3["date"])?></td> -->
                    <td><?=$row6[name]?></td>
                    <td><?=$row5[number]?></td>
                    <td><?=$row5[to]?></td>
                    <td><?=$row5[cargo]?></td>
                    <td><?=$row5[carNumber]?></td>
                    <td><?=$isCurrencyPayment?></td>
                    <td>
                    <?php if (($row5[partener] != $user_salary && $row5[partener] != '') || $row5[idUser] != $user_salary) { ?> 
                        <?php 
                            if ($user_salary == $row5[partener]) {
                               $partener_id = $row5[idUser];
                            } else {
                                $partener_id = $row5[partener];
                            }
                        $result_set10 = $db->getUserByID($partener_id);
                                $row10 = $result_set10->fetch_assoc(); ?>
                       <?php echo $row10[name]; ?>
                    <?php } ?>

                    </td>
                    <td><b><?=round($totalSum*$percent,2)?></b>
                        <?php if ($row7[deliveryNote] == 1 || $row7[date] > 0) {?>
                        <b style="float:right;"><?php  echo round($ClearenceCursSumm, 2); ?> Лей (<?php echo date('d-m-Y', $row7[date]) ?>)</b>
                    <?php } ?>
                    </td>
                    <?  $totalSumClearence += $totalSum; ?>
                    <?  $totalClearenceCursSumm += $ClearenceCursSumm; ?>
                </tr>
    <?php }  ?>
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td colspan="2"><b>Итого:</b></td>
                <td><b><?= round($totalSumClearence*$percent,2) ?></b><b style="float:right;"><?= round($totalClearenceCursSumm,2) ?> Лей</b></td></tr>
            </table>
        </div>
 <div class="col-md-12 hide" id="tab_two" >
            <table  class="table table-striped mt-2 interactiveTable">
            <tr><th colspan="11" style="background:#e74a3b; color:#fff;text-align: center; line-height:32px;">Не расстаможенные в этом месяце</th></tr>
                    <?php 
    while (($row3 = $difference_custom->fetch_assoc()) != false) { 
        $result_set1 = $db->getRowWhere("contractors", "id", $row3[customer]);
        $row1 = $result_set1->fetch_assoc();

        if ($row3[currency] == 'EURO') {
                        $customerPrice = round($row3[customerPrice]*$dif_currency,2);
                        $carrierPrice = round($row3[carrierPrice]*$dif_currency,2);
                    } elseif ($row3[currency] == 'USD') {
                        $customerPrice = round($row3[customerPrice],2);
                        $carrierPrice = round($row3[carrierPrice],2);
                    }

        
        ($row3[isCurrencyPayment] == 1) ? $isCurrencyPayment = "+" : $isCurrencyPayment = "-";
                        if($isCurrencyPayment == "+") $totalSum = $totalSum - $row3[comision_static];

                        $totalSum = $customerPrice - $carrierPrice - 5;


                        if (($row3[partener] != $user_salary && $row3[partener] != '') || $row3[idUser] != $user_salary) {
                          $totalSum = $totalSum/2;
                       }
                        ?>
            <tr <?php if (($row3[partener] != $user_salary && $row3[partener] != '') || $row3[idUser] != $user_salary) {?>  style="background: #a4a4ff;" <?php } ?>>
                    <!-- <td><?=date("d.m.Y", $row3["date"])?></td> -->
                    <td><?=$row1[name]?></td>
                    <td><?=$row3[number]?></td>
                    <td><?=$row3[to]?></td>
                    <td><?=$row3[cargo]?></td>
                    <td><?=$row3[carNumber]?></td>
                    <td><?=$isCurrencyPayment?></td>
                       <td>
                    <?php if (($row3[partener] != $user_salary && $row3[partener] != '') || $row3[idUser] != $user_salary) { ?> 
                        <?php 
                            if ($user_salary == $row3[partener]) {
                               $partener_id = $row3[idUser];
                            } else {
                                $partener_id = $row3[partener];
                            }
                        $result_set11 = $db->getUserByID($partener_id);
                                $row11 = $result_set11->fetch_assoc(); ?>
                       <?php echo $row11[name]; ?>
                    <?php } ?>

                    </td>
                    <td><b><?=round($totalSum*$percent,2)?></b></td>
                <?  $totalSumForAdmin += $totalSum; ?>
                </tr>
    <?php }  ?>
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td></td> 
                <td></td> 
                <td colspan="2"><b>Итого:</b></td>
                <td><b><?= round($totalSumForAdmin*$percent,2) ?></b></td></tr>
            </table>
        </div>
 <div class="col-md-12 hide" id="tab_three">
            <table  class="table table-striped mt-2 interactiveTable">
    <tr><th colspan="11" style="background:#e74a3b; color:#fff;text-align: center; line-height:32px;">Перешли с предыдущего месяца</th></tr> 
                    <?php 
    while (($row4 = $difference_request->fetch_assoc()) != false) {

        $result_set2 = $db->getRowWhere("contractors", "id", $row4[customer]);
        $row2 = $result_set2->fetch_assoc();

        $custom_clearence_send = $db->getRowWhere("customs_clearance", "number", $row4[number]);
        $row_send = $custom_clearence_send->fetch_assoc();



        if ($row4[currency] == 'EURO') {
                        $customerPrice = round($row4[customerPrice]*$dif_currency,2);
                        $carrierPrice = round($row4[carrierPrice]*$dif_currency,2);
                    } elseif ($row4[currency] == 'USD') {
                        $customerPrice = round($row4[customerPrice],2);
                        $carrierPrice = round($row4[carrierPrice],2);
                    }

        $totalSum = $customerPrice - $carrierPrice - 5;

    


        ($row4[isCurrencyPayment] == 1) ? $isCurrencyPayment = "+" : $isCurrencyPayment = "-";
                        if($isCurrencyPayment == "+") { $totalSum = $totalSum - $row4[comision_static];}

                        if ($row_send["deliveryNote"] == '1' && $row_send["date"] > '0') {
                            $ClearenceCursDIFF = getCurs($row_send[date], $totalSum*$percent);
                        } else {
                            $ClearenceCursDIFF = 0;
                        }


                      if (($row4[partener] != $user_salary && $row4[partener] != '') || $row4[idUser] != $user_salary) {
                          $totalSum = $totalSum/2;
                          $ClearenceCursDIFF = $ClearenceCursDIFF/2;
                       }
                         ?>
            <tr <?php if ($row_send["deliveryNote"] == '1' && $row_send["date"] > '0') { ?>style="background: rgb(133, 248, 133);"<?php } ?>
            <?php if (($row4[partener] != $user_salary && $row4[partener] != '') || $row4[idUser] != $user_salary) {?>  style="background: #a4a4ff;" <?php } ?>>
                <!-- <td><?=date("d.m.Y", $row3["date"])?></td> -->
<!--                <td><?=$row_send["deliveryNote"]?></td> -->
                    <td><?=$row2[name]?></td>
                    <td><?=$row4[number]?></td>
                    <td><?=$row4[to]?></td>
                    <td><?=$row4[cargo]?></td>
                    <td><?=$row4[carNumber]?></td>
                    <td><?=$isCurrencyPayment?></td>
                       <td>
                    <?php if (($row4[partener] != $user_salary && $row4[partener] != '') || $row4[idUser] != $user_salary) { ?> 
                        <?php 
                            if ($user_salary == $row4[partener]) {
                               $partener_id = $row4[idUser];
                            } else {
                                $partener_id = $row4[partener];
                            }
                        $result_set12 = $db->getUserByID($partener_id);
                                $row12 = $result_set12->fetch_assoc(); ?>
                       <?php echo $row12[name]; ?>
                    <?php } ?>

                    </td>
                    <td> <?php if ($row_send["deliveryNote"] == '1' && $row_send["date"] > '0') { ?><b><?=round($totalSum*$percent,2)?></b><?php } else {continue;} ?>
                        <?php if ($row_send["deliveryNote"] == '1' && $row_send["date"] > '0') { ?>
                        <b style="float:right;"><?php echo round($ClearenceCursDIFF, 2); ?> Лей (<?php echo date('d-m-Y', $row_send[date]) ?>)
                        <?php } ?>
                        </td> 
                    <?  $totalSumForAdminClearence += $totalSum; ?>
                    <?  $totalClearenceCursDIFF += $ClearenceCursDIFF; ?>
                </tr>
    <?php }  ?>
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td colspan="2"><b>Итого:</b></td>
                <td><b><?= round($totalSumForAdminClearence*$percent,2) ?></b><b style="float:right;"><?= round($totalClearenceCursDIFF,2) ?> Лей</b></td></tr>
                <td style="text-align: right;"><b>(<?= round($totalClearenceCursSumm,2) ?> Лей</b> + <b><?= round($totalClearenceCursDIFF,2) ?> Лей</b>) = <b style="float:right;"><?= round($totalClearenceCursDIFF,2)+round($totalClearenceCursSumm,2) ?> Лей</b></td></tr>
                </table>
            </div>
 <div class="col-md-12 hide" id="tab_four">
            <table  class="table table-striped mt-2 interactiveTable">
    <tr><th colspan="11" style="background:#e74a3b; color:#fff;text-align: center; line-height:32px;">Перешли с предыдущего месяца но не расстаможились</th></tr> 
                    <?php 
    while (($row8 = $difference_request_no_custom->fetch_assoc()) != false) {

        $result_set3 = $db->getRowWhere("contractors", "id", $row8[customer]);
        $row7 = $result_set3->fetch_assoc();

        if ($row8[currency] == 'EURO') {
                        $customerPrice = round($row8[customerPrice]*$dif_currency,2);
                        $carrierPrice = round($row8[carrierPrice]*$dif_currency,2);
                    } elseif ($row5[currency] == 'USD') {
                        $customerPrice = round($row8[customerPrice],2);
                        $carrierPrice = round($row8[carrierPrice],2);
                    }

        $totalSum = $customerPrice - $carrierPrice - 5;
        ($row8[isCurrencyPayment] == 1) ? $isCurrencyPayment = "+" : $isCurrencyPayment = "-";
                        if($isCurrencyPayment == "+") $totalSum = $totalSum - $row8[comision_static];



                      if (($row8[partener] != $user_salary && $row8[partener] != '') || $row8[idUser] != $user_salary) {
                          $totalSum = $totalSum/2;
                       }
                         ?>
            <tr <?php if (($row8[partener] != $user_salary && $row8[partener] != '') || $row8[idUser] != $user_salary) {?>  style="background: #a4a4ff;" <?php } ?>>
                <!-- <td><?=date("d.m.Y", $row3["date"])?></td> -->
                    <td><?=$row7[name]?></td>
                    <td><?=$row8[number]?></td>
                    <td><?=$row8[to]?></td>
                    <td><?=$row8[cargo]?></td>
                    <td><?=$row8[carNumber]?></td>
                    <td><?=$isCurrencyPayment?></td>
                       <td>
                    <?php if (($row8[partener] != $user_salary && $row8[partener] != '') || $row8[idUser] != $user_salary) { ?> 
                        <?php 
                            if ($user_salary == $row8[partener]) {
                               $partener_id = $row8[idUser];
                            } else {
                                $partener_id = $row8[partener];
                            }
                        $result_set13 = $db->getUserByID($partener_id);
                                $row13 = $result_set13->fetch_assoc(); ?>
                       <?php echo $row13[name]; ?>
                    <?php } ?>

                    </td>
                    <td><b><?=round($totalSum*$percent,2)?></b></td>
                    <?  $totalSumForAdminClearence_no_customs += $totalSum; ?> 
                </tr>
    <?php }  ?>
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td colspan="2"><b>Итого:</b></td>
                <td><b><?= round($totalSumForAdminClearence_no_customs*$percent,2) ?></b></td></tr>
        </table>
    </div>
    </div>
    </div>

    <div class="tab-content salary">
  <div id="all" class="tab-pane active" style="border-top: 5px solid;">
     <div class="col-md-12 hide active" id="tab_manager">

    <table class="table table-striped mt-2 interactiveTable">
        <tr>
    
                <th>Категория</th>
                <th>Сумма $</th>
                <th>Сумма (лей)</th>
            </tr>
                <tr><?php if ($user_salary != '110') {
                   $double_plan = 2;
                } else {
                    $double_plan = 1;
                } ?>
                    <td><h2>Выполнение плана (коммисия <?= $percent*100 ?>%  от <?= (round($totalSumClearence+$totalSumForAdminClearence))*$double_plan ?>$)</h2></td>
                    <td><b><?= round($totalSumClearence*$percent,2)+round($totalSumForAdminClearence*$percent,2) ?></b></td> 
                    <td><b><?= round($totalClearenceCursDIFF,2)+round($totalClearenceCursSumm,2) ?></b></td>
                </tr>
                </table>
<div class="progress">
  <div class="progress-bar progress-bar-striped active" role="progressbar"
  aria-valuenow="<?= $plan_bar ?>" aria-valuemin="0" aria-valuemax="100" style="width:<?= $plan_bar ?>%">
    <?= $plan_bar ?>%
  </div>
</div>
        <table class="table table-striped mt-2 interactiveTable"> 
<!-- 
            <tr>
                <th>Коммисион по заявкам</th>
                <th>Дата</th>
                <th>Категория</th>
                <th>Сумма $</th>
                <th>Сумма (лей)</th>
                <th>Комментарий</th>
            </tr>
 
            <tr>
                <td></td>
                <td></td>
                <td>Комиссион</td>
                <td><?=round($commission*$percent,2)?></td>
                <td><?=round($commission*$USD*$percent,2)?></td>
            </tr> -->
            <? $salary = round($commission,2); 
                $salary_percent=round($salary*$percent,2); 
                $salary_percent_lei=round($salary*$USD*$percent,2); 
                
                if ($salary_percent < $stavka) {
                    $salary_percent = $stavka;
                    $salary_percent_lei = $stavka*$USD;
                }
            ?>
            <tr> 
                <td colspan='3'><b>Зарплата</b></td>
                <!-- <td><b><?=$salary_percent?></b></td>
                <td><b><?=$salary_percent_lei?></b></td> -->
            </tr>
            <br>
            <br>
            <br>
            <tr>
                <th>Коммисион по расстаможкам</th>
                <th>Дата</th>
                <th>Категория</th>
                <th>Сумма $</th>
                <th>Сумма (лей)</th>
                <th>Комментарий</th>
            </tr>


            <tr>
                <td></td>
                <td></td>
                <td>Комиссион</td>
                <td><?=round(round($totalSumClearence*$percent,2)+round($totalClearenceCursDIFF,2)/$USD,2)?></td>
                <td><?= round($totalClearenceCursDIFF,2)+round($totalClearenceCursSumm,2)?></td>
                <td><?='Ставка: '.$aditional_stavka.'$ ('.$aditional_stavka*$USD.' лей)'?></td>
            </tr>
            <? $salary2 = round($commission_cusom_clearence,2); 
                $salary_customs = round($salary2*$percent,2);
                $salary_customs_lei = round($salary2*$percent*$USD,2);



                if ($salary_customs < $stavka) {
                    $salary_customs = $stavka;
                    $salary_customs_lei = $stavka*$USD;
                }
            ?> 
        
                    <tr>
                <td colspan='3'><b>Зарплата</b></td>
                <td><b><?=round(round($totalSumClearence*$percent,2)+round($totalClearenceCursDIFF,2)/$USD,2)+$aditional_stavka?></b></td>
                <td><b><?= round($totalClearenceCursDIFF,2)+round($totalClearenceCursSumm,2)+$aditional_stavka*$USD?></b></td>
            </tr>
            </table>
        </div>
             <div class="col-md-12 hide" id="tab_team">
        
    <table class="table table-striped mt-2 interactiveTable">
        <tr>
    
                <th>Категория</th>
                <th>Сумма $</th>
                <th>Сумма (лей)</th>
            </tr>
                <tr>
                    <td><h2>Выполнение плана (коммисия <?= $percent*100 ?>%  от <?= $salary_all ?>$)</h2></td>
                    <td><b><?=round($salary_all*$percent,2)?></b></td>
                    <td><b><?=round($salary_all*$USD*$percent,2)?></b></td>
                    <?php echo $salary_all; ?>
                </tr>
                </table>
<div class="progress">
  <div class="progress-bar progress-bar-striped active" role="progressbar"
  aria-valuenow="<?= $plan_bar ?>" aria-valuemin="0" aria-valuemax="100" style="width:<?= $plan_bar ?>%">
    <?= $plan_bar ?>%
  </div>
</div>

        <table class="table table-striped mt-2 interactiveTable"> 
            <tr>
                <th>Коммисион по заявкам</th>
                <th>Дата</th>
                <th>Категория</th>
                <th>Сумма $</th>
                <th>Сумма (лей)</th>
                <th>Комментарий</th>
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td>Комиссион</td>
                <td><?=round($commission*$percent,2)?></td>
                <td><?=round($commission*$USD*$percent,2)?></td>
            </tr>
            <? $salary = round($commission,2); ?>
            <tr> 
                <td colspan='3'><b>Зарплата</b></td>
                <td><b><?=round($salary*$percent,2)?></b></td>
                <td><b><?=round($salary*$USD*$percent,2)?></b></td>
            </tr>
            <br>
            <br>
            <br>
            <tr>
                <th>Коммисион по расстаможкам</th>
                <th>Дата</th>
                <th>Категория</th>
                <th>Сумма $</th>
                <th>Сумма (лей)</th>
                <th>Комментарий</th>
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td>Комиссион</td>
                <td><?=round($commission_cusom_clearence*$percent,2)?></td>
                <td><?=round($commission_cusom_clearence*$USD*$percent,2)?></td>
            </tr>
            <? $salary2 = round($commission_cusom_clearence,2); ?> 
                    <tr>
                <td colspan='3'><b>Зарплата</b></td>
                <td><b><?=round($salary2*$percent,2)?></b></td>
                <td><b><?=round($salary2*$USD*$percent,2)?></b></td>
            </tr>
            </table>
        </div>
    </div>
</div>

</form>

<script>
    // Select all tabs
$('.nav-tabs a').click(function(){
  $(this).tab('show');
})

// Select tab by name
$('.nav-tabs a[href="#home"]').tab('show')

// Select first tab
$('.nav-tabs a:first').tab('show')

// Select last tab
$('.nav-tabs a:last').tab('show')

// Select fourth tab (zero-based)
$('.nav-tabs li:eq(3) a').tab('show')
</script>