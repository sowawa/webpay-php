# webpay-php [![Build Status](https://travis-ci.org/webpay/webpay-php.png)](https://travis-ci.org/webpay/webpay-php)

WebPay PHP bindings  https://webpay.jp

## Usage

```php
require "vendor/autoload.php";
use WebPay\WebPay;

$webpay = new WebPay('test_secret_YOUR_TEST_API_KEY');
$webpay->charges->create(array(
   "amount"=>400,
   "currency"=>"jpy",
   "card"=>
    array("number"=>"4242-4242-4242-4242",
     "exp_month"=>11,
     "exp_year"=>2014,
     "cvc"=>123,
     "name"=>"KEI KUBO"),
   "description"=>"アイテムの購入"
));
```

See [WebPay PHP API Document](https://webpay.jp/docs/api/php) for more details.

## Contributing

1. Fork it
2. Create your feature branch (`git checkout -b my-new-feature`)
3. Commit your changes (`git commit -am 'Add some feature'`)
4. Push to the branch (`git push origin my-new-feature`)
5. Create new Pull Request
