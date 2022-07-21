<?php

namespace Transaction\CommissionFee\Models;

class Amount
{
    /**
     * @var int|float
     */
    protected int|float $amount;

    /**
     * @var string
     */
    protected string $symbol;

    /**
     * Amount constructor.
     *
     * @param $amount
     * @param $symbol
     */
    public function __construct($amount, $symbol)
    {
        $this->amount = $amount;
        $this->symbol = $symbol;
    }

    /**
     * @return string
     */
    public function getSymbol(): string
    {
        return $this->symbol;
    }

    public function getAmount(): int|float
    {
        return $this->amount;
    }
}
