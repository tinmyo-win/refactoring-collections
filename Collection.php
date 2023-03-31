<?php

include('Arr.php');

class Collection implements ArrayAccess, Countable
{
  protected $items;

  public function __construct($items = [])
  {
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

  public static function make($items)
  {
    return new static($items);
  }

  public function map($callback)
  {
    return new static(Arr::map($this->items, $callback));
  }

  public function filter($callback)
  {
    return new static(array_filter($this->items, $callback));
  }

  public function all()
  {
    return $this->items;
  }

  public function collapse()
  {
    return new static(Arr::collapse($this->items));
  }

  public function toArray()
  {
    return $this->items;
  }

  public function reduce($callback, $initial = null)
  {
    $accumulator = $initial;
    foreach ($this->items as $item) {
      $accumulator = $callback($accumulator, $item);
    }

    return $accumulator;
  }

  public function sum($callback = null)
  {
    if (is_null($callback)) {
      $callback = $this->identity();
    }
    return $this->reduce(function ($total, $item) use ($callback) {
      return $total + $callback($item);
    }, 0);
  }

  public function last(callable $callback = null, $default = null)
  {
    return Arr::last($this->items, $callback, $default);
  }

  public function reverse()
  {
    return new static(array_reverse($this->items, true));
  }

  public function values()
  {
    return new static(array_values($this->items));
  }

  protected function identity()
  {
    return function ($value) {
      return $value;
    };
  }

  public function pluck($value, $key = null)
  {
    return new static(Arr::pluck($this->items, $value, $key));
  }

  public function get($key, $default = null)
  {
    if (array_key_exists($key, $this->items)) {
      return $this->items[$key];
    }

    return value($default);
  }

  public function first(callable $callback = null, $default = null)
  {
    return Arr::first($this->items, $callback, $default);
  }

  public function implode($value, $glue = null)
  {
    $first = $this->first();

    if (is_array($first) || (is_object($first) && !$first instanceof Stringable)) {
      return implode($glue ?? '', $this->pluck($value)->all());
    }

    return implode($value ?? '', $this->items);
  }

  public function sortBy($callback, $options = SORT_REGULAR, $descending = false)
  {
    if (is_array($callback) && !is_callable($callback)) {
      return $this->sortByMany($callback);
    }

    $results = [];

    $callback = $this->valueRetriever($callback);

    foreach ($this->items as $key => $value) {
      $results[$key] = $callback($value, $key);
    }

    $descending ? arsort($results, $options)
      : asort($results, $options);

    foreach (array_keys($results) as $key) {
      $results[$key] = $this->items[$key];
    }

    return new static($results);
  }

  protected function sortByMany(array $comparisons = [])
  {
    $items = $this->items;

    usort($items, function ($a, $b) use ($comparisons) {
      foreach ($comparisons as $comparison) {
        $comparison = Arr::wrap($comparison);

        $prop = $comparison[0];

        $ascending = Arr::get($comparison, 1, true) === true ||
          Arr::get($comparison, 1, true) === 'asc';

        $result = 0;

        if (!is_string($prop) && is_callable($prop)) {
          $result = $prop($a, $b);
        } else {
          $values = [data_get($a, $prop), data_get($b, $prop)];

          if (!$ascending) {
            $values = array_reverse($values);
          }

          $result = $values[0] <=> $values[1];
        }

        if ($result === 0) {
          continue;
        }

        return $result;
      }
    });

    return new static($items);
  }

  protected function valueRetriever($value)
  {
    if ($this->useAsCallable($value)) {
      return $value;
    }

    return function ($item) use ($value) {
      return data_get($item, $value);
    };
  }

  protected function useAsCallable($value)
  {
    return !is_string($value) && is_callable($value);
  }

  public function sortByDesc($callback, $options = SORT_REGULAR)
  {
    return $this->sortBy($callback, $options, true);
  }

  public function zip(array $items)
  {
    $arrayableItems = array_map(function ($items) {
      return $items;
    }, func_get_args());

    $params = array_merge([function () {
      return new static(func_get_args());
    }, $this->items], $arrayableItems);

    return new static(array_map(...$params));
  }

  public function groupBy($groupBy, $preserveKeys = false)
  {
    if (!$this->useAsCallable($groupBy) && is_array($groupBy)) {
      $nextGroups = $groupBy;

      $groupBy = array_shift($nextGroups);
    }

    $groupBy = $this->valueRetriever($groupBy);

    $results = [];

    foreach ($this->items as $key => $value) {
      $groupKeys = $groupBy($value, $key);

      if (!is_array($groupKeys)) {
        $groupKeys = [$groupKeys];
      }

      foreach ($groupKeys as $groupKey) {
        $groupKey = is_bool($groupKey) ? (int) $groupKey : $groupKey;

        if (!array_key_exists($groupKey, $results)) {
          $results[$groupKey] = new static;
        }

        $results[$groupKey]->offsetSet($preserveKeys ? $key : null, $value);
      }
    }

    $result = new static($results);

    if (!empty($nextGroups)) {
      return $result->groupBy($nextGroups, $preserveKeys);
    }

    return $result;
  }

  public function min($callback = null)
  {
      $callback = $this->valueRetriever($callback);

      return $this->map(function ($value) use ($callback) {
          return $callback($value);
      })->filter(function ($value) {
          return ! is_null($value);
      })->reduce(function ($result, $value) {
          return is_null($result) || $value < $result ? $value : $result;
      });
  }
}
