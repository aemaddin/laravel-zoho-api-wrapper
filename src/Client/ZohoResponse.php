<?php

namespace Aemaddin\Zoho\Client;

use Aemaddin\Zoho\Exception\ResponseException;
use GuzzleHttp\Psr7\Response;
use Illuminate\Support\Str;

class ZohoResponse
{
    /**
     * @var mixed
     */
    protected $response = null;

    /**
     * @var mixed
     */
    protected $results = null;

    /**
     * @var null
     */
    protected $status = null;

    /**
     * @var string
     */
    protected $error_message = null;

    /**
     * @var array
     */
    protected $array_response = null;

    /**
     * @var integer
     */
    protected $http_status_code = null;


    /**sss
     * ZohoResponse constructor.
     *
     * @param Response $response
     * @param $action
     */
    public function __construct(Response $response, $action)
    {
        $this->response = $response;
        $this->checkHttpStatusCode();
        $this->parseResponse($action);
    }

    /**
     * @param Response $response
     *
     * @return ZohoResponse
     */
    public function setResponse(Response $response): ZohoResponse
    {
        $this->response = $response;
        return $this;
    }


    /**
     * @param $action
     * @return ZohoResponse
     *
     */
    protected function parseResponse($action)
    {

        $json_response = $this->response->getBody()->getContents();
        $invoke_function = Str::camel(str_replace(' ', '', $action));
        return $invoke_function($json_response);

    }

    /**
     * @param $json_response
     * @return $this
     */
    private function setSuccessResponse($json_response)
    {
        $this->setResults($json_response);
        $this->toArray($json_response);
        $this->setStatus('success');
        return $this;
    }

    /**
     * @param $json_response
     * @throws ResponseException
     */
    private function yieldException($json_response)
    {
        if ($this->http_status_code == 500) {
            $this->internalServerException($json_response);
        }

        $error_response = json_decode($json_response);
        $this->setStatus('error');
        throw new ResponseException($error_response->message, $this->http_status_code, json_encode($error_response));
    }

    /**
     * @param $json_response
     * @throws ResponseException
     */
    private function internalServerException($json_response)
    {
        $error_response = json_decode($json_response);
        $this->setStatus('error');
        throw new ResponseException($error_response->message, $this->http_status_code, json_encode($error_response));
    }

    /**
     * @throws ResponseException
     */
    private function noContentException()
    {
        $this->setStatus("error");
        $error_response = [
            'code' => 'NO_CONTENT',
            'details' => [],
            'message' => 'There is no content available for the request.',
            'status' => 'success',
        ];
        throw new ResponseException("There is no content available for the request.", $this->http_status_code, json_encode($error_response));
    }

    /**
     * @param $json_response
     * @throws ResponseException
     */
    private function insertException($json_response)
    {
        $error_response = json_decode($json_response);
        $error_response = collect($error_response->data);
        $error_response = $error_response->first();
        $this->setStatus('error');
        throw new ResponseException($error_response->message, $this->http_status_code, json_encode($error_response));
    }

    /**
     * @param $json_response
     * @throws ResponseException
     */
    private function updateException($json_response)
    {
        $error_response = json_decode($json_response);
        $error_response = collect($error_response->data);
        $error_response = $error_response->first();
        $this->setStatus('error');
        throw new ResponseException("Failed to update one/more records.", $this->http_status_code, json_encode($error_response));
    }

    /**
     * @param $json_response
     * @throws ResponseException
     */
    private function deleteException($json_response)
    {
        $error_response = json_decode($json_response);
        $error_response = collect($error_response->data);
        $error_response = $error_response->first();
        $this->setStatus('error');
        throw new ResponseException($error_response->message, $this->http_status_code, json_encode($error_response));
    }

    /**
     * Parse response
     * @param $json_response
     * @return ZohoResponse
     */
    private function refreshToken($json_response)
    {
        return $this->setSuccessResponse($json_response);
    }

    /**
     * @param $json_response
     * @return ZohoResponse
     * @throws ResponseException
     */
    private function get($json_response)
    {
        return $this->recordResponse($json_response);
    }

