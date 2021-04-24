<?php


namespace App\Classes;

use Exception;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * TagStack singleton class.
 * The tag stack is order of the tags are controlled...
 * Tags are taken off the stack either parsed or updated/saved to be parsed in the future
 **/
class TagStack
{

    private $stack=[];
    private $templateStack=[];
    private $templateStackId=[];
    private $index;


    // Hold the class instance.
    private static $instance = null;

    // The constructor is private
    // to prevent initiation with outer code.
    private function __construct()
    {
    }

    // The object is created from within the class itself
    // only if the class has no instance.
    public static function getInstance()
    {
        if (self::$instance == null) {
            self::$instance = new TagStack();
        }

        return self::$instance;
    }

    /**
     * @param $value
     * @param $key
     * @throws \Exception
     */
    public function push($value, $key)
    {
        $this->resetPointer();
        if ($this->index=='' || is_null($this->index)) {
            throw new Exception("TagStack index not set");
        }

        $maxStackId = count($this->templateStackId[$this->index]);
        $maxStack = count($this->stack[$this->index]);
        $this->templateStackId[$this->index][$maxStackId]=$key;
        $this->stack[$this->index][$maxStack]=$this->prestore($value);
    }


    public function pop()
    {

        if (isset($this->stack[$this->index])) {
            $stackIdMax = count($this->templateStackId[$this->index])-1;
            $stackMax = count($this->stack[$this->index])-1;

            if (isset($this->stack[$this->index][$stackMax])) {
                $lastItem = $this->stack[$this->index][$stackMax];
                unset($this->stack[$this->index][$stackMax]);
                unset($this->templateStackId[$this->index][$stackIdMax]);

                $this->stack = $this->stack;
                $this->templateStackId = $this->templateStackId;


                return $this->unstore($lastItem);
            } else {
                return false;
            }
        } else {
            return false;
        }
    }



    public function prestore($item)
    {
        return $item;
        return serialize($item);
    }

    public function unstore($item)
    {
        return $item;
        return unserialize($item);
    }



    public function count()
    {
        return count($this->stack[$this->index]);
    }

    public function lastItem()
    {
        if (!empty($this->stack[$this->index])) {
            $max = $this->count()-1;
            if (!empty($this->stack[$this->index][$max])) {
                return $this->stack[$this->index][$max];
            }
        }
        return false;
    }

    public function getPositionOfTag($tagId)
    {

        foreach ($this->stack[$this->index] as $i => $tagInStack) {
            $item = $this->item($i);
            if ($item->getTagId() === $tagId) {
                return $i;
            }
        }

        return false;
    }

    public function previousItemByCurrentPosition($i)
    {

        $previousI = $i-1;

        if (isset($this->stack[$this->index][$previousI])) {
            return $this->item($previousI);
        }
        return false;
    }

    public function getItemCurrentPosition($i)
    {


        if (isset($this->stack[$this->index][$i])) {
            return $this->item($i);
        }
        return false;
    }

    public function previousItemByTagId($tagId)
    {


        $i = $this->getPositionOfTag($tagId);


        if ($i !== false) {
            return $this->previousItemByCurrentPosition($i);
        }

        return false;
    }

    public function previousItem()
    {
        if (!empty($this->stack[$this->index])) {
            $max = $this->count()-2;
            if (!empty($this->stack[$this->index][$max])) {
                return $this->stack[$this->index][$max];
            }
        }
        return false;
    }


    public function resetPointer()
    {

        if ($this->stack && $this->index && isset($this->stack[$this->index])) {
            end($this->stack[$this->index]);
        }
    }

    public function item($i)
    {
        $this->resetPointer();
        if (!isset($this->stack[$this->index][$i])) {
            throw new \Exception("Cannot find index [$i] in tag stack");
        }

        return $this->unstore($this->stack[$this->index][$i]);
    }


    public function exists($i)
    {
        $this->resetPointer();
        if (!isset($this->templateStackId[$this->index][$i])) {
            return false;
        }

        return true;
    }

    public function maxPosition()
    {

        $this->resetPointer();
        if (empty($this->stack[$this->index])) {
            return false;
        }

        return max(array_keys($this->stack[$this->index]));
    }


    public function isFinalTag()
    {

        if (is_null($this->index) && empty($this->stack)) {
            return true;
        }

        return (count($this->stack[$this->index])===1?true:false);
    }


    public function overWrite($tag, $i = false)
    {

        $this->resetPointer();
        if (!$i) {
            $i = $this->maxPosition();
        }
        unset($this->stack[$this->index][$i]);
        return $this->stack[$this->index][$i]=$this->prestore($tag);
    }


    public function incIndex($tagId)
    {



        $this->index=$tagId;
        $this->templateStack[]=$tagId;
        $this->stack[$tagId]=[];
        $this->templateStackId[$tagId]=[];

        $this->index=$tagId;
    }


    public function decIndex($tagId)
    {



        unset($this->stack[$tagId]);
        unset($this->templateStackId[$tagId]);
        array_pop($this->templateStack);
        if (!empty($this->templateStack)) {
            $this->index = end($this->templateStack);
        } else {
            $this->index = null;
        }
    }

    public function getIndex()
    {

        return $this->index;
    }


    public function getStack($which = 'stack')
    {

        return $this->$which;
    }

    public function destroy()
    {
        self::$instance = null;
    }
}
