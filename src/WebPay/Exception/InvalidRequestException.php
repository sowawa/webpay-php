<?php

namespace WebPay\Exception;

class InvalidRequestException extends WebPayException {

    /** @var string */
    private $type;

    /** @var string */
    private $param;

    /**
     * Create a fake InvalidRequestException to indicate the given id is invalid
     *
     * @return InvalidRequestException
     */
    static public function emptyIdException()
    {
        return new self(null, array(
            'message' => 'id must not be empty',
            'type' => 'invalid_request_error',
            'param' => 'id'
        ));
    }

    /**
     * @param integer $status
     * @param array $errorInfo
     */
    public function __construct($status, $errorInfo)
    {
        parent::__construct($errorInfo['message'], $status, $errorInfo);
        $this->type = $errorInfo['type'];
        $this->param = $errorInfo['param'];
    }
}