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

    /** @var array */
    protected $accessors;

    /**
     * @param string $apiKey  Your secret API key
     * @param string $apiBase Default is https://api.webpay.jp
     */
    public function __construct($apiKey, $apiBase = null)
    {
        $description = ServiceDescription::factory(__DIR__ . '/Resources/service_descriptions/webpay_v1.json');
        $this->client = new Client();
        $this->client->setDescription($description);
        $this->client->setBaseUrl($apiBase);
        $this->client->setDefaultOption('auth', array($apiKey, '', 'Basic'));
        $this->client->getEventDispatcher()->addListener('request.error', array($this, 'onRequestError'));
        $this->client->getEventDispatcher()->addListener('request.exception', array($this, 'onRequestException'));

        $this->registerAccessor('charges', new Charges());
        $this->registerAccessor('customers', new Customers());
        $this->registerAccessor('events', new Events());
        $this->registerAccessor('tokens', new Tokens());
        $this->registerAccessor('account', new Account());
    }

    public function registerAccessor($name, Api\AccessorInterface $accessor)
    {
        $accessor->setClient($this);
        $this->accessors[$name] = $accessor;
    }

    public function __get($key)
    {
        $accessor_keys = array('charges', 'customers', 'events', 'tokens', 'account');
        if (in_array($key, $accessor_keys)) {
            return $this->accessors[$key];
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
