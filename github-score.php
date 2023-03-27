<?php

function githubScore($username)
{
  $opts = [
    'http' => [
      'method' => 'GET',
      'header' => [
        'User-Agent: PHP'
      ]
    ]
  ];
  $context = stream_context_create($opts);
  $url = "https://api.github.com/users/{$username}/events";
  $events = json_decode(file_get_contents($url, false, $context), true);

  $eventTypes = [];
  foreach ($events as $event) {
    $eventTypes[] = $event['type'];
  }

  $score = 0;
  foreach ($eventTypes as $eventType) {
    switch ($eventType) {
      case 'PushEvent':
        $score += 5;
        break;
      case 'CreateEvent':
        $score += 4;
        break;
      case 'IssuesEvent':
        $score += 3;
        break;
      case 'CommitCommentEvent':
        $score += 2;
        break;
      default:
        $score += 1;
        break;
    }
  }
  return $score;
}

echo "My Github Score => " . githubScore("tinmyo-win");
