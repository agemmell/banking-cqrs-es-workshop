<?php

declare(strict_types=1);

namespace Workshop\Banking\Aggregates;

use EventSauce\EventSourcing\AggregateRootId;
use Ramsey\Uuid\Uuid;

/**
 * Class AccountId
 *
 * @package Workshop\Banking\Aggregates
 */
final class AccountId implements AggregateRootId
{

    private $id;

    /**
     * AccountId constructor.
     *
     * @param string $id
     */
    private function __construct(string $id)
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function toString(): string
    {
        return $this->id;
    }

    /**
     * @param string $aggregateRootId
     *
     * @return static
     */
    public static function fromString(string $aggregateRootId): AggregateRootId
    {
        return new static($aggregateRootId);
    }

    /**
     * @return static
     * @throws \Exception
     */
    public static function create(): AggregateRootId
    {
        return new static(Uuid::uuid4()->toString());
    }
}
