<?php

namespace Transaction\CommissionFee\Test\Commissions\Types;

use PHPUnit\Framework\TestCase;
use Transaction\CommissionFee\Commissions\Types\WithdrawalBusinessCommission;
use Transaction\CommissionFee\Models\Amount;
use Transaction\CommissionFee\Models\Transaction;
use Transaction\CommissionFee\Services\CurrencyService;

final class WithdrawalBusinessCommissionTest extends TestCase
{
    /**
     * @var CurrencyService
     */
    private CurrencyService $currencyService;

    /**
     * @var Transaction
     */
    private Transaction $transaction;

    /**
     * @var Amount
     */
    private Amount $amount;

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

        $commission = new WithdrawalBusinessCommission($this->transaction, $this->currencyService);
        $commission->calculate();

        $this->assertInstanceOf(
            Amount::class,
            $commission->calculate()
        );
    }
}
