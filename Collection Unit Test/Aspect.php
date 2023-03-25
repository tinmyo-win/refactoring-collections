<?php

class Aspect
{
  public function before($method)
  {
    echo "<i><strong>Start testing " . $method . "</strong></i><br>";
  }

  public function after($method)
  {
    echo "<i><strong>Complete testing " . $method . "</strong></i></br></br>";
  }
}

class Proxy
{
  private $target;
  private $aspect;

  public function __construct($target, $aspect)
  {
    $this->target = $target;
    $this->aspect = $aspect;
  }

  public function __call($method, $args)
  {
    $this->aspect->before($method);
    call_user_func_array([$this->target, $method], $args);
    $this->aspect->after($method);
  }
}
