<?php

namespace RnTorm\LaravelMargn;

class MargnInvoice extends Margn
{
    private $requestUrl = '/invoices';

    public function get($id)
    {
        return $this->httpGet($this->requestUrl.'/'.$id);
    }

    public function getInvoices($data = [])
    {
        $data['documentType'] = 'DOCUMENT_SELL';

        foreach ($data as $key => $value) {
            $this->variables[$key] = $value;
        }

        return $this->httpGet($this->requestUrl);
    }

    public function add($data)
    {
        if (empty($data)) {
            throw new Exception('No data');
        }

        return $this->httpPost($this->requestUrl, $data);
    }

    public function update($id, $data)
    {
        return $this->httpPut($this->requestUrl.'/'.$id, $data);
    }
}
