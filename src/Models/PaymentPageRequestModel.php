<?php

namespace Omnipay\Tosla\Models;

class PaymentPageRequestModel extends BaseModel
{
    public string $clientId;

    public string $apiUser;

    /**
     * İşlem için üretilmiş random değer. hash içerisinde kullanılan değer ile aynı olmalıdır.
     */
    public string $rnd;

    /**
     * İşlem tarihi (yyyyMMddHHmmss). hash içerisinde kullanılan değer ile aynı olmalıdır.
     * İşlem anında verilen tarih ve saat bilgisi olmalıdır.
     * GTM+3 zaman diliminde ve max 1 saat farka izin verilmektedir.
     * Diğer durumlarda hash hatası alınır.
     */
    public string $timeSpan;

    public string $hash;

    /**
     * Taksitli işlemlerde Komisyonlu Ödeme alınmak istendiği durumlarda 1 olarak gönderilmelidir.
     * Dİğer durumlarda 0 veya gönderilmez.
     */
    public string $callbackUrl;

    public ?int $isCommission = 0;

    public string $orderId;

    /**
     * İşlem Tutarı, son iki hane kuruştur. 1522 = 15 TL 22 Kuruş
     */
    public string $amount;

    /**
     * İşlem Para birimi 949
     */
    public int|string $currency;

    public int $installmentCount = 0;

    /**
     * İşleme ait açıklama
     */
    public ?string $description = null;

    /**
     * İstek sonucunda geri gönderilecek bilgi alanı
     */
    public ?string $echo = null;

    /**
     * Ekstra bilgilerin gönderildiği alan. Inquiry servisi cevabında döner
     */
    public ?string $extraParameters = null;

    public string $cardHolderName;
    public string $cardNo;
    public string $expireDate;
    public string $cvv;

    /**
     * isCommission değeri 1 gönderildiği durumlarda zorunludur.
     * totalAmount değeri GetInstallmentOptions’ta dönen
     * taksite karşılık gelen değer gönderilmelidir.
     * isCommission değeri 1 gönderildiğinde bankaya gönderilen tutardır,
     * son iki hane kuruştur. 1528 = 15 TL 28 Kuruş
     */
    public string $totalAmount;
}
