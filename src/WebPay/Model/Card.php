<?php

namespace WebPay\Model;

class Card {

    /** @var array */
    private $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function __get($key)
    {
        if (array_key_exists($key, $this->data)) {
            return $this->data[$key];
        } else {
            throw new \Exception('Undefined field ' . $key);
        }
    }

    public function __set($key, $value)
    {
        throw new \Exception($key . ' is not able to override');
    }
}