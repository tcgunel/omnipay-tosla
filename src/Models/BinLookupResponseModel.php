<?php

namespace Omnipay\Tosla\Models;

class BinLookupResponseModel extends BaseModel
{
    public function __construct(?array $abstract)
    {
        parent::__construct($abstract);
    }

    public ?int $Code = null;
    public ?string $Message = null;
    public ?int $CardPrefix = null;
    public ?int $BankId = null;
    public ?string $BankCode = null;
    public ?string $BankName = null;
    public ?string $CardName = null;
    public ?string $CardClass = null;
    public ?string $CardType = null;
    public ?string $Country = null;

    /** @var null|CommissionPackagesModel[] */
    public ?array $CommissionPackages = null;
}
