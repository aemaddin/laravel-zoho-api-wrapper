<?php

namespace Aemaddin\Zoho\Abstracts;

use Aemaddin\Zoho\Client\ZohoRequest;
use Aemaddin\Zoho\Client\ZohoResponse;
use Aemaddin\Zoho\Exception\RequestException;
use Aemaddin\Zoho\Exception\ZohoException;
use Aemaddin\Zoho\ZohoApi;
use GuzzleHttp\Exception\GuzzleException;

abstract class RestApi
{
    protected $zoho_api = null;

    /**
     * RestApi constructor.
     * @param $config_id
     * @throws GuzzleException
     */
    public function __construct($config_id)
    {
        $this->setZohoApi(new ZohoApi($config_id));
    }

    /**
     * @param ZohoApi $zoho_api
     *
     * @return RestApi
     */
    public function setZohoApi(ZohoApi $zoho_api): RestApi
    {
        $this->zoho_api = $zoho_api;
        return $this;
    }

    /**
     * @return ZohoApi
     */
    public function getZohoApi(): ZohoApi
    {
        return $this->zoho_api;
    }

    /**
     * @param string $action = 'search'
     *
     * @param string $module = 'lead'
     *
     * @param array $param = ['email' => 'test@gmail.com']
     *
     * @return ZohoRequest object
     * @throws RequestException
     */
    public function createRequest($action, $module, array $param): ZohoRequest
    {
        return new ZohoRequest($action, $module, $param);
    }

    /**
     * @param ZohoRequest $request
     *
     * @return ZohoResponse response
     * @throws GuzzleException
     * @throws ZohoException
     */
    public function makeRequest(ZohoRequest $request): ZohoResponse
    {
        return $this->getZohoApi()->get($request);
    }

}
