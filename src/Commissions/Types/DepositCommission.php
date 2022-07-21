<?php

namespace Transaction\CommissionFee\Commissions\Types;

use Transaction\CommissionFee\Commissions\Commission;
use Transaction\CommissionFee\Commissions\CommissionTypeInterface;
use Transaction\CommissionFee\Models\Amount;

class DepositCommission extends Commission implements CommissionTypeInterface
{
    /**
     * @var float
     */
    const COMMISSION_PERCENTAGE = 0.03;

    /**
     * @var array
     */
    const MAX_COMMISSION = [
        'currency' => 'EUR',
        'fee' => 5
    ];

    /**
     * @return Amount
     */
    public function calculate(): Amount
    {
        $commission = $this->getFee(self::COMMISSION_PERCENTAGE);
        $maxCommission = new Amount(self::MAX_COMMISSION['fee'], self::MAX_COMMISSION['currency']);

        if ($this->currencyService->isGreater($commission, $maxCommission)) {
            return $maxCommission;
        }

        return $commission;
    }
}
