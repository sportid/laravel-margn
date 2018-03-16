<?php

namespace RnTorm\LaravelMargn;

use Exception;
use Config;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\BadResponseException;

class Margn
{
    private $baseUrl;
    public $variables;

    public function __construct()
    {
        $configuration = Config::get('laravel-margn');
        $this->variables['token'] = $configuration['API_KEY'];
        $this->baseUrl = $configuration['BASE_URL'];
    }

    public function getAccountEntries()
    {
        // TODO
    }

    public function getAccounts()
    {
        return $this->httpGet('/accounts');
    }

    public function getArticles()
    {
        return self::httpGet('/articles');
    }

    public function getCustomers()
    {
        return self::httpGet('/customers');
    }

    public function postCustomers()
    {
        // NO API REQUEST
    }

    public static function getInvoice($id = null)
    {
        if (empty($id)) {
            throw new Exception('id not specified');
        }

        return $this->httpGet('/invoices'.'/'.$id);
    }

    public static function postInvoice($data, $additional_params = ['documentType' => 'DOCUMENT_SELL'])
    {
        if (empty($data)) {
            throw new Exception('Invoice post data empty');
        }

        foreach ($data as $key => $value) {
            self::$variables[$key] = $value;
        }

        return self::httpPost(self::BASE_URL.'/invoices.json', $data);
    }

    public static function getPayments()
    {
        return self::httpGet(self::BASE_URL.'/payments');
    }

    public static function getProjects()
    {
        return self::httpGet(self::BASE_URL.'/projects');
    }

    public static function transactionEntries()
    {
        return self::httpGet(self::BASE_URL.'/transaction_entries');
    }

    protected function httpGet($requestUrl = null)
    {
        $client = new Client();

        $args = [
            'headers' => [
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ],
            'query' => $this->variables,
        ];

        try {
            $response = $client->request('GET', $this->baseUrl.$requestUrl, $args);
        } catch (BadResponseException $e) {
            \Log::error($e->getResponse()->getBody(true));
            throw $e;
        }

        return json_decode($response->getBody(), true);
    }

    protected function httpPost($requestUrl, $requestData)
    {
        $client = new Client();

        $args = [
            'headers' => [
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ],
            'body' => json_encode($requestData),
            'query' => [
                'token' => $this->variables['token'],
            ],
        ];

        try {
            $response = $client->request('POST', $this->baseUrl.$requestUrl.'.json', $args);
        } catch (BadResponseException $e) {
            \Log::error($e->getResponse()->getBody(true));
            throw $e;
        }

        return json_decode($response->getBody(), true);
    }

    protected function httpPut($requestUrl, $requestData)
    {
        $client = new Client();

        $args = [
            'headers' => [
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ],
            'body' => json_encode($requestData),
            'query' => [
                'token' => $this->variables['token'],
            ],
        ];

        try {
            $response = $client->request('PUT', $this->baseUrl.$requestUrl.'.json', $args);
        } catch (BadResponseException $e) {
            \Log::error($e->getResponse()->getBody(true));
            throw $e;
        }

        return json_decode($response->getBody(), true);
    }
}
