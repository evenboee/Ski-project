<?php

/**
 * Class APIException an exception class thrown whenever the request could not be successfully handled by the API.
 * https://git.gvk.idi.ntnu.no/runehj/sample-rest-api-project/-/blob/master/controller/APIException.php
 * @author Rune Hjelsvold
 */
class APIException extends Exception
{
    /**
     * @var string $instance the URI of the instance finding that the request could not be successfully handled.
     */
    protected $instance;

    /**
     * @var int the detailed error code - as specified in the RESTConstant class.
     */
    protected $detailCode;

    public function __construct($code, $instance, $message = "", $detailCode = -1, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
        $this->instance = $instance;
        $this->detailCode = $detailCode;
    }

    public function getInstance() {
        return $this->instance;
    }

    public function getDetailCode()
    {
        return $this->detailCode;
    }
}
