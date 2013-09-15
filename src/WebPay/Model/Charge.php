<?php

namespace WebPay\Model;

class Charge extends AbstractModel {

    public function __construct($data)
    {
        if ($data['card']) {
            $data['card'] = new Card($data['card']);
        }
        parent::__construct($data);
    }
}
