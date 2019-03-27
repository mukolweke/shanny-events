<?php

<< HEAD
$conn = mysqli_connect('localhost', 'root', '','shanny-events');
=======
class Database
{
    // database connection variables
    private $host = "localhost";
    private $username = "homestead";
    private $password = "secret";
    private $database = "shanny-events";
>>>>>>> 92ba4b8859960aee06004baaf82f8dfb2be80196

    public $conn;

    // database connection function
    public function dbConnect()
    {
        $this->conn = null;
        try {
            $this->conn = mysqli_connect($this->host, $this->username, $this->password, $this->database);
        } catch (mysqli_sql_exception $exception) {
            echo "Connection error: " . $exception->getMessage();
        }

        return $this->conn;
    }
}

?>