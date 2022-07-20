<?php

namespace Transaction\CommissionFee\Test\Services;

use PHPUnit\Framework\TestCase;
use Transaction\CommissionFee\Commissions\CommissionTypeInterface;
use Transaction\CommissionFee\Commissions\Types\DepositCommission;
use Transaction\CommissionFee\Commissions\Types\WithdrawalBusinessCommission;
use Transaction\CommissionFee\Commissions\Types\WithdrawPrivateCommission;
use Transaction\CommissionFee\Models\Amount;
use Transaction\CommissionFee\Models\Transaction;
use Transaction\CommissionFee\Services\CommissionService;
use Transaction\CommissionFee\Services\CurrencyService;
use Transaction\CommissionFee\TransactionCollection;

final class CommissionServiceTest extends TestCase
{
    /**
     * @var CurrencyService
     */
    private $currencyService;

    /**
     * @var TransactionCollection
     */
    private $transactionCollection;

    /**
     * @var Transaction
     */
    private $transaction;

    /**
     * @var CommissionTypeInterface
     */
    private $commissionType;

    /**
     * @var Amount
     */
    private $amount;

    public function setUp(): void
    {
        $this->currencyService = $this->createMock(CurrencyService::class);
        $this->transactionCollection = $this->createMock(TransactionCollection::class);
        $this->transaction = $this->createMock(Transaction::class);
        $this->commissionType = $this->createMock(DepositCommission::class);
        $this->amount = $this->createMock(Amount::class);
        $this->transaction = $this->createMock(Transaction::class);
    }

    public function testCalculateFeeFromCollectionWillReturnCorrectSizeOfArray()
    {
        $transactions = [];
        for ($i = 0; $i < rand(1, 10); $i++) {
            $transactions[] = $this->transaction;
        }
        $transactionCount = count($transactions);

        $this->transactionCollection
            ->expects($this->once())
            ->method('getTransactions')
            ->willReturn($transactions);

        $this->currencyService
            ->expects($this->exactly($transactionCount))
            ->method('roundAndFormat')
            ->willReturn('1');

        $this->commissionType
            ->expects($this->exactly($transactionCount))
            ->method('calculate')
            ->willReturn($this->amount);

        $stub = $this->getMockBuilder(CommissionService::class)
            ->setConstructorArgs([$this->currencyService])
            ->setMethods(['generateCommission'])
            ->getMock();

        $stub->method('generateCommission')
            ->willReturn($this->commissionType);

        $this->assertCount($transactionCount, $stub->calculateFeesFromCollection($this->transactionCollection));
    }

    public function testGeneratesDepositCommission()
    {
        $this->transaction
            ->expects($this->once())
            ->method('getOperationType')
            ->willReturn('deposit');

        $calculator = new CommissionService($this->currencyService);

        $this->assertInstanceOf(
            DepositCommission::class,
            $calculator->generateCommission($this->currencyService, $this->transaction, $this->transactionCollection)
        );
    }

    public function testGeneratesWithdrawBusinessCommission()
    {
        $this->transaction
            ->expects($this->once())
            ->method('getOperationType')
            ->willReturn('withdraw');

        $this->transaction
            ->expects($this->once())
            ->method('getUserType')
            ->willReturn('business');

        $calculator = new CommissionService($this->currencyService);

        $this->assertInstanceOf(
            WithdrawalBusinessCommission::class,
            $calculator->generateCommission($this->currencyService, $this->transaction, $this->transactionCollection)
        );
    }

    public function testGeneratesWithdrawPrivateCommission()
    {
        $this->transaction
            ->expects($this->once())
            ->method('getOperationType')
            ->willReturn('withdraw');

        $this->transaction
            ->expects($this->once())
            ->method('getUserType')
            ->willReturn('private');

        $calculator = new CommissionService($this->currencyService);

        $this->assertInstanceOf(
            WithdrawPrivateCommission::class,
            $calculator->generateCommission($this->currencyService, $this->transaction, $this->transactionCollection)
        );
    }
}
