<?php
namespace Aemaddin\Zoho\Api;

use Aemaddin\Zoho\Abstracts\RestApi;
use Aemaddin\Zoho\Client\ZohoResponse;

class RecordApi extends RestApi
{
    public function __construct($config_id=null)
    {
        parent::__construct($config_id);
    }

    /**
     * @param $module = 'Leads'
     *
     * @return ZohoResponse
     */
    public function listOfRecords($module)
    {
        $request = $this->createRequest('list_of_record', $module, []);
        return $this->makeRequest($request);
    }

    /**
     * @param string $module = 'Leads'
     *
     * @param string $record_id = '1108640000047708962'
     * @return ZohoResponse
     */
    public function recordById($module, $record_id)
    {
        $request = $this->createRequest('specific_record', $module, [$record_id]);
        return $this->makeRequest($request);
    }

    /**
     * @param string $module = 'Lead'
     *
     * @param array $param = ['email' => 'test@gmail.com']
     *
     * @return ZohoResponse
     */
    public function search($module, array $param)
    {
        $request = $this->createRequest('search', $module, $param);
        return $this->makeRequest($request);
    }

    /**
     * @param $module
     * @param array $param
     * @return ZohoResponse
     */
    public function insert($module, array $param)
    {
        $request = $this->createRequest('insert', $module, $param);
        return $this->makeRequest($request);
    }

    /**
     * @param $module
     * @param array $param
     * @return ZohoResponse
     */
    public function bulkUpdate($module, array $param)
    {
        $request = $this->createRequest('b-update', $module, $param);
        return $this->makeRequest($request);
    }

    /**
     * @param $module
     * @param $record_id
     * @param array $param
     * @return ZohoResponse
     */
    public function update($module, $record_id, array $param)
    {
        $param['record_id'] = $record_id;
        $request = $this->createRequest('update', $module, $param);
        return $this->makeRequest($request);
    }

    /**
     * @param $module
     * @param array $param
     * @return ZohoResponse
     */
    public function upsert($module, array $param)
    {
        $request = $this->createRequest('upsert', $module, $param);
        return $this->makeRequest($request);
    }

    /**
     * @param $module
     * @param array $param
     * @return ZohoResponse
     */
    public function bulkDelete($module, array $param)
    {
        $request = $this->createRequest('b-delete', $module, $param);
        return $this->makeRequest($request);
    }

    /**
     * @param $module
     * @param string $record_id
     * @return ZohoResponse
     */
    public function delete($module, string $record_id)
    {
        $request = $this->createRequest('delete', $module, [$record_id]);
        return $this->makeRequest($request);
    }

    /**
     * @param $module
     * @param $param
     * @return ZohoResponse
     */
    public function getDeletedRecord($module, $param)
    {
        //TODO: Need To investigate
        dd("STOP");
        $request = $this->createRequest('deleted', $module, $param);
        return $this->makeRequest($request);
    }

    public function convert()
    {
        //TODO:: Need to Implement
        echo "Need to implement";
    }


}
