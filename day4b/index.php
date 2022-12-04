<?php
/*
 * https://adventofcode.com/2022/day/4
 * --- Part Two ---

It seems like there is still quite a bit of duplicate work planned. Instead, the Elves would like to know the number of pairs that overlap at all.

In the above example, the first two pairs (2-4,6-8 and 2-3,4-5) don't overlap, while the remaining four pairs (5-7,7-9, 2-8,3-7, 6-6,4-6, and 2-6,4-8) do overlap:

    5-7,7-9 overlaps in a single section, 7.
    2-8,3-7 overlaps all of the sections 3 through 7.
    6-6,4-6 overlaps in a single section, 6.
    2-6,4-8 overlaps in sections 4, 5, and 6.

So, in this example, the number of overlapping assignment pairs is 4.

In how many assignment pairs do the ranges overlap?

*/

//setup
$overlapCounter = 0;

//read input into array
$assignments = array(); //multidimensional array [assignment][elf1 assignemnt,elf2 assignment]
$inputLines = file_get_contents('input.txt');
$handle = fopen('input.txt', "r");
if ($handle) { //file did open ok
    
    while (($line = fgets($handle)) !== false) {
        $line = trim($line); //remove lineends
        $lineAsArray = explode(',',$line); //breaks line into array with two elements
        $assignments[] = $lineAsArray;
    }

    fclose($handle);
} else {
    throw new \Exception('Error opening file');
}

foreach($assignments as $assignment) {
    $range1 = explode('-', $assignment[0]);
    $range2 = explode('-', $assignment[1]);

    $overlap = doesRangeOverlap($range1[0],$range1[1],$range2[0],$range2[1]);
    if($overlap) $overlapCounter++;

    echo('<pre>');
    echo($assignment[0] . ',' . $assignment[1] . ' overlap: ' . $overlap);
    echo('<BR>');
    echo('</pre>');
}

echo($overlapCounter);


/**
 * Determines if range 1 overlaps at all with range 2
 */
function doesRangeOverlap($range1Lower, $range1Upper, $range2Lower, $range2Upper) {
    $range1 = range($range1Lower, $range1Upper); //create array of all numbers between lower and upper bounds
    $range2 = range($range2Lower, $range2Upper); //create array of all numbers between lower and upper bounds
    return(count(array_intersect($range1, $range2)) > 0);

}