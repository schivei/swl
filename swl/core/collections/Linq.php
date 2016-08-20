<?php

namespace swl\core\collections;

use \ArrayIterator,
    \InvalidArgumentException,
    \Iterator,
    \swl\core\types\Object;

/**
 * Description of Linq
 *
 * @author schivei
 */
class Linq extends Object
{

    private $count = -1;

    /**
     * @param Iterator $from
     * @throws InvalidArgumentException
     */
    public function __construct(&$from)
    {
        parent::__construct();

        if (!($from instanceof Iterator) && !\is_array($from))
                throw new InvalidArgumentException("Expected an Iterator instance or array by argument.");

        $items = [];

        if ((is_array($from) && count($from) > 0) || ($from instanceof Iterator &&
                $from->valid()))
        {
            foreach ($from as $key => &$value)
            {
                $items[$key] = $value;
            }
        }

        $this->value = new ArrayIterator($items);

        $this->count = $this->value->count();
    }

    /**
     * @param \swl\core\collections\callable $fn
     * @return Iterator
     */
    private function loop(callable $fn)
    {
        foreach ($this->value as $key => &$curr)
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

        $counter = $this->loop($fn);
        $from    = new \swl\core\collections\Linq($counter);

        return $from->Count();
    }

    public function &Where(callable $fn)
    {
        $from = $this->loop($fn);
        $f    = new \swl\core\collections\Linq($from);

        return $f;
    }

    /**
     * @return Iterator
     */
    public function &GetIterator()
    {
        return $this->value;
    }

    public function &ToArray()
    {
        $items     = [];
        foreach ($this->value as $k => &$item) $items[$k] = $item;

        return $items;
    }

    public function &First()
    {
        $first = null;

        if ($this->Count() > 0)
        {
            $first = $this->ToArray()[0];
        }

        return $first;
    }

    public function &Last()
    {
        $last = null;

        if ($this->Count() > 0)
        {
            $items = $this->ToArray();
            $last  = &$items[$this->count - 1];
        }

        return $last;
    }

}
