<?php

namespace Transaction\CommissionFee\Commissions;

use Transaction\CommissionFee\Models\Amount;

interface CommissionTypeInterface
{
    /**
     * @return Amount
     */
    public function calculate(): Amount;
}
