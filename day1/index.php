<?php
/**
 * https://adventofcode.com/2022/day/1
 * Challenge 1
 * In case the Elves get hungry and need extra snacks, they need to know which Elf to ask: they'd like to know how many Calories are being carried by the Elf carrying the most Calories. In the example above, this is 24000 (carried by the fourth Elf).
 * Find the Elf carrying the most Calories. How many total Calories is that Elf carrying?
 */
$inputLines = file_get_contents('input.txt');
$highestElfCalories = 0;
$currentElfCalories = 0;

$handle = fopen('input.txt', "r");
if ($handle) { //file did open ok
    
    while (($line = fgets($handle)) !== false) {

        if($line == "\r\n") { //is a blank line
            if($currentElfCalories > $highestElfCalories) {
                $highestElfCalories = $currentElfCalories;
            }

            //reset accumulator
            $currentElfCalories = 0;
        } else { //not a newline, should be a number
            $currentElfCalories += intval($line); //intval probably isn't necessary but you never know
        }
    }

    fclose($handle);
} else {
    throw new \Exception('Error opening file');
}

echo('The total calories is ' . $highestElfCalories);