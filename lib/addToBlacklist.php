<?php require_once "../lib/db.php"; ?>
<?php require_once "../lib/vars.php"; ?>

<!DOCTYPE html>
<html>
<head>
    <title>Popup cu opțiuni</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
	<style>
		body {
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 0;
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 100vh;
    background-color: #f0f0f0;
    
}

.popup {
    display: none;
    position: absolute;
    background-color: white;
    border: 1px solid #ccc;
    padding: 10px;
    box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
    z-index: 1000;
    
}

.btn {
    padding: 10px 20px;
    border: none;
    cursor: pointer;
    font-size: 20px;
    
}

.btn-primary {
    background-color: #007bff;
    color: white;
}

.btn-secondary {
    background-color: #ccc;
    color: black;
    margin-right: 10px;
}
	</style>
   
        <a href="addToBlacklistZacazciki.php"> <button class="btn btn-secondary" id="addCustomers">Заказчики</button></a>
        <a href="addToBlacklistPerevozciki.php"><button class="btn btn-secondary" id="addCarriers">Перевозчики</button></a>
   
    
    <script>

document.getElementById("openPopup").addEventListener("click", function() {
    document.getElementById("popup").style.display = "block";
});



document.getElementById("addCarriers").addEventListener("click", function() {
    // Logic pentru adăugarea în lista de transportatori
    alert("Adăugare în lista de transportatori");
    document.getElementById("popup").style.display = "none";
});


	</script>
</body>
</html>
