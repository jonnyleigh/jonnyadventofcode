<?php
/*
 * https://adventofcode.com/2022/day/3
 As you finish identifying the misplaced items, the Elves come to you with another issue.

For safety, the Elves are divided into groups of three. Every Elf carries a badge that identifies their group. For efficiency, within each group of three Elves, the badge is the only item type carried by all three Elves. That is, if a group's badge is item type B, then all three Elves will have item type B somewhere in their rucksack, and at most two of the Elves will be carrying any other item type.

The problem is that someone forgot to put this year's updated authenticity sticker on the badges. All of the badges need to be pulled out of the rucksacks so the new authenticity stickers can be attached.

Additionally, nobody wrote down which item type corresponds to each group's badges. The only way to tell which item type is the right one is by finding the one item type that is common between all three Elves in each group.

Every set of three lines in your list corresponds to a single group, but each group can have a different badge item type. So, in the above example, the first group's rucksacks are the first three lines:

vJrwpWtwJgWrhcsFMMfFFhFp
jqHRNqRjqzjGDLGLrsFMfFZSrLrFZsSL
PmmdzqPrVvPwwTWBwg

And the second group's rucksacks are the next three lines:

wMqvLMZHhHMvwLHjbvcjnnSBnvTQFn
ttgJtRGJQctTZtZT
CrZsJsPPZsGzwwsLwLmpwMDw

In the first group, the only item type that appears in all three rucksacks is lowercase r; this must be their badges. In the second group, their badge item type must be Z.

Priorities for these items must still be found to organize the sticker attachment efforts: here, they are 18 (r) for the first group and 52 (Z) for the second group. The sum of these is 70.

Find the item type that corresponds to the badges of each three-Elf group. What is the sum of the priorities of those item types?
*/

//prepare variables
$rucksacks = array(); //multi-dimensional array [rucksack-number][item-number]
$prioritySum = 0;

//read contents of rucksacks into array
$inputLines = file_get_contents('input.txt');
$handle = fopen('input.txt', "r");
if ($handle) { //file did open ok
    
    while (($line = fgets($handle)) !== false) {
        $line = trim($line); //remove lineends
        $lineAsArray = str_split($line); //breaks line into array of characters
        $rucksacks[] = $lineAsArray;
    }

    fclose($handle);
} else {
    throw new \Exception('Error opening file');
}

//find the item that exists in each set of 3 lines
$rucksacksInThrees = array_chunk($rucksacks, 3); //split rucksacks into chunks of three rucksacks
foreach($rucksacksInThrees as $threesome) {
    $itemsInAllArrays = array_intersect($threesome[0], $threesome[1], $threesome[2]);
    $itemsInAllArrays = array_values($itemsInAllArrays); //re-key arrays

    $prioritySum += getPriorityOfLetter($itemsInAllArrays[0]);

}


echo('The answer is: ' . $prioritySum);



function getPriorityOfLetter($letter) {
    if(ord($letter) >= 97 && ord($letter) <= 122 ) { //lowercase letter
        return (ord($letter) - 96);
    } elseif(ord($letter) >= 65 && ord($letter) <= 90 ) { //uppercase letter
        return (ord($letter) - 38);
    }
}