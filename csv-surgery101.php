<?php

include('Collection.php');

$shifts = [
  'Shipping_Steve_A7',
  'Sales_B9',
  'Support_Tara_K11',
  'J15',
  'Warehouse_B2',
  'Shipping_Dave_A6',
];

$shiftIds = Collection::make($shifts)->map(function ($shift) {
  return Collection::make(explode('_', $shift))->last();
});

?>

<pre>
<?php
  print_r($shiftIds);
?>
</pre>