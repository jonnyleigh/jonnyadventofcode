<?php
/*
 * https://adventofcode.com/2022/day/8
 * 
 * The expedition comes across a peculiar patch of tall trees all planted carefully in a grid. The Elves explain that a previous expedition planted these trees as a reforestation effort. Now, they're curious if this would be a good location for a tree house.

First, determine whether there is enough tree cover here to keep a tree house hidden. To do this, you need to count the number of trees that are visible from outside the grid when looking directly along a row or column.

The Elves have already launched a quadcopter to generate a map with the height of each tree (your puzzle input). For example:

30373
25512
65332
33549
35390

Each tree is represented as a single digit whose value is its height, where 0 is the shortest and 9 is the tallest.

A tree is visible if all of the other trees between it and an edge of the grid are shorter than it. Only consider trees in the same row or column; that is, only look up, down, left, or right from any given tree.

All of the trees around the edge of the grid are visible - since they are already on the edge, there are no trees to block the view. In this example, that only leaves the interior nine trees to consider:

    The top-left 5 is visible from the left and top. (It isn't visible from the right or bottom since other trees of height 5 are in the way.)
    The top-middle 5 is visible from the top and right.
    The top-right 1 is not visible from any direction; for it to be visible, there would need to only be trees of height 0 between it and an edge.
    The left-middle 5 is visible, but only from the right.
    The center 3 is not visible from any direction; for it to be visible, there would need to be only trees of at most height 2 between it and an edge.
    The right-middle 3 is visible from the right.
    In the bottom row, the middle 5 is visible, but the 3 and 4 are not.

With 16 trees visible on the edge and another 5 visible in the interior, a total of 21 trees are visible in this arrangement.

Consider your map; how many trees are visible from outside the grid?
*/

//////// Load input data into 2d array
$treeMap = array(); //[x][y]


$inputLines = file_get_contents('input.txt');
$handle = fopen('input.txt', "r");
if ($handle) { //file did open ok
    
    while (($line = fgets($handle)) !== false) {
        $line = trim($line); //remove lineends
        //echo($line . '<BR>');

        $treeMap[] = str_split($line);
    }
}

/*
echo('<pre>');
var_dump($treeMap);
echo('</pre>');
*/



/////////// Go tree by and count the number of visible trees
$numVisibleTrees = 0;
$xPtr = 0;
$yPtr = 0;

$gridWidth = count($treeMap[0]);
$gridHeight = count($treeMap);

echo('Grid Width: ' . $gridWidth . '<BR>');
echo('Grid Height: ' . $gridHeight . '<BR>');
echo('Number of edge trees: ' . (($gridHeight + $gridWidth)*2) . '<BR>');
echo('Total Number of Trees: ' . ($gridWidth * $gridHeight) . '<BR>');


for($x = 1; $x < $gridWidth; $x++) { //starting at 1 and finishing at -1 because no point in checking the edges, which are always visible
    for($y = 1; $y < $gridHeight; $y++) {

        $thisTreeHeight = $treeMap[$x][$y];

        echo("Tree: [$x][$y] is of height $thisTreeHeight ");

        //walk left to see if any trees to the left are blocking it
        $visibleFromLeftFlag = true; //its visible till we find a taller or same size tree
        echo("Walking left from x= " . $x-1 . " to 0 ;");
        for($xPtr = $x-1; $xPtr >= 0; $xPtr--) {
            if($treeMap[$xPtr][$y] >= $thisTreeHeight) {
                $visibleFromLeftFlag = false;
                echo("Found taller tree of height " . $treeMap[$xPtr][$y] . " at coordinates [$xPtr][$y]");
                break; //no point in checking further, it's not visible from the left
            }
        }

        //walk right to see if any trees to the left are blocking it
        $visibleFromRightFlag = true; //its visible till we find a taller or same size tree
        echo("Walking right from x= " . $x+1 . " to $gridWidth ;");
        for($xPtr = $x+1; $xPtr < $gridWidth; $xPtr++) {
            if($treeMap[$xPtr][$y] >= $thisTreeHeight) {
                $visibleFromRightFlag = false;
                echo("Found taller tree of height " . $treeMap[$xPtr][$y] . ' ');
                break; //no point in checking further, it's not visible from the left
            }
        }

        //walk up to see if any trees to the left are blocking it
        $visibleFromNorthFlag = true; //its visible till we find a taller or same size tree
        echo("Walking up from y= " . $y-1 . " to 0 ;");
        for($yPtr = $y-1; $yPtr >= 0; $yPtr--) {
            if($treeMap[$x][$yPtr] >= $thisTreeHeight) {
                $visibleFromNorthFlag = false;
                echo("Found taller tree of height " . $treeMap[$x][$yPtr] . ' ');
                break; //no point in checking further, it's not visible from the left
            }
        }

        //walk down to see if any trees to the left are blocking it
        $visibleFromSouthFlag = true; //its visible till we find a taller or same size tree
        echo("Walking down from y= " . $y+1 . " to $gridHeight ;");
        for($yPtr = $y+1; $yPtr < $gridHeight; $yPtr++) {
            if($treeMap[$x][$yPtr] >= $thisTreeHeight) {
                $visibleFromSouthFlag = false;
                echo("Found taller tree of height " . $treeMap[$x][$yPtr] . ' ');
                break; //no point in checking further, it's not visible from the left
            }
        }

        echo(" Left: $visibleFromLeftFlag; Right: $visibleFromRightFlag; North: $visibleFromNorthFlag; South: $visibleFromSouthFlag;");

        


        if($visibleFromLeftFlag || $visibleFromRightFlag || $visibleFromNorthFlag || $visibleFromSouthFlag) {
            $numVisibleTrees++;
            echo(' No taller tree found ');
        }

        echo('<BR>');
    }
}

//add edge trees to count of visible trees
$numVisibleTrees += (($gridHeight + $gridWidth)*2);
echo('Number of visible trees: ' . $numVisibleTrees . '<BR>');
//2000 is too high
//dn't forget to discount the corners, don't double count
//1995 is too high