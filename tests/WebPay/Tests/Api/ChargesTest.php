<?php

namespace WebPay\Tests\Api;


class ChargesTest extends \WebPay\Tests\WebPayTestCase
{
    public function testCreate()
    {
        $this->mock('charges/create');

        $params = array(
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
        );
        $charge = $this->webpay->charges->create($params);

        $this->assertEquals('ch_2SS17Oh1r8d2djE', $charge->id);
        $this->assertEquals('Test Charge from Java', $charge->description);
        $this->assertEquals('YUUKO SHIONJI', $charge->card->name);

        $this->assertRequest('/charges', $params);
    }

    public function testRetrieve()
    {
        $this->mock('charges/retrieve');
        $id = 'ch_bWp5EG9smcCYeEx';
        $charge = $this->webpay->charges->retrieve($id);

        $this->assertEquals($id, $charge->id);

        $this->assertRequest('/charges/'.$id, null);
    }
}
