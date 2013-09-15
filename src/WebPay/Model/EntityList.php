<?php

namespace WebPay\Model;

class EntityList extends AbstractModel {

    public function __construct($data)
    {
        if (array_key_exists('data', $data) && !empty($data['data'])) {
            switch ($data['data'][0]['object']) {
            case 'charge':
                $constructor = function(array $item) { return new Charge($item); };
                break;
            case 'customer':
                $constructor = function(array $item) { return new Customer($item); };
                break;
            case 'event':
                $constructor = function(array $item) { return new Event($item); };
                break;
            default:
                // TODO: use APIConnectionError;
            }
            $data['data'] = array_map($constructor, $data['data']);
        }
        parent::__construct($data);
    }
}
