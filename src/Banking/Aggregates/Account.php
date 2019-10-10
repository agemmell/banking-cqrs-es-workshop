<?php

declare(strict_types=1);

namespace Workshop\Banking\Aggregates;

use EventSauce\EventSourcing\AggregateRoot;
use EventSauce\EventSourcing\AggregateRootBehaviour;
use Workshop\Messages\AccountWasOpened;
use Workshop\Messages\CloseAccount;
use Workshop\Messages\DepositMoney;
use Workshop\Messages\FailedToCloseAccountWithFunds;
use Workshop\Messages\MoneyWasDeposited;
use Workshop\Messages\MoneyWasWithdrawn;
use Workshop\Messages\OpenAccount;
use Workshop\Messages\WithdrawFailedDueToInsufficientFunds;
use Workshop\Messages\WithdrawMoney;

/**
 * Class Account
 *
 * @package Workshop\Banking\Aggregates
 */
class Account implements AggregateRoot
{
    use AggregateRootBehaviour;


    /** @var AccountId */
    private $id;
    /** @var string */
    private $type;
    /** @var int */
    private $balance;

    public function openAccount(OpenAccount $command)
    {
        // "Always" (no constraint so nothing to check)
        $this->recordThat(new AccountWasOpened($command->id(), $command->type(), $command->openingBalance()));
    }

    /**
     * @param AccountWasOpened $event
     */
    public function applyAccountWasOpened(AccountWasOpened $event)
    {
        $this->id = $event->id();
        $this->type = $event->type();
        $this->balance = $event->openingBalance();
    }

    /**
     * @param DepositMoney $command
     */
    public function depositMoney(DepositMoney $command)
    {
        if ($this->id === $command->id()) {
            $this->recordThat(new MoneyWasDeposited($command->id(), $command->amount()));
        }
    }

    /**
     * @param MoneyWasDeposited $event
     */
    public function applyMoneyWasDeposited(MoneyWasDeposited $event)
    {
        $this->balance += $event->amount();
    }

    public function withdrawMoney(WithdrawMoney $command)
    {
        if ($this->balance >= $command->amount()) {
            $this->recordThat(new MoneyWasWithdrawn($command->id(), $command->amount()));
        } else {
            $this->recordThat(new WithdrawFailedDueToInsufficientFunds($command->id(), $command->amount()));
        }
    }

    public function applyMoneyWasWithdrawn(MoneyWasWithdrawn $event)
    {
        $this->balance -= $event->amount();
    }

    public function applyWithdrawFailedDueToInsufficientFunds(WithdrawFailedDueToInsufficientFunds $event)
    {
        // nothing happens
    }

    public function closeAccount(CloseAccount $command)
    {
        if ($this->balance > 0) {
            $this->recordThat(new FailedToCloseAccountWithFunds($command->id(), $this->balance));
        }
    }

    public function applyFailedToCloseAccountWithFunds(FailedToCloseAccountWithFunds $event)
    {
        // do nothing
    }

}
