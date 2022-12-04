<?php
/*
 * https://adventofcode.com/2022/day/3
 * One Elf has the important job of loading all of the rucksacks with supplies for the jungle journey. Unfortunately, that Elf didn't quite follow the packing instructions, and so a few items now need to be rearranged.

Each rucksack has two large compartments. All items of a given type are meant to go into exactly one of the two compartments. The Elf that did the packing failed to follow this rule for exactly one item type per rucksack.

The Elves have made a list of all of the items currently in each rucksack (your puzzle input), but they need your help finding the errors. Every item type is identified by a single lowercase or uppercase letter (that is, a and A refer to different types of items).

The list of items for each rucksack is given as characters all on a single line. A given rucksack always has the same number of items in each of its two compartments, so the first half of the characters represent items in the first compartment, while the second half of the characters represent items in the second compartment.

For example, suppose you have the following list of contents from six rucksacks:

vJrwpWtwJgWrhcsFMMfFFhFp
jqHRNqRjqzjGDLGLrsFMfFZSrLrFZsSL
PmmdzqPrVvPwwTWBwg
wMqvLMZHhHMvwLHjbvcjnnSBnvTQFn
ttgJtRGJQctTZtZT
CrZsJsPPZsGzwwsLwLmpwMDw

    The first rucksack contains the items vJrwpWtwJgWrhcsFMMfFFhFp, which means its first compartment contains the items vJrwpWtwJgWr, while the second compartment contains the items hcsFMMfFFhFp. The only item type that appears in both compartments is lowercase p.
    The second rucksack's compartments contain jqHRNqRjqzjGDLGL and rsFMfFZSrLrFZsSL. The only item type that appears in both compartments is uppercase L.
    The third rucksack's compartments contain PmmdzqPrV and vPwwTWBwg; the only common item type is uppercase P.
    The fourth rucksack's compartments only share item type v.
    The fifth rucksack's compartments only share item type t.
    The sixth rucksack's compartments only share item type s.

To help prioritize item rearrangement, every item type can be converted to a priority:

    Lowercase item types a through z have priorities 1 through 26.
    Uppercase item types A through Z have priorities 27 through 52.

In the above example, the priority of the item type that appears in both compartments of each rucksack is 16 (p), 38 (L), 42 (P), 22 (v), 20 (t), and 19 (s); the sum of these is 157.

Find the item type that appears in both compartments of each rucksack. What is the sum of the priorities of those item types?
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

//find the item that exists in both compartments of each rucksack
foreach($rucksacks as $rucksack) {
    $arrayLength = count($rucksack);
    //it is even right??
    if($arrayLength % 2 != 0) {
        die('Length of rucksack contents is not an even number!');
    }

    //split into two arrays
    $compartment1 = array_slice($rucksack, 0, $arrayLength / 2); //first half
    $compartment2 = array_slice($rucksack, $arrayLength / 2, $arrayLength); //second half

    //find intersect
    $itemsInBothCompartments = array_intersect($compartment1, $compartment2);
    $itemsInBothCompartments = array_values($itemsInBothCompartments); //rekey the output array to reset the item indexes
    
    echo('<pre>');
    var_dump(implode('',$rucksack));
    var_dump($itemsInBothCompartments);
    var_dump(getPriorityOfLetter($itemsInBothCompartments[0]));
    echo('</pre>');
    
    //sum the priorities of the duplicate items
    $prioritySum += getPriorityOfLetter($itemsInBothCompartments[0]);
    
}

echo('The answer is: ' . $prioritySum);



function getPriorityOfLetter($letter) {
    if(ord($letter) >= 97 && ord($letter) <= 122 ) { //lowercase letter
        return (ord($letter) - 96);
    } elseif(ord($letter) >= 65 && ord($letter) <= 90 ) { //uppercase letter
        return (ord($letter) - 38);
    }
}