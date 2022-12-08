<?php
/*
 * https://adventofcode.com/2022/day/7
 * 
 * Now, you're ready to choose a directory to delete.

The total disk space available to the filesystem is 70000000. To run the update, you need unused space of at least 30000000. You need to find a directory you can delete that will free up enough space to run the update.

In the example above, the total size of the outermost directory (and thus the total amount of used space) is 48381165; this means that the size of the unused space must currently be 21618835, which isn't quite the 30000000 required by the update. Therefore, the update still requires a directory with total size of at least 8381165 to be deleted before it can run.

To achieve this, you have the following options:

    Delete directory e, which would increase unused space by 584.
    Delete directory a, which would increase unused space by 94853.
    Delete directory d, which would increase unused space by 24933642.
    Delete directory /, which would increase unused space by 48381165.

Directories e and a are both too small; deleting them would not free up enough space. However, directories d and / are both big enough! Between these, choose the smallest: d, increasing unused space by 24933642.

Find the smallest directory that, if deleted, would free up enough space on the filesystem to run the update. What is the total size of that directory?

*/


require_once('../day7/TreeNode.php'); //bring in all the functions and data from part 1
require_once('../day5/stack.php');

/////// Load input into tree structure

$root = new TreeNode('/', true);
$root->path = '/';
$currentDirectoryName = '/'; //pointer to where we are in the tree
/** @var TreeNode */
$currentDirectory = $root;
$directoryStack = new Stack();

$inputLines = file_get_contents('input.txt');
$handle = fopen('input.txt', "r");
if ($handle) { //file did open ok
    
    while (($line = fgets($handle)) !== false) {
        $line = trim($line); //remove lineends
        
        if($line == '$ cd /' || $line == '$ ls') { continue; } //whatever, these are not really relevant

        //start decoding input
        if(substr($line, 0, 3) == 'dir') { //we have found a directory
            //store pointer that we are expecting a directory at this level, and we will later add files to it
            $dirName = explode(' ', $line)[1]; //grab the directory name off the LS output
            echo('Found directory ' . $dirName . ' <BR>');

            //create treenode and add it to the set of children
            $d = new TreeNode($dirName, true);
            $currentDirectory->children[] = $d;
        } elseif(substr($line, 0, 7) == '$ cd ..') { //we are stepping out of the directory and back up
            //pop last directory off the stack
            $currentDirectory = $directoryStack->pop();
            $currentDirectoryName = $currentDirectory->name;

            echo('Stepping back up to previous directory, current directory is ' . $currentDirectoryName . ' <BR>');

        } elseif(substr($line, 0, 4) == '$ cd') { //we are stepping into a directory
            $dirName = explode(' ', $line)[2]; //grab the directory name off the CD command

            //push current directory pointer to stack
            $directoryStack->push($currentDirectory);

            //move to next directory
            $currentDirectory = $currentDirectory->findChild($dirName); //this breaks where we have multiple directories with the same name, ordoes it
            $currentDirectoryName = $currentDirectory->name;

            echo('Stepping into directory ' . $dirName . ', current directory is now ' . $currentDirectoryName . ' <BR>');
        } else { //found a file
            //split out name and size
            $fileDetails = explode(' ', $line);
            
            //create node
            $f = new TreeNode($fileDetails[1], false, $fileDetails[0]);

            //add node to current parent
            $currentDirectory->children[] = $f;
            
            echo('Found file ' . $f->name . ' of size ' . $f->fileSize . ' <BR>');
        }

    }

    fclose($handle);
} else {
    throw new \Exception('Error opening file');
}

calculatePathStrings($root);


////////////////// Traverse the tree and calculate the total size
calculateTotalDirectorySize($root);



///////////////// Get all directory sizes into an array

$directorySizeArray = [];
addDirectorySizesIntoArray($root, $directorySizeArray);
asort($directorySizeArray); //sort smallest to largest
echo('<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>');
echo('Total directory count: ' . count($directorySizeArray));


echo('<pre>');
//echo(json_encode($root));
var_dump($directorySizeArray);
//var_dump($root);
echo('</pre>');

//printFileSystem($root);
//echo('<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>');




//// find smallest directory which is greater than $amountOfSpaceToFind
$freeSpace = (70000000 - $root->totalDirectorySize);
$amountOfSpaceToFind = 30000000 - $freeSpace; //5,349,983

echo('Amount of space to find: ' . $amountOfSpaceToFind . '<BR>');
echo('Amount of space to find2: ' . (- 70000000 + $root->totalDirectorySize + 30000000) . '<BR>');


foreach($directorySizeArray as $dirName => $dirSize) {
    if($dirSize > $amountOfSpaceToFind) {
        echo($dirName . ' : ' . $dirSize . '<BR>');
        break;
    }
}

//5404471 is too low





function printFileSystem($treeNode) {
    if($treeNode->isDirectory) {
        echo($treeNode->path . ' (D ' . $treeNode->totalDirectorySize . ') <BR>');
    } else {
        echo($treeNode->path . ' ('.$treeNode->fileSize.') <BR>');
    }

    foreach($treeNode->children as $child) {
        printFileSystem($child);
    }
}

/**
 * Calculates total size of all files within the directory and all sub directories
 * @param TreeNode $treeNode
 * @return int total size
 */
function calculateTotalDirectorySize(&$treeNode) {
    $totalSize = 0;

    //cycle through each child
    foreach($treeNode->children as $child) {
        if(!$child->isDirectory) { //its a file not a directory
            $totalSize += $child->fileSize;
        } else { //its a directory
            //get the size of all children within the directory, recursively
            $totalSize += calculateTotalDirectorySize($child);
        }
    }

    $treeNode->totalDirectorySize = $totalSize;
    return $totalSize;
    
}

/**
 * Calculates full path of directory and updates it into the TreeNode
 * @param TreeNode $treeNode
 * @return int total size
 */
function calculatePathStrings(&$treeNode) {
    $myPath = $treeNode->path ?? '/';
    //cycle through each child
    foreach($treeNode->children as $child) {

        if($myPath == '/') {
            $child->path = '/' . $child->name;
        } else {
            $child->path = $myPath . '/' . $child->name;
        }
        

        if($child->isDirectory) { //it is a directory
            calculatePathStrings($child); //recurse
        } 
    }
    
    
}

/**
 * Gets all precalculated directory sizes and puts them into an array for easy use
 * @param TreeNode $treeNode
 * @param &$outputArray ByRef the array to write into
 */
function addDirectorySizesIntoArray($treeNode, &$outputArray) {

    //add self
    if($treeNode->isDirectory) {
        $outputArray[$treeNode->path] = $treeNode->totalDirectorySize;
    }

    //cycle through each child
    foreach($treeNode->children as $child) {
        if($child->isDirectory) { //it is a directory
            $outputArray[$child->path] = $child->totalDirectorySize;

            addDirectorySizesIntoArray($child, $outputArray); //Recurse
        }
    }
    

}


