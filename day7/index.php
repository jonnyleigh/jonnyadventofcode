<?php
/*
 * https://adventofcode.com/2022/day/7
 * 
 * You can hear birds chirping and raindrops hitting leaves as the expedition proceeds. Occasionally, you can even hear much louder sounds in the distance; how big do the animals get out here, anyway?

The device the Elves gave you has problems with more than just its communication system. You try to run a system update:

$ system-update --please --pretty-please-with-sugar-on-top
Error: No space left on device

Perhaps you can delete some files to make space for the update?

You browse around the filesystem to assess the situation and save the resulting terminal output (your puzzle input). For example:

$ cd /
$ ls
dir a
14848514 b.txt
8504156 c.dat
dir d
$ cd a
$ ls
dir e
29116 f
2557 g
62596 h.lst
$ cd e
$ ls
584 i
$ cd ..
$ cd ..
$ cd d
$ ls
4060174 j
8033020 d.log
5626152 d.ext
7214296 k

The filesystem consists of a tree of files (plain data) and directories (which can contain other directories or files). The outermost directory is called /. You can navigate around the filesystem, moving into or out of directories and listing the contents of the directory you're currently in.

Within the terminal output, lines that begin with $ are commands you executed, very much like some modern computers:

    cd means change directory. This changes which directory is the current directory, but the specific result depends on the argument:
        cd x moves in one level: it looks in the current directory for the directory named x and makes it the current directory.
        cd .. moves out one level: it finds the directory that contains the current directory, then makes that directory the current directory.
        cd / switches the current directory to the outermost directory, /.
    ls means list. It prints out all of the files and directories immediately contained by the current directory:
        123 abc means that the current directory contains a file named abc with size 123.
        dir xyz means that the current directory contains a directory named xyz.

Given the commands and output in the example above, you can determine that the filesystem looks visually like this:

- / (dir)
  - a (dir)
    - e (dir)
      - i (file, size=584)
    - f (file, size=29116)
    - g (file, size=2557)
    - h.lst (file, size=62596)
  - b.txt (file, size=14848514)
  - c.dat (file, size=8504156)
  - d (dir)
    - j (file, size=4060174)
    - d.log (file, size=8033020)
    - d.ext (file, size=5626152)
    - k (file, size=7214296)

Here, there are four directories: / (the outermost directory), a and d (which are in /), and e (which is in a). These directories also contain files of various sizes.

Since the disk is full, your first step should probably be to find directories that are good candidates for deletion. To do this, you need to determine the total size of each directory. The total size of a directory is the sum of the sizes of the files it contains, directly or indirectly. (Directories themselves do not count as having any intrinsic size.)

The total sizes of the directories above can be found as follows:

    The total size of directory e is 584 because it contains a single file i of size 584 and no other directories.
    The directory a has total size 94853 because it contains files f (size 29116), g (size 2557), and h.lst (size 62596), plus file i indirectly (a contains e which contains i).
    Directory d has total size 24933642.
    As the outermost directory, / contains every file. Its total size is 48381165, the sum of the size of every file.

To begin, find all of the directories with a total size of at most 100000, then calculate the sum of their total sizes. In the example above, these directories are a and e; the sum of their total sizes is 95437 (94853 + 584). (As in this example, this process can count files more than once!)

Find all of the directories with a total size of at most 100000. What is the sum of the total sizes of those directories?
*/



 
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
arsort($directorySizeArray);
echo('<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>');
echo('Total directory count: ' . count($directorySizeArray));


echo('<pre>');
//echo(json_encode($root));
var_dump($directorySizeArray);
//var_dump($root);
echo('</pre>');

printFileSystem($root);
echo('<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>');


///////////////// Sum all directories where total size is less than than 100000
$totalSize = 0;
foreach($directorySizeArray as $dirName => $dirSize) {
    if($dirSize < 100000) {
        $totalSize += $dirSize;
        //echo($dirName . ' : ' . $dirSize . ' <BR> ');
    }
}
echo('Total Size of all directories below 100000 : ' . $totalSize . ' <BR> '); 
//169495093 was too high
//214845076 includes root
//274490934 with proper paths - was too high
//229140951 with proper paths but no root
//276180603 with proper paths and is it me fixed



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



class TreeNode {
    public $path; //e.g. a/b/c.txt
    /** should be unique within a given directory */
    public $name;
    /** @var bool is this a directory or file */
    public $isDirectory = false;
    /** if it's a directory it should have children */
    public $children = [];
    /** if it is a file, it should have a size */
    public $fileSize;
    /** total size of all files in this directory and below it */
    public $totalDirectorySize;

    public function __construct($name, $isDirectory, $fileSize=null) {
        $this->name = $name;
        $this->isDirectory = $isDirectory;
        if(!is_null($fileSize)) {
            $this->fileSize = $fileSize;
        }
    }

    /**
     * Depth-first traversal to find first child by name
     * @param string $nameToFind to search for
     * @return TreeNode or NULL if not found
     */
    public function findChild($nameToFind) {
        //is it me you're looking for?
        //if($this->name == $nameToFind) {
        //    return $this;
        //} else {
            //check all my children
            foreach($this->children as $child) {
                //we found it in our children
                if($child->name == $nameToFind) {
                    return $child;
                }

                //our children is a directory, so search that directory too
                if($child->isDirectory) {
                    $nextLevelSearch = $child->findChild($nameToFind); //RECURSE!
                    if(!is_null($nextLevelSearch)) {
                        return $nextLevelSearch;
                    }
                }
            }

            //not found
            return null;
        //}
    }

}







/*
- / (dir)
  - a (dir)
    - e (dir)
      - i (file, size=584)
    - f (file, size=29116)
    - g (file, size=2557)
    - h.lst (file, size=62596)
  - b.txt (file, size=14848514)
  - c.dat (file, size=8504156)
  - d (dir)
    - j (file, size=4060174)
    - d.log (file, size=8033020)
    - d.ext (file, size=5626152)
    - k (file, size=7214296)
    */


    /*
    $root = new TreeNode('/', true);
    $a = new TreeNode('a', true);
    $root->children[] = $a;
    $e = new TreeNode('e', true);
    $a->children[] = $e;
    $i = new TreeNode('i', false, 584);
    $e->children[] = $i;
    $i->fileSize = 999;
    
    echo('<pre>');
    var_dump($root);
    var_dump($root->findChild('a'));
    echo('</pre>');*/