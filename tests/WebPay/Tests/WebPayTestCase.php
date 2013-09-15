<?php

namespace WebPay\Tests;

use WebPay\WebPay;

class WebPayTestCase extends \Guzzle\Tests\GuzzleTestCase
{
    protected $webpay;

    public function setup()
    {
        $this->webpay = new WebPay('test_key', 'http://api.example.com');
    }

    protected function mock($file)
    {
        $plugin = new \Guzzle\Plugin\Mock\MockPlugin();
        $plugin->addResponse(__DIR__ . '/../../mock/' . $file . '.txt');
        $this->webpay->addSubscriber($plugin);
    }
}
