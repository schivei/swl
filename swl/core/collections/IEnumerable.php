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
interface IEnumerable extends IteratorAggregate, Traversable, ArrayAccess,
 Serializable, Countable, Iterator
{

}
