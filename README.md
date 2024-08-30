[![License](https://poser.pugx.org/tcgunel/omnipay-tosla/license)](https://packagist.org/packages/tcgunel/omnipay-tosla)
[![Buy us a tree](https://img.shields.io/badge/Treeware-%F0%9F%8C%B3-lightgreen)](https://plant.treeware.earth/tcgunel/omnipay-tosla)
[![PHP Composer](https://github.com/tcgunel/omnipay-tosla/actions/workflows/tests.yml/badge.svg)](https://github.com/tcgunel/omnipay-tosla/actions/workflows/tests.yml)

# Omnipay Tosla Gateway
Omnipay gateway for Tosla. All the methods of Tosla implemented for easy usage.

## Requirements
| PHP  | Package |
|------|---------|
| ^8.0 | v1.0.0  |

## Installment

```
composer require tcgunel/omnipay-tosla
```

## Usage

Please see the [Wiki](https://github.com/tcgunel/omnipay-tosla/wiki) page for detailed usage of every method.

[Tosla developer center](https://tosla.com/isim-icin/gelistirici-merkezi)

Check /examples folder for examples.

## Methods
#### Payment Services

* binLookup($options) // [Bin Sorgulama](https://tosla.com/isim-icin/gelistirici-merkezi#taksit-bilgisi)
* purchase($options) // [3D Secure](https://tosla.com/isim-icin/gelistirici-merkezi#3d-islem-baslatma) ile yada [3D Secure olmadan](https://tosla.com/isim-icin/gelistirici-merkezi#non3d-ile-odeme) ödeme.
* paymentPage($options) // [Ortak Ödeme Sayfası](https://tosla.com/isim-icin/gelistirici-merkezi#ortak-odeme-sayfasi)
* verifyEnrolment($options) // [3D Ödeme Doğrulama](https://tosla.com/isim-icin/gelistirici-merkezi#callbackurl-hash-dogrulama-mekanizmasi)
* paymentInquiry($options) // [Ödeme Sorgulama](https://tosla.com/isim-icin/gelistirici-merkezi#odeme-sorgulama)
* history($options) // [Ödeme Sorgulama (Tarih Aralığı Parametreli)](https://tosla.com/isim-icin/gelistirici-merkezi#islem-listeleme)
* void($options) // [Gün Sonu Öncesi İşlem İptali](https://tosla.com/isim-icin/gelistirici-merkezi#iptal)
* refund($options) // [İade](https://tosla.com/isim-icin/gelistirici-merkezi#iade)

## Treeware

This package is [Treeware](https://treeware.earth). If you use it in production, then we ask that you [**buy the world a tree**](https://plant.treeware.earth/tcgunel/omnipay-tosla) to thank us for our work. By contributing to the Treeware forest you’ll be creating employment for local families and restoring wildlife habitats.
