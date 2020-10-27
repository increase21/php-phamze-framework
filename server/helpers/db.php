<?php

class db
{
    private $host;
    private $user;
    private $pass;
    private $db_name;
    public $conn;
    private $ex;

    // function to establis a database connection
    public function DbCreateConnection($hostname = null, $username = null, $password = null, $database = null)
    {
        $this->host = is_null($hostname) ? DB_HOST : $hostname;
        $this->user = is_null($username) ? DB_USER : $username;
        $this->pass = is_null($password) ? DB_PASS : $password;
        $this->db_name = is_null($database) ? DB_NAME : $database;
        $this->conn = new mysqli($this->host, $this->user, $this->pass, $this->db_name);
        // check if there is an error in the connection
        if (mysqli_connect_errno()) {
            exit(mysqli_connect_error());
        }
    }

    // function to execute Raw Query statement
    public function DbQuery($statemet, $type = null)
    {  //if there's not connection, create connection
        if (!$this->conn) {
            $this->DbCreateConnection();
        }
        if ($this->ex = $this->conn->query($statemet)) {
            // check if the query type is present and it's not a select query
            if (isset($type) && $type !== 'select') {
                return $this->ex;
            }
            // check if there is no roll fetched;
            if ($this->ex->num_rows === 0) {
                return [];
            }
            // get result
            while ($row = $this->ex->fetch_object()) {
                $arr[] = $row;
            }

            return $arr;
        } else {
            // return the error
            return ['error' => mysqli_error($this->conn)];
        }
    }

    public function DbGetNextPage($page)
    {
        return is_numeric($page) && $page > 1 ? ($page - 1) * 50 : 0;
    }

    public function DbEscapeString($input)
    {
        //if there's not connection, create connection
        if (!$this->conn) {
            $this->DbCreateConnection();
        }

        return $this->conn->real_escape_string($input);
    }

    public function DbGetInputValueString($Obj, $field)
    {
        if (!is_object($Obj)) {
            return false;
        }
        if (!is_string($field)) {
            return false;
        }

        return property_exists($Obj, $field) && is_string($Obj->$field) ? $this->conn->real_escape_string(trim($Obj->$field)) : '';
    }
}
