<?php

require_once "connect.php";

$output = '';


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $action = $_POST['export_action'];

    if ($action == 'export_clients') {

        function cleanData(&$str)
        {
            $str = preg_replace("/\t/", "\\t", $str);
            $str = preg_replace("/\r?\n/", "\\n", $str);
            if (strstr($str, '"')) $str = '"' . str_replace('"', '""', $str) . '"';
        }

        $filename = "shanny_events_clients_" . date('Ymd') . ".xls";

        header("Content-Disposition: attachment; filename=\"$filename\"");
        header("Content-Type: application/vnd.ms-excel");

        $flag = false;
        $sqlClients = "SELECT first_name, last_name, email, phone FROM users;";
        $clientsResult = mysqli_query($conn, $sqlClients);

        while (false !== ($row = mysqli_fetch_assoc($clientsResult))) {
            if (!$flag) {

                echo implode("\t", array_keys($row)) . "\r\n";
                $flag = true;
            }

            array_walk($row, __NAMESPACE__ . '\cleanData');
            echo implode("\t", array_values($row)) . "\r\n";
        }
        exit;
    }

}


?>
