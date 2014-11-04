<?php

namespace swl\core\collections;

use \ArrayIterator,
    \InvalidArgumentException,
    \Iterator;

/**
 * Description of Linq
 *
 * @author schivei
 */
class Linq
{

    /**
     * @var \ArrayIterator
     */
    private $from;
    private $count = -1;

    /**
     * @param Iterator $from
     * @throws InvalidArgumentException
     */
    public function __construct(&$from)
    {
        if (!($from instanceof Iterator) && !\is_array($from))
                throw new InvalidArgumentException("Expected an Iterator instance or array by argument.");

        $items = [];

        foreach ($from as $key => &$value)
        {
            $items[$key] = $value;
        }

        $this->from = new ArrayIterator($items);

        $this->count = $this->from->count();
    }

    /**
     * @param \swl\core\collections\callable $fn
     * @return Iterator
     */
    private function loop(callable $fn)
    {
        foreach ($this->from as $key => &$curr)
        {
            if ($fn($curr, $key) === true) yield $curr;
        }
    }

    public function Count(callable $fn = null)
    {
        if (!$fn)
        {
            return $this->count;
        }

        $counter = &$this->loop($fn);
        $from    = new Linq($counter);

        return $from->Count();
    }

    public function &Where(callable $fn)
    {
        $from = &$this->loop($fn);
        $f    = new \swl\core\collections\Linq($from);

        return $f;
    }

    /**
     * @return Iterator
     */
    public function &GetIterator()
    {
        return $this->from;
    }

    public function &ToArray()
    {
        $items     = [];
        foreach ($this->from as $k => &$item) $items[$k] = $item;

        return $items;
    }

    public function &First()
    {
        $first = null;

        if ($this->Count() > 0)
        {
            $first = &$this->ToArray()[0];
        }

        return $first;
    }

    public function &Last()
    {
        $last = null;

        if ($this->Count() > 0)
        {
            $items = &$this->ToArray();
            $last  = &$items[$this->count - 1];
        }

        return $last;
    }

}
