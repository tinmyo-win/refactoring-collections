<?php

$lastYear = [
    2976.50, // Jan
    2788.84, // Feb
    2353.92, // Mar
    3365.36, // Apr
    2532.99, // May
    1598.42, // Jun
    2751.82, // Jul
    2576.17, // Aug
    2324.87, // Sep
    2299.21, // Oct
    3483.10, // Nov
    2245.08, // Dec
];

$thisYear = [
    3461.77,
    3665.17,
    3210.53,
    3529.07,
    3376.66,
    3825.49,
    2165.24,
    2261.40,
    3988.76,
    3302.42,
    3345.41,
    2904.80,
];

function compare_revenue($thisYear, $lastYear)
{
    var_dump(collect($thisYear)->zip($lastYear));

    exit;

    $deltas = [];
    foreach ($lastYear as $month => $monthlyRevenue) {
        $deltas[] = $thisYear[$month] - $monthlyRevenue;
    }
    return $deltas;
}

$result = compare_revenue($thisYear, $lastYear);

?>

<pre>
<?php
print_r($result);
?>
</pre>