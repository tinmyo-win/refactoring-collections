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
}
