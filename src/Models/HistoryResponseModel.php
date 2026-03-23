<?php

namespace Omnipay\Tosla\Models;

class HistoryResponseModel extends BaseModel
{
    public function __construct(?array $abstract)
    {
        parent::__construct($abstract);

        if (! empty($abstract['Transactions'])) {
            $this->Transactions = [];
            foreach ($abstract['Transactions'] as $value) {
                $this->Transactions[] = new TransactionModel($value);
            }
        }
    }

    public ?int $Code = null;

    public ?string $Message = null;

    public ?int $Count = null;

    public ?array $Transactions = null;
}
