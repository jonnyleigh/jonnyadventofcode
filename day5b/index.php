<?php
/*
 * https://adventofcode.com/2022/day/5
--- Part Two ---

As you watch the crane operator expertly rearrange the crates, you notice the process isn't following your prediction.

Some mud was covering the writing on the side of the crane, and you quickly wipe it away. The crane isn't a CrateMover 9000 - it's a CrateMover 9001.

The CrateMover 9001 is notable for many new and exciting features: air conditioning, leather seats, an extra cup holder, and the ability to pick up and move multiple crates at once.

Again considering the example above, the crates begin in the same configuration:

    [D]    
[N] [C]    
[Z] [M] [P]
 1   2   3 

Moving a single crate from stack 2 to stack 1 behaves the same as before:

[D]        
[N] [C]    
[Z] [M] [P]
 1   2   3 

However, the action of moving three crates from stack 1 to stack 3 means that those three moved crates stay in the same order, resulting in this new configuration:

        [D]
        [N]
    [C] [Z]
    [M] [P]
 1   2   3

Next, as both crates are moved from stack 2 to stack 1, they retain their order as well:

        [D]
        [N]
[C]     [Z]
[M]     [P]
 1   2   3

Finally, a single crate is still moved from stack 1 to stack 2, but now it's crate C that gets moved:

        [D]
        [N]
        [Z]
[M] [C] [P]
 1   2   3

In this example, the CrateMover 9001 has put the crates in a totally different order: MCD.

Before the rearrangement process finishes, update your simulation so that the Elves know where they should stand to be ready to unload the final supplies. After the rearrangement procedure completes, what crate ends up on top of each stack?
*/

require_once('../day5/stack.php');

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

        //to retain the correct order I will POP off items into a temporary stack and then PUSH them onto the target stack
        $tempStack = new Stack();
        for($i=0; $i<$multiplier; $i++) {
            $item = $stacks[intval($from)]->pop();
            $tempStack->push($item);
        }

        for($i=0; $i<$multiplier; $i++) {
            $item = $tempStack->pop();
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