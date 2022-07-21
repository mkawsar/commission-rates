<?php

namespace Transaction\CommissionFee\Models;

class Currency
{
    /**
     * @var string
     */
    protected string $symbol;

    /**
     * @var int|float
     */
    protected int|float $rate;

    /**
     * @var int
     */
    protected int $precision;

    /**
     * Currency constructor.
     *
     * @param $symbol
     * @param $rate
     * @param $precision
     */
    public function __construct($symbol, $rate, $precision)
    {
        $this->symbol = $symbol;
        $this->rate = $rate;
        $this->precision = $precision;
    }

    /**
     * @return string
     */
    public function getSymbol(): string
    {
        return $this->symbol;
    }

    /**
     * @return int|float
     */
    public function getRate(): int|float
    {
        return $this->rate;
    }

    /**
     * @return int
     */
    public function getPrecision(): int
    {
        return $this->precision;
    }
}
