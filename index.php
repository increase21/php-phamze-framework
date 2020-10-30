<?php

include_once __DIR__.'/.config';

function base_url()
{
    if (APP_ENV === 'production') {
        return '//'.$_SERVER['HTTP_HOST'];
    } else {
        return '//'.$_SERVER['HTTP_HOST'].'/'.LOCAL_FOLDER_NAME;
    }
}

function runPagination($totalPages = 0, $activePage = 0, $showLimit = 0)
{
    if ($totalPages <= $showLimit) {
        return 1;
    } elseif (($totalPages - $activePage) > ($showLimit - 1)) {
        return $activePage - 2 > 0 ? $activePage - 2 : $activePage - 1 > 0 ? $activePage - 1 : $activePage;
    } elseif (($totalPages - $activePage) < ($showLimit - 1)) {
        return  $totalPages - 6;
    } else {
        return $activePage - 1;
    }
}

function logFile($file)
{
    $date = date('d-m-Y');
    error_log($file.PHP_EOL, 3, __DIR__.'/logs/'.$date.'_log.txt');
}

try {
    include_once __DIR__.'/server/helpers/db.php';
    include_once __DIR__.'/server/ServerController.php';
    include_once __DIR__.'/server/helpers/validator.php';
    include_once __DIR__.'/server/helpers/helper.php';

    $RM = strtolower($_SERVER['REQUEST_METHOD']); //get the method name
    $url_path = parse_url(APP_ENV === 'production' ? $_SERVER['REQUEST_URI'] : substr($_SERVER['REQUEST_URI'], strpos($_SERVER['REQUEST_URI'], LOCAL_FOLDER_NAME.'/') + strlen(LOCAL_FOLDER_NAME)), PHP_URL_PATH);

    $path = preg_replace('/^\/+|\/+$/', '', $url_path); //sanitize the request URL
    $endpoint = explode('/', $path); // split the url
    $starter = strval(@$endpoint[0]);
    //check the route
    $additionalPath = '/'; //for additional path to add on API route
    //for API requests
    if ($starter === 'api') {
        $apiPath = substr($path, 4); //remove the api
        $endpoint = explode('/', $apiPath); //split the URL again
        //if API versioning is true, remove the first index from enpoint array
        if (API_VERSIONING === true) {
            $additionalPath .= @$endpoint[0].'/';
            array_shift($endpoint);
        }
        //check if there's no endpoint specified
        if (!@$endpoint[0] || @$endpoint[0] === '') {
            return helper::Output_Error(404, 'endpoint not specified');
        }
        include_once __DIR__.'/server/controllers/api/auth/auth.php';
    } else {
        if (!isset($_SESSION)) {
            session_start();
        }
    }
    new ServerController($RM, $additionalPath, $endpoint, $starter === 'api' ? true : false);
} catch (Exception $e) {
    if (APP_DEBUG === true) {
        echo $e;
    }
}
