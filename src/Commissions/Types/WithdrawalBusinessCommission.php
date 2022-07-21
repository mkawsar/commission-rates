<?php

namespace Transaction\CommissionFee\Commissions\Types;

use Transaction\CommissionFee\Commissions\Commission;
use Transaction\CommissionFee\Commissions\CommissionTypeInterface;
use Transaction\CommissionFee\Models\Amount;

class WithdrawalBusinessCommission extends Commission implements CommissionTypeInterface
{
    /**
     * @var float
     */
    const COMMISSION_PERCENTAGE = 0.5;

    /**
     * @var array
     */
    const MIN_COMMISSION = [
        'currency' => 'EUR',
        'fee' => 0.5
    ];

    /**
     * @return Amount
     */
    public function calculate(): Amount
    {
        $commission = $this->getFee(self::COMMISSION_PERCENTAGE);
        $minCommission = new Amount(self::MIN_COMMISSION['fee'], self::MIN_COMMISSION['currency']);

        if ($this->currencyService->isGreater($minCommission, $commission)) {
            return $minCommission;
        }

        return $commission;
    }
}
