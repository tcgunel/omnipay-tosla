<?php

namespace Omnipay\Tosla\Traits;

trait HasResponse
{
    public string $rawResult;

    /**
     * Yapılan isteğin sonucunu bildirir. İşlem başarılı ise success, hatalı ise failure döner.
     *
     * @var string
     */
    public string $status;

    public $errorCode;

    public $errorMessage;

    public $errorGroup;

    /**
     * İstekte belirtilen locale değeri geri dönülür, varsayılan değeri trdir.
     *
     * @var
     */
    public $locale;

    public $systemTime;

    public string $conversationId;
}
