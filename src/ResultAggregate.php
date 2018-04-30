<?php
/**
 * @see       https://github.com/phly/event-dispatcher for the canonical source repository
 * @copyright Copyright (c) 2018 Matthew Weier O'Phinney (https:/mwop.net)
 * @license   https://github.com/phly/event-dispatcher/blob/master/LICENSE.md New BSD License
 */

declare(strict_types=1);

namespace Phly\EventDispatcher;

final class ResultAggregate implements ResultAggregateInterface
{
    /**
     * @var array
     */
    private $results = [];

    /**
     * Push a result into the aggregate.
     *
     * If a result is itself a ResultAggregateInterface, loops
     * through the result set, adding each to the current aggregate.
     *
     * @param mixed $result
     */
    public function push($result) : void
    {
        if (! $result instanceof ResultAggregateInterface) {
            $this->results[] = $result;
            return;
        }

        foreach ($result as $item) {
            $this->results[] = $item;
        }
    }

    /**
     * Retrieve the first result.
     *
     * @return mixed
     */
    public function first()
    {
        $this->rewind();
        return $this->current();
    }

    /**
     * Retrieve the last result.
     *
     * @return mixed
     */
    public function last()
    {
        return end($this->results);
    }

    /**
     * @return mixed
     */
    public function current()
    {
        current($this->results);
    }

    /**
     * @return null|false|string|int
     */
    public function key()
    {
        key($this->results);
    }

    public function next() : void
    {
        next($this->results);
    }

    public function rewind() : void
    {
        reset($this->results);
    }

    public function valid() : bool
    {
        $key = $this->key();
        return null !== $key && false !== $key;
    }
}
