<?php

  include('Collection.php');

  $url = './products.json';
  $productJson = json_decode(file_get_contents($url), true);
  $products = Collection::make($productJson['products']);

  $totalCost = $products->filter(function ($product) {
    $productType = $product['product_type'];
    return $productType == 'Lamp' || $productType == 'Wallet';        // return collect(['Lamp', 'Wallet'])->contains($product['product_type']);
  })->map(function($product) {
    return $product['variants'];
  })->collapse()
  ->sum(function($variant) {
    return $variant['price'];
  });

?>

<pre>
<?php
  print_r("Total price " . $totalCost);
?>
</pre>
