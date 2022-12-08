<?php
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
