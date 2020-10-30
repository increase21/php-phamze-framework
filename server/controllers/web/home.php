<?php

class home extends ServerController
{
    public function __construct()
    {
    }

    public function index()
    {
        $data['page_title'] = 'name';
        $this->loadView('index', $data);
    }
}
