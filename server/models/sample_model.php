<?php

class sample_model
{
    public function __construct()
    {
        //do nothing
    }

    public function loginUser($email)
    {
        $dbQuery = db::DbQuery("SELECT * FROM `users` WHERE `email`= $email");
        if (is_array($DOQuery) && array_key_exists('error', $DOQuery)) {
            return false;
        }

        return $DOQuery;
    }
}
