<?php
/**
 * https://adventofcode.com/2022/day/2
 * Challenge 1b
 * By the time you calculate the answer to the Elves' question, they've already realized that the Elf carrying the most Calories of food might eventually run out of snacks.
 * To avoid this unacceptable situation, the Elves would instead like to know the total Calories carried by the top three Elves carrying the most Calories. That way, even if one of those Elves runs out of snacks, they still have two backups.
 * In the example above, the top three Elves are the fourth Elf (with 24000 Calories), then the third Elf (with 11000 Calories), then the fifth Elf (with 10000 Calories). The sum of the Calories carried by these three elves is 45000.
 * Find the top three Elves carrying the most Calories. How many Calories are those Elves carrying in total?
 */

//stup
$inputLines = file_get_contents('input.txt');
$currentElfCalories = 0;
$elves = array(); //multi-dimensional array [elf-number][sum-of-calories]


//read the data into array of elves
$handle = fopen('input.txt', "r");
if ($handle) { //file did open ok
    
    while (($line = fgets($handle)) !== false) {

        if($line == "\r\n") { //is a blank line
            //push to array
            $elves[] = $currentElfCalories;

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

//sort the array
arsort($elves);

//Find the top three Elves carrying the most Calories. How many Calories are those Elves carrying in total?
$accumulator = 0;
$i = 0;
foreach($elves as $elf) {
    $i++;

    $accumulator += intval($elf);

    if($i > 2) {break;}
}

echo('The answer is: ' . $accumulator);