    /**
     * @param $json_response
     * @return ZohoResponse
     * @throws ResponseException
     */
    private function post($json_response)
    {
        return $this->recordResponse($json_response);
    }

    /**
     * @param $json_response
     * @return ZohoResponse
     * @throws ResponseException
     */
    private function recordResponse($json_response)
    {
        if ($this->http_status_code == 200) {
            return $this->setSuccessResponse($json_response);
        }
        $this->yieldException($json_response);
    }

    /**
     * @param $json_response
     * @return ZohoResponse
     * @throws ResponseException
     */
    private function search($json_response)
    {
        if ($this->http_status_code == 204) {
            $this->noContentException();
        }
        return $this->recordResponse($json_response);
    }

    /**
     * @param $json_response
     * @return ZohoResponse
     * @throws ResponseException
     */
    private function recordList($json_response)
    {
        return $this->search($json_response);
    }

    /**
     * @param $json_response
     * @return ZohoResponse
     * @throws ResponseException
     */
    private function specificRecord($json_response)
    {
        return $this->search($json_response);
    }

    /**
     * @param $json_response
     * @return ZohoResponse
     * @throws ResponseException
     */
    private function insert($json_response)
    {
        if ($this->http_status_code == 201) {
            return $this->setSuccessResponse($json_response);
        }

        if ($this->http_status_code == 400) {
            $this->yieldException($json_response);
        }

        if ($this->http_status_code == 403) {
            $this->yieldException($json_response);
        }

        $this->insertException($json_response);
    }

    /**
     * @param $json_response
     * @return ZohoResponse
     * @throws ResponseException
     */
    private function update($json_response)
    {
        if ($this->http_status_code == 200) {
            return $this->setSuccessResponse($json_response);
        }

        if ($this->http_status_code == 202) {
            $this->updateException($json_response);
        }

        $this->yieldException($json_response);

    }

    /**
     * @param $json_response
     * @return ZohoResponse
     * @throws ResponseException
     */
    private function bulkUpdate($json_response)
    {
        return $this->update($json_response);
    }

    /**
     * @param $json_response
     * @return ZohoResponse
     * @throws ResponseException
     */
    private function upsert($json_response)
    {
        return $this->update($json_response);
    }

    /**
     * @param $json_response
     * @return ZohoResponse
     * @throws ResponseException
     */
    private function delete($json_response)
    {
        if ($this->http_status_code == 200) {
            return $this->setSuccessResponse($json_response);
        }

        if ($this->http_status_code == 400) {
            $this->yieldException($json_response);
        }

        $this->deleteException($json_response);
    }

    /**
     * @param $json_response
     * @return ZohoResponse
     * @throws ResponseException
     */
    private function bulkDelete($json_response)
    {
        return $this->delete($json_response);
    }

    //Process Meta Data Response

    /**
     * @param $json_response
     * @return ZohoResponse
     * @throws ResponseException
     */
    private function metaResponse($json_response)
    {
        if ($this->http_status_code == 200) {
            return $this->setSuccessResponse($json_response);
        }
        $this->yieldException($json_response);
    }

    /**
     * @param $json_response
     * @return ZohoResponse
     * @throws ResponseException
     */
    private function allModules($json_response)
    {
        return $this->metaResponse($json_response);
    }

    /**
     * @param $json_response
     * @return ZohoResponse
     * @throws ResponseException
     */
    private function moduleMeta($json_response)
    {
        return $this->metaResponse($json_response);
    }

    /**
     * @param $json_response
     * @return ZohoResponse
     * @throws ResponseException
     */
    private function fieldMeta($json_response)
    {
        return $this->metaResponse($json_response);
    }

    /**
     * @param $json_response
     * @return ZohoResponse
     * @throws ResponseException
     */
    private function layoutMeta($json_response)
    {
        return $this->metaResponse($json_response);
    }

    /**
     * @param $json_response
     * @return ZohoResponse
     * @throws ResponseException
     */
    private function layoutMetaById($json_response)
    {
        if ($this->http_status_code == 204) {
            $this->noContentException();
        }
        return $this->metaResponse($json_response);
    }

    //Process Notes api

