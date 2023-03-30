<?php

include('Collection.php');

$comments = [
    'Opening brace must be the last content on the line',
    'Closing brace must be on a line by itself',
    'Each PHP statement must be on a line by itself',
];

function buildComment($messages)
{
    return Collection::make($messages)
            ->map(function($message) {
                return "- $message \n";
            })->implode('');
}

echo buildComment($comments);