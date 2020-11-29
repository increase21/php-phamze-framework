# Phonix PHP Framework
### Author 
Name: Increase Nkanta
Git: https://github.com/increase21

# Brief 
Phonix is a PHP library for PHP programmers who hate complex coding.

## How it works

The framework is structured to work as a normal MVC framework. It has Assets, Logs, Server, Views folders. Assets folder contains all the asset files for the applications. File like CSS, JS, IMG ETC. 
Logs folder contains all the file logs using:

```php
logFile('file-to-log');
```
Server folder has serveral other folders. This is where the backend codes reside. Controllers, Helpers, Models are few folders literally important in server folder. Models folder houses the database queries for the application. Helpers folder houses validations, and connectivity. Controllers folder houses functions that interact with the application. The controller folder has two sub folders called, WEB and API.

# A Quick Start

### Controller:WEB
let's have a file called home.php in server/controllers/web

```php

class home extends ServerController
{
    public function __construct()
    {
    }

    public function index()
    {
        $data['page_title'] = 'Welcome to my first sample page';
        $this->loadView('index', $data);
    }
}
```
### Quick Explanation
https://localhost/foldername or https://domain.com
The above URLs will call the home class and the home class will execute the index function

let's have another file called user.php in server/controllers/web

```php

class user extends ServerController
{
    public function __construct()
    {
    }

    public function index()
    {
        $data['page_title'] = 'Welcome to my first sample page';
        $this->loadView('index', $data);
    }
    public function about()
    {
        $data['page_title'] = 'Me dashboard';
        $this->loadView('index', $data);
    }
}
```

https://localhost/foldername/user or https://domain.com/user
The abouve URLs will call the user class and the user class will execute the index function

https://localhost/foldername/user/about or https://domain.com/user/about
The abouve URLs will call the user class and the user class will execute the about function

https://localhost/foldername/user/about/123/HITYT or https://domain.com/user/about/123/HITYT
The abouve URLs will call the user class and the user class will execute the about function then will pass /123/HITYT as parameters to about functions. For the about function to receive the parameters, it must expect them like below:


```php

class user extends ServerController
{
    public function __construct()
    {
    }

    public function index()
    {
        $data['page_title'] = 'Welcome to my first sample page';
        $this->loadView('index', $data);
    }
    public function about($param1, $param2)
    {
        $data['page_title'] = 'Me dashboard';
        $this->loadView('index', $data);
    }
}
```


### Controller:API

### Quick Explanation

let's have another file called user.php in server/controllers/api

```php

class user extends ServerController
{
    public function __construct()
    {
    }

    public function index()
    {
        $jsonPost = $this->postData()
        $email = @$jsonPost->email;
        $passowrd = @$jsonPost->password;

        if(!$email || !validator::IsEmail($email)){
           return helper::Output_Error(null, "Invalid email")
        }
    }
}
```

https://localhost/foldername/api/user or https://domain.com/api/user
The abouve URLs will call the user class and the user class will execute the index function. $this->postData()->json returns all the json post data. helpers, validator and db are loaded as static class functions to the applications. can be use anywhere.

# Available Methods

| Method  | How it works | param |
| ------------- | ------------- |-----------|
| $this->loadModel  | For loading in database file stores in model folder  | file name (required)
| $this->loadView  | For loading in view/frontend file stores in view folder. This method loads in header and footer  | file name (required)
| $this->renderView  | For loading in view/frontend file stores in view folder. This method does not load in header and footer  | file name (required)
| $this->redirect | For redirecting http request| url (required) |
| $this->getPostData | For getting post request payload and query string | payload type (optional/required) |
| $this->logFile | For logging data to log folder | data to log (required)
# NOTE 
More description...


## Contributing
Pull requests are welcome. For major changes, please open an issue first to discuss what you would like to change.

Please make sure to update tests as appropriate.