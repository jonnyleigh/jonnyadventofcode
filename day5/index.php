<?php
/*
 * https://adventofcode.com/2022/day/5

 The expedition can depart as soon as the final supplies have been unloaded from the ships. Supplies are stored in stacks of marked crates, but because the needed supplies are buried under many other crates, the crates need to be rearranged.

The ship has a giant cargo crane capable of moving crates between stacks. To ensure none of the crates get crushed or fall over, the crane operator will rearrange them in a series of carefully-planned steps. After the crates are rearranged, the desired crates will be at the top of each stack.

The Elves don't want to interrupt the crane operator during this delicate procedure, but they forgot to ask her which crate will end up where, and they want to be ready to unload them as soon as possible so they can embark.

They do, however, have a drawing of the starting stacks of crates and the rearrangement procedure (your puzzle input). For example:

    [D]    
[N] [C]    
[Z] [M] [P]
 1   2   3 

move 1 from 2 to 1
move 3 from 1 to 3
move 2 from 2 to 1
move 1 from 1 to 2

In this example, there are three stacks of crates. Stack 1 contains two crates: crate Z is on the bottom, and crate N is on top. Stack 2 contains three crates; from bottom to top, they are crates M, C, and D. Finally, stack 3 contains a single crate, P.

Then, the rearrangement procedure is given. In each step of the procedure, a quantity of crates is moved from one stack to a different stack. In the first step of the above rearrangement procedure, one crate is moved from stack 2 to stack 1, resulting in this configuration:

[D]        
[N] [C]    
[Z] [M] [P]
 1   2   3 

In the second step, three crates are moved from stack 1 to stack 3. Crates are moved one at a time, so the first crate to be moved (D) ends up below the second and third crates:

        [Z]
        [N]
    [C] [D]
    [M] [P]
 1   2   3

Then, both crates are moved from stack 2 to stack 1. Again, because crates are moved one at a time, crate C ends up below crate M:

        [Z]
        [N]
[M]     [D]
[C]     [P]
 1   2   3

Finally, one crate is moved from stack 1 to stack 2:

        [Z]
        [N]
        [D]
[C] [M] [P]
 1   2   3

The Elves just need to know which crate will end up on top of each stack; in this example, the top crates are C in stack 1, M in stack 2, and Z in stack 3, so you should combine these together and give the Elves the message CMZ.

After the rearrangement procedure completes, what crate ends up on top of each stack?
*/


/******************** Initial state
 * 
            [L] [M]         [M]    
        [D] [R] [Z]         [C] [L]
        [C] [S] [T] [G]     [V] [M]
[R]     [L] [Q] [B] [B]     [D] [F]
[H] [B] [G] [D] [Q] [Z]     [T] [J]
[M] [J] [H] [M] [P] [S] [V] [L] [N]
[P] [C] [N] [T] [S] [F] [R] [G] [Q]
[Z] [P] [S] [F] [F] [T] [N] [P] [W]
 1   2   3   4   5   6   7   8   9 
 * 
 */


require_once('stack.php');

//Load initial state
$initialState = array(
    'ZPMHR',
    'PCJB',
    'SNHGLCD',
    'FTMDQSRL',
    'FSPQBTZM',
    'TFSZBG',
    'NRV',
    'PGLTDVCM',
    'WQNJFML'
);

$stacks = array();
$i=1;

foreach($initialState as $state) {
    $s = new Stack();
    foreach(str_split($state) as $letter) { //split string into individual letters
        $s->push($letter);
    }

    $stacks[$i] = $s;
    $i++;
}



//loop through and execute the program
$handle = fopen('input.txt', "r");
if ($handle) { //file did open ok
    
    while (($line = fgets($handle)) !== false) {
        $line = trim($line); //remove lineends
        
        //split instruction line into constituent parts
        $originalLine = $line;
        $line = str_replace('move ', '', $line);
        $instructions = explode(',',str_replace([' from ', ' to '], ',', $line)); //[multiplier, from, to]
        
        $multiplier = $instructions[0]; //how many items to move
        $from = $instructions[1]; //stack to move from
        $to = $instructions[2]; //stack to move to

        echo($originalLine . ' : Moving ' . $multiplier . ' from stack ' . $from . ' to stack ' . $to . '<br>');

        for($i=0; $i<$multiplier; $i++) {
            $item = $stacks[intval($from)]->pop();
            $stacks[$to]->push($item);
        }   

    }

    fclose($handle);
} else {
    throw new \Exception('Error opening file');
}



echo('Tops of stacks: <BR>');
foreach($stacks as $stack) {
    echo($stack->top() . '<BR>');
}