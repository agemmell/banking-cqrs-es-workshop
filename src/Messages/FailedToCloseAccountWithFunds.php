<?php

declare(strict_types=1);

namespace Workshop\Messages;

use EventSauce\EventSourcing\Serialization\SerializablePayload;
use Workshop\Banking\Aggregates\AccountId;

/**
 * Class FailedToCloseAccountWithFunds
 *
 * @package Workshop\Messages
 */
final class FailedToCloseAccountWithFunds implements SerializablePayload
{
    /**
     * @var AccountId
     */
    private $id;

    /**
     * @var int
     */
    private $balance;

    public function __construct(
        AccountId $id,
        int $balance
    ) {
        $this->id = $id;
        $this->balance = $balance;
    }

    public function id(): AccountId
    {
        return $this->id;
    }

    public function balance(): int
    {
        return $this->balance;
    }

    public static function fromPayload(array $payload): SerializablePayload
    {
        return new FailedToCloseAccountWithFunds(
            AccountId::fromString($payload['id']),
            (int)$payload['balance']
        );
    }

    public function toPayload(): array
    {
        return [
            'id' => $this->id->toString(),
            'balance' => (int)$this->balance,
        ];
    }

    /**
     * @codeCoverageIgnore
     */
    public static function withIdAndBalance(
        AccountId $id,
        int $balance
    ): FailedToCloseAccountWithFunds {
        return new FailedToCloseAccountWithFunds(
            $id,
            $balance
        );
    }
}
