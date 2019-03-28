<?php
session_start();

require_once '../backend/auth.php';

$logged_user = new Auth();

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

        $filename = "shanny_events_clients_" . date('Ymd') . ".xlsx";

        header("Content-Disposition: attachment; filename=$filename");
        header("Content-Type: application/vnd.ms-excel");

        $flag = false;

        $clientsResult = $logged_user->getAllClients();

        while (false !== ($row = $clientsResult)) {
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
