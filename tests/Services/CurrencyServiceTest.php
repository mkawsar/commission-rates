<?php

namespace Transaction\CommissionFee\Test\Services;

use PHPUnit\Framework\TestCase;
use Transaction\CommissionFee\Models\Amount;
use Transaction\CommissionFee\Services\CurrencyService;

final class CurrencyServiceTest extends TestCase
{
    /**
     * @var CurrencyService
     */
    private $service;

    public function setUp(): void
    {
        $this->service = new CurrencyService();
        $this->service->collectCurrenciesFromArray([
            [
                'symbol' => 'EUR',
                'rate' => 1,
                'precision' => 2,
            ],
            [
                'symbol' => 'USD',
                'rate' => 5,
                'precision' => 2,
            ],
            [
                'symbol' => 'JPY',
                'rate' => 10,
                'precision' => 0,
            ]
        ]);
    }

    public function testConvert()
    {
        $this->assertEquals(
            new Amount(500, 'USD'),
            $this->service->convert(new Amount(100, 'EUR'), 'USD')
        );

        $this->assertEquals(
            new Amount(10, 'JPY'),
            $this->service->convert(new Amount(5, 'USD'), 'JPY')
        );
    }

    public function testRoundAndFormat()
    {
        $this->assertEquals(
            100.01,
            $this->service->roundAndFormat(new Amount(100.001, 'EUR'))
        );
        $this->assertEquals(
            101,
            $this->service->roundAndFormat(new Amount(100.001, 'JPY'))
        );
    }

    public function testPercentageOfAmount()
    {
        $this->assertEquals(
            new Amount(10, 'EUR'),
            $this->service->getPercentageOfAmount(new Amount(100, 'EUR'), 10)
        );
    }

    public function testIsGreater()
    {
        $this->assertEquals(
            true,
            $this->service->isGreater(
                new Amount(100, 'EUR'),
                new Amount(100, 'USD')
            )
        );

        $this->assertEquals(
            false,
            $this->service->isGreater(
                new Amount(100, 'JPY'),
                new Amount(100, 'USD')
            )
        );
    }

    public function testSumAmounts()
    {
        $this->assertEquals(
            new Amount(120, 'JPY'),
            $this->service->sumAmounts(
                new Amount(10, 'EUR'),
                new Amount(10, 'USD'),
                'JPY'
            )
        );
    }

    public function testSubAmount()
    {
        $this->assertEquals(
            new Amount(80, 'JPY'),
            $this->service->subAmount(
                new Amount(10, 'EUR'),
                new Amount(10, 'USD'),
                'JPY'
            )
        );
    }
}
