<?php

namespace Aemaddin\Zoho\Exception;

use Exception;
use Psy\Util\Json;

class ZohoException extends Exception
{
    protected $message = null;
    protected $exception_json = null;

    /**
     * ZohoException constructor.
     * @param $message
     * @param null $exception_json
     */
    public function __construct($message, $exception_json = null)
    {
        $this->message = $message;
        $this->exception_json = $exception_json;
        parent::__construct($message);
    }

    /**
     * @return ZohoException
     */
    public function getResponse()
    {
        if ($this->exception_json !== null) {
            $this->exception_json = json_decode($this->exception_json, true);
            return $this;
        }
        return $this;
    }

    /**
     * @return int
     */
    public function getHttpStatusCode(): int
    {
        return intval($this->http_status_code);
    }

    /**
     * @return JSON
     */
    public function getExceptionJson()
    {
        return $this->exception_json;
    }

    public function hasResponse()
    {
        return $this->exception_json !== null;
    }
}