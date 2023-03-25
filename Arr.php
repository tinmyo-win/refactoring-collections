<?php

class Arr
{
  public static function collapse($array)
  {

      $results = [];

      foreach ($array as $values) {
          if ($values instanceof Collection) {
              echo "Collection";
              $values = $values->all();
          } elseif (! is_array($values)) {
              echo "Not Array";
              continue;
          }
          $results[] = $values;
      }

      return array_merge([], ...$results);
  }

  public static function first($array, callable $callback = null, $default = null)
  {
    if(is_null($callback)) {
        if(empty($array)) {
            return $default;
        }

        foreach ($array as $item) {
            return $item;
        }
    }

    foreach ($array as $key => $value) {
        if($callback($value, $key)) {
            return $value;
        }
    }

    return $default;
  }

  public static function last($array, callable $callback = null, $default = null)
  {
      if (is_null($callback)) {
          return empty($array) ? $default : end($array);
      }

      return static::first(array_reverse($array, true), $callback, $default);
  }
}
