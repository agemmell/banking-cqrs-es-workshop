<?php

declare(strict_types=1);

namespace Workshop\Messages;

use EventSauce\EventSourcing\Serialization\SerializablePayload;
use Workshop\Banking\Aggregates\AccountId;

/**
 * Class MoneyWasDeposited
 *
 * @package Workshop\Messages
 */
final class MoneyWasDeposited implements SerializablePayload
{
    /**
     * @var AccountId
     */
    private $id;

    /**
     * @var int
     */
    private $amount;

    public function __construct(
        AccountId $id,
        int $amount
    ) {
        $this->id = $id;
        $this->amount = $amount;
    }

    public function id(): AccountId
    {
        return $this->id;
    }

    public function amount(): int
    {
        return $this->amount;
    }

    public static function fromPayload(array $payload): SerializablePayload
    {
        return new MoneyWasDeposited(
            AccountId::fromString($payload['id']),
            (int)$payload['amount']
        );
    }

    public function toPayload(): array
    {
        return [
            'id' => $this->id->toString(),
            'amount' => (int)$this->amount,
        ];
    }

    /**
     * @codeCoverageIgnore
     */
    public static function withIdAndAmount(AccountId $id, int $amount): MoneyWasDeposited
    {
        return new MoneyWasDeposited(
            $id,
            $amount
        );
    }
}
