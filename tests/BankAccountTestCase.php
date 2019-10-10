<?php

namespace Workshop\Tests;

use EventSauce\EventSourcing\AggregateRootId;
use EventSauce\EventSourcing\AggregateRootTestCase;
use Workshop\Banking\Aggregates\Account;
use Workshop\Banking\Aggregates\AccountId;
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
 * Class BankAccountTestCase
 *
 * @package Workshop\Tests
 */
class BankAccountTestCase extends AggregateRootTestCase
{
    /**
     * @test
     */
    public function can_open_an_account()
    {
        $id = $this->aggregateRootId();
        $this->when(
            new OpenAccount($id, 'Checking', 0)
        )->then(
            new AccountWasOpened($id, 'Checking', 0)
        );
    }

    /**
     * @return AggregateRootId
     * @throws \Exception
     */
    protected function newAggregateRootId(): AggregateRootId
    {
        return AccountId::create();
    }

    /**
     * @test
     */
    public function can_deposit_money()
    {
        $id = $this->aggregateRootId();
        $this->given(
            new AccountWasOpened($id, 'Checking', 0)
        )->when(
            new DepositMoney($id, 25)
        )->then(
            new MoneyWasDeposited($id, 25)
        );
    }

    /**
     * @test
     */
    public function can_withdraw_money()
    {
        $id = $this->aggregateRootId();
        $this->given(
            new AccountWasOpened($id, 'Checking', 0),
            new MoneyWasDeposited($id, 25)
        )->when(
            new WithdrawMoney($id, 10)
        )->then(
            new MoneyWasWithdrawn($id, 10)
        );
    }

    /**
     * @test
     */
    public function withdraw_money_failed_due_to_insufficient_funds()
    {
        $id = $this->aggregateRootId();
        $this->given(
            new AccountWasOpened($id, 'Checking', 0),
            new MoneyWasDeposited($id, 25),
            new MoneyWasWithdrawn($id, 10)
        )->when(
            new WithdrawMoney($id, 50)
        )->then(
            new WithdrawFailedDueToInsufficientFunds($id, 50)
        );
    }

    /**
     * @test
     */
    public function close_account()
    {
        $id = $this->aggregateRootId();
        $this->given(
            new AccountWasOpened($id, 'Checking', 0),
            new MoneyWasDeposited($id, 25),
            new MoneyWasWithdrawn($id, 10)
        )->when(
            new CloseAccount($id)
        )->then(
            new FailedToCloseAccountWithFunds($id, 15)
        );
    }

    /**
     * @param object $command
     */
    protected function handle(object $command)
    {
        /** @var Account $accountAggregate */
        $accountAggregate = $this->repository->retrieve($command->id());

        $commandType = get_class($command);
        switch ($commandType) {
            case OpenAccount::class:
                $accountAggregate->openAccount($command);
                break;
            case DepositMoney::class:
                $accountAggregate->depositMoney($command);
                break;
            case WithdrawMoney::class:
                $accountAggregate->withdrawMoney($command);
                break;
            case CloseAccount::class:
                $accountAggregate->closeAccount($command);
                break;
        }

        $this->repository->persist($accountAggregate);
    }

    /**
     * @return string
     */
    protected function aggregateRootClassName(): string
    {
        return Account::class;
    }
}
