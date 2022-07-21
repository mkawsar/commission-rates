<?php

namespace Transaction\CommissionFee;

use League\Csv\Reader;
use Transaction\CommissionFee\Exceptions\FileNotFoundException;
use Transaction\CommissionFee\Models\Amount;
use Transaction\CommissionFee\Models\Transaction;

class TransactionCollection
{
    protected array $transactions = [];

    public function parseFromCSV($path, $append = false)
    {
        if (!file_exists($path)) {
            throw new FileNotFoundException;
        }

        $this->transactions = $append ? $this->transactions : [];

        foreach (Reader::createFromPath($path) as $item) {
            $this->add(new Transaction(
                $this->generateTransactionID(),
                new \DateTime($item[0]),
                $item[1],
                $item[2],
                $item[3],
                new Amount($item[4], $item[5])
            ));
        }
    }

    /**
     * @return int
     */
    private function generateTransactionID(): int
    {
        return $this->isEmpty() ? 1 : end($this->transactions)->getTransactionID() + 1;
    }

    /**
     * @return Transaction[]
     */
    public function getTransactions(): array
    {
        return $this->transactions;
    }

    /**
     * @param Transaction $transaction
     *
     * @return $this
     */
    public function add(Transaction $transaction): self
    {
        $this->transactions[] = $transaction;

        return $this;
    }

    /**
     * @return bool
     */
    private function isEmpty(): bool
    {
        return empty($this->getTransactions());
    }
}
