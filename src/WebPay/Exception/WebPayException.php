<?php

namespace WebPay\Exception;

class WebPayException extends \Exception {

    /** @var integer */
    private $status;

    /** @var array */
    private $errorInfo;

    /**
     * @param string $message
     * @param integer $status
     * @param array $errorInfo
     */
    public function __construct($message, $status = null, $errorInfo = null)
    {
        parent::__construct($message);
        $this->status = $status;
        $this->errorInfo = $errorInfo;
    }
}