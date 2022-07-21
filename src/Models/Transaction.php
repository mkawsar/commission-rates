<?php

namespace Transaction\CommissionFee\Models;

use Cassandra\Date;

class Transaction
{
    /**
     * @var int
     */
    protected int $transactionID;

    /**
     * @var \DateTime
     */
    protected \DateTime $transactionDate;

    /**
     * @var string
     */
    protected string $identificationNumber;

    /**
     * @var string
     */
    protected string $userType;

    /**
     * @var string
     */
    protected string $operationType;

    protected Amount $amount;

    /**
     * Transaction constructor.
     *
     * @param $transactionID
     * @param \DateTime $transactionDate
     * @param $identificationNumber
     * @param $userType
     * @param $operationType
     * @param Amount $amount
     */
    public function __construct(
        $transactionID,
        \DateTime $transactionDate,
        $identificationNumber,
        $userType,
        $operationType,
        Amount $amount
    )
    {
        $this->transactionID = $transactionID;
        $this->transactionDate = $transactionDate;
        $this->identificationNumber = $identificationNumber;
        $this->userType = $userType;
        $this->operationType = $operationType;
        $this->amount = $amount;
    }

    public function getTransactionID(): int
    {
        return $this->transactionID;
    }

    /**
     * @return int
     */
    public function getIdentificationNumber(): int
    {
        return $this->identificationNumber;
    }

    /**
     * @return string
     */
    public function getOperationType(): string
    {
        return $this->operationType;
    }

    /**
     * @return string
     */
    public function getUserType(): string
    {
        return $this->userType;
    }

    /**
     * @return \DateTime
     */
    public function getTransactionDate(): \DateTime
    {
        return $this->transactionDate;
    }

    public function getAmount(): Amount
    {
        return $this->amount;
    }

    /**
     * @return string
     */
    public function getAmountSymbol(): string
    {
        return $this->amount->getSymbol();
    }
}
