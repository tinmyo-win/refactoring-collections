<?php

include('Collection.php');

function rankingCompetition()
{

    $scores = collect([
        ['score' => 76, 'team' => 'A'],
        ['score' => 62, 'team' => 'B'],
        ['score' => 82, 'team' => 'C'],
        ['score' => 86, 'team' => 'D'],
        ['score' => 91, 'team' => 'E'],
        ['score' => 67, 'team' => 'F'],
        ['score' => 67, 'team' => 'G'],
        ['score' => 82, 'team' => 'H'],
    ]);

    $rankedScore = assign_initial_ranking($scores);
    $adjustedScore = adjust_ranking_for_ties($rankedScore);
    return $adjustedScore->sortBy('rank');
}

function assign_initial_ranking($scores)
{
    return $scores->sortByDesc('score')
        ->zip(range(1, count($scores)))
        ->map(function($scoreAndRank) {
            [$score, $rank] = $scoreAndRank;
            return array_merge(['rank' => $rank], $score);
        });
}

function adjust_ranking_for_ties($scores)
{
    return $scores        ->groupBy('score')
    ->map(function($tiedScores) {
        return apply_min_rank($tiedScores);
    })
    ->collapse();
}

function apply_min_rank($tiedScores)
{
    $lowestRank = $tiedScores->pluck('rank')->min();
    return $tiedScores->map(function($rankedScore) use($lowestRank) {
        return array_merge($rankedScore, [
            'rank' => $lowestRank
        ]);
    });
}

?>

<pre>
    <?php
    print_r(rankingCompetition());
    ?>
</pre>