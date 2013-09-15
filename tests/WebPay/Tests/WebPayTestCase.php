<?php

namespace WebPay\Tests;

use WebPay\WebPay;

class WebPayTestCase extends \Guzzle\Tests\GuzzleTestCase
{
    protected $webpay;
    protected $plugins;

    public function setup()
    {
        $this->webpay = new WebPay('test_key', 'http://api.example.com');
        $this->plugins = array();
    }

    protected function mock($file)
    {
        $plugin = new \Guzzle\Plugin\Mock\MockPlugin();
        $plugin->addResponse(__DIR__ . '/../../mock/' . $file . '.txt');
        $this->webpay->addSubscriber($plugin);
        array_push($this->plugins, $plugin);
    }

    protected function assertRequest($path, $params)
    {
        $requests = $this->plugins[0]->getReceivedRequests();
        $request = $requests[0];
        $this->assertEquals($request->getHost(), 'api.example.com');
        $this->assertEquals($request->getPath(), '/v1' . $path);
        $this->assertEquals($request->getUsername(), 'test_key');
        if ($params != null && is_array($params)) {
            $this->assertEquals($request->getPostFields()->toArray(), $params);
        }
    }
}
