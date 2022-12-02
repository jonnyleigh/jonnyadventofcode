<?php
/*
 * https://adventofcode.com/2022/day/2
The Elf finishes helping with the tent and sneaks back over to you. "Anyway, the second column says how the round needs to end: 
    X means you need to lose, Y means you need to end the round in a draw, and Z means you need to win. Good luck!"

The total score is still calculated in the same way, but now you need to figure out what shape to choose so the round ends as indicated. The example above now goes like this:

    In the first round, your opponent will choose Rock (A), and you need the round to end in a draw (Y), so you also choose Rock. This gives you a score of 1 + 3 = 4.
    In the second round, your opponent will choose Paper (B), and you choose Rock so you lose (X) with a score of 1 + 0 = 1.
    In the third round, you will defeat your opponent's Scissors with Rock for a score of 1 + 6 = 7.

Now that you're correctly decrypting the ultra top secret strategy guide, you would get a total score of 12.

Following the Elf's instructions for the second column, what would your total score be if everything goes exactly according to your strategy guide?
 */

$inputLines = file_get_contents('input.txt');
$totalScore = 0;

//create array of scores
$scoreLookup = array(
    "A X" => 3,
    "A Y" => 4,
    "A Z" => 8,
    "B X" => 1,
    "B Y" => 5,
    "B Z" => 9,
    "C X" => 2,
    "C Y" => 6,
    "C Z" => 7,
);

$handle = fopen('input.txt', "r");
if ($handle) { //file did open ok
    
    while (($line = fgets($handle)) !== false) {

        $score = intval($scoreLookup[trim($line)]); //lookup score in array

        $totalScore += $score; //update score counter        
    }

    echo('Total Score: ' . $totalScore);

    fclose($handle);
} else {
    throw new \Exception('Error opening file');
}