<?php
    $searchq = $_GET['q'];

    // Подключение к базе данных
    require_once "../lib/db.php";
    $db = DB::getObject();

    // Запрос к базе данных для поиска городов
    $query = "SELECT name FROM blacklist WHERE name LIKE '%$searchq%'";
    $result_set = $db->query($query);

    $result = array();
    while ($row = $result_set->fetch_assoc()) {
        $result[] = $row['name'];
    }

    echo json_encode($result);
?>
