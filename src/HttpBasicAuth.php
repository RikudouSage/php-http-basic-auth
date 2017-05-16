<?php

namespace Rikudou;

class HttpBasicAuth {

  const ERR_NO_CALLBACK = 1;
  const ERR_CALLBACK_NOT_CALLABLE = 2;

  /** @var callable $callback */
  protected $callback;
  protected $message;

  public function __construct($message, $callback = NULL) {
    $this->message = $message;
    $this->callback = $callback;
  }

  public function setCallback($callback) {
    $this->callback = $callback;
    return $this;
  }

  public function auth() {
    if (is_null($this->callback)) {
      throw new \Exception("The callback must be specified.", static::ERR_NO_CALLBACK);
    }

    $username = "";
    $password = "";

    if (isset($_SERVER['PHP_AUTH_USER'])) {
      $username = $_SERVER['PHP_AUTH_USER'];
    }

    if (isset($_SERVER['PHP_AUTH_PW'])) {
      $password = $_SERVER['PHP_AUTH_PW'];
    }

    if (!$username && !$password && isset($_SERVER['HTTP_AUTHORIZATION'])) {
      if (strpos(strtolower($_SERVER['HTTP_AUTHORIZATION']), 'basic') === 0) {
        list($username, $password) = explode(':', base64_decode(substr($_SERVER['HTTP_AUTHORIZATION'], 6)));
      }
    }

    if(!is_callable($this->callback)) {
      throw new \Exception("The specified callback is not callable.", static::ERR_CALLBACK_NOT_CALLABLE);
    }

    if($username) {
      return call_user_func($this->callback, $username, $password);
    } else {
      header("WWW-Authenticate: Basic realm=\"{$this->message}\"", true, 401);
      return false;
    }

  }

}