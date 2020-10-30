<?php

class db
{
    private static $host;
    private static $user;
    private static $pass;
    private static $db_name;
    public static $conn;
    private static $ex;

    // function to establis a database connection
    public static function DbCreateConnection($hostname = null, $username = null, $password = null, $database = null)
    {
        self::$host = is_null($hostname) ? DB_HOST : $hostname;
        self::$user = is_null($username) ? DB_USER : $username;
        self::$pass = is_null($password) ? DB_PASS : $password;
        self::$db_name = is_null($database) ? DB_NAME : $database;
        self::$conn = new mysqli(self::$host, self::$user, self::$pass, self::$db_name);
        // check if there is an error in the connection
        if (mysqli_connect_errno()) {
            exit(mysqli_connect_error());
        }
    }

    // function to execute Raw Query statement
    public static function DbQuery($statemet, $type = null)
    {  //if there's not connection, create connection
        if (!self::$conn) {
            self::DbCreateConnection();
        }
        if (self::$ex = self::$conn->query($statemet)) {
            // check if the query type is present and it's not a select query
            if (isset($type) && $type !== 'select') {
                return self::$ex;
            }
            // check if there is no roll fetched;
            if (self::$ex->num_rows === 0) {
                return [];
            }
            // get result
            while ($row = self::$ex->fetch_object()) {
                $arr[] = $row;
            }

            return $arr;
        } else {
            // return the error
            return ['error' => mysqli_error(self::$conn)];
        }
    }

    public static function DbGetNextPage($page)
    {
        return is_numeric($page) && $page > 1 ? ($page - 1) * 50 : 0;
    }

    public static function DbEscapeString($input)
    {
        //if there's not connection, create connection
        if (!self::$conn) {
            self::DbCreateConnection();
        }

        return self::$conn->real_escape_string(trim($input));
    }
}
