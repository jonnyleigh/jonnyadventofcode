<?php
/*
 * https://adventofcode.com/2022/day/2
The Elves begin to set up camp on the beach. To decide whose tent gets to be closest to the snack storage, a giant Rock Paper Scissors tournament is already in progress.

Rock Paper Scissors is a game between two players. Each game contains many rounds; in each round, the players each simultaneously choose one of Rock, Paper, or Scissors using a hand shape. Then, a winner for that round is selected: Rock defeats Scissors, Scissors defeats Paper, and Paper defeats Rock. If both players choose the same shape, the round instead ends in a draw.

Appreciative of your help yesterday, one Elf gives you an encrypted strategy guide (your puzzle input) that they say will be sure to help you win. "The first column is what your opponent is going to play: A for Rock, B for Paper, and C for Scissors. The second column--" Suddenly, the Elf is called away to help with someone's tent.

The second column, you reason, must be what you should play in response: X for Rock, Y for Paper, and Z for Scissors. Winning every time would be suspicious, so the responses must have been carefully chosen.

The winner of the whole tournament is the player with the highest score. Your total score is the sum of your scores for each round. The score for a single round is the score for the shape you selected (1 for Rock, 2 for Paper, and 3 for Scissors) plus the score for the outcome of the round (0 if you lost, 3 if the round was a draw, and 6 if you won).

Since you can't be sure if the Elf is trying to help you or trick you, you should calculate the score you would get if you were to follow the strategy guide.

For example, suppose you were given the following strategy guide:

A Y
B X
C Z

This strategy guide predicts and recommends the following:

    In the first round, your opponent will choose Rock (A), and you should choose Paper (Y). This ends in a win for you with a score of 8 (2 because you chose Paper + 6 because you won).
    In the second round, your opponent will choose Paper (B), and you should choose Rock (X). This ends in a loss for you with a score of 1 (1 + 0).
    The third round is a draw with both players choosing Scissors, giving you a score of 3 + 3 = 6.

In this example, if you were to follow the strategy guide, you would get a total score of 15 (8 + 1 + 6).

What would your total score be if everything goes exactly according to your strategy guide?
 */

$inputLines = file_get_contents('input.txt');
$totalScore = 0;

$handle = fopen('input.txt', "r");
if ($handle) { //file did open ok
    
    while (($line = fgets($handle)) !== false) {

        //take the strategy line and split it into opponent and player actions
        $actions = explode(' ',$line);
        //tidy up the input
        $actions[0] = trim($actions[0]);
        $actions[1] = trim($actions[1]);
        
        //calculate the outcome - win, lose or draw
        $outcome = '';
        if($actions[0] == 'A') { //opponent chooses Rock
            echo('Opponent chose Rock ');
            if($actions[1] == 'X') { //player chooses Rock
                echo('Player chose Rock ');
                $outcome = 'draw';
            } elseif($actions[1] == 'Y') { //player chooses Paper
                echo('Player chose Paper ');
                $outcome = 'win';
            } elseif($actions[1] == 'Z') { //player chooses Scissors
                echo('Player chose Scissors ');
                $outcome = 'lose';
            }
        } elseif($actions[0] == 'B') { //opponent chooses Paper
            echo('Opponent chose Paper ');
            if($actions[1] == 'X') { //player chooses Rock
                echo('Player chose Rock ');
                $outcome = 'lose';
            } elseif($actions[1] == 'Y') { //player chooses Paper
                echo('Player chose Paper ');
                $outcome = 'draw';
            } elseif($actions[1] == 'Z') { //player chooses Scissors
                echo('Player chose Scissors ');
                $outcome = 'win';
            }
        } elseif($actions[0] == 'C') { //opponent chooses Scissors
            echo('Opponent chose Scissors ');
            if($actions[1] == 'X') { //player chooses Rock
                echo('Player chose Rock ');
                $outcome = 'win';
            } elseif($actions[1] == 'Y') { //player chooses Paper
                echo('Player chose Paper ');
                $outcome = 'lose';
            } elseif($actions[1] == 'Z') { //player chooses Scissors
                echo('Player chose Scissors ');
                $outcome = 'draw';
            }
        }

        //calculate score
        /*
        The score for a single round is the score for the shape you selected (1 for Rock, 2 for Paper, and 3 for Scissors) 
        plus the score for the outcome of the round (0 if you lost, 3 if the round was a draw, and 6 if you won).
        */

        $score = 0;
        if($actions[1] == 'X') {//player chooses Rock
            $score = 1;
        } elseif($actions[1] == 'Y') { //player chooses Paper
            $score = 2;
        } elseif($actions[1] == 'Z') { //player chooses Scissors
            $score = 3;
        }

        if($outcome == 'win') {
            $score += 6;
        } elseif($outcome == 'draw') {
            $score += 3;
        } elseif($outcome == 'lose') {
            $score += 0; //not strictly necessary but aids readability
        }

        $totalScore += $score; //update score counter

        echo('Outcome is ' . $outcome . ' and the score is ' . $score . '<BR>');
        
    }

    echo('Total Score: ' . $totalScore);

    fclose($handle);
} else {
    throw new \Exception('Error opening file');
}