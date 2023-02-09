<?php
  function map($items, $func) {
    $results = [];
    foreach($items as $item) {
        $results[] = $func($item);
    }
    return $results;
  }

  function filter($items, $func) {
    $result = [];
    foreach ($items as $item) {
        if ($func($item)) {
            $result[] = $item;
        }
    }
    return $result;
  }

  function reduce($items, $callback, $initial) {
    $accumulator = $initial;
    foreach($items as $item) {
        $accumulator = $callback($accumulator, $item);
    }

    return $accumulator;
  }

  function sum ($items, $callback) {
      return reduce($items, function ($total, $item) use ($callback) {
          return $total + $callback($item);
      }, 0);
  }

  function myJoin($items, $callback) {
    return reduce($items, function ($string, $item) use($callback) {
        return $string . $callback($item);
    }, '');
  }

  $customers = [
    ['name' => "Bob", "email" => 'bob@g.com'],
    ['name' => "Alice", "email" => 'alice@g.com'],
    ['name' => "Kyaw", "email" => 'kyaw@g.com'],
    ['name' => "May", "email" => 'may@g.com'],
    ['name' => "Zaw", "email" => 'zaw@g.com'],
  ];

  $inventoryItems = [
    ['productName' => "Apple", 'quantity' => '3', 'price' => 10],
    ['productName' => "Banana", 'quantity' => '7', 'price' => 24],
    ['productName' => "Orange", 'quantity' => '9', 'price' => 19],
    ['productName' => "Bread", 'quantity' => '14', 'price' => 8],
    ['productName' => "Rice", 'quantity' => '2', 'price' => 27],
  ];

  $products = [
    ['productName' => "Apple", 'isOutOfStock' => false, 'price' => 10],
    ['productName' => "Banana", 'isOutOfStock' => true, 'price' => 24],
    ['productName' => "Orange", 'isOutOfStock' => false, 'price' => 19],
    ['productName' => "Bread", 'isOutOfStock' => true, 'price' => 8],
    ['productName' => "Rice", 'isOutOfStock' => false, 'price' => 27],
  ];

  $customerEmails = map($customers, function($customer) {
    return $customer['email'];
  });

  $outOfStockProducts = filter($products, function ($product) {
    return $product['isOutOfStock'];
  });

  $totalPrice = reduce($products, function($total, $product) {
    return $total + $product['price'];
  }, 0);

  $totalPriceSum = sum($products, function($product) {
      return $product['price'];
  });

  $bcc = reduce($customers, function($result, $customer) {
    return $result . $customer['email'] . ', ';
  }, '');

  $bccJoin = myJoin($customers, function($customer) {
    return $customer['email'] . ', ';
  });

  ?>

  <pre>
    <?php
      var_dump($customerEmails);
    ?>
  </pre>
