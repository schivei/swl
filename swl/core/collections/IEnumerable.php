<?php

namespace swl\core\collections;

use \ArrayAccess,
    \Countable,
    \IteratorAggregate,
    \Iterator,
    \Serializable,
    \Traversable;

/**
 *
 * @author schivei
 */
interface IEnumerable extends ArrayAccess,
 Serializable, Countable, Iterator
{
    function Count(callable $fn = null);

    function &Where(callable $fn);

    function &GetIterator();

    function &ToArray();

    function &First();

    function &Last();
}
