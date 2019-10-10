<?php

declare(strict_types=1);

namespace Workshop\Messages;

use EventSauce\EventSourcing\Serialization\SerializablePayload;
use Workshop\Banking\Aggregates\AccountId;

final class OpenAccount implements SerializablePayload
{
    /**
     * @var AccountId
     */
    private $id;

    /**
     * @var string
     */
    private $type;

    /**
     * @var int
     */
    private $openingBalance;

    public function __construct(
        AccountId $id,
        string $type,
        int $openingBalance
    ) {
        $this->id = $id;
        $this->type = $type;
        $this->openingBalance = $openingBalance;
    }

    public function id(): AccountId
    {
        return $this->id;
    }

    public function type(): string
    {
        return $this->type;
    }

    public function openingBalance(): int
    {
        return $this->openingBalance;
    }

    public static function fromPayload(array $payload): SerializablePayload
    {
        return new OpenAccount(
            Workshop\Banking\Aggregates\AccountId::fromString($payload['id']),
            (string)$payload['type'],
            (int)$payload['openingBalance']
        );
    }

    public function toPayload(): array
    {
        return [
            'id' => $this->id->toString(),
            'type' => (string)$this->type,
            'openingBalance' => (int)$this->openingBalance,
        ];
    }

    /**
     * @codeCoverageIgnore
     */
    public static function withIdAndTypeAndOpeningBalance(AccountId $id, string $type, int $openingBalance): OpenAccount
    {
        return new OpenAccount(
            $id,
            $type,
            $openingBalance
        );
    }
}
