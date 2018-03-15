<?php

namespace RnTorm\LaravelMargn;

class MargnInvoice extends Margn
{
    private $requestUrl = '/invoices';

    public function __construct()
    {
        parent::__construct();
    }

    // Requires id
    public function get($id)
    {
        $get_response = $this->httpGet($this->requestUrl.'/'.$id);

        return $get_response;
    }

    // Requires documentType
    public function getInvoices($data = [])
    {
        $data['documentType'] = 'DOCUMENT_SELL';
        if (!array_key_exists('documentType', $data)) {
            throw new HttpException(HttpException::BAD_REQUEST, 'DocumentType not specified');
        }
        if (!empty($data)) {
            foreach ($data as $key => $value) {
                $this->variables[$key] = $value;
            }
        }
        $get_response = $this->httpGet($this->requestUrl);

        return $get_response;
    }

    public function add($data)
    {
        if (empty($data)) {
            throw new Exception('No data');
        }

        $post_response = $this->httpPost($this->requestUrl, $data);

        return $post_response;
    }

    public function update($id, $data)
    {
        $put_response = $this->httpPut($this->requestUrl.'/'.$id, $data);

        return $put_response;
    }
}
