<?php

namespace WebPay\Tests;

class WebPayTest extends \WebPay\Tests\WebPayTestCase
{
    /**
     * @expectedException \WebPay\Exception\APIException
     */
    public function testRequestRaisesAPIException()
    {
        $this->mock('errors/unknown_api_error');
        try {
            $this->webpay->request('account.retrieve', array());
        } catch (\WebPay\Exception\APIException $e) {
            $this->assertEquals('Unknown error occurred', $e->getMessage());
            $this->assertEquals('api_error', $e->getType());
            $this->assertEquals(500, $e->getStatus());
            throw $e;
        }
    }

    /**
     * @expectedException \WebPay\Exception\InvalidRequestException
     */
    public function testRequestRaisesInvalidRequest()
    {
        $this->mock('errors/bad_request');
        try {
            $this->webpay->request('account.retrieve', array());
        } catch (\WebPay\Exception\InvalidRequestException $e) {
            $this->assertEquals('Missing required param: currency', $e->getMessage());
            $this->assertEquals('invalid_request_error', $e->getType());
            $this->assertEquals('currency', $e->getParam());
            $this->assertEquals(400, $e->getStatus());
            throw $e;
        }
    }

    /**
     * @expectedException \WebPay\Exception\InvalidRequestException
     */
    public function testRequestRaisesNotFound()
    {
        $this->mock('errors/not_found');
        try {
            $this->webpay->request('account.retrieve', array());
        } catch (\WebPay\Exception\InvalidRequestException $e) {
            $this->assertEquals('No such charge: foo', $e->getMessage());
            $this->assertEquals('invalid_request_error', $e->getType());
            $this->assertEquals('id', $e->getParam());
            $this->assertEquals(404, $e->getStatus());
            throw $e;
        }
    }

    /**
     * @expectedException \WebPay\Exception\AuthenticationException
     */
    public function testRequestRaisesUnauthorized()
    {
        $this->mock('errors/unauthorized');
        try {
            $this->webpay->request('account.retrieve', array());
        } catch (\WebPay\Exception\AuthenticationException $e) {
            $this->assertEquals('Invalid API key provided. Check your API key is correct.', $e->getMessage());
            $this->assertEquals(401, $e->getStatus());
            throw $e;
        }
    }

    /**
     * @expectedException \WebPay\Exception\CardException
     */
    public function testRequestRaisesCardError()
    {
        $this->mock('errors/card_error');
        try {
            $this->webpay->request('account.retrieve', array());
        } catch (\WebPay\Exception\CardException $e) {
            $this->assertEquals('Your card number is incorrect', $e->getMessage());
            $this->assertEquals('card_error', $e->getType());
            $this->assertEquals('incorrect_number', $e->getCardErrorCode());
            $this->assertEquals('number', $e->getParam());
            $this->assertEquals(402, $e->getStatus());
            throw $e;
        }
    }

    /**
     * @expectedException \WebPay\Exception\APIConnectionException
     */
    public function testServerNotFound()
    {
        try {
            $webpay = new \WebPay\WebPay('', 'http://127.0.0.1:123');
            $webpay->request('account.retrieve', array());
        } catch (\WebPay\Exception\APIConnectionException $e) {
            $this->assertEquals("HTTP connection throws exception: [curl] 7: couldn't connect to host [url] http://127.0.0.1:123/v1/account", $e->getMessage());
            $this->assertNull($e->getStatus());
            $this->assertNull($e->getErrorInfo());
            throw $e;
        }
    }

    /**
     * @expectedException \WebPay\Exception\APIConnectionException
     */
    public function testResponseJsonIsBroken()
    {
        $this->mock('errors/broken_json');
        try {
            $this->webpay->request('account.retrieve', array());
        } catch (\WebPay\Exception\APIConnectionException $e) {
            $this->assertEquals('Guzzle throws exception: Unable to parse response body into JSON: 4', $e->getMessage());
            $this->assertNull($e->getStatus());
            $this->assertNull($e->getErrorInfo());
            throw $e;
        }
    }

    public function testDefaultBaseUrl()
    {
        $webpay = new \WebPay\WebPay('');
        $prop = new \ReflectionProperty('\WebPay\WebPay', 'client');
        $prop->setAccessible(true);
        $this->assertEquals('https://api.webpay.jp', $prop->getValue($webpay)->getBaseUrl());
    }
}
