<?php

namespace Transaction\CommissionFee\Test;

use PHPUnit\Framework\TestCase;
use Transaction\CommissionFee\Exceptions\FileNotFoundException;
use Transaction\CommissionFee\TransactionCollection;

final class TransactionCollectionTest extends TestCase
{
    public function testCannotParseFromInvalidPath()
    {
        $this->expectException(FileNotFoundException::class);
        $collection = new TransactionCollection();
        $collection->parseFromCSV('invalidPath');
    }

    public function testCanParseCSVFile()
    {
        $collection = new TransactionCollection();
        $collection->parseFromCSV('./input.csv');
        $this->assertIsArray($collection->getTransactions());
    }
}
