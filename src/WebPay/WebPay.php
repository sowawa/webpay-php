<?php

namespace WebPay;

use Guzzle\Common\Event;
use Guzzle\Service\Client;
use Guzzle\Service\Description\ServiceDescription;

use WebPay\Api\Charges;
use WebPay\Api\Customers;
use WebPay\Api\Events;
use WebPay\Api\Tokens;
use WebPay\Api\Account;

use WebPay\Exception\WebPayException;
use WebPay\Exception\APIConnectionException;

class WebPay
{
    /** @var Client */
    private $client;

    /** @var Charges */
    private $charges;

    /** @var Customers */
    private $customers;

    /** @var Events */
    private $events;

    /** @var Tokens */
    private $tokens;

    /** @var Account */
    private $account;

    /**
     * @param string $apiKey  Your secret API key
     * @param string $apiBase Default is https://api.webpay.jp
     */
    public function __construct($apiKey, $apiBase = null)
    {
        $description = ServiceDescription::factory(__DIR__ . '/Resources/service_descriptions/webpay_v1.json');
        $this->client = new Client();
        $this->client->setDescription($description);
        if(!is_null($apiBase)) {
            $this->client->setBaseUrl($apiBase);
        }
        $this->client->setDefaultOption('auth', array($apiKey, '', 'Basic'));
        $this->client->getEventDispatcher()->addListener('request.error', array($this, 'onRequestError'));
        $this->client->getEventDispatcher()->addListener('request.exception', array($this, 'onRequestException'));

        $this->charges = new Charges($this);
        $this->customers = new Customers($this);
        $this->events = new Events($this);
        $this->tokens = new Tokens($this);
        $this->account = new Account($this);
    }

    public function __get($key)
    {
        $accessors = array('charges', 'customers', 'events', 'tokens', 'account');
        if (in_array($key, $accessors) && property_exists($this, $key)) {
            return $this->{$key};
        } else {
            throw new \Exception('Unknown accessor ' . $key);
        }
    }

    public function __set($key, $value)
    {
        throw new \Exception($key . ' is not able to override');
    }

    /**
     * Dispatch API request
     *
     * @param string $command A command registered to the service description
     * @param array  $params  Request parameters
     *
     * @return mixed Response object
     */
    public function request($command, array $params)
    {
        $command = $this->client->getCommand($command, $params);
        try {
            return $command->execute();
        } catch (\Guzzle\Common\Exception\RuntimeException $e) {
            $message = 'Guzzle throws exception: ' . $e->getMessage();
            throw new APIConnectionException($message, null, null, $e);
        }
    }

    /**
     * Add a guzzle plugin to the client.
     * This is mainly for testing, but also useful for logging, validation, etc.
     *
     * @param mixed $plugin A guzzle plugin
     */
    public function addSubscriber($plugin)
    {
        $this->client->addSubscriber($plugin);
    }

    /**
     * @param  Event           $event
     * @throws WebPayException
     */
    public function onRequestError(Event $event)
    {
        throw WebPayException::exceptionFromResponse($event['response']);
    }

    /**
     * @param  Event                  $event
     * @throws APIConnectionException
     */
    public function onRequestException(Event $event)
    {
        $cause = $event['exception'];
        $message = 'HTTP connection throws exception: ' . (isset($cause) ? $cause->getMessage() : '(exception is not available)');

       if (isset($event['response'])) {
            $response = $event['response'];
            $status = $response->getStatusCode();
            $data = $response->json();
            $error = isset($data['error']) ? $data['error'] : null;

            throw new APIConnectionException($message, $status, $error, $cause);
        } else {
            throw new APIConnectionException($message, null, null, $cause);
        }
    }
}
