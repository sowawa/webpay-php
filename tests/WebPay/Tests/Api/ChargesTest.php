<?php

namespace WebPay\Tests\Api;


class ChargesTest extends \WebPay\Tests\WebPayTestCase
{
    public function testCreate()
    {
        $this->mock('charges/create');

        $charge = $this->webpay->charges->create(array(
            'amount' => 1000,
            'currency' => "jpy",
            'card' => array(
                'number' => "4242-4242-4242-4242",
                'exp_month' => 12,
                'exp_year' => 2015,
                'cvc' => 123,
                'name' => "YUUKO SHIONJI",
            ),
            'description' => "Test Charge from Java",
        ));

        $this->assertEquals($charge->id, 'ch_2SS17Oh1r8d2djE');
        $this->assertEquals($charge->description, 'Test Charge from Java');
        $this->assertEquals($charge->card->name, 'YUUKO SHIONJI');
    }
}
