<?php

include('Collection.php');

$employees = collect([
    [
        'name' => 'John',
        'department' => 'Sales',
        'email' => 'john@example.com'
    ],
    [
        'name' => 'Jane',
        'department' => 'Marketing',
        'email' => 'jane@example.com'
    ],
    [
        'name' => 'Dave',
        'department' => 'Marketing',
        'email' => 'dave@example.com'
    ],
]);

Collection::macro('toAssoc', function () {
    return $this->reduce(function ($assoc, $keyValuePair) {
        list($key, $value) = $keyValuePair;
        $assoc[$key] = $value;
        return $assoc;
    }, new static);
});

Collection::macro('mapToAssoc', function ($callback) {
    return $this->map($callback)->toAssoc();
});

$emailLookup = $employees->mapToAssoc(function ($employee) {
    return [$employee['email'], $employee['name']];
});

?>

<pre>
<?php
print_r($emailLookup);
?>
</pre>