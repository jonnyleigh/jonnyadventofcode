<?php
class Stack
{
    protected $stack;
    protected $limit;

    public function __construct($values = array()) {
        // initialize the stack
        $this->stack = array_reverse($values);

    }

    public function push($item) {
        // prepend item to the start of the array
        array_unshift($this->stack, $item);

    }

    public function pop() {
        if ($this->isEmpty()) {
            // trap for stack underflow
          throw new RunTimeException('Stack is empty!');
      } else {
            // pop item from the start of the array
            return array_shift($this->stack);
        }
    }

    public function top() {
        return current($this->stack);
    }

    public function isEmpty() {
        return empty($this->stack);
    }
}