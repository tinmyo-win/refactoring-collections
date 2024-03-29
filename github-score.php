<?php

include('Collection.php');

class GithubScore
{
  private $username;

  private function __construct($username)
  {
    $this->username = $username;
  }

  public static function forUser($username)
  {
    return (new self($username))->score();
  }

  private function score()
  {
    return $this->events()->pluck('type')->map(function($eventType) {
      return $this->lookupScore($eventType);
    })->sum();
  }

  private function events()
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
    $url = "https://api.github.com/users/{$this->username}/events";
    return Collection::make(json_decode(file_get_contents($url, false, $context), true));
  }

  private function lookupScore($eventType) {
    return collect([
      'PushEvent' => 5,
      'CreateEvent' => 4,
      'IssuesEvent' => 3,
      'CommitCommentEvent' => 2,
    ])->get($eventType, 1);
  }
}

echo "My Github Score => " . GithubScore::forUser('tinmyo-win');
