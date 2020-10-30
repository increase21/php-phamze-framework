<?php

class login extends ServerController
{
    public $userData;

    public function __construct()
    {
        // if auth is required, run auth here
        $Auth = new auth(true);
        $this->userData = $Auth->CheckToken();
    }

    public function index()
    {
        //if the post data is JSON, use this
        $body = $this->getPostData()->json;
        //if the post data is form data, use this
        $body = $this->getPostData()->post;
        //if a GET request and you want the query string, use
        //   $page = getQueryString('optional here');
        $email = @$body->email;
        $password = @$body->password;

        if (!validator::IsEmail($email)) {
            return helper::Output_Error(null, 'Invalid email');
        }
        if (!$password || strlen($password) < 5) {
            return helper::Output_Error(null, 'A valid password is required');
        }

        $sampleModel = $this->loadModel('sample_model');
        $login = $sampleModel->loginUser($email);

        //if error
        if ($login === false) {
            return helper::Output_Error(500);
        }
        //check count
        if (count($DOQuery) === 0) {
            return helper::Output_Error(null, 'Account not found');
        }

        return helper::Output_Success($DOQuery);
    }
}
