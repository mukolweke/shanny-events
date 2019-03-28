<?php

require_once 'connect.php';

class Auth
{
    private $conn;
    private $email_err;
    private $password_err;

    public function __construct()
    {
        $database = new Database();
        $db = $database->dbConnect();
        $this->conn = $db;
        $this->email_err = $this->password_err = '';
    }

    public function runQuery($sql)
    {
        $stmt = mysqli_prepare($this->conn, $sql);

        return $stmt;
    }

    //function for the registration of the users
    public function register($fname, $lname, $email, $phone, $pass, $utype)
    {
        try {
            $stmt = $this->runQuery("INSERT INTO users(first_name, last_name, email, phone, password, user_type)
                  VALUES('$fname', '$lname', '$email', '$phone', '$pass', $utype)");

            if (mysqli_stmt_execute($stmt)) {
                return true;
            } else {
                return false;
            }
        } catch (mysqli_sql_exception $ex) {
            echo $ex->getMessage();
        }
    }

    //function for user login
    public function login($email, $pass)
    {
        try {
            $stmt = $this->runQuery("SELECT id, email, password, user_type FROM users WHERE email = '$email' AND deleted_at IS NULL OR deleted_at = ''");

            if (mysqli_stmt_execute($stmt)) {
                mysqli_stmt_store_result($stmt);

                if (mysqli_stmt_num_rows($stmt) == 1) {
                    mysqli_stmt_bind_result($stmt, $id, $email, $hashed_password, $user_type);

                    if (mysqli_stmt_fetch($stmt)) {
                        // Password is correct, so start a new session
                        if (password_verify($pass, $hashed_password)) {
                            session_start();

                            // Store data in session variables
                            $_SESSION["loggedin"] = true;
                            $_SESSION["id"] = $id;
                            $_SESSION["email"] = $email;
                            $_SESSION["user_type"] = $user_type;

                            return true;
                        } else {
                            $this->password_err = "The password you entered was not valid.";
                            return false;
                        }
                    }
                } else {
                    $this->email_err = "No account found with that email address.";
                    return false;
                }
            }

        } catch (mysqli_sql_exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function is_logged_in()
    {
        if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
            return true;
        } else {
            return false;
        }
    }

    public function redirect($url)
    {
        header("Location: $url");
    }

    public function logout()
    {
        session_destroy();

        $_SESSION['loggedin'] = false;
    }

    public function getClientInformation($userId)
    {
        try {
            $stmt = $this->runQuery("SELECT * FROM users WHERE id = $userId");

            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);

            while ($row = mysqli_fetch_assoc($result)) {
                return $row;
            }

        } catch (mysqli_sql_exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function editClientInformation($user_id, $fname, $lname, $mail, $phone)
    {
        try {
            $stmt = $this->runQuery("UPDATE users SET first_name = '$fname', last_name = '$lname', email = '$mail', phone = '$phone' WHERE id='$user_id'");

            if (mysqli_stmt_execute($stmt)) {
                return true;
            }

            return false;
        } catch (mysqli_sql_exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function editUserPassword($user_id, $p_pass, $new_pass)
    {
        $stmt = $this->runQuery("SELECT password FROM users WHERE id = '$user_id'");

        if (mysqli_stmt_execute($stmt)) {
            mysqli_stmt_store_result($stmt);

            if (mysqli_stmt_num_rows($stmt) == 1) {
                mysqli_stmt_bind_result($stmt, $hashed_pass);

                if (mysqli_stmt_fetch($stmt)) {
                    if (password_verify($p_pass, $hashed_pass)) {
                        $stmt = $this->runQuery("UPDATE users SET password = '$new_pass' WHERE id='$user_id'");

                        if (mysqli_stmt_execute($stmt)) {
                            return true;
                        }

                        return false;
                    }
                }
            }
        }
    }

    public function deleteAccount($userId)
    {
        $deleted_date = date("Y/m/d"); // today's date

        $stmt = $this->runQuery("UPDATE users SET deleted_at = '$deleted_date' WHERE id='$userId'");

        if (mysqli_stmt_execute($stmt)) {
            return true;
        }

        return false;
    }

    public function getAllEvents($userId)
    {
        $stmt = $this->runQuery("SELECT id, name, location, date, people_count, total_cost, status, total_bal FROM events WHERE user_id = $userId AND deleted_at IS NULL OR deleted_at = ''");

        if (mysqli_stmt_execute($stmt)) {

            mysqli_stmt_bind_result($stmt, $id, $name, $location, $date, $people_count, $total_cost, $status, $total_bal);

            while (mysqli_stmt_fetch($stmt)) {
                $events[] = ['id' => $id, 'name' => $name, 'location' => $location, 'date' => $date, 'people_count' => $people_count, 'total_cost' => $total_cost, 'status' => $status, 'total_bal' => $total_bal];
            }

            if (empty($events)) {
                return false;
            } else {
                return $events;
            }
        }
    }

    function statusName($status)
    {
        if ($status == 1) {
            return "Completed";
        } elseif ($status == 2) {
            return "Ongoing";
        } elseif ($status == 3) {
            return "New Request";
        } elseif ($status == 4) {
            return "Rejected";
        } else {
            return "Unprocessed";
        }
    }

    public function addRequestEvent($event_name, $event_location, $event_date, $event_people, $event_costs)
    {
        $user_id = $_SESSION['id'];

        $stmt = $this->runQuery("INSERT INTO events (name, location, date, people_count, total_cost, status, user_id, total_bal) VALUES ('$event_name', '$event_location', '$event_date', $event_people, '$event_costs', 3, $user_id, '$event_costs')");

        if (mysqli_stmt_execute($stmt)) {
            return true;
        } else {
            return false;
        }
    }

    public function showEditRequestDetails($event_id)
    {
        try {
            $stmt = $this->runQuery("SELECT id, name, location, date, people_count, total_cost FROM events WHERE id = $event_id");

            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);

            while ($row = mysqli_fetch_assoc($result)) {
                return $row;
            }

        } catch (mysqli_sql_exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function editRequestEvent($eventId, $event_name, $event_location, $event_date, $event_people, $event_costs)
    {
        if (empty($event_date)) {
            $event_date_edit = $_POST['event_date_edit'];
        } else {
            $event_date_edit = $event_date;
        }

        $stmt = $this->runQuery("UPDATE events SET name = '$event_name', location = '$event_location', date = '$event_date_edit', people_count = $event_people, total_cost = '$event_costs' WHERE id = $eventId");

        if (mysqli_stmt_execute($stmt)) {
            return true;
        } else {
            return false;
        }
    }

    public function deleteEvent($eventId)
    {
        $deleted_date = date("Y/m/d"); // today's date

        $stmt = $this->runQuery("UPDATE events SET deleted_at = '$deleted_date' WHERE id= $eventId");

        if (mysqli_stmt_execute($stmt)) {
            return true;
        } else {
            return false;
        }
    }

    public function deleteEventTask($eventTaskId)
    {
        $deleted_date = date("Y/m/d"); // today's date

        $stmt = $this->runQuery("UPDATE events_task SET deleted_at = '$deleted_date' WHERE id= $eventTaskId");

        if (mysqli_stmt_execute($stmt)) {
            return true;
        } else {
            return false;
        }
    }

    public function getEventsByStatus($statusId)
    {
        $stmt = $this->runQuery("SELECT id, name, location, date, people_count, total_cost, status, total_bal FROM events WHERE status = $statusId AND deleted_at IS NULL OR deleted_at = ''");

        if (mysqli_stmt_execute($stmt)) {

            mysqli_stmt_bind_result($stmt, $id, $name, $location, $date, $people_count, $total_cost, $status, $total_bal);

            while (mysqli_stmt_fetch($stmt)) {
                $events[] = ['id' => $id, 'name' => $name, 'location' => $location, 'date' => $date, 'people_count' => $people_count, 'total_cost' => $total_cost, 'status' => $status, 'total_bal' => $total_bal];
            }

            if (empty($events)) {
                return false;
            } else {
                return $events;
            }
        }
    }

    public function viewEventDetails($eventId)
    {
        try {
            $stmt = $this->runQuery("SELECT * FROM events WHERE id = $eventId");

            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);

            while ($row = mysqli_fetch_assoc($result)) {
                return $row;
            }

        } catch (mysqli_sql_exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function getStatusDetails($status_id)
    {
        try {
            $stmt = $this->runQuery("SELECT * FROM events_status WHERE id = $status_id");

            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);

            while ($row = mysqli_fetch_assoc($result)) {
                return $row;
            }

        } catch (mysqli_sql_exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function getEventsSubTask($eventId)
    {
        $stmt = $this->runQuery("SELECT id, name, description, cost, event_id, status FROM events_task WHERE event_id = $eventId AND deleted_at IS NULL OR deleted_at = ''");
        if (mysqli_stmt_execute($stmt)) {

            mysqli_stmt_bind_result($stmt, $id, $name, $description, $cost, $event_id, $status);

            while (mysqli_stmt_fetch($stmt)) {
                $eventSubTasks[] = ['id' => $id, 'name' => $name, 'description' => $description, 'cost' => $cost, 'event_id' => $event_id, 'status' => $status];
            }

            if (empty($eventSubTasks)) {
                return false;
            } else {
                return $eventSubTasks;
            }
        }
    }

    public function eventAction($action, $eventId)
    {
        if ($action == 'accept') {
            $stmt = $this->runQuery("UPDATE events SET status = 2 WHERE id = $eventId");
        } elseif ($action == 'reject') {
            $stmt = $this->runQuery("UPDATE events SET status = 4 WHERE id = $eventId");
        } elseif ($action == 'done') {
            $stmt = $this->runQuery("UPDATE events SET status = 1 WHERE id = $eventId");
        }

        if (mysqli_stmt_execute($stmt)) {
            return true;
        } else {
            return false;
        }
    }

    public function addEventTask($name, $description, $cost, $event_id)
    {
        $stmt = $this->runQuery("INSERT INTO events_task (name, description, cost, event_id) VALUES ('$name', '$description', $cost, $event_id)");

        if (mysqli_stmt_execute($stmt)) {
            return true;
        } else {
            return false;
        }
    }

    public function addNotification($from, $to, $message, $event)
    {
        $status = 1;

        $stmt = $this->runQuery("INSERT INTO notifications (from_id, to_id, message, event, status) values ('$from', '$to', '$message', $event, $status)");

        if (mysqli_stmt_execute($stmt)) {
            return true;
        } else {
            return false;
        }
    }

    public function updateBalance($amount, $eventId)
    {
        $stmt = $this->runQuery("UPDATE events SET total_bal = '$amount' WHERE id='$eventId'");

        mysqli_stmt_execute($stmt);
    }

    public function getAllUsersByType($userType)
    {
        $stmt = $this->runQuery("SELECT id, first_name, last_name, email, phone FROM users WHERE user_type = $userType AND deleted_at IS NULL OR deleted_at = ''");

        if (mysqli_stmt_execute($stmt)) {

            mysqli_stmt_bind_result($stmt, $id, $first_name, $last_name, $email, $phone);

            while (mysqli_stmt_fetch($stmt)) {
                $users[] = ['id' => $id, 'first_name' => $first_name, 'last_name' => $last_name,'email' => $email, 'phone' => $phone];
            }

            if (empty($users)) {
                return false;
            } else {
                return $users;
            }
        }
    }

    public function countUserEvents($userId)
    {
        $stmt = $this->runQuery("SELECT COUNT(*) AS eventCount FROM events WHERE user_id = $userId");

        mysqli_stmt_execute($stmt);

        mysqli_stmt_bind_result($stmt, $eventCount);

        mysqli_stmt_fetch($stmt);

        return  $eventCount;
    }

    public function getAllClients()
    {
        $stmt = $this->runQuery("SELECT first_name, last_name, email, phone FROM users WHERE NOT id = 1 ");

        if (mysqli_stmt_execute($stmt)) {

            mysqli_stmt_bind_result($stmt, $id, $first_name, $last_name, $email, $phone);

            while (mysqli_stmt_fetch($stmt)) {
                $users[] = ['id' => $id, 'first_name' => $first_name, 'last_name' => $last_name,'email' => $email, 'phone' => $phone];
            }

            if (empty($users)) {
                return false;
            } else {
                return $users;
            }
        }
    }
}


?>