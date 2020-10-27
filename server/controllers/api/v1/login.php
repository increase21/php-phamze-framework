<?php

class login extends db
{
    public $userData;

    public function __construct()
    {
        // if auth is required, run auth here
        $Auth = new auth(true);
        $getTokenData = $Auth->CheckToken();
        if (!$getTokenData) {
            exit(helper::Output_Error(401));
            $this->userData = $getTokenData;
        }
    }

    public function index()
    {
        //if the post data is JSON, use
        $body = getPostData()->json;
        //if the post data is form data, use
        $body = getPostData()->post;
        //if a GET request and you want the query string, use
        $page = getQueryString('optional here');
        $email = @$body->name;
        $password = @$body->name;

        if (!validator::IsEmail($email)) {
            return helper::Output_Error(null, 'Invalid email');
        }
        if (!$password || strlen($password) < 5) {
            return helper::Output_Error(null, 'A valid password is required');
        }
        //check the user
        $DOQuery = $this->DbQuery("SELECT * FROM `users` WHERE `email`= $email");
        if (is_array($DOQuery) && array_key_exists('error', $DOQuery)) {
            return helper::Output_Error(500);
        }
        if (count($DOQuery) === 0) {
            return helper::Output_Error(null, 'Account not found');
        }

        return helper::Output_Success($DOQuery);
    }
}
