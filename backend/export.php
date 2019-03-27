<?php

require_once "connect.php";

$output = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    //export actions
    $active_page = 'clients';

    $sqlClients = "SELECT * FROM users WHERE user_id = 2 AND deleted_at IS NULL OR deleted_at = ''";

    $clientsResult = mysqli_query($conn, $sqlClients);

    if (mysqli_num_rows($clientsResult) > 0) {
        $output .= '
                   <table class="table" bordered="1">  
                                    <tr>  
                                         <th>Name</th>  
                                         <th>Address</th>  
                                         <th>City</th>  
                       <th>Postal Code</th>
                       <th>Country</th>
                                    </tr>
                  ';
        while ($row = mysqli_fetch_array($clientsResult)) {
            $output .= '
                    <tr>  
                                         <td>' . $row["CustomerName"] . '</td>  
                                         <td>' . $row["Address"] . '</td>  
                                         <td>' . $row["City"] . '</td>  
                       <td>' . $row["PostalCode"] . '</td>  
                       <td>' . $row["Country"] . '</td>
                                    </tr>
                   ';
        }
        $output .= '</table>';
        header('Content-Type: application/xls');
        header('Content-Disposition: attachment; filename=download.xls');
        echo $output;

    }
}

?>