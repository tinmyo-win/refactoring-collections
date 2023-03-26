<?php
include('Collection.php');

function binaryToDecimal($binary)
{
  return Collection::make(str_split($binary))
                ->reverse()
                ->values()
                ->map(function($column, $exponent) {
                      return $column * (2 ** $exponent);
                })
                ->sum();
}

?>

<pre>
<?php

  print_r("Binary 11100111 is in decimal => " . binaryToDecimal("1110011") . "<br>");
  print_r("Binary 10001110101 is in decimal => " . binaryToDecimal("10001110101"));
?>
</pre>


