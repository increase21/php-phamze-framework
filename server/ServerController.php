<?php

class ServerController
{
    public function __construct($method, $additionalPath, $endpoint, $API)
    {
        if ($API) {
            return $this->APIRoute($method, $additionalPath, $endpoint);
        } else {
            return $this->WebRoute($method, $endpoint);
        }
    }

    private function WebRoute($method, $endpoint)
    {
        $className = strval(@$endpoint[0]);
        $methodName = @$endpoint[1];
        if (!$className || $className === '') {
            //check if there's a home page file to run
            if (file_exists(__DIR__.'/controllers/web/home.php')) {
                require_once __DIR__.'/controllers/web/home.php'; //include the file
                //check if the class does not exist
                if (!class_exists('home')) {
                    //throw exception if there's no class home in the file
                    if (APP_DEBUG === true) {
                        throw new Exception('The classname in home.php file is not same name');
                    }
                } else {
                    $rClass = new home(); //new the class
                    if (!method_exists($rClass, 'index')) {
                        //throw exception if there's no class home in the file
                        if (APP_DEBUG === true) {
                            throw new Exception('No index function/method found in controller/web/home.php ');
                        }

                        return;
                    }
                    $rClass->index(); //cal the index function
                }
            } else {
                //throw exception if there's no file name home.php in the controller
                if (APP_DEBUG === true) {
                    throw new Exception('home.php file not found in the controller/web');
                }
            }
        } else {
            //if the class exist
            if (file_exists(__DIR__.'/controllers/web/'.$className.'.php')) {
                require_once __DIR__.'/controllers/web/'.$className.'.php'; //include the file
                //if the class does not exist show error
                if (!class_exists($className)) {
                    //throw exception if there's no class home in the file
                    if (APP_DEBUG === true) {
                        throw new Exception("Class '$className' not found in '$className'.php controller/web");
                    }

                    return;
                }
                $rClass = new $className(); // invoke the constructor
                $rClass->requestMethod = $method; //get request method
             $rClass->requestPage = @$_GET['page']; //get page numner
             //check if the request has a method name and the method exist
             if ($methodName && strlen($methodName) > 0 && method_exists($rClass, $methodName)) {
                 //get all the request params
                 $rParams = count($endpoint) > 2 ? array_values(array_slice($endpoint, 2)) : [];

                 //execute the request
                 return $rClass->$methodName(...$rParams);  //execute the method, pass all params
             } elseif (method_exists($rClass, 'index')) { //if the method name does not exist but there's index method, run the index
                 $methodName = 'index';
                 //get all params
                 $rParams = count($endpoint) > 1 ? array_values(array_slice($endpoint, 1)) : [];

                 return $rClass->$methodName(...$rParams); //execute the method, pass all params
             } else {
                 //if the file does not exist
                 if (APP_DEBUG === true) {
                     throw new Exception("No such file '$className' in controllers");
                 } else {
                     if (APP_SHOW_404 === true) {
                         if ($this->requestMethod === 'get') {
                             $this->loadView('404');
                         } else {
                             http_response_code(404);
                             echo 'Requested resource does not exists';
                         }
                     }
                 }
             }
            } else {
                //if the file does not exist
                if (APP_DEBUG === true) {
                    throw new Exception("No such file '$className' in controllers");
                } else {
                    if (APP_SHOW_404 === true) {
                        if ($this->requestMethod === 'get') {
                            $this->loadView('404');
                        } else {
                            http_response_code(404);
                            echo 'Requested resource does not exists';
                        }
                    }
                }
            }
        }
    }

    private function APIRoute($method, $additionalPath, $endpoint)
    {
        $className = strval(@$endpoint[0]);
        $methodName = @$endpoint[1];
        if (!$className || $className === '') {
            return helper::Output_Error(404, 'endpoint not specified');
        } else {
            //if the class exist
            if (file_exists(__DIR__.'/controllers/api'.$additionalPath.$className.'.php')) {
                require_once __DIR__.'/controllers/api'.$additionalPath.$className.'.php'; //include the file
                //if the class does not exist show error
                if (!class_exists($className)) {
                    //throw exception if there's no class home in the file
                    if (APP_DEBUG === true) {
                        throw new Exception("Class '$className' not found in '$className'.php controller/api");
                    }

                    return;
                }
                $rClass = new $className(); // invoke the constructor
                $rClass->requestMethod = $method; //get request method
             $rClass->requestPage = @$_GET['page']; //get page numner
             //check if the request has a method name and the method exist
             if ($methodName && strlen($methodName) > 0 && method_exists($rClass, $methodName)) {
                 //get all the request params
                 $rParams = count($endpoint) > 2 ? array_values(array_slice($endpoint, 2)) : [];

                 //execute the request
                 return $rClass->$methodName(...$rParams);  //execute the method, pass all params
             } elseif (method_exists($rClass, 'index')) { //if the method name does not exist but there's index method, run the index
                 $methodName = 'index';
                 //get all params
                 $rParams = count($endpoint) > 1 ? array_values(array_slice($endpoint, 1)) : [];

                 return $rClass->$methodName(...$rParams); //execute the method, pass all params
             } else {
                 //if the file does not exist
                 if (APP_DEBUG === true) {
                     throw new Exception("No such file '$className' in controllers/api");
                 }
             }
            } else {
                //if the file does not exist
                if (APP_DEBUG === true) {
                    throw new Exception("No such file '$className' in controllers/api");
                }
            }
        }
    }

    public function loadModel($modelName)
    {
        if (file_exists(__DIR__.'/models/'.$modelName.'.php')) {
            require_once __DIR__.'/models/'.$modelName.'.php';

            return new $modelName();
        } else {
            if (APP_DEBUG === true) {
                throw new Exception('The classname in home.php file is not same name');
            }
        }
    }

    public function loadView($path, $data = null)
    {
        require_once __DIR__.'/../views/layouts/header.php';
        require_once __DIR__.'/../views/'.$path.'.php';
        require_once __DIR__.'/../views/layouts/footer.php';
    }

    public function renderView($path, $data = null)
    {
        require_once __DIR__.'/../views/'.$path.'.php';
    }

    public function redirect($url = null)
    {
        if (!$url) {
            return header('Location: '.base_url());
        }

        return header('Location: '.$url);
    }

    public function checkLogin()
    {
        if (!@$_SESSION['token']) {
            return $this->redirect(base_url().'/login');
        }
    }

    public function getPostData()
    {
        return (object) [
        'json' => @json_decode(file_get_contents('php://input')),
         'post' => @(object) $_POST,
       ];
    }

    public function getQueryString($name = null)
    {
        return $name ? @$_GET[$name] : $_GET;
    }
}
