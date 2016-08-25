<?php

namespace swl\core\collections;

use \ArrayIterator,
    \OverflowException,
    \swl\core\collections\IEnumerable,
    \swl\core\collections\Linq,
    \swl\core\types\TypeString;

/**
 * Description of Enumerable
 *
 * @author schivei
 */
abstract class Enumerable extends Linq implements IEnumerable
{

    public function offsetExists($offset)
    {
        return $offset < $this->count();
    }

    public function offsetGet($offset)
    {
        return $this->offsetExists($offset) ? $this->value[$offset] : NULL;
    }

    public function offsetSet($offset, $value)
    {
        if ($this->offsetExists($offset)) $this->value[$offset] = $value;
        else if ($this->count() === $offset) $this->value .= $value;
        else
                throw new OverflowException(TypeString::Format("A posição %d está fora do limite.",
                                                           $offset));
    }

    public function offsetUnset($offset)
    {
        if ($this->offsetExists($offset))
        {
            $this->value = \substr($this->value, 0, $offset) . \substr($this->value,
                                                                       $offset);
        }
    }

    public function serialize()
    {
        return \serialize($this->value);
    }

    public function unserialize($serialized)
    {
        $this->value = \unserialize($serialized);
    }

    private $position = -1;

    public function current()
    {
        return $this->offsetExists($this->position) ? $this->offsetGet($this->position)
                    : NULL;
    }

    public function next()
    {
        $this->position++;
    }

    public function key()
    {
        return $this->offsetExists($this->position) ? $this->position : NULL;
    }

    public function valid()
    {
        return $this->offsetExists($this->position);
    }

    public function rewind()
    {
        $this->position--;
    }

}
