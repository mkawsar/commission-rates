<?php

namespace Transaction\CommissionFee\Models;

class Transaction
{
    /**
     * @var int
     */
    protected $transactionID;

    /**
     * @var \DateTime
     */
    protected $transactionDate;

    /**
     * @var string
     */
    protected $identificationNumber;

    /**
     * @var string
     */
    protected $userType;

    /**
     * @var string
     */
    protected $operationType;

    /**
     * @var Amount
     */
    protected $amount;

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

    public function getTransactionID()
    {
        return $this->transactionID;
    }

    /**
     * @return int
     */
    public function getIdentificationNumber()
    {
        return $this->identificationNumber;
    }

    /**
     * @return string
     */
    public function getOperationType()
    {
        return $this->operationType;
    }

    /**
     * @return string
     */
    public function getUserType()
    {
        return $this->userType;
    }

    /**
     * @return \DateTime
     */
    public function getTransactionDate()
    {
        return $this->transactionDate;
    }

    /**
     * @return Amount
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * @return string
     */
    public function getAmountSymbol()
    {
        return $this->amount->getSymbol();
    }
}
