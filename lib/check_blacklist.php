<?php
// // Datele pentru conexiune la baza de date
// $servername = "sql306.hostingem.ru"; // Adresa serverului MySQL
// $username = "gnioo_32733497"; // Numele utilizatorului MySQL
// $password = "545426"; // Parola utilizatorului MySQL
// $dbname = "gnioo_32733497_itecomd_crmtest"; // Numele bazei de date
// Datele pentru conexiune la baza de date
$servername = "localhost"; // Adresa serverului MySQL
$username = "root"; // Numele utilizatorului MySQL
$password = ""; // Parola utilizatorului MySQL
$dbname = "itecomd_crmtest"; // Numele bazei de date

// Crearea conexiunii
$conn = mysqli_connect($servername, $username, $password, $dbname);
// Установка кодировки UTF-8 для соединения
mysqli_set_charset($conn, "utf8");
// Verificarea conexiunii
if (!$conn) {
    die("Conexiunea la baza de date a eșuat: " . mysqli_connect_error());
}

// Verificăm dacă a fost trimisă o valoare prin cererea POST
if (isset($_POST['value'])) {
    $valueToCheck = mysqli_real_escape_string($conn, $_POST['value']);

    // SQL query pentru a verifica dacă numele se găsește în tabela blacklist
    $query = "SELECT * FROM blacklist WHERE name = '$valueToCheck'";
    
    // Executăm interogarea
    $result = mysqli_query($conn, $query);

    // Verificăm dacă a apărut o eroare în interogare
    if (!$result) {
        echo "error";
    } else {
        // Verificăm dacă numele se găsește în tabela blacklist
        if (mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            $name = $row['name']; // Extragem numele din rezultatul interogării
            $reason = $row['reason']; // Extragem motivul din rezultatul interogării
            echo "found:$name:$reason"; // Returnăm "found", numele și motivul
        } else {
            echo "not_found";
        }
    }

    // Închidem conexiunea la baza de date
    mysqli_close($conn);
} else {
    echo "error";
}
?>
