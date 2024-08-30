<?php

namespace Omnipay\Tosla\Models;

class CommissionPackagesModel extends BaseModel
{
    public function __construct(?array $abstract)
    {
        parent::__construct($abstract);

        if (! empty($abstract['InstallmentRate'])) {
            foreach ($abstract['InstallmentRate'] as $key => $arg) {
                $this->InstallmentRate[$key] = new InstallmentRateModel($arg);
            }
        }
    }

    public ?string $packageName;

    public ?float $BankCommission;

    /** @var object<InstallmentRateModel>|null */
    public ?object $InstallmentRate;
}
