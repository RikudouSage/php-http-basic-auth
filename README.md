# HTTP Basic auth for php

This module does not require any framework and is really simple.

## Installation

`composer require rikudou/http-basic-auth`

## Usage

```php
<?php

use Rikudou\HttpBasicAuth;

// message displayed in standard browser window
$message = "Please input your username and password!"; 

// add the message to the object
$auth = new HttpBasicAuth($message);

// set the callback, it accepts two parameters, username and password and should
// return true (auth succeeded) or false (auth failed)
// in callback you can do pretty much everything, connect to your db, call classes, etc.
// the callback can be any callable (see http://php.net/manual/en/language.types.callable.php)
$auth->setCallback(function($username, $password) {
   if($username == "foo" && $password == "bar") {
     return true;
   }
   return false;
});

$result = $auth->auth();

if($result) { // auth succeeded
  
} else { // auth failed
  
}

```

The callback can be also set in the constructor:

```php
<?php

use Rikudou\HttpBasicAuth;

$auth = new HttpBasicAuth("Please, authorize", function($username, $password){
  return true;
});

```

The `auth()` method can throw exceptions when the callback is not supplied or it's not
callable.

```php
<?php

use Rikudou\HttpBasicAuth;

$auth = new HttpBasicAuth("Authorize");

try {
  $auth->auth();
} catch (Exception $exception) {
  var_dump($exception->getCode() == HttpBasicAuth::ERR_NO_CALLBACK); // true
}

$auth->setCallback(1); // invalid callback

try {
  $auth->auth();
} catch (Exception $exception) {
  var_dump($exception->getCode() == HttpBasicAuth::ERR_CALLBACK_NOT_CALLABLE); // true
}

```
