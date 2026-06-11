<?php

namespace Omnipay\Tosla\Models;

class InstallmentOptionsResponseModel extends BaseModel
{
    public ?int $Code = null;

    public ?string $Message = null;

    public ?bool $IsExist = null;

    public ?int $PlatformId = null;

    public ?int $BrandId = null;

    public ?int $CardTypeId = null;

    /** @var null|InstallmentOptionModel[] */
    public ?array $InstallmentOptions = null;

    public function setInstallmentOptions(?array $installmentOptions): void
    {
        if (empty($installmentOptions)) {
            return;
        }

        $this->InstallmentOptions = array_map(
            static fn (array $option) => new InstallmentOptionModel($option),
            $installmentOptions
        );
    }
}
