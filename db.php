<?php
	require_once "vars.php";
	


	class DB {
		private $db;
		private static $user = null;
		
		private function __construct() {
			$this->db = new mysqli("localhost", "root", "", "itecomd_crmtest"); 
			$this->db->query("SET NAMES 'utf8'");
		}
		
		public static function getObject() {
			if(self::$user === null) self::$user = new DB();
			return self::$user;
		}
		
		public function checkUser($login, $password) {
			$result_set = $this->db->query("SELECT `password` FROM `users` WHERE `login`='$login'");
			$user = $result_set->fetch_assoc();
			if(!$user) return false;
			return $user["password"] === $password;
		}
		
		public function isAuth() {
			$login = $_SESSION["login"];
			$password = $_SESSION["password"];
			return $this->checkUser($login, $password);
		}
		
		public function login($login, $password) { 
			$password = md5($password);
			if($this->checkUser($login, $password)) {
				$_SESSION["login"] = $login;
				$_SESSION["password"] = $password;
				setcookie("login", $login);
				setcookie("password", $password);
				return true;
			}else{
                return false;
            }
		}


		public function getTransoprtDep($id) {
			$result_set = $this->db->query("SELECT `department` FROM `users` WHERE `id`='$id'");
			return $result_set;
		}


		public function getTransoprtUsers($department) {
			$result_set = $this->db->query("SELECT `id` FROM `users` WHERE `department`='$department' AND `manager_variant` = 'transport'");
			return $result_set;
		}

		public function getTransoprtUsers_requests($id) {
			$result_set = $this->db->query("SELECT `idUser`, COUNT(id) as `count` FROM `get_requests` WHERE `idUserTransport` = '$id' ORDER BY COUNT(id)");
			$ids = $result_set->fetch_assoc();
			return $ids;
		}

		public function getDepUsers($id) {
			$result_set = $this->db->query("SELECT `name` FROM `users` WHERE `department`='$id' ORDER by name");
			return $result_set;
		}


		
		public function getRole($login) {
			$result_set = $this->db->query("SELECT `rights` FROM `users` WHERE `login`='$login'");
			$role = $result_set->fetch_assoc();
			return $role["rights"];
		}
		public function getvariant_manager($id) {
			$result_set = $this->db->query("SELECT `manager_variant` FROM `users` WHERE `id`='$id'");
			$role = $result_set->fetch_assoc();
			return $role["manager_variant"];
		} 
		public function getdepartment_name($id) {
			$result_set = $this->db->query("SELECT `usergroup_name` FROM `user_group` WHERE `id`='$id'");
			$dep_name = $result_set->fetch_assoc();
			return $dep_name["usergroup_name"];
		} 

		public function checkLogin($login) {
			$result_set = $this->db->query("SELECT `password` FROM `users` WHERE `login`='$login'");
			return $result_set;
		}
		
		public function getUserByLogin($login){
			$result_set = $this->db->query("SELECT * FROM `users` WHERE `login`='$login'");
			return $result_set;
		}

		public function getUserByID($id){
			$result_set = $this->db->query("SELECT * FROM `users` WHERE `id`='$id'");
			return $result_set;
		}

		public function getUserPlan($id){
			$result_set = $this->db->query("SELECT `variant_pay` FROM `users` WHERE `id`='$id'");
			return $result_set;
		}
		// public function getAllOrder($table, $order) {
			// $result_set = $this->db->query("SELECT * FROM `$table` ORDER by $order");
			// return $result_set;
		// }
		
		public function editUser($id, $login, $name, $variant_pay, $manager_variant, $department) {
			$this->db->query("UPDATE `users` SET `login`='$login', `name`='$name', `variant_pay`='$variant_pay', `manager_variant`='$manager_variant', `department`='$department' WHERE `id` = '$id'");
		}
		public function editUserGroup($id, $usergroup_name) {
			$this->db->query("UPDATE `user_group` SET `usergroup_name`='$usergroup_name' WHERE `id` = '$id'");
		}

		public function editEndDate($id, $endDate) {
			$this->db->query("UPDATE `payments` SET `dateEnd`='$endDate' WHERE `id` = '$id'");
		}

		public function editPayment($id, $customer, $number, $date, $days, $paymentWasDidAlreary/*, $status*/) {
			$this->db->query("UPDATE `payments` SET `customer`='$customer', `number`='$number', `date`='$date', `days`='$days', `paymentWasDidAlreary`='$paymentWasDidAlreary'/*, `status`='$status'*/ WHERE `id` = '$id'");
		}
		
		public function changePaymentStatus($id) {
			$this->db->query("UPDATE `payments` SET `paymentWasDidAlreary`='1' WHERE `id` = '$id'");
		}
				
		public function changeGetRequestStatus($id) {
			$this->db->query("UPDATE `get_requests` SET `status`='1' WHERE `id` = '$id'");
		}
		
		public function getRowWhere($table, $columnName, $where) {
			$result_set = $this->db->query("SELECT * FROM `$table` WHERE `$columnName`='$where'");
			return $result_set;
		}

		public function getRowWhere_user($table, $columnName, $where, $user_id) {
			$result_set = $this->db->query("SELECT * FROM `$table` WHERE `$columnName`='$where' AND `$idManager`='$user_id'");
			return $result_set;
		}
		
		public function addUser($login, $password, $name, $variant_pay, $manager_variant, $department) {
			$this->db->query("INSERT INTO `users` (`login`, `password`, `name`, `rights`, `variant_pay`, `manager_variant`, `department`) VALUES ('$login', '".md5("$password")."', '$name', '2', '$variant_pay', '$manager_variant', '$department')");
		}

		public function addUserGroup($usergroup_name) {
			$this->db->query("INSERT INTO `user_group` (`usergroup_name`) VALUES ('".$usergroup_name."')");
		}

		 
		public function addAdmin($login, $password, $name) {
			$this->db->query("INSERT INTO `users` (`login`, `password`, `name`, `rights`) VALUES ('$login', '".md5("$password")."', '$name', '1')");
		}
		
		// linkType: 0 - ничего нет, 1 - Растаможка, 2 - Заявки, 3 - Прием заявок, 4 - оплата
		public function addLog($idContractor, $date, $description, $linkType, $idItem, $idUser) {
			$this->db->query("INSERT INTO `logs` (`idContractor`, `date`, `description`, `linkType`, `idItem`, `idUser`) 
									VALUES ('$idContractor', '$date', '$description', '$linkType', '$idItem', '$idUser')");
		} 
		
		public function addRequest($customer, $carrier, $number, $date, $cargo, $carNumber, $fio, $customerPrice, $carrierPrice, $idUser, $isCurrencyPayment, $comision_static, $currency, $from, $to, $dateShipping, $time, $term, $address1, $address2, $transportType, $contactName1, $contactName2, $route, $temperature, $customs1, $customs2, $broker, $pogran, $driverPhones, $sumForCustomer, $sumForCarrier, $weight, $weight_var, $v, $v_var, $pay_variant, $languages, $partener, $customs) {
			$this->db->query("INSERT INTO `requests` (`customer`, `carrier`, `number`, `date`, `cargo`, `carNumber`, `fio`, `customerPrice`, `carrierPrice`, `idUser`, `isCurrencyPayment`, `comision_static`, `currency`, `from`, `to`,
								`dateShipping`, `time`, `term`, `address1`, `address2`, `transportType`, `contactName1`, `contactName2`, `route`, `temperature`, 
								`customs1`, `customs2`, `broker`, `pogran`, `driverPhones`, `sumForCustomer`, `sumForCarrier`, `weight`, `weight_var`, `v`, `v_var`, `pay_variant`, `languages`,`partener`, `customs`)  
								VALUES ('$customer', '$carrier', '$number', '$date', '$cargo', '$carNumber', '$fio', '$customerPrice', '$carrierPrice', '$idUser', '$isCurrencyPayment', '$comision_static', '$currency', '$from', '$to',
									'$dateShipping', '$time', '$term', '$address1', '$address2', '$transportType', '$contactName1', '$contactName2', '$route', '$temperature', 
									'$customs1', '$customs2', '$broker', '$pogran', '$driverPhones', '$sumForCustomer', '$sumForCarrier', '$weight', '$weight_var', '$v', '$v_var', 
									'$pay_variant', '$languages', '$partener', '$customs')");
		}

 
		public function editRequest($id, $customer, $carrier, $number, $date, $cargo, $carNumber, $fio, $customerPrice, $carrierPrice, $idUser, $isCurrencyPayment, $comision_static, $currency, $from, $to, $dateShipping, $time, $term, $address1, $address2, $transportType, $contactName1, $contactName2, $route, $temperature, 
								$customs1, $customs2, $broker, $pogran, $driverPhones, $sumForCustomer, $sumForCarrier, $weight, $weight_var, $v, $v_var, $pay_variant, $languages, $partener, $customs) {
			$this->db->query("UPDATE `requests` SET `customer`='$customer', `carrier`='$carrier', `number`='$number', `date`='$date', `cargo`='$cargo', `carNumber`='$carNumber', 
										`fio`='$fio', `customerPrice`='$customerPrice', `carrierPrice`='$carrierPrice', `idUser`='$idUser', `isCurrencyPayment`='$isCurrencyPayment', `comision_static`='$comision_static', `currency`='$currency', `from`='$from', `to`='$to',
										`dateShipping`='$dateShipping', `time`='$time', `term`='$term', `address1`='$address1', `address2`='$address2', `transportType`='$transportType', `contactName1`='$contactName1', `contactName2`='$contactName2', `route`='$route', 
										`temperature`='$temperature', `customs1`='$customs1', `customs2`='$customs2', `broker`='$broker', `pogran`='$pogran', `driverPhones`='$driverPhones', 
										`sumForCustomer`='$sumForCustomer', `sumForCarrier`='$sumForCarrier', `weight`='$weight', `weight_var`='$weight_var', `v`='$v', `v_var`='$v_var', `pay_variant`='$pay_variant', `languages`='$languages', `partener`='$partener', `customs`='$customs' WHERE `id` = '$id'");
		} 
		
		public function addContractor($contractorsType, $name, $company_form, $contactName, $phone, $email, $country, $city, $bankDetails, $headName, $carsAmount, $directions, $comments, $isMarked, $idManager, $date, $cod_fiscal, $company_import_export, $company_season) {
			$this->db->query("INSERT INTO `contractors` (`contractorsType`, `name`, `company_form`, `contactName`, `phone`, `email`, `country`, `city`, `bankDetails`, `headName`, `carsAmount`, `directions`, `comments`, `isMarked`, `idManager`, `date`, `cod_fiscal`, `company_import_export`, `company_season`) 
								VALUES ('$contractorsType', '$name', '$company_form', '$contactName', '$phone', '$email', '$country', '$city', '$bankDetails', '$headName', '$carsAmount', '$directions', '$comments', '$isMarked', '$idManager', '$date', '$cod_fiscal', '$company_import_export', '$company_season')");
		}

		public function addGetRequest($dateCargoReady, $customer, $weight, $weight_var, $capacity, $capacity_var, $transportType, $info, $point1, $point2, $point3, $point4, $price, $idUser, $idUserTransport, $cargo_status) {
			$timeNow = time();
			$this->db->query("INSERT INTO `get_requests` (`dateCargoReady`, `customer`, `date`, `weight`, `weight_var`, `capacity`, `capacity_var`, `transportType`, `info`, `point1`, `point2`, `point3`, `point4`, `price`, `idUser`, `idUserTransport`, `cargo_status`) 
								VALUES ('$dateCargoReady', '$customer', '$timeNow', '$weight', '$weight_var', '$capacity', '$capacity_var', '$transportType', '$info', '$point1', '$point2', '$point3', '$point4', '$price', '$idUser', '$idUserTransport', '$cargo_status')");
		}

	/*form_request*/

			public function addFormRequest($dateShipping, $weight, $weight_var, $v, $v_var, $broker, $customer, $adress_load, $adress_unload, $custom_in, $custom_out, $contact_factory, $company_details, $phone_other_companies, $customer_name, $customer_phone, $status, $date, $idUser) {
				$timeNow = time(); 
				$this->db->query("INSERT INTO `form_requests_customer` (`dateShipping`, `weight`, `weight_var`, `v`, `v_var`, `broker`, `customer`, `adress_load`, `adress_unload`, `custom_in`, `custom_out`, `contact_factory`, `company_details`, `phone_other_companies`, `customer_name`, `customer_phone`, `status`, `date`, `idUser`) 
									VALUES ('$dateShipping', '$weight', '$weight_var', '$v', '$v_var', '$broker', '$customer', '$adress_load', '$adress_unload', '$custom_in', '$custom_out', '$contact_factory', '$company_details', '$phone_other_companies', '$customer_name', '$customer_phone', '$status', '$date', '$idUser')");
			}	
	 

			public function updateFormRequest($id, $carrier, $carrier_name, $carrier_details, $carrier_phone, $carrier_auto_nr, $carrier_fio, $carrier_transport_type, $carrier_adress, $carrier_pogran, $scan1, $scan2, $scan3, $phone_other_companies_carrier) {
				$this->db->query("UPDATE `form_requests_customer` SET `carrier`='$carrier', `carrier_name`='$carrier_name', `carrier_details`='$carrier_details', `carrier_phone`='$carrier_phone', `carrier_auto_nr`='$carrier_auto_nr', `carrier_fio`='$carrier_fio', `carrier_transport_type`='$carrier_transport_type', `carrier_adress`='$carrier_adress', `carrier_pogran`='$carrier_pogran', `carrier_teh_pasport`='$scan1', `carrier_prava`='$scan2', `carrier_docs`='$scan3', `phone_other_companies_carrier`='$phone_other_companies_carrier' WHERE `id` = '$id'");
			}	


			public function updateFormAdded($id, $added) {
				$this->db->query("UPDATE `form_requests_customer` SET `added`='$added' WHERE `id` = '$id'");
			}	

	/*form_request*/



		public function editFormRequest($id, $dateShipping, $time, $term, $weight, $weight_var, $v, $v_var, $transportType, $from, $to, $route, $pogran, $broker, $customer, $adress_load, $adress_unload, $custom_in, $custom_out, $contact_factory, $contact_broker, $contact_recipient, $pay_period, $company_details, $phone_other_companies, $customer_name, $customer_phone, $status, $date, $idUser, $carrier, $carrier_name, $carrier_details, $carrier_phone, $carrier_auto_nr, $carrier_fio, $carrier_transport_type, $carrier_adress, $carrier_pogran, $phone_other_companies_carrier) {
			$this->db->query("UPDATE `form_requests_customer` SET `dateShipping`='$dateShipping', `time`='$time', `term`='$term', `weight`='$weight', `weight_var`='$weight_var', `v`='$v', `v_var`='$v_var', `transportType`='$transportType', `from`='$from', `to`='$to', `route`='$route', `pogran`='$pogran', `broker`='$broker',`customer`='$customer', `adress_load`='$adress_load', `adress_unload`='$adress_unload', `custom_in`='$custom_in', `custom_out`='$custom_out', `contact_factory`='$contact_factory', `contact_broker`='$contact_broker', 
`contact_recipient`='$contact_recipient', `pay_period`='$pay_period', `company_details`='$company_details', `phone_other_companies`='$phone_other_companies', `customer_name`='$customer_name', `customer_phone`='$customer_phone', `status`='$status', `date`='$date', `idUser`='$idUser', `carrier`='$carrier', `carrier_name`='$carrier_name', `carrier_details`='$carrier_details', `carrier_phone`='$carrier_phone', `carrier_auto_nr`='$carrier_auto_nr', `carrier_fio`='$carrier_fio', `carrier_transport_type`='$carrier_transport_type', `carrier_adress`='$carrier_adress', `carrier_pogran`='$carrier_pogran', `phone_other_companies_carrier`='$phone_other_companies_carrier' WHERE `id` = '$id'");
		}



 
		
		public function addPaymentFromReguest($contractorsType, $customer, $number, $date, $days) {
			$this->db->query("INSERT INTO `payments` (`contractorsType`, `customer`, `date`, `number`, `days`) VALUES ('$contractorsType', '$customer', '$date', '$number', '$days')");
		}
		
		
		public function addClient($name, $company_form, $contactName, $date, $comments, $idManager, $phone, $country, $isMarked) {
			$this->db->query("INSERT INTO `clients` (`name`, `company_form`, `contactName`, `date`, `comments`, `idManager`, `phone`, `country`, `isMarked`) 
										VALUES ('$name', '$company_form', '$contactName', '$date', '$comments', '$idManager', '$phone', '$country', '$isMarked')");
		}
		
		public function addPayment($contractorsType, $customer, $number, $date, $days) {
			$this->db->query("INSERT INTO `payments` (`contractorsType`, `customer`, `date`, `number`, `days`) VALUES ('$contractorsType', '$customer', '$date', '$number', '$days')");
		}
		
		public function addToBlacklist($name, $contactName, $status) {
			$timeNow = time();
			$this->db->query("INSERT INTO `blacklist` (`name`, `contactName`, `date`,`status` ) VALUES ('$name', '$contactName', '$timeNow', '$status')");
		}
		public function addToBlacklist1($name, $contactName, $status) {
			$timeNow = time();
			$this->db->query("INSERT INTO `blacklist1` (`name`, `contactName`, `date`,`status` ) VALUES ('$name', '$contactName', '$timeNow', '$status')");
		}
		
		public function addDoc($id, $name, $category, $path) {
			$timeNow = time();
			$this->db->query("INSERT INTO `docs` (`id`, `name`, `category`, `path`, `date`) VALUES ('$id', '$name', '$category', '$path', '$timeNow')");
		}
		
		public function addTagToContractor($idContractor, $tag) {
			$this->db->query("INSERT INTO `tags` (`idContractor`, `tag`) VALUES ('$idContractor', '$tag')");
		}
		
		public function addTagToClient($idClient, $tag) {
			$this->db->query("INSERT INTO `tags_clients` (`idClient`, `tag`) VALUES ('$idClient', '$tag')");
		}
		
		public function addSalary($idUser, $category, $sum, $date, $comment) {
			$this->db->query("INSERT INTO `salary` (`idUser`, `category`, `sum`, `date`, `comment`) VALUES ('$idUser', '$category', '$sum', '$date', '$comment')");
		}

		// public function getSumWhere($table, $columnSum) {
			// $result_set = $this->db->query("SELECT SUM($columnSum) FROM `$table`");
			// $row = $result_set->fetch_assoc();
			// return $row["SUM($columnSum)"];
		// }
		
		public function editPassword($id, $password) {
			$this->db->query("UPDATE `users` SET `password`='".md5("$password")."' WHERE `id` = '$id'");
		}
						
		public function editSalary($id, $category, $sum, $date, $comment) {
			$this->db->query("UPDATE `salary` SET `category`='$category', `sum`='$sum', `date`='$date', `comment`='$comment' WHERE `id` = '$id'");
		}
						
		public function writeDeliveryNote($id) {
			$this->db->query("UPDATE `customs_clearance` SET `deliveryNote`='1' WHERE `id` = '$id'");
		}
					 
		public function editContractor($id, $contractorsType, $name, $company_form, $contactName, $phone, $email, $country, $city, $bankDetails, $headName, $carsAmount, $directions, $comments, $isMarked, $idManager, $date, $cod_fiscal, $company_import_export, $company_season) {
			$this->db->query("UPDATE `contractors` SET `contractorsType`='$contractorsType', `name`='$name', `company_form`='$company_form', `contactName`='$contactName', 
														`phone`='$phone', `email`='$email', `country`='$country', `city`='$city', `bankDetails`='$bankDetails', 
														`headName`='$headName', `carsAmount`='$carsAmount', `directions`='$directions', `comments`='$comments', 
														`isMarked`='$isMarked', `idManager`='$idManager', `date`='$date', `cod_fiscal`='$cod_fiscal', `company_import_export`= '$company_import_export', `company_season`='$company_season' WHERE `id` = '$id'");
		}
								 
		public function editCustomsClearance($id, $customer, $carrier, $number, $date, $price) {
			$this->db->query("UPDATE `customs_clearance` SET `customer`='$customer', `carrier`='$carrier', `number`='$number', `date`='$date', `price`='$price' WHERE `id` = '$id'");
		}

		public function addCustomsClearance($id, $customer, $carrier, $number, $date, $price, $path, $path2) {
			$this->db->query("INSERT INTO `customs_clearance` (`id`, `customer`, `carrier`, `number`, `date`, `price`, `path`, `path2`, `deliveryNote`) 
															VALUES ('$id', '$customer', '$carrier', '$number', '$date', '$price', '$path', '$path2', '0')");
		}



		public function editCustomsClearanceWithNewFile($id, $customer, $carrier, $number, $date, $price, $path, $path2) {
			$this->db->query("UPDATE `customs_clearance` SET `customer`='$customer', `carrier`='$carrier', `number`='$number', `date`='$date', `price`='$price', `path`='$path', `path2`='$path2' WHERE `id` = '$id'");
		}

		

//updated new
		public function editCustomsClearance_number($customer, $carrier, $number) {
			$this->db->query("UPDATE `customs_clearance` SET `customer`='$customer', `carrier`='$carrier' WHERE `number` = '$number'");
		}

		public function editPayment_number_customer($customer, $number, $contractorsType) {
			$this->db->query("UPDATE `payments` SET `customer`='$customer' WHERE `number` = '$number' AND `contractorsType` = '$contractorsType'");
		}

		public function editPayment_number_carrier($carrier, $number, $contractorsType) {
			$this->db->query("UPDATE `payments` SET `customer`='$carrier' WHERE `number` = '$number' AND `contractorsType` = '$contractorsType'");
		}
// end updated

		
		public function editClient($id, $name, $company_form, $contactName, $comments, $date, $idUser, $phone, $country, $isMarked) {
			$this->db->query("UPDATE `contractors` SET `contactName`='$contactName', `comments`='$comments', `name`='$name', `company_form`='$company_form', `date`='$date', 
									`idManager`='$idUser', `phone`='$phone', `country`='$country', `isMarked`='$isMarked' WHERE `id` = '$id'");
        }

        public function editPlan($plan_manager, $to50, $from50to100, $full100, $to50_v2, $from50to100_v2, $full100_v2) {
			$this->db->query("UPDATE `plan` SET `plan_manager`='$plan_manager', `to50`='$to50', `from50to100`='$from50to100', `full100`='$full100', 
				`to50_v2`='$to50_v2', `from50to100_v2`='$from50to100_v2', `full100_v2`='$full100_v2' WHERE `id` = '1'");
        }


		public function editClient_custom($id, $name, $contactName, $comments, $phone, $idUser) {
			session_start();
			if ($_SESSION["role"] == 1) {
				$this->db->query("UPDATE `contractors` SET `contactName`='$contactName', `comments`='$comments', `name`='$name', `idManager`='$idUser', `phone`='$phone' WHERE `id` = '$id'");
			} else {
				$this->db->query("UPDATE `contractors` SET `contactName`='$contactName', `comments`='$comments', `name`='$name', `phone`='$phone' WHERE `id` = '$id'");
			}
        } 

 
        public function editNextCall($id, $date, $updated_date) {
			$this->db->query("UPDATE `contractors` SET `date`='$date' WHERE `id` = '$id'");
        }
        public function editMark($id, $isMarked) {
			$this->db->query("UPDATE `contractors` SET `isMarked`='$isMarked' WHERE `id` = '$id'");
		} 
					 
		public function editBlacklist($id, $name, $contactName, $reason) {
			$this->db->query("UPDATE `blacklist` SET `contactName`='$contactName', `reason`='$reason', `name`='$name' WHERE `id` = '$id'");
		}

								
		public function editGetRequest($id, $customer, $weight, $weight_var, $capacity, $capacity_var, $transportType, $info, $point1, $point2, $point3, $point4, $price, $idUser, $dateCargoReady) {
			$this->db->query("UPDATE `get_requests` SET `customer`='$customer', `weight`='$weight', `weight_var`='$weight_var', `capacity`='$capacity', `capacity_var`='$capacity_var', `transportType`='$transportType', `info`='$info', 
										`point1`='$point1', `point2`='$point2', `point3`='$point3', `point4`='$point4', `price`='$price', `idUser`='$idUser', `dateCargoReady`='$dateCargoReady' WHERE `id` = '$id'");
		}
											
		public function editDoc($id, $name, $category, $scan) {
			$this->db->query("UPDATE `docs` SET `name`='$name', `category`='$category', `path`='$scan' WHERE `id` = '$id'");
		}
			
		public function getLastID($table) {
			$result_set = $this->db->query("SELECT id FROM `$table` WHERE id=(SELECT MAX(id) FROM $table)");
			$row = $result_set->fetch_assoc();
			return $row[id];
		}
					
		public function getLastNumber() {
			$result_set = $this->db->query("SELECT number FROM `requests` WHERE number=(SELECT MAX(number) FROM `requests`)");
			$row = $result_set->fetch_assoc();
			return $row[number];
		}
		
		public function getRowWhereOrder($table, $columnName, $where, $order) {
			$result_set = $this->db->query("SELECT * FROM `$table` WHERE `$columnName`='$where' ORDER by $order");
			return $result_set;
		}


		public function getRowWhereWhereOrder($table, $columnName1, $where1, $columnName2, $where2, $order) {
			$result_set = $this->db->query("SELECT * FROM `$table` WHERE `$columnName1`='$where1' AND `$columnName2`='$where2' ORDER by $order");
			return $result_set;
		}


		/*form_request*/
		public function getrow_form($table, $columnName1, $where1, $columnName2, $where2, $type_contrator, $order) {
			$result_set = $this->db->query("SELECT * FROM `$table` WHERE `$columnName1`='$where1' AND `$columnName2`='$where2' AND `contractorsType`= '$type_contrator' ORDER by $order");
			return $result_set;
		}



		/*form_request*/


		/*contractors*/
		public function get_contractors_rus($table, $columnName1, $where1, $columnName2, $where2, $order, $user_id) {
			$result_set = $this->db->query("SELECT * FROM `$table` WHERE `$columnName1`='$where1' AND `$columnName2`='$where2' AND `$columnName2`='$where2' AND `name` REGEXP '[а-яА-Я]' AND `idManager` like '$user_id%'  ORDER by $order ");
			return $result_set;
		}


		public function get_contractors_rus_users($table, $columnName1, $where1, $columnName2, $where2, $order, $user_id) {
			$result_set = $this->db->query("SELECT * FROM `$table` WHERE `$columnName1`='$where1' AND `$columnName2`='$where2' AND `$columnName2`='$where2' AND `name` REGEXP '[а-яА-Я]' AND `contractorsType` = 'Заказчик' AND `idManager` like '$user_id%'  ORDER by $order ");
			return $result_set;
		}



		public function get_contractors($table, $columnName1, $where1, $columnName2, $where2, $alpha, $order, $user_id) {
			$result_set = $this->db->query("SELECT * FROM `$table` WHERE `$columnName1`='$where1' AND `$columnName2`='$where2' AND `$columnName2`='$where2' AND `name` like '$alpha%' AND `idManager` like '$user_id%' ORDER by $order ");
			return $result_set; 
		} 

		public function get_contractors_without_manager($table, $columnName1, $where1, $columnName2, $where2, $order) {
			$result_set = $this->db->query("SELECT * FROM `$table` WHERE `$columnName1`='$where1' AND `$columnName2`='$where2' AND `$columnName2`='$where2' AND (`idManager`='' OR `idManager`='0') ORDER by $order ");
			return $result_set; 
		} 



		/*contractors*/
 
 

		/*clients*/

		public function get_clients($table, $columnName1, $where1, $columnName2, $where2, $alpha, $order) {
			$result_set = $this->db->query("SELECT * FROM `$table` WHERE `$columnName1`='$where1' AND `$columnName2`='$where2' AND `$columnName2`='$where2' AND `contractorsType` = 'Заказчик' AND `name` like '$alpha%' ORDER by $order ");
			return $result_set;
		}

		public function get_clients_injob($table, $columnName1, $idManager, $columnName2, $where2, $order) {
			$result_set = $this->db->query("SELECT * FROM `$table` WHERE `$columnName1`='$idManager' AND `$columnName2`='$where2' AND `contractorsType` = 'Заказчик' AND `date` BETWEEN UNIX_TIMESTAMP(DATE_ADD(CURDATE(),INTERVAL -12 HOUR)) AND UNIX_TIMESTAMP(DATE_ADD(CURDATE(),INTERVAL 12 HOUR)) ORDER by $order "); 
			return $result_set;
		}

		public function get_clients_profit($table, $columnName1, $where1, $columnName2, $where2, $current_date_up, $order) {
			$result_set = $this->db->query("SELECT * FROM `$table` WHERE `$columnName1`='$where1' AND `$columnName2`='$where2' AND `contractorsType` = 'Заказчик' AND `date`='$current_date_up' ORDER by $order ");
			return $result_set;
		} 
		public function get_clients_arhive($table, $columnName1, $where1, $columnName2, $where2, $current_date, $order) {
			$result_set = $this->db->query("SELECT * FROM `$table` WHERE `$columnName1`='$where1' AND `$columnName2`='$where2' AND `contractorsType` = 'Заказчик' AND `date`<'$current_date' ORDER by $order ");
			return $result_set;
		} 

			public function get_clients_season($table, $columnName1, $where1, $columnName2, $where2, $filter, $order) {
			$result_set = $this->db->query("SELECT * FROM `$table` WHERE `$columnName1`='$where1' AND `$columnName2`='$where2' AND `contractorsType` = 'Заказчик' AND `company_season` = '$filter' ORDER by $order ");
			return $result_set;
		}  
		/*clients*/

		public function getRowWhereWhereWhereOrder($table, $columnName1, $where1, $columnName2, $where2, $order) {
			$result_set = $this->db->query("SELECT * FROM `$table` WHERE `$columnName1`='$where1' AND `$columnName2`='$where2' ORDER by $order");
			return $result_set;
		}
				
		public function getAllOrder($table, $order) {
			$result_set = $this->db->query("SELECT * FROM `$table` ORDER by $order");
			return $result_set;
		}
		

		//v1
		// public function getCustomsClearanceByMonth($startDate, $amountOfDaysInMonth) {
			// $result_set = $this->db->query("SELECT * FROM `customs_clearance` WHERE `date` >= '$startDate' AND `date` < ($startDate + $amountOfDaysInMonth*86400 - 1) ORDER by number");
			// return $result_set;
		// }
		
		//v2 - 11.04.2019



		public function getCustomsClearanceByMonth_for_user($startDate, $amountOfDaysInMonth, $user_id) {


			$result_set = $this->db->query("SELECT * FROM `customs_clearance` 
												WHERE ((`date` >= '$startDate' AND `date` < ($startDate + $amountOfDaysInMonth*86400 - 1))
													OR (`deliveryNote` = 0 AND `date` < ($startDate + $amountOfDaysInMonth*86400 - 1)))
													AND number IN (SELECT number FROM `requests` WHERE `idUser` = $user_id)
													ORDER by number"); 
			return $result_set;
		}
 



		public function getCustomsClearanceByMonth($startDate, $amountOfDaysInMonth) {


			$result_set = $this->db->query("SELECT * FROM `customs_clearance` 
												WHERE (`date` >= '$startDate' AND `date` < ($startDate + $amountOfDaysInMonth*86400 - 1))
													OR (`deliveryNote` = 0 AND `date` < ($startDate + $amountOfDaysInMonth*86400 - 1))
													ORDER by number");
			return $result_set;
		}


				public function getCustomsClearanceByCustoms($startDate, $amountOfDaysInMonth, $id) {
			$result_set = $this->db->query("SELECT * FROM `customs_clearance` 
												WHERE (`date` >= '$startDate' AND `date` < ($startDate + $amountOfDaysInMonth*86400 - 1) AND `customer`='$id')
													OR (`deliveryNote` = 0 AND `date` < ($startDate + $amountOfDaysInMonth*86400 - 1) AND `customer`='$id')
													ORDER by number DESC");
			return $result_set;
		}

			public function getCustomsClearanceByCarriers($startDate, $amountOfDaysInMonth, $id) {
			$result_set = $this->db->query("SELECT * FROM `customs_clearance` 
												WHERE (`date` >= '$startDate' AND `date` < ($startDate + $amountOfDaysInMonth*86400 - 1) AND `carrier`='$id')
													OR (`deliveryNote` = 0 AND `date` < ($startDate + $amountOfDaysInMonth*86400 - 1) AND `carrier`='$id')
													ORDER by number DESC"); 
			return $result_set;
		}



			public function getCustomsClearanceByCustoms_new($id) {
			$result_set = $this->db->query("SELECT * FROM `customs_clearance` 
												WHERE (`customer`='$id')
													ORDER by number DESC");
			return $result_set;
		}

			public function getCustomsClearanceByCarriers_new($id) {
			$result_set = $this->db->query("SELECT * FROM `customs_clearance` 
												WHERE (`carrier`='$id')
													ORDER by number DESC"); 
			return $result_set;
		}
		

		//v2 - 11.04.2019
		public function getPaymentsByMonth($startDate, $amountOfDaysInMonth, $contractorsType) {
			$result_set = $this->db->query("SELECT * FROM `payments` 
												WHERE ((`date` >= '$startDate' AND `date` < ($startDate + $amountOfDaysInMonth*86400 - 1))
													OR (`paymentWasDidAlreary` = 0 AND `date` < ($startDate + $amountOfDaysInMonth*86400 - 1)))
													AND `contractorsType` = '$contractorsType' ORDER by number DESC");
			return $result_set;
		}

		public function getPaymentsByMonth_new($contractorsType) {
			$result_set = $this->db->query("SELECT * FROM `payments` 
												WHERE (`contractorsType` = '$contractorsType' ORDER by number DESC");
			return $result_set;
		}


				
		public function getRequestsByMonth($startDate, $amountOfDaysInMonth) {
			$result_set = $this->db->query("SELECT * FROM `requests` WHERE `date` >= '$startDate' AND `date` < ($startDate + $amountOfDaysInMonth*86400 - 3601) ORDER by number");
			return $result_set;
		}
				
		public function getRequestsByMonth_activ($todayUnix) {
			$result_set = $this->db->query("SELECT * FROM `requests` WHERE `dateShipping` >= $todayUnix ORDER by number");
			return $result_set;
		}
		public function getRequestsByMonth_end($todayUnix, $minusWeek) {
			$result_set = $this->db->query("SELECT * FROM `requests` WHERE `dateShipping` >= $minusWeek AND `dateShipping` < $todayUnix ORDER by number");
			return $result_set;
		}

		public function getRequestsByMonth_arhive($minusWeek, $minusMonth) {
			$result_set = $this->db->query("SELECT * FROM `requests` WHERE `dateShipping` >= $minusMonth AND `dateShipping` < $minusWeek ORDER by number");
			return $result_set;
		}



		public function getRequestsByMonthByUser($startDate, $amountOfDaysInMonth, $idUser) {
			$result_set = $this->db->query("SELECT * FROM `requests` WHERE `date` >= '$startDate' AND `date` < ($startDate + $amountOfDaysInMonth*86400 - 1) AND 
											(`idUser`='$idUser' OR `partener` = '$idUser') ORDER by number");
			return $result_set; 
		}  

		public function getRequestsHome($alpha, $beta) {
			$result_set = $this->db->query("SELECT * FROM `requests` WHERE `from` LIKE '%$alpha%' AND `to` LIKE '%$beta%' ORDER by date DESC");
			return $result_set;
		}
			


//new added 2022

//new added 2022 end
 

		/*salary by custom clearence*/
		public function getCustom_clearenceByMonthByUser($startDate, $amountOfDaysInMonth, $idUser) {
			$result_set = $this->db->query("SELECT * FROM `requests` WHERE (`idUser`='$idUser' OR `partener` = '$idUser') /*AND `date` >= '$startDate' AND `date` < ($startDate + $amountOfDaysInMonth*86400 - 1)*/ AND `number` IN (SELECT DISTINCT number FROM `customs_clearance` WHERE  `date` >= '$startDate' AND `date` < ($startDate + $amountOfDaysInMonth*86400 - 1)) ORDER by date");
			return $result_set;
		}
 
		public function getsalary_fromCustomclearence($startDate, $amountOfDaysInMonth, $idUser) {
			$result_set = $this->db->query("SELECT * FROM `requests` WHERE (`idUser`='$idUser' OR `partener` = '$idUser') AND `date` >= '$startDate' AND `date` < ($startDate + $amountOfDaysInMonth*86400 - 1) AND `number` IN (SELECT DISTINCT number FROM `customs_clearance` WHERE `deliveryNote` = 1 AND `date` >= '$startDate' AND `date` < ($startDate + $amountOfDaysInMonth*86400 - 1)) ORDER by date");
			return $result_set;
		}


		public function difference_custom($startDate, $amountOfDaysInMonth, $idUser) {
			$result_set = $this->db->query("SELECT * FROM `requests` WHERE (`idUser`='$idUser' OR `partener` = '$idUser') AND `date` >= '$startDate' AND `date` < ($startDate + $amountOfDaysInMonth*86400 - 1) AND number IN(SELECT DISTINCT number FROM `customs_clearance` WHERE `deliveryNote` = 0) ORDER by date DESC");
			return $result_set;
		}

		public function difference_request($startDate, $amountOfDaysInMonth, $startDate_precedent, $amountOfDaysInMonth_precedent, $idUser) {
			$result_set = $this->db->query("SELECT * FROM `requests` WHERE (`idUser`='$idUser' OR `partener` = '$idUser') AND `date` >= '$startDate_precedent' AND `date` < ($startDate_precedent + $amountOfDaysInMonth_precedent*86400 - 1) AND number IN(SELECT DISTINCT number FROM `customs_clearance` 
				WHERE (`date` >= '$startDate' AND `date` < ($startDate + $amountOfDaysInMonth*86400 - 1))
				OR `deliveryNote` = 0) ORDER by date DESC");
			return $result_set;
		}


		public function difference_request_no_custom($startDate, $amountOfDaysInMonth, $startDate_precedent, $amountOfDaysInMonth_precedent, $idUser) {
			$result_set = $this->db->query("SELECT * FROM `requests` WHERE (`idUser`='$idUser' OR `partener` = '$idUser') AND `date` >= '$startDate_precedent' AND `date` < ($startDate_precedent + $amountOfDaysInMonth_precedent*86400 - 1) AND number IN(SELECT DISTINCT number FROM `customs_clearance` 
				WHERE (`date` >= '$startDate' AND `date` < ($startDate + $amountOfDaysInMonth*86400 - 1))
				AND `deliveryNote` = 0) ORDER by date DESC");
			return $result_set;
		}
 


		/*salary by custom clearence*/

		public function getMonthSalary($startDate, $amountOfDaysInMonth, $idUser) {
			$result_set = $this->db->query("SELECT * FROM `salary` WHERE `date` >= '$startDate' AND `date` < ($startDate + $amountOfDaysInMonth*86400 - 1) 
												AND `idUser`='$idUser' ORDER by date DESC");
			return $result_set;
		}
						
		public function getRequestsByEmployee($idUser) {
			$result_set = $this->db->query("SELECT * FROM `requests` WHERE `idUser` = '$idUser' ORDER by number");
			return $result_set;
		}
						
		public function getGetRequestsByMonth($startDate, $amountOfDaysInMonth) {
			$result_set = $this->db->query("SELECT * FROM `get_requests` WHERE `date` >= '$startDate' AND `date` < ($startDate + $amountOfDaysInMonth*86400 - 1) 
												AND `status` = '0' ORDER by date");
			return $result_set;
		}
								
		public function getGetRequestsByMonthByManager($startDate, $amountOfDaysInMonth, $idUser) {
			$result_set = $this->db->query("SELECT * FROM `get_requests` WHERE `date` >= '$startDate' AND `date` < ($startDate + $amountOfDaysInMonth*86400 - 1) 
												AND `idUser` = '$idUser' AND `status` = '0' ORDER by date");
			return $result_set;
		}
			public function formRequestsByMonth($startDate, $amountOfDaysInMonth) {
			$result_set = $this->db->query("SELECT * FROM `form_requests_customer` WHERE/* `date` >= '$startDate' AND `date` < ($startDate + $amountOfDaysInMonth*86400 - 1) 
												AND */`status` = '0' ORDER by date");
			return $result_set;
		}
								
		public function formRequestsByMonthByManager($startDate, $amountOfDaysInMonth, $idUser) {
			$result_set = $this->db->query("SELECT * FROM `form_requests_customer` WHERE `date` >= '$startDate' AND `date` < ($startDate + $amountOfDaysInMonth*86400 - 1) 
												AND `idUser` = '$idUser' AND `status` = '0' ORDER by date");
			return $result_set;
		}
												
		public function getGetRequestsByManager($idUser) {
			$result_set = $this->db->query("SELECT * FROM `get_requests` WHERE `idUser` = '$idUser' AND `status` = '0' ORDER by date");
			return $result_set;
		}
		
		public function getRequestsByMonthForUser($startDate, $amountOfDaysInMonth, $idUser) {
			$result_set = $this->db->query("SELECT * FROM `requests` WHERE `date` >= '$startDate' AND `date` < ($startDate + $amountOfDaysInMonth*86400 - 1) AND `idUser`='$idUser' 
												ORDER by number");
			return $result_set;
		}
		
		public function getAllUsersForAdmin() { 
			$result_set = $this->db->query("SELECT * FROM `users` WHERE `isDeleted`='0' AND `rights` = 2 ORDER by name");
			return $result_set;
		}

		public function getAllUsersDepartments() { 
			$result_set = $this->db->query("SELECT * FROM `user_group` ORDER by id");
			return $result_set;
		}
		
		public function AJAXSearch($search, $contractorsType) {
			// global $arrayOfContractorsTypes;
			// $contractorsType = $arrayOfContractorsTypes[0];
			$result_set = $this->db->query("SELECT DISTINCT name FROM `contractors` WHERE `contractorsType` = '$contractorsType' AND `isDeleted` = '0' AND name LIKE '%$search%' ORDER by name");
			return $result_set;
        }

        public function AJAXSearch2($search) {
			// global $arrayOfContractorsTypes;
			$result_set = $this->db->query("SELECT * FROM `contractors` WHERE `isDeleted` = '0' AND `contractorsType` = 'Заказчик' AND `name` LIKE '%$search%' ORDER by name");
			return $result_set;
        }

        public function AJAXSearchName1($search) {
			$result_set = $this->db->query("SELECT * FROM `requests` WHERE `from` LIKE '%$search%' ORDER by date DESC");
			return $result_set;
        }

		
		public function AJAXSearchBlacklist($search) {
			$result_set = $this->db->query("SELECT * FROM `blacklist` WHERE `name` LIKE '%$search%' ORDER by date DESC");
			return $result_set;
        }

		public function AJAXSearchBlacklist1($search) {
			$result_set = $this->db->query("SELECT * FROM `blacklist1` WHERE `name` LIKE '%$search%' ORDER by date DESC");
			return $result_set;
        }

			
        public function searchClients($idManager, $search) {
			$result_set = $this->db->query("SELECT * FROM `contractors` WHERE `idManager` = '$idManager' AND `isDeleted` = '0' AND `name` LIKE '%$search%' AND `contractorsType` = 'Заказчик' ORDER by date DESC");
			return $result_set;
        }

		public function getAllCountriesInClientsTable() {
			$result_set = $this->db->query("SELECT DISTINCT country FROM `clients` WHERE `isDeleted` = '0' ORDER by name");
			return $result_set;
        }

		public function getAllCountriesInClientsTableByManager($idManager) {
			$result_set = $this->db->query("SELECT DISTINCT country FROM `clients` WHERE `idManager` = '$idManager' AND `isDeleted` = '0' ORDER by name");
			return $result_set;
        }
		
		public function getAllContractorsByTypeAndTag($type, $tag) {
			$result_set = $this->db->query("SELECT contractors.id, contractors.name, contractors.company_form, contractors.contactName, contractors.phone, contractors.email, 
													contractors.country, contractors.city, contractors.bankDetails, contractors.headName, 
													contractors.carsAmount, contractors.directions, contractors.comments, contractors.contractorsType
												FROM `contractors`, `tags` 
												WHERE contractors.contractorsType = '$type' AND tags.tag = '$tag' AND contractors.id = tags.idContractor
												ORDER BY contractors.name");
			return $result_set;
		}
		
		public function getAllClientsByTag($tag) {
			$result_set = $this->db->query("SELECT clients.*
												FROM `clients`, `tags_clients` 
												WHERE tags_clients.tag = '$tag' AND clients.id = tags_clients.idClient
												ORDER BY clients.name");
			return $result_set;
		}
		
		public function getAllClientsByTagAndManager($tag, $idManager) {
			$result_set = $this->db->query("SELECT clients.*
												FROM `clients`, `tags_clients` 
												WHERE tags_clients.tag = '$tag' AND clients.id = tags_clients.idClient AND clients.idManager = '$idManager'
												ORDER BY clients.name");
			return $result_set;
		}
		
		public function deleteWhere($table, $column, $where) {
			$this->db->query("DELETE FROM `$table` WHERE `$column` = '$where'");
		}

		public function softDelete($table, $id) {
			$this->db->query("UPDATE `$table` SET `isDeleted`='1' WHERE `id` = '$id'");
		}
		
		public function delete($table, $id) {
			$this->db->query("DELETE FROM `$table` WHERE `id` = '$id'");
		}
		
		
		public function __destruct() {
			if($this->db) $this->db->close();
		}
	}

	
?>
