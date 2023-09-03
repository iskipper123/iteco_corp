<?php
// Conectare la baza de date
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "itecomd_crmtest";

$conn = mysqli_connect($servername, $username, $password, $dbname);

if (!$conn) {
    die("Conexiunea la baza de date a eșuat: " . mysqli_connect_error());
}

// Obțineți numele din cererea AJAX
$name_to_check = $_POST['name'];

// Verificați numele în baza de date
$sql = "SELECT * FROM blacklist1 WHERE name = '$name_to_check'";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) > 0) {
    // Numele există în blacklist
    echo "in_blacklist";
} else {
    // Numele nu este în blacklist
    echo "not_in_blacklist";
}

mysqli_close($conn);
?>