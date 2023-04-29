<?php
include('Collection.php');

$contact_data = [
    'names' => [
        'Jane',
        'Bob',
        'Mary',
    ],
    'emails' => [
        'jane@example.com',
        'bob@example.com',
        'mary@example.com',
    ],
    'occupations' => [
        'Doctor',
        'Plumber',
        'Dentist',
    ],
];

$contacts = collect($contact_data)->transpose();
print_r( $contacts);