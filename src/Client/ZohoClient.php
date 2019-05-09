<?php

namespace Aemaddin\Zoho\Client;

use Aemaddin\Zoho\Exception\ZohoException;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\RequestException as GuzzleRequestException;

class ZohoClient
{
    private $client;

    public function __construct()
    {
        $this->client = new Client();
    }

    /**
     * @param $client
     * @return mixed
     */
    public function setClient($client)
    {
        $this->client = $client;

        return $this->client;
    }

    /**
     * @param $action
     * @param $http_verb
     * @param $url
     * @param $access_token
     * @param array|null $data
     * @return ZohoResponse
     * @throws ZohoException
     * @throws GuzzleException
     */
    public function execute($action, $http_verb, $url, $access_token, array $data = null)
    {

        if ($data !== null) {
            $data_header['json'] = $data;
        }

        $data_header['headers'] = [
            'Accept' => 'application/json',
            'Content-Length' => '0',
            'Authorization' => 'Zoho-oauthtoken ' . $access_token
        ];

        try {
            $res = $this->client->request($http_verb, $url, $data_header);
        } catch (GuzzleRequestException $e) {
            if ($e->hasResponse()) {
                $res = $e->getResponse();
            } else {
                throw new ZohoException($e->getMessage());
            }
        }

        return new ZohoResponse($res, $action);
    }

    /**
     * @param string $url
     * @return ZohoResponse
     * @throws GuzzleException
     * @throws ZohoException
     */
    public function get(string $url)
    {
        $action = 'get';
        try {
            $res = $this->client->request('GET', $url);
        } catch (GuzzleRequestException $e) {
            if ($e->hasResponse()) {
                $res = $e->getResponse();
            } else {
                throw new ZohoException($e->getMessage());
            }
        }

        return new ZohoResponse($res, $action);
    }

    /**
     * @param $url
     * @param string $action
     * @return ZohoResponse
     * @throws GuzzleException
     * @throws ZohoException
     */
    public function post($url, $action = 'post')
    {
        try {
            $res = $this->client->request('POST', $url);
        } catch (GuzzleRequestException $e) {
            if ($e->hasResponse()) {
                $res = $e->getResponse();
            } else {
                throw new ZohoException($e->getMessage());
            }
        }

        return new ZohoResponse($res, $action);
    }

}