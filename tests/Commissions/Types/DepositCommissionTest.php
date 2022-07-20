<?php

namespace Transaction\CommissionFee\Test\Commissions\Types;


use PHPUnit\Framework\TestCase;
use Transaction\CommissionFee\Commissions\Types\DepositCommission;
use Transaction\CommissionFee\Models\Amount;
use Transaction\CommissionFee\Models\Transaction;
use Transaction\CommissionFee\Services\CurrencyService;

final class DepositCommissionTest extends TestCase
{
    /**
     * @var CurrencyService
     */
    private $currencyService;

    /**
     * @var Transaction
     */
    private $transaction;

    /**
     * @var Amount
     */
    private $amount;

    public function setUp(): void
    {
        $this->currencyService = $this->createMock(CurrencyService::class);
        $this->transaction = $this->createMock(Transaction::class);
        $this->amount = $this->createMock(Amount::class);
    }

    public function testWillReturnAmount()
    {
        $this->transaction
            ->expects($this->atLeastOnce())
            ->method('getAmount')
            ->willReturn($this->amount);

        $this->currencyService
            ->expects($this->atLeastOnce())
            ->method('isGreater')
            ->willReturn($this->amount);

        $this->currencyService
            ->expects($this->atLeastOnce())
            ->method('getPercentageOfAmount')
            ->willReturn($this->amount);

        $commission = new DepositCommission($this->transaction, $this->currencyService);
        $commission->calculate();

        $this->assertInstanceOf(Amount::class, $commission->calculate());
    }
}
