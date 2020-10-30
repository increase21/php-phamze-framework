<?php

class auth
{
    protected $token;
    private $required;

    public function __construct($required = false)
    {
        $header = getallheaders();
        $this->token = @$header['Authorization'];
        $this->required = $required;
    }

    // check Authorization header
    public function CheckToken()
    {
        //   check if the request contains token
        if (strlen($this->token) !== 10) {
            helper::Output_Error(401, 'Invalid authorization');

            exit;
        }
        //   check if token check is not required
        if (!$this->required) {
            return json_encode(['status' => 'not required']);
        }
        //Query statement prepared
        $sql = sprintf("SELECT `email`,`username`,`password`,`token`,`status`, (SELECT `name` FROM `user_types` WHERE `id`=`users`.`user_types_id`) AS `right` FROM `users` WHERE `token` = '%s'", $this->token);
        $check_key = db::DbQuery($sql);

        // check result set if it returns query error
        if (array_key_exists('error', $check_key)) {
            helper::Output_Error(500);

            exit;
        }
        // check if there is no record
        if (!count($check_key)) {
            return helper::Output_Error(401, 'Invalid authorization');

            exit;
        }

        return $check_key[0];
    }
}