    /**
     * @param $json_response
     * @return ZohoResponse
     * @throws ResponseException
     */
    private function noteResponse($json_response)
    {
        if ($this->http_status_code == 200) {
            return $this->setSuccessResponse($json_response);
        }
        $this->yieldException($json_response);
    }

    /**
     * @param $json_response
     * @throws ResponseException
     */
    private function deleteNoteException($json_response)
    {
        $error_response = json_decode($json_response);
        $error_response = collect($error_response->data);
        $error_response = $error_response->first();
        $this->setStatus('error');
        throw new ResponseException($error_response->message, $this->http_status_code, json_encode($error_response));
    }

    /**
     * @param $json_response
     * @return ZohoResponse|void
     * @throws ResponseException
     */
    private function notesData($json_response)
    {
        if ($this->http_status_code == 204) {
            $this->noContentException();
        }
        return $this->noteResponse($json_response);
    }

    /**
     * @param $json_response
     * @return ZohoResponse
     * @throws ResponseException
     */
    private function getSpecificNotes($json_response)
    {
        if ($this->http_status_code == 204) {
            $this->noContentException();
        }
        return $this->noteResponse($json_response);
    }

    /**
     * @param $json_response
     * @return ZohoResponse
     * @throws ResponseException
     */
    private function createNotes($json_response)
    {

        if ($this->http_status_code == 201) {
            return $this->setSuccessResponse($json_response);
        }

        if ($this->http_status_code == 400) {
            $this->yieldException($json_response);
        }

        if ($this->http_status_code == 403) {
            $this->yieldException($json_response);
        }

        $this->insertException($json_response);
    }

    /**
     * @param $json_response
     * @return ZohoResponse
     * @throws ResponseException
     */
    private function createSpecificNote($json_response)
    {
        return $this->createNotes($json_response);
    }

    /**
     * @param $json_response
     * @throws ResponseException
     */
    private function updateNote($json_response)
    {

        if ($this->http_status_code == 202) {
            $this->updateException($json_response);
        }

        $this->noteResponse($json_response);
    }

    /**
     * @param $json_response
     * @return ZohoResponse
     * @throws ResponseException
     */
    private function deleteSpecificNote($json_response)
    {
        if ($this->http_status_code == 200) {
            $delete_response = json_decode($json_response);
            $delete_response = collect($delete_response->data);
            $delete_response = $delete_response->first();
            if ($delete_response->status == 'error') {
                $this->http_status_code = 400;
                $this->deleteNoteException($json_response);
            }
            return $this->noteResponse($json_response);
        }

        $this->yieldException($json_response);
    }


    /**
     * Check HTTP status code (silent/No exceptions!)
     * @return int
     */
    protected function checkHttpStatusCode(): int
    {
        $this->http_status_code = $this->response->getStatusCode();
        return $this->http_status_code;
    }

    /**
     * @param string $json_response
     *
     * @return array
     */
    public function toArray(string $json_response): array
    {
        $this->array_response = json_decode($json_response, true);
        return $this->array_response;
    }

    /**
     * @return array|mixed
     */
    public function getResults()
    {
        return $this->results;
    }

    /**
     * @param string $results
     *
     * @return $this
     */
    public function setResults($results)
    {
        $this->results = json_decode($results);
        return $this;
    }

    /**
     * @return string
     */
    public function getStatus(): string
    {
        return $this->status;
    }

    /**
     * @param string $status
     *
     * @return ZohoResponse
     */
    public function setStatus(string $status)
    {
        $this->status = $status;
        return $this;
    }

    /**
     * @return array
     */
    public function getArrayResponse(): array
    {
        return $this->array_response;
    }

    /**
     * @param array $array_response
     *
     * @return ZohoResponse
     */
    public function setArrayResponse(array $array_response)
    {
        $this->array_response = $array_response;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getErrorMessage()
    {
        return $this->error_message;
    }

    /**
     * @param $error_message
     *
     * @return ZohoResponse
     */
    public function setErrorMessage($error_message)
    {
        $this->error_message = $error_message;
        return $this;
    }

    /**
     * @return int
     */
    public function getHttpStatusCode(): int
    {
        return intval($this->http_status_code);
    }


}