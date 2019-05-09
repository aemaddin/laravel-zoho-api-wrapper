<?php

namespace Aemaddin\Zoho;

use Aemaddin\Zoho\Client\ZohoClient;
use Aemaddin\Zoho\Client\ZohoRequest;
use Aemaddin\Zoho\Client\ZohoResponse;
use GuzzleHttp\Exception\GuzzleException;

/**
 * @property ZohoRequest request
 * @property ZohoClient client
 */
class ZohoApi
{
    /**
     * @var null
     */
    private $client = null;
    /**
     * @var string
     */
    private $api_url = 'https://www.zohoapis.com/crm/v2/';
    /**
     * @var null
     */
    private $response = null;
    /**
     * @var null
     */
    private $request = null;

    /**
     * @var null
     */
    private $authentication = null;

    /**
     * ZohoApi constructor.
     * @param null $config_id
     * @param null $scope
     * @throws GuzzleException
     */
    public function __construct($config_id = null, $scope = null)
    {
        $this->setClient();
        $this->authentication = new Authentication($config_id, $scope);
    }

    /**
     * @param ZohoClient|null $client
     * @return ZohoApi
     */
    public function setClient(ZohoClient $client = null): ZohoApi
    {
        $this->client = $client ?? new ZohoClient();
        return $this;
    }

    /**
     * @return null
     */
    public function getClient()
    {
        return $this->client;
    }

    /**
     * @param ZohoRequest $request
     * @return $this
     */
    public function setRequest(ZohoRequest $request)
    {
        $this->request = $request;
        return $this;
    }

    /**
     * @return ZohoRequest
     */
    public function getRequest(): ZohoRequest
    {
        return $this->request;
    }

    /**
     * @param Authentication $auth
     * @return $this
     */
    public function setAuth(Authentication $auth)
    {
        $this->authentication = $auth;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getAccessToken()
    {
        return $this->authentication->getAccessToken();
    }

    /**
     * @return string
     */
    public function getUrl(): string
    {
        return $this->api_url . $this->request->getURI();
    }

    /**
     * @return mixed
     */
    public function getRequestVerb()
    {
        return $this->request->getHttpVerb();
    }

    /**
     * @return mixed
     */
    public function getJson()
    {
        return $this->request->getDataJson();
    }

    /**
     * @return mixed
     */
    public function getAction()
    {
        return $this->request->getAction();
    }

    /**
     * @param ZohoRequest $request
     * @return ZohoResponse
     * @throws Exception\ZohoException
     * @throws GuzzleException
     */
    public function get(ZohoRequest $request): ZohoResponse
    {
        $this->setRequest($request);
        $access_token = $this->getAccessToken();
        $url = $this->getUrl();
        $http_verb = $this->getRequestVerb();
        $json_data = $this->getJson();
        $action = $this->getAction();

        $this->response = $this->client->execute($action, $http_verb, $url, $access_token, $json_data);
        return $this->response;
    }

}