<?php

  include('Arr.php');

  class Collection implements ArrayAccess, Countable
  {
      protected $items;

      public function __construct($items) {
          $this->items = $items;
      }

      public function offsetExists($key): bool
      {
          return array_key_exists($key, $this->items);
      }

      public function offsetGet($offset)
      {
        return $this->items[$offset];
      }

      public function offsetSet($offset, $value): void
      {
          if ($offset === null) {
              $this->items[] = $value;
          } else {
              $this->items[$offset] = $value;
          }
      }

      public function offsetUnset($offset): void
      {
          unset($this->items[$offset]);
      }

      public function count(): int
      {
        return count($this->items);
      }

      public static function make($items) {
          return new static($items);
      }

      public function map($callback) {
          return new static(array_map($callback, $this->items));
      }

      public function filter($callback) {
          return new static(array_filter( $this->items, $callback));
      }

      public function all() {
        return $this->items;
      }

      public function collapse() {
        return new static(Arr::collapse($this->items));
      }

      public function toArray() {
          return $this->items;
      }

      public function reduce($callback, $initial) {
        $accumulator = $initial;
        foreach($this->items as $item) {
            $accumulator = $callback($accumulator, $item);
        }
    
        return $accumulator;
      }
    
      public function sum ($callback) {
        
          return $this->reduce(function ($total, $item) use ($callback) {
              return $total + $callback($item);
          }, 0);
      }

      public function last(callable $callback = null, $default = null)
      {
        return Arr::last($this->items, $callback, $default);
      }
  }



