<?php

namespace WebPay\Model;

abstract class AbstractModel {

    /** @var array */
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function __get($key)
    {
        if (array_key_exists($key, $this->data)) {
            return $this->data[$key];
        }
        $underscore = $this->decamelize($key);
        if (array_key_exists($underscore, $this->data)) {
            return $this->data[$underscore];
        }
        throw new \Exception('Undefined field ' . $key);

    }

    public function __set($key, $value)
    {
        throw new \Exception($key . ' is not able to override');
    }

    private function decamelize($str) {
        $proc = function ($r1) {
            return '_'.strtolower($r1[0]);
        };
        return preg_replace_callback('/([A-Z])/', $proc ,$str);
    }
}
