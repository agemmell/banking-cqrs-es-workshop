<?php

declare(strict_types=1);

namespace Workshop\Messages;

use EventSauce\EventSourcing\Serialization\SerializablePayload;
use Workshop\Banking\Aggregates\AccountId;

/**
 * Class CloseAccount
 *
 * @package Workshop\Messages
 */
final class CloseAccount implements SerializablePayload
{
    /**
     * @var AccountId
     */
    private $id;

    public function __construct(
        AccountId $id
    ) {
        $this->id = $id;
    }

    public function id(): AccountId
    {
        return $this->id;
    }

    public static function fromPayload(array $payload): SerializablePayload
    {
        return new CloseAccount(
            AccountId::fromString($payload['id'])
        );
    }

    public function toPayload(): array
    {
        return [
            'id' => $this->id->toString(),
        ];
    }

    /**
     * @codeCoverageIgnore
     */
    public static function withId(AccountId $id): CloseAccount
    {
        return new CloseAccount(
            $id
        );
    }
}
