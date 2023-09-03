<?php 

// Verificăm dacă a fost primită o cerere POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Preluați datele din cererea POST
    $dateInput = $_POST['dateInput'];
    $dateInput2 = $_POST['dateInput2'];
    $timeInput = $_POST['timeInput'];
    $termInput = $_POST['termInput'];
    $tag = $_POST['tag'];
    $tag1 = $_POST['tag1'];
    $carNumberInput = $_POST['carNumberInput'];
    $fioInput = $_POST['fioInput'];
    $driverPhonesInput = $_POST['driverPhonesInput'];
    $weightInput = $_POST['weightInput'];
    $weight_var = $_POST['weight_var'];
    $vInput = $_POST['vInput'];
    $vInput_var = $_POST['vInput_var'];
    $categoryInput = $_POST['categoryInput'];
    $fromInput = $_POST['fromInput'];
    $toInput = $_POST['toInput'];
    $routeInput = $_POST['routeInput'];
    $pogranInput = $_POST['pogranInput'];
    $brokerInput = $_POST['brokerInput'];
    $address1Input = $_POST['address1Input'];
    $address2Input = $_POST['address2Input'];
    $contactName1Input = $_POST['contactName1Input'];
    $contactName2Input = $_POST['contactName2Input'];
    $customs1Input = $_POST['customs1Input'];
    $customs2Input = $_POST['customs2Input'];
    $temperatureInput = $_POST['temperatureInput'];
    $customerPriceInput = $_POST['customerPriceInput'];
    $carrierPriceInput = $_POST['carrierPriceInput'];
    $pay_variant = $_POST['pay_variant'];
    $languages = $_POST['languages'];
    $idUserInput = $_POST['idUserInput'];
    $currencyInput = $_POST['currencyInput'];
    $cargoInput = $_POST['cargoInput'];
    $comision_static = $_POST['comision_static'];
    $idUserInput2 = $_POST['idUserInput2'];
    $CustomsInput = $_POST['CustomsInput'];


    // Configurați detaliile de conectare la baza de date (înlocuiți cu detaliile dvs.)
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "itecomd_crmtest";

    // Creați o conexiune către baza de date
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Verificați dacă conexiunea a fost stabilită cu succes
    if ($conn->connect_error) {
        die("Соединение с базой данных не удалось: " . $conn->connect_error);
    }


    // Definește declarația SQL pentru inserarea datelor
    $sql = "INSERT INTO test111 (dateInput, dateInput2, timeInput, termInput, tag, tag1, carNumberInput, fioInput, driverPhonesInput, weightInput, weight_var, vInput, vInput_var, categoryInput, fromInput, toInput, routeInput, pogranInput, brokerInput, address1Input, address2Input, contactName1Input, contactName2Input, customs1Input, customs2Input, temperatureInput, customerPriceInput, carrierPriceInput, pay_variant, languages, idUserInput, currencyInput, cargoInput, comision_static, idUserInput2, CustomsInput) 
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);

    if ($stmt) {
        // Bind parametrii
        $stmt->bind_param("ssssssssssssssssssssssssssssssssssss", $dateInput, $dateInput2, $timeInput, $termInput, $tag, $tag1, $carNumberInput, $fioInput, $driverPhonesInput, $weightInput, $weight_var, $vInput, $vInput_var, $categoryInput, $fromInput, $toInput, $routeInput, $pogranInput, $brokerInput, $address1Input, $address2Input, $contactName1Input, $contactName2Input, $customs1Input, $customs2Input, $temperatureInput, $customerPriceInput, $carrierPriceInput, $pay_variant, $languages, $idUserInput, $currencyInput, $cargoInput, $comision_static, $idUserInput2, $CustomsInput);

        // Executați declarația SQL
        if ($stmt->execute()) {
            $response = "Данные успешно сохранены в базе данных.";
        } else {
            $response = "Ошибка сохранения данных в базу данных: " . $stmt->error;
        }

        // Închideți declarația SQL
        $stmt->close();
    } else {
        $response = "Ошибка подготовки оператора SQL: " . $conn->error;
    }

    // Închideți conexiunea la baza de date
    $conn->close();

    // Trimiteți răspunsul înapoi către client (JavaScript)
    echo $response;
} else {
    http_response_code(400); // Bad Request
    echo "Запрос недействителен.";
}

?>